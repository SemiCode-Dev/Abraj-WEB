@extends('Admin.layouts.app')

@section('title', __('View User'))

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('User Details') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('View user information and activity') }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('Back') }}
            </a>
            <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition shadow-lg">
                <i class="fas fa-edit {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('Edit') }}
            </a>
        </div>
    </div>

    <!-- User Information Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <div class="flex items-start gap-6 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
            @if($user->image)
                <img src="{{ $user->image }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-xl object-cover">
            @else
                <div class="w-24 h-24 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center text-white text-3xl font-bold">
                    {{ substr($user->name, 0, 1) }}
                </div>
            @endif
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $user->name }}</h2>
                <div class="flex flex-wrap gap-2 mb-2">
                    @if($user->is_admin)
                        <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 rounded-full text-xs font-semibold">{{ __('Admin') }}</span>
                    @else
                        <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-full text-xs font-semibold">{{ __('Client') }}</span>
                    @endif
                    @if($user->status === 'active')
                        <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-xs font-semibold">{{ __('Active') }}</span>
                    @elseif($user->status === 'inactive')
                        <span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded-full text-xs font-semibold">{{ __('Inactive') }}</span>
                    @elseif($user->status === 'blocked')
                        <span class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-full text-xs font-semibold">{{ __('Blocked') }}</span>
                    @endif
                    @if($user->email_verified_at)
                        <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-xs font-semibold">{{ __('Verified') }}</span>
                    @else
                        <span class="px-3 py-1 bg-gray-100 dark:bg-gray-900/30 text-gray-700 dark:text-gray-400 rounded-full text-xs font-semibold">{{ __('Not Verified') }}</span>
                    @endif
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('User ID') }}: #US-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Contact Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Contact Information') }}</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-400">{{ __('Email') }}</label>
                        <p class="text-gray-900 dark:text-white font-medium">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-400">{{ __('Phone') }}</label>
                        <p class="text-gray-900 dark:text-white font-medium">{{ $user->phone ?? __('N/A') }}</p>
                    </div>
                    @if($user->phone_country_code)
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-400">{{ __('Country Code') }}</label>
                        <p class="text-gray-900 dark:text-white font-medium">{{ $user->phone_country_code }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Account Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Account Information') }}</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-400">{{ __('Status') }}</label>
                        <p class="text-gray-900 dark:text-white font-medium">
                            @if($user->status === 'active')
                                <span class="text-green-600 dark:text-green-400">{{ __('Active') }}</span>
                            @elseif($user->status === 'inactive')
                                <span class="text-yellow-600 dark:text-yellow-400">{{ __('Inactive') }}</span>
                            @elseif($user->status === 'blocked')
                                <span class="text-red-600 dark:text-red-400">{{ __('Blocked') }}</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-400">{{ __('Role') }}</label>
                        <p class="text-gray-900 dark:text-white font-medium">
                            @if($user->is_admin)
                                <span class="text-purple-600 dark:text-purple-400">{{ __('Admin') }}</span>
                            @else
                                <span class="text-blue-600 dark:text-blue-400">{{ __('Client') }}</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-400">{{ __('Email Verified') }}</label>
                        <p class="text-gray-900 dark:text-white font-medium">
                            @if($user->email_verified_at)
                                <span class="text-green-600 dark:text-green-400">{{ __('Yes') }} - {{ $user->email_verified_at->format('Y-m-d H:i') }}</span>
                            @else
                                <span class="text-gray-600 dark:text-gray-400">{{ __('No') }}</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-400">{{ __('Joined Date') }}</label>
                        <p class="text-gray-900 dark:text-white font-medium">{{ $user->created_at->format('Y-m-d H:i') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->created_at->diffForHumans() }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-400">{{ __('Last Updated') }}</label>
                        <p class="text-gray-900 dark:text-white font-medium">{{ $user->updated_at->format('Y-m-d H:i') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Statistics') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Bookings') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">0</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Completed Bookings') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">0</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Spent') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">$0</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
