<?php

namespace App\Http\Controllers\Web\V1;

use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\V1\HotelApiService;
use App\Http\Requests\Api\V1\HotelSearchRequest;

class HotelController extends Controller
{
    protected HotelApiService $hotelApi;

    public function __construct(HotelApiService $hotelApi)
    {
        $this->hotelApi = $hotelApi;
    }

    public function search(Request $request)
    {
        // $hotels = $this->hotelApi->searchHotel($request->all());

        return view('Web.hotels', [
            // 'hotels' => $hotels
        ]);
    }

    public function getHotels($cityCode)
    {
        $response = $this->hotelApi->getHotels($cityCode);

        $hotels = $response['Hotels'] ?? [];

        $clean = collect($hotels)->map(function ($h) {
            return [
                'name' => $h['HotelName'] ?? '',
                'code' => $h['HotelCode'] ?? '',
                'address' => $h['Address'] ?? '',
            ];
        });

        return response()->json($clean->values());
    }

    public function getCityHotels($cityCode)
    {
        $response = $this->hotelApi->getCityHotels($cityCode);

        $hotels = $response['Hotels'] ?? [];


        return view('Web.hotels', [
            
            'hotels' => $hotels
        ]);
    }

    public function getAllHotels()
    {
       $cities = City::select('code')->get();

       $randomCity = $cities->random();

       $response = $this->hotelApi->getCityHotels($randomCity->code);

        $hotels = $response['Hotels'] ?? [];


        return view('Web.hotels', [
            
            'hotels' => $hotels
        ]);
    }

    
}
