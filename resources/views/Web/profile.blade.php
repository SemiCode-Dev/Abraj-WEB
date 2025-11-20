@extends('Web.layouts.app')

@section('title', __('Profile') . ' - ABRAJ STAY')

@section('content')
<section class="bg-gradient-to-r from-orange-500 via-orange-600 to-blue-900 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ __('Profile') }}</h1>
        <p class="text-orange-100 text-lg max-w-2xl mx-auto">
            {{ __('Manage your account settings and preferences') }}
        </p>
    </div>
</section>

<section class="py-16 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
            <div class="text-center mb-8">
                @auth
                    @if(auth()->user()->avatar ?? false)
                        <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-24 h-24 rounded-full mx-auto border-4 border-orange-500 mb-4">
                    @else
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white text-3xl font-bold mx-auto border-4 border-orange-500 mb-4">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                    @endif
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ auth()->user()->name ?? __('User') }}</h2>
                    <p class="text-gray-600 dark:text-gray-400">{{ auth()->user()->email ?? '' }}</p>
                @else
                    <div class="w-24 h-24 rounded-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user text-4xl text-gray-500 dark:text-gray-400"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('Guest User') }}</h2>
                @endauth
            </div>

            <div class="space-y-6">
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ __('Profile Information') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        {{ __('Profile page is under development. Backend functionality will be implemented soon.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

