<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HotelBooking;
use App\Models\FlightBooking;
use App\Models\CarRentalBooking;
use App\Models\VisaBooking;
use App\Models\PackageContact;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    /**
     * Display a listing of the user's requests.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // 1. Localization
        $lang = $request->header('Accept-Language');
        $language = ($lang && str_contains(strtolower($lang), 'ar')) ? 'ar' : 'en';
        app()->setLocale($language);

        // 2. Fetch all bookings for the user with required relations
        $hotelBookings = HotelBooking::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($booking) use ($language) {
                return [
                    'id' => $booking->id,
                    'reference' => $booking->booking_reference,
                    'hotel_name' => $language === 'ar' ? ($booking->hotel_name_ar ?? $booking->hotel_name) : ($booking->hotel_name_en ?? $booking->hotel_name),
                    'room_name' => $booking->room_name,
                    'check_in' => $booking->check_in ? $booking->check_in->format('Y-m-d') : null,
                    'check_out' => $booking->check_out ? $booking->check_out->format('Y-m-d') : null,
                    'status' => $booking->booking_status,
                    'payment_status' => $booking->payment_status,
                    'total_price' => $booking->total_price . ' ' . $booking->currency,
                    'created_at' => $booking->created_at->format('Y-m-d H:i'),
                ];
            });

        $flightBookings = FlightBooking::where('user_id', $user->id)
            ->with(['originAirport', 'originCountry', 'destinationAirport', 'destinationCountry'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'origin' => $booking->originAirport->name ?? $booking->origin_airport_code,
                    'destination' => $booking->destinationAirport->name ?? $booking->destination_airport_code,
                    'departure_date' => $booking->departure_date ? $booking->departure_date->format('Y-m-d') : null,
                    'return_date' => $booking->return_date ? $booking->return_date->format('Y-m-d') : null,
                    'status' => $booking->status,
                    'created_at' => $booking->created_at->format('Y-m-d H:i'),
                ];
            });

        $carRentalBookings = CarRentalBooking::where('user_id', $user->id)
            ->with(['destinationCity', 'destinationCountry'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'destination' => $booking->destinationCity->name ?? $booking->destinationCountry->name ?? null,
                    'pickup_date' => $booking->pickup_date ? $booking->pickup_date->format('Y-m-d') : null,
                    'pickup_time' => $booking->pickup_time,
                    'status' => $booking->status,
                    'created_at' => $booking->created_at->format('Y-m-d H:i'),
                ];
            });

        $visaBookings = VisaBooking::where('user_id', $user->id)
            ->with('country')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'country' => $booking->country->name ?? null,
                    'visa_type' => $booking->visa_type,
                    'status' => $booking->status,
                    'created_at' => $booking->created_at->format('Y-m-d H:i'),
                ];
            });

        $packageInquiries = PackageContact::where('user_id', $user->id)
            ->with('package')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($contact) use ($language) {
                return [
                    'id' => $contact->id,
                    'package_name' => $language === 'ar' ? ($contact->package->title_ar ?? $contact->package->title) : ($contact->package->title ?? $contact->package->title_ar),
                    'image' => $contact->package->image ? asset('storage/' . $contact->package->image) : asset('images/default-package.jpg'),
                    'status' => $contact->status,
                    'created_at' => $contact->created_at->format('Y-m-d H:i'),
                ];
            });

        // 3. Structured response in sections
        $sections = [
            [
                'type' => 'hotel_bookings',
                'title' => $language === 'ar' ? 'حجوزات الفنادق' : 'Hotel Bookings',
                'data' => $hotelBookings,
                'message' => count($hotelBookings) === 0 ? __('No Requests Yet') : null,
                'description' => count($hotelBookings) === 0 ? __('Your booking requests will appear here once you make a reservation.') : null,
            ],
            [
                'type' => 'flight_bookings',
                'title' => $language === 'ar' ? 'حجوزات الطيران' : 'Flight Bookings',
                'data' => $flightBookings,
                'message' => count($flightBookings) === 0 ? __('No Requests Yet') : null,
                'description' => count($flightBookings) === 0 ? __('Your booking requests will appear here once you make a reservation.') : null,
            ],
            [
                'type' => 'car_rentals',
                'title' => $language === 'ar' ? 'حجوزات السيارات' : 'Car Rentals',
                'data' => $carRentalBookings,
                'message' => count($carRentalBookings) === 0 ? __('No Requests Yet') : null,
                'description' => count($carRentalBookings) === 0 ? __('Your booking requests will appear here once you make a reservation.') : null,
            ],
            [
                'type' => 'visa_applications',
                'title' => $language === 'ar' ? 'طلبات التأشيرة' : 'Visa Applications',
                'data' => $visaBookings,
                'message' => count($visaBookings) === 0 ? __('No Requests Yet') : null,
                'description' => count($visaBookings) === 0 ? __('Your booking requests will appear here once you make a reservation.') : null,
            ],
            [
                'type' => 'package_inquiries',
                'title' => $language === 'ar' ? 'استفسارات الباقات' : 'Package Inquiries',
                'data' => $packageInquiries,
                'message' => count($packageInquiries) === 0 ? __('No Requests Yet') : null,
                'description' => count($packageInquiries) === 0 ? __('Your booking requests will appear here once you make a reservation.') : null,
            ]
        ];

        // Check if user has ANY bookings across all types
        $hasAnyBookings = count($hotelBookings) > 0 
            || count($flightBookings) > 0 
            || count($carRentalBookings) > 0 
            || count($visaBookings) > 0
            || count($packageInquiries) > 0;

        if (!$hasAnyBookings) {
            return response()->json([
                'success' => true,
                'message' => $language === 'ar' ? 'لا توجد طلبات' : 'No requests found',
                'data' => (object)[]
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'User requests fetched successfully',
            'data' => $sections
        ]);
    }
}
