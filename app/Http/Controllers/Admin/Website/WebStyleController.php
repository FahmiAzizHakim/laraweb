<?php

namespace App\Http\Controllers\Admin\Website;

use App\Http\Controllers\Controller;
use App\Services\Website\WebStyleService;
use App\Http\Requests\Website\WebStyleRequest;

class WebStyleController extends Controller
{
    public $service;

    public function __construct(WebStyleService $service)
    {
        $this->service = $service;
        view()->composer('*', function ($view) {
            $view->with('title', 'Main Style Setting');
        });
    }

    public function index()
    {
        $data = $this->service->getList(admin_website_id());
        return view('pages.admin.website.style.list', ["data" => $data]);
    }

    public function edit($id)
    {
        $data = $this->service->getRow($id, admin_website_id());

        if (!$data) {
            return redirect('website/style')->with('failed', 'Style not found');
        }

        return view('pages.admin.website.style.edit', ["data" => $data]);
    }

    public function update(WebStyleRequest $request, $id)
    {
        // Ensure the style belongs to the current user's website.
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('website/style')->with('failed', 'Style not found');
        }

        $data = $request->validated();
        $data['updated_by'] = auth()->user()->email ?? null;

        $result = $this->service->update($id, $data);

        return redirect('website/style')
            ->with($result['status'], $result['message']);
    }
}
