<?php

namespace App\Services\Api\V1;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login($data)
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return [
                'status' => 'error',
                'message' => 'Invalid credentials',
            ];
        }
        auth()->login($user);
        return [
            'status' => 'success',
            'message' => 'Login successful',
            'user' => $user,
        ];
    }

    public function register($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);
        auth()->login($user);
        return [
            'status' => 'success',
            'message' => 'Register successful',
            'user' => $user,
        ];
    }
}
