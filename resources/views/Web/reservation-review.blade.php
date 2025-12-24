@extends('Web.layouts.app')

@section('title', __('Confirm Reservation') . ' - ' . __('Book Hotels - Best Offers and Services'))

@section('content')
    <style>
        /* Forced colors to fix production build issues */
        .force-input-text {
            color: #111827 !important;
            /* text-gray-900 */
        }

        .dark .force-input-text {
            color: #ffffff !important;
        }

        .force-button-text {
            color: #ffffff !important;
        }
    </style>

    <!-- Review Header -->
    <section class="bg-gradient-to-br from-orange-500 via-orange-600 to-blue-900 text-white py-16 relative overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex items-center mb-6">
                <a href="{{ url()->previous() }}"
                    class="flex items-center text-white/90 hover:text-white transition-all duration-300 group">
                    <i
                        class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }} group-hover:translate-x-{{ app()->getLocale() === 'ar' ? '-1' : '1' }} transition-transform"></i>
                    <span class="font-semibold">{{ __('Back') }}</span>
                </a>
            </div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold">{{ __('Confirm Your Reservation') }}</h1>
            </div>
            <p class="text-orange-100 text-lg max-w-2xl">
                {{ __('Please review all booking details carefully before proceeding to payment') }}</p>
        </div>
    </section>

    <!-- Review Content -->
    <section class="py-12 bg-gradient-to-b from-gray-50 to-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Review Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Guest Information -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 border-t-4 border-orange-600">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-circle text-orange-600 text-2xl"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ __('Guest Information') }}</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 rounded-xl p-4">
                                <div class="text-xs text-gray-500 uppercase tracking-wide mb-2">{{ __('Full Name') }}</div>
                                <div class="text-lg font-bold text-gray-900">{{ $guestName }}</div>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4">
                                <div class="text-xs text-gray-500 uppercase tracking-wide mb-2">{{ __('Email Address') }}
                                </div>
                                <div class="text-lg font-bold text-gray-900">{{ $guestEmail }}</div>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4">
                                <div class="text-xs text-gray-500 uppercase tracking-wide mb-2">{{ __('Mobile Number') }}
                                </div>
                                <div class="text-lg font-bold text-gray-900">{{ $guestPhone }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Hotel & Room Information -->
                    @php
                        $hotel = $hotelDetails['HotelDetails'][0] ?? null;
                        $displayRoomName = 'Room';
                        if ($roomData && isset($roomData['Name'])) {
                            if (is_array($roomData['Name']) && count($roomData['Name']) > 0) {
                                $displayRoomName = $roomData['Name'][0];
                            } elseif (is_string($roomData['Name'])) {
                                $displayRoomName = $roomData['Name'];
                            }
                        }
                    @endphp
                    <div class="bg-white rounded-2xl shadow-lg p-8 border-t-4 border-blue-600">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-hotel text-blue-600 text-2xl"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ __('Hotel & Room Details') }}</h2>
                        </div>
                        <div class="space-y-6">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border-2 border-blue-200">
                                <div class="text-xs text-blue-600 uppercase tracking-wide mb-2 font-semibold">
                                    {{ __('Hotel Name') }}</div>
                                <div class="text-2xl font-bold text-gray-900 mb-3">
                                    {{ $hotel['HotelName'] ?? __('International Luxury Hotel') }}
                                </div>
                                @if (isset($hotel['Address']))
                                    <div class="text-sm text-gray-600 flex items-center gap-2">
                                        <i class="fas fa-map-marker-alt text-blue-600"></i>
                                        <span>{{ $hotel['Address'] }}</span>
                                    </div>
                                @endif
                            </div>
                            <div
                                class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border-2 border-purple-200">
                                <div class="text-xs text-purple-600 uppercase tracking-wide mb-2 font-semibold">
                                    {{ __('Room Type') }}</div>
                                <div class="text-2xl font-bold text-gray-900 mb-3">{{ $displayRoomName }}</div>
                                @if (isset($roomData['Inclusion']))
                                    <div class="text-sm text-gray-600 flex items-center gap-2">
                                        <i class="fas fa-utensils text-purple-600"></i>
                                        <span>{{ $roomData['Inclusion'] }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Booking Dates -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 border-t-4 border-green-600">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-green-600 text-2xl"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ __('Booking Dates') }}</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div
                                class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-5 border-2 border-green-200 text-center">
                                <div class="text-xs text-green-600 uppercase tracking-wide mb-2 font-semibold">
                                    {{ __('Check In') }}</div>
                                <div class="text-2xl font-bold text-gray-900">{{ $checkIn }}</div>
                            </div>
                            <div
                                class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-5 border-2 border-yellow-200 text-center">
                                <div class="text-xs text-yellow-600 uppercase tracking-wide mb-2 font-semibold">
                                    {{ __('Nights') }}</div>
                                <div class="text-2xl font-bold text-gray-900">{{ $nights }}</div>
                            </div>
                            <div
                                class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-5 border-2 border-red-200 text-center">
                                <div class="text-xs text-red-600 uppercase tracking-wide mb-2 font-semibold">
                                    {{ __('Check Out') }}</div>
                                <div class="text-2xl font-bold text-gray-900">{{ $checkOut }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-xl p-6 sticky top-24 border-t-4 border-orange-600">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-receipt text-orange-600"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">{{ __('Payment Summary') }}</h3>
                        </div>

                        <!-- Price Summary -->
                        <div
                            class="bg-gradient-to-r from-orange-50 via-orange-100 to-orange-50 rounded-2xl p-6 border-4 border-orange-200 mb-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <div class="text-lg font-bold text-gray-900 mb-1">{{ __('Total Amount') }}</div>
                                    <div class="text-xs text-gray-500 flex items-center gap-1">
                                        <i class="fas fa-check-circle text-green-600"></i>
                                        {{ __('including all taxes and fees') }}
                                    </div>
                                </div>
                                <div class="text-right {{ app()->getLocale() === 'ar' ? 'text-left' : '' }}">
                                    <div class="text-4xl font-extrabold text-orange-600">
                                        {{ number_format($totalFare, 2) }} {{ $currency }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Important Notice -->
                        <div class="bg-yellow-50 border-2 border-yellow-200 rounded-xl p-4 mb-6">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-info-circle text-yellow-600 text-xl mt-1"></i>
                                <div class="text-sm text-yellow-800">
                                    <div class="font-semibold mb-1">{{ __('Important') }}</div>
                                    <div>
                                        {{ __('By clicking "Pay Now", you confirm that all information is correct and agree to proceed with the payment.') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pay Now Button -->
                        @if (isset($paymentData))
                            <form id="paymentForm" method="POST" action="{{ config('services.aps.payment_url') }}">
                                @foreach ($paymentData as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                <button type="submit"
                                    class="w-full bg-gradient-to-r from-orange-600 via-orange-600 to-orange-700 py-5 rounded-xl font-bold text-xl hover:from-orange-700 hover:via-orange-700 hover:to-orange-800 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-0.5 flex items-center justify-center gap-3 group force-button-text">
                                    <i class="fas fa-credit-card group-hover:scale-110 transition-transform text-2xl"></i>
                                    <span>{{ __('Pay Now') }}</span>
                                    <i
                                        class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} group-hover:translate-x-{{ app()->getLocale() === 'ar' ? '1' : '-1' }} transition-transform"></i>
                                </button>
                            </form>
                        @endif

                        <div class="text-center mt-6 pt-6 border-t border-gray-200">
                            <div class="flex items-center justify-center gap-2 text-sm text-gray-600 mb-3">
                                <i class="fas fa-shield-alt text-green-600 text-lg"></i>
                                <span class="font-semibold">{{ __('100% Secure Payment') }}</span>
                            </div>
                            <div class="flex items-center justify-center gap-4 mt-3 flex-wrap">
                                <i class="fab fa-cc-visa text-blue-600 text-3xl"></i>
                                <i class="fab fa-cc-mastercard text-red-600 text-3xl"></i>
                                <i class="fas fa-credit-card text-green-600 text-3xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
