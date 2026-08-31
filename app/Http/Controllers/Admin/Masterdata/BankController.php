<?php

namespace App\Http\Controllers\Admin\Masterdata;

use App\Http\Controllers\Controller;
use App\Services\Masterdata\BankService;
use App\Services\Helper\UploadService;
use App\Http\Requests\Masterdata\BankRequest;

class BankController extends Controller
{
    public $service;
    public $upload;

    public function __construct(BankService $service, UploadService $upload)
    {
        $this->service = $service;
        $this->upload  = $upload;
        view()->composer('*', function ($view) {
            $view->with('title', 'Banks');
        });
    }

    public function index()
    {
        $data = $this->service->getList(admin_website_id());
        return view('pages.admin.master.bank.list', ["data" => $data]);
    }

    public function add()
    {
        return view('pages.admin.master.bank.add');
    }

    public function store(BankRequest $request)
    {
        $data = $request->safe()->only([
            'bank_name', 'bank_account', 'account_name', 'branch', 'remark', 'is_active',
        ]);

        if ($request->hasFile('logo')) {
            $up = $this->upload->store($request->file('logo'), 'bank');
            if ($up) $data['logo'] = $up['uploaded'];
        }

        $data['website_id'] = admin_website_id();
        $data['created_by'] = auth()->user()->email ?? null;

        $result = $this->service->create($data);

        return redirect('master/bank')
            ->with($result['status'], $result['message']);
    }

    public function edit($id)
    {
        $data = $this->service->getRow($id, admin_website_id());

        if (!$data) {
            return redirect('master/bank')->with('failed', 'Bank not found');
        }

        return view('pages.admin.master.bank.edit', ["data" => $data]);
    }

    public function update(BankRequest $request, $id)
    {
        // Ensure the bank belongs to the current admin's website.
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('master/bank')->with('failed', 'Bank not found');
        }

        $data = $request->safe()->only([
            'bank_name', 'bank_account', 'account_name', 'branch', 'remark', 'is_active',
        ]);

        if ($request->hasFile('logo')) {
            $up = $this->upload->store($request->file('logo'), 'bank');
            if ($up) $data['logo'] = $up['uploaded'];
        }

        $data['updated_by'] = auth()->user()->email ?? null;

        $result = $this->service->update($id, $data);

        return redirect('master/bank')
            ->with($result['status'], $result['message']);
    }

    public function destroy($id)
    {
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('master/bank')->with('failed', 'Bank not found');
        }

        $result = $this->service->delete($id);

        return redirect('master/bank')
            ->with($result['status'], $result['message']);
    }
}
