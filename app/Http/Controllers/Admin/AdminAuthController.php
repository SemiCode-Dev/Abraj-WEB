<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminLoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm(): View
    {
        return view('Admin.auth.login');
    }

    /**
     * Handle admin login request.
     */
    public function login(AdminLoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (auth()->attempt($credentials)) {
            $user = auth()->user();

            if (! $user->is_admin) {
                auth()->logout();

                return redirect()->route('admin.login')
                    ->withErrors(['email' => __('You do not have admin access.')]);
            }

            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->route('admin.login')
            ->withErrors(['email' => __('Invalid credentials.')])
            ->onlyInput('email');
    }

    /**
     * Handle admin logout.
     */
    public function logout(): RedirectResponse
    {
        auth()->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
