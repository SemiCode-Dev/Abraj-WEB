@extends('Web.layouts.app')

@section('title', __('Cookie Policy') . ' - ABRAJ STAY')

@section('content')
    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden bg-gray-900">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-b from-blue-900/50 to-gray-900 z-10"></div>
            <img src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&q=80"
                alt="Cookie Policy Background" class="w-full h-full object-cover">
        </div>

        <div class="container mx-auto px-4 relative z-20 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-8">
                {{ __('Cookie Policy') }}
            </h1>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                {{ __('How we use cookies and similar technologies') }}
            </p>
        </div>
    </section>

    <!-- Content Section -->
    <section class="py-20 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto prose dark:prose-invert prose-orange lg:prose-lg">
                <p class="lead text-xl text-gray-600 dark:text-gray-400 mb-12">
                    {{ __('This Cookie Policy explains how Abraj Stay ("we," "us," or "our") uses cookies and similar technologies on our hotel booking website. By using our website, you consent to the use of cookies as outlined in this policy.') }}
                </p>

                <p class="mb-12">
                    {{ __('This policy should be read together with our Privacy Policy and Terms and Conditions, which provide additional information about how we collect, use, and protect your personal information.') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ __('1. Introduction') }}</h2>
                <p>{{ __('This Cookie Policy explains how Abraj Stay ("we," "us," or "our") uses cookies and similar technologies on our hotel booking website. By using our website, you consent to the use of cookies as outlined in this policy.') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-12 mb-6">{{ __('2. What Are Cookies?') }}
                </h2>
                <p>{{ __('Cookies are small text files that are placed on your device (computer, tablet, or smartphone) when you visit our website. They are widely used to make websites function efficiently and to provide information to website owners.') }}
                </p>
                <p>{{ __('Cookies can also be used to remember your preferences, improve your browsing experience, and provide personalized content and advertising.') }}
                </p>
                <p>{{ __('Similar technologies include web beacons, pixels, and local storage that serve similar purposes to cookies.') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-12 mb-6">
                    {{ __('3. Types of Cookies We Use') }}
                </h2>

                <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">
                    {{ __('Strictly Necessary Cookies') }}
                </h3>
                <p>{{ __('These cookies are essential for the operation of our website. They enable core functionalities such as security, network management, and accessibility. Without these cookies, services you have requested cannot be provided.') }}
                </p>
                <ul class="list-disc pl-6 mb-6">
                    <li>session_id - {{ __('Maintains user session (Session)') }}</li>
                    <li>csrf_token - {{ __('Security protection (Session)') }}</li>
                </ul>

                <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('Performance Cookies') }}</h3>
                <p>{{ __('These cookies collect information about how you use our website, such as which pages you visit most often and if you receive error messages. This data helps us improve website performance and user experience.') }}
                </p>
                <ul class="list-disc pl-6 mb-6">
                    <li>Google Analytics - {{ __('Website analytics (2 years)') }}</li>
                </ul>

                <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('Functional Cookies') }}</h3>
                <p>{{ __('These cookies allow our website to remember choices you make and provide enhanced, more personalized features.') }}
                </p>
                <ul class="list-disc pl-6 mb-6">
                    <li>user_preferences - {{ __('Remembers user settings (1 year)') }}</li>
                    <li>language - {{ __('Stores language preference (1 year)') }}</li>
                </ul>

                <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">
                    {{ __('Targeting/Advertising Cookies') }}
                </h3>
                <p>{{ __('These cookies are used to deliver advertisements more relevant to you and your interests. They may also limit the number of times you see an advertisement and help measure advertising campaign effectiveness.') }}
                </p>
                <ul class="list-disc pl-6 mb-6">
                    <li>Google Ads - {{ __('Advertising targeting (90 days)') }}</li>
                    <li>Facebook Pixel - {{ __('Social media advertising (90 days)') }}</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-12 mb-6">{{ __('4. Third-Party Cookies') }}
                </h2>
                <p>{{ __('We work with third-party service providers who may set cookies on our website to provide services they offer. These third parties include:') }}
                </p>
                <ul class="list-disc pl-6 mb-6">
                    <li>{{ __('Analytics providers (Google Analytics)') }}</li>
                    <li>{{ __('Advertising networks (Google Ads, Facebook)') }}</li>
                    <li>{{ __('Social media platforms') }}</li>
                    <li>{{ __('Payment processors') }}</li>
                </ul>
                <p>{{ __('These third parties have their own privacy policies and cookie policies, which we encourage you to review.') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-12 mb-6">{{ __('5. Managing Cookies') }}
                </h2>
                <p>{{ __('You can manage or disable cookies at any time through your browser settings. Most web browsers allow you to control cookies through their settings preferences.') }}
                </p>
                <p>{{ __('However, please note that disabling cookies may affect the functionality of our website and your ability to use certain features, such as booking accommodations or accessing personalized content.') }}
                </p>
                <div
                    class="bg-orange-50 dark:bg-orange-900/20 p-6 rounded-2xl border border-orange-100 dark:border-orange-800 my-8">
                    <p class="text-orange-900 dark:text-orange-200 font-semibold mb-0">
                        {{ __('Important: Strictly necessary cookies cannot be disabled as they are essential for the website to function properly.') }}
                    </p>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-12 mb-6">
                    {{ __('6. Cookie Data Retention') }}
                </h2>
                <p>{{ __('Cookies have different lifespans depending on their purpose:') }}</p>
                <ul class="list-disc pl-6 mb-6">
                    <li>{{ __('Session cookies: Deleted when you close your browser') }}</li>
                    <li>{{ __('Persistent cookies: Remain until deleted or expired') }}</li>
                    <li>{{ __('Third-party cookies: Subject to the third party\'s retention policies') }}</li>
                </ul>
                <p>{{ __('You can delete cookies at any time through your browser settings.') }}</p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-12 mb-6">
                    {{ __('7. Changes to This Policy') }}
                </h2>
                <p>{{ __('We may update this Cookie Policy from time to time to reflect changes in our practices, technologies, legal requirements, or other operational reasons.') }}
                </p>
                <p>{{ __('When we make material changes to this policy, we will update the "Last Updated" date at the top of this page and notify you through appropriate channels.') }}
                </p>
                <p>{{ __('We encourage you to review this policy periodically to stay informed about our use of cookies.') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-12 mb-6">{{ __('8. Contact Us') }}</h2>
                <div
                    class="bg-gray-50 dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 not-prose">
                    <p class="mb-2"><strong>{{ __('Email:') }}</strong> <a href="mailto:info@abrajstay.com"
                            class="text-orange-500 hover:text-orange-600">info@abrajstay.com</a></p>
                    <p class="mb-2"><strong>{{ __('Phone:') }}</strong> <a href="tel:+966920015728" dir="ltr"
                            class="text-orange-500 hover:text-orange-600">+966 9200 15728</a></p>
                    <p><strong>{{ __('Address:') }}</strong>
                        {{ __('Spring Towers, Prince Mohammed Ibn Salman Ibn Abdulaziz Rd, Riyadh, Saudi Arabia') }},
                        {{ __('Post Code: 13316') }}</p>
                </div>

                <div class="mt-16 pt-12 border-t border-gray-200 dark:border-gray-700 text-center not-prose">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                        {{ __('Thank you for trusting Abraj Stay!') }}
                    </h2>

                    <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-2xl mx-auto">
                        {{ __('Your trusted partner for comprehensive travel solutions, from flights and hotels to specialized services.') }}
                    </p>

                    <p class="text-sm text-gray-500 mb-12">
                        {{ __('Effective Date') }}: 01.01.2025 | {{ __('Last Updated') }}: 26.02.2025
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
