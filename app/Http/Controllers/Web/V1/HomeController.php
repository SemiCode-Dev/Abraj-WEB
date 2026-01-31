<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Services\Api\V1\HotelApiService;
use App\Services\Api\V1\PaymentService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    protected HotelApiService $hotelApi;

    protected PaymentService $paymentService;

    public function __construct(HotelApiService $hotelApi, PaymentService $paymentService)
    {
        $this->hotelApi = $hotelApi;
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        set_time_limit(120); // Give more time for initial cache build
        // Cache payment data (doesn't change often)
        $data = Cache::remember('aps_payment_data', 1800, function () {
            return $this->paymentService->apsPayment();
        });

        // Major cities focus (Riyadh, Makkah, Madinah, Jeddah, Dammam)
        $majorCityNames = ['Riyadh', 'Makkah/Mecca', 'Madinah', 'Jeddah', 'Dammam', 'Al Khobar'];
        
        $cityCodes = City::whereIn('name', $majorCityNames)
            ->whereNotNull('code')
            ->where('code', '!=', '')
            ->limit(4)
            ->pluck('code')
            ->toArray();

        // If not enough major cities, get any cities with codes
        if (count($cityCodes) < 4) {
            $otherCodes = City::whereNotIn('name', $majorCityNames)
                ->whereNotNull('code')
                ->where('code', '!=', '')
                ->limit(6 - count($cityCodes))
                ->pluck('code')
                ->toArray();
            $cityCodes = array_merge($cityCodes, $otherCodes);
        }

        // If no cities in database, use empty array (will return empty hotels)
        if (empty($cityCodes)) {
            $cityCodes = [];
        }

        // Cache for 2 hours to improve performance (longer cache for homepage)
        $language = app()->getLocale() === 'ar' ? 'ar' : 'en';
        $cacheKey = 'featured_hotels_homepage_'.$language.'_'.md5(implode(',', $cityCodes));
        $response = Cache::remember($cacheKey, 7200, function () use ($cityCodes, $language) {
            try {
                if (empty($cityCodes)) {
                    return [
                        'Status' => [
                            'Code' => 200,
                            'Description' => 'Success',
                        ],
                        'Hotels' => [],
                    ];
                } else {
                    // Get all hotels from each city for Featured Hotels section (no limit per city, multiple pages)
                    $language = app()->getLocale() === 'ar' ? 'ar' : 'en';
                    $response = $this->hotelApi->getHotelsFromMultipleCities($cityCodes, true, null, $language, 5);

                    // Apply robust Hotel Name Translation if Arabic
                    if ($language === 'ar') {
                        try {
                            $translator = new \App\Services\HotelTranslationService();
                            if (isset($response['Hotels']) && is_array($response['Hotels'])) {
                                $response['Hotels'] = $translator->translateHotels($response['Hotels'], 20);
                            }
                        } catch (\Exception $e) {
                           Log::warning('Homepage translation failed: ' . $e->getMessage());
                        }
                    }

                    return $response;
                }
            } catch (\Exception $e) {
                Log::error('Failed to fetch featured hotels: '.$e->getMessage());

                return [
                    'Status' => [
                        'Code' => 500,
                        'Description' => 'Error',
                    ],
                    'Hotels' => [],
                ];
            }
        });

        $hotels = $response['Hotels'] ?? [];

        if (! is_array($hotels)) {
            $hotels = json_decode(json_encode($hotels), true);
        }

        // Shuffle to show variety
        shuffle($hotels);

        $hotels1 = array_slice($hotels, 0, 4);

        // Get all remaining hotels for Featured Hotels section (not limited to 3)
        $hotels2 = array_slice($hotels, 4);

        // Use cache for cities query
        $cities = Cache::remember('homepage_cities', 3600, function () {
            return City::whereNotNull('code')
                ->where('code', '!=', '')
                ->limit(10)
                ->get();
        });

        // Fetch countries from TBO API with caching (longer cache - 24 hours)
        $countries = Cache::remember('tbo_countries_'.app()->getLocale(), 86400, function () {
            try {
                // Fetch countries in the current locale language
                $language = app()->getLocale() === 'ar' ? 'ar' : 'en';
                $response = $this->hotelApi->getCountries($language);

                // Handle different possible response structures
                if (isset($response['CountryList']) && is_array($response['CountryList'])) {
                    $countryData = $response['CountryList'];
                } elseif (is_array($response) && isset($response[0])) {
                    $countryData = $response;
                } else {
                    $countryData = [];
                }

                // Manual Arabic Translation Interceptor
                if (app()->getLocale() === 'ar' && ! empty($countryData)) {
                    $countryData = $this->translateCountries($countryData);
                }
                
                return $countryData;
            } catch (\Exception $e) {
                Log::error('Failed to fetch countries from TBO API: '.$e->getMessage());
                return [];
            }
        });

        return view('Web.home', [
            'cities' => $cities,
            'hotels' => $hotels1,
            'hotels2' => $hotels2,
            'data' => $data,
            'countries' => $countries,
        ]);
    }

    public function about()
    {
        return view('Web.about');
    }

    public function privacy()
    {
        return view('Web.privacy');
    }

    public function cookies()
    {
        return view('Web.cookies');
    }

    public function terms()
    {
        return view('Web.terms');
    }

    /**
     * Helper method to translate countries to Arabic manually
     */
    private function translateCountries(array $countries): array
    {
        $arabicMap = [
            'SA' => 'المملكة العربية السعودية',
            'AE' => 'الإمارات العربية المتحدة',
            'EG' => 'مصر',
            'BH' => 'البحرين',
            'KW' => 'الكويت',
            'QA' => 'قطر',
            'OM' => 'عمان',
            'JO' => 'الأردن',
            'TR' => 'تركيا',
            'GB' => 'المملكة المتحدة',
            'US' => 'الولايات المتحدة',
            'FR' => 'فرنسا',
            'DE' => 'ألمانيا',
            'IT' => 'إيطاليا',
            'ES' => 'إسبانيا',
            'CH' => 'سويسرا',
            'MY' => 'ماليزيا',
            'ID' => 'إندونيسيا',
            'TH' => 'تايلاند',
            'MA' => 'المغرب',
            'LB' => 'لبنان',
            'DZ' => 'الجزائر',
            'TN' => 'تونس',
            'IQ' => 'العراق',
            'SD' => 'السودان',
            'YE' => 'اليمن',
            'SY' => 'سوريا',
            'PS' => 'فلسطين',
            'AT' => 'النمسا',
            'GR' => 'اليونان',
            'RU' => 'روسيا',
            'CN' => 'الصين',
            'JP' => 'اليابان',
            'KR' => 'كوريا الجنوبية',
            'IN' => 'الهند',
            'PK' => 'باكستان',
            'AU' => 'أستراليا',
            'CA' => 'كندا',
            'BR' => 'البرازيل',
            'AR' => 'الأرجنتين',
            'ZA' => 'جنوب أفريقيا',
            'NL' => 'هولندا',
            'BE' => 'بلجيكا',
            'SE' => 'السويد',
            'NO' => 'النرويج',
            'DK' => 'الدانمارك',
            'PT' => 'البرتغال',
            'IE' => 'أيرلندا',
            'MV' => 'المالديف',
            'MU' => 'موريشيوس',
            'PH' => 'الفلبين',
            'VN' => 'فيتنام',
            'SG' => 'سنغافورة',
            'LK' => 'سريلانكا',
            'NP' => 'نيبال',
            'BD' => 'بنجلاديش',
            'AF' => 'أفغانستان',
            'IR' => 'إيران',
            'AZ' => 'أذربيجان',
            'GE' => 'جورجيا',
            'AM' => 'أرمينيا',
            'KZ' => 'كازاخستان',
            'UZ' => 'أوزبكستان',
            'TM' => 'تركمانستان',
            'KG' => 'قيرغيزستان',
            'TJ' => 'طاجيكستان',
            'UA' => 'أوكرانيا',
            'BY' => 'بيلاروسيا',
            'PL' => 'بولندا',
            'CZ' => 'التشيك',
            'SK' => 'سلوفاكيا',
            'HU' => 'المجر',
            'RO' => 'رومانيا',
            'BG' => 'بلغاريا',
            'RS' => 'صربيا',
            'HR' => 'كرواتيا',
            'SI' => 'سلوفينيا',
            'BA' => 'البوسنة والهرسك',
            'ME' => 'الجبل الأسود',
            'MK' => 'مقيدونيا الشمالية',
            'AL' => 'ألبانيا',
            'CY' => 'قبرص',
            'MT' => 'مالطا',
            'IS' => 'أيسلندا',
            'FI' => 'فنلندا',
            'EE' => 'إستونيا',
            'LV' => 'اتفيا',
            'LT' => 'ليتوانيا',
            'LU' => 'لوكسمبورغ',
            'MC' => 'موناكو',
            'LI' => 'ليختنشتاين',
            'SM' => 'سان مارينو',
            'VA' => 'الفاتيكان',
            'AD' => 'أندورا',
            'MX' => 'المكسيك',
            'CO' => 'كولومبيا',
            'PE' => 'بيرو',
            'CL' => 'تشيلي',
            'VE' => 'فنزويلا',
            'EC' => 'الإكوادور',
            'BO' => 'بوليفيا',
            'UY' => 'أوروغواي',
            'PY' => 'باراغواي',
            'NZ' => 'نيوزيلندا',
            'FJ' => 'فيجي',
            'PG' => 'بابوا غينيا الجديدة',
            'NG' => 'نيجيريا',
            'ET' => 'إثيوبيا',
            'KE' => 'كينيا',
            'TZ' => 'تنزانيا',
            'UG' => 'أوغندا',
            'GH' => 'غانا',
            'CI' => 'ساحل العاج',
            'SN' => 'السنغال',
            'CM' => 'الكاميرون',
            'AO' => 'أنغولا',
            'ZM' => 'زامبيا',
            'ZW' => 'زيمبابوي',
            'BW' => 'بوتسوانا',
            'NA' => 'ناميبيا',
            'MZ' => 'موزمبيق',
            'MG' => 'مدغشقر',
            'SC' => 'سيشل',
            'SO' => 'الصومال',
            'DJ' => 'جيبوتي',
            'ER' => 'إريتريا',
            'LY' => 'ليبيا',
            'MR' => 'موريتانيا',
            'SL' => 'سيراليون',
            'LR' => 'ليبيريا',
            'GN' => 'غينيا',
            'GM' => 'غامبيا',
            'GW' => 'غينيا بيساو',
            'CV' => 'الرأس الأخضر',
            'ST' => 'ساو تومي وبرينسيبي',
            'GQ' => 'غينيا الاستوائية',
            'GA' => 'الغابون',
            'CG' => 'الكونغو',
            'CD' => 'الكونغو الديمقراطية',
            'CF' => 'أفريقيا الوسطى',
            'TD' => 'تشاد',
            'NE' => 'النيجر',
            'ML' => 'مالي',
            'BF' => 'بوركينا فاسو',
            'BJ' => 'بنين',
            'TG' => 'توغو',
            'RW' => 'رواندا',
            'BI' => 'بوروندي',
            'SS' => 'جنوب السودان',
            'LS' => 'ليسوتو',
            'SZ' => 'إسواتيني',
            'KM' => 'جزر القمر',
            'RE' => 'ريونيون',
            'YT' => 'مايوت',
            'SH' => 'سانت هيلانة',
            'CU' => 'كوبا',
            'JM' => 'جامايكا',
            'HT' => 'هايتي',
            'DO' => 'الدومينيكان',
            'BS' => 'جزر البهاما',
            'BB' => 'بربادوس',
            'TT' => 'ترينيداد وتوباغو',
            'CR' => 'كوستاريكا',
            'PA' => 'بنما',
            'BZ' => 'بيليز',
            'GT' => 'غواتيمالا',
            'HN' => 'هندوراس',
            'SV' => 'السلفادور',
            'NI' => 'نيكاراغوا',
            'GL' => 'جرينلاند',
            'FO' => 'جزر فارو',
            'SJ' => 'سفالبارد',
            'GI' => 'جبل طارق',
            'RS' => 'صربيا',
            'San Marino' => 'سان مارينو',
            'Sao Tome & Principe' => 'ساو تومي وبرينسيبي',
            'Senegal' => 'السنغال',
            'Sierra Leone' => 'سيراليون',
            'Singapore' => 'سنغافورة',
            'Sint Maarten (Dutch part)' => 'سينت مارتن',
            'Slovakia' => 'سلوفاكيا',
            'Slovenia' => 'سلوفينيا',
            'Solomon Islands' => 'جزر سليمان',
            'Somalia' => 'الصومال',
            'South Georgia & South Sandwich' => 'جورجيا الجنوبية',
            'South Sudan' => 'جنوب السودان',
            'SC' => 'سيشل',
            'LK' => 'سريلانكا',
            'SG' => 'سنغافورة',
            'HK' => 'هونغ كونغ',
            'PH' => 'الفلبين',
            'VN' => 'فيتنام',
            'AZ' => 'أذربيجان',
            'GE' => 'جورجيا',
            'AM' => 'أرمينيا',
            'KZ' => 'كازاخستان',
            'UZ' => 'أوزبكستان',
            'BA' => 'البوسنة والهرسك',
            'ME' => 'الجبل الأسود',
            'RS' => 'صربيا',
            'HR' => 'كرواتيا',
            'CZ' => 'التشيك',
            'HU' => 'المجر',
            'PL' => 'بولندا',
            // Full country name variations for complete coverage
            'Vanuatu' => 'فانواتو',
            'VU' => 'فانواتو',
            'Vatican' => 'الفاتيكان',
            'Venezuela' => 'فنزويلا',
            'Vietnam' => 'فيتنام',
            'Virgin Islands (US)' => 'جزر العذراء الأمريكية',
            'VI' => 'جزر العذراء الأمريكية',
            'VG' => 'جزر العذراء البريطانية',
            'British Virgin Islands' => 'جزر العذراء البريطانية',
            'Wallis & Futuna Islands' => 'جزر واليس وفوتونا',
            'WF' => 'جزر واليس وفوتونا',
            'Western Sahara' => 'الصحراء الغربية',
            'EH' => 'الصحراء الغربية',
            'Yemen' => 'اليمن',
            'Yugoslavia' => 'يوغوسلافيا',
            'YU' => 'يوغوسلافيا',
            'Zambia' => 'زامبيا',
            'Zimbabwe' => 'زيمبابوي',
            'Algeria' => 'الجزائر',
            'American Samoa' => 'ساموا الأمريكية',
            'AS' => 'ساموا الأمريكية',
            'Andorra' => 'أندورا',
            'Angola' => 'أنغولا',
            'Anguilla' => 'أنغويلا',
            'AI' => 'أنغويلا',
            'Antarctica' => 'القارة القطبية الجنوبية',
            'AQ' => 'القارة القطبية الجنوبية',
            'Antigua & Barbuda' => 'أنتيغوا وبربودا',
            'AG' => 'أنتيغوا وبربودا',
            'Argentina' => 'الأرجنتين',
            'Armenia' => 'أرمينيا',
            'Aruba' => 'أروبا',
            'AW' => 'أروبا',
            'Australia' => 'أستراليا',
            'Austria' => 'النمسا',
            'Azerbaijan' => 'أذربيجان',
            'Bahamas' => 'جزر البهاما',
            'Bahrain' => 'البحرين',
            'Bangladesh' => 'بنجلاديش',
            'Barbados' => 'بربادوس',
            'Belarus' => 'بيلاروسيا',
            'Belgium' => 'بلجيكا',
            'Belize' => 'بيليز',
            'Benin' => 'بنين',
            'Bermuda' => 'برمودا',
            'BM' => 'برمودا',
            'Bhutan' => 'بوتان',
            'BT' => 'بوتان',
            'Bolivia' => 'بوليفيا',
            'Bonaire Saba Sint Eustatius' => 'بونير وسابا وسينت أوستاتيوس',
            'BQ' => 'بونير وسابا وسينت أوستاتيوس',
            'Bosnia & Herzegovina' => 'البوسنة والهرسك',
            'Botswana' => 'بوتسوانا',
            'Bouvet Islands' => 'جزيرة بوفيه',
            'BV' => 'جزيرة بوفيه',
            'Brazil' => 'البرازيل',
            'British Indian Ocean Territory' => 'إقليم المحيط الهندي البريطاني',
            'IO' => 'إقليم المحيط الهندي البريطاني',
            'Brunei Darussalam' => 'بروناي',
            'BN' => 'بروناي',
            'Bulgaria' => 'بلغاريا',
            'Burkina Faso' => 'بوركينا فاسو',
            'Burundi' => 'بوروندي',
            'Cambodia' => 'كمبوديا',
            'KH' => 'كمبوديا',
            'Cameroon' => 'الكاميرون',
            'Canada' => 'كندا',
            'Canada Buffer' => 'كندا',
            'Cape Verde' => 'الرأس الأخضر',
            'Cayman Islands' => 'جزر كايمان',
            'KY' => 'جزر كايمان',
            'Central African Republic' => 'أفريقيا الوسطى',
            'Chad' => 'تشاد',
            'Chile' => 'تشيلي',
            'China' => 'الصين',
            'Christmas Island' => 'جزيرة الكريسماس',
            'CX' => 'جزيرة الكريسماس',
            'Cocos (Keeling) Islands' => 'جزر كوكوس',
            'CC' => 'جزر كوكوس',
            'Colombia' => 'كولومبيا',
            'Comoros' => 'جزر القمر',
            'Congo' => 'الكونغو',
            'Cook Islands' => 'جزر كوك',
            'CK' => 'جزر كوك',
            'Costa Rica' => 'كوستاريكا',
            'Croatia' => 'كرواتيا',
            'Cuba' => 'كوبا',
            'Curacao' => 'كوراساو',
            'CW' => 'كوراساو',
            'Cyprus' => 'قبرص',
            'Czech Republic' => 'التشيك',
            'Denmark' => 'الدانمارك',
            'Djibouti' => 'جيبوتي',
            'Dominica' => 'دومينيكا',
            'DM' => 'دومينيكا',
            'Dominican Republic' => 'الدومينيكان',
            'Ecuador' => 'الإكوادور',
            'Egypt' => 'مصر',
            'El Salvador' => 'السلفادور',
            'Equatorial Guinea' => 'غينيا الاستوائية',
            'Eritrea' => 'إريتريا',
            'Estonia' => 'إستونيا',
            'Ethiopia' => 'إثيوبيا',
            'Falkland Islands' => 'جزر فوكلاند',
            'FK' => 'جزر فوكلاند',
            'Faroe Islands' => 'جزر فارو',
            'Fiji' => 'فيجي',
            'Finland' => 'فنلندا',
            'France' => 'فرنسا',
            'French Guiana' => 'غويانا الفرنسية',
            'GF' => 'غويانا الفرنسية',
            'French Polynesia' => 'بولينيزيا الفرنسية',
            'PF' => 'بولينيزيا الفرنسية',
            'French Southern Territories' => 'الأقاليم الجنوبية الفرنسية',
            'TF' => 'الأقاليم الجنوبية الفرنسية',
            'Gabon' => 'الغابون',
            'Gambia' => 'غامبيا',
            'Georgia' => 'جورجيا',
            'Germany' => 'ألمانيا',
            'Ghana' => 'غانا',
            'Gibraltar' => 'جبل طارق',
            'Greece' => 'اليونان',
            'Greenland' => 'جرينلاند',
            'Grenada' => 'غرينادا',
            'GD' => 'غرينادا',
            'Guadeloupe' => 'غوادلوب',
            'GP' => 'غوادلوب',
            'Guam' => 'غوام',
            'GU' => 'غوام',
            'Guatemala' => 'غواتيمالا',
            'Guernsey' => 'غيرنزي',
            'GG' => 'غيرنزي',
            'Guinea' => 'غينيا',
            'Guinea-Bissau' => 'غينيا بيساو',
            'Guyana' => 'غيانا',
            'GY' => 'غيانا',
            'Haiti' => 'هايتي',
            'Heard & McDonald Islands' => 'جزر هيرد وماكدونالد',
            'HM' => 'جزر هيرد وماكدونالد',
            'Honduras' => 'هندوراس',
            'Hong Kong' => 'هونغ كونغ',
            'Hungary' => 'المجر',
            'Iceland' => 'أيسلندا',
            'India' => 'الهند',
            'Indonesia' => 'إندونيسيا',
            'Iran' => 'إيران',
            'Iraq' => 'العراق',
            'Ireland' => 'أيرلندا',
            'Isle of Man' => 'جزيرة مان',
            'IM' => 'جزيرة مان',
            'Israel' => 'إسرائيل',
            'IL' => 'إسرائيل',
            'Italy' => 'إيطاليا',
            'Ivory Coast' => 'ساحل العاج',
            'Jamaica' => 'جامايكا',
            'Japan' => 'اليابان',
            'Jersey' => 'جيرسي',
            'JE' => 'جيرسي',
            'Jordan' => 'الأردن',
            'Kazakhstan' => 'كازاخستان',
            'Kenya' => 'كينيا',
            'Kiribati' => 'كيريباتي',
            'KI' => 'كيريباتي',
            'Korea' => 'كوريا',
            'Kosovo' => 'كوسوفو',
            'XK' => 'كوسوفو',
            'Kuwait' => 'الكويت',
            'Kyrgyzstan' => 'قيرغيزستان',
            'Laos' => 'لاوس',
            'LA' => 'لاوس',
            'Latvia' => 'اتفيا',
            'Lebanon' => 'لبنان',
            'Lesotho' => 'ليسوتو',
            'Liberia' => 'ليبيريا',
            'Libya' => 'ليبيا',
            'Liechtenstein' => 'ليختنشتاين',
            'Lithuania' => 'ليتوانيا',
            'Luxembourg' => 'لوكسمبورغ',
            'Macao' => 'ماكاو',
            'MO' => 'ماكاو',
            'Macedonia' => 'مقيدونيا الشمالية',
            'Madagascar' => 'مدغشقر',
            'Malawi' => 'ملاوي',
            'MW' => 'ملاوي',
            'Malaysia' => 'ماليزيا',
            'Maldives' => 'المالديف',
            'Mali' => 'مالي',
            'Malta' => 'مالطا',
            'Marshall Islands' => 'جزر مارشال',
            'MH' => 'جزر مارشال',
            'Martinique' => 'مارتينيك',
            'MQ' => 'مارتينيك',
            'Mauritania' => 'موريتانيا',
            'Mauritius' => 'موريشيوس',
            'Mayotte' => 'مايوت',
            'Mexico' => 'المكسيك',
            'Micronesia' => 'ميكرونيزيا',
            'FM' => 'ميكرونيزيا',
            'Moldova' => 'مولدوفا',
            'MD' => 'مولدوفا',
            'Monaco' => 'موناكو',
            'Mongolia' => 'منغوليا',
            'MN' => 'منغوليا',
            'Montenegro' => 'الجبل الأسود',
            'Montserrat' => 'مونتسرات',
            'MS' => 'مونتسرات',
            'Morocco' => 'المغرب',
            'Mozambique' => 'موزمبيق',
            'Myanmar' => 'ميانمار',
            'MM' => 'ميانمار',
            'Namibia' => 'ناميبيا',
            'Nauru' => 'ناورو',
            'NR' => 'ناورو',
            'Nepal' => 'نيبال',
            'Netherlands' => 'هولندا',
            'New Caledonia' => 'كاليدونيا الجديدة',
            'NC' => 'كاليدونيا الجديدة',
            'New Zealand' => 'نيوزيلندا',
            'Nicaragua' => 'نيكاراغوا',
            'Niger' => 'النيجر',
            'Nigeria' => 'نيجيريا',
            'Niue' => 'نيوي',
            'NU' => 'نيوي',
            'Norfolk Island' => 'جزيرة نورفولك',
            'NF' => 'جزيرة نورفولك',
            'North Korea' => 'كوريا الشمالية',
            'KP' => 'كوريا الشمالية',
            'Northern Mariana Islands' => 'جزر ماريانا الشمالية',
            'MP' => 'جزر ماريانا الشمالية',
            'Norway' => 'النرويج',
            'Oman' => 'عمان',
            'Pakistan' => 'باكستان',
            'Palau' => 'بالاو',
            'PW' => 'بالاو',
            'Palestine' => 'فلسطين',
            'Panama' => 'بنما',
            'Papua New Guinea' => 'بابوا غينيا الجديدة',
            'Paraguay' => 'باراغواي',
            'Peru' => 'بيرو',
            'Philippines' => 'الفلبين',
            'Pitcairn' => 'بيتكيرن',
            'PN' => 'بيتكيرن',
            'Poland' => 'بولندا',
            'Portugal' => 'البرتغال',
            'Puerto Rico' => 'بورتوريكو',
            'PR' => 'بورتوريكو',
            'Qatar' => 'قطر',
            'Reunion' => 'ريونيون',
            'Romania' => 'رومانيا',
            'Russia' => 'روسيا',
            'Rwanda' => 'رواندا',
            'Saint Helena' => 'سانت هيلانة',
            'Saint Kitts & Nevis' => 'سانت كيتس ونيفيس',
            'KN' => 'سانت كيتس ونيفيس',
            'Saint Lucia' => 'سانت لوسيا',
            'LC' => 'سانت لوسيا',
            'Saint Pierre & Miquelon' => 'سان بيير وميكلون',
            'PM' => 'سان بيير وميكلون',
            'Saint Vincent & Grenadines' => 'سانت فنسنت والغرينادين',
            'VC' => 'سانت فنسنت والغرينادين',
            'Samoa' => 'ساموا',
            'WS' => 'ساموا',
            'Saudi Arabia' => 'المملكة العربية السعودية',
            'Serbia' => 'صربيا',
            'Seychelles' => 'سيشل',
            'South Africa' => 'جنوب أفريقيا',
            'South Korea' => 'كوريا الجنوبية',
            'Spain' => 'إسبانيا',
            'Sri Lanka' => 'سريلانكا',
            'Sudan' => 'السودان',
            'Suriname' => 'سورينام',
            'SR' => 'سورينام',
            'Svalbard & Jan Mayen' => 'سفالبارد',
            'Swaziland' => 'إسواتيني',
            'Sweden' => 'السويد',
            'Switzerland' => 'سويسرا',
            'Syria' => 'سوريا',
            'Taiwan' => 'تايوان',
            'TW' => 'تايوان',
            'Tajikistan' => 'طاجيكستان',
            'Tanzania' => 'تنزانيا',
            'Thailand' => 'تايلاند',
            'Timor-Leste' => 'تيمور الشرقية',
            'TL' => 'تيمور الشرقية',
            'Togo' => 'توغو',
            'Tokelau' => 'توكيلاو',
            'TK' => 'توكيلاو',
            'Tonga' => 'تونغا',
            'TO' => 'تونغا',
            'Trinidad & Tobago' => 'ترينيداد وتوباغو',
            'Tunisia' => 'تونس',
            'Turkey' => 'تركيا',
            'Turkmenistan' => 'تركمانستان',
            'Turks & Caicos Islands' => 'جزر تركس وكايكوس',
            'TC' => 'جزر تركس وكايكوس',
            'Tuvalu' => 'توفالو',
            'TV' => 'توفالو',
            'Uganda' => 'أوغندا',
            'Ukraine' => 'أوكرانيا',
            'United Arab Emirates' => 'الإمارات العربية المتحدة',
            'United Kingdom' => 'المملكة المتحدة',
            'United States' => 'الولايات المتحدة',
            'Uruguay' => 'أوروغواي',
            'US Minor Outlying Islands' => 'جزر الولايات المتحدة النائية',
            'UM' => 'جزر الولايات المتحدة النائية',
            'Uzbekistan' => 'أوزبكستان',
            // Additional missing countries
            'Soviet Union' => 'الاتحاد السوفيتي',
            'SU' => 'الاتحاد السوفيتي',
            'USSR' => 'الاتحاد السوفيتي',
            'St. Barthelemy' => 'سانت بارتيليمي',
            'Saint Barthelemy' => 'سانت بارتيليمي',
            'BL' => 'سانت بارتيليمي',
            'Saint Martin (French part)' => 'سانت مارتن (الجزء الفرنسي)',
            'St. Martin (French part)' => 'سانت مارتن (الجزء الفرنسي)',
            'MF' => 'سانت مارتن (الجزء الفرنسي)',
            'Namibia' => 'ناميبيا',
            'East Timor' => 'تيمور الشرقية',
        ];

        // Filter out invalid/test countries and translate
        $filteredCountries = [];
        foreach ($countries as $country) {
            $code = $country['Code'] ?? $country['CountryCode'] ?? '';
            $name = $country['Name'] ?? $country['CountryName'] ?? '';

            // Skip invalid/test entries
            $invalidPatterns = [
                'NotAvailable', 'Dummy', 'Buffer', '-1', 'Test',
                'European Monetary Union', 'Netherlands Antilles',
            ];

            $isInvalid = false;
            foreach ($invalidPatterns as $pattern) {
                if (stripos($name, $pattern) !== false || stripos($code, $pattern) !== false) {
                    $isInvalid = true;
                    break;
                }
            }

            if ($isInvalid) {
                continue; // Skip this country
            }

            // Translate if available
            if (isset($arabicMap[$code])) {
                $country['Name'] = $arabicMap[$code];
                $country['CountryName'] = $arabicMap[$code];
            } elseif (isset($arabicMap[$name])) {
                $country['Name'] = $arabicMap[$name];
                $country['CountryName'] = $arabicMap[$name];
            }

            $filteredCountries[] = $country;
        }

        return $filteredCountries;
    }

    /**
     * Helper method to translate cities to Arabic manually
     */
    private function translateCities(array $cities): array
    {
        $arabicMap = [
            'DXB' => 'دبي',
            'AUH' => 'أبو ظبي',
            'RUH' => 'الرياض',
            'JED' => 'جدة',
            'DMM' => 'الدمام',
            'MED' => 'المدينة المنورة',
            'MAK' => 'مكة المكرمة',
            'CAI' => 'القاهرة',
            'IST' => 'اسطنبول',
            'LON' => 'لندن',
            'PAR' => 'باريس',
            'AMM' => 'عمان',
            'KWI' => 'الكويت',
            'DOH' => 'الدوحة',
            'MCT' => 'مسقط',
            'BAH' => 'المنامة',
            'BEI' => 'بيروت',
            'CAS' => 'الدار البيضاء',
            'RAK' => 'مراكش',
            'TUN' => 'تونس',
            'ALG' => 'الجزائر',
            'KRT' => 'الخرطوم',
            'SAN' => 'صنعاء',
            'DAM' => 'دمشق',
            'BAG' => 'بغداد',
            'Makkah' => 'مكة المكرمة',
            'MAK' => 'مكة المكرمة',
            'Al Madinah' => 'المدينة المنورة',
            'Madinah' => 'المدينة المنورة',
            'MED' => 'المدينة المنورة',
            'Al Khobar' => 'الخبر',
            'Khobar' => 'الخبر',
            'DMM' => 'الدمام',
            'Dhahran' => 'الظهران',
            'Abha' => 'أبها',
            'Taif' => 'الطائف',
            'Tabuk' => 'تبوك',
            'Buraidah' => 'بريدة',
            'Hail' => 'حائل',
            'Najran' => 'نجران',
            'Jizan' => 'جيزان',
            'Al Bahah' => 'الباحة',
            'Sakaka' => 'سكاكا',
            'Arar' => 'عرعر',
            'Afif' => 'عفيف',
            'Al Bukayriyah' => 'البكيرية',
            'Al Majma\'ah' => 'المجمعة',
            'Al Lith' => 'الليث',
            'Al Lith Makkah' => 'الليث',
            'Al Qunfudhah' => 'القنفذة',
            'Al Qunfudhah Makkah' => 'القنفذة',
            'Al Wajh' => 'الوجه',
            'Al Lith, Makkah' => 'الليث',
            'Al Qunfudhah, Makkah' => 'القنفذة',
            'Al Madinah Province' => 'منطقة المدينة المنورة',
            'Yanbu' => 'ينبع',
            'Yanbu Al Bahr' => 'ينبع البحر',
        ];

        foreach ($cities as &$city) {
            $code = $city['CityCode'] ?? $city['Code'] ?? '';
            $name = $city['CityName'] ?? $city['Name'] ?? '';

            // Explicit overrides for problematic matches
            if (stripos($name, 'Al Lith') !== false) {
                $city['CityName'] = 'الليث';
                $city['Name'] = 'الليث';

                continue;
            }
            if (stripos($name, 'Al Qunfudhah') !== false) {
                $city['CityName'] = 'القنفذة';
                $city['Name'] = 'القنفذة';

                continue;
            }
            if (stripos($name, 'Al Madinah Province') !== false) {
                $city['CityName'] = 'منطقة المدينة المنورة';
                $city['Name'] = 'منطقة المدينة المنورة';

                continue;
            }

            if (isset($arabicMap[$code])) {
                $city['CityName'] = $arabicMap[$code];
                $city['Name'] = $arabicMap[$code];
            } elseif (isset($arabicMap[$name])) {
                $city['CityName'] = $arabicMap[$name];
                $city['Name'] = $arabicMap[$name];
            } else {
                // Try tougher matching: Case-insensitive and trimmed
                foreach ($arabicMap as $key => $value) {
                    if (strcasecmp(trim($name), trim($key)) === 0) {
                        $city['CityName'] = $value;
                        $city['Name'] = $value;
                        break;
                    }
                }
            }
        }

        return $cities;
    }
}
