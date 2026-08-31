<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\WebStyle;
use App\Models\Banner;
use App\Models\Content;
use App\Models\Website;
use App\Models\Message;

/**
 * Public site for website 1 (Green Logistics Solution), served at /.
 * Views live under resources/views/{layout,pages}/website.
 */
class WebController extends Controller
{
    /** This site's website. */
    protected $websiteId = 1;

    public function __construct()
    {
        // Make this the "current" website so the web_property() helper matches.
        if (Schema::hasTable('websites')) {
            Website::setCurrent(Website::find($this->websiteId));
        }

        view()->share('website', Schema::hasTable('websites') ? Website::find($this->websiteId) : null);

        $styles = Schema::hasTable('web_styles') ? WebStyle::asMap($this->websiteId) : [];
        view()->share('styles', $styles);

        $banners = Schema::hasTable('banners')
            ? Banner::active()->where('website_id', $this->websiteId)->get()
            : collect();
        view()->share('banners', $banners);

        $contents = Schema::hasTable('contents')
            ? Content::active()->where('website_id', $this->websiteId)->get()
            : collect();
        view()->share('contents', $contents);
    }

    public function index()
    {
        // The About section, in display order; the first row is the main one.
        $abouts = app(\App\Services\Website\AboutService::class)->getPublicList($this->websiteId);

        return view("pages.website.index", compact('abouts'));
    }

    public function content($id)
    {
        $content = Content::where('id', $id)
            ->where('is_active', true)
            ->where('website_id', $this->websiteId)
            ->first();

        if (!$content) {
            abort(404);
        }

        return view("pages.website.content", ['content' => $content]);
    }

    public function sendMessage(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:191',
            'email'   => 'required|email|max:191',
            'phone'   => 'nullable|string|max:20',
            'subject' => 'required|string|max:191',
            'message' => 'required|string|max:5000',
        ]);

        $data['website_id'] = $this->websiteId;
        $data['status']     = 'open';

        Message::create($data);

        return back()
            ->with('message_sent', 'Your message has been sent. Thank you!')
            ->withFragment('contact');
    }
}
