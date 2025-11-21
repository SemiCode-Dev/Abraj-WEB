@extends('Web.layouts.app')

@section('title', __('Confirm Reservation') . ' - ' . __('Book Hotels - Best Offers and Services'))

@section('content')
<!-- Reservation Header -->
<section class="bg-gradient-to-r from-orange-500 via-orange-600 to-blue-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('hotel.details', ['locale' => app()->getLocale(), 'id' => request('hotel_id', 1)]) }}?check_in={{ request('check_in') }}&check_out={{ request('check_out') }}&guests={{ request('guests') }}" 
               class="text-white/80 hover:text-white transition">
                <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i> {{ __('Back') }}
            </a>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold mb-2">{{ __('Confirm Reservation') }}</h1>
        <p class="text-orange-100 text-lg">{{ __('Complete your information to complete the reservation process') }}</p>
    </div>
</section>

<!-- Reservation Form -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            <!-- Reservation Form -->
            <div class="lg:col-span-3">
                <!-- Booking Summary Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Booking Summary') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="text-sm text-gray-500 mb-2">{{ __('Hotel') }}</div>
                            <div class="font-bold text-gray-900">{{ __('International Luxury Hotel') }} {{ request('hotel_id', 1) }}</div>
                            <div class="text-sm text-gray-600 mt-1">
                                <i class="fas fa-map-marker-alt {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                {{ __('Riyadh') }}, {{ __('Saudi Arabia') }}
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 mb-2">{{ __('Room Type') }}</div>
                            <div class="font-bold text-gray-900">
                                @if(request('room_type') == 1) {{ __('Deluxe Room') }}
                                @elseif(request('room_type') == 2) {{ __('Superior Room') }}
                                @else {{ __('Luxury Room') }}
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 mb-2">{{ __('Check In Date') }}</div>
                            <div class="font-bold text-gray-900">{{ request('check_in', '--') }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 mb-2">{{ __('Check Out Date') }}</div>
                            <div class="font-bold text-gray-900">{{ request('check_out', '--') }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 mb-2">{{ __('Number of Nights') }}</div>
                            <div class="font-bold text-gray-900">2 {{ __('nights') }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 mb-2">{{ __('Number of Guests') }}</div>
                            <div class="font-bold text-gray-900">{{ request('guests', '2') }} {{ __('Guests') }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Guest Information -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Guest Information') }}</h2>
                    <form id="reservationForm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">{{ __('First Name') }} <span class="text-red-500">*</span></label>
                                <input type="text" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">{{ __('Last Name') }} <span class="text-red-500">*</span></label>
                                <input type="text" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">{{ __('Email') }} <span class="text-red-500">*</span></label>
                                <input type="email" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">{{ __('Mobile Number') }} <span class="text-red-500">*</span></label>
                                <input type="tel" required placeholder="{{ app()->getLocale() === 'ar' ? '05xxxxxxxx' : '+966 5x xxx xxxx' }}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-gray-700 font-semibold mb-2">{{ __('Special Notes (Optional)') }}</label>
                                <textarea rows="3" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900" 
                                          placeholder="{{ __('Any special requests or notes...') }}"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Payment Method -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Payment Method') }}</h2>
                    <div class="space-y-4">
                        <label class="flex items-center p-4 border-2 border-orange-500 rounded-xl cursor-pointer bg-orange-50">
                            <input type="radio" name="payment" value="card" checked class="w-5 h-5 text-orange-600">
                            <div class="{{ app()->getLocale() === 'ar' ? 'mr-4' : 'ml-4' }} flex-1">
                                <div class="font-semibold text-gray-900">{{ __('Credit Card / Mada') }}</div>
                                <div class="text-sm text-gray-600">{{ __('Secure payment through payment gateway') }}</div>
                            </div>
                            <i class="fas fa-credit-card text-orange-600 text-2xl"></i>
                        </label>
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                            <input type="radio" name="payment" value="mada" class="w-5 h-5 text-orange-600">
                            <div class="{{ app()->getLocale() === 'ar' ? 'mr-4' : 'ml-4' }} flex-1">
                                <div class="font-semibold text-gray-900">{{ __('Mada') }}</div>
                                <div class="text-sm text-gray-600">{{ __('Direct payment via Mada') }}</div>
                            </div>
                            <i class="fas fa-mobile-alt text-gray-600 text-2xl"></i>
                        </label>
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
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
                                <label class="block text-gray-700 font-semibold mb-2">{{ __('Cardholder Name') }}</label>
                                <input type="text" placeholder="{{ __('As written on the card') }}" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Terms & Conditions -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <label class="flex items-start cursor-pointer">
                        <input type="checkbox" required class="w-5 h-5 text-orange-600 mt-1 rounded">
                        <div class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} text-sm text-gray-700">
                            {{ __('I agree to') }} <a href="#" class="text-orange-600 hover:underline font-semibold">{{ __('Terms and Conditions') }}</a> 
                            {{ __('and') }} <a href="#" class="text-orange-600 hover:underline font-semibold">{{ __('Privacy Policy') }}</a>
                        </div>
                    </label>
                </div>
            </div>
            
            <!-- Booking Summary Sidebar -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">{{ __('Booking Summary') }}</h3>
                    
                    <!-- Room Details -->
                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <div class="flex gap-4 mb-4">
                            <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" 
                                 alt="غرفة" class="w-20 h-20 object-cover rounded-xl">
                            <div class="flex-1">
                                <div class="font-bold text-gray-900 mb-1">
                                    @if(request('room_type') == 1) {{ __('Deluxe Room') }}
                                    @elseif(request('room_type') == 2) {{ __('Superior Room') }}
                                    @else {{ __('Luxury Room') }}
                                    @endif
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ request('guests', '2') }} {{ __('Guests') }} • 2 {{ __('nights') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Price Breakdown -->
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-gray-700">
                            <span>{{ __('Night Price') }}</span>
                            <span>{{ request('room_type') == 1 ? '300' : (request('room_type') == 2 ? '400' : '500') }} {{ __('SAR') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>{{ __('Number of Nights') }}</span>
                            <span>× 2</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>{{ __('VAT') }}</span>
                            <span>60 {{ __('SAR') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>{{ __('Service Fee') }}</span>
                            <span>40 {{ __('SAR') }}</span>
                        </div>
                        @if(request('room_type') == 1)
                        <div class="flex justify-between text-orange-600">
                            <span><i class="fas fa-gift {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('Free Breakfast') }}</span>
                            <span>- 100 {{ __('SAR') }}</span>
                        </div>
                        @endif
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4 mb-6">
                        <div class="flex justify-between text-xl font-bold text-gray-900 mb-2">
                            <span>{{ __('Total') }}</span>
                            <span class="text-orange-600">
                                @if(request('room_type') == 1) 600
                                @elseif(request('room_type') == 2) 900
                                @else 1100
                                @endif {{ __('SAR') }}
                            </span>
                        </div>
                        <div class="text-xs text-gray-500">{{ __('including all taxes and fees') }}</div>
                    </div>
                    
                    <!-- Security Badge -->
                    <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 mb-6">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-shield-check text-orange-600 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                            <span class="font-semibold text-orange-900">{{ __('Secure and guaranteed booking') }}</span>
                        </div>
                        <div class="text-xs text-orange-700">{{ __('All your data is protected with SSL encryption') }}</div>
                    </div>
                    
                    <!-- Cancel Policy -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }} mt-1"></i>
                            <div>
                                <div class="font-semibold text-blue-900 mb-1">{{ __('Cancellation Policy') }}</div>
                                <div class="text-xs text-blue-700">{{ __('You can cancel for free up to 24 hours before check-in date') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" form="reservationForm" 
                            class="w-full bg-gradient-to-r from-orange-600 to-orange-600 text-white py-4 rounded-xl font-bold text-lg hover:from-orange-700 hover:to-orange-700 transition shadow-lg flex items-center justify-center">
                        <i class="fas fa-lock {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                        {{ __('Confirm Booking and Payment') }}
                    </button>
                    
                    <div class="text-center mt-4">
                        <div class="flex items-center justify-center gap-2 text-sm text-gray-600">
                            <i class="fas fa-lock"></i>
                            <span>{{ __('100% Secure Payment') }}</span>
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
    
    // Form submission
    document.getElementById('reservationForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        alert('{{ __('Reservation request sent successfully! We will contact you soon to confirm the reservation.') }}');
    });
</script>
@endpush

