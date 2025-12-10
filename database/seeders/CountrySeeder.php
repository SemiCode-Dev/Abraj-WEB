<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            [
                'name' => 'Saudi Arabia',
                'name_ar' => 'المملكة العربية السعودية',
                'code' => 'SA',
            ],
            [
                'name' => 'United Arab Emirates',
                'name_ar' => 'الإمارات العربية المتحدة',
                'code' => 'AE',
            ],
            [
                'name' => 'Egypt',
                'name_ar' => 'مصر',
                'code' => 'EG',
            ],
            [
                'name' => 'Jordan',
                'name_ar' => 'الأردن',
                'code' => 'JO',
            ],
            [
                'name' => 'Lebanon',
                'name_ar' => 'لبنان',
                'code' => 'LB',
            ],
        ];

        foreach ($countries as $country) {
            Country::firstOrCreate(
                ['code' => $country['code']],
                $country
            );
        }
    }
}
