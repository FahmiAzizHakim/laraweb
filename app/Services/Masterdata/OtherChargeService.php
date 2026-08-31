<?php

namespace App\Services\Masterdata;

use App\Models\OtherCharge;
use App\Models\PackageDetail;
use Illuminate\Support\Facades\DB;

class OtherChargeService
{
    /* =========================
     * GET LIST (scoped to website when provided)
     * ========================= */
    public function getList($websiteId = null)
    {
        return OtherCharge::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->orderBy('id')
            ->get();
    }

    /* =========================
     * GET SINGLE ROW (scoped to website when provided)
     * ========================= */
    public function getRow($id, $websiteId = null)
    {
        return OtherCharge::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->where('id', $id)
            ->first();
    }

    /* =========================
     * CREATE
     * ========================= */
    public function create($params)
    {
        DB::beginTransaction();

        $sql = OtherCharge::create($params);
        if (!$sql) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Failed to create other charge");
        }

        DB::commit();
        return array(
            "status"  => "success",
            "message" => "Other charge created successfully",
            "data"    => $sql,
        );
    }

    /* =========================
     * UPDATE
     * ========================= */
    public function update($id, $params)
    {
        DB::beginTransaction();

        $data = OtherCharge::find($id);
        if (!$data) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Other charge not found");
        }

        $updated = $data->update($params);
        if (!$updated) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Failed to update other charge");
        }

        DB::commit();
        return array(
            "status"  => "success",
            "message" => "Other charge updated successfully",
            "data"    => $data,
        );
    }

    /* =========================
     * DELETE
     * ========================= */
    public function delete($id)
    {
        $data = OtherCharge::find($id);

        if (!$data) {
            return array("status" => "failed", "message" => "Other charge not found");
        }

        DB::beginTransaction();
        // Drop package lines pointing at this charge so no package keeps a dangling row.
        PackageDetail::where('other_charge_id', $id)->delete();
        $data->delete();
        DB::commit();

        return array("status" => "success", "message" => "Other charge deleted successfully");
    }
}
