<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\V1\TransferBookingRequest;
use App\Models\Country;
use App\Models\TransferBooking;
use Illuminate\Support\Facades\Log;

class TransferController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('name')->get();

        return view('Web.transfer', [
            'countries' => $countries,
        ]);
    }

    public function book(TransferBookingRequest $request)
    {
        try {
            $user = auth()->user();

            TransferBooking::create([
                'user_id' => $user?->id,
                'name' => $user ? $user->name : $request->name,
                'email' => $user ? $user->email : $request->email,
                'phone_country_code' => $request->phone_country_code,
                'phone' => $request->phone,
                'destination_country_id' => $request->destination_country_id,
                'destination_city_id' => $request->destination_city_id,
                'transfer_date' => $request->transfer_date,
                'transfer_time' => $request->transfer_time,
                'trip_type' => $request->trip_type,
                'return_date' => $request->return_date ?? null,
                'return_time' => $request->return_time ?? null,
                'passengers' => $request->passengers,
                'notes' => $request->notes,
            ]);

            return redirect()->back()->with('success', __('Transfer booking request submitted successfully!'));
        } catch (\Exception $e) {
            Log::error('Failed to save transfer booking: '.$e->getMessage());

            return redirect()->back()->with('error', __('Failed to submit transfer booking. Please try again.'));
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
