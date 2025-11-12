<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('home');
})->name('home');

// Hotels Search & Listing
Route::get('/hotels', function () {
    return view('hotels');
})->name('hotels.search');

// Hotel Details with Rooms
Route::get('/hotel/{id}', function ($id) {
    return view('hotel-details', ['hotelId' => $id]);
})->name('hotel.details');

// Room Reservation
Route::get('/reservation', function () {
    return view('reservation');
})->name('reservation');
