<?php

namespace App\Services\Masterdata;

use App\Models\Service;
use Illuminate\Support\Facades\DB;

class ServiceService
{
    /* =========================
     * GET LIST (scoped to website when provided)
     * ========================= */
    public function getList($websiteId = null)
    {
        return Service::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->orderBy('id')
            ->get();
    }

    /* =========================
     * GET SINGLE ROW (scoped to website when provided)
     * ========================= */
    public function getRow($id, $websiteId = null)
    {
        return Service::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->where('id', $id)
            ->first();
    }

    /* =========================
     * CREATE
     * ========================= */
    public function create($params)
    {
        DB::beginTransaction();

        $sql = Service::create($params);
        if (!$sql) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Failed to create service");
        }

        DB::commit();
        return array(
            "status"  => "success",
            "message" => "Service created successfully",
            "data"    => $sql,
        );
    }

    /* =========================
     * UPDATE
     * ========================= */
    public function update($id, $params)
    {
        DB::beginTransaction();

        $data = Service::find($id);
        if (!$data) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Service not found");
        }

        $updated = $data->update($params);
        if (!$updated) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Failed to update service");
        }

        DB::commit();
        return array(
            "status"  => "success",
            "message" => "Service updated successfully",
            "data"    => $data,
        );
    }

    /* =========================
     * DELETE
     * ========================= */
    public function delete($id)
    {
        $data = Service::find($id);

        if (!$data) {
            return array("status" => "failed", "message" => "Service not found");
        }

        $data->delete();
        return array("status" => "success", "message" => "Service deleted successfully");
    }
}
