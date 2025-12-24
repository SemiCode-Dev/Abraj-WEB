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

        $rules['name'] = ['required', 'string', 'max:255'];
        $rules['email'] = ['required', 'string', 'email', 'max:255'];
        $rules['phone_country_code'] = ['required', 'string', 'max:10'];
        $rules['phone'] = ['required', 'string', 'max:11'];

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
