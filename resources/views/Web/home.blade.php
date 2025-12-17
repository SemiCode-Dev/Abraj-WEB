@extends('Web.layouts.app')

@section('title', __('Book Hotels - Best Offers and Services'))

@section('content')
    <section id="home"
        class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white py-16 md:py-24 overflow-hidden min-h-[600px] md:min-h-[700px]">
        
        <!-- Hero Image Slider Background -->
        <div class="absolute inset-0 hero-slider-container" role="region" aria-label="{{ __('Hero Image Slider') }}">
            <div class="hero-slider-wrapper">
                <!-- Slide 1 -->
                <div class="hero-slide active" 
                     data-slide="0"
                     aria-label="{{ __('Slide') }} 1">
                    <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" 
                         alt="{{ __('Luxury Hotel') }}" 
                         class="hero-slide-image" 
                         loading="eager">
            </div>
                <!-- Slide 2 -->
                <div class="hero-slide" 
                     data-slide="1"
                     aria-label="{{ __('Slide') }} 2">
                    <img src="https://images.unsplash.com/photo-1582719508461-905c673771fd?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" 
                         alt="{{ __('Modern Hotel Room') }}" 
                         class="hero-slide-image" 
                         loading="lazy">
                </div>
                <!-- Slide 3 -->
                <div class="hero-slide" 
                     data-slide="2"
                     aria-label="{{ __('Slide') }} 3">
                    <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" 
                         alt="{{ __('Hotel Pool') }}" 
                         class="hero-slide-image" 
                         loading="lazy">
                </div>
                <!-- Slide 4 -->
                <div class="hero-slide" 
                     data-slide="3"
                     aria-label="{{ __('Slide') }} 4">
                    <img src="https://images.unsplash.com/photo-1512453979798-5ea266f8880c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" 
                         alt="{{ __('Hotel Lobby') }}" 
                         class="hero-slide-image" 
                         loading="lazy">
                </div>
                <!-- Slide 5 -->
                <div class="hero-slide" 
                     data-slide="4"
                     aria-label="{{ __('Slide') }} 5">
                    <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" 
                         alt="{{ __('Hotel Suite') }}" 
                         class="hero-slide-image" 
                         loading="lazy">
                </div>
            </div>
            <!-- Overlay -->
            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/60 z-10"></div>
            
            <!-- Slider Navigation Arrows - Centered Vertically -->
            <button id="hero-slider-prev" 
                    class="absolute {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }} z-30 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white p-3 rounded-full transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-white/50 active:scale-95"
                    style="top: 50%; transform: translateY(-50%);"
                    aria-label="{{ __('Previous slide') }}"
                    type="button">
                <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xl"></i>
            </button>
            <button id="hero-slider-next" 
                    class="absolute {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} z-30 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white p-3 rounded-full transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-white/50 active:scale-95"
                    style="top: 50%; transform: translateY(-50%);"
                    aria-label="{{ __('Next slide') }}"
                    type="button">
                <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xl"></i>
            </button>
            
            <!-- Slider Dots Indicator - Bottom Center -->
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-30 flex gap-2 justify-center items-center" role="tablist" aria-label="{{ __('Slide indicators') }}">
                <button class="hero-slider-dot active w-2.5 h-2.5 rounded-full bg-white transition-all duration-300 hover:scale-125 focus:outline-none focus:ring-2 focus:ring-white/50" 
                        data-slide="0"
                        role="tab"
                        aria-selected="true"
                        aria-label="{{ __('Go to slide') }} 1"
                        type="button"></button>
                <button class="hero-slider-dot w-2.5 h-2.5 rounded-full bg-white/50 hover:bg-white/75 transition-all duration-300 hover:scale-125 focus:outline-none focus:ring-2 focus:ring-white/50" 
                        data-slide="1"
                        role="tab"
                        aria-selected="false"
                        aria-label="{{ __('Go to slide') }} 2"
                        type="button"></button>
                <button class="hero-slider-dot w-2.5 h-2.5 rounded-full bg-white/50 hover:bg-white/75 transition-all duration-300 hover:scale-125 focus:outline-none focus:ring-2 focus:ring-white/50" 
                        data-slide="2"
                        role="tab"
                        aria-selected="false"
                        aria-label="{{ __('Go to slide') }} 3"
                        type="button"></button>
                <button class="hero-slider-dot w-2.5 h-2.5 rounded-full bg-white/50 hover:bg-white/75 transition-all duration-300 hover:scale-125 focus:outline-none focus:ring-2 focus:ring-white/50" 
                        data-slide="3"
                        role="tab"
                        aria-selected="false"
                        aria-label="{{ __('Go to slide') }} 4"
                        type="button"></button>
                <button class="hero-slider-dot w-2.5 h-2.5 rounded-full bg-white/50 hover:bg-white/75 transition-all duration-300 hover:scale-125 focus:outline-none focus:ring-2 focus:ring-white/50" 
                        data-slide="4"
                        role="tab"
                        aria-selected="false"
                        aria-label="{{ __('Go to slide') }} 5"
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
                    {{ __('Search for your perfect hotel') }}
                </h1>
                <p class="text-xl md:text-2xl text-slate-300 mb-2">{{ __('Best offers and services in one place') }}</p>
                <p class="text-slate-400">{{ __('Book Now and Save up to 40%') }}</p>
            </div>

            <!-- Enhanced Search Box -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-6 max-w-6xl mx-auto">
                <div id="hotelLoading" class="hidden text-center py-4">
                    <div class="animate-spin rounded-full h-8 w-8 border-4 border-orange-500 border-t-transparent mx-auto">
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-300 mt-2">{{ __('Loading hotels...') }}
                    </p>
                </div>
                <!-- Search Form -->
                <form id="searchForm" action="{{ route('city.hotels', ['cityCode' => 'PLACEHOLDER']) }}" method="GET" class="space-y-3">

                    <!-- Row 1: Country Select, City Select, Hotel Search, Guests -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <!-- Country Select -->
                        <div class="relative">
                            <label class="block text-gray-700 dark:text-gray-300 text-xs font-bold mb-2 uppercase">
                                <i
                                    class="fas fa-globe text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                {{ __('Country') }}
                            </label>
                            <select id="countrySelect" name="country_code" required
                                class="w-full px-4 py-4 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100 text-lg appearance-none bg-white dark:bg-gray-700">
                                <option value="">{{ __('Select Country') }}</option>
                                @if (is_array($countries) && count($countries) > 0)
                                    @foreach ($countries as $country)
                                        <option value="{{ $country['Code'] ?? $country['CountryCode'] ?? '' }}">{{ $country['Name'] ?? $country['CountryName'] ?? '' }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div
                                class="absolute {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} top-11 text-gray-400 pointer-events-none">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>

                        <!-- Destination - City Select -->
                        <div class="relative">
                            <label class="block text-gray-700 dark:text-gray-300 text-xs font-bold mb-2 uppercase">
                                <i
                                    class="fas fa-map-marker-alt text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                {{ __('City') }}
                            </label>
                            <input type="text" id="citySelect" autocomplete="off" placeholder="{{ __('Select City') }}"
                                class="w-full px-4 py-4 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl text-lg"
                                disabled>

                            <input type="hidden" name="destination" id="destinationCode">

                            <div id="cityAutocomplete"
                                class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg max-h-60 overflow-y-auto hidden">
                            </div>
                            <div
                                class="absolute {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} top-11 text-gray-400 pointer-events-none">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>

                        <!-- Hotel Select -->
                        <div class="relative">
                            <label class="block text-gray-700 dark:text-gray-300 text-xs font-bold mb-2 uppercase">
                                <i
                                    class="fas fa-hotel text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                {{ __('Hotel') }}
                            </label>
                            <select id="hotelSelect" name="hotel_code"
                                class="w-full px-4 py-4 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100 text-lg appearance-none bg-white dark:bg-gray-700"
                                disabled>
                                <option value="">{{ __('Select Hotel') }}</option>
                            </select>
                            <div
                                class="absolute {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} top-11 text-gray-400 pointer-events-none">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>

                        
                    </div>

                    <!-- Row 2: Check In, Check Out, and Search Button -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <!-- Guests & Rooms -->
                        <div class="relative">
                            <label class="block text-gray-700 dark:text-gray-300 text-xs font-bold mb-2 uppercase">
                                <i
                                    class="fas fa-users text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                {{ __('Guests / Rooms') }}
                            </label>
                            <div class="relative">
                                <select name="PaxRooms[Adults]"
                                    class="w-full px-4 py-4 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100 text-lg appearance-none bg-white dark:bg-gray-700">
                                    <option value="1">1 {{ __('Guest') }}</option>
                                    <option value="2" selected>2 {{ __('Guests') }}</option>
                                    <option value="3">3 {{ __('Guests') }}</option>
                                    <option value="4">4 {{ __('Guests') }}</option>
                                    <option value="5">5+ {{ __('Guests') }}</option>
                                </select>
                                <div
                                    class="absolute {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} top-4 text-gray-400 pointer-events-none">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
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
                                class="w-full px-4 py-4 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100 text-lg">
                        </div>

                        <!-- Check-out -->
                        <div class="relative">
                            <label class="block text-gray-700 dark:text-gray-300 text-xs font-bold mb-2 uppercase">
                                <i
                                    class="fas fa-calendar-check text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                {{ __('Check Out') }}
                            </label>
                            <input type="date" name="CheckOut" required
                                class="w-full px-4 py-4 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100 text-lg">
                        </div>

                        <!-- Search Button -->
                        <div class="flex items-end">
                            <button type="submit" id="searchBtn"
                                class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-4 rounded-xl font-bold text-lg hover:from-orange-600 hover:to-orange-700 transition transform hover:scale-105 shadow-lg flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-search {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                                <span class="btn-text">{{ __('Search') }}</span>
                                <svg class="btn-loader hidden animate-spin h-5 w-5 text-white {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4l3-3-3-3v4a12 12 0 00-12 12h4z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Quick Filters -->
                <div class="p-4 flex flex-wrap gap-2">
                    <span class="text-xs text-gray-500 dark:text-gray-300 font-semibold">{{ __('Quick Search') }}</span>
                    <a href="#"
                        class="px-3 py-1 bg-gray-100 dark:bg-gray-700 hover:bg-orange-50 dark:hover:bg-orange-900 text-gray-700 dark:text-gray-300 hover:text-orange-600 rounded-full text-xs font-medium transition">
                        <i class="fas fa-fire text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                        {{ __('Today\'s Offers') }}
                    </a>
                    <a href="#"
                        class="px-3 py-1 bg-gray-100 dark:bg-gray-700 hover:bg-orange-50 dark:hover:bg-orange-900 text-gray-700 dark:text-gray-300 hover:text-orange-600 rounded-full text-xs font-medium transition">
                        <i class="fas fa-star text-yellow-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                        {{ __('5 Star Hotels') }}
                    </a>
                    <a href="#"
                        class="px-3 py-1 bg-gray-100 dark:bg-gray-700 hover:bg-orange-50 dark:hover:bg-orange-900 text-gray-700 dark:text-gray-300 hover:text-orange-600 rounded-full text-xs font-medium transition">
                        <i
                            class="fas fa-swimming-pool text-blue-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                        {{ __('With Pool') }}
                    </a>
                    <a href="#"
                        class="px-3 py-1 bg-gray-100 dark:bg-gray-700 hover:bg-orange-50 dark:hover:bg-orange-900 text-gray-700 dark:text-gray-300 hover:text-orange-600 rounded-full text-xs font-medium transition">
                        <i class="fas fa-wifi text-purple-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                        {{ __('Free WiFi') }}
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
                        class="fas fa-shield-check text-2xl text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
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
                    <h2 class="text-4xl font-extrabold text-gray-900 dark:text-gray-100 mb-2">{{ __('Flash Deals') }}</h2>
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ __('Limited time offers - Book before it\'s too late!') }}</p>
                </div>
                <div class="hidden md:flex items-center bg-white px-6 py-3 rounded-full shadow-lg">
                    <i class="fas fa-clock text-red-600 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                    <span class="text-sm font-bold text-gray-700 dark:text-gray-200">{{ __('Ends in') }}</span>
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

                            <a href="{{ route('hotel.details', ['locale' => app()->getLocale(), 'id' => $hotel['HotelCode'] ?? $hotel['HotelId'] ?? 0]) }}" 
                               class="block w-full bg-gradient-to-r from-orange-600 to-orange-600 text-white text-center py-3 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition">
                                {{ __('Book Now') }}
                            </a>

                           

                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>

    <!-- Popular Destinations - Enhanced -->
    <section id="destinations" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-extrabold text-gray-900 mb-3">{{ __('Popular Destinations') }}</h2>
                <p class="text-gray-600 text-lg">{{ __('Discover the best tourist destinations with the best prices') }}
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                @foreach ($cities as $city)
                    <div class="group relative cursor-pointer">
                        <a href="{{ route('city.hotels', $city->code) }}">
                            <div
                                class="relative h-80 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                                @if ($loop->first)
                                    <img src="https://images.unsplash.com/photo-1512453979798-5ea266f8880c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                                        alt="مكة المكرمة"
                                        class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                @elseif ($loop->last)
                                    <img src="https://images.unsplash.com/photo-1582719508461-905c673771fd?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                                        alt="المدينة المنورة"
                                        class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                @elseif ($loop->index == 1)
                                    <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                                        alt="أبو ظبي"
                                        class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                @else
                                    <img src="https://images.unsplash.com/photo-1512453979798-5ea266f8880c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                                        alt="مكة المكرمة"
                                        class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent">
                                </div>
                                <div class="absolute bottom-0 left-0 right-0 p-6">
                                    <h3 class="text-2xl font-bold text-white mb-1">{{ $city->locale_name }}</h3>
                                    <p class="text-white/90 text-sm mb-3">150+ {{ __('hotels available') }}</p>
                                    <div class="flex items-center text-white text-sm">
                                        <span>{{ __('From') }} 120 {{ __('SAR') }}</span>
                                        <i class="fas fa-arrow-left mr-2 text-xs"></i>
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
                    <div class="hotels-slider-track py-12 flex gap-6 transition-transform duration-500 ease-in-out" style="transform: translateX(0);">
                <!-- Hotel 1 -->
                @foreach ($hotels2 as $hotel2)
                    <div
                        class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                        <div class="relative h-64 overflow-hidden">
                            <img src="{{ $hotel2['ImageUrls'][0]['ImageUrl'] ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}"
                                alt="فندق"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            <div class="absolute top-4 left-4 flex gap-2">
                                <div class="bg-white px-3 py-1 rounded-full text-sm font-bold text-gray-900 shadow-lg">
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
                                <div class="bg-orange-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                    <i class="fas fa-fire {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
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
                                    <i class="fas fa-wifi {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ __('WiFi') }}
                                </span>
                                <span class="px-2 py-1 bg-green-50 text-green-700 text-xs rounded-lg font-semibold">
                                    <i
                                        class="fas fa-swimming-pool {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ __('Pool') }}
                                </span>
                                <span class="px-2 py-1 bg-purple-50 text-purple-700 text-xs rounded-lg font-semibold">
                                    <i class="fas fa-utensils {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
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
                                    <span class="text-sm text-gray-500">({{ $count }} {{ __('reviews') }})</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <div>
                                    <span class="text-3xl font-extrabold text-orange-600">350</span>
                                    <span
                                        class="text-gray-500 text-sm {{ app()->getLocale() === 'ar' ? 'mr-1' : 'ml-1' }}">{{ __('SAR') }}</span>
                                    <div class="text-xs text-gray-400">{{ __('per night') }}</div>
                                </div>
                                <a href="#"
                                    class="bg-gradient-to-r from-orange-600 to-orange-600 text-white px-6 py-2 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition shadow-lg">
                                    احجز الآن
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
                    </div>
                </div>
                <!-- Navigation Arrows -->
                <button class="hotels-slider-prev absolute {{ app()->getLocale() === 'ar' ? 'right-0' : 'left-0' }} top-1/2 -translate-y-1/2 z-10 bg-white shadow-lg hover:bg-orange-600 text-gray-700 hover:text-white p-3 rounded-full transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-orange-500 -translate-x-1/2 {{ app()->getLocale() === 'ar' ? 'translate-x-1/2' : '' }}"
                        aria-label="{{ __('Previous') }}"
                        type="button">
                    <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xl"></i>
                </button>
                <button class="hotels-slider-next absolute {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} top-1/2 -translate-y-1/2 z-10 bg-white shadow-lg hover:bg-orange-600 text-gray-700 hover:text-white p-3 rounded-full transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-orange-500 translate-x-1/2 {{ app()->getLocale() === 'ar' ? '-translate-x-1/2' : '' }}"
                        aria-label="{{ __('Next') }}"
                        type="button">
                    <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xl"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Customer Reviews - Enhanced & Professional -->
    <section class="py-20 bg-gradient-to-b from-gray-50 via-white to-gray-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 relative overflow-hidden">
        <!-- Decorative Background Elements -->
        <div class="absolute top-0 left-0 w-full h-full opacity-5">
            <div class="absolute top-20 {{ app()->getLocale() === 'ar' ? 'left-20' : 'right-20' }} w-96 h-96 bg-orange-500 rounded-full mix-blend-multiply filter blur-3xl"></div>
            <div class="absolute bottom-20 {{ app()->getLocale() === 'ar' ? 'right-20' : 'left-20' }} w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto p-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="text-center mb-16">
                <div class="inline-block mb-4">
                    <span class="px-4 py-2 bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 rounded-full text-sm font-semibold">
                        {{ __('Testimonials') }}
                    </span>
                </div>
                <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white mb-4">
                    {{ __('What Our Customers Say') }}
                </h2>
                <p class="text-gray-600 dark:text-gray-300 text-lg mb-8 max-w-2xl mx-auto">
                    {{ __('Real reviews from our distinguished customers') }}
                </p>
                
                <!-- Rating Badge -->
                <div class="inline-flex items-center gap-3 bg-white dark:bg-gray-800 rounded-2xl px-6 py-4 shadow-lg border border-gray-100 dark:border-gray-700">
                    <div class="flex text-yellow-500 text-2xl">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="h-8 w-px bg-gray-300 dark:bg-gray-600"></div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">4.8</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Based on') }} 2,458 {{ __('reviews') }}</div>
                    </div>
                </div>
            </div>

            <!-- Testimonials Slider Container -->
            <div class="relative">
                <!-- Slider Wrapper -->
                <div class="testimonials-slider-wrapper overflow-hidden">
                    <div class="testimonials-slider-track flex gap-8 transition-transform duration-500 ease-in-out" style="transform: translateX(0);">
                        <!-- Review 1 - Featured Style -->
                <div class="group relative bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-lg border border-gray-100 dark:border-gray-700 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <!-- Quote Icon -->
                    <div class="absolute top-6 {{ app()->getLocale() === 'ar' ? 'right-6' : 'left-6' }} text-orange-500/10 dark:text-orange-400/20 text-6xl font-serif">
                        "
                        </div>
                    
                    <!-- Rating Stars -->
                    <div class="flex text-yellow-500 text-lg mb-4 relative z-10">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                    
                    <!-- Review Text -->
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-6 relative z-10 text-lg">
                        "{{ __('Review 1 Text') }}"
                    </p>
                    
                    <!-- Customer Info -->
                    <div class="flex items-center gap-4 pt-6 border-t border-gray-100 dark:border-gray-700 relative z-10">
                        <div class="relative">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-lg transform group-hover:scale-110 transition duration-300">
                                أ
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-white dark:border-gray-800 flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs"></i>
                        </div>
                    </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-900 dark:text-white text-lg mb-1">أحمد محمد</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">3 {{ __('days ago') }}</p>
                    </div>
                </div>

                    <!-- Verified Badge -->
                    <div class="absolute bottom-6 {{ app()->getLocale() === 'ar' ? 'left-6' : 'right-6' }} flex items-center gap-2 text-xs text-orange-600 dark:text-orange-400 font-semibold">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ __('Verified Booking') }}</span>
                        </div>
                </div>

                <!-- Review 2 - Modern Style -->
                <div class="group relative bg-gradient-to-br from-white to-orange-50/50 dark:from-gray-800 dark:to-orange-900/20 rounded-3xl p-8 shadow-lg border-2 border-orange-100 dark:border-orange-900/30 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <!-- Decorative Corner -->
                    <div class="absolute top-0 {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} w-24 h-24 bg-gradient-to-br from-orange-500/10 to-transparent rounded-bl-3xl {{ app()->getLocale() === 'ar' ? 'rounded-br-0 rounded-tr-3xl' : 'rounded-br-3xl rounded-tl-0' }}"></div>
                    
                    <!-- Rating Stars -->
                    <div class="flex text-yellow-500 text-lg mb-4">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                    
                    <!-- Review Text -->
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-6 text-lg">
                        "{{ __('Review 2 Text') }}"
                    </p>
                    
                    <!-- Customer Info -->
                    <div class="flex items-center gap-4 pt-6 border-t border-orange-100 dark:border-orange-900/30">
                        <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-pink-600 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-lg transform group-hover:rotate-6 transition duration-300">
                            س
                            </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-900 dark:text-white text-lg mb-1">سارة علي</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('week ago') }}</p>
                        </div>
                    </div>
                    
                    <!-- Verified Badge -->
                    <div class="mt-4 flex items-center gap-2 text-xs text-orange-600 dark:text-orange-400 font-semibold">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ __('Verified Booking') }}</span>
                    </div>
                </div>

              
            </div>
        </div>
                
                <!-- Navigation Arrows -->
                <button class="testimonials-slider-prev absolute {{ app()->getLocale() === 'ar' ? 'right-0' : 'left-0' }} top-1/2 -translate-y-1/2 z-10 bg-white dark:bg-gray-800 shadow-lg hover:bg-orange-600 dark:hover:bg-orange-600 text-gray-700 dark:text-gray-300 hover:text-white p-3 rounded-full transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-orange-500 -translate-x-1/2 {{ app()->getLocale() === 'ar' ? 'translate-x-1/2' : '' }}"
                        aria-label="{{ __('Previous') }}"
                        type="button">
                    <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xl"></i>
                </button>
                <button class="testimonials-slider-next absolute {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} top-1/2 -translate-y-1/2 z-10 bg-white dark:bg-gray-800 shadow-lg hover:bg-orange-600 dark:hover:bg-orange-600 text-gray-700 dark:text-gray-300 hover:text-white p-3 rounded-full transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-orange-500 translate-x-1/2 {{ app()->getLocale() === 'ar' ? '-translate-x-1/2' : '' }}"
                        aria-label="{{ __('Next') }}"
                        type="button">
                    <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xl"></i>
                </button>
            </div>
            </div>
    </section>

    <!-- Why Choose Us - Modern Split Layout -->
    <section id="about" class="py-20 bg-white dark:bg-gray-900 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <!-- Left Section - Text Content -->
                <div class="{{ app()->getLocale() === 'ar' ? 'lg:text-right' : 'lg:text-left' }} text-center lg:text-left">
                    <!-- Badge -->
                    <div class="inline-block mb-6">
                        <span class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-full text-sm font-semibold">
                            {{ __('Why Choose Us') }}
                        </span>
        </div>

                    <!-- Main Heading -->
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 dark:text-white mb-6 leading-tight">
                        {{ __('Dare to live the life you\'ve always wanted') }}
                    </h2>
                    
                    <!-- Description -->
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 leading-relaxed max-w-xl {{ app()->getLocale() === 'ar' ? 'lg:mr-0 lg:ml-auto' : 'lg:ml-0 lg:mr-auto' }}">
                        {{ __('Discover how you can offset your adventure\'s carbon emissions and support the sustainable initiatives practiced by our operators worldwide.') }}
                    </p>
                    
                    <!-- App Download Buttons -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 justify-center lg:justify-start max-w-md">
                        <a href="#" class="w-full sm:w-auto flex items-center gap-3 bg-black text-white px-8 py-4 rounded-lg hover:bg-gray-800 transition shadow-lg min-w-[200px]">
                            <svg class="w-8 h-8 flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.5,12.92 20.16,13.19L16.81,12L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z" fill="#00D9FF"/>
                            </svg>
                            <div class="text-left">
                                <div class="text-xs">GET IT ON</div>
                                <div class="text-base font-bold">Google Play</div>
                            </div>
                        </a>
                        <a href="#" class="w-full sm:w-auto flex items-center gap-3 bg-black text-white px-8 py-4 rounded-lg hover:bg-gray-800 transition shadow-lg min-w-[200px]">
                            <svg class="w-8 h-8 flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.71,19.5C17.88,20.74 17,21.95 15.66,21.97C14.32,22 13.89,21.18 12.37,21.18C10.84,21.18 10.37,21.95 9.1,22C7.79,22.05 6.8,20.68 5.96,19.47C4.25,17 2.94,12.45 4.7,9.39C5.57,7.87 7.13,6.91 8.82,6.88C10.1,6.86 11.32,7.75 12.11,7.75C12.89,7.75 14.37,6.68 15.92,6.84C16.57,6.87 18.39,7.1 19.56,8.82C19.47,8.88 17.39,10.1 17.41,12.63C17.44,15.65 20.06,16.66 20.09,16.67C20.06,16.74 19.67,18.11 18.71,19.5M13,3.5C13.73,2.67 14.94,2.04 15.94,2C16.07,3.17 15.6,4.35 14.9,5.19C14.21,6.04 13.07,6.7 11.95,6.61C11.8,5.46 12.36,4.26 13,3.5Z" fill="#FFFFFF"/>
                            </svg>
                            <div class="text-left">
                                <div class="text-xs">Download on the</div>
                                <div class="text-base font-bold">App Store</div>
                            </div>
                        </a>
                    </div>
            </div>

                <!-- Right Section - Feature Cards Grid (2 columns) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Card 1 - Destinations (Orange) -->
                    <div class="group relative bg-gradient-to-br from-orange-500 to-orange-600 rounded-3xl p-6 text-white shadow-2xl overflow-hidden transform hover:scale-105 hover:shadow-orange-500/50 transition-all duration-300">
                        <!-- Decorative Background Pattern -->
                        <div class="absolute top-0 {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} w-32 h-32 bg-white/10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2"></div>
                        <div class="relative z-10">
                            <div class="mb-4 flex items-center justify-between">
                                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center transform group-hover:rotate-12 transition-transform duration-300">
                                    <i class="fas fa-suitcase-rolling text-3xl"></i>
                                </div>
                                <div class="text-4xl font-extrabold opacity-20">01</div>
                            </div>
                            <h3 class="text-2xl font-bold mb-2">50,000+ {{ __('Destinations') }}</h3>
                            <p class="text-sm text-orange-100 leading-relaxed">
                                {{ __('Our expert team handpicked all destinations in this site.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Card 2 - 24/7 Support (White) -->
                    <div class="group relative bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-2xl border-2 border-gray-100 dark:border-gray-700 overflow-hidden transform hover:scale-105 hover:shadow-blue-500/20 hover:border-blue-200 dark:hover:border-blue-800 transition-all duration-300">
                        <!-- Decorative Background Pattern -->
                        <div class="absolute top-0 {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} w-32 h-32 bg-blue-100 dark:bg-blue-900/30 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2"></div>
                        <div class="relative z-10">
                            <div class="mb-4 flex items-center justify-between">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center transform group-hover:rotate-12 transition-transform duration-300 shadow-lg">
                                    <i class="fas fa-headset text-3xl text-white"></i>
                                </div>
                                <div class="text-4xl font-extrabold text-gray-100 dark:text-gray-800">02</div>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Great 24/7 Support') }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                                {{ __('We are here to help, before, during, and even after your trip.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Card 3 - Fast Booking (White) -->
                    <div class="group relative bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-2xl border-2 border-gray-100 dark:border-gray-700 overflow-hidden transform hover:scale-105 hover:shadow-green-500/20 hover:border-green-200 dark:hover:border-green-800 transition-all duration-300">
                        <!-- Decorative Background Pattern -->
                        <div class="absolute top-0 {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} w-32 h-32 bg-green-100 dark:bg-green-900/30 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2"></div>
                        <div class="relative z-10">
                            <div class="mb-4 flex items-center justify-between">
                                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center transform group-hover:rotate-12 transition-transform duration-300 shadow-lg">
                                    <i class="fas fa-bolt text-3xl text-white"></i>
                                </div>
                                <div class="text-4xl font-extrabold text-gray-100 dark:text-gray-800">03</div>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Fast Booking') }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                                {{ __('Secure payment and instant confirmation.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Card 4 - Best Price (Orange) -->
                    <div class="group relative bg-gradient-to-br from-orange-500 to-orange-600 rounded-3xl p-6 text-white shadow-2xl overflow-hidden transform hover:scale-105 hover:shadow-orange-500/50 transition-all duration-300">
                        <!-- Decorative Background Pattern -->
                        <div class="absolute top-0 {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} w-32 h-32 bg-white/10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2"></div>
                        <div class="relative z-10">
                            <div class="mb-4 flex items-center justify-between">
                                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center transform group-hover:rotate-12 transition-transform duration-300">
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
    <section class="py-20 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 relative overflow-hidden">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 dark:text-white mb-4">
                {{ __('Book Your Trip Now') }}
            </h2>
            <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-400 mb-10">
                {{ __('Start Your Journey With Us') }}
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('hotels.search') }}" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white px-8 py-4 rounded-xl font-semibold text-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 min-w-[200px]">
                    <i class="fas fa-calendar-check"></i>
                    {{ __('Book Now') }}
                </a>
                <a href="{{ route('contact') }}" class="inline-flex items-center justify-center gap-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-8 py-4 rounded-xl font-semibold text-lg border-2 border-gray-300 dark:border-gray-700 hover:border-orange-500 dark:hover:border-orange-500 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 min-w-[200px]">
                    <i class="fas fa-envelope"></i>
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

        .hotels-slider-track > div,
        .testimonials-slider-track > div {
            flex: 0 0 100%;
            min-width: 0;
            padding: 15px;
            box-sizing: border-box;
        }

        @media (min-width: 768px) {
            .hotels-slider-track > div,
            .testimonials-slider-track > div {
                flex: 0 0 50%;
            }
        }

        @media (min-width: 1024px) {
            .hotels-slider-track > div,
            .testimonials-slider-track > div {
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
                sliderContainer.addEventListener('touchstart', handleTouchStart, { passive: false });
                sliderContainer.addEventListener('touchmove', handleTouchMove, { passive: false });
                sliderContainer.addEventListener('touchend', handleTouchEnd, { passive: true });
                
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
            }, { passive: true });

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
            }, { passive: true });

            // Responsive update
            let hotelsResizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(hotelsResizeTimeout);
                hotelsResizeTimeout = setTimeout(() => {
                    const itemsPerView = hotelsItemsPerView();
                    hotelsCurrentIndex = Math.min(hotelsCurrentIndex, Math.max(0, hotelsItems.length - itemsPerView));
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
            }, { passive: true });

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
            }, { passive: true });

            // Responsive update
            let testimonialsResizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(testimonialsResizeTimeout);
                testimonialsResizeTimeout = setTimeout(() => {
                    const itemsPerView = testimonialsItemsPerView();
                    testimonialsCurrentIndex = Math.min(testimonialsCurrentIndex, Math.max(0, testimonialsItems.length - itemsPerView));
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

            let cities = [];
            let filteredCities = [];
            const countrySelect = document.getElementById("countrySelect");
            const cityInput = document.getElementById("citySelect");
            const cityBox = document.getElementById("cityAutocomplete");
            const destinationCode = document.getElementById("destinationCode");
            const searchForm = document.getElementById("searchForm");
            const searchBtn = document.getElementById("searchBtn");
            const checkInInput = document.querySelector('input[name="CheckIn"]');
            const checkOutInput = document.querySelector('input[name="CheckOut"]');

            // Set default dates
            const today = new Date();
            const nextMonth = new Date(today);
            nextMonth.setMonth(nextMonth.getMonth() + 1);
            
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
                checkOutInput.value = formatDate(nextMonth);
                checkOutInput.min = formatDate(today);
            }

            // Update checkout min date when checkin changes
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

            // -------------------------------------------------------------
            // COUNTRY SELECTION
            // -------------------------------------------------------------
            countrySelect.addEventListener("change", function() {
                const countryCode = this.value;
                
                // Reset city input
                cityInput.value = "";
                destinationCode.value = "";
                cityInput.disabled = true;
                cityBox.classList.add("hidden");
                filteredCities = [];

                if (!countryCode) {
                    return;
                }

                // Show loading
                cityInput.placeholder = "{{ __('Loading cities...') }}";
                cityInput.disabled = true;

                // Fetch cities for selected country from TBO API
                fetch("{{ url('/get-cities') }}/" + countryCode)
                    .then(res => {
                        if (!res.ok) {
                            throw new Error('Failed to fetch cities');
                        }
                        return res.json();
                    })
                    .then(data => {
                        // Handle error response
                        if (data.error) {
                            throw new Error(data.error);
                        }
                        
                        filteredCities = data;
                        cities = data;
                        cityInput.disabled = false;
                        cityInput.placeholder = "{{ __('Select City') }}";
                        
                        if (data.length === 0) {
                            cityInput.placeholder = "{{ __('No cities available') }}";
                        }
                    })
                    .catch(err => {
                        console.error("Error loading cities:", err);
                        cityInput.placeholder = "{{ __('Error loading cities') }}";
                        showToast("{{ __('Error loading cities') }}", "error");
                    });
            });

            // -------------------------------------------------------------
            // CITY AUTOCOMPLETE
            // -------------------------------------------------------------
            function showCityResults(keyword = "") {
                cityBox.innerHTML = "";
                let results = [];

                if (filteredCities.length === 0) {
                    cityBox.classList.add("hidden");
                    return;
                }

                if (keyword === "") {
                    results = filteredCities.slice(0, 10);
                } else {
                    const lowerKeyword = keyword.toLowerCase();
                    results = filteredCities.filter(city =>
                        (city.Name && city.Name.toLowerCase().includes(lowerKeyword)) ||
                        (city.Name_ar && city.Name_ar.includes(keyword))
                    );
                }

                if (results.length === 0) {
                    cityBox.classList.add("hidden");
                    return;
                }

                cityBox.classList.remove("hidden");

                results.forEach(city => {
                    const div = document.createElement("div");
                    div.className =
                        "px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700";

                    div.innerHTML = `
                <div class="flex justify-between">
                    <span>${city.Name || ''}</span>
                    <span class="text-gray-500">${city.Name_ar || ''}</span>
                </div>
            `;

                    div.addEventListener("click", () => {
                        cityInput.value = city.Name || city.Name_ar;
                        destinationCode.value = city.Code;
                        cityBox.classList.add("hidden");
                        
                        // Load hotels for selected city
                        loadHotelsForCity(city.Code);
                    });

                    cityBox.appendChild(div);
                });
            }

            cityInput.addEventListener("focus", function() {
                if (!this.disabled && filteredCities.length > 0) {
                    showCityResults("");
                }
            });

            cityInput.addEventListener("input", function() {
                if (!this.disabled) {
                    const keyword = this.value.toLowerCase();
                    showCityResults(keyword);
                }
            });

            document.addEventListener("click", function(e) {
                if (!cityInput.contains(e.target) && !cityBox.contains(e.target)) {
                    cityBox.classList.add("hidden");
                }
            });

            // -------------------------------------------------------------
            // HOTEL SELECTION
            // -------------------------------------------------------------
            const hotelSelect = document.getElementById("hotelSelect");
            
            function loadHotelsForCity(cityCode) {
                if (!cityCode) {
                    hotelSelect.innerHTML = '<option value="">{{ __('Select Hotel') }}</option>';
                    hotelSelect.disabled = true;
                    return;
                }

                // Reset hotel select
                hotelSelect.innerHTML = '<option value="">{{ __('Loading hotels...') }}</option>';
                hotelSelect.disabled = true;

                // Fetch hotels for selected city
                fetch("{{ url('/get-hotels') }}/" + cityCode)
                    .then(res => {
                        if (!res.ok) {
                            throw new Error('Failed to fetch hotels');
                        }
                        return res.json();
                    })
                    .then(data => {
                        // Handle error response
                        if (data.error) {
                            throw new Error(data.error);
                        }

                        hotelSelect.innerHTML = '<option value="">{{ __('Select Hotel') }}</option>';
                        
                        if (data.length === 0) {
                            hotelSelect.innerHTML = '<option value="">{{ __('No hotels available') }}</option>';
                            hotelSelect.disabled = true;
                            return;
                        }

                        // Populate hotels
                        data.forEach(hotel => {
                            const option = document.createElement("option");
                            option.value = hotel.HotelCode || '';
                            option.textContent = hotel.HotelName || '';
                            hotelSelect.appendChild(option);
                        });

                        hotelSelect.disabled = false;
                    })
                    .catch(err => {
                        console.error("Error loading hotels:", err);
                        hotelSelect.innerHTML = '<option value="">{{ __('Error loading hotels') }}</option>';
                        hotelSelect.disabled = true;
                        showToast("{{ __('Error loading hotels') }}", "error");
                    });
            }

            // Reset hotel select when country or city changes
            countrySelect.addEventListener("change", function() {
                hotelSelect.innerHTML = '<option value="">{{ __('Select Hotel') }}</option>';
                hotelSelect.disabled = true;
            });

            cityInput.addEventListener("input", function() {
                if (this.value === "") {
                    hotelSelect.innerHTML = '<option value="">{{ __('Select Hotel') }}</option>';
                    hotelSelect.disabled = true;
                    destinationCode.value = "";
                }
            });

            // -------------------------------------------------------------
            // FORM SUBMISSION
            // -------------------------------------------------------------
            searchForm.addEventListener("submit", function(e) {
                e.preventDefault();

                const cityCode = destinationCode.value;
                const countryCode = countrySelect.value;
                const hotelCode = hotelSelect.value;
                const checkIn = document.querySelector('input[name="CheckIn"]').value;
                const checkOut = document.querySelector('input[name="CheckOut"]').value;
                const adults = document.querySelector('select[name="PaxRooms[Adults]"]').value;

                if (!countryCode) {
                    showToast("{{ __('Please select a country') }}", "error");
                    return;
                }

                if (!cityCode) {
                    showToast("{{ __('Please select a city') }}", "error");
                    return;
                }

                if (!hotelCode) {
                    showToast("{{ __('Please select a hotel') }}", "error");
                    return;
                }

                if (!checkIn || !checkOut) {
                    showToast("{{ __('Please select check-in and check-out dates') }}", "error");
                    return;
                }

                // Disable button and show loading
                const btnText = searchBtn.querySelector(".btn-text");
                const btnLoader = searchBtn.querySelector(".btn-loader");
                searchBtn.disabled = true;
                if (btnText) btnText.classList.add("hidden");
                if (btnLoader) btnLoader.classList.remove("hidden");

                // Validate hotel code
                if (!hotelCode || hotelCode.trim() === '') {
                    showToast("{{ __('Please select a hotel') }}", "error");
                    searchBtn.disabled = false;
                    if (btnText) btnText.classList.remove("hidden");
                    if (btnLoader) btnLoader.classList.add("hidden");
                    return;
                }

                // Prepare search data
                const searchData = {
                    CheckIn: checkIn,
                    CheckOut: checkOut,
                    HotelCodes: hotelCode.toString().trim(), // Ensure it's a string and not empty
                    GuestNationality: 'AE',
                    PaxRooms: [
                        {
                            Adults: parseInt(adults) || 1,
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

                // Call search API
                fetch("{{ route('hotel.search') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(searchData)
                })
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Failed to search hotels');
                    }
                    return res.json();
                })
                .then(data => {
                    // Handle API error response
                    if (data.Status && data.Status.Code !== 200) {
                        const errorMsg = data.Status.Description || 'Failed to search hotels';
                        throw new Error(errorMsg);
                    }

                    // Handle error response
                    if (data.error) {
                        throw new Error(data.error);
                    }

                    // Store search results in sessionStorage and redirect to hotels page
                    sessionStorage.setItem('hotelSearchResults', JSON.stringify(data));
                    sessionStorage.setItem('searchParams', JSON.stringify(searchData));
                    
                    // Redirect to hotels page with search results
                    const url = "{{ route('hotels.search') }}";
                    window.location.href = url;
                })
                .catch(err => {
                    console.error("Error searching hotels:", err);
                    const errorMessage = err.message || "{{ __('Failed to search hotels. Please try again.') }}";
                    showToast(errorMessage, "error");
                    
                    // Re-enable button
                    searchBtn.disabled = false;
                    if (btnText) btnText.classList.remove("hidden");
                    if (btnLoader) btnLoader.classList.add("hidden");
                });
            });

        });
    </script>
@endpush
