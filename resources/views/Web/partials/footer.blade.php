<!-- Footer -->
<footer class="bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- About -->
            <div>
                <div class="mb-4">
                    @if (file_exists(public_path('images/abraj-stay-logo.png')))
                        <img src="{{ asset('images/abraj-stay-logo.png') }}" alt="ABRAJ STAY" class="h-10 w-auto">
                    @else
                        <h3 class="text-xl font-bold">
                            <span class="text-orange-500">A</span><span class="text-blue-900">BRAJ</span> <span
                                class="text-orange-500">STAY</span>
                        </h3>
                    @endif
                </div>
                <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
                    {{ __('Abraj Stay is a specialized platform offering premium travel options and 24/7 support.') }}
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="font-semibold mb-4 text-center text-gray-900 dark:text-gray-100">{{ __('Quick Links') }}</h4>
                <ul class="space-y-2 text-sm text-gray-600 text-center">
                    <li><a href="#" class="hover:text-orange-500 transition">{{ __('Book Hotel') }}</a></li>
                    <li><a href="#" class="hover:text-orange-500 transition">{{ __('Special Offers') }}</a></li>
                    <li><a href="#" class="hover:text-orange-500 transition">{{ __('Popular Destinations') }}</a>
                    </li>
                    <li><a href="#" class="hover:text-orange-500 transition">{{ __('Travel Guide') }}</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h4 class="font-semibold mb-4 text-center text-gray-900 dark:text-gray-100">{{ __('Support') }}</h4>
                <ul class="space-y-2 text-sm text-gray-600 text-center">
                    <li><a href="#" class="hover:text-orange-500 transition">{{ __('FAQ') }}</a></li>
                    <li><a href="{{ route('contact') }}"
                            class="hover:text-orange-500 transition">{{ __('Contact Us') }}</a></li>
                    <li><a href="#" class="hover:text-orange-500 transition">{{ __('Privacy Policy') }}</a></li>
                    <li><a href="#" class="hover:text-orange-500 transition">{{ __('Terms of Use') }}</a></li>
                </ul>
            </div>

            <!-- Social Media -->
            <div>
                <h4 class="font-semibold mb-4 text-center text-gray-900 dark:text-gray-100">{{ __('Follow Us') }}</h4>
                <div
                    class="flex {{ app()->getLocale() === 'ar' ? 'space-x-reverse space-x-4' : 'space-x-4' }} justify-center">
                    <a href="#"
                        class="w-10 h-10 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-full flex items-center justify-center hover:bg-orange-500 hover:border-orange-500 hover:text-white text-gray-700 dark:text-gray-200 transition">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#"
                        class="w-10 h-10 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-full flex items-center justify-center hover:bg-orange-500 hover:border-orange-500 hover:text-white text-gray-700 dark:text-gray-200 transition">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#"
                        class="w-10 h-10 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-full flex items-center justify-center hover:bg-orange-500 hover:border-orange-500 hover:text-white text-gray-700 dark:text-gray-200 transition">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#"
                        class="w-10 h-10 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-full flex items-center justify-center hover:bg-orange-500 hover:border-orange-500 hover:text-white text-gray-700 dark:text-gray-200 transition">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>

        <div
            class="border-t border-gray-300 dark:border-gray-800 mt-8 pt-8 text-center text-sm text-gray-600 dark:text-gray-300">
            <div class="flex items-center justify-center mb-2">
                @if (file_exists(public_path('images/abraj-stay-logo.png')))
                    <img src="{{ asset('images/abraj-stay-logo.png') }}" alt="ABRAJ STAY" class="h-8 w-auto">
                @else
                    <span class="font-bold">
                        <span class="text-orange-500">A</span><span class="text-blue-900">BRAJ</span> <span
                            class="text-orange-500">STAY</span>
                    </span>
                @endif
            </div>
            <p>&copy; {{ date('Y') }} ABRAJ STAY. {{ __('All rights reserved.') }}</p>
        </div>
    </div>
</footer>
