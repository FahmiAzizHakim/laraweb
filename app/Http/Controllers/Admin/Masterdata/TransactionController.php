<?php

namespace App\Http\Controllers\Admin\Masterdata;

use App\Http\Controllers\Controller;
use App\Services\Masterdata\TransactionService;
use App\Http\Requests\Masterdata\TransactionStatusRequest;

class TransactionController extends Controller
{
    public $service;

    public function __construct(TransactionService $service)
    {
        $this->service = $service;
        view()->composer('*', function ($view) {
            $view->with('title', 'Transactions');
        });
    }

    public function index()
    {
        $data = $this->service->getList(admin_website_id());
        return view('pages.admin.master.transaction.list', ["data" => $data]);
    }

    public function show($id)
    {
        $data = $this->service->getRow($id, admin_website_id());

        if (!$data) {
            return redirect('master/transaction')->with('failed', 'Transaction not found');
        }

        return view('pages.admin.master.transaction.show', [
            "data"     => $data,
            "statuses" => $this->service->statusOptions(),
        ]);
    }

    public function updateStatus(TransactionStatusRequest $request, $id)
    {
        // Ensure the transaction belongs to the current admin's website.
        if (!$this->service->getRow($id, admin_website_id())) {
            return redirect('master/transaction')->with('failed', 'Transaction not found');
        }

        $result = $this->service->updateStatus(
            $id,
            $request->input('status'),
            $request->input('description'),
            auth()->user()->email ?? null
        );

        return redirect('master/transaction/'.$id)
            ->with($result['status'], $result['message']);
    }
}
