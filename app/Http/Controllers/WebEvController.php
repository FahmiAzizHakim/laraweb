<?php

namespace App\Http\Controllers;

/**
 * Public site for website 3 (EV Charging Solution), served at /ev.
 *
 * A copy of website 2 in every respect except its data: it renders the same
 * views (resources/views/{layout,pages}/website2) with website 3's services,
 * products, styles, banners and contents, and its links point at the /ev
 * routes. Behaviour lives in WebInstallerController -- only what differs is
 * declared here, so a fix to one site is a fix to both.
 */
class WebEvController extends WebInstallerController
{
    /** This site's website. */
    protected $websiteId = 3;

    /** Served under /ev, with ev.* route names. */
    protected $routePrefix = 'ev';
}
