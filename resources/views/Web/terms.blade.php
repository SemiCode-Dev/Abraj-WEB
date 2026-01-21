@extends('Web.layouts.app')

@section('title', __('Terms and Conditions') . ' - ABRAJ STAY')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-900 to-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ __('Terms and Conditions') }}</h1>
            <p class="text-blue-100 text-lg max-w-2xl mx-auto">
                {{ __('Please read these terms carefully') }}
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

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">1. {{ __('Platform Definition') }}</h2>
                <p class="mb-4">
                    {{ __('Terms_Intro') }}
                </p>
                <ul class="list-disc {{ app()->getLocale() === 'ar' ? 'pr-6' : 'pl-6' }} mb-4 space-y-2">
                    <li>{{ __('Browse hotels and accommodation facilities') }}</li>
                    <li>{{ __('Make accommodation bookings') }}</li>
                    <li>{{ __('Benefit from additional services such as requesting taxis') }}</li>
                </ul>
                <p class="mb-8">
                    {{ __('The platform acts as a technical intermediary between the user and service providers (hotels and service providers).') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">2. {{ __('Acceptance of Terms') }}</h2>
                <p class="mb-8">
                    {{ __('Acceptance_Content') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">3. {{ __('Amendments to Terms') }}</h2>
                <p class="mb-8">
                    {{ __('Amendments_Content') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">4. {{ __('Registration Conditions') }}
                </h2>
                <p class="mb-4">{{ __('Registration_Intro') }}</p>
                <ul class="list-disc {{ app()->getLocale() === 'ar' ? 'pr-6' : 'pl-6' }} mb-8 space-y-2">
                    <li>{{ __('Registration_Cond_1') }}</li>
                    <li>{{ __('Registration_Cond_2') }}</li>
                    <li>{{ __('Registration_Cond_3') }}</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">5. {{ __('Booking Policy') }}</h2>
                <ul class="list-disc {{ app()->getLocale() === 'ar' ? 'pr-6' : 'pl-6' }} mb-8 space-y-2">
                    <li>{{ __('Booking_Policy_1') }}</li>
                    <li>{{ __('Booking_Policy_2') }}</li>
                    <li>{{ __('Booking_Policy_3') }}</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">6.
                    {{ __('Cancellation and Refund (Compatible with Sadad)') }}</h2>
                <p class="mb-4">
                    {{ __('Cancellation_Intro') }}
                </p>

                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('First: Return Options') }}</h3>
                <ul class="list-disc {{ app()->getLocale() === 'ar' ? 'pr-6' : 'pl-6' }} mb-4 space-y-2">
                    <li>{{ __('Return_Options_1') }}</li>
                    <li>{{ __('Return_Options_2') }}</li>
                </ul>

                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Cash_Refund_Controls_Intro') }}
                </h3>
                <p class="mb-4">
                    {{ __('Cash_Refund_Controls_Content') }}
                </p>

                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                    {{ __('Cancellation_by_Platform_Intro') }}</h3>
                <p class="mb-4">
                    {{ __('Cancellation_by_Platform_Content') }}
                </p>

                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Violation_of_Policies_Intro') }}
                </h3>
                <p class="mb-8">
                    {{ __('Violation_of_Policies_Content') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">7.
                    {{ __('Prices and Payment (Sadad Requirements)') }}</h2>
                <ul class="list-disc {{ app()->getLocale() === 'ar' ? 'pr-6' : 'pl-6' }} mb-8 space-y-2">
                    <li>{{ __('Prices_Payment_1') }}</li>
                    <li>{{ __('Prices_Payment_2') }}</li>
                    <li>{{ __('Prices_Payment_3') }}</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">8.
                    {{ __('Privacy Policy and Data Protection') }}</h2>
                <p class="mb-8">
                    {{ __('Privacy_Status') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">9. {{ __('Intellectual Property') }}</h2>
                <p class="mb-8">
                    {{ __('IP_Content') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">10. {{ __('User Content') }}</h2>
                <p class="mb-8">
                    {{ __('User_Content_Policy') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">11. {{ __('Limitation of Liability') }}
                </h2>
                <p class="mb-8">
                    {{ __('Liability_Content') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">12. {{ __('Law and Jurisdiction') }}</h2>
                <p class="mb-8">
                    {{ __('Jurisdiction_Content') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">13. {{ __('Contact Us') }}</h2>
                <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 mb-8">
                    <p class="mb-2"><strong>{{ __('Email:') }}</strong> <a href="mailto:info@abrajstay.com"
                            class="text-orange-500 hover:text-orange-600">info@abrajstay.com</a></p>
                    <p class="mb-2"><strong>{{ __('Phone:') }}</strong> <a href="tel:+966920015728" dir="ltr"
                            class="text-orange-500 hover:text-orange-600">+966 9200 15728</a></p>
                    <p><strong>{{ __('Address:') }}</strong>
                        {{ __('Spring Towers, Prince Mohammed Ibn Salman Ibn Abdulaziz Rd, Riyadh, Saudi Arabia, Zip Code: 13316') }}
                    </p>
                </div>

                <div class="mt-16 pt-12 border-t border-gray-200 dark:border-gray-700 text-center">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                        {{ __('Thank you for choosing Abraj Stay!') }}
                    </h2>

                    <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-2xl mx-auto">
                        {{ __('By using our website or services, you consent to the terms of this agreement. Please review this policy periodically for updates or changes.') }}
                    </p>

                    <div class="mt-4 text-gray-600 dark:text-gray-400">
                        <p class="mb-1 italic">{{ __('Best regards,') }}</p>
                        <p class="font-bold text-gray-900 dark:text-white">{{ __('Abraj Stay Team') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
