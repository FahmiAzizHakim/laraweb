<?php

use App\Models\Website;

if (!function_exists('current_website')) {
    /**
     * The active website record for the current request (or null).
     */
    function current_website()
    {
        return Website::current();
    }
}

if (!function_exists('admin_website_id')) {
    /**
     * The website_id of the logged-in admin user. Used to scope all
     * admin CRUD (get/create) to the user's own website.
     */
    function admin_website_id()
    {
        return optional(auth()->user())->website_id;
    }
}

if (!function_exists('web_property')) {
    /**
     * Fetch a website property (column) by name from the current website.
     *
     * Usage in any Blade view / controller:
     *   {{ web_property('email') }}
     *   {{ web_property('web_name', 'Default Name') }}
     *   web_property()            // returns the current Website model
     *
     * @param  string|null  $key
     * @param  mixed         $default
     * @return mixed
     */
    function web_property($key = null, $default = null)
    {
        $website = Website::current();

        if ($key === null) {
            return $website;
        }

        $value = $website ? ($website->{$key} ?? null) : null;

        return ($value === null || $value === '') ? $default : $value;
    }
}

if (!function_exists('web_asset')) {
    /**
     * Convenience wrapper for image columns (logo, etc.) that returns a
     * full asset() URL, falling back to $default when unset.
     *
     * @param  string  $key
     * @param  string|null  $default  Path used when the property is empty.
     * @return string
     */
    function web_asset($key, $default = null)
    {
        $path = web_property($key, $default);

        return $path ? asset($path) : '';
    }
}
