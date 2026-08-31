<?php

namespace App\Services\Website;

use App\Models\About;
use Illuminate\Support\Facades\DB;

class AboutService
{
    /* =========================
     * GET LIST (scoped to website when provided), in display order
     * ========================= */
    public function getList($websiteId = null)
    {
        return About::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->ordered()
            ->get();
    }

    /* =========================
     * GET LIST for the public site: active only, in display order
     * ========================= */
    public function getPublicList($websiteId)
    {
        return About::forWebsite($websiteId)->active()->ordered()->get();
    }

    /* =========================
     * GET SINGLE ROW (scoped to website when provided)
     * ========================= */
    public function getRow($id, $websiteId = null)
    {
        return About::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->where('id', $id)
            ->first();
    }

    /* =========================
     * CREATE
     * ========================= */
    public function create($params)
    {
        DB::beginTransaction();

        // Blank order means "put it last", so the main block keeps its place.
        if (!isset($params['order']) || $params['order'] === null || $params['order'] === '') {
            $params['order'] = (int) About::when(
                $params['website_id'] ?? null,
                fn ($q) => $q->where('website_id', $params['website_id'])
            )->max('order') + 1;
        }

        $sql = About::create($params);
        if (!$sql) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Failed to create about");
        }

        DB::commit();
        return array(
            "status"  => "success",
            "message" => "About created successfully",
            "data"    => $sql,
        );
    }

    /* =========================
     * UPDATE
     * ========================= */
    public function update($id, $params)
    {
        DB::beginTransaction();

        $data = About::find($id);
        if (!$data) {
            DB::rollBack();
            return array("status" => "failed", "message" => "About not found");
        }

        $updated = $data->update($params);
        if (!$updated) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Failed to update about");
        }

        DB::commit();
        return array(
            "status"  => "success",
            "message" => "About updated successfully",
            "data"    => $data,
        );
    }

    /* =========================
     * DELETE
     * ========================= */
    public function delete($id)
    {
        $data = About::find($id);

        if (!$data) {
            return array("status" => "failed", "message" => "About not found");
        }

        $data->delete();
        return array("status" => "success", "message" => "About deleted successfully");
    }
}
