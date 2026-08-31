<?php

namespace App\Http\Controllers\Admin\Masterdata;

use App\Http\Controllers\Controller;
use App\Services\Masterdata\DeliveryPriceService;
use App\Http\Requests\Masterdata\DeliveryPriceRequest;

class DeliveryPriceController extends Controller
{
    public $service;

    public function __construct(DeliveryPriceService $service)
    {
        $this->service = $service;
        view()->composer('*', function ($view) {
            $view->with('title', 'Delivery Prices');
        });
    }

    public function index()
    {
        $data = $this->service->getList(admin_website_id());
        return view('pages.admin.master.deliveryprice.list', ['data' => $data]);
    }

    public function add()
    {
        return view('pages.admin.master.deliveryprice.add', [
            'provinces' => $this->service->provinces(),
        ]);
    }

    public function store(DeliveryPriceRequest $request)
    {
        $params = $this->params($request);
        $params['website_id'] = admin_website_id();
        $params['created_by'] = auth()->user()->email ?? null;

        $result = $this->service->create($params);

        if ($result['status'] === 'failed') {
            return back()->withInput()->with('failed', $result['message']);
        }

        return redirect('master/deliveryprice')->with($result['status'], $result['message']);
    }

    public function edit($id)
    {
        $data = $this->service->getRow($id, admin_website_id());

        if (!$data) {
            return redirect('master/deliveryprice')->with('failed', 'Delivery price not found');
        }

        $provinceCode = $this->service->provinceOfCity($data->city_code);

        return view('pages.admin.master.deliveryprice.edit', [
            'data'         => $data,
            'provinces'    => $this->service->provinces(),
            'provinceCode' => $provinceCode,
            'cities'       => $provinceCode ? $this->service->citiesByProvince($provinceCode) : collect(),
            'districts'    => $data->city_code ? $this->service->districtsByCity($data->city_code) : collect(),
            'subdistricts' => $data->district_code ? $this->service->subdistrictsByDistrict($data->district_code) : collect(),
        ]);
    }

    public function update(DeliveryPriceRequest $request, $id)
    {
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('master/deliveryprice')->with('failed', 'Delivery price not found');
        }

        $params = $this->params($request);
        $params['website_id'] = admin_website_id();
        $params['updated_by'] = auth()->user()->email ?? null;

        $result = $this->service->update($id, $params);

        if ($result['status'] === 'failed') {
            return back()->withInput()->with('failed', $result['message']);
        }

        return redirect('master/deliveryprice')->with($result['status'], $result['message']);
    }

    public function destroy($id)
    {
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('master/deliveryprice')->with('failed', 'Delivery price not found');
        }

        $result = $this->service->delete($id);

        return redirect('master/deliveryprice')->with($result['status'], $result['message']);
    }

    /* ---- AJAX endpoints for cascading selects ---- */

    public function cities($provinceCode)
    {
        return response()->json($this->service->citiesByProvince($provinceCode));
    }

    public function districts($cityCode)
    {
        return response()->json($this->service->districtsByCity($cityCode));
    }

    public function subdistricts($districtCode)
    {
        return response()->json($this->service->subdistrictsByDistrict($districtCode));
    }

    private function params(DeliveryPriceRequest $request): array
    {
        return $request->safe()->only([
            'city_code', 'district_code', 'subdistrict_code', 'price', 'is_active',
        ]);
    }
}
