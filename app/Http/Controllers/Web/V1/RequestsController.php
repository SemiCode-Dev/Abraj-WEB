<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Models\HotelBooking;
use App\Models\FlightBooking;
use App\Models\CarRentalBooking;
use App\Models\TransferBooking;
use App\Models\VisaBooking;
use App\Models\PackageContact;
use Illuminate\Http\Request;

class RequestsController extends Controller
{
    /**
     * Display user's booking requests
     */
    public function index()
    {
        $user = auth()->user();
        
        // If user is not logged in, redirect to home with message
        if (!$user) {
            return redirect()->route('home')->with('error', __('Please login to view your requests'));
        }

        // Fetch all bookings for the user
        $hotelBookings = HotelBooking::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $flightBookings = FlightBooking::where('user_id', $user->id)
            ->with(['originCity', 'originCountry', 'destinationCity', 'destinationCountry'])
            ->orderBy('created_at', 'desc')
            ->get();

        $carRentalBookings = CarRentalBooking::where('user_id', $user->id)
            ->with(['destinationCity', 'destinationCountry'])
            ->orderBy('created_at', 'desc')
            ->get();

        $transferBookings = TransferBooking::where('user_id', $user->id)
            ->with(['destinationCity', 'destinationCountry'])
            ->orderBy('created_at', 'desc')
            ->get();

        $visaBookings = VisaBooking::where('user_id', $user->id)
            ->with('country')
            ->orderBy('created_at', 'desc')
            ->get();

        $packageContacts = PackageContact::where('user_id', $user->id)
            ->with('package')
            ->orderBy('created_at', 'desc')
            ->get();

        // Check if user has any bookings
        $hasBookings = $hotelBookings->count() > 0 
            || $flightBookings->count() > 0 
            || $carRentalBookings->count() > 0 
            || $transferBookings->count() > 0 
            || $visaBookings->count() > 0
            || $packageContacts->count() > 0;

        return view('Web.requests', compact(
            'hotelBookings',
            'flightBookings',
            'carRentalBookings',
            'transferBookings',
            'visaBookings',
            'packageContacts',
            'hasBookings'
        ));
    }
}
