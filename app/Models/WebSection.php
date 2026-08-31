<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One block of a website's landing page: its order, and whether it shows on the
 * page and in the navigation.
 *
 * section_key names the Blade partial that renders it, so these rows are edited
 * rather than created -- a key with no partial would render nothing.
 */
class WebSection extends Model
{
    protected $table = 'web_sections';

    protected $fillable = [
        'website_id',
        'section_key',
        'section_name',
        'nav_label',
        'anchor',
        'order',
        'show_in_page',
        'show_in_nav',
        'remark',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'order'        => 'integer',
        'show_in_page' => 'boolean',
        'show_in_nav'  => 'boolean',
    ];

    public function website()
    {
        return $this->belongsTo(Website::class, 'website_id');
    }

    public function scopeForWebsite($query, $websiteId)
    {
        return $query->where('website_id', $websiteId);
    }

    /** Display order: `order` first, then id so ties stay put. */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('id');
    }

    public function scopeOnPage($query)
    {
        return $query->where('show_in_page', true);
    }

    public function scopeInNav($query)
    {
        return $query->where('show_in_nav', true);
    }

    /**
     * The menu label, falling back to the section name.
     */
    public function getLabelAttribute(): string
    {
        return $this->nav_label ?: $this->section_name;
    }
}
