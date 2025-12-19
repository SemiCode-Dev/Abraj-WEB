<?php

namespace App\Services\Api\V1;

use Illuminate\Support\Facades\Http;

class HotelApiService
{
    protected string $baseUrl;

    protected string $username;

    protected string $password;

    public function __construct()
    {
        $this->baseUrl = config('services.tbo.base_url');
        $this->username = config('services.tbo.username');
        $this->password = config('services.tbo.password');
    }

    private function sendRequest(string $endpoint, array $payload)
    {
        $url = rtrim($this->baseUrl, '/').'/'.$endpoint;

        return Http::timeout(10) // 10 seconds timeout (reduced from 30s)
            ->retry(1, 200) // Retry 1 time with 200ms delay (reduced from 2 retries)
            ->withBasicAuth($this->username, $this->password)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post($url, $payload)
            ->json();
    }

    public function searchHotel(array $data)
    {
        // Validate required fields
        if (empty($data['HotelCodes'])) {
            throw new \InvalidArgumentException('HotelCodes cannot be null or empty');
        }

        if (empty($data['CheckIn']) || empty($data['CheckOut'])) {
            throw new \InvalidArgumentException('CheckIn and CheckOut dates are required');
        }

        $payload = [
            'CheckIn' => $data['CheckIn'],
            'CheckOut' => $data['CheckOut'],
            'HotelCodes' => (string) $data['HotelCodes'], // Ensure it's a string
            'GuestNationality' => $data['GuestNationality'] ?? 'AE',
            'PaxRooms' => $data['PaxRooms'] ?? [
                [
                    'Adults' => 1,
                    'Children' => 0,
                    'ChildrenAges' => [],
                ],
            ],
            'ResponseTime' => $data['ResponseTime'] ?? 18,
            'IsDetailedResponse' => $data['IsDetailedResponse'] ?? true,
            'Filters' => $data['Filters'] ?? [
                'Refundable' => true,
                'NoOfRooms' => 0,
                'MealType' => 'All',
            ],
        ];

        // Debug logging for TBO API requests/responses (only in debug mode)
        if (config('app.debug')) {
            \Log::debug('TBO API Search Request', $payload);
        }
        
        $response = $this->sendRequest('Search', $payload);
        
        if (config('app.debug')) {
            \Log::debug('TBO API Search Response', [
                'status_code' => $response['Status']['Code'] ?? 'unknown',
                'hotels_count' => isset($response['Hotels']) ? count($response['Hotels']) : 0,
            ]);
        }
        
        return $response;
    }

    public function getHotels($cityCode, int $page = 1)
    {
        $payload = [
            'CityCode' => $cityCode,
            'IsDetailedResponse' => 'false',
            'PageNo' => $page,
        ];

        return $this->sendRequest('TBOHotelCodeList', $payload);
    }

    public function getCityHotels($cityCode, int $page = 1)
    {
        $payload = [
            'CityCode' => $cityCode,
            'IsDetailedResponse' => 'true',
            'PageNo' => $page,
        ];

        return $this->sendRequest('TBOHotelCodeList', $payload);
    }

    /**
     * Get all hotels from a city by fetching all pages
     */
    public function getAllCityHotels(string $cityCode, bool $detailed = true): array
    {
        $allHotels = [];
        $page = 1;
        $maxPages = 3; // Reduced from 100 to 3 for faster performance

        do {
            try {
                $response = $this->getCityHotels($cityCode, $page);

                // Check if request was successful
                if (isset($response['Status']) && $response['Status']['Code'] !== 200) {
                    break;
                }

                $hotels = $response['Hotels'] ?? [];

                if (empty($hotels) || ! is_array($hotels)) {
                    break;
                }

                $allHotels = array_merge($allHotels, $hotels);

                // Check if there are more pages
                // Some APIs return TotalPages or similar field
                $totalPages = $response['TotalPages'] ?? $response['TotalPageCount'] ?? null;

                if ($totalPages !== null) {
                    if ($page >= $totalPages) {
                        break;
                    }
                } else {
                    // If no pagination info, check if we got fewer hotels than expected
                    // This is a fallback - if we get empty or very few results, assume we're done
                    if (count($hotels) === 0) {
                        break;
                    }
                }

                $page++;

                // Safety check
                if ($page > $maxPages) {
                    break;
                }

                // Small delay to avoid rate limiting
                usleep(50000); // 0.05 second (reduced from 0.1s)
            } catch (\Exception $e) {
                // Log error but continue with what we have
                \Illuminate\Support\Facades\Log::warning('Error fetching hotels page '.$page.': '.$e->getMessage());
                break;
            }
        } while (true);

        return [
            'Status' => [
                'Code' => 200,
                'Description' => 'Success',
            ],
            'Hotels' => $allHotels,
            'TotalHotels' => count($allHotels),
            'PagesFetched' => $page - 1,
        ];
    }

