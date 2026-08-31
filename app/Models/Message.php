<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'message';

    protected $fillable = [
        'website_id',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
    ];
}
