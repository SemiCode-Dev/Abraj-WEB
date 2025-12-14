<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlightBooking;
use Illuminate\Http\Request;

class FlightBookingController extends Controller
{
    public function index(Request $request): \Illuminate\Contracts\View\View
    {
        $query = FlightBooking::with(['user', 'originCountry', 'originCity', 'destinationCountry', 'destinationCity'])
            ->latest('created_at');

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(15);

        $stats = [
            'total' => FlightBooking::count(),
            'pending' => FlightBooking::where('status', 'pending')->count(),
            'followup' => FlightBooking::where('status', 'followup')->count(),
            'done' => FlightBooking::where('status', 'done')->count(),
        ];

        return view('Admin.flight-bookings', [
            'bookings' => $bookings,
            'stats' => $stats,
            'currentStatus' => $request->status ?? 'all',
        ]);
    }

    public function updateStatus(Request $request, FlightBooking $flightBooking): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,followup,done',
        ]);

        $flightBooking->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', __('Status updated successfully'));
    }
}
