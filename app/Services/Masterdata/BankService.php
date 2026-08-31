<?php

namespace App\Services\Masterdata;

use App\Models\Bank;
use Illuminate\Support\Facades\DB;

class BankService
{
    /* =========================
     * GET LIST (scoped to website when provided)
     * ========================= */
    public function getList($websiteId = null)
    {
        return Bank::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->orderBy('bank_name')
            ->get();
    }

    /* =========================
     * GET SINGLE ROW (scoped to website when provided)
     * ========================= */
    public function getRow($id, $websiteId = null)
    {
        return Bank::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->where('id', $id)
            ->first();
    }

    /* =========================
     * CREATE
     * ========================= */
    public function create($params)
    {
        DB::beginTransaction();

        $sql = Bank::create($params);
        if (!$sql) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Failed to create bank");
        }

        DB::commit();
        return array(
            "status"  => "success",
            "message" => "Bank created successfully",
            "data"    => $sql,
        );
    }

    /* =========================
     * UPDATE
     * ========================= */
    public function update($id, $params)
    {
        DB::beginTransaction();

        $data = Bank::find($id);
        if (!$data) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Bank not found");
        }

        $updated = $data->update($params);
        if (!$updated) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Failed to update bank");
        }

        DB::commit();
        return array(
            "status"  => "success",
            "message" => "Bank updated successfully",
            "data"    => $data,
        );
    }

    /* =========================
     * DELETE
     * ========================= */
    public function delete($id)
    {
        $data = Bank::find($id);

        if (!$data) {
            return array("status" => "failed", "message" => "Bank not found");
        }

        $data->delete();
        return array("status" => "success", "message" => "Bank deleted successfully");
    }
}
