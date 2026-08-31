<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebStyle extends Model
{
    protected $table = 'web_styles';

    protected $fillable = [
        'style_key',
        'style_label',
        'style_value',
        'style_type',
        'style_group',
        'description',
        'order',
        'is_active',
        'created_by',
        'updated_by',
    ];

    /**
     * Return active styles as a [style_key => style_value] map,
     * ready to share with the website views.
     */
    public static function asMap($websiteId = null)
    {
        $query = static::where('is_active', true);

        if ($websiteId) {
            $query->where('website_id', $websiteId);
        }

        return $query->orderBy('order')
            ->pluck('style_value', 'style_key')
            ->toArray();
    }
}
