<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreCarRentalBookingRequest;
use App\Services\Api\V1\CarRentalService;
use Illuminate\Http\JsonResponse;

class CarRentalController extends Controller
{
    protected $carRentalService;

    public function __construct(CarRentalService $carRentalService)
    {
        $this->carRentalService = $carRentalService;
    }

    public function store(StoreCarRentalBookingRequest $request): JsonResponse
    {
        $booking = $this->carRentalService->createBooking($request->validated());
        return response()->json(['success' => true, 'message' => 'Car rental booking created successfully', 'data' => $booking], 201);
    }
}
