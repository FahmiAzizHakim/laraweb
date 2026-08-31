<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChangePasswordController extends Controller
{
    public function __construct()
    {
        view()->composer('*', function ($view) {
            $view->with('title', 'Change Password');
        });
    }

    public function edit()
    {
        return view('pages.admin.password.change');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', 'min:8', 'different:current_password'],
        ], [
            'current_password.current_password' => 'The current password is incorrect.',
            'password.different'                => 'The new password must be different from the current one.',
        ]);

        // 'hashed' cast on the User model hashes the plain value on save.
        $request->user()->update(['password' => $validated['password']]);

        return redirect('/dashboard')->with('success', 'Password updated successfully.');
    }
}
