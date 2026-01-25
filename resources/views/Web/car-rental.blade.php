@extends('Web.layouts.app')

@section('title', __('Book Your Ride') . ' - ABRAJ STAY')

@section('content')
    <section class="bg-gradient-to-r from-orange-500 via-orange-600 to-blue-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                <i class="fas fa-car {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                {{ __('Book Your Ride') }}
            </h1>
            <p class="text-orange-100 text-lg max-w-2xl mx-auto">
                {{ __('Reliable transportation within cities and between destinations, with comfort and safety.') }}
            </p>
        </div>
    </section>

    <section class="py-16 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                <!-- <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6 text-center">
                                                                                {{ __('Booking Form') }}
                                                                            </h2> -->

                @if (session('success'))
                    <div
                        class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="mb-6 p-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('car-rental.book') }}" method="POST" class="space-y-6">
                    @csrf

                    @if (!auth()->check())
                        <div class="mb-6">
                            <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                {{ __('Name') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                placeholder="{{ __('Enter your name') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                {{ __('Email') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                placeholder="{{ __('Enter your email') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-6 intl-tel-input-container">
                            <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                {{ __('Phone Number') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="carPhone" name="phone" value="{{ old('phone') }}" required
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                            <input type="hidden" name="phone_country_code" id="carPhoneCountryCode"
                                value="{{ old('phone_country_code') }}">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            @error('phone_country_code')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @else
                        <div
                            class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg">
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                {{ __('You are logged in as') }}: <strong>{{ auth()->user()->name }}</strong>
                                ({{ auth()->user()->email }})
                            </p>
                        </div>
                    @endif

                    <div class="mb-6">
                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                            {{ __('Destination') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="relative">
                                <label class="block text-gray-700 dark:text-gray-300 text-xs font-bold mb-2 uppercase">
                                    {{ __('Country') }}
                                </label>
                                <select id="destination_country_id" name="destination_country_id"
                                    class="w-full px-4 py-3 border-2 border-gray-100 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100 bg-white appearance-none cursor-pointer">
                                    <option value="">{{ __('Select Country') }}</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"
                                            {{ old('destination_country_id') == $country->id ? 'selected' : '' }}>
                                            {{ $country->locale_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="absolute {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} top-11 text-gray-400 pointer-events-none">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                                @error('destination_country_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="relative">
                                <label class="block text-gray-700 dark:text-gray-300 text-xs font-bold mb-2 uppercase">
                                    {{ __('City') }}
                                </label>
                                <select id="destination_city_id" name="destination_city_id" disabled
                                    class="w-full px-4 py-3 border-2 border-gray-100 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100 bg-white appearance-none cursor-pointer">
                                    <option value="">{{ __('Select City') }}</option>
                                </select>
                                <div
                                    class="absolute {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} top-11 text-gray-400 pointer-events-none">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                                @error('destination_city_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                            {{ __('Driver Option') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="driver_option" required
                            class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                            <option value="without_driver"
                                {{ old('driver_option') === 'without_driver' ? 'selected' : '' }}>
                                {{ __('Without Driver') }}</option>
                            <option value="with_driver" {{ old('driver_option') === 'with_driver' ? 'selected' : '' }}>
                                {{ __('With Driver') }}</option>
                        </select>
                        @error('driver_option')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                {{ __('Pickup Date') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="pickup_date" value="{{ old('pickup_date') }}" required
                                min="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                            @error('pickup_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                {{ __('Pickup Time') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="pickup_time" value="{{ old('pickup_time') }}" required
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                            @error('pickup_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                {{ __('Return Date') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="return_date" value="{{ old('return_date') }}" required
                                min="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                            @error('return_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                {{ __('Return Time') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="return_time" value="{{ old('return_time') }}" required
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                            @error('return_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-4 rounded-xl font-bold text-lg hover:from-orange-600 hover:to-orange-700 transition shadow-lg flex items-center justify-center dark:from-orange-600 dark:to-orange-700">
                        <i class="fas fa-paper-plane {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                        {{ __('Submit Booking') }}
                    </button>
                </form>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Use shared phone validation utility
                if (window.initPhoneValidation) {
                    window.initPhoneValidation('carPhone', 'carPhoneCountryCode', {
                        initialCountry: "{{ auth()->check() && auth()->user()->phone_country_code ? strtolower(str_replace('+', '', auth()->user()->phone_country_code)) : 'sa' }}"
                    });
                }

                const countrySelect = document.getElementById('destination_country_id');
                const citySelect = document.getElementById('destination_city_id');

                function loadCities(countryId, initialCityId = null) {
                    if (!countryId) {
                        citySelect.innerHTML = '<option value="">{{ __('Select Country First') }}</option>';
                        citySelect.disabled = true;
                        return;
                    }

                    citySelect.disabled = false;
                    citySelect.innerHTML = '<option value="">{{ __('Loading...') }}</option>';

                    fetch(`/{{ app()->getLocale() }}/locations/countries/${countryId}/cities?v=v10`)
                        .then(res => res.json())
                        .then(data => {
                            citySelect.innerHTML = '<option value="">{{ __('Select City') }}</option>';
                            data.forEach(city => {
                                const option = document.createElement('option');
                                option.value = city.id;
                                option.text = city.name;
                                if (initialCityId && initialCityId == city.id) {
                                    option.selected = true;
                                }
                                citySelect.appendChild(option);
                            });
                        })
                        .catch(() => {
                            citySelect.innerHTML = '<option value="">{{ __('Error loading cities') }}</option>';
                        });
                }

                if (countrySelect) {
                    countrySelect.addEventListener('change', function() {
                        loadCities(this.value);
                    });

                    // Initial Load (e.g. Validation Error or Edit)
                    if (countrySelect.value) {
                        loadCities(countrySelect.value, '{{ old('destination_city_id') }}');
                    }
                }

                const pickupDateInput = document.querySelector('input[name="pickup_date"]');
                const returnDateInput = document.querySelector('input[name="return_date"]');

                // Set minimum return date based on pickup date
                pickupDateInput.addEventListener('change', function() {
                    if (this.value && returnDateInput) {
                        const pickupDate = new Date(this.value);
                        pickupDate.setDate(pickupDate.getDate() + 1);
                        returnDateInput.min = pickupDate.toISOString().split('T')[0];

                        if (returnDateInput.value && returnDateInput.value <= this.value) {
                            returnDateInput.value = '';
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
