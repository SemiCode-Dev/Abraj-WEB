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

    public function getTypes(\Illuminate\Http\Request $request): JsonResponse
    {
        if ($request->has('lang')) {
            app()->setLocale($request->lang);
        }

        $types = [
            'tourist' => __('Tourist Visa'),
            'business' => __('Business Visa'),
            'transit' => __('Transit Visa'),
            'work' => __('Work Visa'),
            'student' => __('Student Visa'),
            'other' => __('Other'),
        ];

        return response()->json([
            'success' => true,
            'data' => $types,
        ]);
    }

    public function getCountries(\Illuminate\Http\Request $request): JsonResponse
    {
        if ($request->has('lang')) {
            app()->setLocale($request->lang);
        }

        $countries = \App\Models\Country::all()->map(function ($country) {
            return [
                'id' => $country->id,
                'name' => $country->locale_name,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $countries,
        ]);
    }

    public function getCities(\Illuminate\Http\Request $request): JsonResponse
    {
        if ($request->has('lang')) {
            app()->setLocale($request->lang);
        }

        $cities = \App\Models\City::all()->map(function ($city) {
            return [
                'id' => $city->id,
                'name' => $city->locale_name,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $cities,
        ]);
    }

    public function getNationalities(\Illuminate\Http\Request $request): JsonResponse
    {
        if ($request->has('lang')) {
            app()->setLocale($request->lang);
        }

        $nationalities = \App\Models\Country::all()->map(function ($country) {
            return [
                'id' => $country->id,
                'name' => $country->locale_nationality,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $nationalities,
        ]);
    }
}
