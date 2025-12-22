<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
@include('Web.partials.head')

@stack('styles')

<body class="font-cairo antialiased bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100">
    @include('Web.partials.navbar')
    @include('Web.partials.top-banner')

    <!-- Main Content -->
    @if (session()->has('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                showToast('{{ session('success') }}' , 'success');
            });
        </script>
    @endif
    @if (session()->has('error'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                showToast('{{ session('error') }}' , 'error');
            });
        </script>
    @endif

    <main>
        @yield('content')
    </main>

    @include('Web.partials.footer')

    <!-- WhatsApp CTA Button - Bottom Left -->
    <a href="https://wa.me/966500000000?text={{ app()->getLocale() === 'ar' ? 'مرحباً، أريد الاستفسار عن حجز فندق' : 'Hello, I would like to inquire about hotel booking' }}"
        target="_blank"
        class="fixed {{ app()->getLocale() === 'ar' ? 'bottom-6 left-6' : 'bottom-6 right-6' }} z-50 bg-green-500 hover:bg-green-600 text-white rounded-full w-14 h-14 flex items-center justify-center shadow-2xl transition-all duration-300 hover:scale-110 group">
        <i class="fab fa-whatsapp text-xl"></i>
        <span
            class="absolute {{ app()->getLocale() === 'ar' ? 'right-full mr-3' : 'left-full ml-3' }} transform -translate-y-1/2 bg-gray-900 text-white px-4 py-2 rounded-lg text-sm whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">
            {{ __('Contact us on WhatsApp') }}
        </span>
    </a>

    <!-- Scroll to Top Button - Bottom Right -->
    <button id="scrollToTop"
        class="fixed {{ app()->getLocale() === 'ar' ? 'bottom-6 right-6' : 'bottom-6 left-6' }} z-50 bg-orange-500 hover:bg-orange-600 text-white rounded-full w-14 h-14 flex items-center justify-center shadow-2xl transition-all duration-300 opacity-0 invisible hover:scale-110">
        <i class="fas fa-arrow-up text-xl"></i>
    </button>

    <!-- Login/Register Modal -->
    <div id="authModal"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center overflow-y-auto">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-4xl w-full mx-4 my-8 relative"
            id="modalContent">
            <!-- Close Button -->
            <button onclick="closeAuthModal()"
                class="absolute top-4 {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-times text-2xl"></i>
            </button>

            <!-- Tabs -->
            <div class="flex border-b border-gray-200">
                <button id="loginTab" onclick="switchTab('login')"
                    class="flex-1 px-6 py-4 text-center font-semibold text-orange-500 border-b-2 border-orange-500">
                    {{ __('Login') }}
                </button>
                <button id="registerTab" onclick="switchTab('register')"
                    class="flex-1 px-6 py-4 text-center font-semibold text-gray-500 hover:text-orange-500 transition">
                    {{ __('Register') }}
                </button>
            </div>

            <!-- Login Form -->
            <div id="loginForm" class="p-6 max-w-md mx-auto">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">{{ __('Welcome Back') }}</h2>
                <form onsubmit="handleLogin(event)">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">{{ __('Email') }}</label>
                        <input type="email" required name="email"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">{{ __('Password') }}</label>
                        <input type="password" required name="password"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900">
                    </div>
                    <div
                        class="flex items-center justify-between mb-6 {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : '' }}">
                        <label class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-orange-500 rounded">
                            <span
                                class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }} text-sm text-gray-600">{{ __('Remember Me') }}</span>
                        </label>
                        <a href="#"
                            class="text-sm text-orange-500 hover:underline">{{ __('Forgot Password?') }}</a>
                    </div>
                    <button type="submit" id="loginBtn"
                        class="w-full mt-6 bg-gradient-to-r from-orange-500 to-orange-600 text-white py-3 rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 transition shadow-lg flex items-center justify-center gap-2">

                        <span class="btn-text">{{ __('Login') }}</span>

                        <!-- Loader -->
                        <svg class="btn-loader hidden animate-spin h-5 w-5 text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8v4l3-3-3-3v4a12 12 0 00-12 12h4z">
                            </path>
                        </svg>

                    </button>

                </form>
                <div class="mt-6 text-center">
                    <p class="text-gray-600">{{ __('Or') }}</p>
                    <div class="flex gap-4 justify-center mt-4">
                        {{-- <button class="w-12 h-12 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition">
                            <i class="fab fa-facebook-f"></i>
                        </button> --}}
                        <button class="w-12 h-12 bg-gray-800 text-white rounded-full hover:bg-gray-900 transition">
                            <i class="fab fa-google"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Register Form -->
            <div id="registerForm" class="hidden">
                <div
                    class="grid grid-cols-1 md:grid-cols-2 gap-0 min-h-[500px] {{ app()->getLocale() === 'en' ? 'md:flex-row-reverse' : '' }}">
                    <!-- Section 1 - Social Sign Up -->
                    <section
                        class="p-8 flex flex-col items-center justify-center bg-gradient-to-br from-orange-50 via-orange-100 to-blue-50 {{ app()->getLocale() === 'ar' ? 'border-l md:border-l-0 md:border-r' : 'border-r md:border-r-0 md:border-l' }} border-gray-200">
                        <div class="w-full max-w-sm">
                            <h3 class="text-2xl font-bold text-gray-900 mb-3 text-center">{{ __('Sign Up With') }}</h3>
                            <p class="text-gray-600 text-sm mb-8 text-center">
                                {{ __('Use your social account for quick registration') }}</p>
                            <div class="w-full space-y-3">
                                <button type="button"
                                    class="w-full flex items-center justify-center gap-3 bg-white border-2 border-gray-300 text-gray-700 py-3.5 rounded-xl font-semibold hover:bg-gray-50 hover:border-orange-500 hover:text-orange-500 transition shadow-md">
                                    <i class="fab fa-google text-xl text-red-600"></i>
                                    <span>{{ __('Sign up with Google') }}</span>
                                </button>
                                {{-- <button type="button"
                                    class="w-full flex items-center justify-center gap-3 bg-white border-2 border-gray-300 text-gray-700 py-3.5 rounded-xl font-semibold hover:bg-gray-50 hover:border-orange-500 hover:text-orange-500 transition shadow-md">
                                    <i class="fab fa-facebook-f text-xl text-blue-600"></i>
                                    <span>{{ __('Sign up with Facebook') }}</span>
                                </button> --}}
                                {{-- <button type="button"
                                    class="w-full flex items-center justify-center gap-3 bg-white border-2 border-gray-300 text-gray-700 py-3.5 rounded-xl font-semibold hover:bg-gray-50 hover:border-orange-500 hover:text-orange-500 transition shadow-md">
                                    <i class="fab fa-apple text-xl text-gray-900"></i>
                                    <span>{{ __('Sign up with Apple') }}</span>
                                </button>
                                <button type="button"
                                    class="w-full flex items-center justify-center gap-3 bg-white border-2 border-gray-300 text-gray-700 py-3.5 rounded-xl font-semibold hover:bg-gray-50 hover:border-orange-500 hover:text-orange-500 transition shadow-md">
                                    <i class="fab fa-twitter text-xl text-blue-400"></i>
                                    <span>{{ __('Sign up with Twitter') }}</span>
                                </button> --}}
                            </div>
                        </div>
                    </section>

                    <!-- Section 2 - Registration Form -->
                    <section class="p-8 bg-white">
                        <div class="w-full max-w-md mx-auto">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">
                                {{ __('Create New Account') }}</h2>
                            <form onsubmit="handleRegister(event)">
                                <div class="space-y-4">
                                    <div>
                                        <label
                                            class="block text-gray-700 font-semibold mb-2">{{ __('Full Name') }}</label>
                                        <input type="text" name="name" required
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-gray-700 font-semibold mb-2">{{ __('Email') }}</label>
                                        <input type="email" name="email" required
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-gray-700 font-semibold mb-2">{{ __('Phone') }}</label>
                                        <input type="tel" name="phone" required
                                            placeholder="{{ app()->getLocale() === 'ar' ? '05xxxxxxxx' : '+966 5x xxx xxxx' }}"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-gray-700 font-semibold mb-2">{{ __('Password') }}</label>
                                        <input type="password" name="password" required
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900">
                                    </div>
                                </div>

                                <!-- Terms and Submit -->
                                <div class="mt-6">
                                    <label
                                        class="flex items-start {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : '' }}">
                                        <input type="checkbox" required class="w-4 h-4 text-orange-500 rounded mt-1">
                                        <span
                                            class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }} text-sm text-gray-600">{{ __('I agree to') }}
                                            <a href="#"
                                                class="text-orange-500 hover:underline">{{ __('Terms and Conditions') }}</a>
                                            {{ __('and') }} <a href="#"
                                                class="text-orange-500 hover:underline">{{ __('Privacy Policy') }}</a></span>
                                    </label>
                                </div>

                                <button type="submit" id="registerBtn"
                                    class="w-full mt-6 bg-gradient-to-r from-orange-500 to-orange-600 text-white py-3 rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 transition shadow-lg flex items-center justify-center gap-2">

                                    <span class="btn-text">{{ __('Register') }}</span>

                                    <!-- Loader -->
                                    <svg class="btn-loader hidden animate-spin h-5 w-5 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8v4l3-3-3-3v4a12 12 0 00-12 12h4z">
                                        </path>
                                    </svg>

                                </button>

                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')

    <script>
        // Mobile menu toggle functions
        function openMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            const backdrop = document.getElementById('mobile-menu-backdrop');
            const isRTL = document.documentElement.dir === 'rtl';

            if (mobileMenu && backdrop) {
                backdrop.classList.remove('hidden');
                mobileMenu.classList.remove('hidden');
                // Force reflow to ensure transition works
                void mobileMenu.offsetWidth;

                if (isRTL) {
                    mobileMenu.classList.remove('translate-x-full');
                } else {
                    mobileMenu.classList.remove('-translate-x-full');
                }
                document.body.style.overflow = 'hidden';
            }
        }

        function closeMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            const backdrop = document.getElementById('mobile-menu-backdrop');
            const isRTL = document.documentElement.dir === 'rtl';

            if (mobileMenu && backdrop) {
                if (isRTL) {
                    mobileMenu.classList.add('translate-x-full');
                } else {
                    mobileMenu.classList.add('-translate-x-full');
                }

                backdrop.classList.add('hidden');

                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                    document.body.style.overflow = '';
                }, 300);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenuClose = document.getElementById('mobile-menu-close');
            const backdrop = document.getElementById('mobile-menu-backdrop');

            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', openMobileMenu);
            }

            if (mobileMenuClose) {
                mobileMenuClose.addEventListener('click', closeMobileMenu);
            }

            if (backdrop) {
                backdrop.addEventListener('click', closeMobileMenu);
            }

            // Profile dropdown toggle
            const profileToggle = document.getElementById('profile-toggle');
            const profileDropdown = document.getElementById('profile-dropdown');
            const profileContainer = document.getElementById('profile-dropdown-container');

            if (profileToggle && profileDropdown && profileContainer) {
                // Toggle dropdown on click
                profileToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isVisible = !profileDropdown.classList.contains('opacity-0');

                    if (isVisible) {
                        profileDropdown.classList.add('opacity-0', 'invisible');
                    } else {
                        profileDropdown.classList.remove('opacity-0', 'invisible');
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!profileContainer.contains(e.target)) {
                        profileDropdown.classList.add('opacity-0', 'invisible');
                    }
                });

                // Close dropdown on hover out (optional - can be removed if you want click-only)
                profileContainer.addEventListener('mouseleave', function() {
                    profileDropdown.classList.add('opacity-0', 'invisible');
                });

                // Show dropdown on hover
                profileContainer.addEventListener('mouseenter', function() {
                    profileDropdown.classList.remove('opacity-0', 'invisible');
                });
            }
        });

        // Auth Modal Functions
        function openAuthModal() {
            document.getElementById('authModal').classList.remove('hidden');
            document.getElementById('authModal').classList.add('flex');
            switchTab('login');
        }

        function closeAuthModal() {
            document.getElementById('authModal').classList.add('hidden');
            document.getElementById('authModal').classList.remove('flex');
        }

        function switchTab(tab) {
            const loginTab = document.getElementById('loginTab');
            const registerTab = document.getElementById('registerTab');
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');

            if (tab === 'login') {
                loginTab.classList.add('text-orange-500', 'border-b-2', 'border-orange-500');
                loginTab.classList.remove('text-gray-500');
                registerTab.classList.remove('text-orange-500', 'border-b-2', 'border-orange-500');
                registerTab.classList.add('text-gray-500');
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');
            } else {
                registerTab.classList.add('text-orange-500', 'border-b-2', 'border-orange-500');
                registerTab.classList.remove('text-gray-500');
                loginTab.classList.remove('text-orange-500', 'border-b-2', 'border-orange-500');
                loginTab.classList.add('text-gray-500');
                registerForm.classList.remove('hidden');
                loginForm.classList.add('hidden');
            }
        }

        async function handleLogin(event) {
            event.preventDefault();

            let btn = document.getElementById("loginBtn");
            let text = btn.querySelector(".btn-text");
            let loader = btn.querySelector(".btn-loader");

            // Show loading
            text.classList.add("hidden");
            loader.classList.remove("hidden");
            btn.disabled = true;

            let form = event.target;
            let data = {
                email: form.email.value,
                password: form.password.value,
            };

            try {
                let response = await fetch("{{ route('site.login', ['locale' => app()->getLocale()]) }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });

                let result = await response.json();

                if (result.status === "success") {
                    // document.getElementById('user-icon').classList.remove('hidden');
                    closeAuthModal();
                    
                    // Check if there's a pending reservation
                    const pendingReservation = sessionStorage.getItem('pendingReservation');
                    if (pendingReservation) {
                        // Reload to trigger the reservation form fill
                        window.location.reload();
                    } else {
                        window.location.reload();
                    }
                } else {
                    showToast(result.message, "error");
                }
            } catch (err) {
                showToast("Error, try again!", "error");
            }

            // Hide loading
            text.classList.remove("hidden");
            loader.classList.add("hidden");
            btn.disabled = false;
        }


        async function handleRegister(event) {
            event.preventDefault();

            let btn = document.getElementById("registerBtn");
            let text = btn.querySelector(".btn-text");
            let loader = btn.querySelector(".btn-loader");

            // Show loading
            text.classList.add("hidden");
            loader.classList.remove("hidden");
            btn.disabled = true;

            let form = event.target;
            let data = {
                name: form.name.value,
                email: form.email.value,
                phone: form.phone.value,
                password: form.password.value,
            };

            try {
                let response = await fetch("/register", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });

                let result = await response.json();

                if (result.status === "success") {
                    closeAuthModal();
                    window.location.reload();
                } else {
                    showToast(result.message, "error");
                }
            } catch (err) {
                console.log(err);
                showToast("Error, try again!", "error");
            }

            // Hide loading
            text.classList.remove("hidden");
            loader.classList.add("hidden");
            btn.disabled = false;
        }


        // Close modal when clicking outside
        document.getElementById('authModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAuthModal();
            }
        });

        // Scroll to Top Button
        const scrollToTopBtn = document.getElementById('scrollToTop');

        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.remove('opacity-0', 'invisible');
                scrollToTopBtn.classList.add('opacity-100', 'visible');
            } else {
                scrollToTopBtn.classList.add('opacity-0', 'invisible');
                scrollToTopBtn.classList.remove('opacity-100', 'visible');
            }
        });

        scrollToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Theme is handled in `resources/js/app.js` (avoids duplicate listeners)
    </script>
    <script>
        function showToast(message, type = "success") {
            let container = document.getElementById("toast-container");

            let bg = type === "success" ?
                "bg-green-500" :
                "bg-red-500";

            let toast = document.createElement("div");
            toast.className =
                bg +
                " text-white px-4 py-3 rounded-lg shadow-lg animate-fadeIn flex items-center gap-3";

            toast.innerHTML = `
        <span>${message}</span>
    `;

            container.appendChild(toast);

            // Remove after 10 sec
            setTimeout(() => {
                toast.classList.add("opacity-0");
                setTimeout(() => toast.remove(), 300);
            }, 10000);
        }
    </script>
    <div id="toast-container"
        class="fixed top-5 {{ app()->getLocale() === 'ar' ? 'left-5' : 'right-5' }} z-[9999] space-y-3"></div>

</body>

</html>
