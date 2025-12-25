<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CarRentalBookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FlightBookingController;
use App\Http\Controllers\Admin\PackageContactController;
use App\Http\Controllers\Admin\TransferBookingController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\VisaBookingController;
use App\Http\Controllers\Web\V1\AuthController;
use App\Http\Controllers\Web\V1\CarRentalController;
use App\Http\Controllers\Web\V1\FlightController;
use App\Http\Controllers\Web\V1\HomeController;
use App\Http\Controllers\Web\V1\HotelController;
use App\Http\Controllers\Web\V1\PackageController;
use App\Http\Controllers\Web\V1\PaymentController;
use App\Http\Controllers\Web\V1\ProfileController;
use App\Http\Controllers\Web\V1\RequestsController;
use App\Http\Controllers\Web\V1\TransferController;
use App\Http\Controllers\Web\V1\VisaController;
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
    Route::get('/hotel/{id}', [HotelController::class, 'show'])->name('hotel.details');

    // Room Reservation
    Route::get('/reservation', [HotelController::class, 'reservation'])->name('reservation');
    Route::post('/reservation/review', [HotelController::class, 'review'])->name('reservation.review');
    Route::post('/reservation', [HotelController::class, 'reservation'])->name('reservation.submit');

    // Hotel Booking Flow
    Route::post('/hotel/booking', [\App\Http\Controllers\Web\V1\HotelBookingController::class, 'store'])->name('hotel.booking.store');
    Route::get('/booking/success/{reference}', [HotelController::class, 'bookingSuccess'])->name('booking.success');

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

    // Visa Service
    Route::get('/visa', [VisaController::class, 'index'])->name('visa');
    Route::post('/visa/book', [VisaController::class, 'book'])->name('visa.book');

    // Profile Routes
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });

    Route::get('/requests', [\App\Http\Controllers\Web\V1\RequestsController::class, 'index'])->name('requests');

    // Logout route (requires auth)
    Route::middleware('auth')->group(function () {
        Route::post('/logout', function () {
            auth()->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect()->route('home', ['locale' => app()->getLocale()]);
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

        // Package Contacts
        Route::get('/package-contacts', [PackageContactController::class, 'index'])->name('package-contacts.index');
        Route::patch('/package-contacts/{packageContact}/status', [PackageContactController::class, 'updateStatus'])->name('package-contacts.update-status');

        // Flight Bookings
        Route::get('/flight-bookings', [FlightBookingController::class, 'index'])->name('flight-bookings.index');
        Route::patch('/flight-bookings/{flightBooking}/status', [FlightBookingController::class, 'updateStatus'])->name('flight-bookings.update-status');

        // Transfer Bookings
        Route::get('/transfer-bookings', [TransferBookingController::class, 'index'])->name('transfer-bookings.index');
        Route::patch('/transfer-bookings/{transferBooking}/status', [TransferBookingController::class, 'updateStatus'])->name('transfer-bookings.update-status');

        // Car Rental Bookings
        Route::get('/car-rental-bookings', [CarRentalBookingController::class, 'index'])->name('car-rental-bookings.index');
        Route::patch('/car-rental-bookings/{carRentalBooking}/status', [CarRentalBookingController::class, 'updateStatus'])->name('car-rental-bookings.update-status');

        // Visa Bookings
        Route::get('/visa-bookings', [VisaBookingController::class, 'index'])->name('visa-bookings.index');
        Route::patch('/visa-bookings/{visaBooking}/status', [VisaBookingController::class, 'updateStatus'])->name('visa-bookings.update-status');

        Route::get('/bookings', [\App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings');

        Route::get('/transactions', [\App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transactions');
        Route::get('/transactions/{booking}/report', [\App\Http\Controllers\Admin\TransactionController::class, 'downloadReport'])->name('transactions.report');

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
