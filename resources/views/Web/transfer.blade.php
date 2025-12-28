@extends('Web.layouts.app')

@section('title', __('Transfer Booking') . ' - ABRAJ STAY')

@section('content')
    <section class="bg-gradient-to-r from-orange-500 via-orange-600 to-blue-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ __('Transfer Booking') }}</h1>
            <p class="text-orange-100 text-lg max-w-2xl mx-auto">
                {{ __('Book your transfer service and enjoy comfortable transportation.') }}
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

                <form action="{{ route('transfer.book') }}" method="POST" class="space-y-6">
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
                        <input type="tel" id="transferPhone" name="phone"
                            value="{{ old('phone', auth()->check() ? auth()->user()->phone : '') }}" required
                            maxlength="11"
                            class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                        <input type="hidden" name="phone_country_code" id="transferPhoneCountryCode"
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

                    <div class="mb-6">
                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                            {{ __('Trip Type') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-4">
                            <label class="flex items-center">
                                <input type="radio" name="trip_type" value="go"
                                    {{ old('trip_type', 'go') == 'go' ? 'checked' : '' }} required
                                    class="mr-2 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }} text-orange-500 focus:ring-orange-500">
                                <span class="text-gray-700 dark:text-gray-300">{{ __('Go') }}</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="trip_type" value="go_and_back"
                                    {{ old('trip_type') == 'go_and_back' ? 'checked' : '' }} required
                                    class="mr-2 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }} text-orange-500 focus:ring-orange-500">
                                <span class="text-gray-700 dark:text-gray-300">{{ __('Go and Back') }}</span>
                            </label>
                        </div>
                        @error('trip_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                {{ __('Transfer Date') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="transfer_date" value="{{ old('transfer_date') }}" required
                                min="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                            @error('transfer_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                {{ __('Transfer Time') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="transfer_time" value="{{ old('transfer_time') }}" required
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                            @error('transfer_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div id="return-fields" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6" style="display: none;">
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                {{ __('Return Date') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="return_date" value="{{ old('return_date') }}"
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
                            <input type="time" name="return_time" value="{{ old('return_time') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                            @error('return_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                            {{ __('Number of Passengers') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="passengers" value="{{ old('passengers', 1) }}" required
                            min="1" max="50" placeholder="{{ __('Enter number of passengers') }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                        @error('passengers')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
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
                const transferPhoneInput = document.querySelector("#transferPhone");
                if (transferPhoneInput) {
                    const iti = window.intlTelInput(transferPhoneInput, {
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

                    transferPhoneInput.addEventListener("countrychange", function() {
                        const countryData = iti.getSelectedCountryData();
                        document.querySelector("#transferPhoneCountryCode").value = countryData.iso2
                            .toUpperCase();
                    });

                    // Set initial value
                    const initialCountryData = iti.getSelectedCountryData();
                    document.querySelector("#transferPhoneCountryCode").value = initialCountryData.iso2.toUpperCase();
                }

                // Initialize Dynamic City Selector
                new DynamicSelector({
                    countrySelector: '#destination_country_id',
                    citySelector: '#destination_city_id',
                    apiUrl: '/{{ app()->getLocale() }}/locations/countries/{id}/cities',
                    placeholder: '{{ __('Select City') }}',
                    loadingText: '{{ __('Loading...') }}',
                    errorText: '{{ __('Error loading cities') }}',
                    initialCityId: '{{ old('destination_city_id') }}'
                });

                const tripTypeRadios = document.querySelectorAll('input[name="trip_type"]');
                const returnFields = document.getElementById('return-fields');
                const returnDateInput = document.querySelector('input[name="return_date"]');
                const returnTimeInput = document.querySelector('input[name="return_time"]');
                const transferDateInput = document.querySelector('input[name="transfer_date"]');

                // Toggle return fields based on trip type
                tripTypeRadios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        if (this.value === 'go_and_back') {
                            returnFields.style.display = 'grid';
                            returnDateInput.setAttribute('required', 'required');
                            returnTimeInput.setAttribute('required', 'required');
                        } else {
                            returnFields.style.display = 'none';
                            returnDateInput.removeAttribute('required');
                            returnTimeInput.removeAttribute('required');
                            returnDateInput.value = '';
                            returnTimeInput.value = '';
                        }
                    });
                });

                // Set minimum return date based on transfer date
                transferDateInput.addEventListener('change', function() {
                    if (this.value && returnDateInput) {
                        const transferDate = new Date(this.value);
                        transferDate.setDate(transferDate.getDate() + 1);
                        returnDateInput.min = transferDate.toISOString().split('T')[0];

                        if (returnDateInput.value && returnDateInput.value <= this.value) {
                            returnDateInput.value = '';
                        }
                    }
                });

                // Initialize return fields visibility
                const selectedTripType = document.querySelector('input[name="trip_type"]:checked');
                if (selectedTripType && selectedTripType.value === 'go_and_back') {
                    returnFields.style.display = 'grid';
                    returnDateInput.setAttribute('required', 'required');
                    returnTimeInput.setAttribute('required', 'required');
                }
            });
        </script>
    @endpush
@endsection
