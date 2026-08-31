<?php

namespace App\Services\Website;

use App\Models\WebSection;
use Illuminate\Support\Facades\DB;

class WebSectionService
{
    /* =========================
     * GET LIST (scoped to website), in display order
     * ========================= */
    public function getList($websiteId = null)
    {
        return WebSection::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->ordered()
            ->get();
    }

    /* =========================
     * GET SINGLE ROW (scoped to website when provided)
     * ========================= */
    public function getRow($id, $websiteId = null)
    {
        return WebSection::when($websiteId, fn ($q) => $q->where('website_id', $websiteId))
            ->where('id', $id)
            ->first();
    }

    /* =========================
     * UPDATE one section
     *
     * section_key is never touched: it names the Blade partial that renders the
     * block, so changing it would point the row at a view that does not exist.
     * ========================= */
    public function update($id, $params)
    {
        DB::beginTransaction();

        $data = WebSection::find($id);
        if (!$data) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Section not found");
        }

        unset($params['section_key'], $params['website_id']);

        $updated = $data->update($params);
        if (!$updated) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Failed to update section");
        }

        DB::commit();
        return array(
            "status"  => "success",
            "message" => "Section updated successfully",
            "data"    => $data,
        );
    }

    /* =========================
     * REORDER from a list of ids in their new order, and set the visibility
     * flags in the same pass -- the list screen saves everything at once.
     * ========================= */
    public function saveOrder($websiteId, array $order, array $onPage, array $inNav)
    {
        DB::beginTransaction();

        $rows = WebSection::forWebsite($websiteId)->get()->keyBy('id');
        $saved = 0;

        foreach (array_values($order) as $position => $id) {
            $row = $rows->get((int) $id);

            if (!$row) {
                continue;
            }

            $row->update([
                'order'        => $position,
                'show_in_page' => in_array((string) $id, $onPage, true),
                'show_in_nav'  => in_array((string) $id, $inNav, true),
            ]);

            $saved++;
        }

        DB::commit();

        return array(
            "status"  => "success",
            "message" => "Section order saved ($saved sections)",
        );
    }
}
