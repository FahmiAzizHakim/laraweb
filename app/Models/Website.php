<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $table = 'websites';

    protected $fillable = [
        'web_name',
        'company_name',
        'logo',
        'logo_white',
        'phone_number',
        'whatsapp_no',
        'email',
        'facebook_link',
        'twitter_link',
        'instagram_link',
        'linkedin_link',
        'address',
        'location',
        'domain',
        'is_active',
        'created_by',
        'updated_by',
    ];

    /**
     * Per-request cache of the current website.
     */
    protected static $current = null;

    /**
     * The active website for the current request.
     *
     * For now there is a single website; this is where host-based
     * (domain) resolution would go for true multi-website support.
     */
    public static function current()
    {
        if (static::$current === null) {
            try {
                static::$current = static::resolveForRequest() ?: false;
            } catch (\Throwable $e) {
                static::$current = false;
            }
        }

        return static::$current ?: null;
    }

    /**
     * Resolve which website a public request belongs to:
     *   1. matching domain (production, host-based)
     *   2. first active website (fallback)
     */
    protected static function resolveForRequest()
    {
        $request = request();

        // 1) host / domain match
        if ($request) {
            $site = static::where('is_active', true)->where('domain', $request->getHost())->first();
            if ($site) {
                return $site;
            }
        }

        // 2) fallback
        return static::where('is_active', true)->orderBy('id')->first();
    }

    /**
     * Force the "current" website for this request (used by dedicated
     * per-website controllers such as the installer site).
     */
    public static function setCurrent($website): void
    {
        static::$current = $website ?: false;
    }

    public static function currentId()
    {
        return optional(static::current())->id;
    }

    public static function flushCache(): void
    {
        static::$current = null;
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::flushCache());
        static::deleted(fn () => static::flushCache());
    }
}
