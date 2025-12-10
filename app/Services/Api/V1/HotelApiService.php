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

        return Http::withBasicAuth($this->username, $this->password)
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

        return $this->sendRequest('Search', $payload);
    }

    public function getHotels($cityCode)
    {
        $payload = [
            'CityCode' => $cityCode,
            'IsDetailedResponse' => 'false',
        ];

        return $this->sendRequest('TBOHotelCodeList', $payload);
    }

    public function getCityHotels($cityCode)
    {
        $payload = [
            'CityCode' => $cityCode,
            'IsDetailedResponse' => 'true',
        ];

        return $this->sendRequest('TBOHotelCodeList', $payload);
    }

    public function getCountries()
    {
        $url = rtrim($this->baseUrl, '/').'/CountryList';

        return Http::withBasicAuth($this->username, $this->password)
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
}
