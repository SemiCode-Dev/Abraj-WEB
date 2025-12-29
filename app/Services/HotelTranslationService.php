<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Stichoza\GoogleTranslate\GoogleTranslate;

class HotelTranslationService
{
    protected $filePath = 'translations/hotels_ar.json';

    protected $translations = []; // Maps HotelCode => ArabicName

    protected $isModified = false;

    protected $translator;

    public function __construct()
    {
        $this->loadTranslations();
        $this->translator = new GoogleTranslate('ar');
        $this->translator->setSource('en');
    }

    protected function loadTranslations()
    {
        if (Storage::exists($this->filePath)) {
            $json = Storage::get($this->filePath);
            $this->translations = json_decode($json, true) ?? [];
        } else {
            // Seed defaults if needed?
            // Optional: Seed common hotels if we had a list.
        }
    }

    public function saveTranslations()
    {
        if ($this->isModified) {
            Storage::put($this->filePath, json_encode($this->translations, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            $this->isModified = false;
        }
    }

    /**
     * Batch translate hotels
     *
     * @param  array  $hotels  List of hotel objects (associative arrays)
     * @param  int  $maxApiCalls  Max API calls to make this request
     * @return array Translated hotels array
     */
    public function translateHotels(array $hotels, int $maxApiCalls = 1): array
    {
        $apiCalls = 0;

        foreach ($hotels as &$hotel) {
            $code = $hotel['HotelCode'] ?? null;
            $name = $hotel['HotelName'] ?? $hotel['Name'] ?? null;

            if (! $code || ! $name) {
                continue;
            }

            // Translate Location (City/Country) - Always attempt this
            $this->translateLocation($hotel);

            // 1. Check Local Storage
            if (isset($this->translations[$code])) {
                $cached = $this->translations[$code];
                
                // Handle legacy string cache (Name only) vs new array cache (Name + Address)
                if (is_array($cached)) {
                    $hotel['HotelName'] = $cached['name'] ?? $name;
                    $hotel['Name'] = $cached['name'] ?? $name;
                    // Only override address if we have a translation for it
                    if (!empty($cached['address'])) {
                        $hotel['Address'] = $cached['address'];
                    }
                } else {
                    $hotel['HotelName'] = $cached;
                    $hotel['Name'] = $cached;
                }
                
                // If we have a full hit (name + address if present), continue
                if (is_array($cached) && isset($cached['address'])) {
                    continue;
                }
            }

            // 2. If limit reached, skip translation for now (return English)
            if ($apiCalls >= $maxApiCalls) {
                continue;
            }

            // 3. Translate via API
            try {
                $needsSave = false;
                $cachedData = isset($this->translations[$code]) && is_array($this->translations[$code]) 
                    ? $this->translations[$code] 
                    : (isset($this->translations[$code]) ? ['name' => $this->translations[$code]] : []);

                // Translate Name
                if (empty($cachedData['name'])) {
                    $translatedName = $this->translator->translate($name);
                    if ($translatedName && $translatedName !== $name) {
                        $cachedData['name'] = $translatedName;
                        $hotel['HotelName'] = $translatedName;
                        $hotel['Name'] = $translatedName;
                        $needsSave = true;
                    } else {
                         $cachedData['name'] = $name;
                    }
                }

                // Translate Address
                $address = $hotel['Address'] ?? '';
                if (!empty($address) && empty($cachedData['address'])) {
                     $translatedAddress = $this->translator->translate($address);
                     if ($translatedAddress && $translatedAddress !== $address) {
                        $cachedData['address'] = $translatedAddress;
                        $hotel['Address'] = $translatedAddress;
                        $needsSave = true;
                     } else {
                        $cachedData['address'] = $address;
                     }
                }

                if ($needsSave) {
                    $this->translations[$code] = $cachedData;
                    $this->isModified = true;
                    $apiCalls++;
                }

            } catch (\Exception $e) {
                Log::warning("Hotel Translation Failed [$code]: ".$e->getMessage());
            }
        }

        $this->saveTranslations();

        return $hotels;
    }

    /**
     * Efficiently translate an array of strings in a single API call
     *
     * @param array $strings
     * @param string $source
     * @param string $target
     * @return array
     */
    public function translateStrings(array $strings, string $source = 'en', string $target = 'ar'): array
    {
        if (empty($strings)) {
            return [];
        }

        // Filter out empty/null strings
        $strings = array_values(array_filter($strings));
        if (empty($strings)) {
            return [];
        }

        $result = [];
        $chunks = array_chunk($strings, 50); // Use smaller chunks for safety (max ~5000 chars)
        $delimiter = ' | ';

        foreach ($chunks as $chunk) {
            try {
                $textToTranslate = implode($delimiter, $chunk);
                
                $this->translator->setSource($source);
                $this->translator->setTarget($target);
                
                $translatedText = $this->translator->translate($textToTranslate);
                
                if (!$translatedText) {
                    foreach ($chunk as $original) {
                        $result[$original] = $original;
                    }
                    continue;
                }

                $translatedStrings = explode($delimiter, $translatedText);
                
                foreach ($chunk as $index => $original) {
                    $translated = trim($translatedStrings[$index] ?? $original);
                    $result[$original] = $translated;
                }
            } catch (\Exception $e) {
                Log::warning("Batch translation chunk failed: " . $e->getMessage());
                foreach ($chunk as $original) {
                    $result[$original] = $original;
                }
            }
        }

        return $result;
    }

    /**
     * Translate City and Country names in the hotel object
     */
    protected function translateLocation(&$hotel)
    {
        // 1. Translate City (Database Lookup with Mapping)
        if (!empty($hotel['CityName'])) {
            $cityName = trim($hotel['CityName']);
            $cityNameLower = strtolower($cityName);
            
            // Map common API variations to our DB names or direct Arabic
            $cityMap = [
                'khobar' => 'الخبر', 
                'al khobar' => 'الخبر',
                'medina' => 'المدينة المنورة',
                'al madinah' => 'المدينة المنورة',
                'makkah' => 'مكة المكرمة',
                'mecca' => 'مكة المكرمة',
                'riyadh' => 'الرياض',
                'jeddah' => 'جدة',
                'dammam' => 'الدمام',
                'london' => 'لندن',
                'paris' => 'باريس',
                'rome' => 'روما',
                'milan' => 'ميلانو',
                'istanbul' => 'اسطنبول',
                'cairo' => 'القاهرة',
                'alexandria' => 'الإسكندرية',
                'dubai' => 'دبي',
                'abu dhabi' => 'أبو ظبي',
                'doha' => 'الدوحة',
                'manama' => 'المنامة',
                'muscat' => 'مسقط',
                'kuwait city' => 'الكويت',
                'amman' => 'عمان',
                'beirut' => 'بيروت',
                'casablanca' => 'الدار البيضاء',
                'marrakech' => 'مراكش',
                'madrid' => 'مدريد',
                'barcelona' => 'برشلونة',
                'bangkok' => 'بانكوك',
                'phuket' => 'بوكيت',
                'kuala lumpur' => 'كوالالمبور',
                'singapore' => 'سنغافورة',
                'tokyo' => 'طوكيو',
                'new york' => 'نيويورك',
                'los angeles' => 'لوس أنجلوس',
                'miami' => 'ميامي',
                'chicago' => 'شيكاغو',
                'frankfurt' => 'فرانكفورت',
                'munich' => 'ميونيخ',
                'geneva' => 'جنيف',
                'zurich' => 'زيورخ',
                'vienna' => 'فيينا',
                'salzburg' => 'سالزبورغ',
                'prague' => 'براغ',
                'budapest' => 'بودابست',
                'amsterdam' => 'أمستردام',
                'athens' => 'أثينا',
                'lisbon' => 'لشبونة',
                'rosario' => 'روساريو',
                'rosario de la frontera' => 'روساريو دي لا فرونتيرا',
                'salta' => 'سالتا',
                'buenos aires' => 'بوينس آيرس',
            ];

            if (isset($cityMap[$cityNameLower])) {
                $hotel['CityName'] = $cityMap[$cityNameLower];
            } else {
                 // Standard Lookup in Database
                $city = \App\Models\City::where('name', $cityName)->first();
                if ($city && !empty($city->name_ar)) {
                    $hotel['CityName'] = $city->name_ar;
                }
            }
        }

        // 2. Translate Country (Static Map)
        if (!empty($hotel['CountryName'])) {
            $hotel['CountryName'] = $this->translateCountry($hotel['CountryName']);
        }
    }

    protected function translateCountry($name)
    {
        $nameLower = strtolower(trim($name));
        $map = [
            'saudi arabia' => 'المملكة العربية السعودية',
            'united arab emirates' => 'الإمارات العربية المتحدة',
            'uae' => 'الإمارات العربية المتحدة',
            'egypt' => 'مصر',
            'bahrain' => 'البحرين',
            'kuwait' => 'الكويت',
            'qatar' => 'قطر',
            'oman' => 'عمان',
            'jordan' => 'الأردن',
            'turkey' => 'تركيا',
            'united kingdom' => 'المملكة المتحدة',
            'uk' => 'المملكة المتحدة',
            'united states' => 'الولايات المتحدة',
            'usa' => 'الولايات المتحدة',
            'france' => 'فرنسا',
            'germany' => 'ألمانيا',
            'italy' => 'إيطاليا',
            'spain' => 'إسبانيا',
            'switzerland' => 'سويسرا',
            'malaysia' => 'ماليزيا',
            'indonesia' => 'إندونيسيا',
            'thailand' => 'تايلاند',
            'morocco' => 'المغرب',
            'argentina' => 'الأرجنتين',
            'brazil' => 'البرازيل',
            'canada' => 'كندا',
            'china' => 'الصين',
            'japan' => 'اليابان',
            'south korea' => 'كوريا الجنوبية',
            'australia' => 'أستراليا',
            'india' => 'الهند',
            'pakistan' => 'باكستان',
            'greece' => 'اليونان',
            'netherlands' => 'هولندا',
            'belgium' => 'بلجيكا',
            'portugal' => 'البرتغال',
            'austria' => 'النمسا',
            'sweden' => 'السويد',
            'norway' => 'النرويج',
            'denmark' => 'الدانمارك',
            'russia' => 'روسيا',
            'south africa' => 'جنوب أفريقيا',
            'mexico' => 'المكسيك',
            'cyprus' => 'قبرص',
            'lebanon' => 'لبنان',
            'syria' => 'سوريا',
            'iraq' => 'العراق',
            'tunisia' => 'تونس',
            'algeria' => 'الجزائر',
            'libya' => 'ليبيا',
            'sudan' => 'السودان',
            'yemen' => 'اليمن',
            'palestine' => 'فلسطين',
        ];

        return $map[$nameLower] ?? $name;
    }
}
