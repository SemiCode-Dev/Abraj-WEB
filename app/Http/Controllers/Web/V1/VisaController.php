<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\V1\VisaBookingRequest;
use App\Models\Country;
use App\Models\VisaBooking;
use Illuminate\Support\Facades\Log;

class VisaController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('name')->get();

        return view('Web.visa', [
            'countries' => $countries,
        ]);
    }

    public function book(VisaBookingRequest $request)
    {
        try {
            $user = auth()->user();

            VisaBooking::create([
                'user_id' => $user?->id,
                'name' => $user ? $user->name : $request->name,
                'phone_country_code' => $user ? ($user->phone_country_code ?? '966') : $request->phone_country_code,
                'phone' => $user ? ($user->phone ?? '') : $request->phone,
                'visa_type' => $request->visa_type,
                'country_id' => $request->country_id,
                'duration' => $request->duration,
                'passport_number' => $request->passport_number ?? null,
                'comment' => $request->comment,
            ]);

            return redirect()->back()->with('success', __('Visa service request submitted successfully!'));
        } catch (\Exception $e) {
            Log::error('Failed to save visa booking: '.$e->getMessage());

            return redirect()->back()->with('error', __('Failed to submit visa service request. Please try again.'));
        }
    }
}
