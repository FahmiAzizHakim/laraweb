<?php

namespace App\Services\Website;

use App\Models\Content;
use Illuminate\Support\Facades\DB;

class ContentService
{
    /* =========================
     * GET LIST
     * ========================= */
    public function getList($websiteId = null)
    {
        return Content::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->orderByDesc('created_at')
            ->get();
    }

    /* =========================
     * GET SINGLE ROW (scoped to website when provided)
     * ========================= */
    public function getRow($id, $websiteId = null)
    {
        return Content::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->where('id', $id)
            ->first();
    }

    /* =========================
     * CREATE
     * ========================= */
    public function create($params)
    {
        DB::beginTransaction();

        $sql = Content::create($params);
        if (!$sql) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Failed to create content");
        }

        DB::commit();
        return array(
            "status"  => "success",
            "message" => "Content created successfully",
            "data"    => $sql,
        );
    }

    /* =========================
     * UPDATE
     * ========================= */
    public function update($id, $params)
    {
        DB::beginTransaction();

        $data = Content::find($id);
        if (!$data) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Content not found");
        }

        $updated = $data->update($params);
        if (!$updated) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Failed to update content");
        }

        DB::commit();
        return array(
            "status"  => "success",
            "message" => "Content updated successfully",
            "data"    => $data,
        );
    }

    /* =========================
     * DELETE
     * ========================= */
    public function delete($id)
    {
        $data = Content::find($id);

        if (!$data) {
            return array("status" => "failed", "message" => "Content not found");
        }

        $data->delete();
        return array("status" => "success", "message" => "Content deleted successfully");
    }
}
