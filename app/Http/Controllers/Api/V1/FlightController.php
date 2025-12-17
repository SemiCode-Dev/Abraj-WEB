<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreFlightBookingRequest;
use App\Services\Api\V1\FlightService;
use Illuminate\Http\JsonResponse;

class FlightController extends Controller
{
    protected $flightService;

    public function __construct(FlightService $flightService)
    {
        $this->flightService = $flightService;
    }

    public function store(StoreFlightBookingRequest $request): JsonResponse
    {
        $booking = $this->flightService->createBooking($request->validated());
        return response()->json(['success' => true, 'message' => 'Flight booking created successfully', 'data' => $booking], 201);
    }
}
