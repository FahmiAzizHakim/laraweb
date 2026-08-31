<?php

namespace App\Http\Controllers\Admin\Masterdata;

use App\Http\Controllers\Controller;
use App\Services\Masterdata\ProductService;
use App\Services\Masterdata\ServiceService;
use App\Services\Masterdata\CategoryService;
use App\Services\Helper\UploadService;
use App\Http\Requests\Masterdata\ProductRequest;

class ProductController extends Controller
{
    public $service;
    public $services;
    public $categories;
    public $upload;

    public function __construct(
        ProductService $service,
        ServiceService $services,
        CategoryService $categories,
        UploadService $upload
    ) {
        $this->service    = $service;
        $this->services   = $services;
        $this->categories = $categories;
        $this->upload     = $upload;
        view()->composer('*', function ($view) {
            $view->with('title', 'Products');
        });
    }

    public function index()
    {
        $data = $this->service->getList(admin_website_id());
        return view('pages.admin.master.product.list', ["data" => $data]);
    }

    public function add()
    {
        return view('pages.admin.master.product.add', [
            "services"   => $this->services->getList(admin_website_id()),
            "categories" => $this->categories->getList(admin_website_id()),
            "selected"   => [],
        ]);
    }

    public function store(ProductRequest $request)
    {
        $params = $this->params($request);
        $params['created_by'] = auth()->user()->email ?? null;

        $result = $this->service->create(
            $params,
            $request->input('categories', []),
            $this->uploadImages($request),
            $request->input('variants', []),
            $request->input('specifications', [])
        );

        return redirect('master/product')->with($result['status'], $result['message']);
    }

    public function edit($id)
    {
        $data = $this->service->getRow($id, admin_website_id());

        if (!$data) {
            return redirect('master/product')->with('failed', 'Product not found');
        }

        return view('pages.admin.master.product.edit', [
            "data"       => $data,
            "services"   => $this->services->getList(admin_website_id()),
            "categories" => $this->categories->getList(admin_website_id()),
            "selected"   => $data->categories->pluck('id')->toArray(),
        ]);
    }

    public function update(ProductRequest $request, $id)
    {
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('master/product')->with('failed', 'Product not found');
        }

        $params = $this->params($request);
        $params['updated_by'] = auth()->user()->email ?? null;

        $result = $this->service->update(
            $id,
            $params,
            $request->input('categories', []),
            $this->uploadImages($request),
            $request->input('remove_images', []),
            $request->input('variants', []),
            $request->input('specifications', [])
        );

        return redirect('master/product')->with($result['status'], $result['message']);
    }

    public function destroy($id)
    {
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('master/product')->with('failed', 'Product not found');
        }

        $result = $this->service->delete($id);

        return redirect('master/product')->with($result['status'], $result['message']);
    }

    /**
     * Persistable product columns from the request.
     */
    private function params(ProductRequest $request): array
    {
        $data = $request->safe()->only([
            'service_id', 'products_name', 'products_code', 'products_price', 'products_description',
            'products_weight', 'products_width', 'products_length', 'products_height', 'remark', 'is_active',
        ]);

        // price column is NOT NULL (default 0.00)
        $data['products_price'] = $request->input('products_price') ?: 0;

        return $data;
    }

    /**
     * Upload any submitted images and return rows for product_images.
     */
    private function uploadImages(ProductRequest $request): array
    {
        $images = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $up = $this->upload->store($file, 'product');
                if ($up) {
                    $images[] = ['image_name' => $up['original'], 'image_url' => $up['uploaded']];
                }
            }
        }

        return $images;
    }
}
