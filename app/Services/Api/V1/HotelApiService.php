<?php

namespace App\Services\Api\V1;

use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;
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
            ->retry(1, 100)
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
        // Validate required fields: either HotelCodes or CityCode must be present
        if (empty($data['HotelCodes']) && empty($data['CityCode'])) {
            throw new \InvalidArgumentException('Either HotelCodes or CityCode must be provided');
        }

        if (empty($data['CheckIn']) || empty($data['CheckOut'])) {
            throw new \InvalidArgumentException('CheckIn and CheckOut dates are required');
        }

        $payload = [
            'CheckIn' => $data['CheckIn'],
            'CheckOut' => $data['CheckOut'],
            'GuestNationality' => $data['GuestNationality'] ?? 'SA',
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
                'Refundable' => false,
                'NoOfRooms' => 1,
                'MealType' => 'All',
            ],
        ];

        if (! empty($data['HotelCodes'])) {
            $payload['HotelCodes'] = (string) $data['HotelCodes'];
        }

        if (! empty($data['CityCode'])) {
            $payload['CityCode'] = (string) $data['CityCode'];
        }

        // Debug logging for TBO API requests/responses (only in debug mode)
        if (config('app.debug')) {
            \Log::debug('TBO API Search Request', $payload);
        }

        $response = $this->sendRequest('Search', $payload);

        if (config('app.debug')) {
            \Log::debug('TBO API Search Response', [
                'status_code' => $response['Status']['Code'] ?? 'unknown',
                'hotels_count' => count($response['Hotels'] ?? $response['HotelResult'] ?? []),
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

    public function getCityHotels($cityCode, int $page = 1, string $language = 'en', bool $detailed = true)
    {
        $payload = [
            'CityCode' => $cityCode,
            'IsDetailedResponse' => $detailed ? 'true' : 'false',
            'PageNo' => $page,
            'Language' => $language,
        ];

        return $this->sendRequest('TBOHotelCodeList', $payload);
    }
    
    // ...

    public function getAllCityHotels(string $cityCode, bool $detailed = true, string $language = 'en'): array
    {
        $allHotels = [];
        $page = 1;
        $maxPages = 3; 

        do {
            try {
                $response = $this->getCityHotels($cityCode, $page, $language, $detailed);

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
        // Chunk city codes to 25 to reduce sequential batches (was 10)
        $chunks = array_chunk($cityCodes, 25);

        foreach ($chunks as $chunk) {
            try {
                $responses = Http::pool(function (Pool $pool) use ($chunk, $detailed, $language) {
                    $requests = [];
                    foreach ($chunk as $cityCode) {
                        $requests[] = $pool->asJson()
                            ->withBasicAuth($this->username, $this->password)
                            ->timeout(45) // Increased timeout for larger lists
                            ->post(rtrim($this->baseUrl, '/').'/TBOHotelCodeList', [
                                'CityCode' => (string) $cityCode,
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
                        if (! empty($hotels) && is_array($hotels)) {
                            if ($maxHotelsPerCity !== null) {
                                $hotels = array_slice($hotels, 0, $maxHotelsPerCity);
                            }
                            $allHotels = array_merge($allHotels, $hotels);
                        }
                    } elseif ($response instanceof \Exception) {
                        Log::warning('Concurrent hotel fetch exception: '.$response->getMessage());
                    }
                }
            } catch (\Exception $e) {
                Log::error('Guzzle Pool Error: '.$e->getMessage());
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
     * Concurrent Search for multiple hotel batches (Availability)
     */
    /**
     * Concurrent Search for multiple hotel batches (Availability)
     */
    public function searchHotelConcurrent(array $batchedHotelCodes, array $params): array
    {
        $allResults = [];
        
        // Chunk the pool execution to avoid overwhelming the network/API
        // Process 10 batches (e.g. 10 * 40 = 400 hotels) at a time
        $chunks = array_chunk($batchedHotelCodes, 10);

        foreach ($chunks as $chunk) {
            try {
                $responses = Http::pool(function (Pool $pool) use ($chunk, $params) {
                    $requests = [];
                    foreach ($chunk as $idsString) {
                        $payload = array_merge($params, [
                            'HotelCodes' => (string) $idsString,
                        ]);

                        $requests[] = $pool->asJson()
                            ->withBasicAuth($this->username, $this->password)
                            ->timeout(60)
                            ->post(rtrim($this->baseUrl, '/').'/Search', $payload);
                    }
                    return $requests;
                });

                foreach ($responses as $response) {
                    if ($response instanceof \Illuminate\Http\Client\Response && $response->successful()) {
                        $resData = $response->json();
                        
                        $hResults = [];
                        if (isset($resData['HotelResult']) && is_array($resData['HotelResult'])) {
                            $hResults = $resData['HotelResult'];
                        } elseif (isset($resData['Hotels']) && is_array($resData['Hotels'])) {
                            $hResults = $resData['Hotels'];
                        }

                        if (!empty($hResults)) {
                            foreach ($hResults as $h) {
                                $code = $h['HotelCode'] ?? $h['Code'] ?? '';
                                if ($code) {
                                    $allResults[] = $h;
                                }
                            }
                        }
                    } elseif ($response instanceof \Exception) {
                         Log::warning('Concurrent search batch failed: '.$response->getMessage());
                    }
                }
            } catch (\Exception $e) {
                Log::error('Guzzle Pool Error in Search: '.$e->getMessage());
            }
            
            // Small delay between pool chunks to be nice to the API
            usleep(100000); // 100ms
        }

        return [
            'Status' => ['Code' => 200],
            'HotelResult' => $allResults,
        ];
    }

    /**
     * Get hotels from multiple cities using TBOHotelCodeList
     * This replaces the random city approach with a comprehensive approach
     */
    public function getHotelsFromMultipleCities(array $cityCodes, bool $detailed = true, ?int $maxHotelsPerCity = null, string $language = 'en', int $maxPages = 5): array
    {
        $cacheKey = 'hotels_raw_candidates_'.$language.'_'.md5(json_encode($cityCodes).$maxPages);
        return \Illuminate\Support\Facades\Cache::remember($cacheKey, 86400, function() use ($cityCodes, $detailed, $maxHotelsPerCity, $language, $maxPages) {
            
            // Limit maxPages to 5 for real-time performance
            $maxPages = min($maxPages, 5);
            $allHotels = []; // Keyed by HotelCode for deduplication
            $totalPagesFetched = 0;

            // 1. Fetch Page 1 for ALL cities concurrently in large chunks
            $chunks = array_chunk($cityCodes, 150); // Increased from 60 to 150 for massive surge
            foreach ($chunks as $chunk) {
                try {
                    $responses = Http::pool(function (Pool $pool) use ($chunk, $detailed, $language) {
                        $requests = [];
                        foreach ($chunk as $cityCode) {
                            $requests[] = $pool->asJson()
                                ->withBasicAuth($this->username, $this->password)
                                ->timeout(8) // Reduced to 8s to fail fast on slow cities and avoid 504
                                ->post(rtrim($this->baseUrl, '/').'/TBOHotelCodeList', [
                                    'CityCode' => (string) $cityCode,
                                    'IsDetailedResponse' => $detailed ? 'true' : 'false',
                                    'PageNo' => 1,
                                    'Language' => $language,
                                ]);
                        }
                        return $requests;
                    });

                    $citiesToFetchMore = [];

                    foreach ($responses as $index => $response) {
                        $cityCode = $chunk[$index];
                        if ($response instanceof \Illuminate\Http\Client\Response && $response->successful()) {
                            $data = $response->json();
                            $hotels = $data['Hotels'] ?? [];
                            if (!empty($hotels) && is_array($hotels)) {
                                foreach ($hotels as $h) {
                                    $code = $h['HotelCode'] ?? $h['Code'] ?? '';
                                    if ($code) $allHotels[$code] = $h;
                                }
                                $totalPagesFetched++;

                                // Check if more pages exist
                                $totalPages = (int)($data['TotalPages'] ?? $data['TotalPageCount'] ?? 1);
                                if ($totalPages > 1 && $maxPages > 1) {
                                    $citiesToFetchMore[] = [
                                        'code' => $cityCode,
                                        'total' => min($totalPages, $maxPages)
                                    ];
                                }
                            }
                        }
                    }

                    // 2. Fetch remaining pages concurrently
                    if (!empty($citiesToFetchMore)) {
                        $pageRequests = [];
                        foreach ($citiesToFetchMore as $city) {
                            for ($p = 2; $p <= $city['total']; $p++) {
                                $pageRequests[] = ['code' => $city['code'], 'page' => $p];
                            }
                        }

                        $pageChunks = array_chunk($pageRequests, 60);
                        foreach ($pageChunks as $pChunk) {
                            $pResponses = Http::pool(function (Pool $pool) use ($pChunk, $detailed, $language) {
                                $reqs = [];
                                foreach ($pChunk as $item) {
                                    $reqs[] = $pool->asJson()
                                        ->withBasicAuth($this->username, $this->password)
                                        ->timeout(30)
                                        ->post(rtrim($this->baseUrl, '/').'/TBOHotelCodeList', [
                                            'CityCode' => (string) $item['code'],
                                            'IsDetailedResponse' => $detailed ? 'true' : 'false',
                                            'PageNo' => $item['page'],
                                            'Language' => $language,
                                        ]);
                                }
                                return $reqs;
                            });

                            foreach ($pResponses as $pRes) {
                                if ($pRes instanceof \Illuminate\Http\Client\Response && $pRes->successful()) {
                                    $pData = $pRes->json();
                                    $pHotels = $pData['Hotels'] ?? [];
                                    if (!empty($pHotels) && is_array($pHotels)) {
                                        foreach ($pHotels as $h) {
                                            $pCode = $h['HotelCode'] ?? $h['Code'] ?? '';
                                            if ($pCode) $allHotels[$pCode] = $h;
                                        }
                                        $totalPagesFetched++;
                                    }
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Exhaustive Hotel Fetch Error: '.$e->getMessage());
                }
            }

            return [
                'Status' => ['Code' => 200, 'Description' => 'Success'],
                'Hotels' => array_values($allHotels), // Convert back to indexed array
                'TotalHotels' => count($allHotels),
                'CitiesProcessed' => count($cityCodes),
                'PagesFetched' => $totalPagesFetched,
            ];
        });
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
                'Language' => $language,
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
            'PaymentMode' => 'Limit',
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
