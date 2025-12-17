<?php

namespace App\Services\Api\V1;

use App\Models\CarRentalBooking;
use Illuminate\Support\Facades\Auth;

class CarRentalService
{
    /**
     * Create a new car rental booking.
     *
     * @param array $data
     * @return \App\Models\CarRentalBooking
     */
    public function createBooking(array $data)
    {
        if (Auth::guard('sanctum')->check()) {
            $user = Auth::guard('sanctum')->user();
            $data['user_id'] = $user->id;
            $data['name'] = $data['name'] ?? $user->name;
            $data['email'] = $data['email'] ?? $user->email;
            $data['phone'] = $data['phone'] ?? $user->phone;
        }

        return CarRentalBooking::create($data);
    }
}
