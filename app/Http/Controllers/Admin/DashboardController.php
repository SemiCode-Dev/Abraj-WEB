<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\HotelBooking;
use App\Models\ContactMessage;

class DashboardController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        $users = User::where('is_admin', 0)->latest('created_at')->paginate(15);
        $totalUsers = User::count();
        
        // Calculate total revenue from paid bookings
        $totalRevenue = HotelBooking::where('payment_status', 'paid')->sum('total_price');
        
        // Calculate total bookings
        $totalBookings = HotelBooking::count();
        
        // Calculate total user reports (contact messages)
        $totalReports = ContactMessage::count();

        return view('Admin.dashboard', [
            'users' => $users,
            'totalUsers' => $totalUsers,
            'totalRevenue' => $totalRevenue,
            'totalBookings' => $totalBookings,
            'totalReports' => $totalReports,
        ]);
    }
}
