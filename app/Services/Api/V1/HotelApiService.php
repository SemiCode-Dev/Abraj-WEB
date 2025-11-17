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
        return Http::withBasicAuth($this->username, $this->password)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post($this->baseUrl.$endpoint, $payload)
            ->json();
    }

    public function searchHotel(array $data)
    {
        $payload = [
            "CheckIn" => $data['CheckIn'],
            "CheckOut" => $data['CheckOut'],
            "HotelCodes" => $data['HotelCodes'],
            "PaxRooms[Adults]" => $data['PaxRooms']['Adults'],
            // "PaxRooms[Children]" => $data['PaxRooms']['Children'],
            "GuestNationality" => "SA", 
            "IsDetailedResponse" => "true"
        ];
        return $this->sendRequest('Search', $payload);
    }

    public function getHotels($cityCode)
    {
        $payload = [
            "CityCode" => $cityCode,
            "IsDetailedResponse" => "false"
        ];

        return $this->sendRequest('TBOHotelCodeList', $payload);
    }
}
