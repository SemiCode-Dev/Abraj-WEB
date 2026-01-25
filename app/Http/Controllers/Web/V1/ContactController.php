<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Display the contact form.
     */
    public function index()
    {
        return view('Web.contact');
    }

    /**
     * Store a contact message.
     */
    public function store(Request $request)
    {
        $validationRules = [
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ];

        if (!auth()->check()) {
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

        $request->validate($validationRules);

        try {
            $user = auth()->user();

            $countryCode = $user ? ($user->phone_country_code ?? '966') : $request->phone_country_code;
            if ($countryCode && !str_starts_with($countryCode, '+')) {
                $countryCode = '+' . $countryCode;
            }

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
