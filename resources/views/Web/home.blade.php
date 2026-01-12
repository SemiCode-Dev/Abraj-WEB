@extends('Web.layouts.app')

@section('title', __('Book Hotels - Best Offers and Services'))

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

        /* Ensure disabled/readonly inputs still show correct color */
        input.force-input-text:disabled,
        input.force-input-text[readonly] {
            color: #111827 !important;
            opacity: 1;
            /* Fix for some browsers dimming text */
        }

        .dark input.force-input-text:disabled,
        .dark input.force-input-text[readonly] {
            color: #ffffff !important;
        }

        /* Hide scrollbar for Chrome, Safari and Opera */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        .scrollbar-hide {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }
    </style>

    <section id="home"
        class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white py-16 md:py-24 overflow-hidden min-h-[600px] md:min-h-[700px]">

        <!-- Hero Image Slider Background -->
        <div class="absolute inset-0 hero-slider-container" role="region" aria-label="{{ __('Hero Image Slider') }}">
            <div class="hero-slider-wrapper">
                <!-- Slide 1 -->
                <div class="hero-slide active" data-slide="0" aria-label="{{ __('Slide') }} 1">
                    <img src="{{ asset('images/banners/banner1.jpg') }}" alt="{{ __('Luxury Hotel') }}"
                        class="hero-slide-image" loading="eager">
                </div>
                <!-- Slide 2 -->
                <div class="hero-slide" data-slide="1" aria-label="{{ __('Slide') }} 2">
                    <img src="{{ asset('images/banners/banner2.jpg') }}" alt="{{ __('Modern Hotel Room') }}"
                        class="hero-slide-image" loading="lazy">
                </div>
                <!-- Slide 3 -->
                <div class="hero-slide" data-slide="2" aria-label="{{ __('Slide') }} 3">
                    <img src="{{ asset('images/banners/banner3.jpg') }}" alt="{{ __('Hotel Pool') }}"
                        class="hero-slide-image" loading="lazy">
                </div>
                <!-- Slide 4 -->
                <div class="hero-slide" data-slide="3" aria-label="{{ __('Slide') }} 4">
                    <img src="{{ asset('images/banners/banner4.jpg') }}" alt="{{ __('Hotel Lobby') }}"
                        class="hero-slide-image" loading="lazy">
                </div>
            </div>
            <!-- Overlay -->
            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/60 z-10"></div>

            <!-- Slider Navigation Arrows - Centered Vertically -->
            <button id="hero-slider-prev"
                class="absolute {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }} z-30 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white p-3 rounded-full transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-white/50 active:scale-95"
                style="top: 50%; transform: translateY(-50%);" aria-label="{{ __('Previous slide') }}" type="button">
                <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xl"></i>
            </button>
            <button id="hero-slider-next"
                class="absolute {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} z-30 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white p-3 rounded-full transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-white/50 active:scale-95"
                style="top: 50%; transform: translateY(-50%);" aria-label="{{ __('Next slide') }}" type="button">
                <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xl"></i>
            </button>

            <!-- Slider Dots Indicator - Bottom Center -->
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-30 flex gap-2 justify-center items-center"
                role="tablist" aria-label="{{ __('Slide indicators') }}">
                <button
                    class="hero-slider-dot active w-2.5 h-2.5 rounded-full bg-white transition-all duration-300 hover:scale-125 focus:outline-none focus:ring-2 focus:ring-white/50"
                    data-slide="0" role="tab" aria-selected="true" aria-label="{{ __('Go to slide') }} 1"
                    type="button"></button>
                <button
                    class="hero-slider-dot w-2.5 h-2.5 rounded-full bg-white/50 hover:bg-white/75 transition-all duration-300 hover:scale-125 focus:outline-none focus:ring-2 focus:ring-white/50"
                    data-slide="1" role="tab" aria-selected="false" aria-label="{{ __('Go to slide') }} 2"
                    type="button"></button>
                <button
                    class="hero-slider-dot w-2.5 h-2.5 rounded-full bg-white/50 hover:bg-white/75 transition-all duration-300 hover:scale-125 focus:outline-none focus:ring-2 focus:ring-white/50"
                    data-slide="2" role="tab" aria-selected="false" aria-label="{{ __('Go to slide') }} 3"
                    type="button"></button>
                <button
                    class="hero-slider-dot w-2.5 h-2.5 rounded-full bg-white/50 hover:bg-white/75 transition-all duration-300 hover:scale-125 focus:outline-none focus:ring-2 focus:ring-white/50"
                    data-slide="3" role="tab" aria-selected="false" aria-label="{{ __('Go to slide') }} 4"
                    type="button"></button>
            </div>
        </div>

        <!-- Floating Elements -->
        <div
            class="absolute top-20 right-20 w-72 h-72 bg-orange-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob z-5">
        </div>
        <div
            class="absolute bottom-20 left-20 w-72 h-72 bg-blue-900 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000 z-5">
        </div>

        <!-- Content Layer (Text and Search) -->
        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h1
                    class="text-5xl md:text-7xl p-2 font-extrabold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-white via-blue-100 to-cyan-200">
                    {{ __('Your Perfect Stay Starts Here') }}
                </h1>
                <p class="text-xl md:text-2xl text-slate-300 mb-2">{{ __('Best Hotels and Selected Offers') }}</p>
                <p class="text-slate-400">{{ __('Save up to 40% on Your Booking') }}</p>
            </div>

            <!-- Relocated Detailed Search Box -->
            <div
                class="bg-white/95 dark:bg-gray-800/95 backdrop-blur-md rounded-3xl shadow-2xl p-8 max-w-5xl mx-auto border border-white/20">
                <div class="mb-8 text-center">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Find Your Perfect Stay') }}
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400">{{ __('Detailed search with all options') }}</p>
                </div>

                <div id="hotelLoading" class="hidden text-center py-4">
                    <div class="animate-spin rounded-full h-8 w-8 border-4 border-orange-500 border-t-transparent mx-auto">
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-300 mt-2">{{ __('Loading hotels...') }}</p>
                </div>

                <!-- Search Form -->
                <form id="searchForm" action="{{ route('city.hotels', ['cityCode' => 'PLACEHOLDER']) }}" method="GET"
                    class="space-y-6">
                    <!-- Row 1: Country, City, Adults -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Country Select -->
                        <div class="relative">
                            <label class="block text-gray-700 dark:text-gray-300 text-xs font-bold mb-2 uppercase">
                                <i
                                    class="fas fa-globe text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                {{ __('Country') }}
                            </label>
                            <input type="text" id="countrySearchInput" autocomplete="off"
                                placeholder="{{ __('Select Country') }}"
                                value="{{ app()->getLocale() === 'ar' ? 'المملكة العربية السعودية' : 'Saudi Arabia' }}"
                                class="w-full px-4 py-4 border-2 border-gray-100 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl text-lg force-input-text bg-white">
                            <input type="hidden" id="countrySelect" name="country_code" value="SA">
                            <div id="countryAutocomplete"
                                class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl max-h-60 overflow-y-auto hidden">
                            </div>
                            <div
                                class="absolute {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} top-11 text-gray-400 pointer-events-none">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>

                        <!-- City Select -->
                        <div class="relative">
                            <label class="block text-gray-700 dark:text-gray-300 text-xs font-bold mb-2 uppercase">
                                <i
                                    class="fas fa-map-marker-alt text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                {{ __('City') }}
                            </label>
                            <input type="text" id="citySelect" autocomplete="off"
                                placeholder="{{ __('Select City') }}"
                                class="w-full px-4 py-4 border-2 border-gray-100 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl text-lg force-input-text bg-white"
                                disabled>
                            <input type="hidden" name="destination" id="destinationCode">
                            <div id="cityAutocomplete"
                                class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl max-h-60 overflow-y-auto hidden">
                            </div>
                            <div
                                class="absolute {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} top-11 text-gray-400 pointer-events-none">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>

                        <!-- Adults -->
                        <div class="relative">
                            <label class="block text-gray-700 dark:text-gray-300 text-xs font-bold mb-2 uppercase">
                                <i
                                    class="fas fa-user text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                {{ __('Adults') }}
                            </label>
                            <select name="adults"
                                class="w-full px-4 py-4 border-2 border-gray-100 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100 text-lg appearance-none bg-white">
                                <option value="1">{{ app()->getLocale() === 'ar' ? '1 بالغ' : '1 Adult' }}</option>
                                <option value="2" selected>2 {{ __('Adults') }}</option>
                                <option value="3">3 {{ __('Adults') }}</option>
                                <option value="4">4 {{ __('Adults') }}</option>
                                <option value="5">5+ {{ __('Adults') }}</option>
                            </select>
                            <div
                                class="absolute {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} top-11 text-gray-400 pointer-events-none">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Children, CheckIn, CheckOut, Search Button -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <!-- Children -->
                        <div class="relative">
                            <label class="block text-gray-700 dark:text-gray-300 text-xs font-bold mb-2 uppercase">
                                <i
                                    class="fas fa-child text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                {{ __('Children') }}
                            </label>
                            <select name="children"
                                class="w-full px-4 py-4 border-2 border-gray-100 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100 text-lg appearance-none bg-white">
                                <option value="" disabled selected>{{ __('Select number of children') }}</option>
                                <option value="1">{{ app()->getLocale() === 'ar' ? '1 طفل' : '1 Child' }}</option>
                                <option value="2">2 {{ __('Children') }}</option>
                                <option value="3">3 {{ __('Children') }}</option>
                                <option value="4">4+ {{ __('Children') }}</option>
                            </select>
                            <div
                                class="absolute {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} top-11 text-gray-400 pointer-events-none">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>

                        <!-- Check-in -->
                        <div class="relative">
                            <label class="block text-gray-700 dark:text-gray-300 text-xs font-bold mb-2 uppercase">
                                <i
                                    class="fas fa-calendar-alt text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                {{ __('Check In') }}
                            </label>
                            <input type="date" name="CheckIn" required
                                class="w-full px-4 py-4 border-2 border-gray-100 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100 text-lg">
                        </div>

                        <!-- Check-out -->
                        <div class="relative">
                            <label class="block text-gray-700 dark:text-gray-300 text-xs font-bold mb-2 uppercase">
                                <i
                                    class="fas fa-calendar-check text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                {{ __('Check Out') }}
                            </label>
                            <input type="date" name="CheckOut" required
                                class="w-full px-4 py-4 border-2 border-gray-100 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100 text-lg">
                        </div>

                        <!-- Search Button -->
                        <div class="flex items-end">
                            <button type="submit" id="searchBtn"
                                class="w-full bg-gradient-to-r from-orange-500 to-orange-600 py-4 rounded-xl font-bold text-lg hover:from-orange-600 hover:to-orange-700 transition transform hover:scale-[1.02] shadow-xl flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed text-white">
                                <i class="fas fa-search {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                                <span class="btn-text">{{ __('Search') }}</span>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Quick Filters -->
                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex flex-wrap gap-2 items-center">
                    <span
                        class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider mr-2">{{ __('Quick Search') }}:</span>
                    <a href="#"
                        class="px-4 py-2 bg-gray-50 dark:bg-gray-700/50 hover:bg-orange-50 dark:hover:bg-orange-900/30 text-gray-600 dark:text-gray-300 hover:text-orange-600 rounded-xl text-xs font-bold transition-all duration-300 flex items-center border border-gray-100 dark:border-gray-700">
                        <i class="fas fa-fire text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                        {{ __('Today\'s Offers') }}
                    </a>
                    <a href="#"
                        class="px-4 py-2 bg-gray-50 dark:bg-gray-700/50 hover:bg-orange-50 dark:hover:bg-orange-900/30 text-gray-600 dark:text-gray-300 hover:text-orange-600 rounded-xl text-xs font-bold transition-all duration-300 flex items-center border border-gray-100 dark:border-gray-700">
                        <i class="fas fa-star text-yellow-500 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                        {{ __('5 Star Hotels') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Badges -->
    <section class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-center gap-8 md:gap-12">
                <div class="flex items-center text-gray-600">
                    <i
                        class="fas fa-lock text-2xl text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                    <div>
                        <div class="font-bold text-sm">{{ __('Secure Booking') }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-300">{{ __('SSL Encrypted') }}</div>
                    </div>
                </div>
                <div class="flex items-center text-gray-600">
                    <i
                        class="fas fa-money-bill-wave text-2xl text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                    <div>
                        <div class="font-bold text-sm">{{ __('Best Price') }}</div>
                        <div class="text-xs text-gray-500">{{ __('Price Guarantee') }}</div>
                    </div>
                </div>
                <div class="flex items-center text-gray-600">
                    <i
                        class="fas fa-headset text-2xl text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                    <div>
                        <div class="font-bold text-sm">{{ __('24/7 Support') }}</div>
                        <div class="text-xs text-gray-500">{{ __('Always Available') }}</div>
                    </div>
                </div>
                <div class="flex items-center text-gray-600">
                    <i
                        class="fas fa-undo text-2xl text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                    <div>
                        <div class="font-bold text-sm">{{ __('Free Cancellation') }}</div>
                        <div class="text-xs text-gray-500">{{ __('Up to 24 hours') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Flash Deals with Countdown -->
    <section id="offers" class="py-16 bg-gradient-to-br from-orange-50 via-red-50 to-pink-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-4xl font-extrabold text-gray-900 mb-2">{{ __('Flash Deals') }}</h2>
                    <p class="text-gray-600">
                        {{ __('Limited time offers - Book before it\'s too late!') }}</p>
                </div>
                <div class="hidden md:flex items-center bg-white px-6 py-3 rounded-full shadow-lg">
                    <i class="fas fa-clock text-red-600 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                    <span class="text-sm font-bold text-gray-700">{{ __('Ends in') }}</span>
                    <span class="text-xl font-bold text-red-600 mr-3" id="countdown">23:45:12</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($hotels as $hotel)
                    <div
                        class="group relative bg-white @if ($loop->first) dark:bg-gray-800 @endif rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="relative h-56 overflow-hidden">
                            <img src="{{ $hotel['ImageUrls'][0]['ImageUrl'] ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}"
                                alt="فندق"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            <div
                                class="absolute top-0 right-0 bg-gradient-to-br from-red-600 to-pink-600 text-white px-4 py-2 rounded-bl-2xl font-bold text-lg shadow-lg">
                                -40%
                            </div>
                            <div
                                class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                                <div class="text-white text-sm font-semibold">{{ $hotel['CityName'] }},
                                    {{ $hotel['CountryName'] }}</div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <h3
                                    class="text-xl font-bold text-gray-900 @if ($loop->first) dark:text-gray-100 @endif">
                                    {{ Str::limit($hotel['HotelName'], 15) }}
                                </h3>
                                <div class="flex items-center bg-yellow-100 px-2 py-1 rounded-lg">
                                    <i class="fas fa-star text-yellow-500 text-xs ml-1"></i>
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
                                    <span class="text-xs font-bold text-gray-900">{{ $count }}</span>
                                </div>
                            </div>
                            <div
                                class="flex items-center text-xs text-gray-600 @if ($loop->first) dark:text-gray-300 @endif mb-4">
                                <i class="fas fa-map-marker-alt ml-1"></i>
                                <span>{{ Str::limit($hotel['Address'], 30) }}</span>
                            </div>

                            <a href="{{ route('hotel.details', ['locale' => app()->getLocale(), 'id' => $hotel['HotelCode'] ?? ($hotel['HotelId'] ?? 0)]) }}"
                                class="block w-full bg-gradient-to-r from-orange-600 to-orange-600 text-center py-3 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition force-button-text">
                                {{ __('Book Now') }}
                            </a>



                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>

    <!-- Popular Destinations - Enhanced & Random Global Cities -->
    <section id="destinations" class="py-16 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-extrabold text-gray-900 mb-3">{{ __('Popular Destinations') }}</h2>
                <p class="text-gray-600 text-lg">{{ __('Discover the best tourist destinations with the best prices') }}
                </p>
            </div>

            @php
                // Fetch 5 random cities from different countries that have hotels
                // Primary query: Cities known to have hotels
                $randomCities = \App\Models\City::where('hotels_count', '>', 0)
                    ->whereNotNull('code')
                    ->where('code', '!=', '')
                    ->inRandomOrder()
                    ->take(50)
                    ->get()
                    ->unique('country_id')
                    ->take(5);

                // Fallback query: If no cities found with hotels_count > 0 (e.g. data not synced yet)
                // Just show any valid cities so the section isn't empty
if ($randomCities->isEmpty()) {
    $randomCities = \App\Models\City::whereNotNull('code')
        ->where('code', '!=', '')
        ->inRandomOrder()
        ->take(50)
        ->get()
        ->unique('country_id')
        ->take(5);
}

// Predefined high-quality destination images
$fallbackImages = [
    'https://images.unsplash.com/photo-1506929113675-bc7a264fa1d7?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1514282401047-d79a71a590e8?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1533105079780-92b9be482077?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1516483638261-f4dbaf036963?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1523906834658-6e24ef2386f9?auto=format&fit=crop&w=800&q=80',
                ];
                shuffle($fallbackImages);
            @endphp

            <div
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6 overflow-x-auto pb-4 md:pb-0 scrollbar-hide">
                @foreach ($randomCities as $index => $city)
                    <div class="group relative cursor-pointer min-w-[280px] sm:min-w-0">
                        <a href="{{ route('city.hotels', $city->code) }}">
                            <div
                                class="relative h-80 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                                @php
                                    $cityImage = $city->image;
                                    // Robust check: must be non-empty and look like a URL if present
                                    if (
                                        empty($cityImage) ||
                                        (!str_starts_with($cityImage, 'http') && !str_starts_with($cityImage, '/'))
                                    ) {
                                        $cityImage = $fallbackImages[$index % count($fallbackImages)];
                                    }
                                @endphp
                                <img src="{{ $cityImage }}" alt="{{ $city->locale_name }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-500">

                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent">
                                </div>
                                <div class="absolute bottom-0 left-0 right-0 p-6">
                                    <h3 class="text-2xl font-bold text-white mb-3">{{ $city->locale_name }}</h3>

                                    <div class="flex items-center text-white text-sm font-semibold">
                                        <span>{{ __('Explore Now') }}</span>
                                        <i
                                            class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }} text-xs"></i>
                                    </div>
                                </div>
                                <div
                                    class="absolute top-4 right-4 bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-white text-xs font-semibold">
                                    <i
                                        class="fas fa-fire text-orange-400 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ __('Trending') }}
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>



    <!-- Featured Hotels - Premium Design -->
    <section id="hotels" class="py-16 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-12">
                <div>
                    <h2 class="text-4xl font-extrabold text-gray-900 mb-2">{{ __('Featured Hotels') }}</h2>
                    <p class="text-gray-600">{{ __('Choose from the best recommended hotels') }}</p>
                </div>
                <div class="hidden md:flex gap-2">
                    <button
                        class="px-4 py-2 bg-orange-600 text-white rounded-lg font-semibold">{{ __('All') }}</button>
                    <button class="px-4 py-2 bg-white text-gray-700 rounded-lg font-semibold hover:bg-gray-100">5
                        {{ __('stars') }}</button>
                    <button class="px-4 py-2 bg-white text-gray-700 rounded-lg font-semibold hover:bg-gray-100">4
                        {{ __('stars') }}</button>
                    <button class="px-4 py-2 bg-white text-gray-700 rounded-lg font-semibold hover:bg-gray-100">3
                        {{ __('stars') }}</button>
                </div>
            </div>

            <!-- Hotels Slider Container -->
            <div class="relative">
                <!-- Slider Wrapper -->
                <div class="hotels-slider-wrapper overflow-hidden">
                    <div class="hotels-slider-track py-12 flex gap-6 transition-transform duration-500 ease-in-out"
                        style="transform: translateX(0);">
                        <!-- Hotel 1 -->
                        @foreach ($hotels2 as $hotel2)
                            <div
                                class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                                <div class="relative h-64 overflow-hidden">
                                    <img src="{{ $hotel2['ImageUrls'][0]['ImageUrl'] ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}"
                                        alt="فندق"
                                        class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                    <div class="absolute top-4 left-4 flex gap-2">
                                        <div
                                            class="bg-white px-3 py-1 rounded-full text-sm font-bold text-gray-900 shadow-lg">
                                            @php
                                                $stars = [
                                                    'FiveStar' => 5,
                                                    'FourStar' => 4,
                                                    'ThreeStar' => 3,
                                                    'TwoStar' => 2,
                                                    'OneStar' => 1,
                                                ];
                                                $count = $stars[$hotel2['HotelRating']] ?? 5;
                                            @endphp
                                            <i class="fas fa-star text-yellow-500 ml-1"></i> {{ $count }}
                                        </div>
                                        <div
                                            class="bg-orange-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                            <i
                                                class="fas fa-fire {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                            {{ __('Popular') }}
                                        </div>
                                    </div>
                                    <div
                                        class="absolute bottom-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-lg text-xs font-semibold text-gray-900">
                                        <i class="fas fa-images {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                        {{ __('photos') }}
                                    </div>
                                </div>
                                <div class="p-6">
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $hotel2['HotelName'] }}
                                            </h3>
                                            <p class="text-gray-600 text-sm flex items-center">
                                                <i class="fas fa-map-marker-alt text-orange-600 ml-1 text-xs"></i>
                                                {{ $hotel2['CityName'] }}, {{ $hotel2['CountryName'] }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap gap-2 mb-4">
                                        <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg font-semibold">
                                            <i
                                                class="fas fa-wifi {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                            {{ __('WiFi') }}
                                        </span>
                                        <span
                                            class="px-2 py-1 bg-green-50 text-green-700 text-xs rounded-lg font-semibold">
                                            <i
                                                class="fas fa-swimming-pool {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                            {{ __('Pool') }}
                                        </span>
                                        <span
                                            class="px-2 py-1 bg-purple-50 text-purple-700 text-xs rounded-lg font-semibold">
                                            <i
                                                class="fas fa-utensils {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                            {{ __('Restaurant') }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center">
                                            <div class="flex text-yellow-500 text-sm ml-2">
                                                @for ($i = 0; $i < $count; $i++)
                                                    <i class="fas fa-star"></i>
                                                @endfor

                                            </div>
                                            <span class="text-sm text-gray-500">({{ $count }}
                                                {{ __('reviews') }})</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                                        <a href="#"
                                            class="bg-gradient-to-r from-orange-600 to-orange-600 px-6 py-2 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition shadow-lg force-button-text w-full text-center">
                                            {{ __('Book Now') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Navigation Arrows -->
            <button
                class="hotels-slider-prev absolute {{ app()->getLocale() === 'ar' ? 'right-0' : 'left-0' }} top-1/2 -translate-y-1/2 z-10 bg-white shadow-lg hover:bg-orange-600 text-gray-700 hover:text-white p-3 rounded-full transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-orange-500 -translate-x-1/2 {{ app()->getLocale() === 'ar' ? 'translate-x-1/2' : '' }}"
                aria-label="{{ __('Previous') }}" type="button">
                <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xl"></i>
            </button>
            <button
                class="hotels-slider-next absolute {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} top-1/2 -translate-y-1/2 z-10 bg-white shadow-lg hover:bg-orange-600 text-gray-700 hover:text-white p-3 rounded-full transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-orange-500 translate-x-1/2 {{ app()->getLocale() === 'ar' ? '-translate-x-1/2' : '' }}"
                aria-label="{{ __('Next') }}" type="button">
                <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xl"></i>
            </button>
        </div>
        </div>
    </section>

    <!-- Customer Reviews - Enhanced & Professional -->
    <section class="py-24 relative overflow-hidden bg-slate-900 border-y border-white/5">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/testimonials-bg.png') }}" alt="Testimonials Background"
                class="w-full h-full object-cover opacity-60">
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900/40 via-transparent to-slate-900/40"></div>
        </div>

        <!-- Decorative Background Elements (Kept for depth) -->
        <div class="absolute top-0 left-0 w-full h-full opacity-10">
            <div
                class="absolute top-20 {{ app()->getLocale() === 'ar' ? 'left-20' : 'right-20' }} w-96 h-96 bg-orange-500 rounded-full mix-blend-multiply filter blur-3xl">
            </div>
            <div
                class="absolute bottom-20 {{ app()->getLocale() === 'ar' ? 'right-20' : 'left-20' }} w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl">
            </div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto p-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="text-center mb-16">
                <div class="inline-block mb-4">
                    <span
                        class="px-4 py-2 bg-orange-500/20 backdrop-blur-md text-orange-400 rounded-full text-sm font-bold border border-orange-500/30">
                        {{ __('Testimonials') }}
                    </span>
                </div>
                <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-4 drop-shadow-lg">
                    {{ __('What Our Customers Say') }}
                </h2>
                <p class="text-slate-200 text-lg mb-8 max-w-2xl mx-auto drop-shadow-md">
                    {{ __('Real reviews from our distinguished customers') }}
                </p>

                <!-- Rating Badge -->
                <div
                    class="inline-flex items-center gap-3 bg-white/10 backdrop-blur-xl rounded-3xl px-8 py-5 shadow-2xl border border-white/20">
                    <div class="flex text-yellow-400 text-3xl">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="h-10 w-px bg-white/20"></div>
                    <div>
                        <div class="text-3xl font-bold text-white leading-none">4.8</div>
                        <div class="text-xs text-slate-300 mt-1 uppercase tracking-wider font-semibold">
                            {{ __('Based on') }} 2,458
                            {{ __('reviews') }}</div>
                    </div>
                </div>
            </div>

            <!-- Testimonials Slider Container -->
            <div class="relative">
                <!-- Slider Wrapper -->
                <div class="testimonials-slider-wrapper overflow-hidden pb-12">
                    <div class="testimonials-slider-track flex gap-8 transition-transform duration-500 ease-in-out"
                        style="transform: translateX(0);">
                        <!-- Review 1 - Featured Style -->
                        <div
                            class="group relative bg-white/5 backdrop-blur-md rounded-[2.5rem] p-8 shadow-2xl border border-white/10 hover:bg-white/10 transition-all duration-300 transform hover:-translate-y-2">
                            <!-- Quote Icon -->
                            <div
                                class="absolute top-6 {{ app()->getLocale() === 'ar' ? 'right-6' : 'left-6' }} text-orange-400/10 text-6xl font-serif">
                                "
                            </div>

                            <!-- Rating Stars -->
                            <div class="flex text-yellow-400 text-lg mb-4 relative z-10">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>

                            <!-- Review Text -->
                            <p class="text-slate-100 leading-relaxed mb-6 relative z-10 text-lg font-medium">
                                "{{ __('Review 1 Text') }}"
                            </p>

                            <!-- Customer Info -->
                            <div class="flex items-center gap-4 pt-6 border-t border-white/10 relative z-10">
                                <div class="relative">
                                    <div
                                        class="w-16 h-16 bg-gradient-to-br from-blue-500/80 to-blue-600/80 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-lg transform group-hover:scale-110 transition duration-300 backdrop-blur-sm">
                                        أ
                                    </div>
                                    <div
                                        class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-slate-900 flex items-center justify-center">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-white text-lg mb-1">أحمد محمد</h4>
                                    <p class="text-sm text-slate-400">3 {{ __('days ago') }}</p>
                                </div>
                            </div>

                            <!-- Verified Badge -->
                            <div
                                class="absolute bottom-6 {{ app()->getLocale() === 'ar' ? 'left-6' : 'right-6' }} flex items-center gap-2 text-xs text-orange-400 font-bold tracking-wide">
                                <i class="fas fa-check-circle"></i>
                                <span class="uppercase">{{ __('Verified Booking') }}</span>
                            </div>
                        </div>

                        <!-- Review 2 - Modern Style -->
                        <div
                            class="group relative bg-gradient-to-br from-white/10 to-transparent backdrop-blur-md rounded-[2.5rem] p-8 shadow-2xl border border-orange-500/10 hover:border-orange-500/30 transition-all duration-300 transform hover:-translate-y-2">
                            <!-- Decorative Corner -->
                            <div
                                class="absolute top-0 {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} w-24 h-24 bg-gradient-to-br from-orange-500/10 to-transparent rounded-bl-3xl {{ app()->getLocale() === 'ar' ? 'rounded-br-0 rounded-tr-3xl' : 'rounded-br-3xl rounded-tl-0' }}">
                            </div>

                            <!-- Rating Stars -->
                            <div class="flex text-yellow-400 text-lg mb-4">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>

                            <!-- Review Text -->
                            <p class="text-slate-100 leading-relaxed mb-6 text-lg font-medium">
                                "{{ __('Review 2 Text') }}"
                            </p>

                            <!-- Customer Info -->
                            <div class="flex items-center gap-4 pt-6 border-t border-white/10">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-pink-500/80 to-pink-600/80 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-lg transform group-hover:rotate-6 transition duration-300 backdrop-blur-sm">
                                    س
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-white text-lg mb-1">سارة علي</h4>
                                    <p class="text-sm text-slate-400">{{ __('week ago') }}</p>
                                </div>
                            </div>

                            <!-- Verified Badge -->
                            <div class="mt-4 flex items-center gap-2 text-xs text-orange-400 font-bold tracking-wide">
                                <i class="fas fa-check-circle"></i>
                                <span class="uppercase">{{ __('Verified Booking') }}</span>
                            </div>
                        </div>


                    </div>
                </div>

                <!-- Navigation Arrows -->
                <button
                    class="testimonials-slider-prev absolute {{ app()->getLocale() === 'ar' ? 'right-0' : 'left-0' }} top-1/2 -translate-y-1/2 z-20 bg-white/10 backdrop-blur-lg shadow-2xl hover:bg-orange-600 text-white p-4 rounded-full transition-all duration-300 border border-white/10 -translate-x-1/2 {{ app()->getLocale() === 'ar' ? 'translate-x-1/2' : '' }}"
                    aria-label="{{ __('Previous') }}" type="button">
                    <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-2xl"></i>
                </button>
                <button
                    class="testimonials-slider-next absolute {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} top-1/2 -translate-y-1/2 z-20 bg-white/10 backdrop-blur-lg shadow-2xl hover:bg-orange-600 text-white p-4 rounded-full transition-all duration-300 border border-white/10 translate-x-1/2 {{ app()->getLocale() === 'ar' ? '-translate-x-1/2' : '' }}"
                    aria-label="{{ __('Next') }}" type="button">
                    <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-2xl"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Why Choose Us - Modern Split Layout -->
    <section id="about" class="py-20 bg-white dark:bg-gray-900 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <!-- Left Section - Text Content -->
                <div
                    class="{{ app()->getLocale() === 'ar' ? 'lg:text-right' : 'lg:text-left' }} text-center lg:text-left">
                    <!-- Badge -->
                    <div class="inline-block mb-6">
                        <span
                            class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-full text-sm font-semibold">
                            {{ __('Why Choose Us') }}
                        </span>
                    </div>

                    <!-- Main Heading -->
                    <h2
                        class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 dark:text-white mb-6 leading-tight">
                        {{ __('Live the Experience You\'ve Always Dreamed Of') }}
                    </h2>

                    <!-- Description -->
                    <p
                        class="text-lg text-gray-600 dark:text-gray-400 mb-8 leading-relaxed max-w-xl {{ app()->getLocale() === 'ar' ? 'lg:mr-0 lg:ml-auto' : 'lg:ml-0 lg:mr-auto' }}">
                        {{ __('Enjoy curated travel experiences, flexible options, and trusted support every step of the way.') }}
                    </p>
                    <p
                        class="text-lg text-gray-600 dark:text-gray-400 mb-8 leading-relaxed max-w-xl {{ app()->getLocale() === 'ar' ? 'lg:mr-0 lg:ml-auto' : 'lg:ml-0 lg:mr-auto' }}">
                        {{ __('Download the app and book your trip with ease') }}
                    </p>

                    <!-- App Download Buttons -->
                    <div
                        class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 justify-center lg:justify-start max-w-md">
                        <a href="#"
                            class="w-full sm:w-auto flex items-center gap-3 bg-black text-white px-8 py-4 rounded-lg hover:bg-gray-800 transition shadow-lg min-w-[200px]">
                            <svg class="w-8 h-8 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.5,12.92 20.16,13.19L16.81,12L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z"
                                    fill="#00D9FF" />
                            </svg>
                            <div class="text-left">
                                <div class="text-xs">{{ __('GET IT ON') }}</div>
                                <div class="text-base font-bold">{{ __('Google Play') }}</div>
                            </div>
                        </a>
                        <a href="#"
                            class="w-full sm:w-auto flex items-center gap-3 bg-black text-white px-8 py-4 rounded-lg hover:bg-gray-800 transition shadow-lg min-w-[200px]">
                            <svg class="w-8 h-8 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M18.71,19.5C17.88,20.74 17,21.95 15.66,21.97C14.32,22 13.89,21.18 12.37,21.18C10.84,21.18 10.37,21.95 9.1,22C7.79,22.05 6.8,20.68 5.96,19.47C4.25,17 2.94,12.45 4.7,9.39C5.57,7.87 7.13,6.91 8.82,6.88C10.1,6.86 11.32,7.75 12.11,7.75C12.89,7.75 14.37,6.68 15.92,6.84C16.57,6.87 18.39,7.1 19.56,8.82C19.47,8.88 17.39,10.1 17.41,12.63C17.44,15.65 20.06,16.66 20.09,16.67C20.06,16.74 19.67,18.11 18.71,19.5M13,3.5C13.73,2.67 14.94,2.04 15.94,2C16.07,3.17 15.6,4.35 14.9,5.19C14.21,6.04 13.07,6.7 11.95,6.61C11.8,5.46 12.36,4.26 13,3.5Z"
                                    fill="#FFFFFF" />
                            </svg>
                            <div class="text-left">
                                <div class="text-xs">{{ __('Download on the') }}</div>
                                <div class="text-base font-bold">{{ __('App Store') }}</div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Right Section - Feature Cards Grid (2 columns) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Card 1 - Destinations (Orange) -->
                    <div
                        class="group relative bg-gradient-to-br from-orange-500 to-orange-600 rounded-3xl p-6 text-white shadow-2xl overflow-hidden transform hover:scale-105 hover:shadow-orange-500/50 transition-all duration-300">
                        <!-- Decorative Background Pattern -->
                        <div
                            class="absolute top-0 {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} w-32 h-32 bg-white/10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2">
                        </div>
                        <div class="relative z-10">
                            <div class="mb-4 flex items-center justify-between">
                                <div
                                    class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center transform group-hover:rotate-12 transition-transform duration-300">
                                    <i class="fas fa-suitcase-rolling text-3xl"></i>
                                </div>
                                <div class="text-4xl font-extrabold opacity-20">01</div>
                            </div>
                            <h3 class="text-2xl font-bold mb-2">50,000+ {{ __('Hotels Worldwide') }}</h3>
                            <p class="text-sm text-orange-100 leading-relaxed">
                                {{ __('Our expert team handpicked all destinations in this site.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Card 2 - 24/7 Support (White) -->
                    <div
                        class="group relative bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-2xl border-2 border-gray-100 dark:border-gray-700 overflow-hidden transform hover:scale-105 hover:shadow-blue-500/20 hover:border-blue-200 dark:hover:border-blue-800 transition-all duration-300">
                        <!-- Decorative Background Pattern -->
                        <div
                            class="absolute top-0 {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} w-32 h-32 bg-blue-100 dark:bg-blue-900/30 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2">
                        </div>
                        <div class="relative z-10">
                            <div class="mb-4 flex items-center justify-between">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center transform group-hover:rotate-12 transition-transform duration-300 shadow-lg">
                                    <i class="fas fa-headset text-3xl text-white"></i>
                                </div>
                                <div class="text-4xl font-extrabold text-gray-100 dark:text-gray-800">02</div>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                {{ __('24/7 Customer Support') }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                                {{ __('We\'re here for you before, during, and throughout your journey') }}
                            </p>
                        </div>
                    </div>

                    <!-- Card 3 - Fast Booking (White) -->
                    <div
                        class="group relative bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-2xl border-2 border-gray-100 dark:border-gray-700 overflow-hidden transform hover:scale-105 hover:shadow-green-500/20 hover:border-green-200 dark:hover:border-green-800 transition-all duration-300">
                        <!-- Decorative Background Pattern -->
                        <div
                            class="absolute top-0 {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} w-32 h-32 bg-green-100 dark:bg-green-900/30 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2">
                        </div>
                        <div class="relative z-10">
                            <div class="mb-4 flex items-center justify-between">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center transform group-hover:rotate-12 transition-transform duration-300 shadow-lg">
                                    <i class="fas fa-bolt text-3xl text-white"></i>
                                </div>
                                <div class="text-4xl font-extrabold text-gray-100 dark:text-gray-800">03</div>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Fast Booking') }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                                {{ __('Secure payment and instant confirmation.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Card 4 - Best Price (Orange) -->
                    <div
                        class="group relative bg-gradient-to-br from-orange-500 to-orange-600 rounded-3xl p-6 text-white shadow-2xl overflow-hidden transform hover:scale-105 hover:shadow-orange-500/50 transition-all duration-300">
                        <!-- Decorative Background Pattern -->
                        <div
                            class="absolute top-0 {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} w-32 h-32 bg-white/10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2">
                        </div>
                        <div class="relative z-10">
                            <div class="mb-4 flex items-center justify-between">
                                <div
                                    class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center transform group-hover:rotate-12 transition-transform duration-300">
                                    <i class="fas fa-money-bill-wave text-3xl"></i>
                                </div>
                                <div class="text-4xl font-extrabold opacity-20">04</div>
                            </div>
                            <h3 class="text-2xl font-bold mb-2">{{ __('Best Price') }}</h3>
                            <p class="text-sm text-orange-100 leading-relaxed">
                                {{ __('Price match within 48 hours of order confirmation.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Book Your Trip Now Section -->
    <section class="py-24 relative overflow-hidden text-white">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/booking-bg.png') }}" alt="Booking Background"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px]"></div>
        </div>

        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-4 drop-shadow-lg">
                {{ __('Book Your Trip Now') }}
            </h2>
            <p class="text-xl md:text-2xl text-slate-100 mb-10 drop-shadow-md">
                {{ __('Start Your Journey With Us') }}
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <a href="{{ route('hotels.search') }}"
                    class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white px-10 py-4 rounded-xl font-bold text-lg shadow-2xl hover:shadow-orange-500/40 transform hover:scale-105 transition-all duration-300 min-w-[220px]">
                    <i class="fas fa-calendar-check text-xl"></i>
                    {{ __('Book Now') }}
                </a>
                <a href="{{ route('contact') }}"
                    class="inline-flex items-center justify-center gap-2 bg-white/10 backdrop-blur-md text-white px-10 py-4 rounded-xl font-bold text-lg border-2 border-white/30 hover:border-orange-500 hover:bg-white/20 shadow-2xl hover:shadow-white/10 transform hover:scale-105 transition-all duration-300 min-w-[220px]">
                    <i class="fas fa-envelope text-xl"></i>
                    {{ __('Contact Us') }}
                </a>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        /* Hero Slider Styles */
        .hero-slider-container {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            touch-action: pan-y;
            z-index: 1;
        }

        .hero-slider-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .hero-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transform: scale(1.1);
            transition: opacity 1.5s ease-in-out, transform 8s ease-out;
            z-index: 1;
            will-change: opacity, transform;
            pointer-events: none;
        }

        .hero-slide.active {
            opacity: 1;
            transform: scale(1);
            z-index: 2;
            pointer-events: auto;
        }

        .hero-slide:not(.active) {
            opacity: 0.05;
        }

        .hero-slide-image {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-slider-dot {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .hero-slider-dot.active {
            background-color: white;
            width: 2.5rem;
            border-radius: 0.25rem;
        }

        /* Smooth fade transition */
        .hero-slide.fade-out {
            opacity: 0;
            transition: opacity 0.8s ease-in-out;
        }

        .hero-slide.fade-in {
            opacity: 1;
            transition: opacity 0.8s ease-in-out;
        }

        /* Mobile optimizations */
        @media (max-width: 768px) {
            .hero-slider-dot {
                width: 2rem;
                height: 2rem;
            }

            .hero-slider-dot.active {
                width: 2rem;
            }
        }

        /* Hotels & Testimonials Slider Styles */
        .hotels-slider-wrapper,
        .testimonials-slider-wrapper {
            position: relative;
            overflow: hidden;
            margin: 0 -12px;
            padding: 12px;
        }

        .hotels-slider-track,
        .testimonials-slider-track {
            display: flex;
            padding-bottom: 20px;
            will-change: transform;
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hotels-slider-track>div,
        .testimonials-slider-track>div {
            flex: 0 0 100%;
            min-width: 0;
            padding: 15px;
            box-sizing: border-box;
        }

        @media (min-width: 768px) {

            .hotels-slider-track>div,
            .testimonials-slider-track>div {
                flex: 0 0 50%;
            }
        }

        @media (min-width: 1024px) {

            .hotels-slider-track>div,
            .testimonials-slider-track>div {
                flex: 0 0 33.333%;
            }
        }

        .hotels-slider-prev,
        .hotels-slider-next,
        .testimonials-slider-prev,
        .testimonials-slider-next {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .hotels-slider-wrapper:hover .hotels-slider-prev,
        .hotels-slider-wrapper:hover .hotels-slider-next,
        .testimonials-slider-wrapper:hover .testimonials-slider-prev,
        .testimonials-slider-wrapper:hover .testimonials-slider-next {
            opacity: 1;
        }

        @media (max-width: 768px) {

            .hotels-slider-prev,
            .hotels-slider-next,
            .testimonials-slider-prev,
            .testimonials-slider-next {
                opacity: 1;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Enhanced Hero Slider Functionality
        (function() {
            const slides = document.querySelectorAll('.hero-slide');
            const dots = document.querySelectorAll('.hero-slider-dot');
            const prevBtn = document.getElementById('hero-slider-prev');
            const nextBtn = document.getElementById('hero-slider-next');
            const sliderContainer = document.querySelector('.hero-slider-container');
            let currentSlide = 0;
            let autoSlideInterval;
            let isTransitioning = false;
            let touchStartX = 0;
            let touchEndX = 0;
            const SWIPE_THRESHOLD = 50;
            const AUTO_SLIDE_INTERVAL = 6000; // 6 seconds

            // Preload next image for smoother transitions
            function preloadNextImage(index) {
                const nextIndex = (index + 1) % slides.length;
                const nextSlide = slides[nextIndex];
                if (nextSlide) {
                    const img = nextSlide.querySelector('.hero-slide-image');
                    if (img && !img.complete) {
                        const preloadImg = new Image();
                        preloadImg.src = img.src;
                    }
                }
            }

            function showSlide(index, direction = 'next') {
                if (isTransitioning || index < 0 || index >= slides.length || index === currentSlide) return;

                isTransitioning = true;

                // Remove active class from all slides and dots
                slides.forEach((slide) => {
                    slide.classList.remove('active', 'fade-in', 'fade-out');
                });

                dots.forEach((dot) => {
                    dot.classList.remove('active');
                    dot.setAttribute('aria-selected', 'false');
                });

                // Add active class to current slide and dot
                if (slides[index]) {
                    slides[index].classList.add('active');
                }
                if (dots[index]) {
                    dots[index].classList.add('active');
                    dots[index].setAttribute('aria-selected', 'true');
                }

                currentSlide = index;

                // Reset transition flag after animation completes
                setTimeout(() => {
                    isTransitioning = false;
                    // Preload next image
                    preloadNextImage(index);
                }, 1600);
            }

            function nextSlide() {
                if (isTransitioning) return;
                const nextIndex = (currentSlide + 1) % slides.length;
                showSlide(nextIndex, 'next');
            }

            function prevSlide() {
                if (isTransitioning) return;
                const prevIndex = (currentSlide - 1 + slides.length) % slides.length;
                showSlide(prevIndex, 'prev');
            }

            function goToSlide(index) {
                if (isTransitioning || index === currentSlide) return;
                showSlide(index);
                resetAutoSlide();
            }

            function startAutoSlide() {
                clearInterval(autoSlideInterval);
                autoSlideInterval = setInterval(() => {
                    if (!isTransitioning && document.visibilityState === 'visible') {
                        nextSlide();
                    }
                }, AUTO_SLIDE_INTERVAL);
            }

            function resetAutoSlide() {
                clearInterval(autoSlideInterval);
                startAutoSlide();
            }

            function pauseAutoSlide() {
                clearInterval(autoSlideInterval);
            }

            // Touch/Swipe support for mobile
            function handleTouchStart(e) {
                touchStartX = e.touches[0].clientX;
            }

            function handleTouchMove(e) {
                // Prevent default to avoid scrolling while swiping
                e.preventDefault();
            }

            function handleTouchEnd(e) {
                touchEndX = e.changedTouches[0].clientX;
                handleSwipe();
            }

            function handleSwipe() {
                const diff = touchStartX - touchEndX;
                const isRTL = document.documentElement.dir === 'rtl';

                if (Math.abs(diff) > SWIPE_THRESHOLD) {
                    if ((diff > 0 && !isRTL) || (diff < 0 && isRTL)) {
                        nextSlide();
                    } else {
                        prevSlide();
                    }
                    resetAutoSlide();
                }
            }

            // Keyboard navigation
            function handleKeyPress(e) {
                if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
                    e.preventDefault();
                    const isRTL = document.documentElement.dir === 'rtl';

                    if ((e.key === 'ArrowLeft' && !isRTL) || (e.key === 'ArrowRight' && isRTL)) {
                        prevSlide();
                    } else {
                        nextSlide();
                    }
                    resetAutoSlide();
                }
            }

            // Event listeners
            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    nextSlide();
                    resetAutoSlide();
                });
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    prevSlide();
                    resetAutoSlide();
                });
            }

            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => goToSlide(index));
            });

            // Touch events for mobile swipe
            if (sliderContainer) {
                sliderContainer.addEventListener('touchstart', handleTouchStart, {
                    passive: false
                });
                sliderContainer.addEventListener('touchmove', handleTouchMove, {
                    passive: false
                });
                sliderContainer.addEventListener('touchend', handleTouchEnd, {
                    passive: true
                });

                // Pause on hover (desktop)
                sliderContainer.addEventListener('mouseenter', pauseAutoSlide);
                sliderContainer.addEventListener('mouseleave', startAutoSlide);

                // Pause when tab is hidden
                document.addEventListener('visibilitychange', () => {
                    if (document.visibilityState === 'hidden') {
                        pauseAutoSlide();
                    } else {
                        startAutoSlide();
                    }
                });
            }

            // Keyboard navigation
            document.addEventListener('keydown', handleKeyPress);

            // Initialize - ensure first slide is visible
            if (slides.length > 0) {
                // Make sure first slide is active
                slides[0].classList.add('active');
                if (dots[0]) {
                    dots[0].classList.add('active');
                    dots[0].setAttribute('aria-selected', 'true');
                }
                currentSlide = 0;

                // Start auto slide after a delay
                setTimeout(() => {
                    startAutoSlide();
                    preloadNextImage(0);
                }, 2000);
            }
        })();

        // Hotels Slider Functionality
        (function() {
            const hotelsTrack = document.querySelector('.hotels-slider-track');
            const hotelsPrev = document.querySelector('.hotels-slider-prev');
            const hotelsNext = document.querySelector('.hotels-slider-next');
            if (!hotelsTrack || !hotelsPrev || !hotelsNext) return;

            const hotelsItems = hotelsTrack.querySelectorAll('> div');
            let hotelsCurrentIndex = 0;
            const hotelsItemsPerView = () => {
                if (window.innerWidth >= 1024) return 3;
                if (window.innerWidth >= 768) return 2;
                return 1;
            };
            const hotelsMaxIndex = Math.max(0, hotelsItems.length - hotelsItemsPerView());

            function updateHotelsSlider() {
                const itemsPerView = hotelsItemsPerView();
                const maxIndex = Math.max(0, hotelsItems.length - itemsPerView);
                const translateX = -(hotelsCurrentIndex * (100 / itemsPerView));
                const isRTL = document.documentElement.dir === 'rtl';

                hotelsTrack.style.transform = `translateX(${isRTL ? -translateX : translateX}%)`;

                hotelsPrev.style.opacity = hotelsCurrentIndex === 0 ? '0.5' : '1';
                hotelsPrev.style.pointerEvents = hotelsCurrentIndex === 0 ? 'none' : 'auto';
                hotelsNext.style.opacity = hotelsCurrentIndex >= maxIndex ? '0.5' : '1';
                hotelsNext.style.pointerEvents = hotelsCurrentIndex >= maxIndex ? 'none' : 'auto';
            }

            hotelsPrev.addEventListener('click', () => {
                if (hotelsCurrentIndex > 0) {
                    hotelsCurrentIndex--;
                    updateHotelsSlider();
                }
            });

            hotelsNext.addEventListener('click', () => {
                const itemsPerView = hotelsItemsPerView();
                const maxIndex = Math.max(0, hotelsItems.length - itemsPerView);
                if (hotelsCurrentIndex < maxIndex) {
                    hotelsCurrentIndex++;
                    updateHotelsSlider();
                }
            });

            // Touch/Swipe support
            let hotelsTouchStartX = 0;
            let hotelsTouchEndX = 0;
            const SWIPE_THRESHOLD = 50;

            hotelsTrack.addEventListener('touchstart', (e) => {
                hotelsTouchStartX = e.touches[0].clientX;
            }, {
                passive: true
            });

            hotelsTrack.addEventListener('touchend', (e) => {
                hotelsTouchEndX = e.changedTouches[0].clientX;
                const diff = hotelsTouchStartX - hotelsTouchEndX;
                const isRTL = document.documentElement.dir === 'rtl';

                if (Math.abs(diff) > SWIPE_THRESHOLD) {
                    if ((diff > 0 && !isRTL) || (diff < 0 && isRTL)) {
                        hotelsNext.click();
                    } else {
                        hotelsPrev.click();
                    }
                }
            }, {
                passive: true
            });

            // Responsive update
            let hotelsResizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(hotelsResizeTimeout);
                hotelsResizeTimeout = setTimeout(() => {
                    const itemsPerView = hotelsItemsPerView();
                    hotelsCurrentIndex = Math.min(hotelsCurrentIndex, Math.max(0, hotelsItems.length -
                        itemsPerView));
                    updateHotelsSlider();
                }, 250);
            });

            updateHotelsSlider();
        })();

        // Testimonials Slider Functionality
        (function() {
            const testimonialsTrack = document.querySelector('.testimonials-slider-track');
            const testimonialsPrev = document.querySelector('.testimonials-slider-prev');
            const testimonialsNext = document.querySelector('.testimonials-slider-next');
            if (!testimonialsTrack || !testimonialsPrev || !testimonialsNext) return;

            const testimonialsItems = testimonialsTrack.querySelectorAll('> div');
            let testimonialsCurrentIndex = 0;
            const testimonialsItemsPerView = () => {
                if (window.innerWidth >= 1024) return 3;
                if (window.innerWidth >= 768) return 2;
                return 1;
            };
            const testimonialsMaxIndex = Math.max(0, testimonialsItems.length - testimonialsItemsPerView());

            function updateTestimonialsSlider() {
                const itemsPerView = testimonialsItemsPerView();
                const maxIndex = Math.max(0, testimonialsItems.length - itemsPerView);
                const translateX = -(testimonialsCurrentIndex * (100 / itemsPerView));
                const isRTL = document.documentElement.dir === 'rtl';

                testimonialsTrack.style.transform = `translateX(${isRTL ? -translateX : translateX}%)`;

                testimonialsPrev.style.opacity = testimonialsCurrentIndex === 0 ? '0.5' : '1';
                testimonialsPrev.style.pointerEvents = testimonialsCurrentIndex === 0 ? 'none' : 'auto';
                testimonialsNext.style.opacity = testimonialsCurrentIndex >= maxIndex ? '0.5' : '1';
                testimonialsNext.style.pointerEvents = testimonialsCurrentIndex >= maxIndex ? 'none' : 'auto';
            }

            testimonialsPrev.addEventListener('click', () => {
                if (testimonialsCurrentIndex > 0) {
                    testimonialsCurrentIndex--;
                    updateTestimonialsSlider();
                }
            });

            testimonialsNext.addEventListener('click', () => {
                const itemsPerView = testimonialsItemsPerView();
                const maxIndex = Math.max(0, testimonialsItems.length - itemsPerView);
                if (testimonialsCurrentIndex < maxIndex) {
                    testimonialsCurrentIndex++;
                    updateTestimonialsSlider();
                }
            });

            // Touch/Swipe support
            let testimonialsTouchStartX = 0;
            let testimonialsTouchEndX = 0;
            const SWIPE_THRESHOLD = 50;

            testimonialsTrack.addEventListener('touchstart', (e) => {
                testimonialsTouchStartX = e.touches[0].clientX;
            }, {
                passive: true
            });

            testimonialsTrack.addEventListener('touchend', (e) => {
                testimonialsTouchEndX = e.changedTouches[0].clientX;
                const diff = testimonialsTouchStartX - testimonialsTouchEndX;
                const isRTL = document.documentElement.dir === 'rtl';

                if (Math.abs(diff) > SWIPE_THRESHOLD) {
                    if ((diff > 0 && !isRTL) || (diff < 0 && isRTL)) {
                        testimonialsNext.click();
                    } else {
                        testimonialsPrev.click();
                    }
                }
            }, {
                passive: true
            });

            // Responsive update
            let testimonialsResizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(testimonialsResizeTimeout);
                testimonialsResizeTimeout = setTimeout(() => {
                    const itemsPerView = testimonialsItemsPerView();
                    testimonialsCurrentIndex = Math.min(testimonialsCurrentIndex, Math.max(0,
                        testimonialsItems.length - itemsPerView));
                    updateTestimonialsSlider();
                }, 250);
            });

            updateTestimonialsSlider();
        })();

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Countdown timer
        function updateCountdown() {
            const now = new Date().getTime();
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            tomorrow.setHours(0, 0, 0, 0);
            const distance = tomorrow - now;

            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            const countdownEl = document.getElementById('countdown');
            if (countdownEl) {
                countdownEl.textContent =
                    String(hours).padStart(2, '0') + ':' +
                    String(minutes).padStart(2, '0') + ':' +
                    String(seconds).padStart(2, '0');
            }
        }

        setInterval(updateCountdown, 1000);
        updateCountdown();
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // -------------------------------------------------------------
            // DATE INITIALIZATION
            // -------------------------------------------------------------
            const checkInInput = document.querySelector('input[name="CheckIn"]');
            const checkOutInput = document.querySelector('input[name="CheckOut"]');

            const today = new Date();
            const nextWeek = new Date(today);
            nextWeek.setDate(today.getDate() + 7);

            const formatDate = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };

            if (checkInInput && !checkInInput.value) {
                checkInInput.value = formatDate(today);
                checkInInput.min = formatDate(today);
            }
            if (checkOutInput && !checkOutInput.value) {
                checkOutInput.value = formatDate(nextWeek);
                checkOutInput.min = formatDate(today);
            }

            if (checkInInput && checkOutInput) {
                checkInInput.addEventListener('change', function() {
                    if (this.value) {
                        const checkInDate = new Date(this.value);
                        checkInDate.setDate(checkInDate.getDate() + 1);
                        checkOutInput.min = formatDate(checkInDate);
                        if (checkOutInput.value && checkOutInput.value <= this.value) {
                            checkOutInput.value = formatDate(checkInDate);
                        }
                    }
                });
            }

            // -----------------------------------------------------------------------------
            // DATA INJECTION
            // -----------------------------------------------------------------------------
            const allCountries = @json($countries ?? []);

            // -----------------------------------------------------------------------------
            // COUNTRY SEARCH LOGIC
            // -----------------------------------------------------------------------------
            function initCountrySearch(inputEl, codeEl, listEl, data) {
                if (!inputEl || !codeEl || !listEl) return;

                function showCountryResults(keyword = "") {
                    listEl.innerHTML = "";
                    const lowerK = keyword.toLowerCase();
                    const results = keyword === "" ? data : data.filter(c => {
                        const name = c.Name || c.CountryName || "";
                        return name.toLowerCase().includes(lowerK);
                    });

                    if (results.length === 0) {
                        listEl.classList.add("hidden");
                        return;
                    }

                    listEl.classList.remove("hidden");
                    const seenCodes = new Set(); // Prevent duplicates if needed

                    results.forEach(c => {
                        const code = c.Code || c.CountryCode;
                        const name = c.Name || c.CountryName;
                        if (!code || seenCodes.has(code)) return;
                        seenCodes.add(code);

                        const div = document.createElement("div");
                        div.className =
                            "px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100";
                        div.innerHTML = `<span class="font-medium">${name}</span>`;

                        div.addEventListener("click", () => {
                            inputEl.value = name;
                            codeEl.value = code;
                            listEl.classList.add("hidden");
                            // Trigger change on the hidden input so City logic picks it up
                            codeEl.dispatchEvent(new Event("change"));
                        });

                        listEl.appendChild(div);
                    });
                }

                inputEl.addEventListener("focus", () => showCountryResults(inputEl.value));
                inputEl.addEventListener("input", () => showCountryResults(inputEl.value));
            }


            // -----------------------------------------------------------------------------
            // DUAL FORM LOGIC (Hero & Main)
            // -----------------------------------------------------------------------------

            // Helper to initialize country/city logic
            function initCitySearch(countryEl, cityEl, cityBoxEl, codeEl, hotelEl = null, initialCountryCode =
                null) {
                let filteredCitiesLocal = [];

                function loadCitiesForCountry(countryCode) {
                    cityEl.value = "";
                    codeEl.value = "";
                    cityEl.disabled = true;
                    cityBoxEl.classList.add("hidden");
                    filteredCitiesLocal = [];

                    if (hotelEl) {
                        hotelEl.innerHTML = '<option value="">{{ __('Select Hotel') }}</option>';
                        hotelEl.disabled = true;
                    }

                    if (!countryCode) return;

                    cityEl.placeholder = "{{ __('Loading cities...') }}";

                    let url = "{{ route('locations.cities', ['country' => ':id']) }}";
                    url = url.replace(':id', countryCode);
                    url += (url.includes('?') ? '&' : '?') + 'v=v10';

                    fetch(url)
                        .then(res => res.json())
                        .then(data => {
                            filteredCitiesLocal = data;
                            cityEl.disabled = false;
                            cityEl.placeholder = "{{ __('Select City') }}";
                        })
                        .catch(err => {
                            cityEl.placeholder = "{{ __('Error') }}";
                        });
                }

                countryEl.addEventListener("change", function() {
                    loadCitiesForCountry(this.value);
                });

                // Auto-load cities if initial country code is provided
                if (initialCountryCode) {
                    setTimeout(() => {
                        loadCitiesForCountry(initialCountryCode);
                    }, 100);
                }

                function showResultsLocal(keyword = "") {
                    cityBoxEl.innerHTML = "";
                    if (filteredCitiesLocal.length === 0) {
                        cityBoxEl.classList.add("hidden");
                        return;
                    }

                    let results = keyword === "" ? filteredCitiesLocal :
                        filteredCitiesLocal.filter(city => city.name && city.name.toLowerCase().includes(keyword
                            .toLowerCase()));

                    if (results.length === 0) {
                        cityBoxEl.classList.add("hidden");
                        return;
                    }

                    cityBoxEl.classList.remove("hidden");
                    const seenNames = new Set();

                    results.forEach(city => {
                        if (!city.name || seenNames.has(city.name)) return;
                        seenNames.add(city.name);

                        const div = document.createElement("div");
                        div.className =
                            "px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100";
                        div.innerHTML = `<span class="font-medium">${city.name}</span>`;

                        div.addEventListener("click", () => {
                            cityEl.value = city.name;
                            codeEl.value = city.code;
                            cityBoxEl.classList.add("hidden");
                            if (hotelEl) loadHotelsForCityInternal(city.code, hotelEl);
                        });
                        cityBoxEl.appendChild(div);
                    });
                }

                cityEl.addEventListener("focus", () => {
                    if (!cityEl.disabled && filteredCitiesLocal.length > 0) showResultsLocal("");
                });
                cityEl.addEventListener("input", function() {
                    showResultsLocal(this.value);
                });
            }

            function loadHotelsForCityInternal(cityCode, selectEl) {
                if (!cityCode) return;
                selectEl.innerHTML = '<option value="">{{ __('Loading hotels...') }}</option>';
                selectEl.disabled = true;

                let url = "{{ route('ajax.get-hotels', ['cityCode' => ':code']) }}";
                url = url.replace(':code', cityCode);

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        selectEl.innerHTML = '<option value="">{{ __('Select Hotel') }}</option>';
                        data.forEach(hotel => {
                            const option = document.createElement("option");
                            option.value = hotel.HotelCode || '';
                            option.textContent = hotel.HotelName || '';
                            selectEl.appendChild(option);
                        });
                        selectEl.disabled = false;
                    });
            }

            // Initialize Main Form Country
            initCountrySearch(
                document.getElementById("countrySearchInput"),
                document.getElementById("countrySelect"),
                document.getElementById("countryAutocomplete"),
                allCountries
            );

            // Initialize Main Form City
            // Note: We pass the HIDDEN input 'countrySelect' as the country element
            initCitySearch(
                document.getElementById("countrySelect"),
                document.getElementById("citySelect"),
                document.getElementById("cityAutocomplete"),
                document.getElementById("destinationCode"),
                document.getElementById(
                    "hotelSelect"), // This is removed/null now probably, but logic handles null
                'SA' // Auto-load Saudi Arabia cities on page load
            );

            // Initialize Hero Form Country
            initCountrySearch(
                document.getElementById("heroCountrySearchInput"),
                document.getElementById("heroCountrySelect"),
                document.getElementById("heroCountryAutocomplete"),
                allCountries
            );

            // Initialize Hero Form City
            initCitySearch(
                document.getElementById("heroCountrySelect"),
                document.getElementById("heroCitySelect"),
                document.getElementById("heroCityAutocomplete"),
                document.getElementById("heroDestinationCode")
            );


            // Main Form Submission
            document.getElementById("searchForm").addEventListener("submit", function(e) {
                e.preventDefault();

                const cityCode = document.getElementById("destinationCode").value;
                const checkIn = this.querySelector('input[name="CheckIn"]').value;
                const checkOut = this.querySelector('input[name="CheckOut"]').value;
                const adults = this.querySelector('select[name="adults"]').value;
                const children = this.querySelector('select[name="children"]').value;

                if (!cityCode) {
                    // Falls back to Country selection?? 
                    // Actually, if only Country is selected, we might want to go to all hotels in country?
                    // But current logic favors City.
                    // If no city, let's check if Country is selected
                    const countrySelect = document.getElementById("countrySelect");
                    if (countrySelect && countrySelect.value) {
                        // Redirect to All Hotels with Country Filter
                        const baseUrl = "{{ route('all.hotels') }}";
                        window.location.href = baseUrl +
                            `?country=${countrySelect.value}&check_in=${checkIn}&check_out=${checkOut}&adults=${adults}&children=${children}`;
                        return;
                    }

                    showToast("{{ __('Please select a destination') }}", "error");
                    return;
                }

                if (!checkIn || !checkOut) {
                    showToast("{{ __('Please select dates') }}", "error");
                    return;
                }

                // Redirect to City Hotels with Availability Params
                let baseUrl = "{{ route('city.hotels', ['cityCode' => ':code']) }}";
                baseUrl = baseUrl.replace(':code', cityCode);

                window.location.href = baseUrl +
                    `?CheckIn=${checkIn}&CheckOut=${checkOut}&adults=${adults}&children=${children}`;
            });

            // Hero Form Submission
            document.getElementById("heroSearchForm").addEventListener("submit", function(e) {
                e.preventDefault();
                const cityCode = document.getElementById("heroDestinationCode").value;

                if (!cityCode) {
                    showToast("{{ __('Please select a city') }}", "error");
                    return;
                }

                const baseUrl = "{{ route('city.hotels', ['cityCode' => ':cityCode']) }}";
                window.location.href = baseUrl.replace(':cityCode', cityCode);
            });

            // Global click to close autocompletes
            document.addEventListener("click", function(e) {
                const boxes = [
                    document.getElementById("cityAutocomplete"),
                    document.getElementById("countryAutocomplete"),
                    document.getElementById("heroCityAutocomplete"),
                    document.getElementById("heroCountryAutocomplete")
                ];
                const inputs = [
                    document.getElementById("citySelect"),
                    document.getElementById("countrySearchInput"),
                    document.getElementById("heroCitySelect"),
                    document.getElementById("heroCountrySearchInput")
                ];
                boxes.forEach((box, i) => {
                    if (box && inputs[i] && !inputs[i].contains(e.target) && !box.contains(e
                            .target)) {
                        box.classList.add("hidden");
                    }
                });
            });

        });
    </script>
@endpush
