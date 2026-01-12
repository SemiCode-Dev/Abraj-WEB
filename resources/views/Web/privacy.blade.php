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

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">1. {{ __('Introduction') }}</h2>
                <p class="mb-8">
                    {{ __('Welcome to Abraj Stay ("we," "our," "us"). Your privacy is important to us, and we are committed to protecting your personal data. This Privacy Policy explains how we collect, use, and protect your information when you use our website, mobile application, or any related services (collectively, the "Platform").') }}
                </p>
                <p class="mb-8">
                    {{ __('By using our services, you consent to the data practices described in this Privacy Policy. If you do not agree, please discontinue use of our services.') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">2. {{ __('Information We Collect') }}</h2>
                <p class="mb-4">
                    {{ __('We collect different types of information to improve your booking experience:') }}
                </p>

                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">A.
                    {{ __('Personal Information You Provide') }}</h3>
                <ul class="list-disc {{ app()->getLocale() === 'ar' ? 'pr-6' : 'pl-6' }} mb-6 space-y-2">
                    <li>{{ __('Name, email address, phone number, and billing information.') }}</li>
                    <li>{{ __('Booking details, including check-in and check-out dates.') }}</li>
                    <li>{{ __('Payment details (processed securely by third-party providers).') }}</li>
                    <li>{{ __('Communication preferences and customer support interactions.') }}</li>
                </ul>

                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">B.
                    {{ __('Information We Collect Automatically') }}</h3>
                <ul class="list-disc {{ app()->getLocale() === 'ar' ? 'pr-6' : 'pl-6' }} mb-6 space-y-2">
                    <li>{{ __('IP address, browser type, and device information.') }}</li>
                    <li>{{ __('Cookies and tracking technologies to enhance user experience.') }}</li>
                    <li>{{ __('Usage data, such as pages visited and time spent on our Platform.') }}</li>
                </ul>

                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">C.
                    {{ __('Information from Third Parties') }}</h3>
                <ul class="list-disc {{ app()->getLocale() === 'ar' ? 'pr-6' : 'pl-6' }} mb-8 space-y-2">
                    <li>{{ __('Booking partners, hotels, and payment processors may share information with us.') }}</li>
                    <li>{{ __('Social media platforms (if you choose to connect via social login).') }}</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">3.
                    {{ __('How We Use Your Information') }}</h2>
                <p class="mb-4">{{ __('We use your information for the following purposes:') }}</p>
                <ul class="list-disc {{ app()->getLocale() === 'ar' ? 'pr-6' : 'pl-6' }} mb-8 space-y-2">
                    <li><strong>{{ __('To process bookings') }}:</strong>
                        {{ __('Confirming reservations and providing customer support.') }}</li>
                    <li><strong>{{ __('To improve our services') }}:</strong>
                        {{ __('Enhancing website performance and user experience.') }}</li>
                    <li><strong>{{ __('To prevent fraud') }}:</strong>
                        {{ __('Detecting and preventing unauthorized access or security threats.') }}</li>
                    <li><strong>{{ __('To comply with legal obligations') }}:</strong>
                        {{ __('Meeting regulatory and legal requirements.') }}</li>
                    <li><strong>{{ __('To send marketing communications') }}:</strong>
                        {{ __('Providing offers, promotions, and updates (you can opt-out at any time).') }}</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">4. {{ __('Sharing Your Information') }}
                </h2>
                <p class="mb-4">{{ __('We may share your information with:') }}</p>
                <ul class="list-disc {{ app()->getLocale() === 'ar' ? 'pr-6' : 'pl-6' }} mb-4 space-y-2">
                    <li><strong>{{ __('Accommodation Providers') }}:</strong>
                        {{ __('Hotels and rental properties need booking details to fulfill your reservation.') }}</li>
                    <li><strong>{{ __('Payment Processors') }}:</strong>
                        {{ __('Secure processing of transactions and fraud prevention.') }}</li>
                    <li><strong>{{ __('Third-Party Service Providers') }}:</strong>
                        {{ __('IT, analytics, and customer support services.') }}</li>
                    <li><strong>{{ __('Legal Authorities') }}:</strong>
                        {{ __('When required by law or to protect our rights and users.') }}</li>
                </ul>
                <p class="mb-8 font-semibold text-gray-900 dark:text-white">
                    {{ __('We do not sell your personal information to third parties.') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">5. {{ __('Data Security') }}</h2>
                <p class="mb-8">
                    {{ __('We implement technical and organizational measures to protect your data from unauthorized access, alteration, or loss. However, no system is 100% secure. Please use strong passwords and avoid sharing sensitive data unnecessarily.') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">6. {{ __('Your Rights and Choices') }}
                </h2>
                <p class="mb-4">{{ __('Depending on your location, you may have the following rights:') }}</p>
                <ul class="list-disc {{ app()->getLocale() === 'ar' ? 'pr-6' : 'pl-6' }} mb-8 space-y-2">
                    <li><strong>{{ __('Access & Correction') }}:</strong>
                        {{ __('Request a copy of your personal data or correct inaccuracies.') }}</li>
                    <li><strong>{{ __('Deletion') }}:</strong>
                        {{ __('Request the deletion of your data where applicable.') }}</li>
                    <li><strong>{{ __('Opt-out of Marketing') }}:</strong>
                        {{ __('Unsubscribe from promotional emails anytime.') }}</li>
                    <li><strong>{{ __('Data Portability') }}:</strong>
                        {{ __('Receive a copy of your data in a structured format.') }}</li>
                </ul>
                <p class="mb-8">
                    {{ __('To exercise these rights, contact us at') }} <a href="mailto:info@abrajstay.com"
                        class="text-orange-500 hover:text-orange-600">info@abrajstay.com</a>
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">7.
                    {{ __('Cookies and Tracking Technologies') }}</h2>
                <p class="mb-8">
                    {{ __('We use cookies to enhance your browsing experience. You can manage your cookie preferences in your browser settings.') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">8.
                    {{ __('International Data Transfers') }}</h2>
                <p class="mb-8">
                    {{ __('Your information may be stored or processed in countries outside your residence. We ensure data protection measures are in place when transferring data internationally.') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">9.
                    {{ __('Changes to This Privacy Policy') }}</h2>
                <p class="mb-8">
                    {{ __('We may update this Privacy Policy periodically. Changes will be posted on this page with an updated "Last Updated" date. Continued use of our services after changes take effect constitutes acceptance of the updated policy.') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">10. {{ __('Contact Us') }}</h2>
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
