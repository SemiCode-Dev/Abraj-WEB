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

            // 1. Check Local Storage
            if (isset($this->translations[$code])) {
                $hotel['HotelName'] = $this->translations[$code];
                $hotel['Name'] = $this->translations[$code];

                continue;
            }

            // 2. If limit reached, skip translation for now (return English)
            if ($apiCalls >= $maxApiCalls) {
                continue;
            }

            // 3. Translate via API
            try {
                // Remove generic terms for better translation?
                // e.g. "Hotel" sometimes translates to "فندق", usually fine.
                // Google Translate is usually good with "Ritz Carlton Hotel" -> "فندق ريتز كارلتون"

                $translated = $this->translator->translate($name);

                if ($translated && $translated !== $name) {
                    $this->translations[$code] = $translated;
                    $this->isModified = true;

                    $hotel['HotelName'] = $translated;
                    $hotel['Name'] = $translated;

                    $apiCalls++;
                    // usleep(100000); // 100ms throttle - Removed for performance
                } else {
                    // Failed to translate meaningfully, or same name
                    // Cache the English name to avoid retrying forever?
                    // Yes, cache it.
                    $this->translations[$code] = $name;
                    $this->isModified = true;
                }

            } catch (\Exception $e) {
                Log::warning("Hotel Translation Failed [$code]: ".$e->getMessage());
            }
        }

        $this->saveTranslations();

        return $hotels;
    }
}
