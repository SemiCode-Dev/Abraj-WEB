<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\V1\CarRentalBookingRequest;
use App\Mail\BookingRequestMail;
use App\Models\CarRentalBooking;
use App\Models\Country;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CarRentalController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('name')->get();

        return view('Web.car-rental', [
            'countries' => $countries,
        ]);
    }

    public function book(CarRentalBookingRequest $request)
    {
        try {
            $user = auth()->user();

            $countryCode = $user ? ($user->phone_country_code ?? '966') : $request->phone_country_code;
            if ($countryCode && ! str_starts_with($countryCode, '+')) {
                $countryCode = '+'.$countryCode;
            }

            CarRentalBooking::create([
                'user_id' => $user?->id,
                'name' => $user ? $user->name : $request->name,
                'email' => $user ? $user->email : $request->email,
                'phone_country_code' => $countryCode,
                'phone' => $user ? ($user->phone ?? '') : $request->phone,
                'destination_country_id' => $request->destination_country_id,
                'destination_city_id' => $request->destination_city_id,
                'pickup_date' => $request->pickup_date,
                'pickup_time' => $request->pickup_time,
                'return_date' => $request->return_date,
                'return_time' => $request->return_time,
                'driver_option' => $request->driver_option,
                'drivers' => $request->driver_option === 'with_driver' ? 1 : 0,
                'notes' => $request->notes,
            ]);

            // Send Email Notification
            try {
                $destinationCountry = Country::find($request->destination_country_id);
                $destinationCity = \App\Models\City::find($request->destination_city_id);

                $mailData = [
                    'name' => $user ? $user->name : $request->name,
                    'email' => $user ? $user->email : $request->email,
                    'phone' => $countryCode . ($user ? ($user->phone ?? '') : $request->phone),
                    'destination_country' => $destinationCountry ? ($destinationCountry->name_en ?? $destinationCountry->name) : $request->destination_country_id,
                    'destination_city' => $destinationCity ? ($destinationCity->name ?? $destinationCity->name_ar) : $request->destination_city_id,
                    'pickup_date' => $request->pickup_date,
                    'pickup_time' => $request->pickup_time,
                    'return_date' => $request->return_date,
                    'return_time' => $request->return_time,
                    'driver_option' => $request->driver_option,
                    'notes' => $request->notes,
                ];

                Mail::to('ab2429601@gmail.com')->send(new BookingRequestMail($mailData, 'Request (Mishwar/Car Rental)'));
            } catch (\Exception $e) {
                Log::error('Failed to send car rental booking email: ' . $e->getMessage());
            }

            return redirect()->back()->with('success', __('Car rental booking request submitted successfully!'));
        } catch (\Exception $e) {
            Log::error('Failed to save car rental booking: '.$e->getMessage());

            return redirect()->back()->with('error', __('Failed to submit car rental booking. Please try again.'));
        }
    }
}
