@extends('Web.layouts.app')

@section('title', __('Privacy Policy') . ' - ABRAJ STAY')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-900 to-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ __('Privacy Policy') }}</h1>
            <p class="text-blue-100 text-lg max-w-2xl mx-auto">
                {{ __('Your privacy is our priority') }}
            </p>
        </div>
    </section>

    <!-- Content Section -->
    <section class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose prose-lg dark:prose-invert max-w-none text-gray-600 dark:text-gray-400">

                <div
                    class="mb-12 p-6 bg-blue-50 dark:bg-blue-900/30 rounded-2xl border border-blue-100 dark:border-blue-800">
                    <p class="text-lg text-blue-900 dark:text-blue-100 leading-relaxed font-semibold">
                        {{ __('Terms_Privacy_Welcome') }}
                    </p>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">8.
                    {{ __('Privacy Policy and Data Protection') }}</h2>
                <p class="mb-8">
                    {{ __('Privacy_Status') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ __('Data we collect:') }}</h2>
                <ul class="list-disc {{ app()->getLocale() === 'ar' ? 'pr-6' : 'pl-6' }} mb-8 space-y-2">
                    <li>{{ __('Data_Collect_1') }}</li>
                    <li>{{ __('Data_Collect_2') }}</li>
                    <li>{{ __('Data_Collect_3') }}</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ __('Use of Data:') }}</h2>
                <ul class="list-disc {{ app()->getLocale() === 'ar' ? 'pr-6' : 'pl-6' }} mb-8 space-y-2">
                    <li>{{ __('Data_Use_1') }}</li>
                    <li>{{ __('Data_Use_2') }}</li>
                    <li>{{ __('Data_Use_3') }}</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ __('Sharing of Data:') }}</h2>
                <p class="mb-4">{{ __('Data_Sharing_Intro') }}</p>
                <ul class="list-disc {{ app()->getLocale() === 'ar' ? 'pr-6' : 'pl-6' }} mb-8 space-y-2">
                    <li>{{ __('Data_Sharing_1') }}</li>
                    <li>{{ __('Data_Sharing_2') }}</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ __('Data Security') }}</h2>
                <p class="mb-12">
                    {{ __('Data_Security_Content') }}
                </p>

                <div class="mt-16 pt-12 border-t border-gray-200 dark:border-gray-700 text-center">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                        {{ __('Thank you for trusting Abraj Stay!') }}
                    </h2>

                    <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-2xl mx-auto">
                        {{ __('By using our site or services, you agree to the terms of this Privacy Policy. Please review this policy periodically for updates or changes.') }}
                    </p>

                    <p class="text-sm text-gray-500 mb-12">
                        {{ __('Effective Date') }}: 01.01.2025 | {{ __('Last Updated') }}: 18.02.2025
                    </p>

                    <div class="mt-12 text-gray-600 dark:text-gray-400">
                        <p class="mb-1 italic">{{ __('Best regards,') }}</p>
                        <p class="font-bold text-gray-900 dark:text-white">{{ __('Abraj Stay Team') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
