@extends('Admin.layouts.app')

@section('title', __('Users Management'))

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Users Management') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Manage user accounts and permissions') }}</p>
        </div>
        <div class="flex gap-3">
            <button class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <i class="fas fa-download {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('Export') }}
            </button>
            <button class="px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition shadow-lg">
                <i class="fas fa-plus {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('Add User') }}
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Users') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalUsers }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Active') }}</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $activeUsers }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-check text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('New This Month') }}</p>
                    <p class="text-2xl font-bold text-orange-600 dark:text-orange-400 mt-1">{{ $newThisMonth }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-plus text-orange-600 dark:text-orange-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Verified') }}</p>
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400 mt-1">{{ $verifiedUsers }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('Search') }}</label>
                <input type="text" placeholder="{{ __('Name, email, phone...') }}"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('Status') }}</label>
                <select class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <option>{{ __('All Status') }}</option>
                    <option>{{ __('Active') }}</option>
                    <option>{{ __('Inactive') }}</option>
                    <option>{{ __('Suspended') }}</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('Role') }}</label>
                <select class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <option>{{ __('All Roles') }}</option>
                    <option>{{ __('Customer') }}</option>
                    <option>{{ __('Admin') }}</option>
                    <option>{{ __('Manager') }}</option>
                </select>
            </div>
            <div class="flex items-end">
                <button class="w-full px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition">
                    {{ __('Apply Filters') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <input type="checkbox" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                        </th>
                        <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('User') }}</th>
                        <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Email') }}</th>
                        <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Phone') }}</th>
                        <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Role') }}</th>
                        <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Bookings') }}</th>
                        <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Status') }}</th>
                        <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Joined') }}</th>
                        <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="py-4 px-6">
                            <input type="checkbox" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                @if($user->image)
                                    <img src="{{ $user->image }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-lg object-cover">
                                @else
                                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center text-white text-sm font-bold">{{ substr($user->name, 0, 1) }}</div>
                                @endif
                                <div>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">ID: #US-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="text-sm text-gray-900 dark:text-white">{{ $user->email }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="text-sm text-gray-900 dark:text-white">{{ $user->phone ?? 'N/A' }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-full text-xs font-semibold">{{ __('Customer') }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">0</span>
                        </td>
                        <td class="py-4 px-6">
                            @if($user->email_verified_at)
                                <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-xs font-semibold">{{ __('Verified') }}</span>
                            @else
                                <span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded-full text-xs font-semibold">{{ __('Pending') }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $user->created_at->format('Y-m-d') }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <button class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition" title="{{ __('View') }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="p-2 text-orange-600 dark:text-orange-400 hover:bg-orange-50 dark:hover:bg-orange-900/30 rounded-lg transition" title="{{ __('Edit') }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition" title="{{ __('Suspend') }}">
                                    <i class="fas fa-ban"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-8 px-6 text-center text-gray-600 dark:text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                                <p class="text-lg font-semibold">{{ __('No users found') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                               
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $users->links() }}
        </div>
            </div>
            <div class="flex gap-2">
                <button class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition disabled:opacity-50" disabled>
                    <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}"></i>
                </button>
                <button class="px-4 py-2 bg-orange-600 text-white rounded-lg">1</button>
                <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">2</button>
                <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">3</button>
                <button class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

