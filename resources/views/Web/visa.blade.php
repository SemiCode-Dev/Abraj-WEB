@extends('Web.layouts.app')

@section('title', __('Visa Service') . ' - ABRAJ STAY')

@section('content')
    <section class="bg-gradient-to-r from-orange-500 via-orange-600 to-blue-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ __('Visa Service') }}</h1>
            <p class="text-orange-100 text-lg max-w-2xl mx-auto">
                {{ __('Apply for your visa with us and get professional assistance.') }}
            </p>
        </div>
    </section>

    <section class="py-16 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6 text-center">
                    {{ __('Visa Service Request') }}
                </h2>

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

                <form action="{{ route('visa.book') }}" method="POST" class="space-y-6">
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
                        <div class="mb-6 intl-tel-input-container">
                            <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                {{ __('Phone Number') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="visaPhone" name="phone" value="{{ old('phone') }}" required
                                maxlength="11"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                            <input type="hidden" name="phone_country_code" id="visaPhoneCountryCode"
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
                            {{ __('Visa Type') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="visa_type" required
                            class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                            <option value="">{{ __('Select Visa Type') }}</option>
                            <option value="tourist" {{ old('visa_type') == 'tourist' ? 'selected' : '' }}>
                                {{ __('Tourist Visa') }}</option>
                            <option value="business" {{ old('visa_type') == 'business' ? 'selected' : '' }}>
                                {{ __('Business Visa') }}</option>
                            <option value="transit" {{ old('visa_type') == 'transit' ? 'selected' : '' }}>
                                {{ __('Transit Visa') }}</option>
                            <option value="work" {{ old('visa_type') == 'work' ? 'selected' : '' }}>
                                {{ __('Work Visa') }}</option>
                            <option value="student" {{ old('visa_type') == 'student' ? 'selected' : '' }}>
                                {{ __('Student Visa') }}</option>
                            <option value="other" {{ old('visa_type') == 'other' ? 'selected' : '' }}>{{ __('Other') }}
                            </option>
                        </select>
                        @error('visa_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                            {{ __('Nationality') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="nationalitySearchInput" autocomplete="off"
                                placeholder="{{ __('Select Nationality') }}"
                                class="w-full px-4 py-3 border-2 border-gray-100 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100 bg-white shadow-sm">
                            <input type="hidden" id="nationality_id" name="nationality_id"
                                value="{{ old('nationality_id') }}">
                            <div id="nationalityAutocomplete"
                                class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl max-h-60 overflow-y-auto hidden">
                            </div>
                            @error('nationality_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                            {{ __('Country') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="countrySearchInput" autocomplete="off"
                                placeholder="{{ __('Select Country') }}"
                                class="w-full px-4 py-3 border-2 border-gray-100 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100 bg-white shadow-sm">
                            <input type="hidden" id="country_id" name="country_id" value="{{ old('country_id') }}">
                            <div id="countryAutocomplete"
                                class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl max-h-60 overflow-y-auto hidden">
                            </div>
                            @error('country_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                            {{ __('Duration (Days)') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="duration" value="{{ old('duration') }}" required min="1"
                            max="365" placeholder="{{ __('Enter duration in days') }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                        @error('duration')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    @auth
                        <div class="mb-6">
                            <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                {{ __('Passport Number') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="passport_number" value="{{ old('passport_number') }}" required
                                placeholder="{{ __('Enter passport number') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                            @error('passport_number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endauth

                    <div class="mb-6">
                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                            {{ __('Comment') }}
                        </label>
                        <textarea name="comment" rows="4" placeholder="{{ __('Enter any additional comments or requirements') }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">{{ old('comment') }}</textarea>
                        @error('comment')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-4 rounded-xl font-bold text-lg hover:from-orange-600 hover:to-orange-700 transition shadow-lg flex items-center justify-center dark:from-orange-600 dark:to-orange-700">
                        <i class="fas fa-paper-plane {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                        {{ __('Submit Request') }}
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const visaPhoneInput = document.querySelector("#visaPhone");
            if (visaPhoneInput) {
                const iti = window.intlTelInput(visaPhoneInput, {
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

                visaPhoneInput.addEventListener("countrychange", function() {
                    const countryData = iti.getSelectedCountryData();
                    document.querySelector("#visaPhoneCountryCode").value = "+" + countryData.dialCode;
                });

                // Set initial value
                const initialCountryData = iti.getSelectedCountryData();
                document.querySelector("#visaPhoneCountryCode").value = "+" + initialCountryData.dialCode;
            }

            // Searchable Selectors Logic
            const allCountries = @json($countries->map(fn($c) => ['id' => $c->id, 'name' => $c->locale_name, 'nationality' => $c->locale_nationality]));

            function initSearchableSelector(options) {
                let {
                    inputEl,
                    hiddenEl,
                    resultsEl,
                    data = [],
                    mode = 'name'
                } = options;
                if (!inputEl || !hiddenEl || !resultsEl) return;

                function showResults(keyword = "") {
                    resultsEl.innerHTML = "";
                    const lowerK = keyword.toLowerCase();
                    const results = data.filter(item => {
                        const val = item[mode] || "";
                        return val.toLowerCase().includes(lowerK);
                    });

                    if (results.length === 0) {
                        resultsEl.classList.add("hidden");
                        return;
                    }

                    resultsEl.classList.remove("hidden");
                    results.forEach(item => {
                        const val = item[mode];
                        if (!val) return;
                        const div = document.createElement("div");
                        div.className =
                            "px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm border-b border-gray-50 dark:border-gray-700 last:border-0";
                        div.innerHTML = `<span class="font-medium">${val}</span>`;
                        div.addEventListener("click", () => {
                            inputEl.value = val;
                            hiddenEl.value = item.id;
                            resultsEl.classList.add("hidden");
                            hiddenEl.dispatchEvent(new Event("change"));
                        });
                        resultsEl.appendChild(div);
                    });
                }

                inputEl.addEventListener("focus", () => showResults(inputEl.value));
                inputEl.addEventListener("input", () => showResults(inputEl.value));

                document.addEventListener("click", (e) => {
                    if (!inputEl.contains(e.target) && !resultsEl.contains(e.target)) {
                        resultsEl.classList.add("hidden");
                    }
                });

                // Handle initial value
                if (hiddenEl.value) {
                    const initialItem = data.find(x => x.id == hiddenEl.value);
                    if (initialItem) {
                        inputEl.value = initialItem[mode];
                    }
                }
            }

            initSearchableSelector({
                inputEl: document.getElementById('nationalitySearchInput'),
                hiddenEl: document.getElementById('nationality_id'),
                resultsEl: document.getElementById('nationalityAutocomplete'),
                data: allCountries.filter(c => c.nationality),
                mode: 'nationality'
            });

            initSearchableSelector({
                inputEl: document.getElementById('countrySearchInput'),
                hiddenEl: document.getElementById('country_id'),
                resultsEl: document.getElementById('countryAutocomplete'),
                data: allCountries,
                mode: 'name'
            });
        });
    </script>
@endpush
