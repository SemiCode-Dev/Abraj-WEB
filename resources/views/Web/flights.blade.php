@extends('Web.layouts.app')

@section('title', __('Flight Booking') . ' - ABRAJ STAY')

@section('content')
    <section class="bg-gradient-to-r from-orange-500 via-orange-600 to-blue-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ __('Flight Booking') }}</h1>
            <p class="text-orange-100 text-lg max-w-2xl mx-auto">
                {{ __('Book your flight with us and enjoy the best travel experience.') }}
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

                <form action="{{ route('flights.book') }}" method="POST" class="space-y-6">
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
                    @else
                        <div
                            class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg">
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                {{ __('You are logged in as') }}: <strong>{{ auth()->user()->name }}</strong>
                                ({{ auth()->user()->email }})
                            </p>
                        </div>
                    @endif

                    <div class="mb-6 intl-tel-input-container">
                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                            {{ __('Phone Number') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" id="flightPhone" name="phone"
                            value="{{ old('phone', auth()->check() ? auth()->user()->phone : '') }}" required
                            maxlength="11"
                            class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                        <input type="hidden" name="phone_country_code" id="flightPhoneCountryCode"
                            value="{{ old('phone_country_code', auth()->check() ? auth()->user()->phone_country_code : '') }}">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @error('phone_country_code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                            {{ __('From') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <select name="origin_country_id" id="origin_country_id" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                                    <option value="">{{ __('Select Country') }}</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"
                                            {{ old('origin_country_id') == $country->id ? 'selected' : '' }}>
                                            {{ $country->locale_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('origin_country_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <select name="origin_city_id" id="origin_city_id" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100"
                                    disabled>
                                    <option value="">{{ __('Select City') }}</option>
                                </select>
                                @error('origin_city_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                            {{ __('Destination') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <select name="destination_country_id" id="destination_country_id" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                                    <option value="">{{ __('Select Country') }}</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"
                                            {{ old('destination_country_id') == $country->id ? 'selected' : '' }}>
                                            {{ $country->locale_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('destination_country_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <select name="destination_city_id" id="destination_city_id" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100"
                                    disabled>
                                    <option value="">{{ __('Select City') }}</option>
                                </select>
                                @error('destination_city_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                {{ __('Number of Adults') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="adults" value="{{ old('adults', 1) }}" required min="1"
                                max="20" placeholder="{{ __('Enter number of adults') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                            @error('adults')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                {{ __('Number of Children') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="children" value="{{ old('children', 0) }}" required min="0"
                                max="20" placeholder="0"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                            @error('children')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                {{ __('Departure Date') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="date" name="departure_date" value="{{ old('departure_date') }}" required
                                    min="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                            </div>
                            @error('departure_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                {{ __('Return Date') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="date" name="return_date" value="{{ old('return_date') }}" required
                                    min="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                            </div>
                            @error('return_date')
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
        <script src="{{ asset('js/dynamic-selector.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const flightPhoneInput = document.querySelector("#flightPhone");
                if (flightPhoneInput) {
                    const iti = window.intlTelInput(flightPhoneInput, {
                        initialCountry: "{{ auth()->check() && auth()->user()->phone_country_code ? strtolower(auth()->user()->phone_country_code) : 'sa' }}",
                        separateDialCode: true,
                        countrySearch: false,
                        geoIpLookup: function(callback) {
                            fetch("https://ipapi.co/json")
                                .then(res => res.json())
                                .then(data => callback(data.country_code))
                                .catch(() => callback("sa"));
                        },
                        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                    });

                    flightPhoneInput.addEventListener("countrychange", function() {
                        const countryData = iti.getSelectedCountryData();
                        document.querySelector("#flightPhoneCountryCode").value = countryData.iso2
                            .toUpperCase();
                    });

                    // Set initial value
                    const initialCountryData = iti.getSelectedCountryData();
                    document.querySelector("#flightPhoneCountryCode").value = initialCountryData.iso2.toUpperCase();
                }

                // Initialize Dynamic City Selector for Origin
                new DynamicSelector({
                    countrySelector: '#origin_country_id',
                    citySelector: '#origin_city_id',
                    // Use {id} as placeholder, ensuring it matches JS replacement
                    apiUrl: '/{{ app()->getLocale() }}/locations/countries/{id}/cities',
                    placeholder: '{{ __('Select City') }}',
                    loadingText: '{{ __('Loading...') }}',
                    errorText: '{{ __('Error loading cities') }}',
                    initialCityId: '{{ old('origin_city_id') }}'
                });

                // Initialize Dynamic City Selector for Destination
                new DynamicSelector({
                    countrySelector: '#destination_country_id',
                    citySelector: '#destination_city_id',
                    apiUrl: '/{{ app()->getLocale() }}/locations/countries/{id}/cities',
                    placeholder: '{{ __('Select City') }}',
                    loadingText: '{{ __('Loading...') }}',
                    errorText: '{{ __('Error loading cities') }}',
                    initialCityId: '{{ old('destination_city_id') }}'
                });

                // Set minimum return date based on departure date
                const departureDateInput = document.querySelector('input[name="departure_date"]');
                const returnDateInput = document.querySelector('input[name="return_date"]');

                departureDateInput.addEventListener('change', function() {
                    if (this.value) {
                        const departureDate = new Date(this.value);
                        departureDate.setDate(departureDate.getDate() + 1);
                        returnDateInput.min = departureDate.toISOString().split('T')[0];

                        if (returnDateInput.value && returnDateInput.value <= this.value) {
                            returnDateInput.value = '';
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
