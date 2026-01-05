<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Services\LocationService;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    public function __construct(
        protected LocationService $locationService
    ) {
    }

    /**
     * Get cities for a specific country with caching.
     *
     * @param int|string $country Identifier (ID or Code)
     * @return JsonResponse
     */
    public function getCities($country): JsonResponse
    {
        $cities = $this->locationService->getCities($country);
        
        return response()->json($cities)
                ->header('Cache-Control', 'public, max-age=86400');
    }

    public function getAirports($country): JsonResponse
    {
        $airports = $this->locationService->getAirports($country);
        
        return response()->json($airports)
                ->header('Cache-Control', 'public, max-age=86400');
    }
}
