<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreTransferBookingRequest;
use App\Services\Api\V1\TransferService;
use Illuminate\Http\JsonResponse;

class TransferController extends Controller
{
    protected $transferService;

    public function __construct(TransferService $transferService)
    {
        $this->transferService = $transferService;
    }

    public function store(StoreTransferBookingRequest $request): JsonResponse
    {
        $booking = $this->transferService->createBooking($request->validated());
        return response()->json(['success' => true, 'message' => 'Transfer booking created successfully', 'data' => $booking], 201);
    }
}
