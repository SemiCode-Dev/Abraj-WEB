@extends('Web.layouts.app')

@section('title', __('Available Hotels') . ' - ' . __('Book Hotels - Best Offers and Services'))

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-r from-orange-500 via-orange-600 to-blue-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2">{{ __('Available Hotels') }}</h1>
                <p class="text-orange-100">
                    <i class="fas fa-map-marker-alt {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                    {{ request('destination', __('All Destinations')) }}
                </p>
            </div>
            <div class="hidden md:block bg-white/20 backdrop-blur-sm px-6 py-4 rounded-xl">
                <div class="text-sm text-orange-100 mb-1">{{ __('Stay Date') }}</div>
                <div class="font-bold">
                    {{ request('check_in', '--') }} → {{ request('check_out', '--') }}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filters & Results -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Sidebar Filters -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">{{ __('Filter Results') }}</h3>
                    
                    <!-- Date Selection -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-900 mb-4">{{ __('Select Dates') }}</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-2">
                                    <i class="fas fa-calendar-alt text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ __('Check In') }}
                                </label>
                                <input type="date" id="filterCheckIn" name="check_in" value="{{ request('check_in') }}"
                                    class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 text-sm">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-2">
                                    <i class="fas fa-calendar-check text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ __('Check Out') }}
                                </label>
                                <input type="date" id="filterCheckOut" name="check_out" value="{{ request('check_out') }}"
                                    class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 text-sm">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Price Range -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-900 mb-4">{{ __('Price Range') }}</h4>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" class="w-5 h-5 text-orange-600 rounded">
                                <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} text-gray-700">{{ __('Less than 200 SAR') }}</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="w-5 h-5 text-orange-600 rounded">
                                <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} text-gray-700">{{ __('200 - 400 SAR') }}</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="w-5 h-5 text-orange-600 rounded">
                                <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} text-gray-700">{{ __('400 - 600 SAR') }}</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="w-5 h-5 text-orange-600 rounded">
                                <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} text-gray-700">{{ __('More than 600 SAR') }}</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Star Rating -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-900 mb-4">{{ __('Rating') }}</h4>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" class="w-5 h-5 text-orange-600 rounded">
                                <span class="mr-3 flex text-yellow-500">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="w-5 h-5 text-orange-600 rounded">
                                <span class="mr-3 flex text-yellow-500">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="w-5 h-5 text-orange-600 rounded">
                                <span class="mr-3 flex text-yellow-500">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Amenities -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-900 mb-4">{{ __('Amenities') }}</h4>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" class="w-5 h-5 text-orange-600 rounded">
                                <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} text-gray-700">
                                    <i class="fas fa-wifi text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i> {{ __('Free WiFi') }}
                                </span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="w-5 h-5 text-orange-600 rounded">
                                <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} text-gray-700">
                                    <i class="fas fa-swimming-pool text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i> {{ __('Pool') }}
                                </span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="w-5 h-5 text-orange-600 rounded">
                                <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} text-gray-700">
                                    <i class="fas fa-utensils text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i> {{ __('Restaurant') }}
                                </span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="w-5 h-5 text-orange-600 rounded">
                                <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} text-gray-700">
                                    <i class="fas fa-spa text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i> {{ __('Spa') }}
                                </span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="w-5 h-5 text-orange-600 rounded">
                                <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} text-gray-700">
                                    <i class="fas fa-dumbbell text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i> {{ __('Gym') }}
                                </span>
                            </label>
                        </div>
                    </div>
                    
                    <button type="button" id="applyFiltersBtn" class="w-full bg-orange-600 text-white py-3 rounded-xl font-semibold hover:bg-orange-700 transition">
                        {{ __('Apply Filter') }}
                    </button>
                </div>
            </div>
            
            <!-- Hotels List -->
            <div class="lg:col-span-3">
                <!-- Sort Bar -->
                <div class="bg-white rounded-xl shadow-md p-4 mb-6 flex items-center justify-between">
                    <div class="text-gray-700">
                        <span class="font-semibold">{{ __('Found') }}</span>
                        <span class="text-orange-600 font-bold">24 {{ __('hotels') }}</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-gray-600 text-sm">{{ __('Sort by') }}:</span>
                        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 text-gray-900">
                            <option>{{ __('Most Popular') }}</option>
                            <option>{{ __('Lowest Price') }}</option>
                            <option>{{ __('Highest Price') }}</option>
                            <option>{{ __('Highest Rating') }}</option>
                        </select>
                    </div>
                </div>
                
                <!-- Hotels Grid -->
                <div class="space-y-6">
                    @for($i = 1; $i <= 6; $i++)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-0">
                            <!-- Hotel Image -->
                            <div class="relative h-64 md:h-full min-h-[250px]">
                                <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                     alt="فندق" class="w-full h-full object-cover">
                                <div class="absolute top-4 left-4 flex gap-2">
                                    <div class="bg-white px-3 py-1 rounded-full text-sm font-bold text-gray-900 shadow-lg">
                                        <i class="fas fa-star text-yellow-500 ml-1"></i> 4.{{ 5 + $i }}
                                    </div>
                                    @if($i % 2 == 0)
                                    <div class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                        <i class="fas fa-fire {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('Discount') }} 30%
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Hotel Info -->
                            <div class="md:col-span-2 p-6 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ __('International Luxury Hotel') }} {{ $i }}</h3>
                                            <p class="text-gray-600 flex items-center mb-2">
                                                <i class="fas fa-map-marker-alt text-orange-600 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                                                {{ request('destination', __('Riyadh')) }}, {{ __('Saudi Arabia') }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg font-semibold">
                                            <i class="fas fa-wifi {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('WiFi') }}
                                        </span>
                                        <span class="px-3 py-1 bg-green-50 text-green-700 text-xs rounded-lg font-semibold">
                                            <i class="fas fa-swimming-pool {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('Pool') }}
                                        </span>
                                        <span class="px-3 py-1 bg-purple-50 text-purple-700 text-xs rounded-lg font-semibold">
                                            <i class="fas fa-utensils {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('Restaurant') }}
                                        </span>
                                        <span class="px-3 py-1 bg-orange-50 text-orange-700 text-xs rounded-lg font-semibold">
                                            <i class="fas fa-parking {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('Parking') }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center mb-4">
                                        <div class="flex text-yellow-500 text-sm ml-2">
                                            @for($j = 0; $j < 5; $j++)
                                            <i class="fas fa-star"></i>
                                            @endfor
                                        </div>
                                        <span class="text-sm text-gray-500 {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">({{ 100 + $i * 20 }} {{ __('reviews') }})</span>
                                        <span class="text-sm text-gray-500">•</span>
                                        <span class="text-sm text-orange-600 font-semibold {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">{{ __('Free Cancellation') }}</span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                    <div>
                                        <div class="flex items-baseline">
                                            <span class="text-3xl font-extrabold text-orange-600">{{ 200 + $i * 50 }}</span>
                                            <span class="text-gray-500 text-sm {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">{{ __('SAR') }}</span>
                                        </div>
                                        <div class="text-xs text-gray-400">/ {{ __('per night') }} • {{ __('including taxes') }}</div>
                                    </div>
                                    <a href="{{ route('hotel.details', ['locale' => app()->getLocale(), 'id' => $i]) }}?check_in={{ request('check_in') }}&check_out={{ request('check_out') }}&guests={{ request('guests') }}" 
                                       class="bg-gradient-to-r from-orange-600 to-orange-600 text-white px-8 py-3 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition shadow-lg">
                                        {{ __('View Rooms') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
                
                <!-- Pagination -->
                <div class="mt-8 flex justify-center">
                    <div class="flex gap-2">
                        <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-orange-50 hover:border-orange-600 transition">{{ __('Previous') }}</button>
                        <button class="px-4 py-2 bg-orange-600 text-white rounded-lg">1</button>
                        <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-orange-50 hover:border-orange-600 transition">2</button>
                        <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-orange-50 hover:border-orange-600 transition">3</button>
                        <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-orange-50 hover:border-orange-600 transition">{{ __('Next') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('filterCheckIn')?.setAttribute('min', today);
    document.getElementById('filterCheckOut')?.setAttribute('min', today);

    // Update check-out minimum date when check-in changes
    document.getElementById('filterCheckIn')?.addEventListener('change', function() {
        const checkInDate = this.value;
        const checkOutInput = document.getElementById('filterCheckOut');
        if (checkOutInput && checkInDate) {
            const nextDay = new Date(checkInDate);
            nextDay.setDate(nextDay.getDate() + 1);
            checkOutInput.setAttribute('min', nextDay.toISOString().split('T')[0]);
            if (checkOutInput.value && checkOutInput.value <= checkInDate) {
                checkOutInput.value = nextDay.toISOString().split('T')[0];
            }
        }
    });

    // Apply filters button
    document.getElementById('applyFiltersBtn')?.addEventListener('click', function() {
        const checkIn = document.getElementById('filterCheckIn').value;
        const checkOut = document.getElementById('filterCheckOut').value;
        const currentUrl = new URL(window.location.href);
        
        // Update URL parameters
        if (checkIn) {
            currentUrl.searchParams.set('check_in', checkIn);
        } else {
            currentUrl.searchParams.delete('check_in');
        }
        
        if (checkOut) {
            currentUrl.searchParams.set('check_out', checkOut);
        } else {
            currentUrl.searchParams.delete('check_out');
        }
        
        // Reload page with new parameters
        window.location.href = currentUrl.toString();
    });
</script>
@endpush

