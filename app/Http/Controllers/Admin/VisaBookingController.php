<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisaBooking;
use Illuminate\Http\Request;

class VisaBookingController extends Controller
{
    public function index(Request $request): \Illuminate\Contracts\View\View
    {
        $query = VisaBooking::with(['user', 'country'])
            ->latest('created_at');

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(15);

        $stats = [
            'total' => VisaBooking::count(),
            'pending' => VisaBooking::where('status', 'pending')->count(),
            'followup' => VisaBooking::where('status', 'followup')->count(),
            'done' => VisaBooking::where('status', 'done')->count(),
        ];

        return view('Admin.visa-bookings', [
            'bookings' => $bookings,
            'stats' => $stats,
            'currentStatus' => $request->status ?? 'all',
        ]);
    }

    public function updateStatus(Request $request, VisaBooking $visaBooking): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,followup,done',
        ]);

        $visaBooking->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', __('Status updated successfully'));
    }
}
