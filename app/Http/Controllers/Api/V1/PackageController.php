<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StorePackageBookingRequest;
use App\Services\Api\V1\PackageService;
use Illuminate\Http\JsonResponse;

class PackageController extends Controller
{
    protected $packageService;

    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
    }

    public function index(): JsonResponse
    {
        $packages = $this->packageService->getAllPackages();
        return response()->json(['success' => true, 'data' => $packages]);
    }

    public function show($id): JsonResponse
    {
        $package = $this->packageService->getPackageById($id);
        return response()->json(['success' => true, 'data' => $package]);
    }

    public function store(StorePackageBookingRequest $request): JsonResponse
    {
        $contact = $this->packageService->createPackageContact($request->validated());
        return response()->json(['success' => true, 'message' => 'Inquiry sent successfully', 'data' => $contact], 201);
    }
}
