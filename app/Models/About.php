<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One block of a website's "About" section. A website may have several; the
 * lowest `order` (then the lowest id) is the main one.
 */
class About extends Model
{
    protected $table = 'abouts';

    protected $fillable = [
        'website_id',
        'about_title',
        'about_subtitle',
        'about_content',
        'about_image',
        'order',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'order'     => 'integer',
        'is_active' => 'boolean',
    ];

    public function website()
    {
        return $this->belongsTo(Website::class, 'website_id');
    }

    public function scopeForWebsite($query, $websiteId)
    {
        return $query->where('website_id', $websiteId);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Display order: `order` first, then id so rows added later stay put.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('id');
    }

    /**
     * about_content is plain text; blank lines separate paragraphs.
     *
     * @return array<int, string>
     */
    public function getParagraphsAttribute(): array
    {
        $text = trim((string) $this->about_content);

        if ($text === '') {
            return [];
        }

        return array_values(array_filter(array_map(
            fn ($block) => trim(preg_replace('/\s*\R\s*/', ' ', $block)),
            preg_split('/\R\s*\R/', $text)
        ), fn ($block) => $block !== ''));
    }
}
