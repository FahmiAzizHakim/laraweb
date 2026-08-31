<?php

namespace App\Http\Controllers\Admin\Masterdata;

use App\Http\Controllers\Controller;
use App\Services\Masterdata\CategoryService;
use App\Http\Requests\Masterdata\CategoryRequest;

class CategoryController extends Controller
{
    public $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
        view()->composer('*', function ($view) {
            $view->with('title', 'Categories');
        });
    }

    public function index()
    {
        $data = $this->service->getList(admin_website_id());
        return view('pages.admin.master.categories.list', ["data" => $data]);
    }

    public function add()
    {
        return view('pages.admin.master.categories.add', [
            "parents" => $this->service->getParents(admin_website_id()),
        ]);
    }

    public function store(CategoryRequest $request)
    {
        $data = $request->safe()->only(['category_name', 'category_code', 'parent_id', 'remark', 'is_active']);
        $data['website_id'] = admin_website_id();
        $data['created_by'] = auth()->user()->email ?? null;

        $result = $this->service->create($data);

        return redirect('master/categories')
            ->with($result['status'], $result['message']);
    }

    public function edit($id)
    {
        $data = $this->service->getRow($id, admin_website_id());

        if (!$data) {
            return redirect('master/categories')->with('failed', 'Category not found');
        }

        return view('pages.admin.master.categories.edit', [
            "data"    => $data,
            "parents" => $this->service->getParents(admin_website_id(), $id),
        ]);
    }

    public function update(CategoryRequest $request, $id)
    {
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('master/categories')->with('failed', 'Category not found');
        }

        $data = $request->safe()->only(['category_name', 'category_code', 'parent_id', 'remark', 'is_active']);
        $data['updated_by'] = auth()->user()->email ?? null;

        $result = $this->service->update($id, $data);

        return redirect('master/categories')
            ->with($result['status'], $result['message']);
    }

    public function destroy($id)
    {
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('master/categories')->with('failed', 'Category not found');
        }

        $result = $this->service->delete($id);

        return redirect('master/categories')
            ->with($result['status'], $result['message']);
    }
}
