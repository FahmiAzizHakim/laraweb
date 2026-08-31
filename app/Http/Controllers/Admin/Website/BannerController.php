<?php

namespace App\Http\Controllers\Admin\Website;

use App\Http\Controllers\Controller;
use App\Services\Website\BannerService;
use App\Services\Helper\UploadService;
use App\Http\Requests\Website\BannerRequest;

class BannerController extends Controller
{
    public $service;
    public $upload;

    public function __construct(BannerService $service, UploadService $upload)
    {
        $this->service = $service;
        $this->upload  = $upload;
        view()->composer('*', function ($view) {
            $view->with('title', 'Banner');
        });
    }

    public function index()
    {
        $data = $this->service->getList(admin_website_id());
        return view('pages.admin.website.banner.list', ["data" => $data]);
    }

    public function add()
    {
        return view('pages.admin.website.banner.add');
    }

    public function store(BannerRequest $request)
    {
        $data = $request->validated();

        // Store the uploaded image and keep only its path.
        $uploaded = $this->upload->store($request->file('banner_img'), 'banner');
        $data['banner_img'] = $uploaded ? $uploaded['uploaded'] : null;

        $data['website_id'] = admin_website_id();
        $data['created_by'] = auth()->user()->email ?? null;

        $result = $this->service->create($data);

        return redirect('website/banner')
            ->with($result['status'], $result['message']);
    }

    public function edit($id)
    {
        $data = $this->service->getRow($id, admin_website_id());

        if (!$data) {
            return redirect('website/banner')->with('failed', 'Banner not found');
        }

        return view('pages.admin.website.banner.edit', ["data" => $data]);
    }

    public function update(BannerRequest $request, $id)
    {
        // Ensure the banner belongs to the current user's website.
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('website/banner')->with('failed', 'Banner not found');
        }

        $data = $request->validated();

        if ($request->hasFile('banner_img')) {
            $uploaded = $this->upload->store($request->file('banner_img'), 'banner');
            if ($uploaded) {
                $data['banner_img'] = $uploaded['uploaded'];
            }
        } else {
            // Keep the existing image when no new file is uploaded.
            unset($data['banner_img']);
        }

        $data['updated_by'] = auth()->user()->email ?? null;

        $result = $this->service->update($id, $data);

        return redirect('website/banner')
            ->with($result['status'], $result['message']);
    }

    public function destroy($id)
    {
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('website/banner')->with('failed', 'Banner not found');
        }

        $result = $this->service->delete($id);

        return redirect('website/banner')
            ->with($result['status'], $result['message']);
    }
}
