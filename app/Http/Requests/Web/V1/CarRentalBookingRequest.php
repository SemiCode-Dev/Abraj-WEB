<?php

namespace App\Http\Requests\Web\V1;

use Illuminate\Foundation\Http\FormRequest;

class CarRentalBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone_country_code' => ['required', 'string', 'max:10'],
            'phone' => ['required', 'string', 'max:11'],
            'destination_country_id' => ['required', 'exists:countries,id'],
            'destination_city_id' => ['required'],
            'pickup_date' => ['required', 'date', 'after_or_equal:today'],
            'pickup_time' => ['required'],
            'return_date' => ['required', 'date', 'after:pickup_date'],
            'return_time' => ['required'],
            'driver_option' => ['required', 'string', 'in:with_driver,without_driver'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];

        if (auth()->check()) {
            unset($rules['name'], $rules['email'], $rules['phone'], $rules['phone_country_code']);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => __('Name is required.'),
            'email.required' => __('Email is required.'),
            'email.email' => __('Email must be a valid email address.'),
            'phone.required' => __('Phone number is required.'),
            'phone_country_code.required' => __('Country code is required.'),
            'destination_country_id.required' => __('Destination country is required.'),
            'destination_country_id.exists' => __('Selected destination country is invalid.'),
            'destination_city_id.required' => __('Destination city is required.'),
            'destination_city_id.exists' => __('Selected destination city is invalid.'),
            'pickup_date.required' => __('Pickup date is required.'),
            'pickup_date.after_or_equal' => __('Pickup date must be today or later.'),
            'pickup_time.required' => __('Pickup time is required.'),
            'return_date.required' => __('Return date is required.'),
            'return_date.after' => __('Return date must be after pickup date.'),
            'return_time.required' => __('Return time is required.'),
            'driver_option.required' => __('Driver option is required.'),
            'driver_option.in' => __('Selected driver option is invalid.'),
        ];
    }
}
