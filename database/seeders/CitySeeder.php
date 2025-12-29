<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $saudiArabia = Country::where('code', 'SA')->first();

        if (! $saudiArabia) {
            $this->command->error('Saudi Arabia country not found. Please run CountrySeeder first.');

            return;
        }

        $cities = [
            ['Code' => '100218', 'Name' => 'Abha', 'Name_ar' => 'أبها'],
            ['Code' => '160981', 'Name' => 'Afif', 'Name_ar' => 'عفيف'],
            ['Code' => '269830', 'Name' => 'Ahad Rafidah', 'Name_ar' => 'أحد رفيدة'],
            ['Code' => '100164', 'Name' => 'Al Bahah', 'Name_ar' => 'الباحة'],
            ['Code' => '100813', 'Name' => 'Al Bukayriyah', 'Name_ar' => 'البكيرية'],
            ['Code' => '418212', 'Name' => 'Al Hofuf', 'Name_ar' => 'الهفوف'],
            ['Code' => '100491', 'Name' => 'Al Khobar', 'Name_ar' => 'الخبر'],
            ['Code' => '160982', 'Name' => 'Al Lith,   Makkah', 'Name_ar' => 'الليث، مكة'],
            ['Code' => '155776', 'Name' => 'Al Madinah Province', 'Name_ar' => 'منطقة المدينة المنورة'],
            ['Code' => '160980', 'Name' => "Al Majma'ah", 'Name_ar' => 'المجمعة'],
            ['Code' => '160726', 'Name' => 'Al Qunfudhah,   Makkah', 'Name_ar' => 'القنفذة، مكة'],
            ['Code' => '105149', 'Name' => 'Al Wajh', 'Name_ar' => 'الوجه'],
            ['Code' => '155772', 'Name' => 'Al-Ahsa', 'Name_ar' => 'الأحساء'],
            ['Code' => '100304', 'Name' => 'Al-Hofuf', 'Name_ar' => 'الهفوف'],
            ['Code' => '105080', 'Name' => 'Al-Kharj', 'Name_ar' => 'الخرج'],
            ['Code' => '100977', 'Name' => 'AlUla', 'Name_ar' => 'العلا'],
            ['Code' => '396149', 'Name' => 'Ar Rass', 'Name_ar' => 'الرس'],
            ['Code' => '150693', 'Name' => 'Arar', 'Name_ar' => 'عرعر'],
            ['Code' => '407518', 'Name' => 'Baljurashi', 'Name_ar' => 'بلجرشي'],
            ['Code' => '112062', 'Name' => 'Buraydah', 'Name_ar' => 'بريدة'],
            ['Code' => '116137', 'Name' => 'Dammam', 'Name_ar' => 'الدمام'],
            ['Code' => '116182', 'Name' => 'Dhahran', 'Name_ar' => 'الظهران'],
            ['Code' => '117881', 'Name' => 'Farasan Island', 'Name_ar' => 'جزيرة فرسان'],
            ['Code' => '151377', 'Name' => 'Gassim', 'Name_ar' => 'القصيم'],
            ['Code' => '119783', 'Name' => 'Gizan / Jizan', 'Name_ar' => 'جيزان'],
            ['Code' => '151001', 'Name' => 'Gurayat', 'Name_ar' => 'القريات'],
            ['Code' => '120076', 'Name' => 'Hafar Al-Batin', 'Name_ar' => 'حفر الباطن'],
            ['Code' => '418206', 'Name' => 'Hafr Al Baten', 'Name_ar' => 'حفر الباطن'],
            ['Code' => '418207', 'Name' => 'hafr al batin', 'Name_ar' => 'حفر الباطن'],
            ['Code' => '120451', 'Name' => 'Hail', 'Name_ar' => 'حائل'],
            ['Code' => '417936', 'Name' => 'Hanak', 'Name_ar' => 'حَنك'],
            ['Code' => '378145', 'Name' => 'Haradh', 'Name_ar' => 'حرض'],
            ['Code' => '122187', 'Name' => 'Jeddah', 'Name_ar' => 'جدة'],
            ['Code' => '151466', 'Name' => 'Jouf', 'Name_ar' => 'الجوف'],
            ['Code' => '122789', 'Name' => 'Jubail', 'Name_ar' => 'الجبيل'],
            ['Code' => '104104', 'Name' => 'Khafji', 'Name_ar' => 'الخفجي'],
            ['Code' => '114096', 'Name' => 'KHAMIS MUSHAIT', 'Name_ar' => 'خميس مشيط'],
            ['Code' => '417544', 'Name' => 'Khuff', 'Name_ar' => 'الخُف'],
            ['Code' => '103965', 'Name' => 'King Abdullah Economic City', 'Name_ar' => 'مدينة الملك عبدالله الاقتصادية'],
            ['Code' => '127340', 'Name' => 'Madinah', 'Name_ar' => 'المدينة المنورة'],
            ['Code' => '127891', 'Name' => 'Makkah/Mecca', 'Name_ar' => 'مكة المكرمة'],
            ['Code' => '129446', 'Name' => 'Nadschran', 'Name_ar' => 'نجران'],
            ['Code' => '417572', 'Name' => 'Najran', 'Name_ar' => 'نجران'],
            ['Code' => '150906', 'Name' => 'Nejran', 'Name_ar' => 'نجران'],
            ['Code' => '289876', 'Name' => 'Neom Bay (NUM)', 'Name_ar' => 'خليج نيوم'],
            ['Code' => '151316', 'Name' => 'Qaisumah', 'Name_ar' => 'القيصومة'],
            ['Code' => '103469', 'Name' => 'Qurayyat', 'Name_ar' => 'القريات'],
            ['Code' => '103093', 'Name' => 'Rabigh', 'Name_ar' => 'رابغ'],
            ['Code' => '151317', 'Name' => 'Rafha', 'Name_ar' => 'رفحاء'],
            ['Code' => '147536', 'Name' => 'Riyadh', 'Name_ar' => 'الرياض'],
            ['Code' => '273156', 'Name' => 'Sakaka', 'Name_ar' => 'سكاكا'],
            ['Code' => '106312', 'Name' => 'Shaqraa', 'Name_ar' => 'شقراء'],
            ['Code' => '205075', 'Name' => 'Sharma', 'Name_ar' => 'شرما'],
            ['Code' => '150939', 'Name' => 'Sharurah', 'Name_ar' => 'شرورة'],
            ['Code' => '139889', 'Name' => 'Tabuk', 'Name_ar' => 'تبوك'],
            ['Code' => '139663', 'Name' => 'Taif', 'Name_ar' => 'الطائف'],
            ['Code' => '417825', 'Name' => 'Test City', 'Name_ar' => 'مدينة تجريبية'],
            ['Code' => '418231', 'Name' => 'The Red Sea', 'Name_ar' => 'البحر الأحمر'],
            ['Code' => '160883', 'Name' => 'Thuwal', 'Name_ar' => 'ثول'],
            ['Code' => '151034', 'Name' => 'Turaif', 'Name_ar' => 'طريف'],
            ['Code' => '407610', 'Name' => 'Umluj', 'Name_ar' => 'أملج'],
            ['Code' => '299920', 'Name' => 'Umm Lajj', 'Name_ar' => 'أملج'],
            ['Code' => '148373', 'Name' => 'Unayzah', 'Name_ar' => 'عنيزة'],
            ['Code' => '306276', 'Name' => 'Wadi-Ad-Dawasir', 'Name_ar' => 'وادي الدواسر'],
            ['Code' => '151503', 'Name' => 'Wedjh', 'Name_ar' => 'الوجه'],
            ['Code' => '143794', 'Name' => 'Yanbu', 'Name_ar' => 'ينبع'],
        ];

        foreach ($cities as $city) {
            DB::table('cities')->updateOrInsert(
                [
                    'code' => $city['Code'],
                    'country_id' => $saudiArabia->id,
                ],
                [
                    'name' => $city['Name'],
                    'name_ar' => $city['Name_ar'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
