@extends('Web.layouts.app')

@section('title', __('Hotel Details') . ' - ' . __('Book Hotels - Best Offers and Services'))

@section('content')
    <!-- Hotel Header -->
    <section class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('all.hotels') }}?destination={{ request('destination') }}&check_in={{ request('check_in') }}&check_out={{ request('check_out') }}&guests={{ request('guests') }}"
                    class="text-white/80 hover:text-white transition">
                    <i
                        class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                    {{ __('Back to List') }}
                </a>
            </div>
            @php
                $hotel = $hotelDetails['HotelDetails'][0] ?? null;
            @endphp
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                @if ($hotel && isset($hotel['HotelName']))
                    {{ $hotel['HotelName'] }}
                @else
                    {{ __('International Luxury Hotel') }} {{ $hotelId }}
                @endif
            </h1>
            <div class="flex items-center gap-4 flex-wrap">
                @if ($hotel && isset($hotel['Address']))
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                        <span>{{ $hotel['Address'] }}@if (isset($hotel['CityName']))
                                , {{ $hotel['CityName'] }}
                                @endif @if (isset($hotel['CountryName']))
                                    , {{ $hotel['CountryName'] }}
                                @endif
                        </span>
                    </div>
                @else
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                        <span>{{ request('destination', __('Riyadh')) }}, {{ __('Saudi Arabia') }}</span>
                    </div>
                @endif
                @if ($hotel && isset($hotel['HotelRating']))
                    <div class="flex items-center">
                        <div class="flex text-yellow-500 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}">
                            @for ($i = 0; $i < $hotel['HotelRating']; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                        </div>
                        <span
                            class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">{{ $hotel['HotelRating'] }}</span>
                    </div>
                @else
                    <div class="flex items-center">
                        <div class="flex text-yellow-500 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}">
                            @for ($i = 0; $i < 5; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                        </div>
                        <span class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">4.8</span>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Hotel Images Gallery -->
    <section class="py-8 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @php
                $hotel = $hotelDetails['HotelDetails'][0] ?? null;
                $images = [];
                if ($hotel && isset($hotel['Images']) && is_array($hotel['Images'])) {
                    // Images is an array of URLs
                    foreach ($hotel['Images'] as $imgUrl) {
                        $images[] = ['ImageUrl' => $imgUrl];
                    }
                } elseif ($hotel && isset($hotel['Image']) && !empty($hotel['Image'])) {
                    // Single Image field
                    $images[] = ['ImageUrl' => $hotel['Image']];
                } elseif ($hotel && isset($hotel['ImageUrls']) && is_array($hotel['ImageUrls'])) {
                    $images = $hotel['ImageUrls'];
                }
                // Default images if none from API
                if (empty($images)) {
                    $images = [
                        [
                            'ImageUrl' =>
                                'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                        ],
                        [
                            'ImageUrl' =>
                                'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                        ],
                        [
                            'ImageUrl' =>
                                'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                        ],
                        [
                            'ImageUrl' =>
                                'https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                        ],
                        [
                            'ImageUrl' =>
                                'https://images.unsplash.com/photo-1564501049412-61c2a3083791?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                        ],
                    ];
                }
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4" id="hotelGallery">
                @foreach ($images as $index => $image)
                    @php
                        $imageUrl = $image['ImageUrl'] ?? (is_string($image) ? $image : '');
                    @endphp
                    @if ($index == 0)
                        <div class="md:col-span-2 md:row-span-2 cursor-pointer"
                            onclick="openImageModal({{ $index }})">
                            <img src="{{ $imageUrl }}" alt="{{ $hotel['HotelName'] ?? 'فندق' }}"
                                class="w-full h-[400px] object-cover rounded-2xl hover:opacity-90 transition">
                        </div>
                    @elseif($index < 5)
                        <div class="cursor-pointer" onclick="openImageModal({{ $index }})">
                            <img src="{{ $imageUrl }}" alt="{{ $hotel['HotelName'] ?? 'فندق' }}"
                                class="w-full h-48 object-cover rounded-2xl hover:opacity-90 transition">
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center">
        <div class="relative w-full h-full flex items-center justify-center p-4">
            <!-- Close Button -->
            <button onclick="closeImageModal()"
                class="absolute top-4 {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} text-white hover:text-orange-500 transition text-3xl z-10">
                <i class="fas fa-times"></i>
            </button>

            <!-- Previous Button -->
            <button onclick="changeImage(-1)"
                class="absolute {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }} top-1/2 transform -translate-y-1/2 text-white hover:text-orange-500 transition text-4xl z-10 bg-black bg-opacity-50 rounded-full w-12 h-12 flex items-center justify-center">
                <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}"></i>
            </button>

            <!-- Next Button -->
            <button onclick="changeImage(1)"
                class="absolute {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} top-1/2 transform -translate-y-1/2 text-white hover:text-orange-500 transition text-4xl z-10 bg-black bg-opacity-50 rounded-full w-12 h-12 flex items-center justify-center">
                <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}"></i>
            </button>

            <!-- Image Container -->
            <div class="max-w-7xl w-full h-full flex items-center justify-center">
                <img id="modalImage" src="" alt="فندق" class="max-w-full max-h-full object-contain rounded-lg">
            </div>

            <!-- Image Counter -->
            <div
                class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white bg-black bg-opacity-50 px-4 py-2 rounded-full text-sm">
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
                        @php
                            $hotel = $hotelDetails['HotelDetails'][0] ?? null;
                        @endphp
                        @if ($hotel && isset($hotel['Description']))
                            <div class="text-gray-700 leading-relaxed">
                                {!! $hotel['Description'] !!}
                            </div>
                        @else
                            <p class="text-gray-700 leading-relaxed mb-4">
                                {{ __('Hotel Description 1') }}
                            </p>
                            <p class="text-gray-700 leading-relaxed">
                                {{ __('Hotel Description 2') }}
                            </p>
                        @endif
                    </div>

                    <!-- Amenities -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Facilities and Services') }}</h2>
                        @php
                            $hotel = $hotelDetails['HotelDetails'][0] ?? null;
                            $facilities = $hotel['HotelFacilities'] ?? [];
                            $totalFacilities = count($facilities);
                            $showLimit = 10;
                            $hasMore = $totalFacilities > $showLimit;
                        @endphp
                        @if (!empty($facilities) && is_array($facilities))
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="facilitiesList">
                                @foreach ($facilities as $index => $facility)
                                    <div
                                        class="flex items-center facility-item {{ $index >= $showLimit ? 'hidden' : '' }}">
                                        <i
                                            class="fas fa-check-circle text-orange-600 text-lg {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                                        <span class="text-gray-700">{{ $facility }}</span>
                                    </div>
                                @endforeach
                            </div>
                            @if ($hasMore)
                                <div class="mt-4 text-center">
                                    <button type="button" id="showMoreFacilities"
                                        class="text-orange-600 hover:text-orange-700 font-semibold transition">
                                        <i
                                            class="fas fa-chevron-down {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                                        {{ __('Show More') }} ({{ $totalFacilities - $showLimit }} {{ __('more') }})
                                    </button>
                                    <button type="button" id="showLessFacilities"
                                        class="text-orange-600 hover:text-orange-700 font-semibold transition hidden">
                                        <i
                                            class="fas fa-chevron-up {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                                        {{ __('Show Less') }}
                                    </button>
                                </div>
                            @endif
                        @else
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div class="flex items-center">
                                    <i
                                        class="fas fa-wifi text-orange-600 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                                    <span class="text-gray-700">{{ __('Free WiFi') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i
                                        class="fas fa-swimming-pool text-orange-600 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                                    <span class="text-gray-700">{{ __('Pool') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i
                                        class="fas fa-utensils text-orange-600 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                                    <span class="text-gray-700">{{ __('Restaurant') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i
                                        class="fas fa-spa text-orange-600 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                                    <span class="text-gray-700">{{ __('Spa') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i
                                        class="fas fa-dumbbell text-orange-600 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                                    <span class="text-gray-700">{{ __('Gym') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i
                                        class="fas fa-parking text-orange-600 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                                    <span class="text-gray-700">{{ __('Parking') }}</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Date Selection & Availability Check -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Select Dates to View Availability') }}
                        </h2>
                        <form id="availabilityForm" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2">
                                    <i
                                        class="fas fa-calendar-alt text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ __('Check In') }}
                                </label>
                                <input type="date" id="checkInDate" name="check_in"
                                    value="{{ request('check_in') ?: '' }}" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                            </div>
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2">
                                    <i
                                        class="fas fa-calendar-check text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ __('Check Out') }}
                                </label>
                                <input type="date" id="checkOutDate" name="check_out"
                                    value="{{ request('check_out') ?: '' }}" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                            </div>
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2">
                                    <i
                                        class="fas fa-users text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ __('Guests') }}
                                </label>
                                <select id="guestsSelect" name="guests"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                                    <option value="1" {{ request('guests') == '1' ? 'selected' : '' }}>1
                                        {{ __('Guest') }}</option>
                                    <option value="2" {{ request('guests', '2') == '2' ? 'selected' : '' }}>2
                                        {{ __('Guests') }}</option>
                                    <option value="3" {{ request('guests') == '3' ? 'selected' : '' }}>3
                                        {{ __('Guests') }}</option>
                                    <option value="4" {{ request('guests') == '4' ? 'selected' : '' }}>4
                                        {{ __('Guests') }}</option>
                                    <option value="5" {{ request('guests') == '5' ? 'selected' : '' }}>5+
                                        {{ __('Guests') }}</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="button" id="checkAvailabilityBtn"
                                    class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-3 rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 transition shadow-lg">
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
                            <label
                                class="flex items-center p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                                <input type="checkbox" name="extra_features[]" value="late_checkout"
                                    class="w-5 h-5 text-orange-600 rounded">
                                <span class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">
                                    <i
                                        class="fas fa-clock text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ __('Late Check-out') }}
                                </span>
                            </label>
                            <label
                                class="flex items-center p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                                <input type="checkbox" name="extra_features[]" value="early_checkin"
                                    class="w-5 h-5 text-orange-600 rounded">
                                <span class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">
                                    <i
                                        class="fas fa-early-bird text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ __('Early Check-in') }}
                                </span>
                            </label>
                            <label
                                class="flex items-center p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                                <input type="checkbox" name="extra_features[]" value="extra_bed"
                                    class="w-5 h-5 text-orange-600 rounded">
                                <span class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">
                                    <i
                                        class="fas fa-bed text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ __('Extra Bed') }}
                                </span>
                            </label>
                            <label
                                class="flex items-center p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                                <input type="checkbox" name="extra_features[]" value="baby_cot"
                                    class="w-5 h-5 text-orange-600 rounded">
                                <span class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">
                                    <i
                                        class="fas fa-baby text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ __('Baby Cot') }}
                                </span>
                            </label>
                            <label
                                class="flex items-center p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                                <input type="checkbox" name="extra_features[]" value="room_upgrade"
                                    class="w-5 h-5 text-orange-600 rounded">
                                <span class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">
                                    <i
                                        class="fas fa-arrow-up text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ __('Room Upgrade') }}
                                </span>
                            </label>
                            <label
                                class="flex items-center p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                                <input type="checkbox" name="extra_features[]" value="spa_package"
                                    class="w-5 h-5 text-orange-600 rounded">
                                <span class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">
                                    <i
                                        class="fas fa-spa text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ __('Spa Package') }}
                                </span>
                            </label>
                            <label
                                class="flex items-center p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                                <input type="checkbox" name="extra_features[]" value="dinner_package"
                                    class="w-5 h-5 text-orange-600 rounded">
                                <span class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">
                                    <i
                                        class="fas fa-utensils text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
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
                                @if (isset($availableRooms) && count($availableRooms) > 0)
                                    @foreach ($availableRooms as $index => $room)
                                        <div
                                            class="border-2 border-gray-200 rounded-2xl p-6 hover:border-orange-500 transition">
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                                <!-- Room Image -->
                                                <div class="relative h-48 md:h-full min-h-[200px]">
                                                    <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                                                        alt="غرفة" class="w-full h-full object-cover rounded-xl">
                                                </div>

                                                <!-- Room Details -->
                                                <div class="md:col-span-2">
                                                    <div class="flex items-start justify-between mb-3">
                                                        <div>
                                                            <h3 class="text-xl font-bold text-gray-900 mb-2">
                                                                {{ is_array($room['Name']) ? $room['Name'][0] : $room['Name'] }}
                                                            </h3>
                                                            <div class="flex flex-wrap gap-2 mb-3">
                                                                <span
                                                                    class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg">
                                                                    <i
                                                                        class="fas fa-bed {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                                                    {{ __('Room') }}
                                                                </span>
                                                                @if (isset($room['Amenities']))
                                                                    @foreach (array_slice($room['Amenities'] ?? [], 0, 3) as $amenity)
                                                                        <span
                                                                            class="px-2 py-1 bg-green-50 text-green-700 text-xs rounded-lg">
                                                                            <i
                                                                                class="fas fa-check {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                                                            {{ $amenity }}
                                                                        </span>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="flex items-center justify-between pt-4 border-t border-gray-200">
                                                        <div>
                                                            <div class="flex items-baseline">
                                                                <span class="text-3xl font-extrabold text-orange-600">
                                                                    {{ $room['TotalFare'] ?? ($room['Price']['PublishedPrice'] ?? 0) }}
                                                                </span>
                                                                <span
                                                                    class="text-gray-500 text-sm {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">
                                                                    {{ $room['Currency'] ?? ($room['Price']['CurrencyCode'] ?? 'SAR') }}
                                                                </span>
                                                            </div>
                                                            <div class="text-xs text-gray-400">/ {{ __('Total Price') }}
                                                                • {{ __('including taxes') }}</div>
                                                        </div>
                                                        <a href="{{ route('reservation') }}?hotel_id={{ $hotelId }}&booking_code={{ $room['BookingCode'] ?? '' }}&check_in={{ request('check_in') }}&check_out={{ request('check_out') }}&guests={{ request('guests') }}"
                                                            class="bg-gradient-to-r from-orange-600 to-orange-600 text-white px-8 py-3 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition shadow-lg">
                                                            {{ __('Book Now') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-8 text-gray-500">
                                        <i class="fas fa-calendar-times text-4xl mb-4"></i>
                                        <p class="text-lg">{{ __('No rooms available for selected dates') }}</p>
                                    </div>
                                @endif
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
                            @php
                                $hotel = $hotelDetails['HotelDetails'][0] ?? null;
                            @endphp
                            <div class="space-y-4">
                                <!-- Location -->
                                <div class="flex items-start">
                                    <div
                                        class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-map-marker-alt text-orange-600"></i>
                                    </div>
                                    <div class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }}">
                                        <div class="font-semibold text-gray-900 mb-1">{{ __('Location') }}</div>
                                        <p class="text-sm text-gray-600">
                                            @if ($hotel && isset($hotel['Address']))
                                                {{ $hotel['Address'] }}@if (isset($hotel['PinCode']))
                                                    , {{ $hotel['PinCode'] }}
                                                @endif
                                                <br>
                                                @if (isset($hotel['CityName']))
                                                    {{ $hotel['CityName'] }},
                                                @endif
                                                @if (isset($hotel['CountryName']))
                                                    {{ $hotel['CountryName'] }}
                                                @endif
                                            @else
                                                {{ request('destination', __('Riyadh')) }}, {{ __('Saudi Arabia') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Rating -->
                                <div class="flex items-start">
                                    <div
                                        class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-star text-orange-600"></i>
                                    </div>
                                    <div class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }}">
                                        <div class="font-semibold text-gray-900 mb-1">{{ __('Rating') }}</div>
                                        <div class="flex items-center">
                                            <div
                                                class="flex text-yellow-500 text-sm {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}">
                                                @for ($i = 0; $i < ($hotel['HotelRating'] ?? 5); $i++)
                                                    <i class="fas fa-star"></i>
                                                @endfor
                                            </div>
                                            <span class="text-sm text-gray-600">{{ $hotel['HotelRating'] ?? '4.8' }}
                                                @if (isset($hotel['HotelRating']))
                                                    {{ __('Stars') }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Check-in/out Times -->
                                @if ($hotel && (isset($hotel['CheckInTime']) || isset($hotel['CheckOutTime'])))
                                    <div class="flex items-start">
                                        <div
                                            class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-clock text-orange-600"></i>
                                        </div>
                                        <div class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }}">
                                            <div class="font-semibold text-gray-900 mb-1">
                                                {{ __('Check-in / Check-out') }}</div>
                                            <p class="text-sm text-gray-600">
                                                {{ __('Check-in') }}: {{ $hotel['CheckInTime'] ?? '2:00 PM' }}<br>
                                                {{ __('Check-out') }}: {{ $hotel['CheckOutTime'] ?? '12:00 PM' }}
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Website -->
                                @if ($hotel && isset($hotel['HotelWebsiteUrl']))
                                    <div class="flex items-start">
                                        <div
                                            class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-globe text-orange-600"></i>
                                        </div>
                                        <div class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }}">
                                            <div class="font-semibold text-gray-900 mb-1">{{ __('Website') }}</div>
                                            <p class="text-sm text-gray-600">
                                                <a href="{{ $hotel['HotelWebsiteUrl'] }}" target="_blank"
                                                    class="hover:text-orange-600 break-all">{{ $hotel['HotelWebsiteUrl'] }}</a>
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Hotel Policies -->
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">{{ __('Hotel Policies') }}</h3>

                            <div class="space-y-4">
                                <!-- Cancellation Policy -->
                                <div class="bg-orange-50 border border-orange-200 rounded-xl p-4">
                                    <div class="flex items-start">
                                        <i
                                            class="fas fa-check-circle text-orange-600 text-lg {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }} mt-1"></i>
                                        <div>
                                            <div class="font-semibold text-orange-900 mb-1 text-sm">
                                                {{ __('Free Cancellation') }}</div>
                                            <div class="text-xs text-orange-700">
                                                {{ __('You can cancel for free up to 24 hours before arrival') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Policy -->
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                    <div class="flex items-start">
                                        <i
                                            class="fas fa-credit-card text-blue-600 text-lg {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }} mt-1"></i>
                                        <div>
                                            <div class="font-semibold text-blue-900 mb-1 text-sm">
                                                {{ __('Payment Policy') }}</div>
                                            <div class="text-xs text-blue-700">
                                                {{ __('Pay at the hotel or online payment available') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pet Policy -->
                                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                    <div class="flex items-start">
                                        <i
                                            class="fas fa-paw text-gray-600 text-lg {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }} mt-1"></i>
                                        <div>
                                            <div class="font-semibold text-gray-900 mb-1 text-sm">{{ __('Pet Policy') }}
                                            </div>
                                            <div class="text-xs text-gray-700">{{ __('Pets are not allowed') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Hotel -->
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">{{ __('Contact Hotel') }}</h3>
                            @php
                                $hotel = $hotelDetails['HotelDetails'][0] ?? null;
                            @endphp
                            <div class="space-y-4">
                                <!-- Phone -->
                                @if ($hotel && isset($hotel['PhoneNumber']))
                                    <div class="flex items-start">
                                        <div
                                            class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-phone text-orange-600"></i>
                                        </div>
                                        <div class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }}">
                                            <div class="font-semibold text-gray-900 mb-1">{{ __('Phone') }}</div>
                                            <p class="text-sm text-gray-600">
                                                <a href="tel:{{ $hotel['PhoneNumber'] }}"
                                                    class="hover:text-orange-600">{{ $hotel['PhoneNumber'] }}</a>
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Email -->
                                @if ($hotel && isset($hotel['Email']))
                                    <div class="flex items-start">
                                        <div
                                            class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-envelope text-orange-600"></i>
                                        </div>
                                        <div class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }}">
                                            <div class="font-semibold text-gray-900 mb-1">{{ __('Email') }}</div>
                                            <p class="text-sm text-gray-600">
                                                <a href="mailto:{{ $hotel['Email'] }}"
                                                    class="hover:text-orange-600 break-all">{{ $hotel['Email'] }}</a>
                                            </p>
                                        </div>
                                    </div>
                                @endif
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
        // Hotel Images Gallery - Get images from API or use defaults
        @php
            $hotel = $hotelDetails['HotelDetails'][0] ?? null;
            $apiImages = [];
            if ($hotel && isset($hotel['Images']) && is_array($hotel['Images'])) {
                // Images is an array of URLs
                $apiImages = array_slice($hotel['Images'], 0, 5);
            } elseif ($hotel && isset($hotel['Image']) && !empty($hotel['Image'])) {
                // Single Image field
                $apiImages = [$hotel['Image']];
            }
            $defaultImages = ['https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80', 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80', 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80', 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80', 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'];
            $hotelImagesArray = !empty($apiImages) ? $apiImages : $defaultImages;
        @endphp
        const hotelImages = @json($hotelImagesArray);

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
        nextMonth.setDate(nextMonth.getDate() + 1);

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

        // Set default dates if not in URL or if empty string
        if (checkInInput && (!checkInInput.value || checkInInput.value.trim() === '')) {
            checkInInput.value = todayStr;
        }
        if (checkOutInput && (!checkOutInput.value || checkOutInput.value.trim() === '')) {
            // Set check-out to at least one day after check-in
            const checkInDate = checkInInput?.value || todayStr;
            const nextDay = new Date(checkInDate);
            nextDay.setDate(nextDay.getDate() + 1);
            checkOutInput.value = formatDate(nextDay);
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
            // Get values and handle empty strings properly
            let checkIn = checkInInput?.value?.trim() || '';
            let checkOut = checkOutInput?.value?.trim() || '';
            const guests = guestsSelect?.value || '2';

            // Use defaults if empty
            if (!checkIn) {
                checkIn = todayStr;
                if (checkInInput) checkInInput.value = checkIn;
            }
            if (!checkOut) {
                const nextDay = new Date(checkIn);
                nextDay.setDate(nextDay.getDate() + 1);
                checkOut = formatDate(nextDay);
                if (checkOutInput) checkOutInput.value = checkOut;
            }

            const roomsContainer = document.getElementById('roomsContainer');
            const roomsList = document.getElementById('roomsList');
            const noRoomsMessage = document.getElementById('noRoomsMessage');
            const availabilityMessage = document.getElementById('availabilityMessage');
            const checkAvailabilityBtn = document.getElementById('checkAvailabilityBtn');

            // Validate dates are not empty (shouldn't happen after above, but double-check)
            if (!checkIn || !checkOut) {
                if (availabilityMessage) {
                    availabilityMessage.textContent = '{{ __('Please select check-in and check-out dates') }}';
                    availabilityMessage.className = 'text-sm text-red-600';
                    availabilityMessage.classList.remove('hidden');
                }
                return;
            }

            // Validate check-out is after check-in
            if (checkOut <= checkIn) {
                // Auto-adjust check-out to be one day after check-in
                const checkInDate = new Date(checkIn);
                checkInDate.setDate(checkInDate.getDate() + 1);
                checkOut = formatDate(checkInDate);
                if (checkOutInput) checkOutInput.value = checkOut;
            }

            // Update URL immediately with selected parameters
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('check_in', checkIn);
            currentUrl.searchParams.set('check_out', checkOut);
            currentUrl.searchParams.set('guests', guests);
            window.history.replaceState({}, '', currentUrl);

            // Show loading
            if (checkAvailabilityBtn) {
                checkAvailabilityBtn.disabled = true;
                checkAvailabilityBtn.innerHTML =
                    '<i class="fas fa-spinner fa-spin {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('Loading...') }}';
            }
            if (roomsList) roomsList.innerHTML = '';
            if (noRoomsMessage) noRoomsMessage.classList.add('hidden');
            if (availabilityMessage) {
                availabilityMessage.textContent = '{{ __('Searching for available rooms...') }}';
                availabilityMessage.className = 'text-sm text-blue-600';
                availabilityMessage.classList.remove('hidden');
            }

            // Prepare search data - ensure dates are in correct format (YYYY-MM-DD)
            const searchData = {
                CheckIn: checkIn,
                CheckOut: checkOut,
                HotelCodes: '{{ $hotelId }}',
                GuestNationality: 'AE',
                PaxRooms: [{
                    Adults: parseInt(guests) || 1,
                    Children: 0,
                    ChildrenAges: []
                }],
                ResponseTime: 18,
                IsDetailedResponse: true,
                Filters: {
                    Refundable: false,
                    NoOfRooms: 0,
                    MealType: 'All'
                }
            };

            // Log search data for debugging
            console.log('Searching for rooms with data:', searchData);

            try {
                // Call search API
                const response = await fetch("{{ route('hotel.search') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                            'content') || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(searchData)
                });

                // Check if response is ok
                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({
                        error: 'Network error'
                    }));
                    throw new Error(errorData.Status?.Description || errorData.error ||
                        `HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                // Log response for debugging
                console.log('API Response:', data);

                // Mock Data for Fallback
                const mockData = {
                    "Hotels": [{
                        "HotelCode": "{{ $hotelId }}",
                        "HotelName": "International Luxury Hotel",
                        "Rooms": [{
                                "RoomName": "{{ __('Deluxe Room') }}",
                                "RoomDescription": "{{ __('Room Description 1') }}",
                                "Rate": 400,
                                "Currency": "SAR",
                                "ImageUrl": "https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                            },
                            {
                                "RoomName": "{{ __('Superior Room') }}",
                                "RoomDescription": "{{ __('Room Description 2') }}",
                                "Rate": 500,
                                "Currency": "SAR",
                                "ImageUrl": "https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                            },
                            {
                                "RoomName": "{{ __('Luxury Room') }}",
                                "RoomDescription": "{{ __('Room Description 3') }}",
                                "Rate": 600,
                                "Currency": "SAR",
                                "ImageUrl": "https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                            }
                        ]
                    }]
                };

                if (data.Status && data.Status.Code !== 200) {
                    console.warn("API returned error/no rooms (Code " + data.Status.Code + ")");
                    // Show no rooms message instead of mock data
                    if (availabilityMessage) {
                        availabilityMessage.textContent = '{{ __('No rooms available for selected dates') }}';
                        availabilityMessage.className = 'text-sm text-gray-600';
                        availabilityMessage.classList.remove('hidden');
                    }
                    if (noRoomsMessage) noRoomsMessage.classList.remove('hidden');
                    if (roomsList) roomsList.classList.add('hidden');
                    return;
                }

                // Handle error response
                if (data.error) {
                    throw new Error(data.error);
                }

                // Check if response has Hotels or HotelResult array
                const hasHotels = (data.Hotels && Array.isArray(data.Hotels)) || (data.HotelResult && Array.isArray(data
                    .HotelResult));
                if (!hasHotels) {
                    console.warn('No Hotels or HotelResult array in response:', data);
                    if (availabilityMessage) {
                        availabilityMessage.textContent = '{{ __('No rooms available for selected dates') }}';
                        availabilityMessage.className = 'text-sm text-gray-600';
                        availabilityMessage.classList.remove('hidden');
                    }
                    if (noRoomsMessage) noRoomsMessage.classList.remove('hidden');
                    if (roomsList) roomsList.classList.add('hidden');
                    return;
                }

                // Display rooms from API response
                displayRooms(data, checkIn, checkOut, guests);

            } catch (err) {
                console.error("Error searching rooms:", err);

                // Show error message instead of mock data
                if (availabilityMessage) {
                    availabilityMessage.textContent = '{{ __('No rooms available for selected dates') }}';
                    availabilityMessage.className = 'text-sm text-gray-600';
                    availabilityMessage.classList.remove('hidden');
                }
                if (noRoomsMessage) noRoomsMessage.classList.remove('hidden');
                if (roomsList) roomsList.classList.add('hidden');
            } finally {
                if (checkAvailabilityBtn) {
                    checkAvailabilityBtn.disabled = false;
                    checkAvailabilityBtn.innerHTML =
                        '<i class="fas fa-search {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('Check Availability') }}';
                }
            }
        }

        // Function to display rooms from API response
        function displayRooms(data, checkIn, checkOut, guests) {
            const roomsList = document.getElementById('roomsList');
            const noRoomsMessage = document.getElementById('noRoomsMessage');
            const availabilityMessage = document.getElementById('availabilityMessage');

            console.log('Displaying rooms from data:', data);

            // Get hotels from response - handle different response structures
            let hotels = [];
            if (data.HotelResult && Array.isArray(data.HotelResult)) {
                // New API structure: HotelResult array
                hotels = data.HotelResult;
            } else if (data.Hotels && Array.isArray(data.Hotels)) {
                // Old API structure: Hotels array
                hotels = data.Hotels;
            } else if (data.hotels && Array.isArray(data.hotels)) {
                hotels = data.hotels;
            } else if (Array.isArray(data)) {
                hotels = data;
            }

            let allRooms = [];

            // Extract rooms from all hotels
            hotels.forEach(hotel => {
                const hotelCode = hotel.HotelCode || hotel.HotelID;
                const hotelName = hotel.HotelName || '';
                const currency = hotel.Currency || 'USD';

                console.log('Processing hotel:', hotelCode, hotelName);

                // Handle different room structures
                let rooms = [];
                if (hotel.Rooms && Array.isArray(hotel.Rooms)) {
                    rooms = hotel.Rooms;
                } else if (hotel.rooms && Array.isArray(hotel.rooms)) {
                    rooms = hotel.rooms;
                } else if (hotel.RoomDetails && Array.isArray(hotel.RoomDetails)) {
                    rooms = hotel.RoomDetails;
                }

                if (rooms.length > 0) {
                    console.log(`Found ${rooms.length} rooms in hotel ${hotelCode}`);
                    rooms.forEach(room => {
                        allRooms.push({
                            ...room,
                            HotelCode: hotelCode,
                            HotelName: hotelName,
                            Currency: currency // Add currency from hotel level
                        });
                    });
                } else {
                    console.warn(`No rooms found in hotel ${hotelCode}`, hotel);
                }
            });

            console.log(`Total rooms found: ${allRooms.length}`);

            if (allRooms.length === 0) {
                console.warn('No rooms found in response');
                if (noRoomsMessage) noRoomsMessage.classList.remove('hidden');
                if (roomsList) roomsList.classList.add('hidden');
                if (availabilityMessage) {
                    availabilityMessage.textContent = '{{ __('No rooms available for selected dates') }}';
                    availabilityMessage.className = 'text-sm text-gray-600';
                    availabilityMessage.classList.remove('hidden');
                }
                return;
            }

            // Clear existing rooms and show rooms list
            if (roomsList) {
                roomsList.innerHTML = '';
                roomsList.classList.remove('hidden');
                console.log('Rooms list is now visible');
            }
            if (noRoomsMessage) noRoomsMessage.classList.add('hidden');

            // Display rooms
            console.log(`Adding ${allRooms.length} rooms to the list`);
            allRooms.forEach((room, index) => {
                const roomCard = createRoomCard(room, index, checkIn, checkOut, guests);
                if (roomsList && roomCard) {
                    roomsList.appendChild(roomCard);
                    // Get room name for logging
                    let roomNameForLog = 'Unknown';
                    if (room.Name && Array.isArray(room.Name) && room.Name.length > 0) {
                        roomNameForLog = room.Name[0];
                    } else if (room.Name && typeof room.Name === 'string') {
                        roomNameForLog = room.Name;
                    } else if (room.RoomTypeName) {
                        roomNameForLog = room.RoomTypeName;
                    } else if (room.RoomName) {
                        roomNameForLog = room.RoomName;
                    }
                    console.log(`Added room ${index + 1}:`, roomNameForLog);
                }
            });

            // Verify rooms were added
            if (roomsList) {
                const addedRooms = roomsList.children.length;
                console.log(`Total rooms in DOM: ${addedRooms}`);
                if (addedRooms === 0) {
                    console.error('No rooms were added to the DOM!');
                }
            }

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
            try {
                const div = document.createElement('div');
                div.className = 'border-2 border-gray-200 rounded-2xl p-6 hover:border-orange-500 transition';

                // Handle room name - could be array or string
                let roomName = `{{ __('Room') }} ${index + 1}`;
                if (room.Name && Array.isArray(room.Name) && room.Name.length > 0) {
                    roomName = room.Name[0];
                } else if (room.Name && typeof room.Name === 'string') {
                    roomName = room.Name;
                } else if (room.RoomTypeName) {
                    roomName = room.RoomTypeName;
                } else if (room.RoomName) {
                    roomName = room.RoomName;
                } else if (room.RoomType) {
                    roomName = room.RoomType;
                }

                // Handle room description/inclusion
                const roomDescription = room.RoomDescription || room.Description || '';
                const inclusion = room.Inclusion || '';
                const mealType = room.MealType || '';

                // Handle price - prioritize TotalFare, then calculate from DayRates
                let price = 0;
                if (room.TotalFare !== undefined && room.TotalFare !== null) {
                    price = parseFloat(room.TotalFare) || 0;
                } else if (room.Rate) {
                    price = typeof room.Rate === 'object' ? (room.Rate.Amount || room.Rate.TotalAmount || 0) : parseFloat(
                        room.Rate) || 0;
                } else if (room.Price) {
                    price = typeof room.Price === 'object' ? (room.Price.Amount || room.Price.TotalAmount || 0) :
                        parseFloat(room.Price) || 0;
                } else if (room.Amount) {
                    price = parseFloat(room.Amount) || 0;
                } else if (room.DayRates && Array.isArray(room.DayRates) && room.DayRates.length > 0) {
                    // Calculate total from DayRates
                    const dayRates = room.DayRates[0];
                    if (Array.isArray(dayRates)) {
                        price = dayRates.reduce((sum, day) => {
                            return sum + (parseFloat(day.BasePrice) || 0);
                        }, 0);
                    }
                }

                // Calculate per night price
                const checkInDate = new Date(checkIn);
                const checkOutDate = new Date(checkOut);
                const nights = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
                const pricePerNight = nights > 0 ? (price / nights).toFixed(2) : price.toFixed(2);

                const currency = room.Currency || 'USD';
                const imageUrl = room.ImageUrl || room.Image ||
                    'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';

                console.log(`Creating room card ${index + 1}:`, {
                    roomName,
                    price,
                    pricePerNight,
                    currency,
                    nights,
                    bookingCode: room.BookingCode
                });

                // Build room features/info
                let roomInfo = [];
                if (inclusion) {
                    roomInfo.push(
                        `<span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg"><i class="fas fa-utensils {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> ${inclusion}</span>`
                    );
                }
                if (mealType) {
                    const mealTypeText = mealType.replace('_', ' ').replace(/([A-Z])/g, ' $1').trim();
                    roomInfo.push(
                        `<span class="px-2 py-1 bg-green-50 text-green-700 text-xs rounded-lg"><i class="fas fa-coffee {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> ${mealTypeText}</span>`
                    );
                }
                if (room.IsRefundable) {
                    roomInfo.push(
                        `<span class="px-2 py-1 bg-purple-50 text-purple-700 text-xs rounded-lg"><i class="fas fa-shield-alt {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('Refundable') }}</span>`
                    );
                }
                if (room.RoomPromotion && Array.isArray(room.RoomPromotion) && room.RoomPromotion.length > 0) {
                    roomInfo.push(
                        `<span class="px-2 py-1 bg-orange-50 text-orange-700 text-xs rounded-lg"><i class="fas fa-tag {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> ${room.RoomPromotion[0]}</span>`
                    );
                }

                // Build reservation URL with all required data
                const bookingCode = room.BookingCode || '';
                const totalFare = price.toFixed(2); // Use total price, not per night
                const reservationUrl =
                    `{{ route('reservation') }}?hotel_id={{ $hotelId }}&check_in=${checkIn}&check_out=${checkOut}&guests=${guests}&booking_code=${encodeURIComponent(bookingCode)}&total_fare=${totalFare}&currency=${currency}&room_name=${encodeURIComponent(roomName)}&inclusion=${encodeURIComponent(inclusion)}`;

                div.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="relative h-48 md:h-full min-h-[200px]">
                        <img src="${imageUrl}" alt="${roomName}" class="w-full h-full object-cover rounded-xl">
                    </div>
                    <div class="md:col-span-2">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">${roomName}</h3>
                        ${roomDescription ? `<p class="text-gray-600 text-sm mb-3">${roomDescription}</p>` : ''}
                        ${roomInfo.length > 0 ? `<div class="flex flex-wrap gap-2 mb-3">${roomInfo.join('')}</div>` : ''}
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <div>
                                <div class="flex items-baseline">
                                    <span class="text-3xl font-extrabold text-orange-600">${pricePerNight}</span>
                                    <span class="text-gray-500 text-sm {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">${currency}</span>
                                </div>
                                <div class="text-xs text-gray-400">{{ __('per night') }}</div>
                                ${nights > 1 ? `<div class="text-xs text-gray-500 mt-1">{{ __('Total') }}: ${totalFare} ${currency} {{ __('for') }} ${nights} {{ __('nights') }}</div>` : ''}
                            </div>
                            <a href="${reservationUrl}" 
                               class="bg-gradient-to-r from-orange-600 to-orange-600 text-white px-8 py-3 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition shadow-lg">
                                {{ __('Book Now') }}
                            </a>
                        </div>
                    </div>
                </div>
            `;

                return div;
            } catch (error) {
                console.error('Error creating room card:', error, room);
                return null;
            }
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

        // Auto-search rooms on page load only if we have valid dates
        document.addEventListener('DOMContentLoaded', function() {
            // Hide default rooms immediately
            const roomsList = document.getElementById('roomsList');
            if (roomsList) {
                roomsList.classList.add('hidden');
                roomsList.innerHTML = ''; // Clear default content
            }

            // Wait a bit for dates to be set and inputs to be ready
            setTimeout(() => {
                // Only auto-search if we have valid dates (not empty strings)
                const hasValidDates = checkInInput?.value?.trim() && checkOutInput?.value?.trim();
                if (hasValidDates || (!checkInInput?.value && !checkOutInput?.value)) {
                    // Either we have valid dates from URL, or no dates at all (will use defaults)
                    searchRooms();
                }
            }, 500);

            // Facilities Show More/Less functionality
            const showMoreBtn = document.getElementById('showMoreFacilities');
            const showLessBtn = document.getElementById('showLessFacilities');
            const facilityItems = document.querySelectorAll('.facility-item');

            if (showMoreBtn && showLessBtn) {
                showMoreBtn.addEventListener('click', function() {
                    facilityItems.forEach(item => {
                        item.classList.remove('hidden');
                    });
                    showMoreBtn.classList.add('hidden');
                    showLessBtn.classList.remove('hidden');
                });

                showLessBtn.addEventListener('click', function() {
                    facilityItems.forEach((item, index) => {
                        if (index >= 10) {
                            item.classList.add('hidden');
                        }
                    });
                    showMoreBtn.classList.remove('hidden');
                    showLessBtn.classList.add('hidden');
                });
            }
        });
    </script>
@endpush
