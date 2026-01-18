<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test admin user
        User::firstOrCreate(
            ['email' => 'admin@abrajstay.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('Admin@1234'),
                'phone' => '966501234567',
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create additional admin users for testing
        User::firstOrCreate(
            ['email' => 'superadmin@abrajstay.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('SuperAdmin@1234'),
                'phone' => '966501234568',
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
