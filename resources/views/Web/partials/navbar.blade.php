<!-- Navigation -->
<nav class="bg-white dark:bg-gray-900 shadow-md sticky top-0 z-50">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20 md:h-28">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center">
                    @if(file_exists(public_path('images/abraj-stay-logo.png')))
                        <img src="{{ asset('images/abraj-stay-logo.png') }}" alt="ABRAJ STAY" class="h-6 md:h-8 w-auto">
                    @else
                        <span class="flex items-center text-xl md:text-2xl font-bold">
                            <span class="text-orange-500 font-extrabold text-2xl md:text-3xl">A</span>
                            <span class="text-blue-900 font-serif font-bold">BRAJ</span>
                            <span class="text-orange-500 font-serif font-bold ml-2">STAY</span>
                        </span>
                    @endif
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center {{ app()->getLocale() === 'ar' ? 'space-x-reverse space-x-8' : 'space-x-8' }}">
                <a href="{{ route('home') }}" class="text-gray-700 dark:text-gray-200 hover:text-orange-500 transition font-medium text-base">{{ __('Home') }}</a>
                <a href="{{ route('all.hotels') }}" class="text-gray-700 dark:text-gray-200 hover:text-orange-500 transition font-medium text-base">{{ __('Hotels') }}</a>
                <a href="{{ route('packages') }}" class="text-gray-700 dark:text-gray-200 hover:text-orange-500 transition font-medium text-base">{{ __('Packages') }}</a>
                <a href="{{ route('flights') }}" class="text-gray-700 dark:text-gray-200 hover:text-orange-500 transition font-medium text-base">{{ __('Flight Booking') }}</a>
                <a href="{{ route('transfer') }}" class="text-gray-700 dark:text-gray-200 hover:text-orange-500 transition font-medium text-base">{{ __('Transfer') }}</a>
                <a href="{{ route('car-rental') }}" class="text-gray-700 dark:text-gray-200 hover:text-orange-500 transition font-medium text-base">{{ __('Car Rental') }}</a>
                <a href="{{ route('visa') }}" class="text-gray-700 dark:text-gray-200 hover:text-orange-500 transition font-medium text-base">{{ __('Visa Service') }}</a>
                <a href="{{ route('home') }}#about" class="text-gray-700 dark:text-gray-200 hover:text-orange-500 transition font-medium text-base">{{ __('About Us') }}</a>
                <a href="{{ route('contact') }}" class="text-gray-700 dark:text-gray-200 hover:text-orange-500 transition font-medium text-base">{{ __('Contact Us') }}</a>
            </div>

            <!-- Auth Buttons & Controls -->
            <div class="flex items-center {{ app()->getLocale() === 'ar' ? 'space-x-reverse space-x-4' : 'space-x-4' }}">
                <!-- Language Switcher - Desktop & Mobile -->
                @if(app()->getLocale() === 'ar')
                    <a href="{{ str_replace('/ar/', '/en/', strtok(request()->url(), '?')) }}" 
                       class="flex items-center text-gray-700 dark:text-gray-200 hover:text-orange-500 transition"
                       title="English">
                        <img src="https://flagcdn.com/w20/gb.png" alt="English" class="w-5 h-4 md:w-6 md:h-5 rounded">
                    </a>
                @else
                    <a href="{{ str_replace('/en/', '/ar/', strtok(request()->url(), '?')) }}" 
                       class="flex items-center text-gray-700 dark:text-gray-200 hover:text-orange-500 transition"
                       title="العربية">
                        <img src="https://flagcdn.com/w20/sa.png" alt="العربية" class="w-5 h-4 md:w-6 md:h-5 rounded">
                    </a>
                @endif
                
                <!-- Theme Toggle - Desktop & Mobile -->
                <button id="theme-toggle" aria-label="Toggle theme" class="p-1.5 md:p-2 rounded-md text-gray-700 dark:text-gray-200 hover:text-orange-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <i id="theme-sun" class="fas fa-sun text-lg md:text-xl"></i>
                    <i id="theme-moon" class="fas fa-moon text-lg md:text-xl hidden"></i>
                </button>
                
                @auth
                    <!-- Profile Dropdown - Desktop -->
                    <div class="hidden md:block relative" id="profile-dropdown-container">
                        <button id="profile-toggle" class="relative w-10 h-10 md:w-12 md:h-12 rounded-full overflow-hidden border-2 border-orange-500 hover:border-orange-600 transition focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                            @if(auth()->user()->image)
                                <img src="{{ asset('storage/' . auth()->user()->image) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white font-semibold text-base md:text-lg">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div id="profile-dropdown" class="absolute {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-2 opacity-0 invisible transition-all duration-200 z-50">
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <i class="fas fa-user {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('Profile') }}
                            </a>
                            <a href="{{ route('requests') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <i class="fas fa-list {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('Requests') }}
                            </a>
                            <hr class="my-2 border-gray-200 dark:border-gray-700">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                    <i class="fas fa-sign-out-alt {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('Logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Profile Icon - Mobile (in menu) -->
                    <div class="md:hidden">
                        <a href="{{ route('profile') }}" class="text-gray-700 dark:text-gray-200 hover:text-orange-500 transition">
                            @if(auth()->user()->image)
                                <img src="{{ asset('storage/' . auth()->user()->image) }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full border-2 border-orange-500 object-cover">
                            @else
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white text-sm font-semibold border-2 border-orange-500">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                        </a>
                    </div>
                @else
                    <!-- Login Button - Desktop -->
                    <button onclick="openAuthModal()" class="hidden md:block bg-gradient-to-r from-orange-500 to-orange-600 text-white px-8 md:px-12 py-2 md:py-3 rounded-xl hover:from-orange-600 hover:to-orange-700 transition font-semibold text-sm md:text-base shadow-lg">
                        {{ __('Login') }}
                    </button>
                @endauth
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden text-gray-700 dark:text-gray-200" id="mobile-menu-btn" type="button">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Backdrop -->
    <div id="mobile-menu-backdrop" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden transition-opacity duration-300"></div>

    <!-- Mobile Menu Drawer -->
    <div id="mobile-menu" class="fixed top-0 {{ app()->getLocale() === 'ar' ? 'right-0 translate-x-full' : 'left-0 -translate-x-full' }} h-full w-64 bg-white dark:bg-gray-900 shadow-2xl z-50 transform transition-transform duration-300 ease-in-out md:hidden hidden">
        <div class="flex flex-col h-full">
            <!-- Header with Close Button -->
            <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
                <button id="mobile-menu-close" class="text-gray-700 dark:text-gray-200 hover:text-orange-500 transition" type="button">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto px-4 py-4 space-y-3">
                <a href="{{ route('home') }}" onclick="closeMobileMenu()" class="block text-gray-700 dark:text-gray-200 hover:text-orange-500 font-medium py-2">{{ __('Home') }}</a>
                <a href="{{ route('hotels.search') }}" onclick="closeMobileMenu()" class="block text-gray-700 dark:text-gray-200 hover:text-orange-500 font-medium py-2">{{ __('Hotels') }}</a>
                <a href="{{ route('packages') }}" onclick="closeMobileMenu()" class="block text-gray-700 dark:text-gray-200 hover:text-orange-500 font-medium py-2">{{ __('Packages') }}</a>
                <a href="{{ route('flights') }}" onclick="closeMobileMenu()" class="block text-gray-700 dark:text-gray-200 hover:text-orange-500 font-medium py-2">{{ __('Flight Booking') }}</a>
                <a href="{{ route('transfer') }}" onclick="closeMobileMenu()" class="block text-gray-700 dark:text-gray-200 hover:text-orange-500 font-medium py-2">{{ __('Transfer') }}</a>
                <a href="{{ route('car-rental') }}" onclick="closeMobileMenu()" class="block text-gray-700 dark:text-gray-200 hover:text-orange-500 font-medium py-2">{{ __('Car Rental') }}</a>
                <a href="{{ route('visa') }}" onclick="closeMobileMenu()" class="block text-gray-700 dark:text-gray-200 hover:text-orange-500 font-medium py-2">{{ __('Visa Service') }}</a>
                <a href="{{ route('home') }}#about" onclick="closeMobileMenu()" class="block text-gray-700 dark:text-gray-200 hover:text-orange-500 font-medium py-2">{{ __('About Us') }}</a>
                <a href="{{ route('contact') }}" onclick="closeMobileMenu()" class="block text-gray-700 dark:text-gray-200 hover:text-orange-500 font-medium py-2">{{ __('Contact Us') }}</a>
               
                
                @auth
                    <!-- User Menu Items in Mobile -->
                    <div class="pt-3 border-t border-gray-200 dark:border-gray-700 space-y-2">
                        <a href="{{ route('profile') }}" onclick="closeMobileMenu()" class="block text-gray-700 dark:text-gray-200 hover:text-orange-500 font-medium py-2">
                            <i class="fas fa-user {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('Profile') }}
                        </a>
                        <a href="{{ route('requests') }}" onclick="closeMobileMenu()" class="block text-gray-700 dark:text-gray-200 hover:text-orange-500 font-medium py-2">
                            <i class="fas fa-list {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('Requests') }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" onclick="closeMobileMenu()" class="w-full text-left text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-medium py-2">
                                <i class="fas fa-sign-out-alt {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>{{ __('Logout') }}
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Login Button at Bottom -->
                    <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                        <button onclick="openAuthModal(); closeMobileMenu();" class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 py-2 rounded-xl hover:from-orange-600 hover:to-orange-700 transition font-semibold shadow-lg">
                            {{ __('Login') }}
                        </button>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

