<?php

namespace App\Http\Requests\Web\V1;

use Illuminate\Foundation\Http\FormRequest;

class PackageContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'message' => ['nullable', 'string', 'max:1000'],
        ];

        if (!auth()->check()) {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['email'] = ['required', 'string', 'email', 'max:255'];
            $rules['phone_country_code'] = ['required', 'string', 'max:10'];
            $rules['phone'] = [
                'required', 
                'string', 
                function ($attribute, $value, $fail) {
                    $countryCode = strtolower(str_replace('+', '', $this->phone_country_code));
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

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => __('Name is required.'),
            'email.required' => __('Email is required.'),
            'email.email' => __('Email must be a valid email address.'),
            'phone.required' => __('Phone is required.'),
            'phone_country_code.required' => __('Country code is required.'),
            'message.max' => __('Message must not exceed 1000 characters.'),
        ];
    }
}
