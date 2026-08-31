<?php

namespace App\Http\Controllers\Admin\Website;

use App\Http\Controllers\Controller;
use App\Services\Website\WebSectionService;
use App\Http\Requests\Website\WebSectionRequest;
use Illuminate\Http\Request;

/**
 * Landing-page sections: their order and whether each one shows on the page and
 * in the menu.
 *
 * No create or delete: every row is bound to a Blade partial under
 * resources/views/pages/website2/sections/, so the set of sections is fixed by
 * the code and only their arrangement is editable.
 */
class WebSectionController extends Controller
{
    public $service;

    public function __construct(WebSectionService $service)
    {
        $this->service = $service;
        view()->composer('*', function ($view) {
            $view->with('title', 'Landing Page Sections');
        });
    }

    public function index()
    {
        $data = $this->service->getList(admin_website_id());
        return view('pages.admin.website.section.list', ["data" => $data]);
    }

    /**
     * Save the whole list at once: the order the rows were dragged into, plus
     * both visibility checkboxes.
     */
    public function saveOrder(Request $request)
    {
        $data = $request->validate([
            'order'          => 'present|array',
            'order.*'        => 'integer',
            'show_in_page'   => 'nullable|array',
            'show_in_page.*' => 'integer',
            'show_in_nav'    => 'nullable|array',
            'show_in_nav.*'  => 'integer',
        ]);

        $result = $this->service->saveOrder(
            admin_website_id(),
            $data['order'],
            array_map('strval', $data['show_in_page'] ?? []),
            array_map('strval', $data['show_in_nav'] ?? [])
        );

        return redirect('website/section')->with($result['status'], $result['message']);
    }

    public function edit($id)
    {
        $data = $this->service->getRow($id, admin_website_id());

        if (!$data) {
            return redirect('website/section')->with('failed', 'Section not found');
        }

        return view('pages.admin.website.section.edit', ["data" => $data]);
    }

    public function update(WebSectionRequest $request, $id)
    {
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('website/section')->with('failed', 'Section not found');
        }

        $data = $request->validated();
        $data['updated_by'] = auth()->user()->email ?? null;

        $result = $this->service->update($id, $data);

        return redirect('website/section')->with($result['status'], $result['message']);
    }
}
