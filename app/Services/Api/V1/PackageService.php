<?php

namespace App\Services\Api\V1;

use App\Models\Package;
use App\Models\PackageContact;
use Illuminate\Support\Facades\Auth;

class PackageService
{
    /**
     * Get all packages.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllPackages()
    {
        return Package::all();
    }

    /**
     * Get package by ID.
     *
     * @param int $id
     * @return \App\Models\Package
     */
    public function getPackageById($id)
    {
        return Package::findOrFail($id);
    }

    /**
     * Create a new package inquiry/contact.
     *
     * @param array $data
     * @return \App\Models\PackageContact
     */
    public function createPackageContact(array $data)
    {
        if (Auth::guard('sanctum')->check()) {
            $user = Auth::guard('sanctum')->user();
            $data['user_id'] = $user->id;
            $data['name'] = $data['name'] ?? $user->name;
            $data['email'] = $data['email'] ?? $user->email;
            $data['phone'] = $data['phone'] ?? $user->phone;
        }

        return PackageContact::create($data);
    }
}
