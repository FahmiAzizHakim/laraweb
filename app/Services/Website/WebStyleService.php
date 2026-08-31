<?php

namespace App\Services\Website;

use App\Models\WebStyle;
use Illuminate\Support\Facades\DB;

class WebStyleService
{
    /* =========================
     * GET LIST
     * ========================= */
    public function getList($websiteId = null)
    {
        return WebStyle::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->orderBy('order')
            ->get();
    }

    /* =========================
     * GET SINGLE ROW (scoped to website when provided)
     * ========================= */
    public function getRow($id, $websiteId = null)
    {
        return WebStyle::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->where('id', $id)
            ->first();
    }

    /* =========================
     * CREATE
     * ========================= */
    public function create($params)
    {
        DB::beginTransaction();

        $sql = WebStyle::create($params);
        if (!$sql) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Failed to create style");
        }

        DB::commit();
        return array(
            "status"  => "success",
            "message" => "Style created successfully",
            "data"    => $sql,
        );
    }

    /* =========================
     * UPDATE
     * ========================= */
    public function update($id, $params)
    {
        DB::beginTransaction();

        $data = WebStyle::find($id);
        if (!$data) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Style not found");
        }

        $updated = $data->update($params);
        if (!$updated) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Failed to update style");
        }

        DB::commit();
        return array(
            "status"  => "success",
            "message" => "Style updated successfully",
            "data"    => $data,
        );
    }

    /* =========================
     * DELETE
     * ========================= */
    public function delete($id)
    {
        $data = WebStyle::find($id);

        if (!$data) {
            return array("status" => "failed", "message" => "Style not found");
        }

        $data->delete();
        return array("status" => "success", "message" => "Style deleted successfully");
    }
}
