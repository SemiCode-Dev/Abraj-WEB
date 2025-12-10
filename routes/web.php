<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Web\V1\AuthController;
use App\Http\Controllers\Web\V1\CarRentalController;
use App\Http\Controllers\Web\V1\FlightController;
use App\Http\Controllers\Web\V1\HomeController;
use App\Http\Controllers\Web\V1\HotelController;
use App\Http\Controllers\Web\V1\PackageController;
use App\Http\Controllers\Web\V1\PaymentController;
use App\Http\Controllers\Web\V1\TransferController;
use Illuminate\Support\Facades\Route;
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
        'localeViewPath',
    ],
], function () {

    // auth

    Route::post('/site/login', [AuthController::class, 'login'])->name('site.login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    // Home
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::post('/aps/callback', [PaymentController::class, 'apsCallback'])->name('aps.callback');

    // Hotels

    Route::get('/get-hotels/{cityCode}', [HotelController::class, 'getHotels']);
    Route::get('/get-cities/{countryCode}', [HotelController::class, 'getCitiesByCountry']);
    Route::get('/hotels/{cityCode}', [HotelController::class, 'getCityHotels'])->name('city.hotels');
    Route::get('/hotel', [HotelController::class, 'search'])->name('hotels.search');
    Route::post('/hotel/search', [HotelController::class, 'search'])->name('hotel.search');
    Route::get('/hotels', [HotelController::class, 'getAllHotels'])->name('all.hotels');

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

    // Packages
    Route::get('/packages', [PackageController::class, 'index'])->name('packages');
    Route::get('/package/{id}', [PackageController::class, 'show'])->name('package.details');
    Route::post('/package/{id}/contact', [PackageController::class, 'contact'])->name('package.contact');

    // Flights
    Route::get('/flights', [FlightController::class, 'index'])->name('flights');
    Route::post('/flights/book', [FlightController::class, 'book'])->name('flights.book');
    Route::get('/flights/cities/{countryId}', [FlightController::class, 'getCitiesByCountry'])->name('flights.cities');

    // Transfer
    Route::get('/transfer', [TransferController::class, 'index'])->name('transfer');
    Route::post('/transfer/book', [TransferController::class, 'book'])->name('transfer.book');
    Route::get('/transfer/cities/{countryId}', [TransferController::class, 'getCitiesByCountry'])->name('transfer.cities');

    // Car Rental
    Route::get('/car-rental', [CarRentalController::class, 'index'])->name('car-rental');
    Route::post('/car-rental/book', [CarRentalController::class, 'book'])->name('car-rental.book');
    Route::get('/car-rental/cities/{countryId}', [CarRentalController::class, 'getCitiesByCountry'])->name('car-rental.cities');

    // Profile & Requests Routes (temporarily accessible without auth for development)
    Route::get('/profile', function () {
        return view('Web.profile');
    })->name('profile');

    Route::get('/requests', function () {
        return view('Web.requests');
    })->name('requests');

    // Logout route (requires auth)
    Route::middleware('auth')->group(function () {
        Route::post('/logout', function () {
            auth()->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect()->route('home');
        })->name('logout');
    });

});

// Admin Authentication Routes (without middleware protection)
Route::prefix(LaravelLocalization::setLocale().'/admin')->name('admin.')->middleware([
    'localeSessionRedirect',
    'localizationRedirect',
    'localeViewPath',
])->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        })->name('index');

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/users', [UsersController::class, 'index'])->name('users');

        Route::get('/bookings', function () {
            return view('Admin.bookings');
        })->name('bookings');

        Route::get('/transactions', function () {
            return view('Admin.transactions');
        })->name('transactions');

        Route::get('/reviews', function () {
            return view('Admin.reviews');
        })->name('reviews');

        Route::get('/reports', function () {
            return view('Admin.reports');
        })->name('reports');

        Route::get('/settings', function () {
            return view('Admin.settings');
        })->name('settings');
    });
});
