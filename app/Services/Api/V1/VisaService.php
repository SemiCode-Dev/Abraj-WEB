<?php

namespace App\Services\Api\V1;

use App\Models\VisaBooking;
use Illuminate\Support\Facades\Auth;

class VisaService
{
    /**
     * Create a new visa booking.
     *
     * @param array $data
     * @return \App\Models\VisaBooking
     */
    public function createBooking(array $data)
    {
        if (Auth::guard('sanctum')->check()) {
            $user = Auth::guard('sanctum')->user();
            $data['user_id'] = $user->id;
            $data['name'] = $data['name'] ?? $user->name;
            $data['phone'] = $data['phone'] ?? $user->phone;
        }

        return VisaBooking::create($data);
    }
}
