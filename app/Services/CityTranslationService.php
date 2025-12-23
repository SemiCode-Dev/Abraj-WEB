<?php

namespace App\Services;

use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CityTranslationService
{
    protected $filePath = 'translations/cities_ar.json';
    protected $translations = [];
    protected $isModified = false;
    protected $translator;

    public function __construct()
    {
        $this->loadTranslations();
        $this->translator = new GoogleTranslate('ar'); // Target language Arabic
        $this->translator->setSource('en'); // Source language English
    }

    /**
     * Load translations from local storage
     */
    protected function loadTranslations()
    {
        if (Storage::exists($this->filePath)) {
            $json = Storage::get($this->filePath);
            $this->translations = json_decode($json, true) ?? [];
        } else {
            $this->translations = [];
        }
    }

    /**
     * Save translations to local storage if modified
     */
    public function saveTranslations()
    {
        if ($this->isModified) {
            Storage::put($this->filePath, json_encode($this->translations, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            $this->isModified = false;
        }
    }

    /**
     * Get translation for a city name
     * 
     * @param string $cityName English city name
     * @return string Translated city name
     */
    public function translate(string $cityName): string
    {
        $checkName = trim($cityName);
        
        if (empty($checkName)) {
            return $cityName;
        }

        // Check internal cache (memory)
        if (isset($this->translations[$checkName])) {
            return $this->translations[$checkName];
        }

        // Check case-insensitive match
        $lowerName = strtolower($checkName);
        foreach ($this->translations as $key => $value) {
            if (strtolower($key) === $lowerName) {
                return $value;
            }
        }

        // If not found, Translate via API
        try {
            // Apply delay to avoid rate limiting if we are doing many
            // However, this blocking call slows down the request.
            // Ideally, we limit how many new translations happen per request.
            
            $translated = $this->translator->translate($checkName);
            
            // Validate result (sometimes it returns same string or garbage)
            if ($translated && $translated !== $checkName) {
                $this->translations[$checkName] = $translated;
                $this->isModified = true;
                return $translated;
            }
            
            // Even if same, cache it to avoid repeated API calls
            $this->translations[$checkName] = $checkName; 
            $this->isModified = true;
            return $checkName;

        } catch (\Exception $e) {
            Log::warning("Google Translate failed for city '$cityName': " . $e->getMessage());
            return $cityName; // Fallback to English on error
        }
    }

    /**
     * Batch translate a list of cities
     * To prevent timeouts, we limit the number of API calls per request
     */
    public function translateBatch(array $cityNames, int $maxApiCalls = 5): array
    {
        $results = [];
        $apiCalls = 0;

        foreach ($cityNames as $name) {
            $checkName = trim($name);
            if (isset($this->translations[$checkName])) {
                $results[$name] = $this->translations[$checkName];
                continue;
            }

            // Case insensitive check
            $found = false;
            foreach ($this->translations as $key => $value) {
                if (strcasecmp($key, $checkName) === 0) {
                    $results[$name] = $value;
                    $found = true;
                    break;
                }
            }
            if ($found) continue;

            // Needs translation
            if ($apiCalls < $maxApiCalls) {
                $results[$name] = $this->translate($checkName);
                $apiCalls++;
                // Small sleep to be nice to the API
                usleep(200000); // 0.2 seconds
            } else {
                // Skip translation for this request to preserve performance
                $results[$name] = $name;
            }
        }

        $this->saveTranslations();
        return $results;
    }
}
