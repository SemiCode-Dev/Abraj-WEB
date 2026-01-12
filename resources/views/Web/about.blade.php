@extends('Web.layouts.app')

@section('title', __('About Us') . ' - ABRAJ STAY')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-orange-500 via-orange-600 to-blue-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-8">{{ __('About Abraj Stay') }}</h1>
            <p class="text-orange-100 text-lg max-w-2xl mx-auto">
                {{ __('Your Premier Travel Booking Platform') }}
            </p>
            <p class="text-orange-50 text-base max-w-2xl mx-auto mt-4 leading-relaxed opacity-90">
                {{ __('Our mission is to create memories that last a lifetime for every traveler who chooses us.') }}
            </p>
        </div>
    </section>

    <!-- Content Section -->
    <section class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
                <!-- Our Story -->
                <div class="bg-gray-50 dark:bg-gray-800 p-8 rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div
                        class="w-14 h-14 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-history text-orange-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">{{ __('Our Story') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                        {{ __('Founded with a passion for travel and hospitality, Abraj Stay has been revolutionizing the way people book accommodations since our inception. We understand that every journey is unique, and every traveler deserves exceptional service tailored to their needs.') }}
                    </p>
                </div>

                <!-- Mission -->
                <div class="bg-gray-50 dark:bg-gray-800 p-8 rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-bullseye text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">{{ __('Our Mission') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                        {{ __('To provide seamless, reliable, and personalized booking experiences that connect travelers with their perfect accommodations. We strive to make every stay memorable by offering comprehensive services, competitive prices, and unwavering customer support.') }}
                    </p>
                </div>

                <!-- Vision -->
                <div class="bg-gray-50 dark:bg-gray-800 p-8 rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div
                        class="w-14 h-14 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-eye text-orange-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">{{ __('Our Vision') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                        {{ __('To become the leading travel booking platform in the region, known for excellence, innovation, and customer satisfaction. We envision a world where booking your ideal accommodation is effortless and enjoyable.') }}
                    </p>
                </div>

                <!-- Commitment -->
                <div class="bg-gray-50 dark:bg-gray-800 p-8 rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div
                        class="w-14 h-14 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-handshake text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">{{ __('Our Commitment') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                        {{ __('We are committed to providing secure, efficient, and user-friendly booking services. Our dedicated team works around the clock to ensure your travel plans go smoothly from booking to check-out. With partnerships across the region and beyond, we offer you access to premium accommodations at competitive rates.') }}
                    </p>
                </div>
            </div>

            <!-- Values -->
            <div class="bg-blue-900 rounded-3xl p-8 md:p-12 text-white">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold mb-4">{{ __('Our Values') }}</h2>
                    <div class="w-20 h-1 bg-orange-500 mx-auto"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-10 gap-x-8">
                    <div class="flex items-center">
                        <i
                            class="fas fa-check-circle text-orange-500 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                        <span>{{ __('Excellence: We maintain the highest standards in everything we do') }}</span>
                    </div>
                    <div class="flex items-center">
                        <i
                            class="fas fa-check-circle text-orange-500 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                        <span>{{ __('Innovation: We continuously evolve to meet changing travel needs') }}</span>
                    </div>
                    <div class="flex items-center">
                        <i
                            class="fas fa-check-circle text-orange-500 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                        <span>{{ __('Trust: We build lasting relationships through transparency and reliability') }}</span>
                    </div>
                    <div class="flex items-center">
                        <i
                            class="fas fa-check-circle text-orange-500 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                        <span>{{ __('Customer Focus: Your satisfaction is our top priority') }}</span>
                    </div>
                    <div class="flex items-center">
                        <i
                            class="fas fa-check-circle text-orange-500 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                        <span>{{ __('Cultural Respect: We honor local traditions and customs in every destination') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
