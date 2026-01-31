<!-- Footer -->
<footer class="bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
            <!-- About -->
            <div>
                <div class="mb-4">
                    <img src="/images/abraj-stay-logo.png" alt="ABRAJ STAY" class="h-10 w-auto logo-light">
                    <img src="/images/abraj-stay-logo-white.png" alt="ABRAJ STAY" class="h-10 w-auto logo-dark">
                </div>
                <ul class="mt-4 space-y-3 text-sm text-gray-600 dark:text-gray-300">
                    <li class="flex items-center gap-2">
                        <i class="fas fa-envelope text-orange-500 w-5"></i>
                        <a href="mailto:info@abrajstay.com" class="hover:text-orange-500 transition line-clamp-1">
                            info@abrajstay.com
                        </a>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-phone text-orange-500 w-5"></i>
                        <p dir="ltr" class="hover:text-orange-500 transition cursor-pointer">
                            +966 9200 15728
                        </p>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-map-marker-alt text-orange-500 w-5 mt-1"></i>
                        <span>
                            {{ __('Spring Towers, Prince Mohammed Ibn Salman Ibn Abdulaziz Rd, Riyadh, Saudi Arabia, Zip Code: 13316') }}
                        </span>
                    </li>
                </ul>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="font-semibold mb-4 text-center text-gray-900 dark:text-gray-100">{{ __('Quick Links') }}
                </h4>
                <ul class="space-y-2 text-sm text-gray-600 text-center">
                    <li><a href="{{ route('all.hotels') }}"
                            class="hover:text-orange-500 transition">{{ __('Book Hotel') }}</a></li>
                    <li><a href="#offers" class="hover:text-orange-500 transition">{{ __('Special Offers') }}</a></li>
                    <li><a href="#destinations"
                            class="hover:text-orange-500 transition">{{ __('Popular Destinations') }}</a>
                    </li>
                    <li><a href="#" class="hover:text-orange-500 transition">{{ __('Travel Guide') }}</a></li>
                </ul>
            </div>

            <!-- Polices -->
            <div>
                <h4 class="font-semibold mb-4 text-center text-gray-900 dark:text-gray-100">{{ __('Policies') }}</h4>
                <ul class="space-y-2 text-sm text-gray-600 text-center">
                    <li><a href="{{ route('about') }}" class="hover:text-orange-500 transition">{{ __('About Us') }}</a>
                    </li>
                    <li><a href="{{ route('privacy') }}"
                            class="hover:text-orange-500 transition">{{ __('Privacy Policy') }}</a></li>
                    <li><a href="{{ route('terms') }}"
                            class="hover:text-orange-500 transition">{{ __('Terms of Service') }}</a></li>
                    <li><a href="{{ route('cookies') }}"
                            class="hover:text-orange-500 transition">{{ __('Cookie Policy') }}</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h4 class="font-semibold mb-4 text-center text-gray-900 dark:text-gray-100">{{ __('Support') }}</h4>
                <ul class="space-y-2 text-sm text-gray-600 text-center">
                    <li><a href="#" class="hover:text-orange-500 transition">{{ __('FAQ') }}</a></li>
                    <li><a href="{{ route('contact') }}"
                            class="hover:text-orange-500 transition">{{ __('Contact Us') }}</a></li>
                </ul>
            </div>

            <!-- Social Media & Payment Methods -->
            <div class="flex flex-col items-center">
                <div>
                    <h4 class="font-semibold mb-4 text-center text-gray-900 dark:text-gray-100">{{ __('Follow Us') }}
                    </h4>
                    <div
                        class="flex {{ app()->getLocale() === 'ar' ? 'space-x-reverse space-x-4' : 'space-x-4' }} justify-center">
                        <a href="https://web.facebook.com/profile.php?id=61577067520118"
                            class="w-10 h-10 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-full flex items-center justify-center hover:bg-orange-500 hover:border-orange-500 hover:text-white text-gray-700 dark:text-gray-200 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://x.com/AbrajStay"
                            class="w-10 h-10 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-full flex items-center justify-center hover:bg-orange-500 hover:border-orange-500 hover:text-white text-gray-700 dark:text-gray-200 transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://www.instagram.com/abrajstay/"
                            class="w-10 h-10 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-full flex items-center justify-center hover:bg-orange-500 hover:border-orange-500 hover:text-white text-gray-700 dark:text-gray-200 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://www.linkedin.com/company/abraj-stay/"
                            class="w-10 h-10 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-full flex items-center justify-center hover:bg-orange-500 hover:border-orange-500 hover:text-white text-gray-700 dark:text-gray-200 transition">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>


                <div
                    class="flex items-center justify-center space-x-6 {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }} mt-6">
                    <div class="flex items-center justify-center" title="Visa">
                        <i class="fab fa-cc-visa text-4xl text-gray-900 dark:text-gray-100 transition duration-300"></i>
                    </div>
                    <div class="flex items-center justify-center" title="Mastercard">
                        <i class="fab fa-cc-mastercard text-4xl text-gray-900 dark:text-gray-100 transition duration-300"></i>
                    </div>
                    <div class="flex items-center justify-center" title="Apple Pay">
                        <i class="fab fa-cc-apple-pay text-4xl text-gray-900 dark:text-gray-100 transition duration-300"></i>
                    </div>
                </div>
            </div>
        </div>


        <div
            class="border-t border-gray-300 dark:border-gray-800 mt-8 pt-8 grid grid-cols-1 md:grid-cols-3 gap-4 items-center text-sm text-gray-600 dark:text-gray-300">
            <!-- Tourism Authority Logos (Side by side) -->
            <div
                class="flex items-center justify-center md:justify-start space-x-4 {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }} order-3 md:order-1">
                <img src="{{ asset('images/footer/ministry-of-tourism.png') }}" alt="{{ __('Ministry of Tourism') }}"
                    class="h-11 w-auto">
                <img src="{{ asset('images/footer/saudi-tourism-authority.png') }}"
                    alt="{{ __('Saudi Tourism Authority') }}"
                    class="h-11 w-auto dark:invert dark:hue-rotate-180 dark:brightness-200">
            </div>

            <!-- Copyright and Logo (Centered) -->
            <div class="flex flex-col items-center justify-center order-1 md:order-2">
                <div class="flex items-center justify-center mb-2">
                    <img src="/images/abraj-stay-logo.png" alt="ABRAJ STAY" class="h-8 w-auto logo-light">
                    <img src="/images/abraj-stay-logo-white.png" alt="ABRAJ STAY" class="h-8 w-auto logo-dark">
                </div>
                <p>&copy; {{ date('Y') }} ABRAJ STAY. {{ __('All rights reserved.') }}</p>
            </div>

            <!-- Badges (Right/Left) -->
            <div
                class="flex items-center justify-center md:justify-end space-x-4 {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }} order-2 md:order-3">
                <img src="{{ asset('images/footer/vat.png') }}" alt="VAT" class="h-16 w-auto">
                <img src="{{ asset('images/footer/maroof.png') }}" alt="Maroof" class="h-20 w-auto">
            </div>
        </div>

        <!-- Dedicated Registration Info Bar (Bottom) -->
        <div class="mt-10 pt-8  dark:border-gray-800">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-y-8 md:gap-y-0 text-center max-w-6xl mx-auto">
                <!-- Category -->
                <div class="flex flex-col items-center justify-center relative px-4">
                    <p class="text-[10px] md:text-sm text-gray-500 dark:text-gray-400 mb-1 font-semibold uppercase tracking-wider">
                        {{ __('Category') }}:
                    </p>
                    <p class="text-xs md:text-base font-bold text-gray-900 dark:text-white leading-relaxed">
                        {{ __('General Travel and Tourism Services Provider') }}
                    </p>
                    <div class="hidden md:block absolute {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} top-1/2 -translate-y-1/2 h-12 w-px bg-gray-300 dark:bg-gray-700"></div>
                </div>

                <!-- Commercial Registration -->
                <div class="flex flex-col items-center justify-center relative px-4">
                    <p class="text-[10px] md:text-sm text-gray-600 dark:text-gray-400 mb-1 font-semibold uppercase tracking-wider">
                        {{ __('Commercial Registration Number') }}:
                    </p>
                    <p class="text-xs md:text-base font-bold text-gray-900 dark:text-white tracking-[0.2em]">
                        1010363465
                    </p>
                    <div class="hidden md:block absolute {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} top-1/2 -translate-y-1/2 h-12 w-px bg-gray-300 dark:bg-gray-700"></div>
                </div>

                <!-- Tourism License -->
                <div class="flex flex-col items-center justify-center px-4">
                    <p class="text-[10px] md:text-sm text-gray-500 dark:text-gray-400 mb-1 font-semibold uppercase tracking-wider">
                        {{ __('Tourism License Number') }}:
                    </p>
                    <p class="text-xs md:text-base font-bold text-gray-900 dark:text-white tracking-[0.2em]">
                        73103013
                    </p>
                </div>
            </div>
        </div>
    </div>

    </div>
</footer>
