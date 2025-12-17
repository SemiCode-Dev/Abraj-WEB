<?php

namespace App\Services\Api\V1;

use App\Models\TransferBooking;
use Illuminate\Support\Facades\Auth;

class TransferService
{
    /**
     * Create a new transfer booking.
     *
     * @param array $data
     * @return \App\Models\TransferBooking
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

        return TransferBooking::create($data);
    }
}
