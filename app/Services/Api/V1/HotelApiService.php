<?php

namespace App\Services\Api\V1;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Log;

class HotelApiService
{
    protected string $baseUrl;

    protected string $username;

    protected string $password;

    public function __construct()
    {
        $this->baseUrl = config('services.tbo.base_url');
        if (str_starts_with($this->baseUrl, 'http:')) {
            $this->baseUrl = str_replace('http:', 'https:', $this->baseUrl);
        }
        $this->username = config('services.tbo.username');
        $this->password = config('services.tbo.password');
    }

    private function sendRequest(string $endpoint, array $payload)
    {
        $url = rtrim($this->baseUrl, '/').'/'.$endpoint;
        if (config('app.debug')) {
            \Log::debug("TBO API Request to $endpoint", ['url' => $url, 'payload' => $payload]);
        }

        $response = Http::timeout(60) 
            ->retry(1, 200) 
            ->withBasicAuth($this->username, $this->password)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post($url, $payload)
            ->json();

        if (config('app.debug')) {
            \Log::debug("TBO API Response from $endpoint", ['response' => $response]);
        }

        return $response;
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
            'Language' => $data['Language'] ?? (app()->getLocale() === 'ar' ? 'ar' : 'en'),
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

    public function getHotels($cityCode, int $page = 1, string $language = 'en')
    {
        $payload = [
            'CityCode' => $cityCode,
            'IsDetailedResponse' => 'false',
            'PageNo' => $page,
            'Language' => $language,
        ];

        return $this->sendRequest('TBOHotelCodeList', $payload);
    }

    public function getCityHotels($cityCode, int $page = 1, string $language = 'en')
    {
        $payload = [
            'CityCode' => $cityCode,
            'IsDetailedResponse' => 'true',
            'PageNo' => $page,
            'Language' => $language,
        ];

        return $this->sendRequest('TBOHotelCodeList', $payload);
    }

    /**
     * Get all hotels from a city by fetching all pages
     */
    public function getAllCityHotels(string $cityCode, bool $detailed = true, string $language = 'en'): array
    {
        $allHotels = [];
        $page = 1;
        $maxPages = 3; // Reduced from 100 to 3 for faster performance

        do {
            try {
                $response = $this->getCityHotels($cityCode, $page, $language);

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
     * Get hotels from multiple cities using TBOHotelCodeList with concurrency
     */
    public function getHotelsConcurrent(array $cityCodes, bool $detailed = true, ?int $maxHotelsPerCity = null, string $language = 'en', int $maxPages = 1): array
    {
        $allHotels = [];
        // Chunk city codes to avoid overwhelming the API or exceeding connection limits
        $chunks = array_chunk($cityCodes, 10); 

        foreach ($chunks as $chunk) {
            try {
                $responses = Http::pool(function (Pool $pool) use ($chunk, $detailed, $language) {
                    $requests = [];
                    foreach ($chunk as $cityCode) {
                        $requests[] = $pool->asJson()
                            ->withBasicAuth($this->username, $this->password)
                            ->timeout(20)
                            ->post(rtrim($this->baseUrl, '/').'/TBOHotelCodeList', [
                                'CityCode' => (string)$cityCode,
                                'IsDetailedResponse' => $detailed ? 'true' : 'false',
                                'PageNo' => 1,
                                'Language' => $language,
                            ]);
                    }
                    return $requests;
                });

                foreach ($responses as $response) {
                    if ($response instanceof \Illuminate\Http\Client\Response && $response->successful()) {
                        $data = $response->json();
                        $hotels = $data['Hotels'] ?? [];
                        if (!empty($hotels) && is_array($hotels)) {
                            if ($maxHotelsPerCity !== null) {
                                $hotels = array_slice($hotels, 0, $maxHotelsPerCity);
                            }
                            $allHotels = array_merge($allHotels, $hotels);
                        }
                    } elseif ($response instanceof \Exception) {
                        Log::warning('Concurrent hotel fetch exception: ' . $response->getMessage());
                    }
                }
            } catch (\Exception $e) {
                Log::error('Guzzle Pool Error: ' . $e->getMessage());
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
        ];
    }

    /**
     * Get hotels from multiple cities using TBOHotelCodeList
     * This replaces the random city approach with a comprehensive approach
     */
    public function getHotelsFromMultipleCities(array $cityCodes, bool $detailed = true, ?int $maxHotelsPerCity = null, string $language = 'en', int $maxPages = 1): array
    {
        // For better performance, use the concurrent version if only 1 page is requested
        if ($maxPages === 1) {
            return $this->getHotelsConcurrent($cityCodes, $detailed, $maxHotelsPerCity, $language);
        }

        $allHotels = [];
        $totalPagesFetched = 0;

        foreach ($cityCodes as $cityCode) {
            try {
                $cityHotels = [];
                $page = 1;
                // Use passed maxPages for control
                $maxPagesToFetch = $maxPages; 

                do {
                    $response = $detailed
                        ? $this->getCityHotels($cityCode, $page, $language)
                        : $this->getHotels($cityCode, $page, $language);

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
                    if ($page > $maxPagesToFetch) {
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

    public function getCountries(string $language = 'en')
    {
        $url = rtrim($this->baseUrl, '/').'/CountryList';

        // TBO might accept language as a query parameter for GET requests
        return Http::timeout(10)
            ->retry(1, 200)
            ->withBasicAuth($this->username, $this->password)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->get($url, [
                'Language' => $language
            ])
            ->json();
    }

    public function getCitiesByCountry(string $countryCode, string $language = 'en')
    {
        $payload = [
            'CountryCode' => $countryCode,
            'Language' => $language,
        ];

        return $this->sendRequest('CityList', $payload);
    }

    public function getHotelDetails(string $hotelCode, string $language = 'ar')
    {
        // ... (existing code preserved)
        // Ensure hotel code is string
        $payload = [
            'Hotelcodes' => (string) $hotelCode,
            'Language' => $language,
        ];

        return $this->sendRequest('Hoteldetails', $payload);
    }

    /**
     * PreBook Room (TBO API) - Verify availability and price
     */
    public function preBook(string $bookingCode)
    {
        $payload = [
            'BookingCode' => $bookingCode,
            'PaymentMode' => 'Limit'
        ];

        return $this->sendRequest('PreBook', $payload);
    }

    /**
     * Book Room (TBO API)
     */
    public function book(array $data)
    {
        // Log the booking request for audit
        \Illuminate\Support\Facades\Log::info('TBO Book API Request', ['data' => $data]);

        /* 
           Payload structure based on Postman collection:
           {
             "BookingCode": "...",
             "CustomerDetails": [
                { "CustomerNames": [ { "Title": "Mr", "FirstName": "...", "LastName": "...", "Type": "Adult" } ] }
             ],
             "ClientReferenceId": "...",
             "BookingReferenceId": "...",
             "TotalFare": 0.0,
             "EmailId": "...",
             "PhoneNumber": "...",
             "BookingType": "Voucher",
             "PaymentMode": "Limit"
           }
        */

        return $this->sendRequest('Book', $data);
    }
}
