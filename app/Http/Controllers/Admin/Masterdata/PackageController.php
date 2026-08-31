<?php

namespace App\Http\Controllers\Admin\Masterdata;

use App\Http\Controllers\Controller;
use App\Services\Masterdata\PackageService;
use App\Services\Masterdata\ProductService;
use App\Services\Masterdata\OtherChargeService;
use App\Services\Masterdata\ServiceService;
use App\Http\Requests\Masterdata\PackageRequest;

class PackageController extends Controller
{
    public $service;
    public $products;
    public $charges;
    public $services;

    public function __construct(
        PackageService $service,
        ProductService $products,
        OtherChargeService $charges,
        ServiceService $services
    ) {
        $this->service  = $service;
        $this->products = $products;
        $this->charges  = $charges;
        $this->services = $services;
        view()->composer('*', function ($view) {
            $view->with('title', 'Packages');
        });
    }

    public function index()
    {
        $data = $this->service->getList(admin_website_id());
        return view('pages.admin.master.package.list', ["data" => $data]);
    }

    public function add()
    {
        return view('pages.admin.master.package.add', $this->pickers());
    }

    public function store(PackageRequest $request)
    {
        $params = $this->params($request);
        $params['website_id'] = admin_website_id();
        $params['created_by'] = auth()->user()->email ?? null;

        $result = $this->service->create($params, $request->input('details', []));

        return redirect('master/package')->with($result['status'], $result['message']);
    }

    public function edit($id)
    {
        $data = $this->service->getRow($id, admin_website_id());

        if (!$data) {
            return redirect('master/package')->with('failed', 'Package not found');
        }

        return view('pages.admin.master.package.edit', $this->pickers() + ["data" => $data]);
    }

    public function update(PackageRequest $request, $id)
    {
        // Ensure the package belongs to the current admin's website.
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('master/package')->with('failed', 'Package not found');
        }

        $params = $this->params($request);
        $params['updated_by'] = auth()->user()->email ?? null;

        $result = $this->service->update($id, $params, $request->input('details', []));

        return redirect('master/package')->with($result['status'], $result['message']);
    }

    public function destroy($id)
    {
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('master/package')->with('failed', 'Package not found');
        }

        $result = $this->service->delete($id);

        return redirect('master/package')->with($result['status'], $result['message']);
    }

    /**
     * Persistable package columns from the request.
     */
    private function params(PackageRequest $request): array
    {
        $data = $request->safe()->only([
            'service_id', 'package_name', 'package_code', 'package_price', 'package_discount',
            'package_description', 'remark', 'is_active',
        ]);

        // safe()->only() drops a null service_id, so set it explicitly --
        // otherwise clearing the select on edit would leave the old service.
        $data['service_id'] = $request->input('service_id') ?: null;

        // Both money columns are NOT NULL (default 0.00).
        $data['package_price']    = $request->input('package_price') ?: 0;
        $data['package_discount'] = $request->input('package_discount') ?: 0;

        return $data;
    }

    /**
     * Options for the detail-line pickers, scoped to the current website.
     */
    private function pickers(): array
    {
        return [
            "products" => $this->products->getList(admin_website_id()),
            "charges"  => $this->charges->getList(admin_website_id()),
            "services" => $this->services->getList(admin_website_id()),
        ];
    }
}
