<?php

namespace App\Services\Masterdata;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserService
{
    /* =========================
     * GET LIST
     * ========================= */
    public function getList($websiteId = null)
    {
        return User::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->orderBy('id')
            ->get();
    }

    /* =========================
     * GET SINGLE ROW (scoped to website when provided)
     * ========================= */
    public function getRow($id, $websiteId = null)
    {
        return User::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->where('id', $id)
            ->first();
    }

    /* =========================
     * CREATE
     * ========================= */
    public function create(array $params)
    {
        DB::beginTransaction();

        // password is plain here; the User model 'hashed' cast hashes it on save.
        $user = User::create($params);
        if (!$user) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Failed to create user");
        }

        DB::commit();
        return array(
            "status"  => "success",
            "message" => "User created successfully",
            "data"    => $user,
        );
    }

    /* =========================
     * UPDATE
     * ========================= */
    public function update($id, array $params)
    {
        DB::beginTransaction();

        $user = User::find($id);
        if (!$user) {
            DB::rollBack();
            return array("status" => "failed", "message" => "User not found");
        }

        $user->update($params);

        DB::commit();
        return array(
            "status"  => "success",
            "message" => "User updated successfully",
            "data"    => $user,
        );
    }

    /* =========================
     * DELETE
     * ========================= */
    public function delete($id)
    {
        $user = User::find($id);

        if (!$user) {
            return array("status" => "failed", "message" => "User not found");
        }

        if (auth()->id() == $user->id) {
            return array("status" => "failed", "message" => "You cannot delete your own account.");
        }

        $user->delete();
        return array("status" => "success", "message" => "User deleted successfully");
    }
}
