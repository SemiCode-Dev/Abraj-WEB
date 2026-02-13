<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Submit contact form
     * If user is authenticated, uses their data automatically
     * If not, requires name, email, phone
     */
    public function submit(Request $request)
    {
        // Validation rules - same as Web
        $validationRules = [
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ];

        // If user is NOT authenticated, require personal info
        if (!auth('sanctum')->check()) {
            $validationRules['name'] = 'required|string|max:255';
            $validationRules['email'] = 'required|email|max:255';
            $validationRules['phone_country_code'] = 'required|string|max:10';
            $validationRules['phone'] = [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($request) {
                    $countryCode = strtolower(str_replace('+', '', $request->phone_country_code));
                    $lengths = [
                        '966' => 9,  // SA
                        '20' => 11,  // EG
                        '1' => 10,   // US
                        '971' => 9,  // AE
                        '965' => 8,  // KW
                        '973' => 8,  // BH
                        '974' => 8,  // QA
                        '968' => 8,  // OM
                        '962' => 9,  // JO
                        '961' => 8,  // LB
                    ];

                    $expectedLength = $lengths[$countryCode] ?? null;
                    $digitsOnly = preg_replace('/[^0-9]/', '', $value);

                    if ($expectedLength && strlen($digitsOnly) !== $expectedLength) {
                        $fail(__('The phone number must be exactly :length digits for the selected country.', ['length' => $expectedLength]));
                    }
                }
            ];
        }

        // Validate request
        $validated = $request->validate($validationRules);

        try {
            // Get authenticated user (if exists)
            $user = auth('sanctum')->user();

            // Prepare country code
            $countryCode = $user ? ($user->phone_country_code ?? '966') : $request->phone_country_code;
            if ($countryCode && !str_starts_with($countryCode, '+')) {
                $countryCode = '+' . $countryCode;
            }

            // Create contact message - use user data if authenticated
            ContactMessage::create([
                'user_id' => $user?->id,
                'name' => $user ? $user->name : $request->name,
                'email' => $user ? $user->email : $request->email,
                'phone_country_code' => $countryCode,
                'phone' => $user ? $user->phone : $request->phone,
                'subject' => $request->subject,
                'message' => $request->message,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => __('Message sent successfully! Thank you for contacting us.')
            ]);

        } catch (\Exception $e) {
            Log::error('Contact Form Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => __('Failed to send message. Please try again later.')
            ], 500);
        }
    }
}

