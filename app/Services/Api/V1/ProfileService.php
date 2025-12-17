<?php

namespace App\Services\Api\V1;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ProfileService
{
    public function profile($user)
    {
        return [
            'status' => 'success',
            'message' => 'Profile retrieved successfully',
            'user' => $user,
        ];
    }

    public function updateProfile($user, $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
            // Revoke all tokens (logout)
            $user->tokens()->delete();
        }

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            // Delete old image if exists
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }

            // Store new image
            $path = $data['image']->store('uploads/users', 'public');
            $data['image'] = $path;
        }

        $user->update($data);

        return [
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'user' => $user,
        ];
    }
}
