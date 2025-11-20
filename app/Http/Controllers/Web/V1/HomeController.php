<?php

namespace App\Http\Controllers\Web\V1;

use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\V1\HotelApiService;

class HomeController extends Controller
{
    protected HotelApiService $hotelApi;

    public function __construct(HotelApiService $hotelApi)
    {
        $this->hotelApi = $hotelApi;
    }

    public function index()
    {

    $data = [
        "command" => "PURCHASE",
        "access_code" => env("APS_ACCESS_CODE"),
        "merchant_identifier" => env("APS_MERCHANT_ID"),
        "merchant_reference" => uniqid("order_"),
        "amount" => 100,
        "currency" => "SAR",
        "language" => "en",
        "customer_email" => "test@example.com",
        "return_url" => route("aps.callback"),
    ];

    $data["signature"] = $this->apsSignature($data, env("APS_SHA_REQUEST"));


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

        return view('Web.home', [
            'cities' => $cities,
            'hotels' => $hotels1,
            'hotels2' => $hotels2,
            'data' => $data,
        ]);
    }

    public function apsSignature($data, $phrase)
    {
        ksort($data);
        $str = $phrase;

        foreach ($data as $key => $value) {
            $str .= "$key=$value";
        }

        $str .= $phrase;

        return hash('sha256', $str);
    }

  

    public function apsCallback(Request $request)
{
    $data = $request->all();

    // Extract APS signature
    $receivedSignature = $data["signature"] ?? null;
    unset($data["signature"]);

    // Validate signature
    $generatedSignature = $this->apsSignature($data, env("APS_SHA_RESPONSE"));

    if ($receivedSignature !== $generatedSignature) {
        return "Invalid signature — payment not trusted";
    }

    if ($data["status"] == "14") {
        // Payment success
        return "Payment Successful: Order " . $data["merchant_reference"];
    }

    return "Payment Failed: " . $data["response_message"];
}

}
