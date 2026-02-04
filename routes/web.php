<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CarRentalBookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FlightBookingController;
use App\Http\Controllers\Admin\PackageContactController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\VisaBookingController;
use App\Http\Controllers\Web\V1\AuthController;
use App\Http\Controllers\Web\V1\CarRentalController;
use App\Http\Controllers\Web\V1\ContactController;
use App\Http\Controllers\Web\V1\FlightController;
use App\Http\Controllers\Web\V1\HomeController;
use App\Http\Controllers\Web\V1\HotelController;
use App\Http\Controllers\Web\V1\PackageController;
use App\Http\Controllers\Web\V1\PaymentController;
use App\Http\Controllers\Web\V1\ProfileController;
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
    Route::get('/about', [HomeController::class, 'about'])->name('about');
    Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
    Route::get('/cookies', [HomeController::class, 'cookies'])->name('cookies');
    Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
    Route::post('/aps/callback', [PaymentController::class, 'apsCallback'])->name('aps.callback');
    Route::get('/currency/{currency}', [\App\Http\Controllers\Web\V1\CurrencyController::class, 'switchCurrency'])->name('currency.switch');

    // Hotels

    Route::get('/get-hotels/{cityCode}', [HotelController::class, 'getHotels'])->name('ajax.get-hotels');

    Route::get('/hotels/{cityCode}', [HotelController::class, 'getCityHotels'])->name('city.hotels');
    Route::get('/hotel', [HotelController::class, 'getAllHotels'])->name('hotels.search');
    Route::post('/hotel/search', [HotelController::class, 'search'])->name('hotel.search');
    Route::get('/hotels', [HotelController::class, 'getAllHotels'])->name('all.hotels');
    Route::post('/hotels/load-more', [HotelController::class, 'loadMoreHotels'])->name('hotels.load-more');
    Route::post('/hotels/min-prices', [HotelController::class, 'getBatchMinPrices'])->name('hotels.min-prices');

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
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

    // Packages
    Route::get('/packages', [PackageController::class, 'index'])->name('packages');
    Route::get('/package/{id}', [PackageController::class, 'show'])->name('package.details');
    Route::post('/package/{id}/contact', [PackageController::class, 'contact'])->name('package.contact');

    // Flights
    Route::get('/flights', [FlightController::class, 'index'])->name('flights');
    Route::post('/flights/book', [FlightController::class, 'book'])->name('flights.book');

    // Car Rental
    Route::get('/car-rental', [CarRentalController::class, 'index'])->name('car-rental');
    Route::post('/car-rental/book', [CarRentalController::class, 'book'])->name('car-rental.book');

    // Visa Service
    Route::get('/visa', [VisaController::class, 'index'])->name('visa');
    Route::post('/visa/book', [VisaController::class, 'book'])->name('visa.book');

    // Shared Location Routes
    Route::get('/locations/countries/{country}/cities', [\App\Http\Controllers\Web\V1\LocationController::class, 'getCities'])->name('locations.cities');
    Route::get('/locations/countries/{country}/airports', [\App\Http\Controllers\Web\V1\LocationController::class, 'getAirports'])->name('locations.airports');

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

        // Users Management
        Route::get('/users', [UsersController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UsersController::class, 'create'])->name('users.create');
        Route::post('/users', [UsersController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UsersController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UsersController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');
        Route::patch('/users/{user}/toggle-status', [UsersController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::patch('/users/{user}/block', [UsersController::class, 'block'])->name('users.block');

        // Package Contacts
        Route::get('/package-contacts', [PackageContactController::class, 'index'])->name('package-contacts.index');
        Route::patch('/package-contacts/{packageContact}/status', [PackageContactController::class, 'updateStatus'])->name('package-contacts.update-status');

        // Flight Bookings
        Route::get('/flight-bookings', [FlightBookingController::class, 'index'])->name('flight-bookings.index');
        Route::patch('/flight-bookings/{flightBooking}/status', [FlightBookingController::class, 'updateStatus'])->name('flight-bookings.update-status');

        // Car Rental Bookings
        Route::get('/car-rental-bookings', [CarRentalBookingController::class, 'index'])->name('car-rental-bookings.index');
        Route::patch('/car-rental-bookings/{carRentalBooking}/status', [CarRentalBookingController::class, 'updateStatus'])->name('car-rental-bookings.update-status');

        // Visa Bookings
        Route::get('/visa-bookings', [VisaBookingController::class, 'index'])->name('visa-bookings.index');
        Route::patch('/visa-bookings/{visaBooking}/status', [VisaBookingController::class, 'updateStatus'])->name('visa-bookings.update-status');

        Route::get('/bookings', [\App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings');

        Route::get('/transactions', [\App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transactions');
        Route::post('/transactions/{booking}/cancel', [\App\Http\Controllers\Admin\TransactionController::class, 'cancel'])->name('transactions.cancel');
        Route::post('/transactions/{booking}/refund', [\App\Http\Controllers\Admin\TransactionController::class, 'refund'])->name('transactions.refund');
        Route::get('/transactions/{booking}/report', [\App\Http\Controllers\Admin\TransactionController::class, 'downloadReport'])->name('transactions.report');

        Route::get('/reviews', function () {
            return view('Admin.reviews');
        })->name('reviews');

        Route::get('/reports', function () {
            return view('Admin.reports');
        })->name('reports');

        Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings');
        Route::post('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');

        // Discount Codes
        Route::post('/discount-codes', [\App\Http\Controllers\Admin\DiscountCodeController::class, 'store'])->name('discount-codes.store');
        Route::delete('/discount-codes/{discountCode}', [\App\Http\Controllers\Admin\DiscountCodeController::class, 'destroy'])->name('discount-codes.destroy');
    });
});

// Test Email Route (for debugging) - Remove in production
Route::get('/test-email-debug', function () {
    try {
        $config = [
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'username' => config('mail.mailers.smtp.username'),
            'encryption' => config('mail.mailers.smtp.encryption'),
        ];

        \Illuminate\Support\Facades\Log::info('Test email attempt', $config);

        \Illuminate\Support\Facades\Mail::raw('Test OTP email. If you get this, email works!', function ($message) {
            $message->to(config('mail.mailers.smtp.username') ?: 'test@example.com')
                ->subject('Test - Laravel OTP System');
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Email sent! Check inbox.',
            'config' => $config,
        ]);
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Test email error', ['error' => $e->getMessage()]);

        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'config' => $config ?? [],
        ], 500);
    }
});
// Seeder Debugging Route - Remove in production
Route::get('/test-seeding', [\App\Http\Controllers\Web\DebugController::class, 'index']);
Route::get('/run-seeder/{class}', [\App\Http\Controllers\Web\DebugController::class, 'runSeeder']);

// Test Graph Email - Remove in production
Route::get('/test-graph-email', function () {
    try {
        \Illuminate\Support\Facades\Mail::raw('Test email from Microsoft Graph API. If you receive this, the integration is working!', function ($message) {
            $message->to('reservation@abrajstay.com')
                ->subject('Test - Microsoft Graph Integration');
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Email sent successfully via Microsoft Graph!',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
});
