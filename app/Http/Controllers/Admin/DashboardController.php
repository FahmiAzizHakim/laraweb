<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MessageService;
use App\Services\Masterdata\TransactionService;
use App\Models\Website;

class DashboardController extends Controller
{
    public function __construct()
    {
        view()->composer('*', function ($view) {
            $view->with('title', 'Dashboard');
        });
    }

    public function index(MessageService $messages, TransactionService $transactions)
    {
        $user      = auth()->user();
        $websiteId = $user->website_id;

        $unread  = $messages->getList($websiteId)
            ->where('status', '!=', 'read')
            ->values();

        $website = Website::find($websiteId);

        // Transactions overview (newest first from the service).
        $allTransactions   = $transactions->getList($websiteId);
        $recentTransactions = $allTransactions->take(5);
        $transactionStats  = [
            'count'      => $allTransactions->count(),
            'grandtotal' => $allTransactions->sum('grandtotal'),
        ];

        return view('pages.admin.home', [
            'user'               => $user,
            'website'            => $website,
            'unread'             => $unread,
            'recentTransactions' => $recentTransactions,
            'transactionStats'   => $transactionStats,
        ]);
    }
}
