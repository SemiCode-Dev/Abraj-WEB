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
        // CACHE DISABLED FOR TESTING - Check actual load time
        // Cache payment data (doesn't change often)
        // $data = Cache::remember('aps_payment_data', 1800, function () {
        //     return $this->paymentService->apsPayment();
        // });
        $data = $this->paymentService->apsPayment();

        // Get limited city codes for homepage (first 15 cities only for faster loading)
        $cityCodes = City::whereNotNull('code')
            ->where('code', '!=', '')
            ->limit(15)
            ->pluck('code')
            ->toArray();

        // If no cities in database, use empty array (will return empty hotels)
        if (empty($cityCodes)) {
            $cityCodes = [];
        }

        // Get hotels from multiple cities using TBOHotelCodeList
        // CACHE DISABLED FOR TESTING - Check actual load time
        // Cache for 2 hours to improve performance (longer cache for homepage)
        // $cacheKey = 'featured_hotels_homepage_'.md5(implode(',', $cityCodes));
        // $response = Cache::remember($cacheKey, 7200, function () use ($cityCodes) {
        try {
            if (empty($cityCodes)) {
                $response = [
                    'Status' => [
                        'Code' => 200,
                        'Description' => 'Success',
                    ],
                    'Hotels' => [],
                ];
            } else {
                // Get very limited hotels from each city (only 3 per city for homepage speed)
                // This reduces API calls significantly
                $response = $this->hotelApi->getHotelsFromMultipleCities($cityCodes, true, 3);
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch featured hotels: '.$e->getMessage());

            $response = [
                'Status' => [
                    'Code' => 500,
                    'Description' => 'Error',
                ],
                'Hotels' => [],
            ];
        }
        // });

        $hotels = $response['Hotels'] ?? [];

        if (! is_array($hotels)) {
            $hotels = json_decode(json_encode($hotels), true);
        }

        // Shuffle to show variety
        shuffle($hotels);

        $hotels1 = array_slice($hotels, 0, 4);

        $hotels2 = array_slice($hotels, 4, 3);

        // Get cities for display (limit to first 10 for performance)
        // CACHE DISABLED FOR TESTING - Check actual load time
        // Use cache for cities query
        // $cities = Cache::remember('homepage_cities', 3600, function () {
        //     return City::whereNotNull('code')
        //         ->where('code', '!=', '')
        //         ->limit(10)
        //         ->get();
        // });
        $cities = City::whereNotNull('code')
            ->where('code', '!=', '')
            ->limit(10)
            ->get();

        // Fetch countries from TBO API
        // CACHE DISABLED FOR TESTING - Check actual load time
        // Fetch countries from TBO API with caching (longer cache - 24 hours)
        // $countries = Cache::remember('tbo_countries', 86400, function () {
        try {
            $response = $this->hotelApi->getCountries();

            // Handle different possible response structures
            if (isset($response['CountryList']) && is_array($response['CountryList'])) {
                $countries = $response['CountryList'];
            } elseif (is_array($response) && isset($response[0])) {
                // If response is directly an array
                $countries = $response;
            } else {
                $countries = [];
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch countries from TBO API: '.$e->getMessage());

            $countries = [];
        }
        // });

        return view('Web.home', [
            'cities' => $cities,
            'hotels' => $hotels1,
            'hotels2' => $hotels2,
            'data' => $data,
            'countries' => $countries,
        ]);
    }
}
