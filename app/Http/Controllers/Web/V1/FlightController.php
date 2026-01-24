<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\V1\FlightBookingRequest;
use App\Mail\BookingRequestMail;
use App\Models\Country;
use App\Models\FlightBooking;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FlightController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('name')->get();

        return view('Web.flights', [
            'countries' => $countries,
        ]);
    }

    public function book(FlightBookingRequest $request)
    {
        try {
            $user = auth()->user();

            $countryCode = $user ? ($user->phone_country_code ?? '966') : $request->phone_country_code;
            if ($countryCode && ! str_starts_with($countryCode, '+')) {
                $countryCode = '+'.$countryCode;
            }

            FlightBooking::create([
                'user_id' => $user?->id,
                'name' => $user ? $user->name : $request->name,
                'email' => $user ? $user->email : $request->email,
                'phone_country_code' => $countryCode,
                'phone' => $user ? ($user->phone ?? '') : $request->phone,
                'origin_country_id' => $request->origin_country_id,
                'origin_airport_id' => $request->origin_airport_id,
                'destination_country_id' => $request->destination_country_id,
                'destination_airport_id' => $request->destination_airport_id,
                'adults' => $request->adults,
                'children' => $request->children,
                'departure_date' => $request->departure_date,
                'return_date' => $request->return_date,
                'notes' => $request->notes,
            ]);

            // Send Email Notification
            // Send Email Notification
            try {
                $originCountry = Country::find($request->origin_country_id);
                $destinationCountry = Country::find($request->destination_country_id);

                // Safe load airports using Model
                $originAirport = \App\Models\Airport::find($request->origin_airport_id);
                $destinationAirport = \App\Models\Airport::find($request->destination_airport_id);

                $mailData = [
                    'name' => $user ? $user->name : $request->name,
                    'email' => $user ? $user->email : $request->email,
                    'phone' => $countryCode.($user ? ($user->phone ?? '') : $request->phone),
                    'origin_country' => $originCountry ? ($originCountry->name_en ?? $originCountry->name) : $request->origin_country_id,
                    'origin_airport' => $originAirport ? ($originAirport->name ?? $originAirport->name_ar) : $request->origin_airport_id,
                    'destination_country' => $destinationCountry ? ($destinationCountry->name_en ?? $destinationCountry->name) : $request->destination_country_id,
                    'destination_airport' => $destinationAirport ? ($destinationAirport->name ?? $destinationAirport->name_ar) : $request->destination_airport_id,
                    'adults' => $request->adults,
                    'children' => $request->children,
                    'departure_date' => $request->departure_date,
                    'return_date' => $request->return_date,
                    'notes' => $request->notes,
                ];

                Mail::to('ab2429601@gmail.com')->send(new BookingRequestMail($mailData, 'Flight Booking'));
            } catch (\Exception $e) {
                Log::error('Failed to send flight booking email: '.$e->getMessage());
            }

            return redirect()->back()->with('success', __('Flight booking request submitted successfully!'));
        } catch (\Exception $e) {
            Log::error('Failed to save flight booking: '.$e->getMessage());

            return redirect()->back()->with('error', __('Failed to submit flight booking. Please try again.'));
        }
    }
}
