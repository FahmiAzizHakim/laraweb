<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Services\MenuService;
use App\Models\Message;

class AdminComposer
{
    public function compose(View $view)
    {
        if (!auth()->check()) {
            return;
        }

        $user = auth()->user();
        $menus = [];

        if(isset($user->menuGroup->id))
            $menus = app(MenuService::class)->getMenus($user->menuGroup->id);
        // dd($menus);

        // Unread inbox count for the sidebar badge (scoped to the user's website).
        $unreadMessages = 0;
        try {
            $unreadMessages = Message::where('status', '!=', 'read')
                ->when($user->website_id, fn ($q) => $q->where('website_id', $user->website_id))
                ->count();
        } catch (\Throwable $e) {
            $unreadMessages = 0;
        }

        $view->with([
            'authUser'       => $user,
            'menus'          => $menus,
            'unreadMessages' => $unreadMessages,
        ]);
    }
}
