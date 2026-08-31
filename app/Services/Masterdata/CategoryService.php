<?php

namespace App\Services\Masterdata;

use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    public function getList($websiteId = null)
    {
        return Category::with('parent')
            ->when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->orderBy('id')
            ->get();
    }

    public function getRow($id, $websiteId = null)
    {
        return Category::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->where('id', $id)
            ->first();
    }

    /**
     * Category options for the parent dropdown (same website, optional exclude).
     */
    public function getParents($websiteId = null, $excludeId = null)
    {
        return Category::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->orderBy('category_name')
            ->get();
    }

    public function create($params)
    {
        DB::beginTransaction();

        $sql = Category::create($params);
        if (!$sql) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Failed to create category");
        }

        DB::commit();
        return array("status" => "success", "message" => "Category created successfully", "data" => $sql);
    }

    public function update($id, $params)
    {
        DB::beginTransaction();

        $data = Category::find($id);
        if (!$data) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Category not found");
        }

        $data->update($params);

        DB::commit();
        return array("status" => "success", "message" => "Category updated successfully", "data" => $data);
    }

    public function delete($id)
    {
        $data = Category::find($id);

        if (!$data) {
            return array("status" => "failed", "message" => "Category not found");
        }

        if ($data->children()->count() > 0) {
            return array("status" => "failed", "message" => "Cannot delete: this category has sub-categories.");
        }

        $usedByProducts = DB::table('product_categories')->where('category_id', $id)->count();
        if ($usedByProducts > 0) {
            return array("status" => "failed", "message" => "Cannot delete: {$usedByProducts} product(s) use this category.");
        }

        $data->delete();
        return array("status" => "success", "message" => "Category deleted successfully");
    }
}
