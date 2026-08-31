<?php

namespace App\Services\Helper;

use DB;
use Xendit\Xendit;
use Illuminate\Support\Facades\Http;

class XenditService
{
    public function getBalance()
    {
        Xendit::setApiKey(env('XENDIT_KEY'));

        $getBalance = \Xendit\Balance::getBalance('CASH');
        
        if(isset($getBalance))
            return ["success" => true, "data" => $getBalance];
        
        else
            return ["success" => false];
    }

    public function createVA($params)
    {
        Xendit::setApiKey(env('XENDIT_KEY'));

        $params = [ 
            "external_id"       => $params['transaction_code'],
            "bank_code"         => $params['bank_code'],
            "name"              => $params['name'],
            "is_single_use"     => true,
            "is_closed"         => true,
            "expected_amount"   => $params['amount'],
        ];

        $createVA = \Xendit\VirtualAccounts::create($params);

        if(isset($createVA))
            return ["success" => true, "data" => $createVA];
        else
            return ["success" => false];
    }
}