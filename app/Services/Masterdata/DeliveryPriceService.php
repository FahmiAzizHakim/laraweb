<?php

namespace App\Services\Masterdata;

use App\Models\DeliveryPrice;
use Illuminate\Support\Facades\DB;

class DeliveryPriceService
{
    /* =========================================================
     * CRUD
     * ========================================================= */

    /**
     * List rows for a website, joined to region names for display.
     */
    public function getList($websiteId = null)
    {
        return DeliveryPrice::query()
            ->from('delivery_prices as dp')
            ->when($websiteId, fn ($q) => $q->where('dp.website_id', $websiteId))
            ->leftJoin('glb_cities as c', 'c.city_code', '=', 'dp.city_code')
            ->leftJoin('glb_districts as d', 'd.district_code', '=', 'dp.district_code')
            ->leftJoin('glb_subdistricts as s', 's.subdistrict_code', '=', 'dp.subdistrict_code')
            ->select(
                'dp.*',
                'c.city_name',
                'd.district_name',
                's.subdistrict_name'
            )
            ->orderBy('c.city_name')
            ->orderBy('d.district_name')
            ->orderBy('s.subdistrict_name')
            ->get();
    }

    public function getRow($id, $websiteId = null)
    {
        return DeliveryPrice::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->where('id', $id)
            ->first();
    }

    public function create(array $params)
    {
        $params = $this->normalize($params);

        if ($this->duplicateExists($params['website_id'], $params['city_code'], $params['district_code'], $params['subdistrict_code'])) {
            return ['status' => 'failed', 'message' => 'A delivery price for this area already exists.'];
        }

        DB::beginTransaction();
        $row = DeliveryPrice::create($params);
        if (!$row) {
            DB::rollBack();
            return ['status' => 'failed', 'message' => 'Failed to create delivery price'];
        }
        DB::commit();

        return ['status' => 'success', 'message' => 'Delivery price created successfully', 'data' => $row];
    }

    public function update($id, array $params)
    {
        $row = DeliveryPrice::find($id);
        if (!$row) {
            return ['status' => 'failed', 'message' => 'Delivery price not found'];
        }

        $params = $this->normalize($params);

        if ($this->duplicateExists($params['website_id'] ?? $row->website_id, $params['city_code'], $params['district_code'], $params['subdistrict_code'], $id)) {
            return ['status' => 'failed', 'message' => 'A delivery price for this area already exists.'];
        }

        DB::beginTransaction();
        $updated = $row->update($params);
        if (!$updated) {
            DB::rollBack();
            return ['status' => 'failed', 'message' => 'Failed to update delivery price'];
        }
        DB::commit();

        return ['status' => 'success', 'message' => 'Delivery price updated successfully', 'data' => $row];
    }

    public function delete($id)
    {
        $row = DeliveryPrice::find($id);
        if (!$row) {
            return ['status' => 'failed', 'message' => 'Delivery price not found'];
        }
        $row->delete();
        return ['status' => 'success', 'message' => 'Delivery price deleted successfully'];
    }

    /**
     * Normalize blank district/subdistrict to null so the "level" is unambiguous.
     * A subdistrict without a district makes no sense, so clear the deeper level
     * whenever a shallower one is empty.
     */
    private function normalize(array $params): array
    {
        $params['district_code']    = $params['district_code']    ?? null;
        $params['subdistrict_code'] = $params['subdistrict_code'] ?? null;

        if ($params['district_code'] === '') $params['district_code'] = null;
        if ($params['subdistrict_code'] === '') $params['subdistrict_code'] = null;

        if (empty($params['district_code'])) {
            $params['subdistrict_code'] = null; // no district => cannot have subdistrict
        }

        return $params;
    }

    /**
     * MySQL treats NULLs as distinct in a UNIQUE index, so enforce uniqueness
     * of the (website, city, district, subdistrict) tuple here too.
     */
    private function duplicateExists($websiteId, $cityCode, $districtCode, $subdistrictCode, $ignoreId = null): bool
    {
        return DeliveryPrice::where('website_id', $websiteId)
            ->where('city_code', $cityCode)
            ->where(fn ($q) => is_null($districtCode) ? $q->whereNull('district_code') : $q->where('district_code', $districtCode))
            ->where(fn ($q) => is_null($subdistrictCode) ? $q->whereNull('subdistrict_code') : $q->where('subdistrict_code', $subdistrictCode))
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists();
    }

    /* =========================================================
     * REGION LOOKUPS (for cascading selects)
     * ========================================================= */

    public function provinces()
    {
        return DB::table('glb_provinces')
            ->where('is_active', true)
            ->orderBy('province_name')
            ->get(['province_code', 'province_name']);
    }

