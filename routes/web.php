<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

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

// Language switcher route
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'en'])) {
        session(['locale' => $locale]);
        App::setLocale($locale);
        
        // Get the current URL and replace locale
        $currentUrl = url()->previous();
        $parsedUrl = parse_url($currentUrl);
        $path = $parsedUrl['path'] ?? '/';
        
        // Extract current locale from path
        $pathSegments = explode('/', trim($path, '/'));
        if (in_array($pathSegments[0] ?? '', ['ar', 'en'])) {
            $pathSegments[0] = $locale;
        } else {
            array_unshift($pathSegments, $locale);
        }
        
        $newPath = '/' . implode('/', $pathSegments);
        if (isset($parsedUrl['query'])) {
            $newPath .= '?' . $parsedUrl['query'];
        }
        
        return redirect($newPath);
    }
    return redirect()->back();
})->name('lang.switch');

// Routes with locale prefix
Route::prefix('{locale}')->where(['locale' => 'ar|en'])->group(function () {
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

    // Contact Us
    Route::get('/contact', function () {
        return view('contact');
    })->name('contact');
});

// Default routes (redirect to Arabic)
Route::get('/', function () {
    return redirect('/ar');
});

Route::get('/hotels', function () {
    return redirect('/ar/hotels');
});

Route::get('/hotel/{id}', function ($id) {
    return redirect("/ar/hotel/{$id}");
});

Route::get('/reservation', function () {
    return redirect('/ar/reservation');
});

Route::get('/contact', function () {
    return redirect('/ar/contact');
});
