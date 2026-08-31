<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MessageService;

class MessageController extends Controller
{
    public $service;

    public function __construct(MessageService $service)
    {
        $this->service = $service;
        view()->composer('*', function ($view) {
            $view->with('title', 'Pesan Masuk');
        });
    }

    public function index()
    {
        $data = $this->service->getList(admin_website_id());
        return view('pages.admin.message.list', ["data" => $data]);
    }

    public function show($id)
    {
        $data = $this->service->getRow($id, admin_website_id());

        if (!$data) {
            return redirect('message')->with('failed', 'Message not found');
        }

        // Opening a message marks it as read.
        $this->service->markAsRead($data);

        return view('pages.admin.message.show', ["data" => $data]);
    }
}
