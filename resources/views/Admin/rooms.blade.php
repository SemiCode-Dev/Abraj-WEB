@extends('Admin.layouts.app')

@section('title', __('Rooms Management'))

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Rooms Management') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Manage hotel rooms and availability') }}</p>
            </div>
            <div class="flex gap-3">
                <button
                    class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <i class="fas fa-download {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('Export') }}
                </button>
                <button
                    class="px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition shadow-lg">
                    <i class="fas fa-plus {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('Add Room') }}
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Rooms') }}</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">342</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-bed text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Available') }}</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">198</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Occupied') }}</p>
                        <p class="text-2xl font-bold text-orange-600 dark:text-orange-400 mt-1">124</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-check text-orange-600 dark:text-orange-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Maintenance') }}</p>
                        <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">20</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-tools text-red-600 dark:text-red-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('Search') }}</label>
                    <input type="text" placeholder="{{ __('Room number, type...') }}"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>
                <div>
                    <label
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('Hotel') }}</label>
                    <select
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option>{{ __('All Hotels') }}</option>
                        <option>{{ __('International Luxury Hotel') }}</option>
                        <option>{{ __('Comfort & Relaxation Hotel') }}</option>
                    </select>
                </div>
                <div>
                    <label
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('Status') }}</label>
                    <select
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option>{{ __('All Status') }}</option>
                        <option>{{ __('Available') }}</option>
                        <option>{{ __('Occupied') }}</option>
                        <option>{{ __('Maintenance') }}</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button
                        class="w-full px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition">
                        {{ __('Apply Filters') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Rooms Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Room Card 1 -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="relative h-48 bg-gradient-to-br from-blue-500 to-blue-600">
                    <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                        alt="Room" class="w-full h-full object-cover opacity-80">
                    <div class="absolute top-4 {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }}">
                        <span
                            class="px-3 py-1 bg-green-500 text-white rounded-full text-xs font-semibold">{{ __('Available') }}</span>
                    </div>
                    <div class="absolute bottom-4 {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }}">
                        <div class="text-white text-2xl font-bold">Room 101</div>
                        <div class="text-white/90 text-sm">Deluxe Suite</div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Hotel') }}</div>
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ __('International Luxury Hotel') }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Price') }}</div>
                            <div class="text-lg font-bold text-orange-600 dark:text-orange-400">USD 350</div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2 mb-4">
                        <span
                            class="px-2 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs rounded-lg">
                            <i class="fas fa-bed {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>2
                            {{ __('Beds') }}
                        </span>
                        <span
                            class="px-2 py-1 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs rounded-lg">
                            <i class="fas fa-users {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>4
                            {{ __('Guests') }}
                        </span>
                        <span
                            class="px-2 py-1 bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 text-xs rounded-lg">
                            <i class="fas fa-expand {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>45 m²
                        </span>
                    </div>

                    <div class="flex items-center gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button
                            class="flex-1 px-4 py-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition text-sm font-semibold">
                            <i
                                class="fas fa-eye {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>{{ __('View') }}
                        </button>
                        <button
                            class="flex-1 px-4 py-2 bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900/50 transition text-sm font-semibold">
                            <i
                                class="fas fa-edit {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>{{ __('Edit') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Room Card 2 -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="relative h-48 bg-gradient-to-br from-green-500 to-green-600">
                    <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                        alt="Room" class="w-full h-full object-cover opacity-80">
                    <div class="absolute top-4 {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }}">
                        <span
                            class="px-3 py-1 bg-orange-500 text-white rounded-full text-xs font-semibold">{{ __('Occupied') }}</span>
                    </div>
                    <div class="absolute bottom-4 {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }}">
                        <div class="text-white text-2xl font-bold">Room 205</div>
                        <div class="text-white/90 text-sm">Standard Room</div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Hotel') }}</div>
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ __('Comfort & Relaxation Hotel') }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Price') }}</div>
                            <div class="text-lg font-bold text-orange-600 dark:text-orange-400">USD 280</div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2 mb-4">
                        <span
                            class="px-2 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs rounded-lg">
                            <i class="fas fa-bed {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>1
                            {{ __('Bed') }}
                        </span>
                        <span
                            class="px-2 py-1 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs rounded-lg">
                            <i class="fas fa-users {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>2
                            {{ __('Guests') }}
                        </span>
                        <span
                            class="px-2 py-1 bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 text-xs rounded-lg">
                            <i class="fas fa-expand {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>30 m²
                        </span>
                    </div>

                    <div class="mb-4 p-3 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                        <div class="text-xs text-gray-600 dark:text-gray-400 mb-1">{{ __('Current Guest') }}</div>
                        <div class="text-sm font-semibold text-gray-900 dark:text-white">سارة علي</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Check-out: 2024-01-22</div>
                    </div>

                    <div class="flex items-center gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button
                            class="flex-1 px-4 py-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition text-sm font-semibold">
                            <i
                                class="fas fa-eye {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>{{ __('View') }}
                        </button>
                        <button
                            class="flex-1 px-4 py-2 bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900/50 transition text-sm font-semibold">
                            <i
                                class="fas fa-edit {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>{{ __('Edit') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Room Card 3 -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="relative h-48 bg-gradient-to-br from-purple-500 to-purple-600">
                    <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                        alt="Room" class="w-full h-full object-cover opacity-80">
                    <div class="absolute top-4 {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }}">
                        <span
                            class="px-3 py-1 bg-red-500 text-white rounded-full text-xs font-semibold">{{ __('Maintenance') }}</span>
                    </div>
                    <div class="absolute bottom-4 {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }}">
                        <div class="text-white text-2xl font-bold">Room 310</div>
                        <div class="text-white/90 text-sm">Executive Suite</div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Hotel') }}</div>
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ __('Premium Stay Hotel') }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Price') }}</div>
                            <div class="text-lg font-bold text-orange-600 dark:text-orange-400">USD 420</div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2 mb-4">
                        <span
                            class="px-2 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs rounded-lg">
                            <i class="fas fa-bed {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>2
                            {{ __('Beds') }}
                        </span>
                        <span
                            class="px-2 py-1 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs rounded-lg">
                            <i class="fas fa-users {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>4
                            {{ __('Guests') }}
                        </span>
                        <span
                            class="px-2 py-1 bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 text-xs rounded-lg">
                            <i class="fas fa-expand {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>55 m²
                        </span>
                    </div>

                    <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                        <div class="text-xs text-gray-600 dark:text-gray-400 mb-1">{{ __('Maintenance Note') }}</div>
                        <div class="text-sm text-gray-900 dark:text-white">Under maintenance until 2024-01-20</div>
                    </div>

                    <div class="flex items-center gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button
                            class="flex-1 px-4 py-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition text-sm font-semibold">
                            <i
                                class="fas fa-eye {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>{{ __('View') }}
                        </button>
                        <button
                            class="flex-1 px-4 py-2 bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900/50 transition text-sm font-semibold">
                            <i
                                class="fas fa-edit {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>{{ __('Edit') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
