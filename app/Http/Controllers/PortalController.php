<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Website;
use App\Models\WebStyle;
use Illuminate\Support\Facades\Schema;

/**
 * The portal on the main index: a banner and one option per website.
 *
 * Nothing here is hard-coded per site -- the options are the active rows of
 * the websites table, so adding a fourth website is a seeder change, not a
 * code change. Only the route each website lives at is mapped here.
 */
class PortalController extends Controller
{
    /**
     * Website id => the route that opens it, plus the line shown on its card.
     * A website with no entry here simply is not offered on the portal.
     */
    protected $sites = [
        1 => [
            'route'    => 'home',
            'tagline'  => 'Import & export, warehousing, fulfilment, cargo and transport management.',
            'icon'     => 'bi-truck',
        ],
        2 => [
            'route'    => 'installer.home',
            'tagline'  => 'Furniture and AC installation, dismantling and cleaning, by size.',
            'icon'     => 'bi-tools',
        ],
        3 => [
            'route'    => 'ev.home',
            'tagline'  => 'Home charging and public SPKLU for electric vehicles.',
            'icon'     => 'bi-ev-station',
        ],
    ];

    public function index()
    {
        // The portal speaks for the group, so web_property() reads the parent
        // company's row (website 1) for the name, logo and contact details.
        if (Schema::hasTable('websites')) {
            Website::setCurrent(Website::find(1));
        }

        $websites = Schema::hasTable('websites')
            ? Website::where('is_active', true)->orderBy('id')->get()
            : collect();

        // One card per active website that has a route mapped above.
        $options = $websites
            ->filter(fn ($w) => isset($this->sites[$w->id]) && \Illuminate\Support\Facades\Route::has($this->sites[$w->id]['route']))
            ->map(fn ($w) => [
                'website' => $w,
                'url'     => route($this->sites[$w->id]['route']),
                'tagline' => $this->sites[$w->id]['tagline'],
                'icon'    => $this->sites[$w->id]['icon'],
                'banner'  => $this->bannerFor($w->id),
            ])
            ->values();

        // The portal's own look follows website 1's theme, so the group brand
        // stays consistent without needing style rows of its own.
        $styles = Schema::hasTable('web_styles') ? WebStyle::asMap(1) : [];

        return view('pages.portal.index', [
            'options' => $options,
            'styles'  => $styles,
            'hero'    => $this->bannerFor(1) ?: 'webassets/img/gls/hero1.jpg',
        ]);
    }

    /**
     * A website's first active banner image, or null.
     */
    protected function bannerFor($websiteId): ?string
    {
        if (!Schema::hasTable('banners')) {
            return null;
        }

        return Banner::where('website_id', $websiteId)
            ->where('is_active', true)
            ->orderBy('id')
            ->value('banner_img');
    }
}
