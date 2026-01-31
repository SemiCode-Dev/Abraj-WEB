@extends('Web.layouts.app')

@section('title', __('Available Hotels') . ' - ' . __('Book Hotels - Best Offers and Services'))

@section('content')
    <!-- Page Header -->
    <section class="bg-gradient-to-r from-orange-500 via-orange-600 to-blue-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold mb-2">{{ __('Available Hotels') }}</h1>
                    <p class="text-orange-100">
                        <i class="fas fa-map-marker-alt {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                        {{ request('destination', __('All Destinations')) }}
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- Filters & Results -->
    <section class="py-8 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Sidebar Filters -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 sticky top-24">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">{{ __('Filter Results') }}</h3>

                        <!-- Hotel Name Search -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-200 mb-4">{{ __('Search Hotel') }}</h4>
                            <div class="relative">
                                <input type="text" id="hotelNameFilter" placeholder="{{ __('Search for a hotel...') }}"
                                    class="w-full px-4 py-2 border-2 border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-white dark:bg-gray-700 placeholder-gray-400 dark:placeholder-gray-400 text-sm">
                                <i
                                    class="fas fa-search absolute {{ app()->getLocale() === 'ar' ? 'left-3' : 'right-3' }} top-3 text-gray-400 dark:text-gray-500"></i>
                            </div>
                        </div>

                        <!-- Star Rating -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-200 mb-4">{{ __('Rating') }}</h4>
                            <div class="space-y-3">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" class="filter-rating w-5 h-5 text-orange-600 rounded"
                                        data-rating="5">
                                    <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} flex text-yellow-500">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" class="filter-rating w-5 h-5 text-orange-600 rounded"
                                        data-rating="4">
                                    <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} flex text-yellow-500">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" class="filter-rating w-5 h-5 text-orange-600 rounded"
                                        data-rating="3">
                                    <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} flex text-yellow-500">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Amenities -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-200 mb-4">{{ __('Amenities') }}</h4>
                            <div class="space-y-3">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" class="filter-amenity w-5 h-5 text-orange-600 rounded"
                                        data-amenity="wifi">
                                    <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} text-gray-700 dark:text-gray-300">
                                        <i
                                            class="fas fa-wifi text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                                        {{ __('Free WiFi') }}
                                    </span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" class="filter-amenity w-5 h-5 text-orange-600 rounded"
                                        data-amenity="pool">
                                    <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} text-gray-700 dark:text-gray-300">
                                        <i
                                            class="fas fa-swimming-pool text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                                        {{ __('Pool') }}
                                    </span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" class="filter-amenity w-5 h-5 text-orange-600 rounded"
                                        data-amenity="restaurant">
                                    <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} text-gray-700 dark:text-gray-300">
                                        <i
                                            class="fas fa-utensils text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                                        {{ __('Restaurant') }}
                                    </span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" class="filter-amenity w-5 h-5 text-orange-600 rounded"
                                        data-amenity="spa">
                                    <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} text-gray-700 dark:text-gray-300">
                                        <i
                                            class="fas fa-spa text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                                        {{ __('Spa') }}
                                    </span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" class="filter-amenity w-5 h-5 text-orange-600 rounded"
                                        data-amenity="gym">
                                    <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} text-gray-700 dark:text-gray-300">
                                        <i
                                            class="fas fa-dumbbell text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                                        {{ __('Gym') }}
                                    </span>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Hotels List -->
                <div class="lg:col-span-3">
                    <!-- Sort Bar -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 mb-6 flex items-center justify-between">
                        <div class="text-gray-700 dark:text-gray-300">
                            <span class="font-semibold">{{ __('Found') }}</span>
                            <span class="text-orange-600 dark:text-orange-400 font-bold">{{ count($hotels) ?? 0 }} {{ __('hotels') }}</span>
                        </div>
                        <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-4"
                            onsubmit="return false;">
                            <!-- Maintain existing filters -->
                            @if (request('CheckIn'))
                                <input type="hidden" name="CheckIn" value="{{ request('CheckIn') }}">
                            @endif
                            @if (request('CheckOut'))
                                <input type="hidden" name="CheckOut" value="{{ request('CheckOut') }}">
                            @endif
                            @if (request('PaxRooms'))
                                @foreach (request('PaxRooms', []) as $key => $room)
                                    @foreach ($room as $k => $v)
                                        @if (is_array($v))
                                            @foreach ($v as $ik => $iv)
                                                <input type="hidden"
                                                    name="PaxRooms[{{ $key }}][{{ $k }}][{{ $ik }}]"
                                                    value="{{ $iv }}">
                                            @endforeach
                                        @else
                                            <input type="hidden"
                                                name="PaxRooms[{{ $key }}][{{ $k }}]"
                                                value="{{ $v }}">
                                        @endif
                                    @endforeach
                                @endforeach
                            @endif

                            <span class="text-gray-600 dark:text-gray-300 text-sm">{{ __('Sort by') }}:</span>
                            <select id="sortSelect" onchange="sortHotels(this.value)"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 text-gray-900 dark:text-white dark:bg-gray-700">
                                <option value="">{{ __('Default') }}</option>
                                <option value="price_asc">{{ __('Lowest Price') }}</option>
                                <option value="price_desc">{{ __('Highest Price') }}</option>
                            </select>
                        </form>
                    </div>

                    <!-- Hotels Grid -->
                    <div id="noResultsMessage" class="hidden py-16 text-center bg-white dark:bg-gray-800 rounded-2xl shadow-lg mb-6">
                        <div class="mb-4">
                            <i class="fas fa-hotel text-gray-200 dark:text-gray-700 text-7xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                            {{ __('No hotels available for the selected dates') }}</h3>
                        <p class="text-gray-500 dark:text-gray-400">{{ __('Try adjusting your filters or search terms') }}</p>
                    </div>

                    <div class="space-y-6" id="hotelsGrid">
                        @php $shownHotels = []; @endphp
                        @foreach ($hotels as $hotel)
                            @if (in_array($hotel['HotelCode'], $shownHotels))
                                @continue
                            @endif
                            @php $shownHotels[] = $hotel['HotelCode']; @endphp
                            @php
                                // Extract rating number from HotelRating (can be "FiveStar" or 5)
                                $ratingMap = [
                                    'FiveStar' => 5,
                                    'FourStar' => 4,
                                    'ThreeStar' => 3,
                                    'TwoStar' => 2,
                                    'OneStar' => 1,
                                    '5' => 5,
                                    '4' => 4,
                                    '3' => 3,
                                    '2' => 2,
                                    '1' => 1,
                                ];
                                $rawRating = $hotel['HotelRating'] ?? 0;
                                $ratingNumber =
                                    $ratingMap[$rawRating] ?? (is_numeric($rawRating) ? (int) $rawRating : 0);

                                // Extract facilities and normalize them
                                $facilities = $hotel['HotelFacilities'] ?? [];
                                if (!is_array($facilities)) {
                                    $facilities = [];
                                }
                                $facilitiesLower = array_map('strtolower', $facilities);
                                $facilitiesJson = json_encode($facilitiesLower);
                            @endphp
                            <div class="hotel-card bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300"
                                data-id="{{ $hotel['HotelCode'] }}" data-name="{{ strtolower($hotel['HotelName']) }}"
                                data-rating="{{ $ratingNumber }}" data-facilities='{{ $facilitiesJson }}'
                                data-price="{{ $hotel['MinPrice'] ?? 0 }}"
                                style="{{ $loop->index >= 12 ? 'display: none;' : '' }}">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-0">
                                    <!-- Hotel Image -->
                                    <div class="relative h-64 md:h-full max-h-[250px]">
                                        @php
                                            $hotelImage = null;
                                            if (isset($hotel['ImageUrls']) && is_array($hotel['ImageUrls']) && !empty($hotel['ImageUrls'][0]['ImageUrl'])) {
                                                $hotelImage = $hotel['ImageUrls'][0]['ImageUrl'];
                                            } elseif (isset($hotel['Image']) && !empty($hotel['Image'])) {
                                                $hotelImage = $hotel['Image'];
                                            } elseif (isset($hotel['Images']) && is_array($hotel['Images']) && !empty($hotel['Images'][0])) {
                                                $hotelImage = is_array($hotel['Images'][0]) ? ($hotel['Images'][0]['ImageUrl'] ?? null) : $hotel['Images'][0];
                                            }
                                            $defaultHotelImage = asset('images/default.jpg');
                                        @endphp
                                        <img src="{{ $hotelImage ?? $defaultHotelImage }}"
                                            alt="{{ $hotel['HotelName'] ?? 'فندق' }}" 
                                            class="w-full h-full object-cover"
                                            onerror="this.onerror=null; this.src='{{ $defaultHotelImage }}';">
                                    </div>

                                    <!-- Hotel Info -->
                                    <div class="md:col-span-2 p-6 flex flex-col justify-between">
                                        <div>
                                            <div class="flex items-start justify-between mb-3">
                                                <div>
                                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
                                                        {{ $hotel['HotelName'] }}</h3>
                                                    <p class="text-gray-600 dark:text-gray-300 flex items-center mb-2">
                                                        <i
                                                            class="fas fa-map-marker-alt text-orange-600 dark:text-orange-400 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                                                        {{ $hotel['CityName'] }}, {{ $hotel['CountryName'] }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="flex flex-wrap gap-2 mb-4">
                                                @php
                                                    $fStr = json_encode($facilitiesLower);
                                                @endphp
                                                @if (strpos($fStr, 'wifi') !== false || strpos($fStr, 'internet') !== false || strpos($fStr, 'wireless') !== false)
                                                    <span
                                                        class="px-3 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs rounded-lg font-semibold">
                                                        <i
                                                            class="fas fa-wifi {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                                        {{ __('WiFi') }}
                                                    </span>
                                                @endif
                                                @if (strpos($fStr, 'pool') !== false || strpos($fStr, 'swimming') !== false)
                                                    <span
                                                        class="px-3 py-1 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-xs rounded-lg font-semibold">
                                                        <i
                                                            class="fas fa-swimming-pool {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                                        {{ __('Pool') }}
                                                    </span>
                                                @endif
                                                @if (strpos($fStr, 'restaurant') !== false || strpos($fStr, 'dining') !== false)
                                                    <span
                                                        class="px-3 py-1 bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 text-xs rounded-lg font-semibold">
                                                        <i
                                                            class="fas fa-utensils {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                                        {{ __('Restaurant') }}
                                                    </span>
                                                @endif
                                                @if (strpos($fStr, 'parking') !== false)
                                                    <span
                                                        class="px-3 py-1 bg-orange-50 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 text-xs rounded-lg font-semibold">
                                                        <i
                                                            class="fas fa-parking {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                                        {{ __('Parking') }}
                                                    </span>
                                                @endif
                                                @if (strpos($fStr, 'gym') !== false || strpos($fStr, 'fitness') !== false)
                                                    <span
                                                        class="px-3 py-1 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 text-xs rounded-lg font-semibold">
                                                        <i
                                                            class="fas fa-dumbbell {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                                        {{ __('Gym') }}
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="flex items-center mb-4">
                                                <div class="flex text-yellow-500 dark:text-yellow-400 text-sm ml-2">
                                                    @php
                                                        $stars = [
                                                            'FiveStar' => 5,
                                                            'FourStar' => 4,
                                                            'ThreeStar' => 3,
                                                            'TwoStar' => 2,
                                                            'OneStar' => 1,
                                                            '5' => 5,
                                                            '4' => 4,
                                                            '3' => 3,
                                                            '2' => 2,
                                                            '1' => 1,
                                                        ];
                                                        $rawRating = $hotel['HotelRating'] ?? 5;
                                                        $count =
                                                            $stars[$rawRating] ??
                                                            (is_numeric($rawRating) ? (int) $rawRating : 5);
                                                    @endphp
                                                    @for ($j = 0; $j < $count; $j++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                </div>
                                                {{-- <span class="text-sm text-gray-500 {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">({{ $hotel['ReviewCount'] }} {{ __('reviews') }})</span> --}}
                                                <span class="text-sm text-gray-500 dark:text-gray-400">•</span>
                                                <span
                                                    class="text-sm text-orange-600 dark:text-orange-400 font-semibold {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">{{ __('Free Cancellation') }}</span>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                                            <div class="price-container">
                                                <div class="live-price-wrapper"
                                                    data-hotel-id="{{ $hotel['HotelCode'] }}">
                                                    @php
                                                        $checkIn = request('check_in', request('CheckIn'));
                                                        $checkOut = request('check_out', request('CheckOut'));
                                                        $hasPrice = isset($hotel['MinPrice']) && $hotel['MinPrice'] > 0;
                                                        $currency = $hotel['Currency'] ?? 'USD';
                                                        $hasSearchDates = $checkIn && $checkOut;
                                                        $isSearchResult = $hotel['IsSearchResult'] ?? false;

                                                        $nights = 0;
                                                        if ($hasSearchDates) {
                                                            try {
                                                                $in = \Carbon\Carbon::parse($checkIn);
                                                                $out = \Carbon\Carbon::parse($checkOut);
                                                                $nights = $in->diffInDays($out);
                                                            } catch (\Exception $e) {
                                                            }
                                                        }
                                                    @endphp

                                                    @if ($hasPrice)
                                                        {{-- Show price directly from backend --}}
                                                        <div class="price-content">
                                                            @if ($isSearchResult && $nights > 0)
                                                                <div
                                                                    class="text-[10px] tracking-wider text-gray-400 dark:text-gray-500 font-bold mb-1">
                                                                    {{ __('Total for') }} {{ $nights }}
                                                                    {{ $nights > 1 ? __('nights') : __('night') }}
                                                                </div>
                                                            @endif
                                                            <div class="flex items-baseline">
                                                                <span
                                                                    class="text-3xl font-extrabold text-orange-600 dark:text-orange-400 amount">{{ number_format($hotel['MinPrice'], 2) }}</span>
                                                                <span
                                                                    class="text-gray-500 dark:text-gray-400 text-sm currency {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">{{ $currency }}</span>
                                                            </div>
                                                        </div>
                                                    @else
                                                        @if ($hasSearchDates)
                                                            {{-- Loading Skeleton for AJAX fetch --}}
                                                            <div
                                                                class="price-skeleton h-8 w-max min-w-[100px] bg-gray-100 dark:bg-gray-700 animate-pulse rounded-lg flex items-center px-3">
                                                                <div class="h-4 w-12 bg-gray-200 dark:bg-gray-600 rounded"></div>
                                                                <div class="h-3 w-8 bg-gray-200 dark:bg-gray-600 rounded ml-2"></div>
                                                            </div>
                                                        @else
                                                            {{-- Show the "No available rooms" by default, AJAX will replace it if price is found --}}
                                                            <div
                                                                class="text-xs text-red-500 font-bold italic no-availability-msg">
                                                                {{ app()->getLocale() == 'ar' ? 'لا يوجد غرف متاحة حالياً' : 'No available rooms currently' }}
                                                            </div>
                                                            {{-- Invisible pulse loader so JS knows we are still trying --}}
                                                            <div
                                                                class="price-skeleton h-1 w-8 bg-gray-50 animate-pulse rounded opacity-0">
                                                            </div>
                                                        @endif

                                                        {{-- Live Price Value (Initially Hidden) --}}
                                                        <div class="price-content hidden">
                                                            <div class="flex items-baseline">
                                                                <span
                                                                    class="text-3xl font-extrabold text-orange-600 dark:text-orange-400 amount"></span>
                                                                <span
                                                                    class="text-gray-500 dark:text-gray-400 text-sm currency {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}"></span>
                                                            </div>
                                                        </div>

                                                        {{-- No Availability Message (Initially Hidden) --}}
                                                        <div
                                                            class="no-availability-msg hidden text-red-500 font-bold text-sm">
                                                            {{ __('No rooms available.') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <a href="{{ route('hotel.details', array_merge(['id' => $hotel['HotelCode'] ?? 1, 'locale' => app()->getLocale()], request()->only(['CheckIn', 'CheckOut', 'PaxRooms', 'check_in', 'check_out']))) }}"
                                                class="bg-gradient-to-r from-orange-600 to-orange-600 text-white px-8 py-3 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition shadow-lg">
                                                {{ __('View Rooms') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>


                    <!-- Client-Side Pagination -->
                    <div id="paginationContainer" class="mt-8 flex justify-center">
                        <div class="flex gap-2 items-center flex-wrap justify-center">
                            <!-- Pagination will be generated by JavaScript -->
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Common map for translating rating strings to numbers
        const ratingMap = {
            'FiveStar': 5,
            'FourStar': 4,
            'ThreeStar': 3,
            'TwoStar': 2,
            'OneStar': 1,
            '5': 5,
            '4': 4,
            '3': 3,
            '2': 2,
            '1': 1
        };

        // Pagination Configuration
        const HOTELS_PER_PAGE = 12;
        let currentPage = 1; /* RESTORED: from visibleCount */
        let filteredHotels = [];
        const isRTL = '{{ app()->getLocale() }}' === 'ar';
        const currentLocale = '{{ app()->getLocale() }}';

        // Incremental Loading Configuration
        const remainingCityCodes = @json($remainingCityCodes ?? []);
        const loadMoreRoute = '{{ route('hotels.load-more') }}';
        const csrfToken = '{{ csrf_token() }}';
        let isFetching = false;

        // Live Pricing Configuration
        const minPricesRoute = '{{ route('hotels.min-prices') }}';
        const searchParams = {
            check_in: '{{ request('CheckIn', request('check_in')) }}',
            check_out: '{{ request('CheckOut', request('check_out')) }}',
            pax_rooms: @json(request('PaxRooms', []))
        };
        const fetchedPriceIds = new Set();

        async function fetchLivePrices() {
            if (!searchParams.check_in || !searchParams.check_out) {
                document.querySelectorAll('.price-skeleton').forEach(el => el.classList.add('hidden'));
                return;
            }

            // Collect IDs of visible hotel cards that haven't been fetched yet
            const visibleWrappers = Array.from(document.querySelectorAll('.live-price-wrapper:not(.price-fetched)'))
                .filter(el => {
                    const card = el.closest('.hotel-card');
                    return card && card.style.display !== 'none' && el.querySelector('.price-skeleton') !== null;
                });

            const idsToFetch = visibleWrappers.map(el => el.dataset.hotelId);

            if (idsToFetch.length === 0) return;

            // Mark as fetching to avoid duplicate calls
            idsToFetch.forEach(id => {
                const wrappers = document.querySelectorAll(`.live-price-wrapper[data-hotel-id="${id}"]`);
                wrappers.forEach(w => w.classList.add('price-fetched'));
            });

            try {
                const response = await fetch(minPricesRoute, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        hotel_ids: idsToFetch,
                        CheckIn: searchParams.check_in,
                        CheckOut: searchParams.check_out,
                        PaxRooms: searchParams.pax_rooms
                    })
                });

                if (response.ok) {
                    const data = await response.json();

                    idsToFetch.forEach(id => {
                        const wrappers = document.querySelectorAll(
                            `.live-price-wrapper[data-hotel-id="${id}"]`);
                        const info = data[id];

                        wrappers.forEach(wrapper => {
                            const skeleton = wrapper.querySelector('.price-skeleton');
                            const content = wrapper.querySelector('.price-content');
                            const noAvail = wrapper.querySelector('.no-availability-msg');

                            if (skeleton) skeleton.classList.add('hidden');

                            if (info && info.status === 'available') {
                                if (noAvail) noAvail.classList.add('hidden');
                                if (content) {
                                    content.classList.remove('hidden');
                                    const amountEl = content.querySelector('.amount');
                                    const currencyEl = content.querySelector('.currency');
                                    if (amountEl) amountEl.textContent = info.amount;
                                    if (currencyEl) currencyEl.textContent = info.currency;
                                }
                            } else {
                                if (content) content.classList.add('hidden');
                                if (noAvail) noAvail.classList.remove('hidden');
                            }
                        });
                    });
                }
            } catch (error) {
                console.error('Failed to fetch live prices:', error);
                // Revert "price-fetched" class for retry if needed, or keep for stability
            }
        }


        // Hotel Filtering and Pagination Logic
        function applyFiltersAndPagination(e, keepPage = false) {
            // Get selected ratings
            const selectedRatings = Array.from(document.querySelectorAll('.filter-rating:checked'))
                .map(cb => parseInt(cb.dataset.rating));

            // Get selected amenities
            const selectedAmenities = Array.from(document.querySelectorAll('.filter-amenity:checked'))
                .map(cb => cb.dataset.amenity);

            // Get name filter
            const nameSearch = document.getElementById('hotelNameFilter').value.toLowerCase();

            // Get all hotel cards and deduplicate them by data-id for the filter logic
            const allCards = Array.from(document.querySelectorAll('.hotel-card'));
            const uniqueCards = [];
            const seenIds = new Set();

            allCards.forEach(card => {
                const id = card.dataset.id;
                if (!seenIds.has(id)) {
                    seenIds.add(id);
                    uniqueCards.push(card);
                } else {
                    card.style.display = 'none'; // Hide any extra duplicates that might exist
                }
            });

            // Filter hotels
            filteredHotels = uniqueCards.filter(card => {
                let show = true;

                // Filter by name
                if (nameSearch && !card.dataset.name.includes(nameSearch)) {
                    show = false;
                }

                // Filter by rating
                if (selectedRatings.length > 0) {
                    const hotelRating = parseInt(card.dataset.rating);
                    if (!selectedRatings.includes(hotelRating)) {
                        show = false;
                    }
                }

                // Filter by amenities
                if (selectedAmenities.length > 0 && show) {
                    const hotelFacilities = JSON.parse(card.dataset.facilities || '[]');

                    // Check if hotel has ALL selected amenities
                    const hasAllAmenities = selectedAmenities.every(amenity => {
                        const amenityPatterns = {
                            'wifi': ['wifi', 'wi-fi', 'internet', 'wireless'],
                            'pool': ['pool', 'swimming', 'piscine'],
                            'restaurant': ['restaurant', 'dining', 'food'],
                            'spa': ['spa', 'massage', 'wellness'],
                            'gym': ['gym', 'fitness', 'exercise', 'workout']
                        };

                        const patterns = amenityPatterns[amenity] || [amenity];
                        return hotelFacilities.some(facility =>
                            patterns.some(pattern => facility.includes(pattern))
                        );
                    });

                    if (!hasAllAmenities) {
                        show = false;
                    }
                }

                return show;
            });

            // Reset pagination
            if (!keepPage) {
                currentPage = 1;
            } else {
                // Ensure currentPage is still valid
                const totalFiltered = filteredHotels.length;
                const maxPage = Math.ceil(totalFiltered / HOTELS_PER_PAGE) || 1;
                if (currentPage > maxPage) {
                    currentPage = maxPage;
                }
            }

            // Update display
            updateHotelDisplay();
            updatePagination();
            updateHotelCount();
        }

        function updateHotelDisplay() {
            const allHotelCards = document.querySelectorAll('.hotel-card');
            const startIndex = (currentPage - 1) * HOTELS_PER_PAGE;
            const endIndex = startIndex + HOTELS_PER_PAGE;

            allHotelCards.forEach(card => card.style.display = 'none');

            // Show only hotels for current page
            filteredHotels.forEach((card, index) => {
                if (index >= startIndex && index < endIndex) {
                    card.style.display = '';
                }
            });

            // Trigger live price fetching for the newly visible hotels
            fetchLivePrices();
        }

        function updatePagination() {
            const totalPages = Math.ceil(filteredHotels.length / HOTELS_PER_PAGE);
            const paginationContainer = document.querySelector('#paginationContainer > div');
            if (!paginationContainer) return;
            paginationContainer.innerHTML = '';

            // Single row preferred, but allow wrap if truly needed to avoid hiding.
            // Gap-1 and px-1 keep it tight.
            paginationContainer.className = 'flex gap-1 items-center justify-center flex-wrap max-w-full px-1';

            if (totalPages <= 1) return;

            // Common class for compact buttons
            const commonClasses =
                'px-2 py-1 md:px-3 md:py-1.5 border rounded-lg transition flex items-center whitespace-nowrap text-xs md:text-sm font-semibold shadow-sm';

            // Previous Button
            const prevBtn = document.createElement('button');
            prevBtn.className = currentPage === 1 ?
                `${commonClasses} bg-gray-100 border-gray-300 text-gray-400 cursor-not-allowed` :
                `${commonClasses} bg-white border-gray-300 text-gray-900 hover:bg-orange-50 hover:border-orange-600`;
            prevBtn.disabled = currentPage === 1;
            prevBtn.innerHTML = isRTL ?
                `{{ __('Previous') }} <i class="fas fa-chevron-right ml-1"></i>` :
                `<i class="fas fa-chevron-left mr-1"></i> {{ __('Previous') }}`;
            prevBtn.onclick = () => {
                if (currentPage > 1) {
                    currentPage--;
                    updateHotelDisplay();
                    updatePagination();
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
            };
            paginationContainer.appendChild(prevBtn);

            // Page Numbers
            const startPage = Math.max(1, currentPage - 2);
            const endPage = Math.min(totalPages, currentPage + 2);

            const createPageBtn = (num) => {
                const btn = document.createElement('button');
                btn.textContent = num;
                btn.className = num === currentPage ?
                    'px-2 py-1 md:px-3 md:py-1.5 bg-orange-600 text-white rounded-lg font-semibold text-xs md:text-sm' :
                    'px-2 py-1 md:px-3 md:py-1.5 bg-white border border-gray-300 rounded-lg text-gray-900 hover:bg-orange-50 hover:border-orange-600 transition text-xs md:text-sm';
                btn.onclick = () => {
                    currentPage = num;
                    updateHotelDisplay();
                    updatePagination();
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                };
                return btn;
            };

            if (startPage > 1) {
                paginationContainer.appendChild(createPageBtn(1));
                if (startPage > 2) {
                    const dots = document.createElement('span');
                    dots.className = 'px-0.5 text-gray-500 text-xs';
                    dots.textContent = '...';
                    paginationContainer.appendChild(dots);
                }
            }
            for (let i = startPage; i <= endPage; i++) paginationContainer.appendChild(createPageBtn(i));
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    const dots = document.createElement('span');
                    dots.className = 'px-0.5 text-gray-500 text-xs';
                    dots.textContent = '...';
                    paginationContainer.appendChild(dots);
                }
                paginationContainer.appendChild(createPageBtn(totalPages));
            }

            // Next Button
            const nextBtn = document.createElement('button');
            nextBtn.className = currentPage === totalPages ?
                `${commonClasses} bg-gray-100 border-gray-300 text-gray-400 cursor-not-allowed` :
                `${commonClasses} bg-white border-gray-300 text-gray-900 hover:bg-orange-50 hover:border-orange-600`;
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.innerHTML = isRTL ?
                `<i class="fas fa-chevron-left ml-1"></i> {{ __('Next') }}` :
                `{{ __('Next') }} <i class="fas fa-chevron-right ml-1"></i>`;
            nextBtn.onclick = () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    updateHotelDisplay();
                    updatePagination();
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
            };
            paginationContainer.appendChild(nextBtn);
        }

        function updateHotelCount() {
            const countElement = document.querySelector('.text-orange-600.font-bold');
            if (countElement) countElement.textContent = filteredHotels.length + ' {{ __('hotels') }}';

            const noResultsMessage = document.getElementById('noResultsMessage');
            const hotelsGrid = document.getElementById('hotelsGrid');
            const paginationContainer = document.getElementById('paginationContainer');

            if (filteredHotels.length === 0) {
                if (noResultsMessage) noResultsMessage.classList.remove('hidden');
                if (hotelsGrid) hotelsGrid.classList.add('hidden');
                if (paginationContainer) paginationContainer.classList.add('hidden');
            } else {
                if (noResultsMessage) noResultsMessage.classList.add('hidden');
                if (hotelsGrid) hotelsGrid.classList.remove('hidden');
                if (paginationContainer) paginationContainer.classList.remove('hidden');
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            applyFiltersAndPagination();

            // Apply filter when name changes
            const nameInput = document.getElementById('hotelNameFilter');
            if (nameInput) {
                nameInput.addEventListener('input', applyFiltersAndPagination);
            }

            // Apply filters when checkboxes change
            document.querySelectorAll('.filter-rating, .filter-amenity').forEach(checkbox => {
                checkbox.addEventListener('change', (e) => applyFiltersAndPagination(e, false));
            });
        });

        // Background Fetching Logic
        async function fetchNextBatch() {
            if (remainingCityCodes.length === 0) return;
            if (isFetching) return;

            isFetching = true;
            // Take next batch (e.g. 20 cities)
            const batchSize = 20;
            const batchCodes = remainingCityCodes.splice(0, batchSize);

            try {
                // Update UI state
                const btn = document.getElementById('loadMoreBtn');
                const status = document.getElementById('loadMoreStatus');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML =
                        `<i class="fas fa-spinner fa-spin {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i> {{ __('Loading...') }}`;
                    btn.classList.add('opacity-75');
                }

                const response = await fetch(loadMoreRoute, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        cityCodes: batchCodes
                    })
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.hotels && data.hotels.length > 0) {
                        appendHotels(data.hotels);
                        // Re-run filters but KEEP current page
                        applyFiltersAndPagination(null, true);

                        if (status) {
                            status.textContent = `{{ __('Loaded') }} ${data.hotels.length} {{ __('more hotels') }}`;
                            setTimeout(() => status.textContent = '', 4000);
                        }
                    } else {
                        // NO MORE HOTELS returned from server for this batch
                        if (status) status.textContent = `{{ __('No more hotels found') }}`;
                        // Remove button if server has no more data
                        const btn = document.getElementById('loadMoreBtn');
                        if (btn) btn.remove();
                    }
                }
            } catch (error) {
                console.error('Failed to load background batch', error);
                const status = document.getElementById('loadMoreStatus');
                if (status) status.textContent = `{{ __('Failed to load. Please try again.') }}`;
                // Put codes back to retry
                remainingCityCodes.unshift(...batchCodes);
            } finally {
                isFetching = false;
                // UI automatically recovers via applyFiltersAndPagination()
                // But explicitly call updatePagination to ensure button removal is reflected if remainingCityCodes hit 0
                updatePagination();
            }
        }

        function appendHotels(hotels) {
            const grid = document.getElementById('hotelsGrid');
            if (!grid) return;

            // Get existing IDs to prevent duplicates
            const existingIds = new Set(Array.from(grid.querySelectorAll('.hotel-card')).map(card => card.dataset.id));

            // Generate HTML only for hotels that aren't already in the grid
            const newHotels = hotels.filter(h => !existingIds.has(String(h.HotelCode)));
            if (newHotels.length === 0) return;

            const newCardsHTML = newHotels.map(hotel => createHotelCardHTML(hotel)).join('');

            // insertAdjacentHTML is faster than reflowing specific elements
            grid.insertAdjacentHTML('beforeend', newCardsHTML);
        }

        function createHotelCardHTML(hotel) {
            const isAr = '{{ app()->getLocale() }}' === 'ar';
            return `
            <div class="hotel-card bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300"
                data-id="${hotel.HotelCode}"
                data-name="${hotel.HotelName.toLowerCase()}"
                data-rating="${ratingMap[hotel.HotelRating] || (parseInt(hotel.HotelRating) || 5)}"
                data-price="${hotel.MinPrice || 0}"
                data-facilities='${JSON.stringify((hotel.HotelFacilities || []).map(f => f.toLowerCase())).replace(/'/g, "&apos;")}'>
            ` + createHotelCardInner(hotel);
        }

        function createHotelCardInner(hotel) {
            const isAr = '{{ app()->getLocale() }}' === 'ar';

            const rawRating = hotel.HotelRating || 5;
            const ratingNumber = ratingMap[rawRating] || (parseInt(rawRating) || 5);

            // Helper for stars
            let starsHtml = '';
            for (let i = 0; i < ratingNumber; i++) {
                starsHtml += '<i class="fas fa-star"></i>';
            }

            // Facilities
            const facilities = hotel.HotelFacilities || [];
            const facilitiesLower = facilities.map(f => f.toLowerCase());
            const facilitiesJson = JSON.stringify(facilitiesLower).replace(/'/g, "&apos;"); // escape quotes

            // Image
            // We use loading="lazy" for appended images
            const defaultHotelImage = '{{ asset('images/default.jpg') }}';
            let imageUrl = defaultHotelImage;
            if (hotel.ImageUrls && hotel.ImageUrls[0] && hotel.ImageUrls[0].ImageUrl) {
                imageUrl = hotel.ImageUrls[0].ImageUrl;
            } else if (hotel.Image && hotel.Image) {
                imageUrl = hotel.Image;
            } else if (hotel.Images && hotel.Images[0]) {
                imageUrl = typeof hotel.Images[0] === 'string' ? hotel.Images[0] : (hotel.Images[0].ImageUrl || defaultHotelImage);
            }

            // Min Price
            const price = hotel.MinPrice || hotel.StartPrice || '';
            const currency = hotel.Currency || 'USD';

            // Strings
            const wifiText = '{{ __('WiFi') }}';
            const poolText = '{{ __('Pool') }}';
            const restaurantText = '{{ __('Restaurant') }}';
            const parkingText = '{{ __('Parking') }}';
            const viewRoomsText = '{{ __('View Rooms') }}';
            const freeCancelText = '{{ __('Free Cancellation') }}';


            // Dynamic badges based on facilities (simplified check like in blade)
            // Replicating Blade HTML structure exactly


            return `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-0">
                    <!-- Hotel Image -->
                    <div class="relative h-64 md:h-full max-h-[250px]">
                        <img src="${imageUrl}" alt="Hotel" loading="lazy" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='${defaultHotelImage}';">
                    </div>

                    <!-- Hotel Info -->
                    <div class="md:col-span-2 p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900 mb-1">${hotel.HotelName}</h3>
                                    <p class="text-gray-600 flex items-center mb-2">
                                        <i class="fas fa-map-marker-alt text-orange-600 ${isAr ? 'ml-2' : 'mr-2'}"></i>
                                        ${hotel.CityName || ''}, ${hotel.CountryName || ''}
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2 mb-4">
                                ${facilitiesJson.includes('wifi') || facilitiesJson.includes('internet') || facilitiesJson.includes('wireless') ? `
                                        <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg font-semibold">
                                            <i class="fas fa-wifi ${isAr ? 'ml-1' : 'mr-1'}"></i> ${wifiText}
                                        </span>` : ''}

                                ${facilitiesJson.includes('pool') || facilitiesJson.includes('swimming') ? `
                                        <span class="px-3 py-1 bg-green-50 text-green-700 text-xs rounded-lg font-semibold">
                                            <i class="fas fa-swimming-pool ${isAr ? 'ml-1' : 'mr-1'}"></i> ${poolText}
                                        </span>` : ''}

                                ${facilitiesJson.includes('restaurant') || facilitiesJson.includes('dining') ? `
                                        <span class="px-3 py-1 bg-purple-50 text-purple-700 text-xs rounded-lg font-semibold">
                                            <i class="fas fa-utensils ${isAr ? 'ml-1' : 'mr-1'}"></i> ${restaurantText}
                                        </span>` : ''}

                                ${facilitiesJson.includes('parking') ? `
                                        <span class="px-3 py-1 bg-orange-50 text-orange-700 text-xs rounded-lg font-semibold">
                                            <i class="fas fa-parking ${isAr ? 'ml-1' : 'mr-1'}"></i> ${parkingText}
                                        </span>` : ''}
                            </div>

                            <div class="flex items-center mb-4">
                                <div class="flex text-yellow-500 text-sm ml-2">
                                    ${starsHtml}
                                </div>
                                <span class="text-sm text-gray-500">•</span>
                                <span class="text-sm text-orange-600 font-semibold ${isAr ? 'mr-2' : 'ml-2'}">${freeCancelText}</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <div class="price-container">
                                <div class="live-price-wrapper" data-hotel-id="${hotel.HotelCode || hotel.Code}">
                                    <div class="price-skeleton h-8 w-max min-w-[100px] bg-gray-100 animate-pulse rounded-lg flex items-center px-3">
                                        <div class="h-4 w-12 bg-gray-200 rounded"></div>
                                        <div class="h-3 w-8 bg-gray-200 rounded ml-2"></div>
                                    </div>
                                    <div class="price-content hidden">
                                        <div class="flex items-baseline">
                                            <span class="text-3xl font-extrabold text-orange-600 amount"></span>
                                            <span class="text-gray-500 text-sm currency ${isAr ? 'mr-2' : 'ml-2'}"></span>
                                        </div>
                                    </div>
                                    <div class="no-availability-msg hidden text-red-500 font-bold text-sm">
                                        {{ __('No rooms available.') }}
                                    </div>
                                </div>
                            </div>
                             <a href="/${currentLocale}/hotel/${hotel.HotelCode || hotel.Code}${window.location.search}"
                                class="bg-gradient-to-r from-orange-600 to-orange-600 text-white px-8 py-3 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition shadow-lg">
                                ${viewRoomsText}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            `;
        }

        function sortHotels(order) {
            const grid = document.getElementById('hotelsGrid');
            if (!grid) return;

            const cards = Array.from(grid.querySelectorAll('.hotel-card'));

            if (order === 'price_asc') {
                cards.sort((a, b) => parseFloat(a.dataset.price) - parseFloat(b.dataset.price));
            } else if (order === 'price_desc') {
                cards.sort((a, b) => parseFloat(b.dataset.price) - parseFloat(a.dataset.price));
            } else {
                // Default order (usually ID or random, tough to revert without index)
                // We'll leave as is or basic sort
                return;
            }

            // Re-append to grid
            cards.forEach(card => grid.appendChild(card));

            // Re-apply pagination logic (Show first 12, hide rest)
            applyFiltersAndPagination(null, true);
        }
    </script>
@endpush
