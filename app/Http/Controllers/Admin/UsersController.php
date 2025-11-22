<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        $users = User::where('is_admin', 0)->latest('created_at')->paginate(15);
        $totalUsers = User::where('is_admin', 0)->count();
        $activeUsers = User::where('is_admin', 0)->whereNotNull('email_verified_at')->count();
        $newThisMonth = User::where('is_admin', 0)
            ->whereDate('created_at', '>=', now()->startOfMonth())
            ->count();
        $verifiedUsers = User::where('is_admin', 0)->whereNotNull('email_verified_at')->count();

        return view('Admin.users', [
            'users' => $users,
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'newThisMonth' => $newThisMonth,
            'verifiedUsers' => $verifiedUsers,
        ]);
    }
}
