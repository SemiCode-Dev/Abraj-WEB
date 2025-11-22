<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        $users = User::where('is_admin', 0)->latest('created_at')->paginate(15);
        $totalUsers = User::count();
        $adminCount = User::where('is_admin', true)->count();

        return view('Admin.dashboard', [
            'users' => $users,
            'totalUsers' => $totalUsers,
            'adminCount' => $adminCount,
        ]);
    }
}
