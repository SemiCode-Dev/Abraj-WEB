<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransferBooking;
use Illuminate\Http\Request;

class TransferBookingController extends Controller
{
    public function index(Request $request): \Illuminate\Contracts\View\View
    {
        $query = TransferBooking::with(['user', 'destinationCountry', 'destinationCity'])
            ->latest('created_at');

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(15);

        $stats = [
            'total' => TransferBooking::count(),
            'pending' => TransferBooking::where('status', 'pending')->count(),
            'followup' => TransferBooking::where('status', 'followup')->count(),
            'done' => TransferBooking::where('status', 'done')->count(),
        ];

        return view('Admin.transfer-bookings', [
            'bookings' => $bookings,
            'stats' => $stats,
            'currentStatus' => $request->status ?? 'all',
        ]);
    }

    public function updateStatus(Request $request, TransferBooking $transferBooking): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,followup,done',
        ]);

        $transferBooking->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', __('Status updated successfully'));
    }
}
