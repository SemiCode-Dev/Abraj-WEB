@extends('Admin.layouts.app')

@section('title', __('Dashboard'))
@section('page-title', __('Dashboard'))

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Dashboard') }}</h1>
        <p class="text-gray-600 dark:text-gray-400">{{ __('Welcome back! Here\'s what\'s happening today.') }}</p>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex items-center justify-end gap-3 mb-6">
        <button class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition flex items-center gap-2">
            <i class="fas fa-download"></i>{{ __('Export') }}
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Bookings -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg transform hover:scale-105 transition duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-check text-2xl"></i>
                </div>
                <span class="text-sm bg-white/20 px-3 py-1 rounded-full">+12%</span>
            </div>
            <div class="text-3xl font-bold mb-1">1,247</div>
            <div class="text-blue-100 text-sm">{{ __('Total Bookings') }}</div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 text-white shadow-lg transform hover:scale-105 transition duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-2xl"></i>
                </div>
                <span class="text-sm bg-white/20 px-3 py-1 rounded-full">+23%</span>
            </div>
            <div class="text-3xl font-bold mb-1">SAR 2.4M</div>
            <div class="text-orange-100 text-sm">{{ __('Total Revenue') }}</div>
        </div>

        <!-- Total Users -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg transform hover:scale-105 transition duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <span class="text-sm bg-white/20 px-3 py-1 rounded-full">+15%</span>
            </div>
            <div class="text-3xl font-bold mb-1">8,924</div>
            <div class="text-purple-100 text-sm">{{ __('Total Users') }}</div>
        </div>

        <!-- Unread Reports -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-6 text-white shadow-lg transform hover:scale-105 transition duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
                <a href="{{ route('admin.reports') }}" class="text-sm bg-white/20 px-3 py-1 rounded-full hover:bg-white/30 transition">View</a>
            </div>
            <div class="text-3xl font-bold mb-1">45</div>
            <div class="text-red-100 text-sm">{{ __('Unread Reports') }}</div>
        </div>
    </div>

    <!-- Charts and Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Revenue Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Revenue Overview') }}</h2>
                <select class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm">
                    <option>{{ __('Last 7 Days') }}</option>
                    <option>{{ __('Last 30 Days') }}</option>
                    <option>{{ __('Last 3 Months') }}</option>
                </select>
            </div>
            <div class="h-64 flex items-end justify-between gap-2">
                <div class="flex-1 flex flex-col items-center group cursor-pointer">
                    <div class="w-full bg-gradient-to-t from-blue-500 to-blue-400 rounded-t-lg mb-2 group-hover:from-blue-600 group-hover:to-blue-500 transition" style="height: 45%;"></div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Mon</span>
                </div>
                <div class="flex-1 flex flex-col items-center group cursor-pointer">
                    <div class="w-full bg-gradient-to-t from-blue-500 to-blue-400 rounded-t-lg mb-2 group-hover:from-blue-600 group-hover:to-blue-500 transition" style="height: 65%;"></div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Tue</span>
                </div>
                <div class="flex-1 flex flex-col items-center group cursor-pointer">
                    <div class="w-full bg-gradient-to-t from-blue-500 to-blue-400 rounded-t-lg mb-2 group-hover:from-blue-600 group-hover:to-blue-500 transition" style="height: 80%;"></div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Wed</span>
                </div>
                <div class="flex-1 flex flex-col items-center group cursor-pointer">
                    <div class="w-full bg-gradient-to-t from-blue-500 to-blue-400 rounded-t-lg mb-2 group-hover:from-blue-600 group-hover:to-blue-500 transition" style="height: 55%;"></div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Thu</span>
                </div>
                <div class="flex-1 flex flex-col items-center group cursor-pointer">
                    <div class="w-full bg-gradient-to-t from-orange-500 to-orange-400 rounded-t-lg mb-2 group-hover:from-orange-600 group-hover:to-orange-500 transition" style="height: 90%;"></div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Fri</span>
                </div>
                <div class="flex-1 flex flex-col items-center group cursor-pointer">
                    <div class="w-full bg-gradient-to-t from-orange-500 to-orange-400 rounded-t-lg mb-2 group-hover:from-orange-600 group-hover:to-orange-500 transition" style="height: 75%;"></div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Sat</span>
                </div>
                <div class="flex-1 flex flex-col items-center group cursor-pointer">
                    <div class="w-full bg-gradient-to-t from-green-500 to-green-400 rounded-t-lg mb-2 group-hover:from-green-600 group-hover:to-green-500 transition" style="height: 100%;"></div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Sun</span>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">{{ __('Recent Activity') }}</h2>
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-calendar-check text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">New booking created</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">2 minutes ago</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Payment received</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">15 minutes ago</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user-plus text-orange-600 dark:text-orange-400"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">New user registered</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">1 hour ago</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-bed text-purple-600 dark:text-purple-400"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Room status updated</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">2 hours ago</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Recent Bookings') }}</h2>
            <a href="{{ route('admin.bookings') }}" class="text-orange-600 dark:text-orange-400 hover:text-orange-700 dark:hover:text-orange-300 text-sm font-semibold">
                {{ __('View All') }} <i class="fas fa-arrow-left {{ app()->getLocale() === 'ar' ? 'mr-1' : 'ml-1' }}"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400">{{ __('Booking ID') }}</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400">{{ __('Guest') }}</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400">{{ __('Hotel') }}</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400">{{ __('Check In') }}</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400">{{ __('Check Out') }}</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400">{{ __('Amount') }}</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400">{{ __('Status') }}</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="py-4 px-4">
                            <span class="font-mono text-sm font-semibold text-gray-900 dark:text-white">#BK-2024-001</span>
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white text-xs font-bold">A</div>
                                <span class="text-sm text-gray-900 dark:text-white">أحمد محمد</span>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-sm text-gray-900 dark:text-white">{{ __('International Luxury Hotel') }}</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-sm text-gray-600 dark:text-gray-400">2024-01-15</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-sm text-gray-600 dark:text-gray-400">2024-01-18</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">SAR 1,050</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-xs font-semibold">{{ __('Confirmed') }}</span>
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-2">
                                <button class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="p-2 text-orange-600 dark:text-orange-400 hover:bg-orange-50 dark:hover:bg-orange-900/30 rounded-lg transition">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="py-4 px-4">
                            <span class="font-mono text-sm font-semibold text-gray-900 dark:text-white">#BK-2024-002</span>
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-pink-500 to-pink-600 rounded-lg flex items-center justify-center text-white text-xs font-bold">س</div>
                                <span class="text-sm text-gray-900 dark:text-white">سارة علي</span>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-sm text-gray-900 dark:text-white">{{ __('Comfort & Relaxation Hotel') }}</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-sm text-gray-600 dark:text-gray-400">2024-01-20</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-sm text-gray-600 dark:text-gray-400">2024-01-22</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">SAR 840</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded-full text-xs font-semibold">{{ __('Pending') }}</span>
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-2">
                                <button class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="p-2 text-orange-600 dark:text-orange-400 hover:bg-orange-50 dark:hover:bg-orange-900/30 rounded-lg transition">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="py-4 px-4">
                            <span class="font-mono text-sm font-semibold text-gray-900 dark:text-white">#BK-2024-003</span>
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center text-white text-xs font-bold">خ</div>
                                <span class="text-sm text-gray-900 dark:text-white">خالد أحمد</span>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-sm text-gray-900 dark:text-white">{{ __('Premium Stay Hotel') }}</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-sm text-gray-600 dark:text-gray-400">2024-01-25</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-sm text-gray-600 dark:text-gray-400">2024-01-28</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">SAR 1,260</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-xs font-semibold">{{ __('Confirmed') }}</span>
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-2">
                                <button class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="p-2 text-orange-600 dark:text-orange-400 hover:bg-orange-50 dark:hover:bg-orange-900/30 rounded-lg transition">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

