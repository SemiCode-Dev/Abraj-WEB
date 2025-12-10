<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'title' => 'Makkah & Madinah Umrah Package',
                'title_ar' => 'باقة العمرة مكة والمدينة',
                'description' => 'Complete Umrah package with 5-star hotel accommodation, transportation, and guided tours.',
                'description_ar' => 'باقة عمرة كاملة مع إقامة في فندق 5 نجوم، النقل، وجولات إرشادية.',
                'price' => 2500.00,
                'duration' => '7 Days / 6 Nights',
                'duration_ar' => '7 أيام / 6 ليال',
                'details' => "• Round trip flights\n• 5-star hotel accommodation\n• Daily breakfast\n• Transportation to holy sites\n• Professional guide\n• Visa assistance",
                'details_ar' => "• تذاكر طيران ذهاب وإياب\n• إقامة في فندق 5 نجوم\n• إفطار يومي\n• النقل إلى المواقع المقدسة\n• مرشد محترف\n• مساعدة في التأشيرة",
                'is_active' => true,
            ],
            [
                'title' => 'Riyadh City Tour Package',
                'title_ar' => 'باقة جولة مدينة الرياض',
                'description' => 'Explore the capital city with visits to historical sites, modern attractions, and cultural experiences.',
                'description_ar' => 'استكشف العاصمة مع زيارة المواقع التاريخية والمعالم الحديثة والتجارب الثقافية.',
                'price' => 1200.00,
                'duration' => '4 Days / 3 Nights',
                'duration_ar' => '4 أيام / 3 ليال',
                'details' => "• Hotel accommodation\n• City tour guide\n• Entrance fees\n• Transportation\n• Daily breakfast",
                'details_ar' => "• إقامة في الفندق\n• مرشد جولات المدينة\n• رسوم الدخول\n• النقل\n• إفطار يومي",
                'is_active' => true,
            ],
            [
                'title' => 'Jeddah Beach Resort Package',
                'title_ar' => 'باقة منتجع جدة الساحلي',
                'description' => 'Relaxing beach vacation with luxury resort accommodation and water activities.',
                'description_ar' => 'عطلة شاطئية مريحة مع إقامة في منتجع فاخر وأنشطة مائية.',
                'price' => 1800.00,
                'duration' => '5 Days / 4 Nights',
                'duration_ar' => '5 أيام / 4 ليال',
                'details' => "• Beachfront resort\n• All meals included\n• Water sports activities\n• Spa access\n• Airport transfers",
                'details_ar' => "• منتجع على الشاطئ\n• جميع الوجبات مشمولة\n• أنشطة رياضات مائية\n• الوصول إلى السبا\n• النقل من/إلى المطار",
                'is_active' => true,
            ],
            [
                'title' => 'AlUla Heritage & Culture Package',
                'title_ar' => 'باقة العلا التراثية والثقافية',
                'description' => 'Discover the ancient wonders of AlUla with visits to historical sites, rock formations, and cultural experiences.',
                'description_ar' => 'اكتشف عجائب العلا القديمة مع زيارة المواقع التاريخية والتكوينات الصخرية والتجارب الثقافية.',
                'price' => 2200.00,
                'duration' => '4 Days / 3 Nights',
                'duration_ar' => '4 أيام / 3 ليال',
                'details' => "• Luxury desert camp accommodation\n• Guided tours to Hegra (Mada'in Saleh)\n• Old Town AlUla visit\n• Elephant Rock and Maraya Concert Hall\n• Traditional meals\n• Transportation",
                'details_ar' => "• إقامة في مخيم صحراوي فاخر\n• جولات إرشادية إلى الحجر (مدائن صالح)\n• زيارة البلدة القديمة في العلا\n• صخرة الفيل وقاعة مرايا للحفلات\n• وجبات تقليدية\n• النقل",
                'is_active' => true,
            ],
            [
                'title' => 'Taif Mountain Escape Package',
                'title_ar' => 'باقة هروب جبال الطائف',
                'description' => 'Escape to the cool mountains of Taif with beautiful gardens, historical sites, and refreshing climate.',
                'description_ar' => 'اهرب إلى جبال الطائف الباردة مع الحدائق الجميلة والمواقع التاريخية والمناخ المنعش.',
                'price' => 1500.00,
                'duration' => '3 Days / 2 Nights',
                'duration_ar' => '3 أيام / ليلتان',
                'details' => "• Mountain resort accommodation\n• Shubra Palace visit\n• Al Rudaf Park and gardens\n• Rose farms tour\n• Cable car experience\n• Traditional breakfast",
                'details_ar' => "• إقامة في منتجع جبلي\n• زيارة قصر شبرا\n• حديقة الروداف والحدائق\n• جولة مزارع الورد\n• تجربة التلفريك\n• إفطار تقليدي",
                'is_active' => true,
            ],
            [
                'title' => 'Abha Asir Region Package',
                'title_ar' => 'باقة منطقة عسير أبها',
                'description' => 'Experience the stunning landscapes of Asir region with its mountains, valleys, and traditional architecture.',
                'description_ar' => 'استمتع بالمناظر الطبيعية الخلابة لمنطقة عسير مع جبالها ووديانها وعمارتها التقليدية.',
                'price' => 1700.00,
                'duration' => '4 Days / 3 Nights',
                'duration_ar' => '4 أيام / 3 ليال',
                'details' => "• Mountain hotel accommodation\n• Al Soudah Park visit\n• Rijal Almaa heritage village\n• Cable car to mountain peaks\n• Traditional Asir architecture tour\n• Local cuisine experiences",
                'details_ar' => "• إقامة في فندق جبلي\n• زيارة منتزه السودة\n• قرية رجال ألمع التراثية\n• تلفريك إلى قمم الجبال\n• جولة العمارة العسيرية التقليدية\n• تجارب المأكولات المحلية",
                'is_active' => true,
            ],
            [
                'title' => 'Dammam & Eastern Province Tour',
                'title_ar' => 'باقة جولة الدمام والمنطقة الشرقية',
                'description' => 'Explore the Eastern Province with its modern cities, heritage sites, and beautiful coastline.',
                'description_ar' => 'استكشف المنطقة الشرقية مع مدنها الحديثة ومواقعها التراثية وساحلها الجميل.',
                'price' => 1400.00,
                'duration' => '3 Days / 2 Nights',
                'duration_ar' => '3 أيام / ليلتان',
                'details' => "• City center hotel\n• King Fahd Causeway visit\n• Heritage Village tour\n• Corniche walk\n• Half Moon Bay beach\n• Traditional markets",
                'details_ar' => "• فندق وسط المدينة\n• زيارة جسر الملك فهد\n• جولة القرية التراثية\n• نزهة الكورنيش\n• شاطئ نصف القمر\n• الأسواق التقليدية",
                'is_active' => true,
            ],
            [
                'title' => 'Extended Umrah & Tourism Package',
                'title_ar' => 'باقة العمرة الممتدة والسياحة',
                'description' => 'Complete 14-day package combining Umrah pilgrimage with tourism to multiple Saudi cities.',
                'description_ar' => 'باقة كاملة لمدة 14 يوماً تجمع بين العمرة والسياحة في عدة مدن سعودية.',
                'price' => 4500.00,
                'duration' => '14 Days / 13 Nights',
                'duration_ar' => '14 يوماً / 13 ليلة',
                'details' => "• Round trip flights\n• 5-star hotels in Makkah, Madinah, and Riyadh\n• All meals included\n• Transportation between cities\n• Professional Umrah guide\n• City tours in Riyadh\n• Visa processing\n• Travel insurance",
                'details_ar' => "• تذاكر طيران ذهاب وإياب\n• فنادق 5 نجوم في مكة والمدينة والرياض\n• جميع الوجبات مشمولة\n• النقل بين المدن\n• مرشد عمرة محترف\n• جولات مدينة في الرياض\n• معالجة التأشيرة\n• تأمين السفر",
                'is_active' => true,
            ],
            [
                'title' => 'Yanbu Industrial & Beach Package',
                'title_ar' => 'باقة ينبع الصناعية والشاطئية',
                'description' => 'Discover Yanbu with its industrial heritage, beautiful beaches, and Red Sea activities.',
                'description_ar' => 'اكتشف ينبع مع تراثها الصناعي وشواطئها الجميلة وأنشطة البحر الأحمر.',
                'price' => 1300.00,
                'duration' => '3 Days / 2 Nights',
                'duration_ar' => '3 أيام / ليلتان',
                'details' => "• Beachfront hotel\n• Yanbu Old Town visit\n• Red Sea diving or snorkeling\n• Industrial city tour\n• Beach activities\n• Seafood dining",
                'details_ar' => "• فندق على الشاطئ\n• زيارة البلدة القديمة في ينبع\n• غوص أو سباحة في البحر الأحمر\n• جولة المدينة الصناعية\n• أنشطة شاطئية\n• تناول المأكولات البحرية",
                'is_active' => true,
            ],
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}
