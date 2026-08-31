<?php

namespace App\Services\Website;

use App\Models\Website;
use Illuminate\Support\Facades\DB;

class WebsiteService
{
    public function current()
    {
        return Website::current();
    }

    public function getRow($id)
    {
        return Website::find($id);
    }

    public function update($id, array $params)
    {
        DB::beginTransaction();

        $website = Website::find($id);
        if (!$website) {
            DB::rollBack();
            return array("status" => "failed", "message" => "Website not found");
        }

        $website->update($params);

        DB::commit();
        return array(
            "status"  => "success",
            "message" => "Website settings saved successfully",
            "data"    => $website,
        );
    }
}
