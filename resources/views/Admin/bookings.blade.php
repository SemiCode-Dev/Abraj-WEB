@extends('Admin.layouts.app')

@section('title', __('Bookings Management'))

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Bookings Management') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Manage and track all hotel bookings') }}</p>
            </div>
            <div class="flex gap-3">
                <button
                    class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <i class="fas fa-filter {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('Filter') }}
                </button>
                <button
                    class="px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition shadow-lg">
                    <i class="fas fa-plus {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('New Booking') }}
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Bookings') }}</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                            {{ number_format($stats['total']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-check text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Confirmed') }}</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">
                            {{ number_format($stats['confirmed']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Pending') }}</p>
                        <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">
                            {{ number_format($stats['pending']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Cancelled') }}</p>
                        <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">
                            {{ number_format($stats['cancelled']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times-circle text-red-600 dark:text-red-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <form action="{{ route('admin.bookings') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('Search') }}</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="{{ __('Booking ID, Guest name...') }}"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>
                <div>
                    <label
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('Status') }}</label>
                    <select name="status"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="All Status">{{ __('All Status') }}</option>
                        <option value="CONFIRMED" {{ request('status') == 'CONFIRMED' ? 'selected' : '' }}>
                            {{ __('Confirmed') }}</option>
                        <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>{{ __('Pending') }}
                        </option>
                        <option value="CANCELLED" {{ request('status') == 'CANCELLED' ? 'selected' : '' }}>
                            {{ __('Cancelled') }}</option>
                        <option value="FAILED" {{ request('status') == 'FAILED' ? 'selected' : '' }}>{{ __('Failed') }}
                        </option>
                    </select>
                </div>
                <div>
                    <label
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('Check In Date') }}</label>
                    <input type="date" name="date" value="{{ request('date') }}"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition">
                        {{ __('Apply Filters') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Bookings Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <input type="checkbox"
                                    class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                            </th>
                            <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ __('Booking ID') }}</th>
                            <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ __('Guest') }}</th>
                            <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ __('Hotel') }}</th>
                            <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ __('Room') }}</th>
                            <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ __('Check In') }}</th>
                            <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ __('Check Out') }}</th>
                            <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ __('Amount') }}</th>
                            <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ __('Status') }}</th>
                            <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($bookings as $booking)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="py-4 px-6">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        class="font-mono text-sm font-semibold text-gray-900 dark:text-white">#{{ $booking->booking_reference }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        @php
                                            $initial = mb_substr($booking->guest_name, 0, 1);
                                        @endphp
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white text-sm font-bold">
                                            {{ $initial }}</div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $booking->guest_name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $booking->guest_email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $booking->hotel_name }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-sm text-gray-900 dark:text-white">{{ $booking->room_name }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $booking->check_in->format('Y-m-d') }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $booking->check_out->format('Y-m-d') }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $booking->currency }}
                                        {{ number_format($booking->total_price, 2) }}</div>
                                    <div
                                        class="text-xs {{ $booking->payment_status === 'PAID' ? 'text-green-600' : 'text-yellow-600' }}">
                                        {{ $booking->payment_status }}
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    @if ($booking->booking_status === 'CONFIRMED')
                                        <span
                                            class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-xs font-semibold">{{ __('Confirmed') }}</span>
                                    @elseif($booking->booking_status === 'PENDING')
                                        <span
                                            class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded-full text-xs font-semibold">{{ __('Pending') }}</span>
                                    @elseif($booking->booking_status === 'CANCELLED')
                                        <span
                                            class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-full text-xs font-semibold">{{ __('Cancelled') }}</span>
                                    @else
                                        <span
                                            class="px-3 py-1 bg-gray-100 dark:bg-gray-900/30 text-gray-700 dark:text-gray-400 rounded-full text-xs font-semibold">{{ $booking->booking_status }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2">
                                        <button
                                            class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition"
                                            title="{{ __('View') }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button
                                            class="p-2 text-orange-600 dark:text-orange-400 hover:bg-orange-50 dark:hover:bg-orange-900/30 rounded-lg transition"
                                            title="{{ __('Edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-8 px-6 text-center text-gray-500">
                                    {{ __('No bookings found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Showing') }} <span class="font-semibold">{{ $bookings->firstItem() ?: 0 }}</span>
                    {{ __('to') }} <span class="font-semibold">{{ $bookings->lastItem() ?: 0 }}</span>
                    {{ __('of') }} <span class="font-semibold">{{ number_format($bookings->total()) }}</span>
                    {{ __('results') }}
                </div>
                <div class="flex gap-2">
                    {{ $bookings->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>
@endsection
