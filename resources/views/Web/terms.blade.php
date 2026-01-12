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

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">1. {{ __('Introduction') }}</h2>
                <p class="mb-8">
                    {{ __('Welcome to Abraj Stay ("we," "our," "us"). By using our website (https://www.abrajstay.com/), mobile application, or any related services (collectively, the "Platform"), you agree to comply with and be bound by these Terms and Conditions ("Terms"). If you do not agree with these Terms, please do not use our services.') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">2. {{ __('Services Provided') }}</h2>
                <p class="mb-8">
                    {{ __('Our Platform enables users ("Customers") to search, compare, and book accommodations (hotels, apartments, resorts, etc.) provided by third-party suppliers ("Providers"). We act as an intermediary, facilitating reservations but do not own, manage, or control any accommodations listed.') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">3. {{ __('User Eligibility') }}</h2>
                <ul class="list-disc {{ app()->getLocale() === 'ar' ? 'pr-6' : 'pl-6' }} mb-8 space-y-2">
                    <li>{{ __('You must be at least 18 years old to use our services.') }}</li>
                    <li>{{ __('You are responsible for maintaining the confidentiality of your account credentials.') }}
                    </li>
                    <li>{{ __('You agree to provide accurate, current, and complete information when making a reservation.') }}
                    </li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">4. {{ __('Reservations and Payments') }}
                </h2>
                <p class="mb-4">
                    {{ __('When you book an accommodation, you enter into a direct contract with the Provider. We facilitate the reservation process but are not a party to the agreement.') }}
                </p>
                <p class="mb-4">
                    {{ __('Payment terms, cancellation policies, and refund eligibility vary by Provider. It is your responsibility to review these before booking.') }}
                </p>
                <p class="mb-8">
                    {{ __('We may process payments on behalf of Providers, but liability for the booking remains with them.') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">5. {{ __('Cancellations and Refunds') }}
                </h2>
                <ul class="list-disc {{ app()->getLocale() === 'ar' ? 'pr-6' : 'pl-6' }} mb-8 space-y-2">
                    <li>{{ __('Cancellation policies depend on the Provider\'s terms. Some bookings may be non-refundable.') }}
                    </li>
                    <li>{{ __('Refunds, if applicable, will be processed based on the Provider\'s policy and may take up to [X] business days.') }}
                    </li>
                    <li>{{ __('Service fees charged by Abraj Stay may be non-refundable.') }}</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">6. {{ __('Pricing and Taxes') }}</h2>
                <p class="mb-4">
                    {{ __('Prices displayed on our Platform are set by Providers and may include taxes, service fees, or additional charges.') }}
                </p>
                <p class="mb-8">
                    {{ __('We strive to ensure accurate pricing, but errors may occur. In such cases, we reserve the right to cancel the booking and offer an alternative or full refund.') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">7. {{ __('User Responsibilities') }}</h2>
                <ul class="list-disc {{ app()->getLocale() === 'ar' ? 'pr-6' : 'pl-6' }} mb-8 space-y-2">
                    <li>{{ __('You agree to use the Platform in a lawful manner and not engage in fraudulent, abusive, or unauthorized activities.') }}
                    </li>
                    <li>{{ __('You are responsible for complying with the Provider\'s rules and policies during your stay.') }}
                    </li>
                    <li>{{ __('You must not post false reviews, manipulate ratings, or misrepresent your experience.') }}
                    </li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">8. {{ __('Liability Disclaimer') }}</h2>
                <p class="mb-4">
                    {{ __('We do not guarantee the availability, quality, or suitability of accommodations listed on our Platform.') }}
                </p>
                <p class="mb-4">
                    {{ __('We are not liable for damages, losses, or expenses arising from your stay, including but not limited to booking errors, cancellations, property conditions, or safety issues.') }}
                </p>
                <p class="mb-8">
                    {{ __('Providers are solely responsible for the services they offer, and any claims must be directed to them.') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">9. {{ __('Intellectual Property') }}</h2>
                <p class="mb-8">
                    {{ __('All content on the Platform (logos, trademarks, text, images, software) is owned by Abraj Stay or licensed to us. You may not use, copy, or distribute any content without permission.') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">10. {{ __('Modifications to Terms') }}
                </h2>
                <p class="mb-8">
                    {{ __('We reserve the right to update or modify these Terms at any time. Continued use of the Platform after changes take effect constitutes acceptance of the updated Terms.') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">11.
                    {{ __('Governing Law & Dispute Resolution') }}</h2>
                <p class="mb-8">
                    {{ __('These Terms are governed by the laws of [Jurisdiction]. Any disputes shall be resolved through arbitration or courts in [Jurisdiction].') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">12. {{ __('Contact Us') }}</h2>
                <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700">
                    <p class="mb-2"><strong>{{ __('Email:') }}</strong> <a href="mailto:info@abrajstay.com"
                            class="text-orange-500 hover:text-orange-600">info@abrajstay.com</a></p>
                    <p class="mb-2"><strong>{{ __('Phone:') }}</strong> <a href="tel:+966920015728" dir="ltr"
                            class="text-orange-500 hover:text-orange-600">+966 9200 15728</a></p>
                    <p><strong>{{ __('Address:') }}</strong>
                        {{ __('Spring Towers, Prince Mohammed Ibn Salman Ibn Abdulaziz Rd, Riyadh, Saudi Arabia') }}</p>
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
                        <p class="text-sm opacity-75 mt-2">
                            {{ __('Your trusted partner for comprehensive travel solutions, from flights and hotels to specialized services.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
