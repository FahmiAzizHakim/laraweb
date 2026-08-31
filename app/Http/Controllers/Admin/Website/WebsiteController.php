<?php

namespace App\Http\Controllers\Admin\Website;

use App\Http\Controllers\Controller;
use App\Services\Website\WebsiteService;
use App\Services\Helper\UploadService;
use App\Http\Requests\Website\WebsiteRequest;

class WebsiteController extends Controller
{
    public $service;
    public $upload;

    public function __construct(WebsiteService $service, UploadService $upload)
    {
        $this->service = $service;
        $this->upload  = $upload;
        view()->composer('*', function ($view) {
            $view->with('title', 'Website Setting');
        });
    }

    public function edit()
    {
        $data = $this->service->getRow(admin_website_id());

        if (!$data) {
            return redirect('/dashboard')->with('failed', 'No website configured.');
        }

        return view('pages.admin.website.setting.edit', ["data" => $data]);
    }

    public function update(WebsiteRequest $request)
    {
        // Always update the user's own website, regardless of any submitted id.
        $id   = admin_website_id();
        $data = $request->safe()->only([
            'web_name', 'company_name', 'phone_number', 'whatsapp_no', 'email',
            'facebook_link', 'twitter_link', 'instagram_link', 'linkedin_link',
            'address', 'location', 'is_active',
        ]);

        // Logo uploads (keep existing when not re-uploaded).
        if ($request->hasFile('logo')) {
            $up = $this->upload->store($request->file('logo'), 'website');
            if ($up) $data['logo'] = $up['uploaded'];
        }
        if ($request->hasFile('logo_white')) {
            $up = $this->upload->store($request->file('logo_white'), 'website');
            if ($up) $data['logo_white'] = $up['uploaded'];
        }

        $data['updated_by'] = auth()->user()->email ?? null;

        $result = $this->service->update($id, $data);

        return redirect('website/setting')
            ->with($result['status'], $result['message']);
    }
}
