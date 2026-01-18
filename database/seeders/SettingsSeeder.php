<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->updateOrInsert(
            ['key' => 'commission_percentage'],
            [
                'value' => '0',
                'type' => 'float',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
