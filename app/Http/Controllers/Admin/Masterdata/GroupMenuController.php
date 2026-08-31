<?php

namespace App\Http\Controllers\Admin\Masterdata;

use App\Http\Controllers\Controller;
use App\Services\Masterdata\GroupMenuService;
use App\Http\Requests\Masterdata\GroupMenuRequest;

class GroupMenuController extends Controller
{
    public $service;

    public function __construct(GroupMenuService $service)
    {
        $this->service = $service;
        view()->composer('*', function ($view) {
            $view->with('title', 'User Access Group');
        });
    }

    public function index()
    {
        $data = $this->service->getList(admin_website_id());
        return view('pages.admin.master.groupmenu.list', ["data" => $data]);
    }

    public function add()
    {
        return view('pages.admin.master.groupmenu.add', [
            "menuTree" => $this->service->getMenuTree(admin_website_id()),
            "selected" => [],
        ]);
    }

    public function store(GroupMenuRequest $request)
    {
        $data = $request->safe()->only(['code', 'name', 'desc', 'activestatus']);
        $data['website_id'] = admin_website_id();
        $data['created_by'] = auth()->user()->email ?? '_SYS_';
        $data['updated_by'] = auth()->user()->email ?? '_SYS_';

        $result = $this->service->create($data, $request->input('menus', []));

        return redirect('master/groupmenu')
            ->with($result['status'], $result['message']);
    }

    public function edit($id)
    {
        $data = $this->service->getRow($id, admin_website_id());

        if (!$data) {
            return redirect('master/groupmenu')->with('failed', 'Group not found');
        }

        return view('pages.admin.master.groupmenu.edit', [
            "data"     => $data,
            "menuTree" => $this->service->getMenuTree(admin_website_id()),
            "selected" => $this->service->getSelectedMenuIds($id),
        ]);
    }

    public function update(GroupMenuRequest $request, $id)
    {
        // Ensure the group belongs to the current admin's website.
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('master/groupmenu')->with('failed', 'Group not found');
        }

        $data = $request->safe()->only(['name', 'desc', 'activestatus']);
        $data['updated_by'] = auth()->user()->email ?? '_SYS_';

        $result = $this->service->update($id, $data, $request->input('menus', []));

        return redirect('master/groupmenu')
            ->with($result['status'], $result['message']);
    }

    public function destroy($id)
    {
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('master/groupmenu')->with('failed', 'Group not found');
        }

        $result = $this->service->delete($id);

        return redirect('master/groupmenu')
            ->with($result['status'], $result['message']);
    }
}
