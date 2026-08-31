<?php

namespace App\Services;

use App\Models\UserMenuGroupDetail;
use App\Models\Menu;
use App\Models\UserMenuGroup;

class MenuService
{
    public function getMenus($role_id, $parent_id = null)
    {
        $data = Menu::where('parent_id', $parent_id)
						->where('activestatus', 1)
            ->whereIn('id', function ($query) use ($role_id) {
                $query->select('menu_id')
                    ->from('users_menugroupdetail')
                    ->where('usergroup_id', $role_id);
            })
        ->get();
				$ret = [];
				$i = 0;
				foreach($data as $row => $val)
				{
					$ret[$i]["id"] = $val->id;
					$ret[$i]["parent_id"] = $val->parent_id;
					$ret[$i]["name_in"] = $val->name_in;
					$ret[$i]["name_en"] = $val->name_en;
					$ret[$i]["menu_url"] = $val->menu_url;
					$ret[$i]["menu_icon"] = $val->menu_icon;
					$ret[$i]["menu_type"] = $val->menu_type;
					$ret[$i]["menu_desc"] = $val->menu_desc;
					if($ret[$i]["menu_type"] == "FOLDER")
					{
						$ret[$i]["children"] = $this->getMenus($role_id, $val->id);
					}
					$i++;
				}
				// dd($ret);
				return $ret;
    }
}