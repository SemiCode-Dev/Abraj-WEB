@extends('Web.layouts.app')

@section('title', __('Requests') . ' - ABRAJ STAY')

@section('content')
<section class="bg-gradient-to-r from-orange-500 via-orange-600 to-blue-900 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ __('My Requests') }}</h1>
        <p class="text-orange-100 text-lg max-w-2xl mx-auto">
            {{ __('View and manage your booking requests') }}
        </p>
    </div>
</section>

<section class="py-16 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-list text-3xl text-orange-600 dark:text-orange-400"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ __('No Requests Yet') }}</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    {{ __('Your booking requests will appear here once you make a reservation.') }}
                </p>
                <a href="{{ route('hotels.search', ['locale' => app()->getLocale()]) }}" 
                   class="inline-block bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 py-3 rounded-xl hover:from-orange-600 hover:to-orange-700 transition font-semibold shadow-lg">
                    {{ __('Browse Hotels') }}
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

