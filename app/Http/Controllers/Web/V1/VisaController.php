<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\V1\VisaBookingRequest;
use App\Models\Country;
use App\Models\VisaBooking;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingRequestMail;

class VisaController extends Controller
{
    public function index()
    {
        if (app()->getLocale() == 'ar') {
            $countries = Country::orderBy('name_ar')->get();
        } else {
            $countries = Country::orderBy('name')->get();
        }

        return view('Web.visa', [
            'countries' => $countries,
        ]);
    }

    public function book(VisaBookingRequest $request)
    {
        try {
            $user = auth()->user();

            $countryCode = $user ? ($user->phone_country_code ?? '966') : $request->phone_country_code;
            if ($countryCode && !str_starts_with($countryCode, '+')) {
                $countryCode = '+' . $countryCode;
            }

            VisaBooking::create([
                'user_id' => $user?->id,
                'name' => $user ? $user->name : $request->name,
                'phone_country_code' => $countryCode,
                'phone' => $user ? ($user->phone ?? '') : $request->phone,
                'visa_type' => $request->visa_type,
                'country_id' => $request->country_id,
                'nationality_id' => $request->nationality_id,
                'duration' => $request->duration,
                'passport_number' => $request->passport_number ?? null,
                'comment' => $request->comment,
            ]);

            // Send Email Notification
            try {
                $country = Country::find($request->country_id);
                $nationality = Country::find($request->nationality_id);

                $mailData = [
                    'name' => $user ? $user->name : $request->name,
                    'phone' => $countryCode . ($user ? ($user->phone ?? '') : $request->phone),
                    'visa_type' => $request->visa_type,
                    'country' => $country ? ($country->name_en ?? $country->name) : $request->country_id,
                    'nationality' => $nationality ? ($nationality->name_en ?? $nationality->name) : $request->nationality_id,
                    'duration' => $request->duration,
                    'passport_number' => $request->passport_number ?? null,
                    'comment' => $request->comment,
                ];

                Mail::to('support@abrajstay.com')->send(new BookingRequestMail($mailData, 'Visa Booking'));
            } catch (\Exception $e) {
                Log::error('Failed to send visa booking email: ' . $e->getMessage());
            }

            return redirect()->back()->with('success', __('Visa service request submitted successfully!'));
        } catch (\Exception $e) {
            Log::error('Failed to save visa booking: '.$e->getMessage());

            return redirect()->back()->with('error', __('Failed to submit visa service request. Please try again.'));
        }
    }
}
