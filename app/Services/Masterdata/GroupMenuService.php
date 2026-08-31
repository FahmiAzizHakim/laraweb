<?php

namespace App\Services\Masterdata;

use App\Models\UserMenuGroup;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GroupMenuService
{
    /* =========================
     * GET LIST
     * ========================= */
    public function getList($websiteId = null)
    {
        return UserMenuGroup::withCount('groupDetails')
            ->when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->orderBy('id')
            ->get();
    }

    /* =========================
     * GET SINGLE ROW (scoped to website when provided)
     * ========================= */
    public function getRow($id, $websiteId = null)
    {
        return UserMenuGroup::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->where('id', $id)
            ->first();
    }

    /* =========================
     * MENU TREE (active menus for the website, nested by parent)
     * ========================= */
    public function getMenuTree($websiteId = null)
    {
        return Menu::whereNull('parent_id')
            ->where('activestatus', 1)
            ->when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->orderBy('id')
            ->with('childrenRecursive')
            ->get();
    }

    /* =========================
     * SELECTED MENU IDS FOR A GROUP
     * ========================= */
    public function getSelectedMenuIds($id): array
    {
        $group = UserMenuGroup::find($id);

        return $group ? $group->menus()->pluck('menus.id')->toArray() : [];
    }

    /* =========================
     * CREATE
     * ========================= */
    public function create($params, array $menuIds = [])
    {
        DB::beginTransaction();

        $group = UserMenuGroup::create($params);
        if (!$group) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Failed to create group");
        }

        $group->menus()->sync($menuIds);

        DB::commit();
        return array(
            "status"  => "success",
            "message" => "Group created successfully",
            "data"    => $group,
        );
    }

    /* =========================
     * UPDATE
     * ========================= */
    public function update($id, $params, array $menuIds = [])
    {
        DB::beginTransaction();

        $group = UserMenuGroup::find($id);
        if (!$group) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Group not found");
        }

        $group->update($params);
        $group->menus()->sync($menuIds);

        DB::commit();
        return array(
            "status"  => "success",
            "message" => "Group updated successfully",
            "data"    => $group,
        );
    }

    /* =========================
     * DELETE
     * ========================= */
    public function delete($id)
    {
        $group = UserMenuGroup::find($id);

        if (!$group) {
            return array("status" => "failed", "message" => "Group not found");
        }

        // Block deletion while users still belong to this group.
        $inUse = User::where('roles_code', $group->code)->count();
        if ($inUse > 0) {
            return array(
                "status"  => "failed",
                "message" => "Cannot delete: {$inUse} user(s) still belong to this group.",
            );
        }

        DB::beginTransaction();
        $group->menus()->detach();   // remove access rows first (FK)
        $group->delete();
        DB::commit();

        return array("status" => "success", "message" => "Group deleted successfully");
    }
}
