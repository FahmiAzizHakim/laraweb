<?php

namespace App\Http\Controllers\Admin\Masterdata;

use App\Http\Controllers\Controller;
use App\Services\Masterdata\UserService;
use App\Http\Requests\Masterdata\UserRequest;
use App\Models\UserMenuGroup;

class UserController extends Controller
{
    public $service;

    public function __construct(UserService $services)
    {
        $this->service = $services;
        view()->composer('*', function ($view) {
            $view->with('title', 'Users');
        });
    }

    public function index()
    {
        $data = $this->service->getList(admin_website_id());
        return view('pages.admin.master.user.list', ["data" => $data]);
    }

    public function add()
    {
        return view('pages.admin.master.user.add', [
            "groups" => $this->groups(),
        ]);
    }

    public function store(UserRequest $request)
    {
        $data = $request->safe()->only(['name', 'email', 'password', 'roles_code', 'is_active']);
        $data['website_id'] = admin_website_id();

        $result = $this->service->create($data);

        return redirect('master/user')
            ->with($result['status'], $result['message']);
    }

    public function edit($id)
    {
        $data = $this->service->getRow($id, admin_website_id());

        if (!$data) {
            return redirect('master/user')->with('failed', 'User not found');
        }

        return view('pages.admin.master.user.edit', [
            "data"   => $data,
            "groups" => $this->groups(),
        ]);
    }

    public function update(UserRequest $request, $id)
    {
        // Ensure the user belongs to the current admin's website.
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('master/user')->with('failed', 'User not found');
        }

        $data = $request->safe()->only(['name', 'email', 'password', 'roles_code', 'is_active']);

        // Keep the current password when the field is left blank.
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $result = $this->service->update($id, $data);

        return redirect('master/user')
            ->with($result['status'], $result['message']);
    }

    public function destroy($id)
    {
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('master/user')->with('failed', 'User not found');
        }

        $result = $this->service->delete($id);

        return redirect('master/user')
            ->with($result['status'], $result['message']);
    }

    /**
     * Active access groups (of the current admin's website) for the roles_code dropdown.
     */
    private function groups()
    {
        return UserMenuGroup::where('activestatus', 1)
            ->when(admin_website_id(), fn ($q) => $q->where('website_id', admin_website_id()))
            ->orderBy('name')
            ->get();
    }
}
