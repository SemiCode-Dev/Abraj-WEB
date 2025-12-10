<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Services\Api\V1\HotelApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HotelController extends Controller
{
    protected HotelApiService $hotelApi;

    public function __construct(HotelApiService $hotelApi)
    {
        $this->hotelApi = $hotelApi;
    }

    public function search(Request $request)
    {
        try {
            $hotelCodes = $request->input('HotelCodes');

            // Validate HotelCodes
            if (empty($hotelCodes)) {
                return response()->json([
                    'Status' => [
                        'Code' => 400,
                        'Description' => 'Hotel Codes can not be null or empty',
                    ],
                ], 400);
            }

            // Handle PaxRooms - can be array or single object
            $paxRooms = $request->input('PaxRooms');
            if (! is_array($paxRooms) || empty($paxRooms)) {
                $paxRooms = [
                    [
                        'Adults' => $request->input('PaxRooms.0.Adults', $request->input('PaxRooms.Adults', 1)),
                        'Children' => $request->input('PaxRooms.0.Children', $request->input('PaxRooms.Children', 0)),
                        'ChildrenAges' => $request->input('PaxRooms.0.ChildrenAges', []),
                    ],
                ];
            }

            $data = [
                'CheckIn' => $request->input('CheckIn'),
                'CheckOut' => $request->input('CheckOut'),
                'HotelCodes' => $hotelCodes,
                'GuestNationality' => $request->input('GuestNationality', 'AE'),
                'PaxRooms' => $paxRooms,
                'ResponseTime' => $request->input('ResponseTime', 18),
                'IsDetailedResponse' => $request->input('IsDetailedResponse', true),
                'Filters' => $request->input('Filters', [
                    'Refundable' => true,
                    'NoOfRooms' => 0,
                    'MealType' => 'All',
                ]),
            ];

            $response = $this->hotelApi->searchHotel($data);

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Failed to search hotels: '.$e->getMessage());

            return response()->json([
                'Status' => [
                    'Code' => 500,
                    'Description' => __('Failed to search hotels'),
                ],
            ], 500);
        }
    }

    public function getHotels($cityCode)
    {
        try {
            $response = $this->hotelApi->getCityHotels($cityCode);

            $hotels = $response['Hotels'] ?? [];

            if (! is_array($hotels)) {
                $hotels = json_decode(json_encode($hotels), true);
            }

            $formattedHotels = collect($hotels)->map(function ($hotel) {
                return [
                    'HotelCode' => $hotel['HotelCode'] ?? '',
                    'HotelName' => $hotel['HotelName'] ?? '',
                    'HotelRating' => $hotel['HotelRating'] ?? '',
                    'Address' => $hotel['Address'] ?? '',
                    'CityName' => $hotel['CityName'] ?? '',
                    'CountryName' => $hotel['CountryName'] ?? '',
                    'ImageUrl' => isset($hotel['ImageUrls'][0]['ImageUrl']) ? $hotel['ImageUrls'][0]['ImageUrl'] : '',
                ];
            })->filter(function ($hotel) {
                return ! empty($hotel['HotelCode']);
            });

            return response()->json($formattedHotels->values());
        } catch (\Exception $e) {
            Log::error('Failed to fetch hotels from TBO API: '.$e->getMessage());

            return response()->json(['error' => __('Failed to fetch hotels')], 500);
        }
    }

    public function getCityHotels($cityCode)
    {
        $response = $this->hotelApi->getCityHotels($cityCode);

        $hotels = $response['Hotels'] ?? [];

        return view('Web.hotels', [

            'hotels' => $hotels,
        ]);
    }

    public function getAllHotels()
    {
        $cities = City::select('code')->get();

        $randomCity = $cities->random();

        $response = $this->hotelApi->getCityHotels($randomCity->code);

        $hotels = $response['Hotels'] ?? [];

        return view('Web.hotels', [

            'hotels' => $hotels,
        ]);
    }

    public function getCitiesByCountry($countryCode)
    {
        try {
            $response = $this->hotelApi->getCitiesByCountry($countryCode);

            // Handle different possible response structures
            $cities = [];
            if (isset($response['CityList']) && is_array($response['CityList'])) {
                $cities = $response['CityList'];
            } elseif (is_array($response) && isset($response[0])) {
                $cities = $response;
            }

            // Transform the response to match expected format
            $formattedCities = collect($cities)->map(function ($city) {
                return [
                    'Name' => $city['CityName'] ?? $city['Name'] ?? '',
                    'Name_ar' => $city['CityName'] ?? $city['Name'] ?? '', // TBO API might not have Arabic name
                    'Code' => $city['CityCode'] ?? $city['Code'] ?? '',
                ];
            })->filter(function ($city) {
                return ! empty($city['Code']);
            });

            return response()->json($formattedCities->values());
        } catch (\Exception $e) {
            Log::error('Failed to fetch cities from TBO API: '.$e->getMessage());

            return response()->json(['error' => 'Failed to fetch cities'], 500);
        }
    }
}
