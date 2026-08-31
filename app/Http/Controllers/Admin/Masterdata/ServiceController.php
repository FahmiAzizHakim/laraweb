<?php

namespace App\Http\Controllers\Admin\Masterdata;

use App\Http\Controllers\Controller;
use App\Services\Masterdata\ServiceService;
use App\Services\Helper\UploadService;
use App\Http\Requests\Masterdata\ServiceRequest;

class ServiceController extends Controller
{
    public $service;
    public $upload;

    public function __construct(ServiceService $service, UploadService $upload)
    {
        $this->service = $service;
        $this->upload  = $upload;
        view()->composer('*', function ($view) {
            $view->with('title', 'Services');
        });
    }

    public function index()
    {
        $data = $this->service->getList(admin_website_id());
        return view('pages.admin.master.service.list', ["data" => $data]);
    }

    public function add()
    {
        return view('pages.admin.master.service.add');
    }

    public function store(ServiceRequest $request)
    {
        $data = $request->safe()->only([
            'service_name', 'service_title', 'service_subtitle', 'service_description', 'remark', 'is_active',
        ]);

        if ($request->hasFile('service_image')) {
            $up = $this->upload->store($request->file('service_image'), 'service');
            if ($up) $data['service_image'] = $up['uploaded'];
        }
        if ($request->hasFile('service_icon')) {
            $up = $this->upload->store($request->file('service_icon'), 'service');
            if ($up) $data['service_icon'] = $up['uploaded'];
        }

        $data['website_id'] = admin_website_id();
        $data['created_by'] = auth()->user()->email ?? null;

        $result = $this->service->create($data);

        return redirect('master/service')
            ->with($result['status'], $result['message']);
    }

    public function edit($id)
    {
        $data = $this->service->getRow($id, admin_website_id());

        if (!$data) {
            return redirect('master/service')->with('failed', 'Service not found');
        }

        return view('pages.admin.master.service.edit', ["data" => $data]);
    }

    public function update(ServiceRequest $request, $id)
    {
        // Ensure the service belongs to the current admin's website.
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('master/service')->with('failed', 'Service not found');
        }

        $data = $request->safe()->only([
            'service_name', 'service_title', 'service_subtitle', 'service_description', 'remark', 'is_active',
        ]);

        if ($request->hasFile('service_image')) {
            $up = $this->upload->store($request->file('service_image'), 'service');
            if ($up) $data['service_image'] = $up['uploaded'];
        }
        if ($request->hasFile('service_icon')) {
            $up = $this->upload->store($request->file('service_icon'), 'service');
            if ($up) $data['service_icon'] = $up['uploaded'];
        }

        $data['updated_by'] = auth()->user()->email ?? null;

        $result = $this->service->update($id, $data);

        return redirect('master/service')
            ->with($result['status'], $result['message']);
    }

    public function destroy($id)
    {
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('master/service')->with('failed', 'Service not found');
        }

        $result = $this->service->delete($id);

        return redirect('master/service')
            ->with($result['status'], $result['message']);
    }
}
