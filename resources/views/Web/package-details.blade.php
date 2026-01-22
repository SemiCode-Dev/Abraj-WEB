@extends('Web.layouts.app')

@section('title', $package->locale_title)

@section('content')
    <!-- Package Hero Image -->
    <section class="relative h-96 overflow-hidden">
        <img src="{{ $package->image ? asset('storage/' . $package->image) : 'https://images.unsplash.com/photo-1551884170-09fb70a3a2ed?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80' }}"
            alt="{{ $package->locale_title }}" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-2">{{ $package->locale_title }}</h1>
            @if ($package->price)
                <p class="text-2xl font-bold text-orange-400">{{ number_format($package->price, 2) }} {{ __('USD') }}</p>
            @endif
        </div>
    </section>

    <!-- Package Details -->
    <section class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    @if ($package->locale_description)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ __('Description') }}</h2>
                            <p class="text-gray-600 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                                {{ $package->locale_description }}
                            </p>
                        </div>
                    @endif

                    @if ($package->locale_details)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ __('Package Details') }}
                            </h2>
                            <div class="text-gray-600 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                                {!! nl2br(e($package->locale_details)) !!}
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Contact Form Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 sticky top-24">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ __('Contact Us') }}</h2>

                        @if (session('success'))
                            <div
                                class="mb-4 p-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 rounded-lg">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div
                                class="mb-4 p-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 rounded-lg">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form
                            action="{{ route('package.contact', ['id' => $package->id, 'locale' => app()->getLocale()]) }}"
                            method="POST">
                            @csrf

                            @if (!auth()->check())
                                <div class="mb-4">
                                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                        {{ __('Full Name') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" value="{{ old('name') }}" required
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                        {{ __('Email') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="email" value="{{ old('email') }}" required
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4 intl-tel-input-container">
                                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                        {{ __('Phone') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" id="packagePhone" name="phone" value="{{ old('phone') }}"
                                        required maxlength="11"
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                                    <input type="hidden" name="phone_country_code" id="packagePhoneCountryCode"
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
                                    <p class="text-sm text-blue-700 dark:text-blue-300 text-center">
                                        {{ __('You are logged in as') }}:<br><strong>{{ auth()->user()->name }}</strong>
                                    </p>
                                </div>
                            @endif

                            <div class="mb-6">
                                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                    {{ __('Message') }} ({{ __('Optional') }})
                                </label>
                                <textarea name="message" rows="4"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-3 rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 transition shadow-lg">
                                <i class="fas fa-paper-plane {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                                {{ __('Send Inquiry') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const packagePhoneInput = document.querySelector("#packagePhone");
            if (packagePhoneInput) {
                const iti = window.intlTelInput(packagePhoneInput, {
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

                packagePhoneInput.addEventListener("countrychange", function() {
                    const countryData = iti.getSelectedCountryData();
                    document.querySelector("#packagePhoneCountryCode").value = "+" + countryData.dialCode;
                });

                // Set initial value
                const initialCountryData = iti.getSelectedCountryData();
                document.querySelector("#packagePhoneCountryCode").value = "+" + initialCountryData.dialCode;
            }
        });
    </script>
@endpush
