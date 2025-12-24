<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\V1\CarRentalBookingRequest;
use App\Models\CarRentalBooking;
use App\Models\Country;
use Illuminate\Support\Facades\Log;

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

            CarRentalBooking::create([
                'user_id' => $user?->id,
                'name' => $user ? $user->name : $request->name,
                'email' => $user ? $user->email : $request->email,
                'phone_country_code' => $user ? ($user->phone_country_code ?? '966') : $request->phone_country_code,
                'phone' => $user ? ($user->phone ?? '') : $request->phone,
                'destination_country_id' => $request->destination_country_id,
                'destination_city_id' => $request->destination_city_id,
                'pickup_date' => $request->pickup_date,
                'pickup_time' => $request->pickup_time,
                'return_date' => $request->return_date,
                'return_time' => $request->return_time,
                'drivers' => 1, // Default value
                'notes' => $request->notes,
            ]);

            return redirect()->back()->with('success', __('Car rental booking request submitted successfully!'));
        } catch (\Exception $e) {
            Log::error('Failed to save car rental booking: '.$e->getMessage());

            return redirect()->back()->with('error', __('Failed to submit car rental booking. Please try again.'));
        }
    }

    public function getCitiesByCountry($countryId)
    {
        try {
            $country = Country::with('cities')->findOrFail($countryId);
            $cities = $country->cities()->orderBy('name')->get();

            return response()->json($cities->map(function ($city) {
                return [
                    'id' => $city->id,
                    'name' => $city->locale_name,
                    'name_ar' => $city->name_ar,
                    'code' => $city->code,
                ];
            }));
        } catch (\Exception $e) {
            Log::error('Failed to fetch cities: '.$e->getMessage());

            return response()->json(['error' => __('Failed to fetch cities')], 500);
        }
    }
}
