@extends('Web.layouts.app')

@section('title', __('Hotel Details') . ' - ' . __('Book Hotels - Best Offers and Services'))

@section('content')
<!-- Hotel Header -->
<section class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('hotels.search') }}?destination={{ request('destination') }}&check_in={{ request('check_in') }}&check_out={{ request('check_out') }}&guests={{ request('guests') }}" 
               class="text-white/80 hover:text-white transition">
                <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i> {{ __('Back to List') }}
            </a>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ __('International Luxury Hotel') }} {{ $hotelId }}</h1>
        <div class="flex items-center gap-4 flex-wrap">
            <div class="flex items-center">
                <i class="fas fa-map-marker-alt {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                <span>{{ request('destination', __('Riyadh')) }}, {{ __('Saudi Arabia') }}</span>
            </div>
            <div class="flex items-center">
                <div class="flex text-yellow-500 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}">
                    @for($i = 0; $i < 5; $i++)
                    <i class="fas fa-star"></i>
                    @endfor
                </div>
                <span class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">4.8</span>
                <span class="text-white/70">(245 {{ __('reviews') }})</span>
            </div>
        </div>
    </div>
</section>

<!-- Hotel Images Gallery -->
<section class="py-8 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4" id="hotelGallery">
            <div class="md:col-span-2 md:row-span-2 cursor-pointer" onclick="openImageModal(0)">
                <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" 
                     alt="فندق" class="w-full h-full object-cover rounded-2xl hover:opacity-90 transition">
            </div>
            <div class="cursor-pointer" onclick="openImageModal(1)">
                <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                     alt="فندق" class="w-full h-48 object-cover rounded-2xl hover:opacity-90 transition">
            </div>
            <div class="cursor-pointer" onclick="openImageModal(2)">
                <img src="https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                     alt="فندق" class="w-full h-48 object-cover rounded-2xl hover:opacity-90 transition">
            </div>
            <div class="cursor-pointer" onclick="openImageModal(3)">
                <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                     alt="فندق" class="w-full h-48 object-cover rounded-2xl hover:opacity-90 transition">
            </div>
            <div class="cursor-pointer" onclick="openImageModal(4)">
                <img src="https://images.unsplash.com/photo-1564501049412-61c2a3083791?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                     alt="فندق" class="w-full h-48 object-cover rounded-2xl hover:opacity-90 transition">
            </div>
        </div>
    </div>
</section>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center">
    <div class="relative w-full h-full flex items-center justify-center p-4">
        <!-- Close Button -->
        <button onclick="closeImageModal()" class="absolute top-4 {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} text-white hover:text-orange-500 transition text-3xl z-10">
            <i class="fas fa-times"></i>
        </button>
        
        <!-- Previous Button -->
        <button onclick="changeImage(-1)" class="absolute {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }} top-1/2 transform -translate-y-1/2 text-white hover:text-orange-500 transition text-4xl z-10 bg-black bg-opacity-50 rounded-full w-12 h-12 flex items-center justify-center">
            <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}"></i>
        </button>
        
        <!-- Next Button -->
        <button onclick="changeImage(1)" class="absolute {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} top-1/2 transform -translate-y-1/2 text-white hover:text-orange-500 transition text-4xl z-10 bg-black bg-opacity-50 rounded-full w-12 h-12 flex items-center justify-center">
            <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}"></i>
        </button>
        
        <!-- Image Container -->
        <div class="max-w-7xl w-full h-full flex items-center justify-center">
            <img id="modalImage" src="" alt="فندق" class="max-w-full max-h-full object-contain rounded-lg">
        </div>
        
        <!-- Image Counter -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white bg-black bg-opacity-50 px-4 py-2 rounded-full text-sm">
            <span id="imageCounter">1 / 5</span>
        </div>
    </div>
</div>

