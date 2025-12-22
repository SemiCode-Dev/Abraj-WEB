<?php

namespace App\Http\Requests\Web\V1;

use Illuminate\Foundation\Http\FormRequest;

class ReservationReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'hotel_id' => ['required', 'string'],
            'booking_code' => ['required', 'string'],
            'check_in' => ['required', 'date'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests' => ['required', 'integer', 'min:1'],
            'terms' => ['required', 'accepted'],
        ];

        // If user is not authenticated, require name, email, and phone
        // If authenticated, these are optional (will use user's data if not provided)
        if (! auth()->check()) {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['email'] = ['required', 'string', 'email', 'max:255'];
            $rules['phone'] = ['required', 'string', 'max:20'];
        } else {
            // For authenticated users, these fields are optional
            $rules['name'] = ['nullable', 'string', 'max:255'];
            $rules['email'] = ['nullable', 'string', 'email', 'max:255'];
            $rules['phone'] = ['nullable', 'string', 'max:20'];
        }

        $rules['notes'] = ['nullable', 'string', 'max:1000'];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'hotel_id.required' => __('Hotel ID is required.'),
            'booking_code.required' => __('Booking code is required.'),
            'check_in.required' => __('Check-in date is required.'),
            'check_in.date' => __('Check-in must be a valid date.'),
            'check_out.required' => __('Check-out date is required.'),
            'check_out.date' => __('Check-out must be a valid date.'),
            'check_out.after' => __('Check-out date must be after check-in date.'),
            'guests.required' => __('Number of guests is required.'),
            'guests.integer' => __('Number of guests must be a number.'),
            'guests.min' => __('Number of guests must be at least 1.'),
            'name.required' => __('Name is required.'),
            'email.required' => __('Email is required.'),
            'email.email' => __('Please enter a valid email address.'),
            'phone.required' => __('Phone number is required.'),
            'terms.required' => __('You must accept the Terms and Conditions.'),
            'terms.accepted' => __('You must accept the Terms and Conditions.'),
        ];
    }
}
