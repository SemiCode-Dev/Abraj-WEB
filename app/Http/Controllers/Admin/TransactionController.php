<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelBooking;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = HotelBooking::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_reference', 'like', "%{$search}%")
                  ->orWhere('payment_reference', 'like', "%{$search}%")
                  ->orWhere('guest_name', 'like', "%{$search}%")
                  ->orWhere('guest_email', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status') && $request->status !== 'All Status') {
            $query->where('payment_status', strtolower($request->status));
        }

        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(10);

        // Stats for cards based on unified logic:
        // - Failed: Either payment or booking failed
        // - Successful: Both payment paid and booking confirmed
        // - Pending: Neither failed, but at least one is pending
        $stats = [
            'total_revenue' => HotelBooking::where('payment_status', 'paid')->sum('total_price'),
            'successful' => HotelBooking::where('payment_status', 'paid')
                                       ->where('booking_status', 'confirmed')
                                       ->count(),
            'failed' => HotelBooking::where(function($q) {
                                        $q->where('payment_status', 'failed')
                                          ->orWhere('booking_status', 'failed');
                                    })->count(),
            'pending' => HotelBooking::where('payment_status', '!=', 'failed')
                                     ->where('booking_status', '!=', 'failed')
                                     ->where(function($q) {
                                         $q->where('payment_status', 'pending')
                                           ->orWhere('booking_status', 'pending');
                                     })->count(),
        ];

        return view('Admin.transactions', compact('transactions', 'stats'));
    }

    public function downloadReport(HotelBooking $booking)
    {
        $content = "Transaction Report\n";
        $content .= "------------------\n";
        $content .= "Booking Reference: " . $booking->booking_reference . "\n";
        $content .= "Transaction ID: " . ($booking->payment_reference ?: 'N/A') . "\n";
        $content .= "Customer: " . $booking->guest_name . " (" . $booking->guest_email . ")\n";
        $content .= "Hotel: " . $booking->hotel_name . "\n";
        $content .= "Room: " . $booking->room_name . "\n";
        $content .= "Check-in: " . $booking->check_in->format('Y-m-d') . "\n";
        $content .= "Check-out: " . $booking->check_out->format('Y-m-d') . "\n";
        $content .= "Amount: " . $booking->currency . " " . number_format($booking->total_price, 2) . "\n";
        $content .= "Payment Status: " . ucfirst($booking->payment_status) . "\n";
        $content .= "Payment Date: " . $booking->created_at->format('Y-m-d H:i:s') . "\n";
        
        if ($booking->payment_details) {
            $content .= "\nPayment Details:\n";
            foreach ($booking->payment_details as $key => $value) {
                if (is_scalar($value)) {
                    $content .= ucfirst(str_replace('_', ' ', $key)) . ": " . $value . "\n";
                }
            }
        }

        $filename = "report-" . $booking->booking_reference . ".txt";
        
        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
