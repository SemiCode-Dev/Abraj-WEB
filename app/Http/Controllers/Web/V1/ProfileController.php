<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\V1\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        return view('Web.profile', [
            'user' => $user,
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            $user = Auth::user();

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ];

            // Update password if provided
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($user->image && Storage::disk('public')->exists($user->image)) {
                    Storage::disk('public')->delete($user->image);
                }

                // Store new image
                $imagePath = $request->file('image')->store('users', 'public');
                $data['image'] = $imagePath;
            }

            $user->update($data);

            return redirect()->route('profile')
                ->with('success', __('Profile updated successfully!'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to update profile: '.$e->getMessage());

            return redirect()->back()
                ->with('error', __('Failed to update profile. Please try again.'));
        }
    }
}
