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
        $data = $this->paymentService->apsPayment();

        $codes = ['122187', '100218', '100304', '127891'];

        $randomCode = $codes[array_rand($codes)];

        $response = $this->hotelApi->getCityHotels($randomCode);

        $hotels = $response['Hotels'] ?? [];

        if (! is_array($hotels)) {
            $hotels = json_decode(json_encode($hotels), true);
        }

        shuffle($hotels);

        $hotels1 = array_slice($hotels, 0, 4);

        $hotels2 = array_slice($hotels, 4, 3);

        $cities = City::whereIn('code', $codes)->get();

        // Fetch countries from TBO API with caching
        $countries = Cache::remember('tbo_countries', 3600, function () {
            try {
                $response = $this->hotelApi->getCountries();

                // Handle different possible response structures
                if (isset($response['CountryList']) && is_array($response['CountryList'])) {
                    return $response['CountryList'];
                }

                // If response is directly an array
                if (is_array($response) && isset($response[0])) {
                    return $response;
                }

                return [];
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
}
