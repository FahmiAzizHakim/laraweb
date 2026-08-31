<?php

namespace App\Services\Helper;

use Auth;
use App\Models\User;
use App\Models\Communication\Notification;
use Illuminate\Support\Facades\Validator;
use App\Models\Profile\FirebaseToken;
use Illuminate\Support\Facades\Http;

class NotificationService
{
    public function insert($params = [])
    {
        $validator = Validator::make($params, [
            "user_id"       => "required|exists:users,id",
            "business_id"   => "nullable|exists:business,id",
            "type"          => "required",
            "title"         => "required",
            "img"           => "nullable",
            "message"       => "required",
            "url"           => "nullable",
            "mobile_url"    => "nullable"
        ]);

        if ($validator->fails()) {
            return false;
        }

        $user = User::where('id', $params['user_id'])->first();

        $data = Notification::create($validator->validated());

        Self::sendNotif($params['user_id'], $params['title'], $params['message']);

        return (isset($data)) ? true : false;
    }

    public function get($params)
    {
        $query = new Notification();
        foreach($params as $idx => $val)
        {
            $query = $query->where($idx, $val);
        }
        $data = $query->select("*")
                        ->selectRaw("DATE_FORMAT(created_at, '%d %b %Y %H:%i') as time")
                        ->orderBy("created_at", "DESC")
                        ->get();
        $unread = $query->where('is_read', 0)->count();
        

        return ["data" => $data, "unread" => $unread];
    }

    public function readnotif($id)
    {
        $data = Notification::where('id', $id)->first();
        $data->update(['is_read' => 1]);

        $updated = Notification::where('id', $id)->where('is_read', true)->first();
        return (isset($updated)) ? true : false;
    }

    public function delete($id)
    {
        $data = Notification::where('id', $id)->first();
        if(!isset($data)) return false;
        Notification::where('id', $id)->delete();

        return true;
    }
    
    public function sendNotif($user_id, $title, $message)
    {
        $header = [
            "Authorization" => "key=".env('FCM_API_SERVER_TOKEN')
        ];
        $url = "https://fcm.googleapis.com/fcm/send";
        $tokens = FirebaseToken::where('user_id', $user_id)->get();

        foreach ($tokens as $value) {
            $body = [
                "to"            => $value->token,
                "notification"  => [
                    "title" => $title,
                    "body"  => $message,
                    "icon"  => "https://halalind.com/favicon.png"
                ]
            ];
            Http::withHeaders($header)->post($url, $body)->json();
        }
        
        
    }
}