<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - ABRAJ STAY</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo:400,500,600,700,800,900" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Modern Admin Panel Styles */
        body {
            direction: ltr;
            overflow-x: hidden;
        }

        html {
            overflow-x: hidden;
        }

        .min-h-screen {
            min-width: 0;
            width: 100%;
            overflow-x: hidden;
        }

        .max-h-screen {
            max-width: 0;
            width: 100%;
            overflow-x: hidden;
        }

        .admin-content {
            max-width: 100%;
            overflow-x: hidden;
        }

        .admin-sidebar {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.1);
        }

        .admin-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            color: #cbd5e1;
            font-weight: 500;
            width: 100%;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .admin-nav-link > span:not(.admin-badge) {
            flex: 1;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .admin-nav-link:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            transform: translateX(4px);
        }

        .admin-nav-link.active {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
        }

        .admin-nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: #ffffff;
            border-radius: 0 4px 4px 0;
        }

        .admin-nav-link i {
            width: 20px;
            text-align: center;
            font-size: 18px;
        }

        .admin-badge {
            margin-left: auto;
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            min-width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            white-space: nowrap;
        }

        .admin-nav-link.active .admin-badge {
            background: rgba(255, 255, 255, 0.3);
        }

        .admin-header {
            background: #ffffff;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .dark .admin-header {
            background: #1e293b;
            border-bottom-color: #334155;
        }

        .admin-search {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
        }

        .admin-search:focus {
            background: #ffffff;
            border-color: #f97316;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        }

        .dark .admin-search {
            background: #0f172a;
            border-color: #334155;
            color: #ffffff;
        }

        .dark .admin-search:focus {
            background: #1e293b;
        }

        .admin-content {
            background: #f8fafc;
            min-height: calc(100vh - 64px);
        }

        .dark .admin-content {
            background: #0f172a;
        }

        .admin-profile-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
        }

        .admin-profile-card:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .sidebar-section-title {
            color: #64748b;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        @media (max-width: 1024px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.open {
                transform: translateX(0);
            }
        }

        #sidebar-user-dropdown {
            min-width: 200px;
        }
    </style>

    @stack('styles')
</head>

