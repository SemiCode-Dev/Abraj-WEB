<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreVisaBookingRequest;
use App\Services\Api\V1\VisaService;
use Illuminate\Http\JsonResponse;

class VisaController extends Controller
{
    protected $visaService;

    public function __construct(VisaService $visaService)
    {
        $this->visaService = $visaService;
    }

    public function store(StoreVisaBookingRequest $request): JsonResponse
    {
        $booking = $this->visaService->createBooking($request->validated());
        return response()->json(['success' => true, 'message' => 'Visa inquiry created successfully', 'data' => $booking], 201);
    }
}
