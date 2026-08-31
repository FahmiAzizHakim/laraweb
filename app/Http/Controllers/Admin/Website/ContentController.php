<?php

namespace App\Http\Controllers\Admin\Website;

use App\Http\Controllers\Controller;
use App\Services\Website\ContentService;
use App\Services\Helper\UploadService;
use App\Http\Requests\Website\ContentRequest;

class ContentController extends Controller
{
    public $service;
    public $upload;

    public function __construct(ContentService $service, UploadService $upload)
    {
        $this->service = $service;
        $this->upload  = $upload;
        view()->composer('*', function ($view) {
            $view->with('title', 'Content');
        });
    }

    public function index()
    {
        $data = $this->service->getList(admin_website_id());
        return view('pages.admin.website.content.list', ["data" => $data]);
    }

    public function add()
    {
        return view('pages.admin.website.content.add');
    }

    public function store(ContentRequest $request)
    {
        $data = $request->validated();

        $uploaded = $this->upload->store($request->file('media'), 'content');
        $data['media'] = $uploaded ? $uploaded['uploaded'] : null;

        $data['website_id'] = admin_website_id();
        $data['created_by'] = auth()->user()->email ?? null;

        $result = $this->service->create($data);

        return redirect('website/content')
            ->with($result['status'], $result['message']);
    }

    public function edit($id)
    {
        $data = $this->service->getRow($id, admin_website_id());

        if (!$data) {
            return redirect('website/content')->with('failed', 'Content not found');
        }

        return view('pages.admin.website.content.edit', ["data" => $data]);
    }

    public function update(ContentRequest $request, $id)
    {
        // Ensure the content belongs to the current user's website.
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('website/content')->with('failed', 'Content not found');
        }

        $data = $request->validated();

        if ($request->hasFile('media')) {
            $uploaded = $this->upload->store($request->file('media'), 'content');
            if ($uploaded) {
                $data['media'] = $uploaded['uploaded'];
            }
        } else {
            unset($data['media']);
        }

        $data['updated_by'] = auth()->user()->email ?? null;

        $result = $this->service->update($id, $data);

        return redirect('website/content')
            ->with($result['status'], $result['message']);
    }

    public function destroy($id)
    {
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('website/content')->with('failed', 'Content not found');
        }

        $result = $this->service->delete($id);

        return redirect('website/content')
            ->with($result['status'], $result['message']);
    }
}
