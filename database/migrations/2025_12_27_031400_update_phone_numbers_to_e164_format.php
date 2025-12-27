<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all existing phone numbers to split into country code and number
        DB::table('users')->whereNotNull('phone')->chunk(100, function ($users) {
            foreach ($users as $user) {
                $phone = $user->phone;
                $countryCode = $user->phone_country_code ?? '20';
                
                // Skip if already processed (phone doesn't start with + or digit)
                if (empty($phone)) {
                    continue;
                }
                
                // Map country codes to dial codes
                $dialCodes = [
                    'EG' => '20', 'SA' => '966', 'AE' => '971', 'KW' => '965',
                    'QA' => '974', 'OM' => '968', 'BH' => '973', 'JO' => '962',
                    'LB' => '961', 'IQ' => '964', 'SY' => '963', 'YE' => '967',
                    'PS' => '970', 'SD' => '249', 'DZ' => '213', 'MA' => '212',
                    'TN' => '216', 'LY' => '218',
                ];
                
                // Get dial code
                if (isset($dialCodes[strtoupper($countryCode)])) {
                    $dialCode = $dialCodes[strtoupper($countryCode)];
                } elseif (is_numeric($countryCode)) {
                    $dialCode = $countryCode;
                } else {
                    $dialCode = '20'; // Default to Egypt
                }
                
                // Remove any existing + or country code from phone
                $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                
                // Remove leading 0 if present
                if (str_starts_with($cleanPhone, '0')) {
                    $cleanPhone = substr($cleanPhone, 1);
                }
                
                // Remove dial code if it's at the start
                if (str_starts_with($cleanPhone, $dialCode)) {
                    $cleanPhone = substr($cleanPhone, strlen($dialCode));
                }
                
                // Update: phone_country_code = +20, phone = 1141367100
                DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'phone_country_code' => '+' . $dialCode,
                        'phone' => $cleanPhone,
                    ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove + from phone_country_code
        DB::table('users')->whereNotNull('phone_country_code')->chunk(100, function ($users) {
            foreach ($users as $user) {
                $code = str_replace('+', '', $user->phone_country_code);
                
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['phone_country_code' => $code]);
            }
        });
    }
};
