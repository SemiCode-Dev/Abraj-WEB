@extends('Web.layouts.app')

@section('title', __('Contact Us') . ' - ABRAJ STAY')

@section('content')
    <section class="bg-gradient-to-r from-orange-500 via-orange-600 to-blue-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ __('Contact Us') }}</h1>
            <p class="text-orange-100 text-lg max-w-2xl mx-auto">
                {{ __('We\'d love to hear from you. Send us a message and we\'ll respond as soon as possible.') }}
            </p>
        </div>
    </section>

    <section class="py-16 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">{{ __('Get in Touch') }}</h2>
                        <form id="contactForm" class="space-y-6">
                            @if (!auth()->check())
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                            {{ __('Your Name') }} <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="name" required
                                            class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                            {{ __('Your Email') }} <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" name="email" required
                                            class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                                    </div>
                                </div>

                                <div class="mb-6 intl-tel-input-container">
                                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                        {{ __('Your Phone') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" id="contactPhone" name="phone" required maxlength="15"
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                                    <input type="hidden" name="phone_country_code" id="contactPhoneCountryCode">
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

                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                    {{ __('Subject') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="subject" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                            </div>
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                    {{ __('Your Message') }} <span class="text-red-500">*</span>
                                </label>
                                <textarea name="message" rows="6" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100"></textarea>
                            </div>
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-4 rounded-xl font-bold text-lg hover:from-orange-600 hover:to-orange-700 transition shadow-lg flex items-center justify-center dark:from-orange-600 dark:to-orange-700">
                                <i class="fas fa-paper-plane {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                                {{ __('Send Message') }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Contact Info Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">{{ __('Contact Information') }}
                        </h3>

                        <div class="space-y-6">
                            <!-- Phone -->
                            <div class="flex items-start">
                                <div
                                    class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-phone text-orange-600 text-xl"></i>
                                </div>
                                <div class="{{ app()->getLocale() === 'ar' ? 'mr-4' : 'ml-4' }}">
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-1">{{ __('Call Us') }}</h4>
                                    <a href="tel:+966123456789"
                                        class="text-gray-600 dark:text-gray-400 hover:text-orange-600 transition">
                                        +966 12 345 6789
                                    </a>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="flex items-start">
                                <div
                                    class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-envelope text-orange-600 text-xl"></i>
                                </div>
                                <div class="{{ app()->getLocale() === 'ar' ? 'mr-4' : 'ml-4' }}">
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-1">{{ __('Email Us') }}</h4>
                                    <a href="mailto:info@abrajstay.com"
                                        class="text-gray-600 dark:text-gray-400 hover:text-orange-600 transition">
                                        info@abrajstay.com
                                    </a>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="flex items-start">
                                <div
                                    class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-map-marker-alt text-orange-600 text-xl"></i>
                                </div>
                                <div class="{{ app()->getLocale() === 'ar' ? 'mr-4' : 'ml-4' }}">
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-1">{{ __('Visit Us') }}</h4>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                                        {{ __('Riyadh') }}, {{ __('Saudi Arabia') }}<br>
                                        King Fahd Road, Building 123
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Social Media Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">{{ __('Follow Us') }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-6">
                            {{ __('Connect with us on social media') }}</p>
                        <div class="grid grid-cols-2 gap-4">
                            <a href="#" target="_blank"
                                class="flex items-center justify-center p-4 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 rounded-xl transition group">
                                <i class="fab fa-facebook-f text-blue-600 text-2xl group-hover:scale-110 transition"></i>
                                <span
                                    class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} font-semibold text-gray-900 dark:text-white">Facebook</span>
                            </a>
                            <a href="#" target="_blank"
                                class="flex items-center justify-center p-4 bg-sky-50 dark:bg-sky-900/20 hover:bg-sky-100 dark:hover:bg-sky-900/40 rounded-xl transition group">
                                <i class="fab fa-twitter text-sky-500 text-2xl group-hover:scale-110 transition"></i>
                                <span
                                    class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} font-semibold text-gray-900 dark:text-white">Twitter</span>
                            </a>
                            <a href="#" target="_blank"
                                class="flex items-center justify-center p-4 bg-pink-50 dark:bg-pink-900/20 hover:bg-pink-100 dark:hover:bg-pink-900/40 rounded-xl transition group">
                                <i class="fab fa-instagram text-pink-600 text-2xl group-hover:scale-110 transition"></i>
                                <span
                                    class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} font-semibold text-gray-900 dark:text-white">Instagram</span>
                            </a>
                            <a href="#" target="_blank"
                                class="flex items-center justify-center p-4 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 rounded-xl transition group">
                                <i class="fab fa-youtube text-red-600 text-2xl group-hover:scale-110 transition"></i>
                                <span
                                    class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} font-semibold text-gray-900 dark:text-white">YouTube</span>
                            </a>
                            <a href="#" target="_blank"
                                class="flex items-center justify-center p-4 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 rounded-xl transition group">
                                <i class="fab fa-linkedin-in text-blue-700 text-2xl group-hover:scale-110 transition"></i>
                                <span
                                    class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} font-semibold text-gray-900 dark:text-white">LinkedIn</span>
                            </a>
                            <a href="#" target="_blank"
                                class="flex items-center justify-center p-4 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40 rounded-xl transition group">
                                <i class="fab fa-whatsapp text-green-600 text-2xl group-hover:scale-110 transition"></i>
                                <span
                                    class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} font-semibold text-gray-900 dark:text-white">WhatsApp</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const contactPhoneInput = document.querySelector("#contactPhone");
            if (contactPhoneInput) {
                const iti = window.intlTelInput(contactPhoneInput, {
                    initialCountry: "{{ auth()->check() ? strtolower(auth()->user()->phone_country_code ?? 'sa') : 'sa' }}",
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

                @if (auth()->check())
                    iti.setNumber("{{ auth()->user()->phone }}");
                @endif

                contactPhoneInput.addEventListener("countrychange", function() {
                    const countryData = iti.getSelectedCountryData();
                    document.querySelector("#contactPhoneCountryCode").value = "+" + countryData.dialCode;
                });

                // Set initial value
                const initialCountryData = iti.getSelectedCountryData();
                document.querySelector("#contactPhoneCountryCode").value = "+" + initialCountryData.dialCode;
            }
        });

        // Contact form submission
        document.getElementById('contactForm')?.addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnContent = submitBtn.innerHTML;

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> {{ __('Sending...') }}';

            // Get form data
            const formData = new FormData(form);

            fetch("{{ route('contact.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        showToast(data.message, 'success');
                        form.reset();
                        // If user is logged in, values will be reset but we want to keep them
                        @if (auth()->check())
                            window.location.reload();
                        @endif
                    } else {
                        showToast(data.message || '{{ __('Something went wrong') }}', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('{{ __('Failed to send message') }}', 'error');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnContent;
                });
        });
    </script>
@endpush
