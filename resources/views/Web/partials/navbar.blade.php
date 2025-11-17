<!-- Navigation -->
<nav class="bg-white dark:bg-gray-900 shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="flex items-center">
                    @if(file_exists(public_path('images/abraj-stay-logo.png')))
                        <img src="{{ asset('images/abraj-stay-logo.png') }}" alt="ABRAJ STAY" class="h-7 md:h-9 w-auto">
                    @else
                        <span class="flex items-center text-2xl font-bold">
                            <span class="text-orange-500 font-extrabold text-3xl">A</span>
                            <span class="text-blue-900 font-serif font-bold">BRAJ</span>
                            <span class="text-orange-500 font-serif font-bold ml-2">STAY</span>
                        </span>
                    @endif
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center {{ app()->getLocale() === 'ar' ? 'space-x-reverse space-x-8' : 'space-x-8' }}">
                <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="text-gray-700 dark:text-gray-200 hover:text-orange-500 transition font-medium">{{ __('Home') }}</a>
                <a href="{{ route('hotels.search', ['locale' => app()->getLocale()]) }}" class="text-gray-700 dark:text-gray-200 hover:text-orange-500 transition font-medium">{{ __('Hotels') }}</a>
                <a href="{{ route('home', ['locale' => app()->getLocale()]) }}#offers" class="text-gray-700 dark:text-gray-200 hover:text-orange-500 transition font-medium">{{ __('Offers') }}</a>
                <a href="{{ route('home', ['locale' => app()->getLocale()]) }}#destinations" class="text-gray-700 dark:text-gray-200 hover:text-orange-500 transition font-medium">{{ __('Destinations') }}</a>
                <a href="{{ route('home', ['locale' => app()->getLocale()]) }}#about" class="text-gray-700 dark:text-gray-200 hover:text-orange-500 transition font-medium">{{ __('About Us') }}</a>
                <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}" class="text-gray-700 dark:text-gray-200 hover:text-orange-500 transition font-medium">{{ __('Contact Us') }}</a>
            </div>

            <!-- Auth Buttons -->
            <div class="flex items-center {{ app()->getLocale() === 'ar' ? 'space-x-reverse space-x-4' : 'space-x-4' }}">
                <!-- User Icon - Only show when logged in -->
                <a href="#" id="user-icon" class="text-gray-700 dark:text-gray-200 hover:text-orange-500 transition hidden">
                    <i class="fas fa-user-circle text-xl"></i>
                </a>
                <button onclick="openAuthModal()" class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 py-2 rounded-xl hover:from-orange-600 hover:to-orange-700 transition font-semibold shadow-lg">
                    {{ __('Login') }}
                </button>
                <button class="md:hidden text-gray-700 dark:text-gray-200" id="mobile-menu-btn">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="hidden md:hidden bg-white dark:bg-gray-900 border-t" id="mobile-menu">
        <div class="px-4 py-4 space-y-3">
            <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="block text-gray-700 dark:text-gray-200 hover:text-orange-500 font-medium">{{ __('Home') }}</a>
            <a href="{{ route('hotels.search', ['locale' => app()->getLocale()]) }}" class="block text-gray-700 dark:text-gray-200 hover:text-orange-500 font-medium">{{ __('Hotels') }}</a>
            <a href="{{ route('home', ['locale' => app()->getLocale()]) }}#offers" class="block text-gray-700 dark:text-gray-200 hover:text-orange-500 font-medium">{{ __('Offers') }}</a>
            <a href="{{ route('home', ['locale' => app()->getLocale()]) }}#destinations" class="block text-gray-700 dark:text-gray-200 hover:text-orange-500 font-medium">{{ __('Destinations') }}</a>
            <a href="{{ route('home', ['locale' => app()->getLocale()]) }}#about" class="block text-gray-700 dark:text-gray-200 hover:text-orange-500 font-medium">{{ __('About Us') }}</a>
            <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}" class="block text-gray-700 dark:text-gray-200 hover:text-orange-500 font-medium">{{ __('Contact Us') }}</a>
        </div>
    </div>
</nav>

