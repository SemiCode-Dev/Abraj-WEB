<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Api\V1\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function profile()
    {
        $data = $this->profileService->profile(auth()->user());
        return response()->json($data, 200);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . auth()->id(),
            'phone' => 'sometimes|string|unique:users,phone,' . auth()->id(),
            'password' => 'sometimes|string|min:6',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $this->profileService->updateProfile(auth()->user(), $request->all());
        return response()->json($data, 200);
    }
}