<!-- Hotel Info & Rooms -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Hotel Details -->
            <div class="lg:col-span-2">
                <!-- Description -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('About the Hotel') }}</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        {{ __('Hotel Description 1') }}
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        {{ __('Hotel Description 2') }}
                    </p>
                </div>
                
                <!-- Amenities -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Facilities and Services') }}</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="flex items-center">
                            <i class="fas fa-wifi text-orange-600 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                            <span class="text-gray-700">{{ __('Free WiFi') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-swimming-pool text-orange-600 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                            <span class="text-gray-700">{{ __('Pool') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-utensils text-orange-600 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                            <span class="text-gray-700">{{ __('Restaurant') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-spa text-orange-600 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                            <span class="text-gray-700">{{ __('Spa') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-dumbbell text-orange-600 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                            <span class="text-gray-700">{{ __('Gym') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-parking text-orange-600 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                            <span class="text-gray-700">{{ __('Parking') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-concierge-bell text-orange-600 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                            <span class="text-gray-700">{{ __('Room Service') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt text-orange-600 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                            <span class="text-gray-700">{{ __('Safe') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-air-conditioner text-orange-600 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                            <span class="text-gray-700">{{ __('Air Conditioning') }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Date Selection & Availability Check -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Select Dates to View Availability') }}</h2>
                    <form id="availabilityForm" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2">
                                <i class="fas fa-calendar-alt text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                {{ __('Check In') }}
                            </label>
                            <input type="date" id="checkInDate" name="check_in" value="{{ request('check_in') }}" required
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2">
                                <i class="fas fa-calendar-check text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                {{ __('Check Out') }}
                            </label>
                            <input type="date" id="checkOutDate" name="check_out" value="{{ request('check_out') }}" required
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2">
                                <i class="fas fa-users text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                {{ __('Guests') }}
                            </label>
                            <select id="guestsSelect" name="guests" class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                                <option value="1" {{ request('guests') == '1' ? 'selected' : '' }}>1 {{ __('Guest') }}</option>
                                <option value="2" {{ request('guests', '2') == '2' ? 'selected' : '' }}>2 {{ __('Guests') }}</option>
                                <option value="3" {{ request('guests') == '3' ? 'selected' : '' }}>3 {{ __('Guests') }}</option>
                                <option value="4" {{ request('guests') == '4' ? 'selected' : '' }}>4 {{ __('Guests') }}</option>
                                <option value="5" {{ request('guests') == '5' ? 'selected' : '' }}>5+ {{ __('Guests') }}</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="button" id="checkAvailabilityBtn" class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-3 rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 transition shadow-lg">
                                <i class="fas fa-search {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                                {{ __('Check Availability') }}
                            </button>
                        </div>
                    </form>
                    <div id="availabilityMessage" class="hidden text-sm text-gray-600"></div>
                </div>

                <!-- Extra Features Selection -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Extra Features') }}</h2>
                    <p class="text-gray-600 text-sm mb-4">{{ __('Select extra features for your stay') }}</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <label class="flex items-center p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                            <input type="checkbox" name="extra_features[]" value="airport_transfer" class="w-5 h-5 text-orange-600 rounded">
                            <span class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">
                                <i class="fas fa-plane text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                {{ __('Airport Transfer') }}
                            </span>
                        </label>
                        <label class="flex items-center p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                            <input type="checkbox" name="extra_features[]" value="late_checkout" class="w-5 h-5 text-orange-600 rounded">
                            <span class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">
                                <i class="fas fa-clock text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                {{ __('Late Check-out') }}
                            </span>
                        </label>
                        <label class="flex items-center p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                            <input type="checkbox" name="extra_features[]" value="early_checkin" class="w-5 h-5 text-orange-600 rounded">
                            <span class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">
                                <i class="fas fa-early-bird text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                {{ __('Early Check-in') }}
                            </span>
                        </label>
                        <label class="flex items-center p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                            <input type="checkbox" name="extra_features[]" value="extra_bed" class="w-5 h-5 text-orange-600 rounded">
                            <span class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">
                                <i class="fas fa-bed text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                {{ __('Extra Bed') }}
                            </span>
                        </label>
                        <label class="flex items-center p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                            <input type="checkbox" name="extra_features[]" value="baby_cot" class="w-5 h-5 text-orange-600 rounded">
                            <span class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">
                                <i class="fas fa-baby text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                {{ __('Baby Cot') }}
                            </span>
                        </label>
                        <label class="flex items-center p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                            <input type="checkbox" name="extra_features[]" value="room_upgrade" class="w-5 h-5 text-orange-600 rounded">
                            <span class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">
                                <i class="fas fa-arrow-up text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                {{ __('Room Upgrade') }}
                            </span>
                        </label>
                        <label class="flex items-center p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                            <input type="checkbox" name="extra_features[]" value="spa_package" class="w-5 h-5 text-orange-600 rounded">
                            <span class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">
                                <i class="fas fa-spa text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                {{ __('Spa Package') }}
                            </span>
                        </label>
                        <label class="flex items-center p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                            <input type="checkbox" name="extra_features[]" value="dinner_package" class="w-5 h-5 text-orange-600 rounded">
                            <span class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">
                                <i class="fas fa-utensils text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                {{ __('Dinner Package') }}
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Available Rooms -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Available Rooms') }}</h2>
                    
                    <div id="roomsContainer" class="space-y-6">
                        <div id="noRoomsMessage" class="hidden text-center py-8 text-gray-500">
                            <i class="fas fa-calendar-times text-4xl mb-4"></i>
                            <p class="text-lg">{{ __('No rooms available for selected dates') }}</p>
                            <p class="text-sm mt-2">{{ __('Please select check-in and check-out dates') }}</p>
                        </div>
                        
                        <div id="roomsList" class="space-y-6">
                        @for($i = 1; $i <= 3; $i++)
                        <div class="border-2 border-gray-200 rounded-2xl p-6 hover:border-orange-500 transition">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Room Image -->
                                <div class="relative h-48 md:h-full min-h-[200px]">
                                    <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                         alt="غرفة" class="w-full h-full object-cover rounded-xl">
                                    @if($i == 1)
                                    <div class="absolute top-4 {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }} bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold">
                                        {{ __('Most Booked') }}
                                    </div>
                                    @endif
                                </div>
                                
                                <!-- Room Details -->
                                <div class="md:col-span-2">
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900 mb-2">
                                                @if($i == 1) {{ __('Deluxe Room') }} @elseif($i == 2) {{ __('Superior Room') }} @else {{ __('Luxury Room') }} @endif
                                            </h3>
                                            <div class="flex flex-wrap gap-2 mb-3">
                                                <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg">
                                                    <i class="fas fa-bed {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('King Bed') }}
                                                </span>
                                                <span class="px-2 py-1 bg-green-50 text-green-700 text-xs rounded-lg">
                                                    <i class="fas fa-users {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ 2 + $i }} {{ __('people') }}
                                                </span>
                                                <span class="px-2 py-1 bg-purple-50 text-purple-700 text-xs rounded-lg">
                                                    <i class="fas fa-ruler-combined {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ 25 + $i * 5 }} m²
                                                </span>
                                            </div>
                                            <p class="text-gray-600 text-sm mb-4">
                                                @if($i == 1)
                                                {{ __('Room Description 1') }}
                                                @elseif($i == 2)
                                                {{ __('Room Description 2') }}
                                                @else
                                                {{ __('Room Description 3') }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                        <div>
                                            <div class="flex items-baseline">
                                                <span class="text-3xl font-extrabold text-orange-600">{{ 300 + $i * 100 }}</span>
                                                <span class="text-gray-500 text-sm {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">{{ __('SAR') }}</span>
                                            </div>
                                            <div class="text-xs text-gray-400">/ {{ __('per night') }} • {{ __('including taxes') }}</div>
                                            @if($i == 1)
                                            <div class="text-xs text-orange-600 font-semibold mt-1">
                                                <i class="fas fa-gift {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('Free Breakfast') }}
                                            </div>
                                            @endif
                                        </div>
                                        <a href="{{ route('reservation') }}?hotel_id={{ $hotelId }}&room_type={{ $i }}&check_in={{ request('check_in') }}&check_out={{ request('check_out') }}&guests={{ request('guests') }}" 
                                           class="bg-gradient-to-r from-orange-600 to-orange-600 text-white px-8 py-3 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition shadow-lg">
                                            {{ __('Book Now') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endfor
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Hotel Info Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    <!-- Hotel Quick Info -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">{{ __('Hotel Information') }}</h3>
                    
                    <div class="space-y-4">
                        <!-- Location -->
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-orange-600"></i>
                            </div>
                            <div class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }}">
                                <div class="font-semibold text-gray-900 mb-1">{{ __('Location') }}</div>
                                <p class="text-sm text-gray-600">
                                    {{ request('destination', __('Riyadh')) }}, {{ __('Saudi Arabia') }}<br>
                                    King Fahd Road, Building 123
                                </p>
                            </div>
                        </div>

                        <!-- Rating -->
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-star text-orange-600"></i>
                            </div>
                            <div class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }}">
                                <div class="font-semibold text-gray-900 mb-1">{{ __('Rating') }}</div>
                                <div class="flex items-center">
                                    <div class="flex text-yellow-500 text-sm {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}">
                                        @for($i = 0; $i < 5; $i++)
                                        <i class="fas fa-star"></i>
                                        @endfor
                                    </div>
                                    <span class="text-sm text-gray-600">4.8 (245 {{ __('reviews') }})</span>
                                </div>
                            </div>
                        </div>

                        <!-- Check-in/out Times -->
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-clock text-orange-600"></i>
                            </div>
                            <div class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }}">
                                <div class="font-semibold text-gray-900 mb-1">{{ __('Check-in / Check-out') }}</div>
                                <p class="text-sm text-gray-600">
                                    {{ __('Check-in') }}: 2:00 PM<br>
                                    {{ __('Check-out') }}: 12:00 PM
                                </p>
                            </div>
                        </div>
                    </div>
                    </div>

                    <!-- Hotel Policies -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">{{ __('Hotel Policies') }}</h3>
                    
                    <div class="space-y-4">
                        <!-- Cancellation Policy -->
                        <div class="bg-orange-50 border border-orange-200 rounded-xl p-4">
                            <div class="flex items-start">
                                <i class="fas fa-check-circle text-orange-600 text-lg {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }} mt-1"></i>
                                <div>
                                    <div class="font-semibold text-orange-900 mb-1 text-sm">{{ __('Free Cancellation') }}</div>
                                    <div class="text-xs text-orange-700">{{ __('You can cancel for free up to 24 hours before arrival') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Policy -->
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <div class="flex items-start">
                                <i class="fas fa-credit-card text-blue-600 text-lg {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }} mt-1"></i>
                                <div>
                                    <div class="font-semibold text-blue-900 mb-1 text-sm">{{ __('Payment Policy') }}</div>
                                    <div class="text-xs text-blue-700">{{ __('Pay at the hotel or online payment available') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Pet Policy -->
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <div class="flex items-start">
                                <i class="fas fa-paw text-gray-600 text-lg {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }} mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900 mb-1 text-sm">{{ __('Pet Policy') }}</div>
                                    <div class="text-xs text-gray-700">{{ __('Pets are not allowed') }}</div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <!-- Contact Hotel -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">{{ __('Contact Hotel') }}</h3>
                        
                        <div class="space-y-3">
                            <a href="tel:+966123456789" class="flex items-center text-gray-700 hover:text-orange-600 transition">
                                <i class="fas fa-phone text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                                <span>+966 12 345 6789</span>
                            </a>
                            <a href="mailto:info@hotel.com" class="flex items-center text-gray-700 hover:text-orange-600 transition">
                                <i class="fas fa-envelope text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                                <span>info@hotel.com</span>
                            </a>
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
    // Hotel Images Gallery
    const hotelImages = [
        'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
        'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
        'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
        'https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
        'https://images.unsplash.com/photo-1564501049412-61c2a3083791?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'
    ];
    
    let currentImageIndex = 0;
    
    function openImageModal(index) {
        currentImageIndex = index;
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const imageCounter = document.getElementById('imageCounter');
        
        modalImage.src = hotelImages[index];
        imageCounter.textContent = `${index + 1} / ${hotelImages.length}`;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    function changeImage(direction) {
        currentImageIndex += direction;
        
        if (currentImageIndex < 0) {
            currentImageIndex = hotelImages.length - 1;
        } else if (currentImageIndex >= hotelImages.length) {
            currentImageIndex = 0;
        }
        
        const modalImage = document.getElementById('modalImage');
        const imageCounter = document.getElementById('imageCounter');
        
        modalImage.src = hotelImages[currentImageIndex];
        imageCounter.textContent = `${currentImageIndex + 1} / ${hotelImages.length}`;
    }
    
    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        } else if (e.key === 'ArrowLeft') {
            changeImage(-1);
        } else if (e.key === 'ArrowRight') {
            changeImage(1);
        }
    });
    
    // Close modal when clicking outside image
    document.getElementById('imageModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeImageModal();
        }
    });

    // Set default dates and minimum dates
    const today = new Date();
    const nextMonth = new Date(today);
    nextMonth.setMonth(nextMonth.getMonth() + 1);
    
    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    const todayStr = formatDate(today);
    const nextMonthStr = formatDate(nextMonth);

    const checkInInput = document.getElementById('checkInDate');
    const checkOutInput = document.getElementById('checkOutDate');
    const guestsSelect = document.getElementById('guestsSelect');

    // Set default dates if not in URL
    if (checkInInput && !checkInInput.value) {
        checkInInput.value = todayStr;
    }
    if (checkOutInput && !checkOutInput.value) {
        checkOutInput.value = nextMonthStr;
    }

    // Set minimum dates
    if (checkInInput) {
        checkInInput.setAttribute('min', todayStr);
    }
    if (checkOutInput) {
        checkOutInput.setAttribute('min', todayStr);
    }

    // Update check-out minimum date when check-in changes
    if (checkInInput) {
        checkInInput.addEventListener('change', function() {
            const checkInDate = this.value;
            if (checkOutInput && checkInDate) {
                const nextDay = new Date(checkInDate);
                nextDay.setDate(nextDay.getDate() + 1);
                checkOutInput.setAttribute('min', formatDate(nextDay));
                if (checkOutInput.value && checkOutInput.value <= checkInDate) {
                    checkOutInput.value = formatDate(nextDay);
                }
            }
        });
    }

    // Function to search for rooms
    async function searchRooms() {
        const checkIn = checkInInput?.value || todayStr;
        const checkOut = checkOutInput?.value || nextMonthStr;
        const guests = guestsSelect?.value || '2';
        const roomsContainer = document.getElementById('roomsContainer');
        const roomsList = document.getElementById('roomsList');
        const noRoomsMessage = document.getElementById('noRoomsMessage');
        const availabilityMessage = document.getElementById('availabilityMessage');
        const checkAvailabilityBtn = document.getElementById('checkAvailabilityBtn');

        if (!checkIn || !checkOut) {
            if (availabilityMessage) {
                availabilityMessage.textContent = '{{ __('Please select check-in and check-out dates') }}';
                availabilityMessage.className = 'text-sm text-red-600';
                availabilityMessage.classList.remove('hidden');
            }
            return;
        }

        if (checkOut <= checkIn) {
            if (availabilityMessage) {
                availabilityMessage.textContent = '{{ __('Check Out date must be after Check In date') }}';
                availabilityMessage.className = 'text-sm text-red-600';
                availabilityMessage.classList.remove('hidden');
            }
            return;
        }

        // Show loading
        if (checkAvailabilityBtn) {
            checkAvailabilityBtn.disabled = true;
            checkAvailabilityBtn.innerHTML = '<i class="fas fa-spinner fa-spin {{ app()->getLocale() === "ar" ? "ml-2" : "mr-2" }}"></i>{{ __("Loading...") }}';
        }
        if (roomsList) roomsList.innerHTML = '';
        if (noRoomsMessage) noRoomsMessage.classList.add('hidden');
        if (availabilityMessage) {
            availabilityMessage.textContent = '{{ __("Searching for available rooms...") }}';
            availabilityMessage.className = 'text-sm text-blue-600';
            availabilityMessage.classList.remove('hidden');
        }

        // Prepare search data
        const searchData = {
            CheckIn: checkIn,
            CheckOut: checkOut,
            HotelCodes: '{{ $hotelId }}',
            GuestNationality: 'AE',
            PaxRooms: [
                {
                    Adults: parseInt(guests) || 2,
                    Children: 0,
                    ChildrenAges: []
                }
            ],
            ResponseTime: 18,
            IsDetailedResponse: true,
            Filters: {
                Refundable: true,
                NoOfRooms: 0,
                MealType: 'All'
            }
        };

        try {
            // Call search API
            const response = await fetch("{{ route('hotel.search') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                },
                body: JSON.stringify(searchData)
            });

            const data = await response.json();

            // Handle API error response
            if (data.Status && data.Status.Code !== 200) {
                throw new Error(data.Status.Description || 'Failed to search rooms');
            }

            // Handle error response
            if (data.error) {
                throw new Error(data.error);
            }

            // Display rooms from API response
            displayRooms(data, checkIn, checkOut, guests);

        } catch (err) {
            console.error("Error searching rooms:", err);
            if (availabilityMessage) {
                availabilityMessage.textContent = err.message || '{{ __("Failed to search rooms. Please try again.") }}';
                availabilityMessage.className = 'text-sm text-red-600';
                availabilityMessage.classList.remove('hidden');
            }
            if (noRoomsMessage) noRoomsMessage.classList.remove('hidden');
        } finally {
            if (checkAvailabilityBtn) {
                checkAvailabilityBtn.disabled = false;
                checkAvailabilityBtn.innerHTML = '<i class="fas fa-search {{ app()->getLocale() === "ar" ? "ml-2" : "mr-2" }}"></i>{{ __("Check Availability") }}';
            }
        }
    }

    // Function to display rooms from API response
    function displayRooms(data, checkIn, checkOut, guests) {
        const roomsList = document.getElementById('roomsList');
        const noRoomsMessage = document.getElementById('noRoomsMessage');
        const availabilityMessage = document.getElementById('availabilityMessage');

        // Get hotels from response
        const hotels = data.Hotels || [];
        let allRooms = [];

        // Extract rooms from all hotels
        hotels.forEach(hotel => {
            if (hotel.Rooms && Array.isArray(hotel.Rooms)) {
                hotel.Rooms.forEach(room => {
                    allRooms.push({
                        ...room,
                        HotelCode: hotel.HotelCode,
                        HotelName: hotel.HotelName
                    });
                });
            }
        });

        if (allRooms.length === 0) {
            if (noRoomsMessage) noRoomsMessage.classList.remove('hidden');
            if (roomsList) roomsList.classList.add('hidden');
            if (availabilityMessage) {
                availabilityMessage.textContent = '{{ __("No rooms available for selected dates") }}';
                availabilityMessage.className = 'text-sm text-gray-600';
            }
            return;
        }

        // Clear existing rooms
        if (roomsList) {
            roomsList.innerHTML = '';
            roomsList.classList.remove('hidden');
        }
        if (noRoomsMessage) noRoomsMessage.classList.add('hidden');

        // Display rooms
        allRooms.forEach((room, index) => {
            const roomCard = createRoomCard(room, index, checkIn, checkOut, guests);
            if (roomsList) roomsList.appendChild(roomCard);
        });

        // Calculate nights
        const checkInDate = new Date(checkIn);
        const checkOutDate = new Date(checkOut);
        const nights = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));

        if (availabilityMessage) {
            availabilityMessage.textContent = `{{ __('Found') }} ${allRooms.length} {{ __('Available Rooms') }}`;
            availabilityMessage.className = 'text-sm text-green-600';
            availabilityMessage.classList.remove('hidden');
        }

        // Update booking summary sidebar
        updateBookingSummary(checkIn, checkOut, guests, nights);
    }

    // Function to create room card
    function createRoomCard(room, index, checkIn, checkOut, guests) {
        const div = document.createElement('div');
        div.className = 'border-2 border-gray-200 rounded-2xl p-6 hover:border-orange-500 transition';
        
        const roomName = room.RoomTypeName || room.RoomName || `{{ __('Room') }} ${index + 1}`;
        const roomDescription = room.RoomDescription || '';
        const price = room.Rate || room.Price || 0;
        const currency = room.Currency || 'SAR';
        const imageUrl = room.ImageUrl || 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';

        div.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="relative h-48 md:h-full min-h-[200px]">
                    <img src="${imageUrl}" alt="${roomName}" class="w-full h-full object-cover rounded-xl">
                </div>
                <div class="md:col-span-2">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">${roomName}</h3>
                    ${roomDescription ? `<p class="text-gray-600 text-sm mb-3">${roomDescription}</p>` : ''}
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div>
                            <span class="text-3xl font-extrabold text-orange-600">${price}</span>
                            <span class="text-gray-500 text-sm {{ app()->getLocale() === 'ar' ? 'mr-1' : 'ml-1' }}">${currency}</span>
                            <div class="text-xs text-gray-400">{{ __('per night') }}</div>
                        </div>
                        <a href="{{ route('reservation') }}?hotel_id={{ $hotelId }}&room_type=${index}&check_in=${checkIn}&check_out=${checkOut}&guests=${guests}" 
                           class="bg-gradient-to-r from-orange-600 to-orange-600 text-white px-8 py-3 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition shadow-lg">
                            {{ __('Book Now') }}
                        </a>
                    </div>
                </div>
            </div>
        `;
        
        return div;
    }

    // Check availability button
    document.getElementById('checkAvailabilityBtn')?.addEventListener('click', function() {
        searchRooms();
    });

    // Update booking summary sidebar
    function updateBookingSummary(checkIn, checkOut, guests, nights) {
        // This will be called to update the sidebar if needed
        // For now, we'll just update the URL parameters
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('check_in', checkIn);
        currentUrl.searchParams.set('check_out', checkOut);
        currentUrl.searchParams.set('guests', guests);
        window.history.replaceState({}, '', currentUrl);
    }

    // Auto-search rooms on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Wait a bit for dates to be set
        setTimeout(() => {
            searchRooms();
        }, 500);
    });
</script>
@endpush

