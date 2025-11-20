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
        </div>
    </div>
</section>

