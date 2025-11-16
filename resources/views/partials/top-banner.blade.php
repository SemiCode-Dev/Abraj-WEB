<!-- Top Banner with Stats -->
<section class="bg-gradient-to-r from-orange-500 via-orange-600 to-blue-900 text-white py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between text-sm">
            <div class="flex items-center {{ app()->getLocale() === 'ar' ? 'space-x-reverse space-x-6' : 'space-x-6' }}">
                <div class="flex items-center">
                    <i class="fas fa-users text-lg {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                    <span>{{ __('More than') }} <strong>2 {{ __('happy customers') }}</strong></span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-hotel text-lg {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                    <span>{{ __('More than') }} <strong>50,000</strong> {{ __('hotels worldwide') }}</span>
                </div>
            </div>
            <div class="flex items-center {{ app()->getLocale() === 'ar' ? 'space-x-reverse space-x-4' : 'space-x-4' }} mt-2 md:mt-0">
                <!-- Language Switcher - Show only the other language flag -->
                @if(app()->getLocale() === 'ar')
                    <a href="{{ route('lang.switch', ['locale' => 'en']) }}" 
                       class="flex items-center hover:opacity-80 transition"
                       title="English">
                        <img src="https://flagcdn.com/w20/gb.png" alt="English" class="w-5 h-4 rounded">
                    </a>
                @else
                    <a href="{{ route('lang.switch', ['locale' => 'ar']) }}" 
                       class="flex items-center hover:opacity-80 transition"
                       title="العربية">
                        <img src="https://flagcdn.com/w20/sa.png" alt="العربية" class="w-5 h-4 rounded">
                    </a>
                @endif
                <!-- Theme Toggle -->
                <button id="theme-toggle" aria-label="Toggle theme" class="p-1.5 rounded-md text-white/80 hover:text-white hover:bg-white/10 transition">
                    <i id="theme-sun" class="fas fa-sun text-sm"></i>
                    <i id="theme-moon" class="fas fa-moon text-sm hidden"></i>
                </button>
            </div>
        </div>
    </div>
</section>

