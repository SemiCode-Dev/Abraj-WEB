<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\V1\FlightBookingRequest;
use App\Models\Country;
use App\Models\FlightBooking;
use Illuminate\Support\Facades\Log;

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

            FlightBooking::create([
                'user_id' => $user?->id,
                'name' => $user ? $user->name : $request->name,
                'email' => $user ? $user->email : $request->email,
                'phone_country_code' => $user ? ($user->phone_country_code ?? '966') : $request->phone_country_code,
                'phone' => $user ? ($user->phone ?? '') : $request->phone,
                'origin_country_id' => $request->origin_country_id,
                'origin_city_id' => $request->origin_city_id,
                'destination_country_id' => $request->destination_country_id,
                'destination_city_id' => $request->destination_city_id,
                'adults' => $request->adults,
                'children' => $request->children,
                'departure_date' => $request->departure_date,
                'return_date' => $request->return_date,
                'notes' => $request->notes,
            ]);

            return redirect()->back()->with('success', __('Flight booking request submitted successfully!'));
        } catch (\Exception $e) {
            Log::error('Failed to save flight booking: '.$e->getMessage());

            return redirect()->back()->with('error', __('Failed to submit flight booking. Please try again.'));
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
