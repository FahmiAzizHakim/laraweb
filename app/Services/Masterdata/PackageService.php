<?php

namespace App\Services\Masterdata;

use App\Models\Package;
use App\Models\PackageDetail;
use App\Models\Product;
use App\Models\OtherCharge;
use Illuminate\Support\Facades\DB;

class PackageService
{
    /* =========================
     * GET LIST (scoped to website when provided)
     *
     * $serviceId null means "no service filter", NOT "packages without a
     * service" -- for those, use Package::forService(null) or the '' key of
     * getListByService().
     * ========================= */
    public function getList($websiteId = null, $serviceId = null)
    {
        return Package::with(['service', 'details.product', 'details.otherCharge'])
            ->when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->when(!is_null($serviceId), fn ($q) => $q->forService($serviceId))
            ->orderByDesc('id')
            ->get();
    }

    /* =========================
     * GET LIST grouped by service, for "packages of this service" listings.
     * Packages tied to no service are keyed under ''.
     * ========================= */
    public function getListByService($websiteId = null)
    {
        return $this->getList($websiteId)->groupBy(fn ($p) => (string) $p->service_id);
    }

    /* =========================
     * GET SINGLE ROW (scoped to website when provided)
     * ========================= */
    public function getRow($id, $websiteId = null)
    {
        return Package::with(['service', 'details.product', 'details.otherCharge'])
            ->when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->where('id', $id)
            ->first();
    }

    /* =========================
     * CREATE
     * ========================= */
    public function create($params, array $details = [])
    {
        DB::beginTransaction();

        $package = Package::create($params);
        if (!$package) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Failed to create package");
        }

        $this->syncDetails($package, $details);

        DB::commit();
        return array(
            "status"  => "success",
            "message" => "Package created successfully",
            "data"    => $package,
        );
    }

    /* =========================
     * UPDATE
     * ========================= */
    public function update($id, $params, array $details = [])
    {
        DB::beginTransaction();

        $package = Package::find($id);
        if (!$package) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Package not found");
        }

        $package->update($params);
        $this->syncDetails($package, $details);

        DB::commit();
        return array(
            "status"  => "success",
            "message" => "Package updated successfully",
            "data"    => $package,
        );
    }

    /* =========================
     * DELETE
     * ========================= */
    public function delete($id)
    {
        $package = Package::find($id);

        if (!$package) {
            return array("status" => "failed", "message" => "Package not found");
        }

        DB::beginTransaction();
        $package->details()->delete();
        $package->delete();
        DB::commit();

        return array("status" => "success", "message" => "Package deleted successfully");
    }

    /**
     * Create / update / delete detail lines from submitted rows.
     * Each row: [id?, _remove?, line_type, product_id, other_charge_id, other_benefit, qty].
     *
     * A line holds exactly one of the three kinds; the other two columns are
     * nulled out so switching a line's type never leaves a stale reference.
     */
    private function syncDetails(Package $package, array $details): void
    {
        foreach ($details as $row) {
            $id     = $row['id'] ?? null;
            $remove = !empty($row['_remove']);
            $data   = $this->detailData($package->website_id, $row);

            if ($id) {
                $detail = PackageDetail::where('package_id', $package->id)->where('id', $id)->first();
                if (!$detail) {
                    continue;
                }
                if ($remove || $data === null) {
                    $detail->delete();
                    continue;
                }
                $detail->update($data);
            } else {
                // Skip blank / removed new rows.
                if ($remove || $data === null) {
                    continue;
                }
                $data['package_id'] = $package->id;
                PackageDetail::create($data);
            }
        }
    }

    /**
     * Normalise one submitted line into persistable columns, or null when the
     * line is empty / references something outside this website.
     */
    private function detailData($websiteId, array $row): ?array
    {
        $type = $row['line_type'] ?? '';
        $qty  = max(1, (int) ($row['qty'] ?? 1));

        if ($type === 'product') {
            $productId = $row['product_id'] ?? null;
            if (!$productId || !$this->productBelongsToWebsite($productId, $websiteId)) {
                return null;
            }

            return ['product_id' => $productId, 'other_charge_id' => null, 'other_benefit' => null, 'qty' => $qty];
        }

        if ($type === 'charge') {
            $chargeId = $row['other_charge_id'] ?? null;
            if (!$chargeId || !OtherCharge::where('id', $chargeId)->where('website_id', $websiteId)->exists()) {
                return null;
            }

            return ['product_id' => null, 'other_charge_id' => $chargeId, 'other_benefit' => null, 'qty' => $qty];
        }

        if ($type === 'benefit') {
            $benefit = trim($row['other_benefit'] ?? '');
            if ($benefit === '') {
                return null;
            }

            return ['product_id' => null, 'other_charge_id' => null, 'other_benefit' => $benefit, 'qty' => $qty];
        }

        return null;
    }

    /**
     * Products carry no website_id of their own; ownership runs through the service.
     */
    private function productBelongsToWebsite($productId, $websiteId): bool
    {
        return Product::where('id', $productId)
            ->whereHas('service', fn ($s) => $s->where('website_id', $websiteId))
            ->exists();
    }
}
