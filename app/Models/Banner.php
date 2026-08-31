<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banners';

    protected $fillable = [
        'website_id',
        'banner_title',
        'banner_img',
        'banner_text',
        'content_id',
        'is_active',
        'created_by',
        'updated_by',
    ];

    /**
     * Scope: only active banners, in display order.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('id');
    }
}