    /**
     * Get hotels from multiple cities using TBOHotelCodeList
     * This replaces the random city approach with a comprehensive approach
     */
    public function getHotelsFromMultipleCities(array $cityCodes, bool $detailed = true, ?int $maxHotelsPerCity = null): array
    {
        $allHotels = [];
        $totalPagesFetched = 0;

        foreach ($cityCodes as $cityCode) {
            try {
                $cityHotels = [];
                $page = 1;
                // Reduced max pages for faster loading (especially for homepage)
                $maxPages = $maxHotelsPerCity !== null ? 3 : 10; // Limit per city to avoid too many requests

                do {
                    $response = $detailed
                        ? $this->getCityHotels($cityCode, $page)
                        : $this->getHotels($cityCode, $page);

                    // Check if request was successful
                    if (isset($response['Status']) && $response['Status']['Code'] !== 200) {
                        break;
                    }

                    $hotels = $response['Hotels'] ?? [];

                    if (empty($hotels) || ! is_array($hotels)) {
                        break;
                    }

                    $cityHotels = array_merge($cityHotels, $hotels);
                    $totalPagesFetched++;

                    // Check if we have enough hotels from this city
                    if ($maxHotelsPerCity !== null && count($cityHotels) >= $maxHotelsPerCity) {
                        $cityHotels = array_slice($cityHotels, 0, $maxHotelsPerCity);
                        break;
                    }

                    // Check if there are more pages
                    $totalPages = $response['TotalPages'] ?? $response['TotalPageCount'] ?? null;

                    if ($totalPages !== null) {
                        if ($page >= $totalPages) {
                            break;
                        }
                    } else {
                        // If no pagination info, check if we got empty results
                        if (count($hotels) === 0) {
                            break;
                        }
                    }

                    $page++;

                    // Safety check
                    if ($page > $maxPages) {
                        break;
                    }

                    // Reduced delay for faster loading (50ms instead of 100ms)
                    usleep(50000); // 0.05 second
                } while (true);

                $allHotels = array_merge($allHotels, $cityHotels);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Error fetching hotels from city '.$cityCode.': '.$e->getMessage());

                continue; // Continue with next city
            }
        }

        return [
            'Status' => [
                'Code' => 200,
                'Description' => 'Success',
            ],
            'Hotels' => $allHotels,
            'TotalHotels' => count($allHotels),
            'CitiesProcessed' => count($cityCodes),
            'PagesFetched' => $totalPagesFetched,
        ];
    }

    public function getCountries()
    {
        $url = rtrim($this->baseUrl, '/').'/CountryList';

        return Http::timeout(10) // 10 seconds timeout (reduced from 30s)
            ->retry(1, 200) // Retry 1 time with 200ms delay (reduced from 2 retries)
            ->withBasicAuth($this->username, $this->password)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->get($url)
            ->json();
    }

    public function getCitiesByCountry(string $countryCode)
    {
        $payload = [
            'CountryCode' => $countryCode,
        ];

        return $this->sendRequest('CityList', $payload);
    }

    public function getHotelDetails(string $hotelCode, string $language = 'ar')
    {
        // Validate hotel code
        if (empty($hotelCode)) {
            throw new \InvalidArgumentException('HotelCodes cannot be null or empty');
        }

        $payload = [
            'Hotelcodes' => (string) $hotelCode,
            'Language' => $language,
        ];

        // Log request for debugging
        \Illuminate\Support\Facades\Log::info('Hotel details API request', [
            'endpoint' => 'Hoteldetails',
            'payload' => $payload,
            'base_url' => $this->baseUrl,
        ]);

        return $this->sendRequest('Hoteldetails', $payload);
    }
}
