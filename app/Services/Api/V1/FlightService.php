<?php

namespace App\Services\Api\V1;

use App\Models\FlightBooking;
use Illuminate\Support\Facades\Auth;

class FlightService
{
    /**
     * Create a new flight booking.
     *
     * @param array $data
     * @return \App\Models\FlightBooking
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

        return FlightBooking::create($data);
    }
}
