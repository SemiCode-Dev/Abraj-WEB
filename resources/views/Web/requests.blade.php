@extends('Web.layouts.app')

@section('title', __('Requests') . ' - ABRAJ STAY')

@section('content')
    <section class="bg-gradient-to-r from-orange-500 via-orange-600 to-blue-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ __('My Requests') }}</h1>
            <p class="text-orange-100 text-lg max-w-2xl mx-auto">
                {{ __('View and manage your booking requests') }}
            </p>
        </div>
    </section>

    <section class="py-16 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (!$hasBookings)
                {{-- No Bookings - Show Browse Hotels Button --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                    <div class="text-center py-12">
                        <div
                            class="w-20 h-20 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-list text-3xl text-orange-600 dark:text-orange-400"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ __('No Requests Yet') }}
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            {{ __('Your booking requests will appear here once you make a reservation.') }}
                        </p>
                        <a href="{{ route('all.hotels') }}"
                            class="inline-block bg-gradient-to-r from-orange-500 to-orange-600 text-white px-8 py-4 rounded-xl hover:from-orange-600 hover:to-orange-700 transition font-semibold shadow-lg transform hover:scale-105">
                            <i class="fas fa-hotel mr-2"></i>
                            {{ __('Browse Hotels') }}
                        </a>
                    </div>
                </div>
            @else
                {{-- Has Bookings - Display Them --}}
                <div class="space-y-6">
                    {{-- Hotel Bookings --}}
                    @if ($hotelBookings->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center">
                                <i class="fas fa-hotel text-orange-600 mr-3"></i>
                                {{ __('Hotel Bookings') }}
                            </h2>
                            <div class="space-y-4">
                                @foreach ($hotelBookings as $booking)
                                    <div
                                        class="border border-gray-200 dark:border-gray-700 rounded-xl p-6 hover:shadow-md transition">
                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                                        {{ app()->getLocale() == 'ar' ? $booking->hotel_name_ar ?? $booking->hotel_name : $booking->hotel_name_en ?? ($booking->hotel_name ?? __('Hotel Booking')) }}
                                                    </h3>
                                                    <span
                                                        class="px-3 py-1 rounded-full text-xs font-semibold
                                                    @if ($booking->booking_status === 'confirmed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @elseif($booking->booking_status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @elseif($booking->booking_status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 @endif">
                                                        {{ __(ucfirst($booking->booking_status)) }}
                                                    </span>
                                                </div>
                                                <div
                                                    class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-600 dark:text-gray-400">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-hashtag text-orange-600 mr-2 w-4"></i>
                                                        <span>{{ __('Reference') }}:
                                                            <strong>{{ $booking->booking_reference }}</strong></span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <i class="fas fa-bed text-orange-600 mr-2 w-4"></i>
                                                        <span>{{ $booking->room_name ?? __('Room') }}</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <i class="fas fa-calendar-check text-orange-600 mr-2 w-4"></i>
                                                        <span>{{ __('Check-in') }}:
                                                            {{ $booking->check_in->format('d M Y') }}</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <i class="fas fa-calendar-times text-orange-600 mr-2 w-4"></i>
                                                        <span>{{ __('Check-out') }}:
                                                            {{ $booking->check_out->format('d M Y') }}</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <i class="fas fa-money-bill-wave text-orange-600 mr-2 w-4"></i>
                                                        <span><strong>{{ number_format($booking->total_price, 2) }}
                                                                {{ $booking->currency }}</strong></span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <i class="fas fa-credit-card text-orange-600 mr-2 w-4"></i>
                                                        <span
                                                            class="px-2 py-1 rounded text-xs font-semibold
                                                        @if ($booking->payment_status === 'paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                        @elseif($booking->payment_status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                        @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                                            {{ __('Payment') }}:
                                                            {{ __(ucfirst($booking->payment_status)) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                @if ($booking->confirmation_number)
                                                    <div
                                                        class="mt-3 flex items-center text-sm text-green-600 dark:text-green-400">
                                                        <i class="fas fa-check-circle mr-2"></i>
                                                        <span>{{ __('Confirmation') }}:
                                                            <strong>{{ $booking->confirmation_number }}</strong></span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex flex-col gap-2">
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $booking->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Flight Bookings --}}
                    @if ($flightBookings->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center">
                                <i class="fas fa-plane text-orange-600 mr-3"></i>
                                {{ __('Flight Bookings') }}
                            </h2>
                            <div class="space-y-4">
                                @foreach ($flightBookings as $booking)
                                    <div
                                        class="border border-gray-200 dark:border-gray-700 rounded-xl p-6 hover:shadow-md transition">
                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                                        {{ $booking->originAirport->locale_name ?? __('N/A') }}
                                                        <i
                                                            class="fas {{ app()->getLocale() == 'ar' ? 'fa-arrow-left' : 'fa-arrow-right' }} mx-2 text-sm text-gray-400"></i>
                                                        {{ $booking->destinationAirport->locale_name ?? __('N/A') }}
                                                    </h3>
                                                    <span
                                                        class="px-3 py-1 rounded-full text-xs font-semibold
                                                    @if ($booking->status === 'confirmed') bg-green-100 text-green-800
                                                    @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                        {{ __(ucfirst($booking->status)) }}
                                                    </span>
                                                </div>
                                                <div
                                                    class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-600 dark:text-gray-400">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-calendar text-orange-600 mr-2 w-4"></i>
                                                        <span>{{ __('Departure') }}:
                                                            {{ \Carbon\Carbon::parse($booking->departure_date)->format('d M Y') }}</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <i class="fas fa-users text-orange-600 mr-2 w-4"></i>
                                                        <span>{{ $booking->adults }} {{ __('Adults') }},
                                                            {{ $booking->children }} {{ __('Children') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $booking->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Car Rental Bookings --}}
                    @if ($carRentalBookings->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center">
                                <i class="fas fa-car text-orange-600 mr-3"></i>
                                {{ __('Car Rental Bookings') }}
                            </h2>
                            <div class="space-y-4">
                                @foreach ($carRentalBookings as $booking)
                                    <div
                                        class="border border-gray-200 dark:border-gray-700 rounded-xl p-6 hover:shadow-md transition">
                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                                        {{ __('Car Rental') }}
                                                    </h3>
                                                    <span
                                                        class="px-3 py-1 rounded-full text-xs font-semibold
                                                    @if ($booking->status === 'confirmed') bg-green-100 text-green-800
                                                    @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                        {{ __(ucfirst($booking->status)) }}
                                                    </span>
                                                </div>
                                                <div
                                                    class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-600 dark:text-gray-400">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-map-marker-alt text-orange-600 mr-2 w-4"></i>
                                                        <span>
                                                            {{ app()->getLocale() == 'ar' ? $booking->destinationCity->name_ar ?? $booking->destinationCity->name : $booking->destinationCity->name }},
                                                            {{ app()->getLocale() == 'ar' ? $booking->destinationCountry->name_ar ?? $booking->destinationCountry->name : $booking->destinationCountry->name }}
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <i class="fas fa-calendar text-orange-600 mr-2 w-4"></i>
                                                        <span>{{ \Carbon\Carbon::parse($booking->pickup_date)->format('d M Y') }}</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <i class="fas fa-user-tie text-orange-600 mr-2 w-4"></i>
                                                        <span>{{ $booking->driver_option === 'with_driver' ? __('With Driver') : __('Without Driver') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $booking->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif


                    {{-- Visa Bookings --}}
                    @if ($visaBookings->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center">
                                <i class="fas fa-passport text-orange-600 mr-3"></i>
                                {{ __('Visa Applications') }}
                            </h2>
                            <div class="space-y-4">
                                @foreach ($visaBookings as $booking)
                                    <div
                                        class="border border-gray-200 dark:border-gray-700 rounded-xl p-6 hover:shadow-md transition">
                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                                        {{ app()->getLocale() == 'ar' ? $booking->country->name_ar ?? $booking->country->name : $booking->country->name }}
                                                        {{ __('Visa') }}
                                                    </h3>
                                                    <span
                                                        class="px-3 py-1 rounded-full text-xs font-semibold
                                                    @if ($booking->status === 'approved') bg-green-100 text-green-800
                                                    @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                        {{ __(ucfirst($booking->status)) }}
                                                    </span>
                                                </div>
                                                <div
                                                    class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-600 dark:text-gray-400">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-user text-orange-600 mr-2 w-4"></i>
                                                        <span>{{ $booking->name }}</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <i class="fas fa-clock text-orange-600 mr-2 w-4"></i>
                                                        <span>{{ __('Duration') }}: {{ $booking->duration }}
                                                            {{ __('Days') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $booking->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Package Inquiries --}}
                    @if ($packageContacts->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center">
                                <i class="fas fa-box-open text-orange-600 mr-3"></i>
                                {{ __('Package Inquiries') }}
                            </h2>
                            <div class="space-y-4">
                                @foreach ($packageContacts as $contact)
                                    <div
                                        class="border border-gray-200 dark:border-gray-700 rounded-xl p-6 hover:shadow-md transition">
                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                                        {{ $contact->package->locale_title ?? ($contact->package->title ?? __('Package Inquiry')) }}
                                                    </h3>
                                                    <span
                                                        class="px-3 py-1 rounded-full text-xs font-semibold
                                                        @if ($contact->status === 'contacted') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                        @elseif($contact->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 @endif">
                                                        {{ __(ucfirst($contact->status)) }}
                                                    </span>
                                                </div>
                                                <div
                                                    class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-600 dark:text-gray-400">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-user text-orange-600 mr-2 w-4"></i>
                                                        <span>{{ $contact->name }}</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <i class="fas fa-envelope text-orange-600 mr-2 w-4"></i>
                                                        <span>{{ $contact->email }}</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <i class="fas fa-phone text-orange-600 mr-2 w-4"></i>
                                                        <span>{{ $contact->phone }}</span>
                                                    </div>
                                                    @if ($contact->package)
                                                        <div class="flex items-center">
                                                            <i class="fas fa-clock text-orange-600 mr-2 w-4"></i>
                                                            <span>{{ $contact->package->locale_duration ?? ($contact->package->duration ?? __('Duration')) }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                @if ($contact->message)
                                                    <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                                            <i class="fas fa-comment-dots text-orange-600 mr-2"></i>
                                                            {{ $contact->message }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex flex-col gap-2">
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $contact->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Browse More Hotels Button --}}
                    <div class="text-center pt-8">
                        <a href="{{ route('all.hotels') }}"
                            class="inline-block bg-gradient-to-r from-orange-500 to-orange-600 text-white px-8 py-4 rounded-xl hover:from-orange-600 hover:to-orange-700 transition font-semibold shadow-lg transform hover:scale-105">
                            <i class="fas fa-hotel mr-2"></i>
                            {{ __('Browse More Hotels') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
