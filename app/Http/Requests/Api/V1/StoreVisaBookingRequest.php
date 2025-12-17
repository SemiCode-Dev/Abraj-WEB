<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreVisaBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isRequired = Auth::guard('sanctum')->check() ? 'nullable' : 'required';
        // Based on nullable fields in migration, but we typically want contact info.
        // I'll keep them nullable to match schema but ideally this should be stricter.
        return [
            'name' => [$isRequired, 'string', 'max:255'],
            'phone_country_code' => ['nullable', 'string', 'max:10'],
            'phone' => [$isRequired, 'string', 'max:255'],
            'visa_type' => ['nullable', 'string', 'max:255'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'duration' => ['nullable', 'integer'],
            'passport_number' => ['nullable', 'string', 'max:255'],
            'comment' => ['nullable', 'string'],
        ];
    }
}