    public function citiesByProvince(string $provinceCode)
    {
        return DB::table('glb_cities')
            ->where('province_code', $provinceCode)
            ->where('is_active', true)
            ->orderBy('city_name')
            ->get(['city_code', 'city_name', 'city_type']);
    }

    /**
     * Look up the province_code that a given city belongs to (for edit hydration).
     */
    public function provinceOfCity(?string $cityCode): ?string
    {
        if (!$cityCode) return null;

        return DB::table('glb_cities')->where('city_code', $cityCode)->value('province_code');
    }

    public function districtsByCity(string $cityCode)
    {
        return DB::table('glb_districts')
            ->where('city_code', $cityCode)
            ->where('is_active', true)
            ->orderBy('district_name')
            ->get(['district_code', 'district_name']);
    }

    public function subdistrictsByDistrict(string $districtCode)
    {
        return DB::table('glb_subdistricts')
            ->where('district_code', $districtCode)
            ->where('is_active', true)
            ->orderBy('subdistrict_name')
            ->get(['subdistrict_code', 'subdistrict_name']);
    }

    /* =========================================================
     * FALLBACK RESOLVER: subdistrict -> district -> city
     * ========================================================= */

    /**
     * Resolve the fare directly from a known code chain (city required,
     * district/subdistrict optional). Picks the most specific price that
     * exists, falling back subdistrict -> district -> city. Unlike
     * resolveBySubdistrict(), this does not re-read the glb_* hierarchy, so it
     * works even when the subdistrict table is not seeded.
     */
    public function resolveFare(int $websiteId, ?string $cityCode, ?string $districtCode = null, ?string $subdistrictCode = null): ?DeliveryPrice
    {
        if (empty($cityCode)) return null;

        $districtCode    = $districtCode    ?: null;
        $subdistrictCode = $subdistrictCode ?: null;

        if ($subdistrictCode && $districtCode) {
            $row = DeliveryPrice::forWebsite($websiteId)->active()
                ->where('city_code', $cityCode)
                ->where('district_code', $districtCode)
                ->where('subdistrict_code', $subdistrictCode)
                ->first();
            if ($row) return $row;
        }

        if ($districtCode) {
            $row = DeliveryPrice::forWebsite($websiteId)->active()
                ->where('city_code', $cityCode)
                ->where('district_code', $districtCode)
                ->whereNull('subdistrict_code')
                ->first();
            if ($row) return $row;
        }

        return $this->resolveByCity($websiteId, $cityCode);
    }

    /**
     * Effective price for a subdistrict, falling back to district then city.
     * Returns the winning DeliveryPrice model, or null.
     */
    public function resolveBySubdistrict(int $websiteId, string $subdistrictCode): ?DeliveryPrice
    {
        $region = DB::table('glb_subdistricts')
            ->select('subdistrict_code', 'district_code', 'city_code')
            ->where('subdistrict_code', $subdistrictCode)
            ->first();

        if (!$region) return null;

        // 1) exact subdistrict price
        $row = DeliveryPrice::forWebsite($websiteId)->active()
            ->where('city_code', $region->city_code)
            ->where('district_code', $region->district_code)
            ->where('subdistrict_code', $region->subdistrict_code)
            ->first();
        if ($row) return $row;

        // 2) district price
        if ($region->district_code) {
            $row = DeliveryPrice::forWebsite($websiteId)->active()
                ->where('city_code', $region->city_code)
                ->where('district_code', $region->district_code)
                ->whereNull('subdistrict_code')
                ->first();
            if ($row) return $row;
        }

        // 3) city price
        return $this->resolveByCity($websiteId, $region->city_code);
    }

    /**
     * Effective price for a district, falling back to city.
     */
    public function resolveByDistrict(int $websiteId, string $districtCode): ?DeliveryPrice
    {
        $region = DB::table('glb_districts')
            ->select('district_code', 'city_code')
            ->where('district_code', $districtCode)
            ->first();

        if (!$region) return null;

        $row = DeliveryPrice::forWebsite($websiteId)->active()
            ->where('city_code', $region->city_code)
            ->where('district_code', $region->district_code)
            ->whereNull('subdistrict_code')
            ->first();
        if ($row) return $row;

        return $this->resolveByCity($websiteId, $region->city_code);
    }

    /**
     * Price for a city (no fallback below city).
     */
    public function resolveByCity(int $websiteId, string $cityCode): ?DeliveryPrice
    {
        return DeliveryPrice::forWebsite($websiteId)->active()
            ->where('city_code', $cityCode)
            ->whereNull('district_code')
            ->whereNull('subdistrict_code')
            ->first();
    }
}
