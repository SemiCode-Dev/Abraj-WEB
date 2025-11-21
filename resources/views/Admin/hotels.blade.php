@extends('Admin.layouts.app')

@section('title', __('Hotels Management'))

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Hotels Management') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Manage hotels and their information') }}</p>
        </div>
        <div class="flex gap-3">
            <button class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <i class="fas fa-download {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('Export') }}
            </button>
            <button class="px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition shadow-lg">
                <i class="fas fa-plus {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('Add Hotel') }}
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Hotels') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">124</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-building text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Active') }}</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">98</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('5 Stars') }}</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">45</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-star text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Rooms') }}</p>
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400 mt-1">3,456</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-bed text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('Search') }}</label>
                <input type="text" placeholder="{{ __('Hotel name, city...') }}" 
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('City') }}</label>
                <select class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <option>{{ __('All Cities') }}</option>
                    <option>{{ __('Riyadh') }}</option>
                    <option>{{ __('Jeddah') }}</option>
                    <option>{{ __('Dubai') }}</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('Rating') }}</label>
                <select class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <option>{{ __('All Ratings') }}</option>
                    <option>5 {{ __('stars') }}</option>
                    <option>4 {{ __('stars') }}</option>
                    <option>3 {{ __('stars') }}</option>
                </select>
            </div>
            <div class="flex items-end">
                <button class="w-full px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition">
                    {{ __('Apply Filters') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Hotels Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Hotel Card 1 -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="relative h-48">
                <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                     alt="Hotel" class="w-full h-full object-cover">
                <div class="absolute top-4 {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }} flex gap-2">
                    <span class="px-3 py-1 bg-white text-gray-900 rounded-full text-xs font-semibold">
                        <i class="fas fa-star text-yellow-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>4.8
                    </span>
                    <span class="px-3 py-1 bg-green-500 text-white rounded-full text-xs font-semibold">{{ __('Active') }}</span>
                </div>
                <div class="absolute bottom-4 {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }}">
                    <div class="text-white text-xl font-bold">{{ __('International Luxury Hotel') }}</div>
                    <div class="text-white/90 text-sm flex items-center gap-1">
                        <i class="fas fa-map-marker-alt"></i>{{ __('Riyadh') }}
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Rooms') }}</div>
                        <div class="text-lg font-bold text-gray-900 dark:text-white">142</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Available') }}</div>
                        <div class="text-lg font-bold text-green-600 dark:text-green-400">98</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Occupied') }}</div>
                        <div class="text-lg font-bold text-orange-600 dark:text-orange-400">44</div>
                    </div>
                </div>
                
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="px-2 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs rounded-lg">
                        <i class="fas fa-wifi {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>{{ __('WiFi') }}
                    </span>
                    <span class="px-2 py-1 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs rounded-lg">
                        <i class="fas fa-swimming-pool {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>{{ __('Pool') }}
                    </span>
                    <span class="px-2 py-1 bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 text-xs rounded-lg">
                        <i class="fas fa-utensils {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>{{ __('Restaurant') }}
                    </span>
                </div>

                <div class="flex items-center gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button class="flex-1 px-4 py-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition text-sm font-semibold">
                        <i class="fas fa-eye {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>{{ __('View') }}
                    </button>
                    <button class="flex-1 px-4 py-2 bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900/50 transition text-sm font-semibold">
                        <i class="fas fa-edit {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>{{ __('Edit') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Hotel Card 2 -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="relative h-48">
                <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                     alt="Hotel" class="w-full h-full object-cover">
                <div class="absolute top-4 {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }} flex gap-2">
                    <span class="px-3 py-1 bg-white text-gray-900 rounded-full text-xs font-semibold">
                        <i class="fas fa-star text-yellow-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>4.9
                    </span>
                    <span class="px-3 py-1 bg-green-500 text-white rounded-full text-xs font-semibold">{{ __('Active') }}</span>
                </div>
                <div class="absolute bottom-4 {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }}">
                    <div class="text-white text-xl font-bold">{{ __('Comfort & Relaxation Hotel') }}</div>
                    <div class="text-white/90 text-sm flex items-center gap-1">
                        <i class="fas fa-map-marker-alt"></i>{{ __('Jeddah') }}
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Rooms') }}</div>
                        <div class="text-lg font-bold text-gray-900 dark:text-white">98</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Available') }}</div>
                        <div class="text-lg font-bold text-green-600 dark:text-green-400">72</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Occupied') }}</div>
                        <div class="text-lg font-bold text-orange-600 dark:text-orange-400">26</div>
                    </div>
                </div>
                
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="px-2 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs rounded-lg">
                        <i class="fas fa-wifi {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>{{ __('WiFi') }}
                    </span>
                    <span class="px-2 py-1 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs rounded-lg">
                        <i class="fas fa-spa {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>{{ __('Spa') }}
                    </span>
                    <span class="px-2 py-1 bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 text-xs rounded-lg">
                        <i class="fas fa-dumbbell {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>{{ __('Gym') }}
                    </span>
                </div>

                <div class="flex items-center gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button class="flex-1 px-4 py-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition text-sm font-semibold">
                        <i class="fas fa-eye {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>{{ __('View') }}
                    </button>
                    <button class="flex-1 px-4 py-2 bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900/50 transition text-sm font-semibold">
                        <i class="fas fa-edit {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>{{ __('Edit') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Hotel Card 3 -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="relative h-48">
                <img src="https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                     alt="Hotel" class="w-full h-full object-cover">
                <div class="absolute top-4 {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }} flex gap-2">
                    <span class="px-3 py-1 bg-white text-gray-900 rounded-full text-xs font-semibold">
                        <i class="fas fa-star text-yellow-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>4.7
                    </span>
                    <span class="px-3 py-1 bg-green-500 text-white rounded-full text-xs font-semibold">{{ __('Active') }}</span>
                </div>
                <div class="absolute bottom-4 {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }}">
                    <div class="text-white text-xl font-bold">{{ __('Premium Stay Hotel') }}</div>
                    <div class="text-white/90 text-sm flex items-center gap-1">
                        <i class="fas fa-map-marker-alt"></i>{{ __('Dubai') }}
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Rooms') }}</div>
                        <div class="text-lg font-bold text-gray-900 dark:text-white">156</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Available') }}</div>
                        <div class="text-lg font-bold text-green-600 dark:text-green-400">112</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Occupied') }}</div>
                        <div class="text-lg font-bold text-orange-600 dark:text-orange-400">44</div>
                    </div>
                </div>
                
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="px-2 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs rounded-lg">
                        <i class="fas fa-wifi {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>{{ __('WiFi') }}
                    </span>
                    <span class="px-2 py-1 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs rounded-lg">
                        <i class="fas fa-swimming-pool {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>{{ __('Pool') }}
                    </span>
                    <span class="px-2 py-1 bg-orange-50 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 text-xs rounded-lg">
                        <i class="fas fa-car {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>{{ __('Parking') }}
                    </span>
                </div>

                <div class="flex items-center gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button class="flex-1 px-4 py-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition text-sm font-semibold">
                        <i class="fas fa-eye {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>{{ __('View') }}
                    </button>
                    <button class="flex-1 px-4 py-2 bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900/50 transition text-sm font-semibold">
                        <i class="fas fa-edit {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>{{ __('Edit') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

