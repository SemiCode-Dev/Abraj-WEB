<!-- Navigation -->
<nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="/" class="flex items-center">
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
            <div class="hidden md:flex items-center space-x-reverse space-x-8">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-orange-500 transition font-medium">الرئيسية</a>
                <a href="{{ route('hotels.search') }}" class="text-gray-700 hover:text-orange-500 transition font-medium">الفنادق</a>
                <a href="{{ route('home') }}#offers" class="text-gray-700 hover:text-orange-500 transition font-medium">العروض</a>
                <a href="{{ route('home') }}#destinations" class="text-gray-700 hover:text-orange-500 transition font-medium">الوجهات</a>
                <a href="{{ route('home') }}#about" class="text-gray-700 hover:text-orange-500 transition font-medium">من نحن</a>
                <a href="{{ route('home') }}#contact" class="text-gray-700 hover:text-orange-500 transition font-medium">اتصل بنا</a>
            </div>
            
            <!-- Auth Buttons -->
            <div class="flex items-center space-x-reverse space-x-4">
                <!-- User Icon - Only show when logged in -->
                <a href="#" id="user-icon" class="text-gray-700 hover:text-orange-500 transition hidden">
                    <i class="fas fa-user-circle text-xl"></i>
                </a>
                <button onclick="openAuthModal()" class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 py-2 rounded-xl hover:from-orange-600 hover:to-orange-700 transition font-semibold shadow-lg">
                    تسجيل الدخول
                </button>
                <button class="md:hidden text-gray-700" id="mobile-menu-btn">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div class="hidden md:hidden bg-white border-t" id="mobile-menu">
        <div class="px-4 py-4 space-y-3">
            <a href="{{ route('home') }}" class="block text-gray-700 hover:text-orange-500 font-medium">الرئيسية</a>
            <a href="{{ route('hotels.search') }}" class="block text-gray-700 hover:text-orange-500 font-medium">الفنادق</a>
            <a href="{{ route('home') }}#offers" class="block text-gray-700 hover:text-orange-500 font-medium">العروض</a>
            <a href="{{ route('home') }}#destinations" class="block text-gray-700 hover:text-orange-500 font-medium">الوجهات</a>
            <a href="{{ route('home') }}#about" class="block text-gray-700 hover:text-orange-500 font-medium">من نحن</a>
            <a href="{{ route('home') }}#contact" class="block text-gray-700 hover:text-orange-500 font-medium">اتصل بنا</a>
        </div>
    </div>
</nav>

