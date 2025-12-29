<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CarRentalController;
use App\Http\Controllers\Api\V1\FlightController;
use App\Http\Controllers\Api\V1\PackageController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\TransferController;
use App\Http\Controllers\Api\V1\VisaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// api/v1/login - User login
Route::post('/login', [AuthController::class, 'login']);
// api/v1/register - User registration
Route::post('/login', [AuthController::class, 'login']);
// api/v1/register - User registration
Route::post('/register', [AuthController::class, 'register']);

// OTP Routes
Route::post('/auth/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp']);

// Password Reset
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    // api/v1/logout - User logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // api/v1/profile - User profile
    Route::get('/profile', [ProfileController::class, 'profile']);
    Route::post('/profile', [ProfileController::class, 'updateProfile']);
});

// Packages
Route::get('/packages', [PackageController::class, 'index']);
Route::get('/packages/{id}', [PackageController::class, 'show']);
Route::post('/packages/book', [PackageController::class, 'store']);

// Flight Booking
Route::post('/flights/book', [FlightController::class, 'store']);

// Transfer Booking
Route::post('/transfers/book', [TransferController::class, 'store']);

// Car Rental Booking
Route::post('/car-rentals/book', [CarRentalController::class, 'store']);

// Visa Booking
Route::post('/visas/book', [VisaController::class, 'store']);

// ============================================
// DEVELOPMENT HELPER - Get OTP from Database
// REMOVE THIS IN PRODUCTION!
// ============================================
Route::get('/dev/get-otp/{identifier}', function ($identifier) {
    $user = \App\Models\User::where('email', $identifier)
        ->orWhere('phone', str_replace('+', '', preg_replace('/[^0-9+]/', '', $identifier)))
        ->first();

    if (! $user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    $otp = \App\Models\Otp::where('user_id', $user->id)->first();

    if (! $otp) {
        return response()->json(['error' => 'No OTP found. Request one first.'], 404);
    }

    return response()->json([
        'otp' => $otp->token,
        'expires_at' => $otp->expires_at,
        'attempts' => $otp->attempts,
        'verified' => $otp->verified ? 'yes' : 'no',
    ]);
});
