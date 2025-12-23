<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelBooking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = HotelBooking::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_reference', 'like', "%{$search}%")
                  ->orWhere('guest_name', 'like', "%{$search}%")
                  ->orWhere('hotel_name', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status') && $request->status !== 'All Status' && $request->status !== __('All Status')) {
            // Map localized status back to database value if necessary
            $status = $request->status;
            $query->where('booking_status', $status);
        }

        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('check_in', $request->date);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);

        // Stats
        $stats = [
            'total' => HotelBooking::count(),
            'confirmed' => HotelBooking::where('booking_status', 'CONFIRMED')->count(),
            'pending' => HotelBooking::where('booking_status', 'PENDING')->count(),
            'cancelled' => HotelBooking::where('booking_status', 'CANCELLED')->count(),
        ];

        return view('Admin.bookings', compact('bookings', 'stats'));
    }
}
