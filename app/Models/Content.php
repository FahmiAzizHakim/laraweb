<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $table = 'contents';

    protected $fillable = [
        'website_id',
        'content_title',
        'content_subtitle',
        'content',
        'description',
        'media',
        'reference',
        'additional_url',
        'is_active',
        'created_by',
        'updated_by',
    ];

    /**
     * Scope: only active content, newest first.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderByDesc('created_at');
    }
}
