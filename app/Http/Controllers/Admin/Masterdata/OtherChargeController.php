<?php

namespace App\Http\Controllers\Admin\Masterdata;

use App\Http\Controllers\Controller;
use App\Services\Masterdata\OtherChargeService;
use App\Http\Requests\Masterdata\OtherChargeRequest;

class OtherChargeController extends Controller
{
    public $service;

    public function __construct(OtherChargeService $service)
    {
        $this->service = $service;
        view()->composer('*', function ($view) {
            $view->with('title', 'Other Charges');
        });
    }

    public function index()
    {
        $data = $this->service->getList(admin_website_id());
        return view('pages.admin.master.othercharge.list', ["data" => $data]);
    }

    public function add()
    {
        return view('pages.admin.master.othercharge.add');
    }

    public function store(OtherChargeRequest $request)
    {
        $data = $request->safe()->only([
            'code', 'name', 'description', 'remark', 'price', 'is_active',
        ]);

        $data['website_id'] = admin_website_id();
        $data['created_by'] = auth()->user()->email ?? null;

        $result = $this->service->create($data);

        return redirect('master/othercharge')
            ->with($result['status'], $result['message']);
    }

    public function edit($id)
    {
        $data = $this->service->getRow($id, admin_website_id());

        if (!$data) {
            return redirect('master/othercharge')->with('failed', 'Other charge not found');
        }

        return view('pages.admin.master.othercharge.edit', ["data" => $data]);
    }

    public function update(OtherChargeRequest $request, $id)
    {
        // Ensure the charge belongs to the current admin's website.
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('master/othercharge')->with('failed', 'Other charge not found');
        }

        $data = $request->safe()->only([
            'code', 'name', 'description', 'remark', 'price', 'is_active',
        ]);

        $data['updated_by'] = auth()->user()->email ?? null;

        $result = $this->service->update($id, $data);

        return redirect('master/othercharge')
            ->with($result['status'], $result['message']);
    }

    public function destroy($id)
    {
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('master/othercharge')->with('failed', 'Other charge not found');
        }

        $result = $this->service->delete($id);

        return redirect('master/othercharge')
            ->with($result['status'], $result['message']);
    }
}
