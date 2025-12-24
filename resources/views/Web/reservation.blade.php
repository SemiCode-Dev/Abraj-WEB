@extends('Web.layouts.app')

@section('title', __('Confirm Reservation') . ' - ' . __('Book Hotels - Best Offers and Services'))

@section('content')
    <style>
        /* Forced colors to fix production build issues */
        .force-input-text {
            color: #111827 !important;
        }

        .dark .force-input-text {
            color: #ffffff !important;
        }

        .force-button {
            background-color: #ea580c !important;
            background-image: linear-gradient(to right, #ea580c, #c2410c) !important;
            color: #ffffff !important;
            border: none !important;
        }

        .force-button:hover {
            background-color: #c2410c !important;
            background-image: linear-gradient(to right, #c2410c, #9a3412) !important;
        }

        input.force-input-text:disabled,
        input.force-input-text[readonly] {
            color: #111827 !important;
            opacity: 1 !important;
            -webkit-text-fill-color: #111827 !important;
        }

        .dark input.force-input-text:disabled,
        .dark input.force-input-text[readonly] {
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
        }
    </style>

    <!-- Reservation Header -->
    <section class="bg-gradient-to-br from-orange-500 via-orange-600 to-blue-900 text-white py-16 relative overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex items-center mb-6">
                <a href="{{ route('hotel.details', ['id' => request('hotel_id', 1)]) }}?check_in={{ request('check_in') }}&check_out={{ request('check_out') }}&guests={{ request('guests') }}"
                    class="flex items-center text-white/90 hover:text-white transition-all duration-300 group">
                    <i
                        class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }} group-hover:translate-x-{{ app()->getLocale() === 'ar' ? '-1' : '1' }} transition-transform"></i>
                    <span class="font-semibold">{{ __('Back') }}</span>
                </a>
            </div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-calendar-check text-2xl"></i>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold">{{ __('Confirm Reservation') }}</h1>
            </div>
            <p class="text-orange-100 text-lg max-w-2xl">
                {{ __('Complete your information to complete the reservation process') }}</p>
        </div>
    </section>

    <!-- Reservation Form -->
    <section class="py-12 bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Reservation Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Booking Summary Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border-l-4 border-orange-600">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-hotel text-orange-600"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('Booking Summary') }}
                            </h2>
                        </div>
                        @php
                            $hotel = $hotelDetails['HotelDetails'][0] ?? null;
                            $roomName = 'Room';
                            if ($roomData && isset($roomData['Name'])) {
                                if (is_array($roomData['Name']) && count($roomData['Name']) > 0) {
                                    $roomName = $roomData['Name'][0];
                                } elseif (is_string($roomData['Name'])) {
                                    $roomName = $roomData['Name'];
                                }
                            }
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-hotel text-orange-600"></i>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                        {{ __('Hotel') }}</div>
                                </div>
                                <div class="font-bold text-gray-900 dark:text-white text-lg">
                                    @if ($hotel && isset($hotel['HotelName']))
                                        {{ $hotel['HotelName'] }}
                                    @else
                                        {{ __('International Luxury Hotel') }} {{ $hotelId }}
                                    @endif
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-300 mt-2 flex items-center">
                                    <i
                                        class="fas fa-map-marker-alt {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }} text-orange-600"></i>
                                    @if ($hotel && isset($hotel['Address']))
                                        {{ $hotel['Address'] }}@if (isset($hotel['CityName']))
                                            , {{ $hotel['CityName'] }}
                                        @endif
                                    @else
                                        {{ __('Riyadh') }}, {{ __('Saudi Arabia') }}
                                    @endif
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-bed text-orange-600"></i>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                        {{ __('Room Type') }}</div>
                                </div>
                                <div class="font-bold text-gray-900 dark:text-white text-lg">
                                    @php
                                        $displayRoomName = 'Room';
                                        if ($roomData && isset($roomData['Name'])) {
                                            if (is_array($roomData['Name']) && count($roomData['Name']) > 0) {
                                                $displayRoomName = $roomData['Name'][0];
                                            } elseif (is_string($roomData['Name'])) {
                                                $displayRoomName = $roomData['Name'];
                                            }
                                        } elseif (request('room_name')) {
                                            $displayRoomName = request('room_name');
                                        }
                                    @endphp
                                    {{ $displayRoomName }}
                                </div>
                                @if (($roomData && isset($roomData['Inclusion'])) || request('inclusion'))
                                    <div class="text-sm text-gray-600 dark:text-gray-300 mt-2 flex items-center">
                                        <i
                                            class="fas fa-utensils {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }} text-orange-600"></i>
                                        {{ $roomData['Inclusion'] ?? request('inclusion') }}
                                    </div>
                                @endif
                            </div>
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-calendar-check text-blue-600"></i>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                        {{ __('Check In') }}</div>
                                </div>
                                <div class="font-bold text-gray-900 dark:text-white text-lg">
                                    {{ $checkIn ?? request('check_in', '--') }}
                                </div>
                            </div>
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-calendar-times text-blue-600"></i>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                        {{ __('Check Out') }}</div>
                                </div>
                                <div class="font-bold text-gray-900 dark:text-white text-lg">
                                    {{ $checkOut ?? request('check_out', '--') }}
                                </div>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-moon text-green-600"></i>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                        {{ __('Nights') }}</div>
                                </div>
                                <div class="font-bold text-gray-900 dark:text-white text-lg">{{ $nights ?? 2 }}
                                    {{ __('nights') }}</div>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-users text-purple-600"></i>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                        {{ __('Guests') }}</div>
                                </div>
                                <div class="font-bold text-gray-900 dark:text-white text-lg">
                                    {{ $guests ?? request('guests', '2') }}
                                    {{ __('Guests') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Guest Information Step -->
                    <div id="guestInfoStep"
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 border-t-4 border-orange-600">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-circle text-orange-600 text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Guest Information') }}
                                </h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Please fill in your details to complete the booking') }}</p>
                            </div>
                        </div>
                        @auth
                            <div
                                class="mb-6 p-5 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 border-2 border-orange-200 dark:border-orange-800 rounded-xl hover:shadow-md transition-shadow">
                                <label class="flex items-start cursor-pointer group">
                                    <input type="checkbox" id="useAccountInfo"
                                        class="w-6 h-6 text-orange-600 rounded mt-1 cursor-pointer">
                                    <div class="{{ app()->getLocale() === 'ar' ? 'mr-4' : 'ml-4' }} flex-1">
                                        <div
                                            class="font-bold text-gray-900 dark:text-white mb-1 group-hover:text-orange-600 transition-colors">
                                            <i
                                                class="fas fa-check-circle {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                                            {{ __('Use my account information') }}
                                        </div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                            <div><i
                                                    class="fas fa-user {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                                <strong>{{ __('Name') }}:</strong> {{ auth()->user()->name }}
                                            </div>
                                            <div><i
                                                    class="fas fa-envelope {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                                <strong>{{ __('Email') }}:</strong> {{ auth()->user()->email }}
                                            </div>
                                            @if (auth()->user()->phone)
                                                <div><i
                                                        class="fas fa-phone {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                                    <strong>{{ __('Phone') }}:</strong> {{ auth()->user()->phone }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </label>
                            </div>
                        @endauth
                        <form id="reservationForm" onsubmit="return false;">
                            <div id="guestInfoSection" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="group">
                                        <label
                                            class="block text-gray-700 dark:text-gray-300 font-semibold mb-2 flex items-center gap-2">
                                            <i class="fas fa-user text-orange-600"></i>
                                            <span>{{ __('Full Name') }} <span class="text-red-500">*</span></span>
                                        </label>
                                        <input type="text" id="guestName" name="name" required
                                            class="w-full px-4 py-3.5 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-900 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all hover:border-orange-300 force-input-text">
                                    </div>
                                    <div class="group">
                                        <label
                                            class="block text-gray-700 dark:text-gray-300 font-semibold mb-2 flex items-center gap-2">
                                            <i class="fas fa-envelope text-orange-600"></i>
                                            <span>{{ __('Email') }} <span class="text-red-500">*</span></span>
                                        </label>
                                        <input type="email" id="guestEmail" name="email" required
                                            class="w-full px-4 py-3.5 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-900 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all hover:border-orange-300 force-input-text">
                                    </div>
                                </div>
                                <div class="group intl-tel-input-container">
                                    <label
                                        class="block text-gray-700 dark:text-gray-300 font-semibold mb-2 flex items-center gap-2">
                                        <i class="fas fa-phone text-orange-600"></i>
                                        <span>{{ __('Mobile Number') }} <span class="text-red-500">*</span></span>
                                    </label>
                                    <input type="tel" id="guestPhone" name="phone"
                                        value="{{ old('phone', auth()->check() ? auth()->user()->phone : '') }}" required
                                        maxlength="11"
                                        class="w-full px-4 py-3.5 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-900 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all hover:border-orange-300 force-input-text">
                                    <input type="hidden" name="phone_country_code" id="guestPhoneCountryCode"
                                        value="{{ old('phone_country_code', auth()->check() ? auth()->user()->phone_country_code : '') }}">
                                </div>
                                <div class="group">
                                    <label
                                        class="block text-gray-700 dark:text-gray-300 font-semibold mb-2 flex items-center gap-2">
                                        <i class="fas fa-sticky-note text-orange-600"></i>
                                        <span>{{ __('Special Notes (Optional)') }}</span>
                                    </label>
                                    <textarea rows="4" id="guestNotes" name="notes"
                                        class="w-full px-4 py-3.5 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-900 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all hover:border-orange-300 resize-none force-input-text"
                                        placeholder="{{ __('Any special requests or notes...') }}"></textarea>
                                </div>
                            </div>
                        </form>

                        <!-- Submit Form to Review Page -->
                        <form id="reviewForm" method="POST"
                            action="{{ route('reservation.review', ['locale' => app()->getLocale()]) }}"
                            style="display: none;">
                            @csrf
                            <input type="hidden" name="hotel_id" value="{{ request('hotel_id') }}">
                            <input type="hidden" name="booking_code" value="{{ request('booking_code') }}">
                            <input type="hidden" name="check_in" value="{{ request('check_in') }}">
                            <input type="hidden" name="check_out" value="{{ request('check_out') }}">
                            <input type="hidden" name="guests" value="{{ request('guests', 1) }}">
                            <input type="hidden" name="total_fare"
                                value="{{ isset($totalFare) ? $totalFare : request('total_fare', 0) }}">
                            <input type="hidden" name="currency"
                                value="{{ isset($currency) ? $currency : request('currency', 'USD') }}">
                            <input type="hidden" name="room_name" value="{{ request('room_name') }}">
                            <input type="hidden" name="inclusion" value="{{ request('inclusion') }}">
                            <input type="hidden" id="reviewFormName" name="name" value="">
                            <input type="hidden" id="reviewFormEmail" name="email" value="">
                            <input type="hidden" id="reviewFormPhone" name="phone" value="">
                            <input type="hidden" id="reviewFormPhoneCountryCode" name="phone_country_code"
                                value="">
                            <input type="hidden" id="reviewFormNotes" name="notes" value="">
                            <input type="hidden" id="reviewFormTerms" name="terms" value="0">
                        </form>
                    </div>

                    <!-- Review Step -->
                    <div id="reviewStep"
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 border-t-4 border-blue-600 hidden">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-clipboard-check text-blue-600 text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ __('Review Your Booking') }}</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Please review your booking details before proceeding to payment') }}</p>
                            </div>
                        </div>

                        <!-- Guest Information Review -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6 mb-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <i class="fas fa-user text-orange-600"></i>
                                {{ __('Guest Information') }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Full Name') }}</div>
                                    <div class="font-semibold text-gray-900 dark:text-white" id="reviewName">-</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Email') }}</div>
                                    <div class="font-semibold text-gray-900 dark:text-white" id="reviewEmail">-</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Mobile Number') }}
                                    </div>
                                    <div class="font-semibold text-gray-900 dark:text-white" id="reviewPhone">-</div>
                                </div>
                                <div id="reviewNotesContainer" class="hidden">
                                    <div class="text-sm text-gray-500 mb-1">{{ __('Special Notes') }}</div>
                                    <div class="font-semibold text-gray-900" id="reviewNotes">-</div>
                                </div>
                            </div>
                        </div>

                        <!-- Booking Summary Review -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6 mb-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <i class="fas fa-calendar-check text-orange-600"></i>
                                {{ __('Booking Details') }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <div class="text-sm text-gray-500 mb-1">{{ __('Hotel') }}</div>
                                    <div class="font-semibold text-gray-900">
                                        @if ($hotel && isset($hotel['HotelName']))
                                            {{ $hotel['HotelName'] }}
                                        @else
                                            {{ __('International Luxury Hotel') }} {{ $hotelId }}
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500 mb-1">{{ __('Room Type') }}</div>
                                    <div class="font-semibold text-gray-900">
                                        @php
                                            $displayRoomName = 'Room';
                                            if ($roomData && isset($roomData['Name'])) {
                                                if (is_array($roomData['Name']) && count($roomData['Name']) > 0) {
                                                    $displayRoomName = $roomData['Name'][0];
                                                } elseif (is_string($roomData['Name'])) {
                                                    $displayRoomName = $roomData['Name'];
                                                }
                                            } elseif (request('room_name')) {
                                                $displayRoomName = request('room_name');
                                            }
                                        @endphp
                                        {{ $displayRoomName }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500 mb-1">{{ __('Check In') }}</div>
                                    <div class="font-semibold text-gray-900">{{ $checkIn ?? request('check_in', '--') }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500 mb-1">{{ __('Check Out') }}</div>
                                    <div class="font-semibold text-gray-900">{{ $checkOut ?? request('check_out', '--') }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500 mb-1">{{ __('Nights') }}</div>
                                    <div class="font-semibold text-gray-900">{{ $nights ?? 2 }} {{ __('nights') }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500 mb-1">{{ __('Guests') }}</div>
                                    <div class="font-semibold text-gray-900">{{ $guests ?? request('guests', '2') }}
                                        {{ __('Guests') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Price Review -->
                        @php
                            $totalFare = 0;
                            $currency = 'USD';
                            if ($roomData && isset($roomData['TotalFare']) && $roomData['TotalFare'] > 0) {
                                $totalFare = (float) $roomData['TotalFare'];
                                $currency = $roomData['Currency'] ?? 'USD';
                            } elseif (request('total_fare')) {
                                $totalFare = (float) request('total_fare');
                                $currency = request('currency', 'USD');
                            }
                        @endphp
                        <div
                            class="bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/40 dark:to-orange-800/40 border-2 border-orange-200 dark:border-orange-800 rounded-xl p-6 mb-6">
                            <div class="flex justify-between items-center">
                                <span
                                    class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Total Amount') }}</span>
                                <span class="text-3xl font-extrabold text-orange-600">
                                    @if ($totalFare > 0)
                                        {{ number_format($totalFare, 2) }} {{ $currency }}
                                    @else
                                        {{ __('N/A') }}
                                    @endif
                                </span>
                            </div>
                            <div class="text-xs text-gray-600 dark:text-gray-400 mt-2 flex items-center gap-1">
                                <i class="fas fa-check-circle text-green-600"></i>
                                {{ __('including all taxes and fees') }}
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-4">
                            <button type="button" id="backToGuestInfoBtn"
                                class="flex-1 bg-gray-200 text-gray-700 py-4 rounded-xl font-bold text-lg hover:bg-gray-300 transition-all duration-300 flex items-center justify-center gap-3">
                                <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}"></i>
                                <span>{{ __('Back') }}</span>
                            </button>
                            <button type="button" id="proceedToPaymentBtn"
                                class="flex-1 py-4 rounded-xl font-bold text-lg transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-0.5 flex items-center justify-center gap-3 group force-button">
                                <i class="fas fa-lock group-hover:scale-110 transition-transform"></i>
                                <span>{{ __('Proceed to Payment') }}</span>
                                <i
                                    class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} group-hover:translate-x-{{ app()->getLocale() === 'ar' ? '1' : '-1' }} transition-transform"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Payment Method - Hidden -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6 hidden">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ __('Payment Method') }}</h2>
                        <div class="space-y-4">
                            <label
                                class="flex items-center p-4 border-2 border-orange-500 rounded-xl cursor-pointer bg-orange-50">
                                <input type="radio" name="payment" value="card" checked
                                    class="w-5 h-5 text-orange-600">
                                <div class="{{ app()->getLocale() === 'ar' ? 'mr-4' : 'ml-4' }} flex-1">
                                    <div class="font-semibold text-gray-900">{{ __('Credit Card / Mada') }}</div>
                                    <div class="text-sm text-gray-600">{{ __('Secure payment through payment gateway') }}
                                    </div>
                                </div>
                                <i class="fas fa-credit-card text-orange-600 text-2xl"></i>
                            </label>
                            <label
                                class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                                <input type="radio" name="payment" value="mada" class="w-5 h-5 text-orange-600">
                                <div class="{{ app()->getLocale() === 'ar' ? 'mr-4' : 'ml-4' }} flex-1">
                                    <div class="font-semibold text-gray-900">{{ __('Mada') }}</div>
                                    <div class="text-sm text-gray-600">{{ __('Direct payment via Mada') }}</div>
                                </div>
                                <i class="fas fa-mobile-alt text-gray-600 text-2xl"></i>
                            </label>
                            <label
                                class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                                <input type="radio" name="payment" value="apple" class="w-5 h-5 text-orange-600">
                                <div class="{{ app()->getLocale() === 'ar' ? 'mr-4' : 'ml-4' }} flex-1">
                                    <div class="font-semibold text-gray-900">Apple Pay</div>
                                    <div class="text-sm text-gray-600">{{ __('Fast and secure payment') }}</div>
                                </div>
                                <i class="fab fa-apple text-gray-600 text-2xl"></i>
                            </label>
                        </div>

                        <!-- Card Details (shown when card is selected) -->
                        <div id="cardDetails" class="mt-6 pt-6 border-t border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label class="block text-gray-700 font-semibold mb-2">{{ __('Card Number') }}</label>
                                    <input type="text" placeholder="1234 5678 9012 3456" maxlength="19"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">{{ __('Expiry Date') }}</label>
                                    <input type="text" placeholder="MM/YY" maxlength="5"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">CVV</label>
                                    <input type="text" placeholder="123" maxlength="3"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900">
                                </div>
                                <div class="md:col-span-2">
                                    <label
                                        class="block text-gray-700 font-semibold mb-2">{{ __('Cardholder Name') }}</label>
                                    <input type="text" placeholder="{{ __('As written on the card') }}"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Terms & Conditions -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border-2 border-gray-100 dark:border-gray-700">
                        <label class="flex items-start cursor-pointer group">
                            <input type="checkbox" id="termsCheckbox" required
                                class="w-6 h-6 text-orange-600 mt-0.5 rounded cursor-pointer">
                            <div
                                class="{{ app()->getLocale() === 'ar' ? 'mr-4' : 'ml-4' }} text-sm text-gray-700 dark:text-gray-300">
                                {{ __('I agree to') }}
                                <a href="#"
                                    class="text-orange-600 hover:text-orange-700 underline font-semibold transition-colors">{{ __('Terms and Conditions') }}</a>
                                {{ __('and') }}
                                <a href="#"
                                    class="text-orange-600 hover:text-orange-700 underline font-semibold transition-colors">{{ __('Privacy Policy') }}</a>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Booking Summary Sidebar -->
                <div class="lg:col-span-1">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 sticky top-24 border-t-4 border-orange-600">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-receipt text-orange-600"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Booking Summary') }}</h3>
                        </div>

                        <!-- Room Details -->
                        <div class="mb-6 pb-6 border-b-2 border-gray-100">
                            @php
                                $hotel = $hotelDetails['HotelDetails'][0] ?? null;
                                $roomImage =
                                    'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80';
                                if ($hotel && isset($hotel['Image']) && !empty($hotel['Image'])) {
                                    $roomImage = $hotel['Image'];
                                } elseif (
                                    $hotel &&
                                    isset($hotel['Images']) &&
                                    is_array($hotel['Images']) &&
                                    count($hotel['Images']) > 0
                                ) {
                                    $roomImage = $hotel['Images'][0];
                                }

                                $roomName = 'Room';
                                if ($roomData && isset($roomData['Name'])) {
                                    if (is_array($roomData['Name']) && count($roomData['Name']) > 0) {
                                        $roomName = $roomData['Name'][0];
                                    } elseif (is_string($roomData['Name'])) {
                                        $roomName = $roomData['Name'];
                                    }
                                }
                            @endphp
                            <div
                                class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-900 rounded-xl p-4">
                                <div class="flex gap-4">
                                    <img src="{{ $roomImage }}" alt="{{ $roomName }}"
                                        class="w-24 h-24 object-cover rounded-xl shadow-md">
                                    <div class="flex-1">
                                        <div class="font-bold text-gray-900 dark:text-white mb-2 text-lg">
                                            {{ $roomName }}</div>
                                        <div class="flex flex-wrap gap-2 mb-2">
                                            <span
                                                class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-lg font-semibold">
                                                <i
                                                    class="fas fa-users {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                                {{ $guests ?? request('guests', '2') }} {{ __('Guests') }}
                                            </span>
                                            <span
                                                class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-lg font-semibold">
                                                <i
                                                    class="fas fa-moon {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                                {{ $nights ?? 2 }} {{ __('nights') }}
                                            </span>
                                        </div>
                                        @if (($roomData && isset($roomData['Inclusion'])) || request('inclusion'))
                                            <div class="text-xs text-gray-600 mt-2 flex items-center">
                                                <i
                                                    class="fas fa-utensils {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }} text-orange-600"></i>
                                                <span>{{ $roomData['Inclusion'] ?? request('inclusion') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Price Breakdown -->
                        @php
                            $totalFare = 0;
                            $currency = 'USD';
                            $pricePerNight = 0;

                            // Get price from roomData or URL parameters
                            if ($roomData && isset($roomData['TotalFare']) && $roomData['TotalFare'] > 0) {
                                $totalFare = (float) $roomData['TotalFare'];
                                $currency = $roomData['Currency'] ?? 'USD';
                            } elseif (request('total_fare')) {
                                $totalFare = (float) request('total_fare');
                                $currency = request('currency', 'USD');
                            }

                            // If still no price, try to get from roomData other fields
                            if ($totalFare == 0 && $roomData) {
                                if (isset($roomData['Rate']) && is_numeric($roomData['Rate'])) {
                                    $totalFare = (float) $roomData['Rate'];
                                } elseif (isset($roomData['Price']) && is_numeric($roomData['Price'])) {
                                    $totalFare = (float) $roomData['Price'];
                                } elseif (isset($roomData['Amount']) && is_numeric($roomData['Amount'])) {
                                    $totalFare = (float) $roomData['Amount'];
                                }
                            }

                            $nightsCount = $nights ?? 2;
                            if ($totalFare > 0 && $nightsCount > 0) {
                                $pricePerNight = $totalFare / $nightsCount;
                            }
                        @endphp
                        <div class="space-y-3 mb-6">
                            @if ($totalFare > 0)
                                @if ($nightsCount > 1 && $pricePerNight > 0)
                                    <div
                                        class="flex justify-between items-center py-2 px-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <span class="text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                            <i class="fas fa-calendar-day text-orange-600"></i>
                                            {{ __('Night Price') }}
                                        </span>
                                        <span
                                            class="font-semibold force-input-text">{{ number_format($pricePerNight, 2) }}
                                            {{ $currency }}</span>
                                    </div>
                                    <div
                                        class="flex justify-between items-center py-2 px-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <span class="text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                            <i class="fas fa-moon text-orange-600"></i>
                                            {{ __('Number of Nights') }}
                                        </span>
                                        <span class="font-semibold force-input-text">× {{ $nightsCount }}</span>
                                    </div>
                                @endif
                                @if ($roomData && isset($roomData['TotalTax']) && $roomData['TotalTax'] > 0)
                                    <div
                                        class="flex justify-between items-center py-2 px-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <span class="text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                            <i class="fas fa-receipt text-orange-600"></i>
                                            {{ __('Tax') }}
                                        </span>
                                        <span
                                            class="font-semibold force-input-text">{{ number_format($roomData['TotalTax'], 2) }}
                                            {{ $currency }}</span>
                                    </div>
                                @endif
                            @else
                                <div class="text-sm text-gray-500 text-center py-4 bg-gray-50 rounded-lg">
                                    <i class="fas fa-info-circle mb-2"></i>
                                    <div>{{ __('Price information not available') }}</div>
                                </div>
                            @endif
                        </div>

                        <div
                            class="bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/40 dark:to-orange-800/40 border-2 border-orange-200 dark:border-orange-800 rounded-xl p-4 mb-6">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Total') }}</span>
                                <span class="text-2xl font-extrabold text-orange-600">
                                    @if ($totalFare > 0)
                                        {{ number_format($totalFare, 2) }} {{ $currency }}
                                    @else
                                        {{ __('N/A') }}
                                    @endif
                                </span>
                            </div>
                            @if ($totalFare > 0)
                                <div class="text-xs text-gray-600 dark:text-gray-400 flex items-center gap-1">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    {{ __('including all taxes and fees') }}
                                </div>
                            @endif
                        </div>

                        <!-- Security Badge -->
                        <div
                            class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-2 border-green-200 dark:border-green-800 rounded-xl p-4 mb-4">
                            <div class="flex items-center mb-2">
                                <div
                                    class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}">
                                    <i class="fas fa-shield-check text-white text-lg"></i>
                                </div>
                                <span
                                    class="font-bold text-green-900 dark:text-green-400">{{ __('Secure and guaranteed booking') }}</span>
                            </div>
                            <div
                                class="text-xs text-green-700 dark:text-green-500 {{ app()->getLocale() === 'ar' ? 'mr-12' : 'ml-12' }}">
                                <i class="fas fa-lock {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                {{ __('All your data is protected with SSL encryption') }}
                            </div>
                        </div>

                        <!-- Cancel Policy -->
                        <div
                            class="bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 border-2 border-blue-200 dark:border-blue-800 rounded-xl p-4 mb-6">
                            <div class="flex items-start">
                                <div
                                    class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }} flex-shrink-0">
                                    <i class="fas fa-info-circle text-white"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-blue-900 dark:text-blue-400 mb-1">
                                        {{ __('Cancellation Policy') }}</div>
                                    <div class="text-xs text-blue-700 dark:text-blue-500">
                                        {{ __('You can cancel for free up to 24 hours before check-in date') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        @if (isset($paymentData))
                            <form id="paymentForm" method="POST" action="{{ config('services.aps.payment_url') }}">
                                @foreach ($paymentData as $k => $v)
                                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                @endforeach
                                <button type="submit" form="paymentForm"
                                    class="w-full py-5 rounded-xl font-bold text-lg transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-0.5 flex items-center justify-center gap-3 group force-button">
                                    <i class="fas fa-lock group-hover:scale-110 transition-transform"></i>
                                    <span>{{ __('Confirm Booking and Payment') }}</span>
                                    <i
                                        class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} group-hover:translate-x-{{ app()->getLocale() === 'ar' ? '1' : '-1' }} transition-transform"></i>
                                </button>
                            </form>
                        @else
                            <button type="button" id="confirmBookingBtn"
                                class="w-full py-5 rounded-xl font-bold text-lg transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-0.5 flex items-center justify-center gap-3 group force-button">
                                <i class="fas fa-lock group-hover:scale-110 transition-transform"></i>
                                <span>{{ __('Confirm Booking and Payment') }}</span>
                                <i
                                    class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} group-hover:translate-x-{{ app()->getLocale() === 'ar' ? '1' : '-1' }} transition-transform"></i>
                            </button>
                        @endif

                        <div class="text-center mt-6 pt-6 border-t border-gray-200">
                            <div class="flex items-center justify-center gap-2 text-sm text-gray-600 mb-3">
                                <i class="fas fa-shield-alt text-green-600 text-lg"></i>
                                <span class="font-semibold">{{ __('100% Secure Payment') }}</span>
                            </div>
                            <div class="flex items-center justify-center gap-4 mt-3 flex-wrap">
                                <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-lg shadow-sm">
                                    <i class="fab fa-cc-visa text-blue-600 text-2xl"></i>
                                    <span class="text-xs text-gray-600 font-semibold">Visa</span>
                                </div>
                                <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-lg shadow-sm">
                                    <i class="fab fa-cc-mastercard text-red-600 text-2xl"></i>
                                    <span class="text-xs text-gray-600 font-semibold">Mastercard</span>
                                </div>
                                <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-lg shadow-sm">
                                    <i class="fas fa-credit-card text-green-600 text-2xl"></i>
                                    <span class="text-xs text-gray-600 font-semibold">Mada</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        let guestIti;
        document.addEventListener('DOMContentLoaded', function() {
            const guestPhoneInput = document.querySelector("#guestPhone");
            if (guestPhoneInput) {
                guestIti = window.intlTelInput(guestPhoneInput, {
                    initialCountry: "{{ auth()->check() && auth()->user()->phone_country_code ? strtolower(auth()->user()->phone_country_code) : 'sa' }}",
                    separateDialCode: true,
                    countrySearch: false,
                    geoIpLookup: function(callback) {
                        fetch("https://ipapi.co/json")
                            .then(res => res.json())
                            .then(data => callback(data.country_code))
                            .catch(() => callback("sa"));
                    },
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                });

                guestPhoneInput.addEventListener("countrychange", function() {
                    const countryData = guestIti.getSelectedCountryData();
                    document.querySelector("#guestPhoneCountryCode").value = countryData.iso2.toUpperCase();
                });

                // Set initial value
                const countryData = guestIti.getSelectedCountryData();
                document.querySelector("#guestPhoneCountryCode").value = countryData.iso2.toUpperCase();
            }
        });

        @auth
        // Use account information checkbox
        const useAccountInfo = document.getElementById('useAccountInfo');
        const guestInfoSection = document.getElementById('guestInfoSection');
        const guestName = document.getElementById('guestName');
        const guestEmail = document.getElementById('guestEmail');
        const guestPhone = document.getElementById('guestPhone');

        if (useAccountInfo) {
            useAccountInfo.addEventListener('change', function() {
                if (this.checked) {
                    // Fill form with user data
                    guestName.value = '{{ auth()->user()->name }}';
                    guestEmail.value = '{{ auth()->user()->email }}';
                    guestPhone.value = '{{ auth()->user()->phone ?? '' }}';

                    // Hide guest information section
                    guestInfoSection.classList.add('hidden');
                } else {
                    // Show guest information section
                    guestInfoSection.classList.remove('hidden');
                }
            });
        }
        @endauth

        // Payment method toggle
        document.querySelectorAll('input[name="payment"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const cardDetails = document.getElementById('cardDetails');
                if (this.value === 'card') {
                    cardDetails.style.display = 'block';
                } else {
                    cardDetails.style.display = 'none';
                }
            });
        });

        // Card number formatting
        document.querySelector('input[placeholder="1234 5678 9012 3456"]')?.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });

        // Expiry date formatting
        document.querySelector('input[placeholder="MM/YY"]')?.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });

        // Function to update review section with guest info
        function updateReviewSection() {
            const name = document.getElementById('guestName')?.value ||
                '{{ auth()->check() ? auth()->user()->name : '' }}';
            const email = document.getElementById('guestEmail')?.value ||
                '{{ auth()->check() ? auth()->user()->email : '' }}';
            const phone = document.getElementById('guestPhone')?.value || '{{ auth()->user()->phone ?? '' }}';
            const countryCode = document.getElementById('guestPhoneCountryCode')?.value ||
                '{{ auth()->user()->phone_country_code ?? '966' }}';
            const notes = document.getElementById('guestNotes')?.value || '';

            const reviewName = document.getElementById('reviewName');
            const reviewEmail = document.getElementById('reviewEmail');
            const reviewPhone = document.getElementById('reviewPhone');
            const reviewPhoneCountryCode = document.getElementById('reviewPhoneCountryCode');
            const reviewNotes = document.getElementById('reviewNotes');
            const reviewNotesContainer = document.getElementById('reviewNotesContainer');

            if (reviewName) reviewName.textContent = name;
            if (reviewEmail) reviewEmail.textContent = email;
            if (reviewPhone) reviewPhone.textContent = (countryCode ? '+' + countryCode + ' ' : '') + phone;

            if (notes && reviewNotes && reviewNotesContainer) {
                reviewNotes.textContent = notes;
                reviewNotesContainer.classList.remove('hidden');
            } else if (reviewNotesContainer) {
                reviewNotesContainer.classList.add('hidden');
            }
        }

        // Back to Guest Info button
        const backToGuestInfoBtn = document.getElementById('backToGuestInfoBtn');
        if (backToGuestInfoBtn) {
            backToGuestInfoBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const guestInfoStep = document.getElementById('guestInfoStep');
                const reviewStep = document.getElementById('reviewStep');
                if (guestInfoStep) guestInfoStep.classList.remove('hidden');
                if (reviewStep) reviewStep.classList.add('hidden');
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        // Proceed to Review button
        const proceedToPaymentBtn = document.getElementById('proceedToPaymentBtn');
        if (proceedToPaymentBtn) {
            proceedToPaymentBtn.addEventListener('click', function(e) {
                e.preventDefault();

                // First validate the guest info
                if (!validateForm()) {
                    return false;
                }

                // Show loading state
                this.disabled = true;
                this.innerHTML =
                    '<i class="fas fa-spinner fa-spin {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('Processing...') }}';

                // Fill hidden form and submit
                const name = document.getElementById('guestName')?.value ||
                    '{{ auth()->check() ? auth()->user()->name : '' }}';
                const email = document.getElementById('guestEmail')?.value ||
                    '{{ auth()->check() ? auth()->user()->email : '' }}';
                const phone = document.getElementById('guestPhone')?.value || '{{ auth()->user()->phone ?? '' }}';
                const countryCode = document.getElementById('guestPhoneCountryCode')?.value ||
                    '{{ auth()->user()->phone_country_code ?? '966' }}';
                const notes = document.getElementById('guestNotes')?.value || '';

                const reviewForm = document.getElementById('reviewForm');
                if (reviewForm) {
                    document.getElementById('reviewFormName').value = name;
                    document.getElementById('reviewFormEmail').value = email;
                    document.getElementById('reviewFormPhone').value = phone;
                    document.getElementById('reviewFormPhoneCountryCode').value = countryCode;
                    document.getElementById('reviewFormNotes').value = notes;
                    document.getElementById('reviewFormTerms').value = '1';
                    reviewForm.submit();
                }
            });
        }

        // Form validation and submission
        const paymentForm = document.getElementById('paymentForm');
        const reservationForm = document.getElementById('reservationForm');
        const termsCheckbox = document.getElementById('termsCheckbox');
        const submitButton = document.querySelector('button[type="submit"]');

        function validateForm() {
            const name = document.getElementById('guestName')?.value.trim();
            const email = document.getElementById('guestEmail')?.value.trim();
            const phone = document.getElementById('guestPhone')?.value.trim();
            const termsAccepted = termsCheckbox?.checked;

            // Check if guest info section is hidden (using account info)
            const guestInfoSection = document.getElementById('guestInfoSection');
            const isUsingAccountInfo = guestInfoSection?.classList.contains('hidden');

            if (isUsingAccountInfo) {
                // If using account info, we still need to check terms
                if (!termsAccepted) {
                    alert('{{ __('Please accept the Terms and Conditions to continue') }}');
                    return false;
                }
                return true;
            }

            // Validate required fields
            if (!name) {
                alert('{{ __('Please enter your full name') }}');
                document.getElementById('guestName')?.focus();
                return false;
            }

            if (!email) {
                alert('{{ __('Please enter your email address') }}');
                document.getElementById('guestEmail')?.focus();
                return false;
            }

            // Basic email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('{{ __('Please enter a valid email address') }}');
                document.getElementById('guestEmail')?.focus();
                return false;
            }

            if (!phone) {
                alert('{{ __('Please enter your mobile number') }}');
                document.getElementById('guestPhone')?.focus();
                return false;
            }

            if (!termsAccepted) {
                alert('{{ __('Please accept the Terms and Conditions to continue') }}');
                termsCheckbox?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                return false;
            }

            return true;
        }

        // Handle payment form if it exists (when paymentData is already generated)
        if (paymentForm) {
            paymentForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Validate form before submission
                if (!validateForm()) {
                    return false;
                }

                // Get email from form or use authenticated user email
                const emailInput = document.getElementById('guestEmail');
                const email = emailInput ? emailInput.value :
                    '{{ auth()->check() ? auth()->user()->email : '' }}';

                // Update customer_email in payment form
                const emailField = paymentForm.querySelector('input[name="customer_email"]');
                if (emailField && email) {
                    emailField.value = email;
                }

                // Disable button to prevent double submission
                const btn = paymentForm.querySelector('button[type="submit"]');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML =
                        '<i class="fas fa-spinner fa-spin {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('Processing...') }}';
                }

                // Submit form to PayFort
                this.submit();
            });
        }

        // Handle confirm booking button (when no payment form exists) - goes to Review first
        const confirmBookingBtn = document.getElementById('confirmBookingBtn');
        if (confirmBookingBtn) {
            confirmBookingBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Validate form before proceeding - check all required fields
                let name = document.getElementById('guestName')?.value.trim() || '';
                let email = document.getElementById('guestEmail')?.value.trim() || '';
                let phone = document.getElementById('guestPhone')?.value.trim() || '';
                const termsCheckbox = document.getElementById('termsCheckbox');
                const termsAccepted = termsCheckbox?.checked;

                // Check if guest info section is hidden (using account info)
                const guestInfoSection = document.getElementById('guestInfoSection');
                const isUsingAccountInfo = guestInfoSection?.classList.contains('hidden');

                // If using account info, get user data
                if (isUsingAccountInfo) {
                    name = '{{ auth()->check() ? auth()->user()->name : '' }}';
                    email = '{{ auth()->check() ? auth()->user()->email : '' }}';
                    phone = '{{ auth()->check() ? auth()->user()->phone ?? '' : '' }}';
                    phoneCountryCode =
                        '{{ auth()->check() ? auth()->user()->phone_country_code ?? '966' : '966' }}';
                } else {
                    phoneCountryCode = document.getElementById('guestPhoneCountryCode')?.value || '966';
                }

                // Validate required fields
                if (!isUsingAccountInfo) {
                    if (!name) {
                        alert('{{ __('Please enter your full name') }}');
                        document.getElementById('guestName')?.focus();
                        return false;
                    }

                    if (!email) {
                        alert('{{ __('Please enter your email address') }}');
                        document.getElementById('guestEmail')?.focus();
                        return false;
                    }

                    // Basic email validation
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(email)) {
                        alert('{{ __('Please enter a valid email address') }}');
                        document.getElementById('guestEmail')?.focus();
                        return false;
                    }

                    if (!phone) {
                        alert('{{ __('Please enter your mobile number') }}');
                        document.getElementById('guestPhone')?.focus();
                        return false;
                    }
                }

                // Check terms and conditions
                if (!termsAccepted) {
                    alert('{{ __('Please accept the Terms and Conditions to continue') }}');
                    termsCheckbox?.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    return false;
                }

                // Get final form values (use trimmed values or fallback to user data)
                const finalName = name || '{{ auth()->check() ? auth()->user()->name : '' }}';
                const finalEmail = email || '{{ auth()->check() ? auth()->user()->email : '' }}';
                const finalPhone = phone || '{{ auth()->check() ? auth()->user()->phone ?? '' : '' }}';
                const finalPhoneCountryCode = phoneCountryCode || '966';
                const notes = document.getElementById('guestNotes')?.value || '';

                // Fill hidden form
                const reviewForm = document.getElementById('reviewForm');
                if (reviewForm) {
                    document.getElementById('reviewFormName').value = finalName;
                    document.getElementById('reviewFormEmail').value = finalEmail;
                    document.getElementById('reviewFormPhone').value = finalPhone;
                    document.getElementById('reviewFormPhoneCountryCode').value = finalPhoneCountryCode;
                    document.getElementById('reviewFormNotes').value = notes;
                    document.getElementById('reviewFormTerms').value = '1';

                    // Submit form to review page
                    reviewForm.submit();
                }
            });
        }

        // Also validate on button click (for payment form button)
        document.querySelectorAll('button[form="paymentForm"]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                // Always prevent default form submission
                e.preventDefault();
                e.stopPropagation();

                // Validate form
                if (!validateForm()) {
                    return false;
                }

                // Trigger payment form submission
                if (paymentForm) {
                    paymentForm.dispatchEvent(new Event('submit'));
                }

                return false;
            });
        });
    </script>
@endpush
