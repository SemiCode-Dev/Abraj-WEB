@extends('layouts.app')

@section('title', __('Book Hotels - Best Offers and Services'))

@section('content')
<section id="home" class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white py-16 md:py-24 overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80')] bg-cover bg-center opacity-20"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/60"></div>
    </div>

    <!-- Floating Elements -->
    <div class="absolute top-20 right-20 w-72 h-72 bg-orange-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
    <div class="absolute bottom-20 left-20 w-72 h-72 bg-blue-900 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h1 class="text-5xl md:text-7xl font-extrabold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-white via-blue-100 to-cyan-200">
                {{ __('Search for your perfect hotel') }}
            </h1>
            <p class="text-xl md:text-2xl text-slate-300 mb-2">{{ __('Best offers and services in one place') }}</p>
            <p class="text-slate-400">{{ __('Book Now and Save up to 40%') }}</p>
        </div>

    <!-- Enhanced Search Box -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-6 max-w-6xl mx-auto">
            <!-- Search Form -->
            <form action="{{ route('hotels.search', ['locale' => app()->getLocale()]) }}" method="GET" class="space-y-3">
                <!-- Row 1: City Select, Hotel Search, Guests -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <!-- Destination - City Select -->
                    <div class="relative">
                        <label class="block text-gray-700 dark:text-gray-300 text-xs font-bold mb-2 uppercase">
                            <i class="fas fa-map-marker-alt text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                            {{ __('Destination') }}
                        </label>
                        <select id="citySelect" name="destination" required
                            class="w-full px-4 py-4 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100 text-lg transition appearance-none bg-white dark:bg-gray-700">
                            <option value="">{{ __('Select City') }}</option>
                            <option value="Riyadh">{{ __('Riyadh') }}</option>
                            <option value="Jeddah">{{ __('Jeddah') }}</option>
                            <option value="Dammam">{{ __('Dammam') }}</option>
                            <option value="Mecca">{{ __('Mecca') }}</option>
                            <option value="Medina">{{ __('Medina') }}</option>
                            <option value="Abha">{{ __('Abha') }}</option>
                            <option value="Taif">{{ __('Taif') }}</option>
                            <option value="Khobar">{{ __('Khobar') }}</option>
                        </select>
                        <div class="absolute {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} top-11 text-gray-400 pointer-events-none">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>

                    <!-- Hotel Autocomplete - Hidden by default, appears after city selection -->
                    <div id="hotelSearchContainer" class="relative hidden">
                        <label class="block text-gray-700 dark:text-gray-300 text-xs font-bold mb-2 uppercase">
                            <i class="fas fa-hotel text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                            {{ __('Search Hotel') }}
                        </label>
                        <div class="relative">
                            <input type="text" id="hotelSearch" name="hotel_name" 
                                placeholder="{{ __('Search for a hotel...') }}"
                                class="w-full px-4 py-4 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100 text-lg">
                            <div class="absolute {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} top-4 text-gray-400">
                                <i class="fas fa-search"></i>
                            </div>
                            <!-- Autocomplete Results -->
                            <div id="hotelAutocomplete" class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg max-h-60 overflow-y-auto hidden">
                                <!-- Results will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>

                    <!-- Guests & Rooms -->
                    <div class="relative">
                        <label class="block text-gray-700 dark:text-gray-300 text-xs font-bold mb-2 uppercase">
                            <i class="fas fa-users text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                            {{ __('Guests / Rooms') }}
                        </label>
                        <div class="relative">
                            <select name="guests" class="w-full px-4 py-4 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100 text-lg appearance-none bg-white dark:bg-gray-700">
                                <option value="1">1 {{ __('Guest') }}</option>
                                <option value="2" selected>2 {{ __('Guests') }}</option>
                                <option value="3">3 {{ __('Guests') }}</option>
                                <option value="4">4 {{ __('Guests') }}</option>
                                <option value="5">5+ {{ __('Guests') }}</option>
                            </select>
                            <div class="absolute {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} top-4 text-gray-400 pointer-events-none">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Check In, Check Out, and Search Button -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <!-- Check-in -->
                    <div class="relative">
                        <label class="block text-gray-700 dark:text-gray-300 text-xs font-bold mb-2 uppercase">
                            <i class="fas fa-calendar-alt text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                            {{ __('Check In') }}
                        </label>
                        <input type="date" name="check_in" required
                            class="w-full px-4 py-4 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100 text-lg">
                    </div>

                    <!-- Check-out -->
                    <div class="relative">
                        <label class="block text-gray-700 dark:text-gray-300 text-xs font-bold mb-2 uppercase">
                            <i class="fas fa-calendar-check text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                            {{ __('Check Out') }}
                        </label>
                        <input type="date" name="check_out" required
                            class="w-full px-4 py-4 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100 text-lg">
                    </div>

                    <!-- Search Button -->
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-4 rounded-xl font-bold text-lg hover:from-orange-600 hover:to-orange-700 transition transform hover:scale-105 shadow-lg flex items-center justify-center">
                            <i class="fas fa-search {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                            {{ __('Search') }}
                        </button>
                    </div>
                </div>
            </form>

            <!-- Quick Filters -->
                <div class="px-4 pb-4 flex flex-wrap gap-2">
                <span class="text-xs text-gray-500 dark:text-gray-300 font-semibold">{{ __('Quick Search') }}</span>
                <a href="#" class="px-3 py-1 bg-gray-100 dark:bg-gray-700 hover:bg-orange-50 dark:hover:bg-orange-900 text-gray-700 dark:text-gray-300 hover:text-orange-600 rounded-full text-xs font-medium transition">
                    <i class="fas fa-fire text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('Today\'s Offers') }}
                </a>
                <a href="#" class="px-3 py-1 bg-gray-100 dark:bg-gray-700 hover:bg-orange-50 dark:hover:bg-orange-900 text-gray-700 dark:text-gray-300 hover:text-orange-600 rounded-full text-xs font-medium transition">
                    <i class="fas fa-star text-yellow-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('5 Star Hotels') }}
                </a>
                <a href="#" class="px-3 py-1 bg-gray-100 dark:bg-gray-700 hover:bg-orange-50 dark:hover:bg-orange-900 text-gray-700 dark:text-gray-300 hover:text-orange-600 rounded-full text-xs font-medium transition">
                    <i class="fas fa-swimming-pool text-blue-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('With Pool') }}
                </a>
                <a href="#" class="px-3 py-1 bg-gray-100 dark:bg-gray-700 hover:bg-orange-50 dark:hover:bg-orange-900 text-gray-700 dark:text-gray-300 hover:text-orange-600 rounded-full text-xs font-medium transition">
                    <i class="fas fa-wifi text-purple-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('Free WiFi') }}
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
                <i class="fas fa-shield-check text-2xl text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                <div>
                    <div class="font-bold text-sm">{{ __('Secure Booking') }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-300">{{ __('SSL Encrypted') }}</div>
                </div>
            </div>
            <div class="flex items-center text-gray-600">
                <i class="fas fa-money-bill-wave text-2xl text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                <div>
                    <div class="font-bold text-sm">{{ __('Best Price') }}</div>
                    <div class="text-xs text-gray-500">{{ __('Price Guarantee') }}</div>
                </div>
            </div>
            <div class="flex items-center text-gray-600">
                <i class="fas fa-headset text-2xl text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                <div>
                    <div class="font-bold text-sm">{{ __('24/7 Support') }}</div>
                    <div class="text-xs text-gray-500">{{ __('Always Available') }}</div>
                </div>
            </div>
            <div class="flex items-center text-gray-600">
                <i class="fas fa-undo text-2xl text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
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
                <p class="text-gray-600 dark:text-gray-300">{{ __('Limited time offers - Book before it\'s too late!') }}</p>
            </div>
            <div class="hidden md:flex items-center bg-white px-6 py-3 rounded-full shadow-lg">
                <i class="fas fa-clock text-red-600 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                <span class="text-sm font-bold text-gray-700 dark:text-gray-200">{{ __('Ends in') }}</span>
                <span class="text-xl font-bold text-red-600 mr-3" id="countdown">23:45:12</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Deal 1 -->
            <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="relative h-56 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1564501049412-61c2a3083791?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                         alt="عرض دبي" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute top-0 right-0 bg-gradient-to-br from-red-600 to-pink-600 text-white px-4 py-2 rounded-bl-2xl font-bold text-lg shadow-lg">
                        -40%
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                        <div class="text-white text-sm font-semibold">{{ __('Dubai') }}, {{ __('United Arab Emirates') }}</div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ __('Burj Al Arab Hotel') }}</h3>
                        <div class="flex items-center bg-yellow-100 px-2 py-1 rounded-lg">
                            <i class="fas fa-star text-yellow-500 text-xs ml-1"></i>
                            <span class="text-xs font-bold text-gray-900">4.9</span>
                        </div>
                    </div>
                            <div class="flex items-center mb-4">
                        <span class="text-3xl font-extrabold text-orange-600">250</span>
                        <span class="text-gray-500 dark:text-gray-300 text-sm {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">{{ __('SAR') }}</span>
                        <span class="text-gray-400 dark:text-gray-500 line-through text-sm">420 {{ __('SAR') }}</span>
                    </div>
                    <div class="flex items-center text-xs text-gray-600 dark:text-gray-300 mb-4">
                        <i class="fas fa-map-marker-alt ml-1"></i>
                        <span>{{ __('City Center') }} • 2.5 {{ __('km from beach') }}</span>
                    </div>
                    <a href="#" class="block w-full bg-gradient-to-r from-orange-600 to-orange-600 text-white text-center py-3 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition">
                        {{ __('Book Now') }}
                    </a>
                </div>
            </div>

            <!-- Deal 2 -->
            <div class="group relative bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="relative h-56 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                         alt="عرض الرياض" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute top-0 right-0 bg-gradient-to-br from-green-600 to-orange-600 text-white px-4 py-2 rounded-bl-2xl font-bold text-lg shadow-lg">
                        -35%
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                        <div class="text-white text-sm font-semibold">{{ __('Riyadh') }}, {{ __('Saudi Arabia') }}</div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xl font-bold text-gray-900">{{ __('Al Faisaliah Hotel') }}</h3>
                        <div class="flex items-center bg-yellow-100 px-2 py-1 rounded-lg">
                            <i class="fas fa-star text-yellow-500 text-xs ml-1"></i>
                            <span class="text-xs font-bold text-gray-900">4.8</span>
                        </div>
                    </div>
                    <div class="flex items-center mb-4">
                        <span class="text-3xl font-extrabold text-orange-600">180</span>
                        <span class="text-gray-500 text-sm {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">{{ __('SAR') }}</span>
                        <span class="text-gray-400 line-through text-sm">280 {{ __('SAR') }}</span>
                    </div>
                    <div class="flex items-center text-xs text-gray-600 mb-4">
                        <i class="fas fa-map-marker-alt ml-1"></i>
                        <span>{{ __('Diplomatic Quarter') }} • {{ __('Near airport') }}</span>
                    </div>
                    <a href="#" class="block w-full bg-gradient-to-r from-orange-600 to-orange-600 text-white text-center py-3 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition">
                        {{ __('Book Now') }}
                    </a>
                </div>
            </div>

            <!-- Deal 3 -->
            <div class="group relative bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="relative h-56 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                         alt="عرض جدة" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute top-0 right-0 bg-gradient-to-br from-purple-600 to-pink-600 text-white px-4 py-2 rounded-bl-2xl font-bold text-lg shadow-lg">
                        -45%
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                        <div class="text-white text-sm font-semibold">{{ __('Jeddah') }}, {{ __('Saudi Arabia') }}</div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xl font-bold text-gray-900">{{ __('Corniche Hotel') }}</h3>
                        <div class="flex items-center bg-yellow-100 px-2 py-1 rounded-lg">
                            <i class="fas fa-star text-yellow-500 text-xs ml-1"></i>
                            <span class="text-xs font-bold text-gray-900">4.7</span>
                        </div>
                    </div>
                    <div class="flex items-center mb-4">
                        <span class="text-3xl font-extrabold text-orange-600">200</span>
                        <span class="text-gray-500 text-sm {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">{{ __('SAR') }}</span>
                        <span class="text-gray-400 line-through text-sm">365 {{ __('SAR') }}</span>
                    </div>
                    <div class="flex items-center text-xs text-gray-600 mb-4">
                        <i class="fas fa-map-marker-alt ml-1"></i>
                        <span>{{ __('Corniche') }} • {{ __('Sea view') }}</span>
                    </div>
                    <a href="#" class="block w-full bg-gradient-to-r from-orange-600 to-orange-600 text-white text-center py-3 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition">
                        {{ __('Book Now') }}
                    </a>
                </div>
            </div>

            <!-- Deal 4 -->
            <div class="group relative bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="relative h-56 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                         alt="عرض أبوظبي" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute top-0 right-0 bg-gradient-to-br from-blue-600 to-cyan-600 text-white px-4 py-2 rounded-bl-2xl font-bold text-lg shadow-lg">
                        -30%
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                        <div class="text-white text-sm font-semibold">{{ __('Abu Dhabi') }}, {{ __('United Arab Emirates') }}</div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xl font-bold text-gray-900">{{ __('Emirates Hotel') }}</h3>
                        <div class="flex items-center bg-yellow-100 px-2 py-1 rounded-lg">
                            <i class="fas fa-star text-yellow-500 text-xs ml-1"></i>
                            <span class="text-xs font-bold text-gray-900">4.9</span>
                        </div>
                    </div>
                    <div class="flex items-center mb-4">
                        <span class="text-3xl font-extrabold text-orange-600">320</span>
                        <span class="text-gray-500 text-sm {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">{{ __('SAR') }}</span>
                        <span class="text-gray-400 line-through text-sm">460 {{ __('SAR') }}</span>
                    </div>
                    <div class="flex items-center text-xs text-gray-600 mb-4">
                        <i class="fas fa-map-marker-alt ml-1"></i>
                        <span>{{ __('Yas Island') }} • {{ __('Near parks') }}</span>
                    </div>
                    <a href="#" class="block w-full bg-gradient-to-r from-orange-600 to-orange-600 text-white text-center py-3 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition">
                        {{ __('Book Now') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Destinations - Enhanced -->
<section id="destinations" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-extrabold text-gray-900 mb-3">{{ __('Popular Destinations') }}</h2>
            <p class="text-gray-600 text-lg">{{ __('Discover the best tourist destinations with the best prices') }}</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            <div class="group relative cursor-pointer">
                <div class="relative h-80 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1512453979798-5ea266f8880c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                         alt="مكة المكرمة" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6">
                        <h3 class="text-2xl font-bold text-white mb-1">{{ __('Mecca') }}</h3>
                        <p class="text-white/90 text-sm mb-3">150+ {{ __('hotels available') }}</p>
                        <div class="flex items-center text-white text-sm">
                            <span>{{ __('From') }} 120 {{ __('SAR') }}</span>
                            <i class="fas fa-arrow-left mr-2 text-xs"></i>
                        </div>
                    </div>
                    <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-white text-xs font-semibold">
                        <i class="fas fa-fire text-orange-400 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('Trending') }}
                    </div>
                </div>
            </div>

            <div class="group relative cursor-pointer">
                <div class="relative h-80 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1582719508461-905c673771fd?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                         alt="المدينة المنورة" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6">
                        <h3 class="text-2xl font-bold text-white mb-1">{{ __('Medina') }}</h3>
                        <p class="text-white/90 text-sm mb-3">120+ {{ __('hotels available') }}</p>
                        <div class="flex items-center text-white text-sm">
                            <span>{{ __('From') }} 100 {{ __('SAR') }}</span>
                            <i class="fas fa-arrow-left mr-2 text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="group relative cursor-pointer">
                <div class="relative h-80 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                         alt="الطائف" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6">
                        <h3 class="text-2xl font-bold text-white mb-1">{{ __('Taif') }}</h3>
                        <p class="text-white/90 text-sm mb-3">80+ {{ __('hotels available') }}</p>
                        <div class="flex items-center text-white text-sm">
                            <span>{{ __('From') }} 150 {{ __('SAR') }}</span>
                            <i class="fas fa-arrow-left mr-2 text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="group relative cursor-pointer">
                <div class="relative h-80 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1512453979798-5ea266f8880c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                         alt="أبها" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6">
                        <h3 class="text-2xl font-bold text-white mb-1">{{ __('Abha') }}</h3>
                        <p class="text-white/90 text-sm mb-3">60+ {{ __('hotels available') }}</p>
                        <div class="flex items-center text-white text-sm">
                            <span>{{ __('From') }} 180 {{ __('SAR') }}</span>
                            <i class="fas fa-arrow-left mr-2 text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>
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
                <button class="px-4 py-2 bg-orange-600 text-white rounded-lg font-semibold">{{ __('All') }}</button>
                <button class="px-4 py-2 bg-white text-gray-700 rounded-lg font-semibold hover:bg-gray-100">5 {{ __('stars') }}</button>
                <button class="px-4 py-2 bg-white text-gray-700 rounded-lg font-semibold hover:bg-gray-100">4 {{ __('stars') }}</button>
                <button class="px-4 py-2 bg-white text-gray-700 rounded-lg font-semibold hover:bg-gray-100">3 {{ __('stars') }}</button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Hotel 1 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                <div class="relative h-64 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                         alt="فندق" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute top-4 left-4 flex gap-2">
                        <div class="bg-white px-3 py-1 rounded-full text-sm font-bold text-gray-900 shadow-lg">
                            <i class="fas fa-star text-yellow-500 ml-1"></i> 4.8
                        </div>
                        <div class="bg-orange-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                            <i class="fas fa-fire {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('Popular') }}
                        </div>
                    </div>
                    <div class="absolute bottom-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-lg text-xs font-semibold text-gray-900">
                        <i class="fas fa-images {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> 24 {{ __('photos') }}
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ __('International Luxury Hotel') }}</h3>
                            <p class="text-gray-600 text-sm flex items-center">
                                <i class="fas fa-map-marker-alt text-orange-600 ml-1 text-xs"></i>
                                {{ __('Riyadh') }}, {{ __('Saudi Arabia') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg font-semibold">
                            <i class="fas fa-wifi {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('WiFi') }}
                        </span>
                        <span class="px-2 py-1 bg-green-50 text-green-700 text-xs rounded-lg font-semibold">
                            <i class="fas fa-swimming-pool {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('Pool') }}
                        </span>
                        <span class="px-2 py-1 bg-purple-50 text-purple-700 text-xs rounded-lg font-semibold">
                            <i class="fas fa-utensils {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('Restaurant') }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex text-yellow-500 text-sm ml-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-sm text-gray-500">(245 {{ __('reviews') }})</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div>
                            <span class="text-3xl font-extrabold text-orange-600">350</span>
                            <span class="text-gray-500 text-sm {{ app()->getLocale() === 'ar' ? 'mr-1' : 'ml-1' }}">{{ __('SAR') }}</span>
                            <div class="text-xs text-gray-400">{{ __('per night') }}</div>
                        </div>
                        <a href="#" class="bg-gradient-to-r from-orange-600 to-orange-600 text-white px-6 py-2 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition shadow-lg">
                            احجز الآن
                        </a>
                    </div>
                </div>
            </div>

            <!-- Hotel 2 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                <div class="relative h-64 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                         alt="فندق" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute top-4 left-4 flex gap-2">
                        <div class="bg-white px-3 py-1 rounded-full text-sm font-bold text-gray-900 shadow-lg">
                            <i class="fas fa-star text-yellow-500 ml-1"></i> 4.9
                        </div>
                        <div class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                            <i class="fas fa-tag {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('Discount') }}
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ __('Comfort & Relaxation Hotel') }}</h3>
                            <p class="text-gray-600 text-sm flex items-center">
                                <i class="fas fa-map-marker-alt text-orange-600 ml-1 text-xs"></i>
                                {{ __('Jeddah') }}, {{ __('Saudi Arabia') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg font-semibold">
                            <i class="fas fa-wifi {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('WiFi') }}
                        </span>
                        <span class="px-2 py-1 bg-green-50 text-green-700 text-xs rounded-lg font-semibold">
                            <i class="fas fa-spa {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('Spa') }}
                        </span>
                        <span class="px-2 py-1 bg-purple-50 text-purple-700 text-xs rounded-lg font-semibold">
                            <i class="fas fa-dumbbell {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('Gym') }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex text-yellow-500 text-sm ml-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-sm text-gray-500">(189 تقييم)</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div>
                            <span class="text-3xl font-extrabold text-orange-600">280</span>
                            <span class="text-gray-500 text-sm {{ app()->getLocale() === 'ar' ? 'mr-1' : 'ml-1' }}">{{ __('SAR') }}</span>
                            <div class="text-xs text-gray-400">{{ __('per night') }}</div>
                        </div>
                        <a href="#" class="bg-gradient-to-r from-orange-600 to-orange-600 text-white px-6 py-2 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition shadow-lg">
                            احجز الآن
                        </a>
                    </div>
                </div>
            </div>

            <!-- Hotel 3 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                <div class="relative h-64 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                         alt="فندق" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute top-4 left-4 flex gap-2">
                        <div class="bg-white px-3 py-1 rounded-full text-sm font-bold text-gray-900 shadow-lg">
                            <i class="fas fa-star text-yellow-500 ml-1"></i> 4.7
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ __('Premium Stay Hotel') }}</h3>
                            <p class="text-gray-600 text-sm flex items-center">
                                <i class="fas fa-map-marker-alt text-orange-600 ml-1 text-xs"></i>
                                {{ __('Dubai') }}, {{ __('United Arab Emirates') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg font-semibold">
                            <i class="fas fa-wifi {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('WiFi') }}
                        </span>
                        <span class="px-2 py-1 bg-green-50 text-green-700 text-xs rounded-lg font-semibold">
                            <i class="fas fa-swimming-pool {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('Pool') }}
                        </span>
                        <span class="px-2 py-1 bg-orange-50 text-orange-700 text-xs rounded-lg font-semibold">
                            <i class="fas fa-car {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('Parking') }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex text-yellow-500 text-sm ml-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-sm text-gray-500">(312 تقييم)</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div>
                            <span class="text-3xl font-extrabold text-orange-600">420</span>
                            <span class="text-gray-500 text-sm {{ app()->getLocale() === 'ar' ? 'mr-1' : 'ml-1' }}">{{ __('SAR') }}</span>
                            <div class="text-xs text-gray-400">{{ __('per night') }}</div>
                        </div>
                        <a href="#" class="bg-gradient-to-r from-orange-600 to-orange-600 text-white px-6 py-2 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition shadow-lg">
                            احجز الآن
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Customer Reviews - Enhanced -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-extrabold text-gray-900 mb-3">{{ __('What Our Customers Say') }}</h2>
            <p class="text-gray-600 text-lg mb-6">{{ __('Real reviews from our distinguished customers') }}</p>
            <div class="flex items-center justify-center gap-2">
                <div class="flex text-yellow-500 text-2xl">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <span class="text-xl font-bold text-gray-900 {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">4.8</span>
                <span class="text-gray-600">{{ __('Based on') }} 2,458 {{ __('reviews') }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Review 1 -->
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition">
                <div class="flex items-center mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                        أ
                    </div>
                    <div class="mr-4 flex-1">
                        <h4 class="font-bold text-gray-900 mb-1">أحمد محمد</h4>
                        <div class="flex items-center gap-2">
                            <div class="flex text-yellow-500 text-sm">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-xs text-gray-500">3 {{ __('days ago') }}</span>
                        </div>
                    </div>
                </div>
                <p class="text-gray-700 leading-relaxed mb-4">
                    "{{ __('Review 1 Text') }}"
                </p>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check-circle text-orange-600 ml-2"></i>
                    <span>{{ __('Confirmed booking') }}</span>
                </div>
            </div>

            <!-- Review 2 -->
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition">
                <div class="flex items-center mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-pink-500 to-pink-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                        س
                    </div>
                    <div class="mr-4 flex-1">
                        <h4 class="font-bold text-gray-900 mb-1">سارة علي</h4>
                        <div class="flex items-center gap-2">
                            <div class="flex text-yellow-500 text-sm">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-xs text-gray-500">{{ __('week ago') }}</span>
                        </div>
                    </div>
                </div>
                <p class="text-gray-700 leading-relaxed mb-4">
                    "{{ __('Review 2 Text') }}"
                </p>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check-circle text-orange-600 ml-2"></i>
                    <span>{{ __('Confirmed booking') }}</span>
                </div>
            </div>

            <!-- Review 3 -->
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition">
                <div class="flex items-center mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                        خ
                    </div>
                    <div class="mr-4 flex-1">
                        <h4 class="font-bold text-gray-900 mb-1">خالد أحمد</h4>
                        <div class="flex items-center gap-2">
                            <div class="flex text-yellow-500 text-sm">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-xs text-gray-500">2 {{ __('weeks ago') }}</span>
                        </div>
                    </div>
                </div>
                <p class="text-gray-700 leading-relaxed mb-4">
                    "{{ __('Review 3 Text') }}"
                </p>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check-circle text-orange-600 ml-2"></i>
                    <span>{{ __('Confirmed booking') }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us - Enhanced -->
<section id="about" class="py-16 bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 right-0 w-96 h-96 bg-orange-500 rounded-full mix-blend-multiply filter blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-900 rounded-full mix-blend-multiply filter blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-extrabold mb-4">{{ __('Why Choose Us') }}</h2>
            <p class="text-slate-300 text-lg">{{ __('We provide you with the best booking experience with full guarantees') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl group-hover:scale-110 transition duration-300">
                    <i class="fas fa-shield-alt text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">{{ __('Secure & Guaranteed') }}</h3>
                <p class="text-blue-100 leading-relaxed">{{ __('Full protection for your data and payments with SSL encryption') }}</p>
            </div>

            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl group-hover:scale-110 transition duration-300">
                    <i class="fas fa-tag text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">{{ __('Best Prices') }}</h3>
                <p class="text-blue-100 leading-relaxed">{{ __('Guarantee of the best prices in the market or refund the difference') }}</p>
            </div>

            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl group-hover:scale-110 transition duration-300">
                    <i class="fas fa-headset text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">{{ __('24/7 Support') }}</h3>
                <p class="text-blue-100 leading-relaxed">{{ __('Support team available around the clock to help you') }}</p>
            </div>

            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl group-hover:scale-110 transition duration-300">
                    <i class="fas fa-check-circle text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">{{ __('Instant Booking') }}</h3>
                <p class="text-blue-100 leading-relaxed">{{ __('Instant confirmation of bookings with instant notifications') }}</p>
            </div>
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
</style>
@endpush

@push('scripts')
<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
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

    // Hotels database by city
    const hotelsByCity = {
        'Riyadh': ['Ritz-Carlton Riyadh', 'Four Seasons Hotel Riyadh', 'Al Faisaliah Hotel', 'Burj Rafal Hotel', 'Holiday Inn Riyadh', 'Marriott Riyadh', 'Hilton Riyadh', 'InterContinental Riyadh', 'Hyatt Regency Riyadh', 'Radisson Blu Hotel Riyadh'],
        'Jeddah': ['Ritz-Carlton Jeddah', 'Hilton Jeddah', 'Park Hyatt Jeddah', 'Radisson Blu Hotel Jeddah', 'Holiday Inn Jeddah', 'Marriott Jeddah', 'InterContinental Jeddah', 'Sheraton Jeddah', 'Mövenpick Hotel Jeddah', 'Grand Mercure Jeddah'],
        'Dammam': ['Sheraton Dammam', 'Holiday Inn Dammam', 'Radisson Blu Hotel Dammam', 'Marriott Dammam', 'Hilton Dammam', 'InterContinental Dammam', 'Grand Hyatt Dammam', 'Crowne Plaza Dammam', 'Novotel Dammam', 'Pullman Dammam'],
        'Mecca': ['Abraj Al Bait', 'Makkah Clock Royal Tower', 'Conrad Makkah', 'Raffles Makkah Palace', 'Hilton Makkah Convention', 'Swissotel Makkah', 'Mövenpick Hotel Makkah', 'Pullman Zamzam Makkah', 'Elaf Al Huda Hotel', 'Al Marwa Rayhaan by Rotana'],
        'Medina': ['Anwar Al Madinah Mövenpick', 'Pullman Zamzam Madina', 'Hilton Madina', 'Shaza Al Madina', 'Dar Al Iman InterContinental', 'Al Eman Royal Hotel', 'Al Haramain Hotel', 'Madinah Millennium Hotel', 'Al Madinah Al Munawwarah Hotel', 'Al Ansar International Hotel'],
        'Abha': ['Abha Palace Hotel', 'Holiday Inn Abha', 'Al Khozama Hotel Abha', 'Abha Hotel', 'Al Salam Hotel Abha', 'Mercure Abha', 'Al Soudah Park Hotel', 'Green Hills Hotel', 'Al Shams Hotel Abha', 'Al Rashid Hotel Abha'],
        'Taif': ['Hilton Taif', 'InterContinental Taif', 'Al Hada Hotel', 'Al Khozama Hotel Taif', 'Shaza Al Madina Taif', 'Al Faisaliah Hotel Taif', 'Al Shafa Hotel', 'Al Raha Hotel Taif', 'Al Waha Hotel', 'Al Manar Hotel Taif'],
        'Khobar': ['Sheraton Dammam Hotel & Towers', 'Holiday Inn Al Khobar', 'Radisson Blu Hotel Al Khobar', 'Marriott Al Khobar', 'Hilton Al Khobar', 'InterContinental Al Khobar', 'Crowne Plaza Al Khobar', 'Novotel Al Khobar', 'Grand Hyatt Al Khobar', 'Pullman Al Khobar']
    };

    // City select change handler
    const citySelect = document.getElementById('citySelect');
    const hotelSearchContainer = document.getElementById('hotelSearchContainer');
    const hotelSearch = document.getElementById('hotelSearch');
    const hotelAutocomplete = document.getElementById('hotelAutocomplete');

    if (citySelect) {
        citySelect.addEventListener('change', function() {
            const selectedCity = this.value;
            if (selectedCity) {
                hotelSearchContainer.classList.remove('hidden');
                hotelSearch.value = '';
                hotelAutocomplete.classList.add('hidden');
            } else {
                hotelSearchContainer.classList.add('hidden');
                hotelSearch.value = '';
                hotelAutocomplete.classList.add('hidden');
            }
        });
    }

    // Hotel search autocomplete
    if (hotelSearch) {
        hotelSearch.addEventListener('input', function() {
            const city = citySelect.value;
            const searchTerm = this.value.toLowerCase().trim();
            
            if (!city || !searchTerm) {
                hotelAutocomplete.classList.add('hidden');
                return;
            }

            const hotels = hotelsByCity[city] || [];
            const filteredHotels = hotels.filter(hotel => hotel.toLowerCase().includes(searchTerm));

            if (filteredHotels.length > 0) {
                hotelAutocomplete.innerHTML = filteredHotels.map(hotel => `
                    <div class="px-4 py-3 hover:bg-orange-50 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-100 dark:border-gray-700 last:border-b-0" onclick="selectHotel('${hotel}')">
                        <div class="flex items-center">
                            <i class="fas fa-hotel text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                            <span class="text-gray-900 dark:text-gray-100">${hotel}</span>
                        </div>
                    </div>
                `).join('');
                hotelAutocomplete.classList.remove('hidden');
            } else {
                hotelAutocomplete.classList.add('hidden');
            }
        });

        document.addEventListener('click', function(e) {
            if (!hotelSearch.contains(e.target) && !hotelAutocomplete.contains(e.target)) {
                hotelAutocomplete.classList.add('hidden');
            }
        });
    }

    function selectHotel(hotelName) {
        hotelSearch.value = hotelName;
        hotelAutocomplete.classList.add('hidden');
    }

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
@endpush
