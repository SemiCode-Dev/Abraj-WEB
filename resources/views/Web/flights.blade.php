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
                        <div class="mb-6 intl-tel-input-container">
                            <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                {{ __('Phone Number') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="flightPhone" name="phone" value="{{ old('phone') }}" required
                                maxlength="11"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                            <input type="hidden" name="phone_country_code" id="flightPhoneCountryCode"
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
                            {{ __('From') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="relative">
                                <input type="text" id="originCountrySearchInput" autocomplete="off"
                                    placeholder="{{ __('Select Country') }}"
                                    class="w-full px-4 py-3 border-2 border-gray-100 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100 bg-white">
                                <input type="hidden" id="origin_country_id" name="origin_country_id"
                                    value="{{ old('origin_country_id') }}">
                                <div id="originCountryAutocomplete"
                                    class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl max-h-60 overflow-y-auto hidden">
                                </div>
                                @error('origin_country_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="relative">
                                <input type="text" id="originAirportSearchInput" autocomplete="off"
                                    placeholder="{{ __('Select Airport') }}"
                                    class="w-full px-4 py-3 border-2 border-gray-100 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100 bg-white"
                                    disabled>
                                <input type="hidden" id="origin_airport_id" name="origin_airport_id"
                                    value="{{ old('origin_airport_id') }}">
                                <div id="originAirportAutocomplete"
                                    class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl max-h-60 overflow-y-auto hidden">
                                </div>
                                @error('origin_airport_id')
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
                            <div class="relative">
                                <input type="text" id="destinationCountrySearchInput" autocomplete="off"
                                    placeholder="{{ __('Select Country') }}"
                                    class="w-full px-4 py-3 border-2 border-gray-100 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100 bg-white">
                                <input type="hidden" id="destination_country_id" name="destination_country_id"
                                    value="{{ old('destination_country_id') }}">
                                <div id="destinationCountryAutocomplete"
                                    class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl max-h-60 overflow-y-auto hidden">
                                </div>
                                @error('destination_country_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="relative">
                                <input type="text" id="destinationAirportSearchInput" autocomplete="off"
                                    placeholder="{{ __('Select Airport') }}"
                                    class="w-full px-4 py-3 border-2 border-gray-100 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100 bg-white"
                                    disabled>
                                <input type="hidden" id="destination_airport_id" name="destination_airport_id"
                                    value="{{ old('destination_airport_id') }}">
                                <div id="destinationAirportAutocomplete"
                                    class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl max-h-60 overflow-y-auto hidden">
                                </div>
                                @error('destination_airport_id')
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
                            <input type="number" name="children" value="{{ old('children', 0) }}" required
                                min="0" max="20" placeholder="0"
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
                        document.querySelector("#flightPhoneCountryCode").value = "+" + countryData.dialCode;
                    });

                    // Set initial value
                    const initialCountryData = iti.getSelectedCountryData();
                    document.querySelector("#flightPhoneCountryCode").value = "+" + initialCountryData.dialCode;
                }

                const allCountries = @json($countries->map(fn($c) => ['id' => $c->id, 'name' => $c->locale_name]));

                function initSearchableSelector(options) {
                    let {
                        inputEl,
                        hiddenEl,
                        resultsEl,
                        data = [],
                        onSelect,
                        placeholder
                    } = options;
                    if (!inputEl || !hiddenEl || !resultsEl) return;

                    let items = data;

                    function showResults(keyword = "") {
                        resultsEl.innerHTML = "";
                        const lowerK = keyword.toLowerCase();
                        const results = items.filter(item => {
                            const name = item.name || item.locale_name || "";
                            return name.toLowerCase().includes(lowerK);
                        });

                        if (results.length === 0) {
                            resultsEl.classList.add("hidden");
                            return;
                        }

                        resultsEl.classList.remove("hidden");
                        results.forEach(item => {
                            const div = document.createElement("div");
                            div.className =
                                "px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm";
                            div.innerHTML = `<span class="font-medium">${item.name}</span>`;
                            div.addEventListener("click", () => {
                                inputEl.value = item.name;
                                hiddenEl.value = item.id;
                                resultsEl.classList.add("hidden");
                                if (onSelect) onSelect(item);
                                hiddenEl.dispatchEvent(new Event("change"));
                            });
                            resultsEl.appendChild(div);
                        });
                    }

                    inputEl.addEventListener("focus", () => showResults(inputEl.value));
                    inputEl.addEventListener("input", () => showResults(inputEl.value));

                    // Click outside to close
                    document.addEventListener("click", (e) => {
                        if (!inputEl.contains(e.target) && !resultsEl.contains(e.target)) {
                            resultsEl.classList.add("hidden");
                        }
                    });

                    return {
                        updateData: (newData, clearInput = true) => {
                            items = newData;
                            if (clearInput) {
                                inputEl.value = "";
                                hiddenEl.value = "";
                            }
                            inputEl.disabled = false;
                            inputEl.placeholder = placeholder || "";
                        },
                        disable: (msg) => {
                            inputEl.disabled = true;
                            inputEl.placeholder = msg || "";
                            inputEl.value = "";
                            hiddenEl.value = "";
                        },
                        setLoading: (msg) => {
                            inputEl.disabled = true;
                            inputEl.placeholder = msg || "{{ __('Loading...') }}";
                        }
                    };
                }

                // --- Origin Logic ---
                const originCountrySelector = initSearchableSelector({
                    inputEl: document.getElementById('originCountrySearchInput'),
                    hiddenEl: document.getElementById('origin_country_id'),
                    resultsEl: document.getElementById('originCountryAutocomplete'),
                    data: allCountries,
                    placeholder: '{{ __('Select Country') }}'
                });

                const originAirportSelector = initSearchableSelector({
                    inputEl: document.getElementById('originAirportSearchInput'),
                    hiddenEl: document.getElementById('origin_airport_id'),
                    resultsEl: document.getElementById('originAirportAutocomplete'),
                    placeholder: '{{ __('Select Airport') }}'
                });

                document.getElementById('origin_country_id').addEventListener('change', function() {
                    const countryId = this.value;
                    if (!countryId) {
                        originAirportSelector.disable('{{ __('Select Airport') }}');
                        return;
                    }

                    originAirportSelector.setLoading('{{ __('Loading...') }}');
                    fetch(`/{{ app()->getLocale() }}/locations/countries/${countryId}/airports?v=v10`)
                        .then(res => res.json())
                        .then(data => {
                            originAirportSelector.updateData(data, true);
                        })
                        .catch(() => {
                            originAirportSelector.disable('{{ __('Error loading airports') }}');
                        });
                });

                // --- Destination Logic ---
                const destinationCountrySelector = initSearchableSelector({
                    inputEl: document.getElementById('destinationCountrySearchInput'),
                    hiddenEl: document.getElementById('destination_country_id'),
                    resultsEl: document.getElementById('destinationCountryAutocomplete'),
                    data: allCountries,
                    placeholder: '{{ __('Select Country') }}'
                });

                const destinationAirportSelector = initSearchableSelector({
                    inputEl: document.getElementById('destinationAirportSearchInput'),
                    hiddenEl: document.getElementById('destination_airport_id'),
                    resultsEl: document.getElementById('destinationAirportAutocomplete'),
                    placeholder: '{{ __('Select Airport') }}'
                });

                document.getElementById('destination_country_id').addEventListener('change', function() {
                    const countryId = this.value;
                    if (!countryId) {
                        destinationAirportSelector.disable('{{ __('Select Airport') }}');
                        return;
                    }

                    destinationAirportSelector.setLoading('{{ __('Loading...') }}');
                    fetch(`/{{ app()->getLocale() }}/locations/countries/${countryId}/airports?v=v10`)
                        .then(res => res.json())
                        .then(data => {
                            destinationAirportSelector.updateData(data, true);
                        })
                        .catch(() => {
                            destinationAirportSelector.disable('{{ __('Error loading airports') }}');
                        });
                });

                // Handle initial values (for old input/validation errors)
                const initialOriginCountryId = '{{ old('origin_country_id') }}';
                if (initialOriginCountryId) {
                    const c = allCountries.find(x => x.id == initialOriginCountryId);
                    if (c) {
                        document.getElementById('originCountrySearchInput').value = c.name;
                        // Trigger the change to load airports
                        document.getElementById('origin_country_id').dispatchEvent(new Event('change'));
                        // Wait for airports to load then set the airport name if exists
                        const initialOriginAirportId = '{{ old('origin_airport_id') }}';
                        if (initialOriginAirportId) {
                            // This is tricky because fetch is async. 
                            // We'd need to handle this in the fetch callback.
                        }
                    }
                }

                const initialDestCountryId = '{{ old('destination_country_id') }}';
                if (initialDestCountryId) {
                    const c = allCountries.find(x => x.id == initialDestCountryId);
                    if (c) {
                        document.getElementById('destinationCountrySearchInput').value = c.name;
                        document.getElementById('destination_country_id').dispatchEvent(new Event('change'));
                    }
                }

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
