<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarRentalBooking;
use Illuminate\Http\Request;

class CarRentalBookingController extends Controller
{
    public function index(Request $request): \Illuminate\Contracts\View\View
    {
        $query = CarRentalBooking::with(['user', 'destinationCountry', 'destinationCity'])
            ->latest('created_at');

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(15);

        $stats = [
            'total' => CarRentalBooking::count(),
            'pending' => CarRentalBooking::where('status', 'pending')->count(),
            'followup' => CarRentalBooking::where('status', 'followup')->count(),
            'done' => CarRentalBooking::where('status', 'done')->count(),
        ];

        return view('Admin.car-rental-bookings', [
            'bookings' => $bookings,
            'stats' => $stats,
            'currentStatus' => $request->status ?? 'all',
        ]);
    }

    public function updateStatus(Request $request, CarRentalBooking $carRentalBooking): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,followup,done',
        ]);

        $carRentalBooking->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', __('Status updated successfully'));
    }
}