<body class="font-cairo antialiased bg-gray-50 dark:bg-gray-900"
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside id="admin-sidebar"
            class="admin-sidebar fixed lg:fixed inset-y-0 {{ app()->getLocale() === 'ar' ? 'right-0' : 'left-0' }} z-30 w-72 transform transition-transform duration-300 ease-in-out {{ app()->getLocale() === 'ar' ? 'translate-x-full lg:translate-x-0' : '-translate-x-full lg:translate-x-0' }}">
            <div class="flex flex-col h-screen overflow-hidden">
                <!-- Logo Section -->
                <div class="flex items-center justify-between h-20 px-6 border-b border-white/10 flex-shrink-0">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-hotel text-white text-xl"></i>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-white">ABRAJ STAY</div>
                            <div class="text-xs text-gray-400">{{ __('Admin Dashboard') }}</div>
                        </div>
                    </a>
                    <button id="sidebar-close" class="lg:hidden text-gray-400 hover:text-white transition p-2">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto min-h-0">
                    <a href="{{ route('admin.dashboard') }}"
                        class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                        class="admin-nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>{{ __('Users') }}</span>
                    </a>

                    <!-- Bookings Section -->
                    <div class="border-t border-white/10">
                        <div
                            class="px-4 mb-2 mt-2 text-xs font-semibold sidebar-section-title uppercase tracking-wider">
                            {{ __('Bookings') }}</div>
                        <a href="{{ route('admin.package-contacts.index') }}"
                            class="admin-nav-link {{ request()->routeIs('admin.package-contacts*') ? 'active' : '' }}">
                            <i class="fas fa-box"></i>
                            <span>{{ __('Package Contacts') }}</span>
                        </a>
                        <a href="{{ route('admin.flight-bookings.index') }}"
                            class="admin-nav-link {{ request()->routeIs('admin.flight-bookings*') ? 'active' : '' }}">
                            <i class="fas fa-plane"></i>
                            <span>{{ __('Flight Bookings') }}</span>
                        </a>
                        <a href="{{ route('admin.car-rental-bookings.index') }}"
                            class="admin-nav-link {{ request()->routeIs('admin.car-rental-bookings*') ? 'active' : '' }}">
                            <i class="fas fa-car-side"></i>
                            <span>{{ __('Car Rental Bookings') }}</span>
                        </a>
                        <a href="{{ route('admin.visa-bookings.index') }}"
                            class="admin-nav-link {{ request()->routeIs('admin.visa-bookings*') ? 'active' : '' }}">
                            <i class="fas fa-passport"></i>
                            <span>{{ __('Visa Bookings') }}</span>
                        </a>
                    </div>

                    <a href="{{ route('admin.transactions') }}"
                        class="admin-nav-link {{ request()->routeIs('admin.transactions*') ? 'active' : '' }}">
                        <i class="fas fa-credit-card"></i>
                        <span>{{ __('Transactions') }}</span>
                    </a>

                    <a href="{{ route('admin.reviews') }}"
                        class="admin-nav-link {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
                        <i class="fas fa-star"></i>
                        <span>{{ __('Client Reviews') }}</span>
                    </a>

                    <a href="{{ route('admin.reports') }}"
                        class="admin-nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>{{ __('User Reports') }}</span>
                        <span class="admin-badge">5</span>
                    </a>

                    <!-- Settings Section -->
                    <div class="pt-6 mt-6 border-t border-white/10">
                        <div class="px-4 mb-3 sidebar-section-title">{{ __('Settings') }}</div>

                        <a href="{{ route('admin.settings') }}"
                            class="admin-nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                            <i class="fas fa-cog"></i>
                            <span>{{ __('Settings') }}</span>
                        </a>
                    </div>
                </nav>

                <!-- User Profile Card - Fixed at Bottom -->
                <div
                    class="p-4 border-t border-white/10 bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 flex-shrink-0">
                    <div class="relative">
                        <div id="sidebar-user-toggle" class="admin-profile-card rounded-xl p-4 cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg flex-shrink-0">
                                    A
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-semibold text-white truncate">Admin User</div>
                                    <div class="text-xs text-gray-400 truncate">admin@abrajstay.com</div>
                                </div>
                                <button class="text-gray-400 hover:text-white transition flex-shrink-0">
                                    <i class="fas fa-chevron-down text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <!-- User Dropdown -->
                        <div id="sidebar-user-dropdown"
                            class="absolute bottom-full left-0 right-0 mb-2 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 py-2 opacity-0 invisible transition-all duration-200 z-50">
                            <a href="#"
                                class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <i
                                    class="fas fa-key w-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }} text-gray-400"></i>Change
                                Password
                            </a>
                            <hr class="my-2 border-gray-200 dark:border-gray-700">
                            <a href="{{ route('home') }}"
                                class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <i
                                    class="fas fa-home w-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }} text-gray-400"></i>View
                                Website
                            </a>
                            <hr class="my-2 border-gray-200 dark:border-gray-700">
                            <form method="POST" action="{{ route('admin.logout') }}" class="block">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left flex items-center px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                                    <i
                                        class="fas fa-sign-out-alt w-5 {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 {{ app()->getLocale() === 'ar' ? 'lg:mr-72' : 'lg:ml-72' }}">
            <!-- Header -->
            <header class="admin-header sticky top-0 z-20">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <!-- Left Section -->
                    <div class="flex items-center gap-4">
                        <!-- Mobile Menu Button -->
                        <button id="sidebar-toggle"
                            class="lg:hidden text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <i class="fas fa-bars text-xl"></i>
                        </button>

                        <!-- Page Title -->
                        <div class="hidden md:block">
                            <h1 class="text-xl font-bold text-gray-900 dark:text-white">@yield('page-title', __('Dashboard'))</h1>
                        </div>
                    </div>

                    <!-- Center Section - Search -->
                    <div class="flex-1 max-w-2xl mx-8 hidden lg:block">
                        <div class="relative">
                                <input type="text" placeholder="{{ __('Search anything...') }}"
                                class="admin-search w-full pl-10 pr-4 py-2.5 rounded-lg text-sm focus:outline-none">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Right Section -->
                    <div class="flex items-center gap-2">
                        <!-- Language Toggle -->
                        @php
                            $targetLocale = app()->getLocale() === 'ar' ? 'en' : 'ar';
                            $targetUrl = LaravelLocalization::getLocalizedURL($targetLocale, null, [], true);
                        @endphp
                        <a href="{{ $targetUrl }}"
                            class="p-2.5 text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition"
                            title="{{ app()->getLocale() === 'ar' ? 'Switch to English' : 'التبديل إلى العربية' }}">
                            <i class="fas fa-globe text-xl"></i>
                        </a>

                        <!-- Theme Toggle -->
                        <button id="admin-theme-toggle"
                            class="p-2.5 text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                            <i id="admin-theme-sun" class="fas fa-sun text-xl"></i>
                            <i id="admin-theme-moon" class="fas fa-moon text-xl hidden"></i>
                        </button>

                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="admin-content flex-1 p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden hidden transition-opacity">
    </div>

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('admin-sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebarClose = document.getElementById('sidebar-close');
            const sidebarBackdrop = document.getElementById('sidebar-backdrop');

            function openSidebar() {
                if (sidebar) sidebar.classList.add('open');
                if (sidebarBackdrop) sidebarBackdrop.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                if (sidebar) sidebar.classList.remove('open');
                if (sidebarBackdrop) sidebarBackdrop.classList.add('hidden');
                document.body.style.overflow = '';
            }

            if (sidebarToggle) sidebarToggle.addEventListener('click', openSidebar);
            if (sidebarClose) sidebarClose.addEventListener('click', closeSidebar);
            if (sidebarBackdrop) sidebarBackdrop.addEventListener('click', closeSidebar);

            // Sidebar User Dropdown
            const sidebarUserToggle = document.getElementById('sidebar-user-toggle');
            const sidebarUserDropdown = document.getElementById('sidebar-user-dropdown');

            if (sidebarUserToggle && sidebarUserDropdown) {
                sidebarUserToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isVisible = !sidebarUserDropdown.classList.contains('opacity-0');
                    sidebarUserDropdown.classList.toggle('opacity-0', isVisible);
                    sidebarUserDropdown.classList.toggle('invisible', isVisible);
                });

                document.addEventListener('click', function(e) {
                    if (!sidebarUserToggle.contains(e.target) && !sidebarUserDropdown.contains(e.target)) {
                        sidebarUserDropdown.classList.add('opacity-0', 'invisible');
                    }
                });
            }



            // Theme Toggle
            const adminThemeToggle = document.getElementById('admin-theme-toggle');
            const adminThemeSun = document.getElementById('admin-theme-sun');
            const adminThemeMoon = document.getElementById('admin-theme-moon');

            function updateAdminThemeIcon() {
                const isDark = document.documentElement.classList.contains('dark');
                if (adminThemeSun && adminThemeMoon) {
                    adminThemeSun.classList.toggle('hidden', isDark);
                    adminThemeMoon.classList.toggle('hidden', !isDark);
                }
            }

            if (adminThemeToggle) {
                adminThemeToggle.addEventListener('click', function() {
                    const isDark = document.documentElement.classList.contains('dark');
                    document.documentElement.classList.toggle('dark', !isDark);
                    localStorage.setItem('theme', !isDark ? 'dark' : 'light');
                    updateAdminThemeIcon();
                });
            }

            updateAdminThemeIcon();
        });
    </script>
</body>

</html>
