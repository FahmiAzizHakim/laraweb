<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMenuAccess
{
    public function handle(Request $request, Closure $next, string $menuUrl)
    {
        $user = auth()->user();
        $menus = auth()->user()
            ->menuGroup
            ->menus()
            ->where('activestatus', 1)
            ->get();

        // dd($menus);

        if (!$user || !$user->menuGroup) {
            abort(403);
        }

        $hasAccess = $user->menuGroup->menus()
            ->where('menu_url', $menuUrl)
            ->where('activestatus', 1)
            ->exists();

        if (!$hasAccess) {
            abort(403);
        }

        return $next($request);
    }
}
