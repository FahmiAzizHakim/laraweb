<?php

namespace App\Services\Website;

use App\Models\Banner;
use Illuminate\Support\Facades\DB;

class BannerService
{
    /* =========================
     * GET LIST
     * ========================= */
    public function getList($websiteId = null)
    {
        return Banner::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->orderBy('id')
            ->get();
    }

    /* =========================
     * GET SINGLE ROW (scoped to website when provided)
     * ========================= */
    public function getRow($id, $websiteId = null)
    {
        return Banner::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->where('id', $id)
            ->first();
    }

    /* =========================
     * CREATE
     * ========================= */
    public function create($params)
    {
        DB::beginTransaction();

        $sql = Banner::create($params);
        if (!$sql) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Failed to create banner");
        }

        DB::commit();
        return array(
            "status"  => "success",
            "message" => "Banner created successfully",
            "data"    => $sql,
        );
    }

    /* =========================
     * UPDATE
     * ========================= */
    public function update($id, $params)
    {
        DB::beginTransaction();

        $data = Banner::find($id);
        if (!$data) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Banner not found");
        }

        $updated = $data->update($params);
        if (!$updated) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Failed to update banner");
        }

        DB::commit();
        return array(
            "status"  => "success",
            "message" => "Banner updated successfully",
            "data"    => $data,
        );
    }

    /* =========================
     * DELETE
     * ========================= */
    public function delete($id)
    {
        $data = Banner::find($id);

        if (!$data) {
            return array("status" => "failed", "message" => "Banner not found");
        }

        $data->delete();
        return array("status" => "success", "message" => "Banner deleted successfully");
    }
}
