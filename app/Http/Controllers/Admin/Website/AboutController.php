<?php

namespace App\Http\Controllers\Admin\Website;

use App\Http\Controllers\Controller;
use App\Services\Website\AboutService;
use App\Services\Helper\UploadService;
use App\Http\Requests\Website\AboutRequest;

class AboutController extends Controller
{
    public $service;
    public $upload;

    public function __construct(AboutService $service, UploadService $upload)
    {
        $this->service = $service;
        $this->upload  = $upload;
        view()->composer('*', function ($view) {
            $view->with('title', 'About');
        });
    }

    public function index()
    {
        $data = $this->service->getList(admin_website_id());
        return view('pages.admin.website.about.list', ["data" => $data]);
    }

    public function add()
    {
        return view('pages.admin.website.about.add');
    }

    public function store(AboutRequest $request)
    {
        $data = $request->validated();

        // Store the uploaded image and keep only its path.
        if ($request->hasFile('about_image')) {
            $uploaded = $this->upload->store($request->file('about_image'), 'about');
            $data['about_image'] = $uploaded ? $uploaded['uploaded'] : null;
        } else {
            unset($data['about_image']);
        }

        $data['website_id'] = admin_website_id();
        $data['created_by'] = auth()->user()->email ?? null;

        $result = $this->service->create($data);

        return redirect('website/about')->with($result['status'], $result['message']);
    }

    public function edit($id)
    {
        $data = $this->service->getRow($id, admin_website_id());

        if (!$data) {
            return redirect('website/about')->with('failed', 'About not found');
        }

        return view('pages.admin.website.about.edit', ["data" => $data]);
    }

    public function update(AboutRequest $request, $id)
    {
        // Ensure the row belongs to the current admin's website.
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('website/about')->with('failed', 'About not found');
        }

        $data = $request->validated();

        if ($request->hasFile('about_image')) {
            $uploaded = $this->upload->store($request->file('about_image'), 'about');
            if ($uploaded) {
                $data['about_image'] = $uploaded['uploaded'];
            }
        } else {
            // Keep the existing image when no new file is uploaded.
            unset($data['about_image']);
        }

        $data['updated_by'] = auth()->user()->email ?? null;

        $result = $this->service->update($id, $data);

        return redirect('website/about')->with($result['status'], $result['message']);
    }

    public function destroy($id)
    {
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('website/about')->with('failed', 'About not found');
        }

        $result = $this->service->delete($id);

        return redirect('website/about')->with($result['status'], $result['message']);
    }
}
