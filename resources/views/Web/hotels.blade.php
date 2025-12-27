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
                <div class="hidden md:block bg-white/20 backdrop-blur-sm px-6 py-4 rounded-xl">
                    <div class="text-sm text-orange-100 mb-1">{{ __('Stay Date') }}</div>
                    <div class="font-bold">
                        {{ request('check_in', '--') }} → {{ request('check_out', '--') }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filters & Results -->
    <section class="py-8 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Sidebar Filters -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">{{ __('Filter Results') }}</h3>

                        <!-- Date Selection -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-900 mb-4">{{ __('Select Dates') }}</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-gray-700 text-sm font-medium mb-2">
                                        <i
                                            class="fas fa-calendar-alt text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                        {{ __('Check In') }}
                                    </label>
                                    <input type="date" id="filterCheckIn" name="check_in"
                                        value="{{ request('check_in') }}"
                                        class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 text-sm">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-medium mb-2">
                                        <i
                                            class="fas fa-calendar-check text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                        {{ __('Check Out') }}
                                    </label>
                                    <input type="date" id="filterCheckOut" name="check_out"
                                        value="{{ request('check_out') }}"
                                        class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 text-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-6 opacity-50">
                            <h4 class="font-semibold text-gray-900 mb-4">{{ __('Price Range') }}</h4>
                            <div class="space-y-3">
                                <label class="flex items-center cursor-not-allowed">
                                    <input type="checkbox" class="w-5 h-5 text-orange-600 rounded" disabled>
                                    <span
                                        class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} text-gray-700">{{ __('Less than 200 SAR') }}</span>
                                </label>
                                <label class="flex items-center cursor-not-allowed">
                                    <input type="checkbox" class="w-5 h-5 text-orange-600 rounded" disabled>
                                    <span
                                        class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} text-gray-700">{{ __('200 - 400 SAR') }}</span>
                                </label>
                                <label class="flex items-center cursor-not-allowed">
                                    <input type="checkbox" class="w-5 h-5 text-orange-600 rounded" disabled>
                                    <span
                                        class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} text-gray-700">{{ __('400 - 600 SAR') }}</span>
                                </label>
                                <label class="flex items-center cursor-not-allowed">
                                    <input type="checkbox" class="w-5 h-5 text-orange-600 rounded" disabled>
                                    <span
                                        class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} text-gray-700">{{ __('More than 600 SAR') }}</span>
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">{{ __('Available after room search') }}</p>
                        </div>

                        <!-- Star Rating -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-900 mb-4">{{ __('Rating') }}</h4>
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
                            <h4 class="font-semibold text-gray-900 mb-4">{{ __('Amenities') }}</h4>
                            <div class="space-y-3">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" class="filter-amenity w-5 h-5 text-orange-600 rounded"
                                        data-amenity="wifi">
                                    <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} text-gray-700">
                                        <i
                                            class="fas fa-wifi text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                                        {{ __('Free WiFi') }}
                                    </span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" class="filter-amenity w-5 h-5 text-orange-600 rounded"
                                        data-amenity="pool">
                                    <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} text-gray-700">
                                        <i
                                            class="fas fa-swimming-pool text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                                        {{ __('Pool') }}
                                    </span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" class="filter-amenity w-5 h-5 text-orange-600 rounded"
                                        data-amenity="restaurant">
                                    <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} text-gray-700">
                                        <i
                                            class="fas fa-utensils text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                                        {{ __('Restaurant') }}
                                    </span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" class="filter-amenity w-5 h-5 text-orange-600 rounded"
                                        data-amenity="spa">
                                    <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} text-gray-700">
                                        <i
                                            class="fas fa-spa text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                                        {{ __('Spa') }}
                                    </span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" class="filter-amenity w-5 h-5 text-orange-600 rounded"
                                        data-amenity="gym">
                                    <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} text-gray-700">
                                        <i
                                            class="fas fa-dumbbell text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                                        {{ __('Gym') }}
                                    </span>
                                </label>
                            </div>
                        </div>

                        <button type="button" id="applyFiltersBtn"
                            class="w-full bg-orange-600 text-white py-3 rounded-xl font-semibold hover:bg-orange-700 transition">
                            {{ __('Apply Filter') }}
                        </button>
                    </div>
                </div>

                <!-- Hotels List -->
                <div class="lg:col-span-3">
                    <!-- Sort Bar -->
                    <div class="bg-white rounded-xl shadow-md p-4 mb-6 flex items-center justify-between">
                        <div class="text-gray-700">
                            <span class="font-semibold">{{ __('Found') }}</span>
                            <span class="text-orange-600 font-bold">{{ count($hotels) ?? 0 }} {{ __('hotels') }}</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-gray-600 text-sm">{{ __('Sort by') }}:</span>
                            <select
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 text-gray-900">
                                <option>{{ __('Most Popular') }}</option>
                                <option>{{ __('Lowest Price') }}</option>
                                <option>{{ __('Highest Price') }}</option>
                                <option>{{ __('Highest Rating') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Hotels Grid -->
                    <div class="space-y-6">
                        @foreach ($hotels as $hotel)
                            @php
                                // Extract rating number from HotelRating (e.g., "FiveStar" => 5)
                                $ratingMap = [
                                    'FiveStar' => 5,
                                    'FourStar' => 4,
                                    'ThreeStar' => 3,
                                    'TwoStar' => 2,
                                    'OneStar' => 1,
                                ];
                                $ratingNumber = $ratingMap[$hotel['HotelRating']] ?? 0;

                                // Extract facilities and normalize them
                                $facilities = $hotel['HotelFacilities'] ?? [];
                                $facilitiesLower = array_map('strtolower', $facilities);
                                $facilitiesJson = json_encode($facilitiesLower);
                            @endphp
                            <div class="hotel-card bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300"
                                data-rating="{{ $ratingNumber }}" data-facilities='{{ $facilitiesJson }}'>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-0">
                                    <!-- Hotel Image -->
                                    <div class="relative h-64 md:h-full max-h-[250px]">
                                        <img src="{{ $hotel['ImageUrls'][0]['ImageUrl'] ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}"
                                            alt="فندق" class="w-full h-full object-cover">
                                    </div>

                                    <!-- Hotel Info -->
                                    <div class="md:col-span-2 p-6 flex flex-col justify-between">
                                        <div>
                                            <div class="flex items-start justify-between mb-3">
                                                <div>
                                                    <h3 class="text-2xl font-bold text-gray-900 mb-1">
                                                        {{ $hotel['HotelName'] }}</h3>
                                                    <p class="text-gray-600 flex items-center mb-2">
                                                        <i
                                                            class="fas fa-map-marker-alt text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                                                        {{ $hotel['CityName'] }}, {{ $hotel['CountryName'] }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="flex flex-wrap gap-2 mb-4">
                                                <span
                                                    class="px-3 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg font-semibold">
                                                    <i
                                                        class="fas fa-wifi {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                                    {{ __('WiFi') }}
                                                </span>
                                                <span
                                                    class="px-3 py-1 bg-green-50 text-green-700 text-xs rounded-lg font-semibold">
                                                    <i
                                                        class="fas fa-swimming-pool {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                                    {{ __('Pool') }}
                                                </span>
                                                <span
                                                    class="px-3 py-1 bg-purple-50 text-purple-700 text-xs rounded-lg font-semibold">
                                                    <i
                                                        class="fas fa-utensils {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                                    {{ __('Restaurant') }}
                                                </span>
                                                <span
                                                    class="px-3 py-1 bg-orange-50 text-orange-700 text-xs rounded-lg font-semibold">
                                                    <i
                                                        class="fas fa-parking {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                                    {{ __('Parking') }}
                                                </span>
                                            </div>

                                            <div class="flex items-center mb-4">
                                                <div class="flex text-yellow-500 text-sm ml-2">
                                                    @php
                                                        $stars = [
                                                            'FiveStar' => 5,
                                                            'FourStar' => 4,
                                                            'ThreeStar' => 3,
                                                            'TwoStar' => 2,
                                                            'OneStar' => 1,
                                                        ];
                                                        $count = $stars[$hotel['HotelRating']] ?? 5;
                                                    @endphp
                                                    @for ($j = 0; $j < $count; $j++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                </div>
                                                {{-- <span class="text-sm text-gray-500 {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">({{ $hotel['ReviewCount'] }} {{ __('reviews') }})</span> --}}
                                                <span class="text-sm text-gray-500">•</span>
                                                <span
                                                    class="text-sm text-orange-600 font-semibold {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">{{ __('Free Cancellation') }}</span>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                            <div>
                                                <div class="flex items-baseline">
                                                    <span class="text-3xl font-extrabold text-orange-600"></span>
                                                    <span
                                                        class="text-gray-500 text-sm {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}"></span>
                                                </div>
                                                {{-- <div class="text-xs text-gray-400">/ {{ __('per night') }} • {{ __('including taxes') }}</div> --}}
                                            </div>
                                            <a href="{{ route('hotel.details', ['id' => $hotel['HotelCode'] ?? ($hotel['HotelCode'] ?? 1), 'locale' => app()->getLocale()]) }}?check_in={{ request('check_in') }}&check_out={{ request('check_out') }}&guests={{ request('guests') }}"
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
        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('filterCheckIn')?.setAttribute('min', today);
        document.getElementById('filterCheckOut')?.setAttribute('min', today);

        // Update check-out minimum date when check-in changes
        document.getElementById('filterCheckIn')?.addEventListener('change', function() {
            const checkInDate = this.value;
            const checkOutInput = document.getElementById('filterCheckOut');
            if (checkOutInput && checkInDate) {
                const nextDay = new Date(checkInDate);
                nextDay.setDate(nextDay.getDate() + 1);
                checkOutInput.setAttribute('min', nextDay.toISOString().split('T')[0]);
                if (checkOutInput.value && checkOutInput.value <= checkInDate) {
                    checkOutInput.value = nextDay.toISOString().split('T')[0];
                }
            }
        });

        // Pagination Configuration
        const HOTELS_PER_PAGE = 12;
        let currentPage = 1;
        let filteredHotels = [];

        // Hotel Filtering and Pagination Logic
        function applyFiltersAndPagination() {
            // Get selected ratings
            const selectedRatings = Array.from(document.querySelectorAll('.filter-rating:checked'))
                .map(cb => parseInt(cb.dataset.rating));

            // Get selected amenities
            const selectedAmenities = Array.from(document.querySelectorAll('.filter-amenity:checked'))
                .map(cb => cb.dataset.amenity);

            // Get all hotel cards
            const allHotelCards = Array.from(document.querySelectorAll('.hotel-card'));

            // Filter hotels
            filteredHotels = allHotelCards.filter(card => {
                let show = true;

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
                        // Check for various possible names for each amenity
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

            // Reset to page 1 when filters change
            currentPage = 1;

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
            filteredHotels.forEach((card, index) => {
                if (index >= startIndex && index < endIndex) card.style.display = '';
            });
        }

        function updatePagination() {
            const totalPages = Math.ceil(filteredHotels.length / HOTELS_PER_PAGE);
            const paginationContainer = document.querySelector('#paginationContainer > div');
            if (!paginationContainer) return;
            paginationContainer.innerHTML = '';
            if (totalPages <= 1) return;

            const isRTL = '{{ app()->getLocale() }}' === 'ar';
            const prevBtn = document.createElement('button');
            prevBtn.className = currentPage === 1 ?
                'px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed flex items-center' :
                'px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-900 hover:bg-orange-50 hover:border-orange-600 transition flex items-center';
            prevBtn.disabled = currentPage === 1;
            prevBtn.innerHTML =
                `<i class="fas fa-chevron-${isRTL ? 'right' : 'left'} ${isRTL ? 'ml-1' : 'mr-1'}"></i> {{ __('Previous') }}`;
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

            const startPage = Math.max(1, currentPage - 2);
            const endPage = Math.min(totalPages, currentPage + 2);
            if (startPage > 1) {
                paginationContainer.appendChild(createPageButton(1));
                if (startPage > 2) {
                    const dots = document.createElement('span');
                    dots.className = 'px-2 text-gray-500';
                    dots.textContent = '...';
                    paginationContainer.appendChild(dots);
                }
            }
            for (let i = startPage; i <= endPage; i++) paginationContainer.appendChild(createPageButton(i));
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    const dots = document.createElement('span');
                    dots.className = 'px-2 text-gray-500';
                    dots.textContent = '...';
                    paginationContainer.appendChild(dots);
                }
                paginationContainer.appendChild(createPageButton(totalPages));
            }

            const nextBtn = document.createElement('button');
            nextBtn.className = currentPage === totalPages ?
                'px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed flex items-center' :
                'px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-900 hover:bg-orange-50 hover:border-orange-600 transition flex items-center';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.innerHTML =
                `{{ __('Next') }} <i class="fas fa-chevron-${isRTL ? 'left' : 'right'} ${isRTL ? 'mr-1' : 'ml-1'}"></i>`;
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

        function createPageButton(pageNum) {
            const btn = document.createElement('button');
            btn.className = pageNum === currentPage ? 'px-4 py-2 bg-orange-600 text-white rounded-lg font-semibold' :
                'px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-900 hover:bg-orange-50 hover:border-orange-600 transition';
            btn.textContent = pageNum;
            btn.onclick = () => {
                currentPage = pageNum;
                updateHotelDisplay();
                updatePagination();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            };
            return btn;
        }

        function updateHotelCount() {
            const countElement = document.querySelector('.text-orange-600.font-bold');
            if (countElement) countElement.textContent = filteredHotels.length + ' {{ __('hotels') }}';
        }

        // Apply filters when checkboxes change
        document.querySelectorAll('.filter-rating, .filter-amenity').forEach(checkbox => {
            checkbox.addEventListener('change', applyFiltersAndPagination);
        });

        // Apply filters button (for date changes)
        document.getElementById('applyFiltersBtn')?.addEventListener('click', function() {
            const checkIn = document.getElementById('filterCheckIn').value;
            const checkOut = document.getElementById('filterCheckOut').value;
            const currentUrl = new URL(window.location.href);

            // Update URL parameters
            if (checkIn) {
                currentUrl.searchParams.set('check_in', checkIn);
            } else {
                currentUrl.searchParams.delete('check_in');
            }

            if (checkOut) {
                currentUrl.searchParams.set('check_out', checkOut);
            } else {
                currentUrl.searchParams.delete('check_out');
            }

            // Reload page with new parameters
            window.location.href = currentUrl.toString();
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            applyFiltersAndPagination();
        });
    </script>
@endpush
