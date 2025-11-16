@extends('layouts.app')

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

<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">{{ __('Get in Touch') }}</h2>
                    <form id="contactForm" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    {{ __('Your Name') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    {{ __('Your Email') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                {{ __('Your Phone') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="phone" required
                                placeholder="{{ app()->getLocale() === 'ar' ? '05xxxxxxxx' : '+966 5x xxx xxxx' }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                {{ __('Subject') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="subject" required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                {{ __('Your Message') }} <span class="text-red-500">*</span>
                            </label>
                            <textarea name="message" rows="6" required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900"></textarea>
                        </div>
                        <button type="submit" 
                            class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-4 rounded-xl font-bold text-lg hover:from-orange-600 hover:to-orange-700 transition shadow-lg flex items-center justify-center">
                            <i class="fas fa-paper-plane {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                            {{ __('Send Message') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Contact Info Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">{{ __('Contact Information') }}</h3>
                    
                    <div class="space-y-6">
                        <!-- Phone -->
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone text-orange-600 text-xl"></i>
                            </div>
                            <div class="{{ app()->getLocale() === 'ar' ? 'mr-4' : 'ml-4' }}">
                                <h4 class="font-semibold text-gray-900 mb-1">{{ __('Call Us') }}</h4>
                                <a href="tel:+966123456789" class="text-gray-600 hover:text-orange-600 transition">
                                    +966 12 345 6789
                                </a>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-orange-600 text-xl"></i>
                            </div>
                            <div class="{{ app()->getLocale() === 'ar' ? 'mr-4' : 'ml-4' }}">
                                <h4 class="font-semibold text-gray-900 mb-1">{{ __('Email Us') }}</h4>
                                <a href="mailto:info@abrajstay.com" class="text-gray-600 hover:text-orange-600 transition">
                                    info@abrajstay.com
                                </a>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-orange-600 text-xl"></i>
                            </div>
                            <div class="{{ app()->getLocale() === 'ar' ? 'mr-4' : 'ml-4' }}">
                                <h4 class="font-semibold text-gray-900 mb-1">{{ __('Visit Us') }}</h4>
                                <p class="text-gray-600 text-sm">
                                    {{ __('Riyadh') }}, {{ __('Saudi Arabia') }}<br>
                                    King Fahd Road, Building 123
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Social Media Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">{{ __('Follow Us') }}</h3>
                    <p class="text-gray-600 text-sm mb-6">{{ __('Connect with us on social media') }}</p>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="#" target="_blank" 
                           class="flex items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-xl transition group">
                            <i class="fab fa-facebook-f text-blue-600 text-2xl group-hover:scale-110 transition"></i>
                            <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} font-semibold text-gray-900">Facebook</span>
                        </a>
                        <a href="#" target="_blank" 
                           class="flex items-center justify-center p-4 bg-sky-50 hover:bg-sky-100 rounded-xl transition group">
                            <i class="fab fa-twitter text-sky-500 text-2xl group-hover:scale-110 transition"></i>
                            <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} font-semibold text-gray-900">Twitter</span>
                        </a>
                        <a href="#" target="_blank" 
                           class="flex items-center justify-center p-4 bg-pink-50 hover:bg-pink-100 rounded-xl transition group">
                            <i class="fab fa-instagram text-pink-600 text-2xl group-hover:scale-110 transition"></i>
                            <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} font-semibold text-gray-900">Instagram</span>
                        </a>
                        <a href="#" target="_blank" 
                           class="flex items-center justify-center p-4 bg-red-50 hover:bg-red-100 rounded-xl transition group">
                            <i class="fab fa-youtube text-red-600 text-2xl group-hover:scale-110 transition"></i>
                            <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} font-semibold text-gray-900">YouTube</span>
                        </a>
                        <a href="#" target="_blank" 
                           class="flex items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-xl transition group">
                            <i class="fab fa-linkedin-in text-blue-700 text-2xl group-hover:scale-110 transition"></i>
                            <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} font-semibold text-gray-900">LinkedIn</span>
                        </a>
                        <a href="#" target="_blank" 
                           class="flex items-center justify-center p-4 bg-green-50 hover:bg-green-100 rounded-xl transition group">
                            <i class="fab fa-whatsapp text-green-600 text-2xl group-hover:scale-110 transition"></i>
                            <span class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }} font-semibold text-gray-900">WhatsApp</span>
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
    // Contact form submission
    document.getElementById('contactForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        
        // Here you would normally send the data to your backend
        // For now, we'll just show a success message
        alert('{{ __('Message sent successfully!') }}\n{{ __('Thank you for contacting us. We will get back to you soon.') }}');
        
        // Reset form
        this.reset();
    });
</script>
@endpush

