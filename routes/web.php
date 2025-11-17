<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\V1\HomeController;
use App\Http\Controllers\Web\V1\HotelController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [
        'localeSessionRedirect',
        'localizationRedirect',
        'localeViewPath'
    ]
], function () {

    // Home
    Route::get('/',[HomeController::class,'index'])->name('home');



    // Hotels

    Route::get('/get-hotels/{cityCode}', [HotelController::class, 'getHotels']);
    Route::get('/hotels', [HotelController::class, 'search'])->name('hotels.search');

    
    // Hotel Details with Rooms
    Route::get('/hotel/{id}', function ($id) {
        return view('Web.hotel-details', ['hotelId' => $id]);
    })->name('hotel.details');

    // Room Reservation
    Route::get('/reservation', function () {
        return view('Web.reservation');
    })->name('reservation');

    // Contact Us
    Route::get('/contact', function () {
        return view('Web.contact');
    })->name('contact');

});
