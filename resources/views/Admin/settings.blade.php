@extends('Admin.layouts.app')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Settings</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage website settings and configurations</p>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Settings Tabs -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex -mb-px">
                    <button onclick="switchTab('slider')" id="tab-slider"
                        class="settings-tab active px-6 py-4 text-sm font-semibold text-orange-600 dark:text-orange-400 border-b-2 border-orange-600 dark:border-orange-400 transition">
                        <i class="fas fa-images mr-2"></i>Slider Images
                    </button>
                    <button onclick="switchTab('social')" id="tab-social"
                        class="settings-tab px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 border-b-2 border-transparent hover:text-orange-600 dark:hover:text-orange-400 transition">
                        <i class="fas fa-share-alt mr-2"></i>Social Media Links
                    </button>
                    <button onclick="switchTab('contact')" id="tab-contact"
                        class="settings-tab px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 border-b-2 border-transparent hover:text-orange-600 dark:hover:text-orange-400 transition">
                        <i class="fas fa-phone mr-2"></i>Contact Information
                    </button>
                    <button onclick="switchTab('commission')" id="tab-commission"
                        class="settings-tab px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 border-b-2 border-transparent hover:text-orange-600 dark:hover:text-orange-400 transition">
                        <i class="fas fa-percentage mr-2"></i>Abraj Commission
                    </button>
                    <button onclick="switchTab('discount')" id="tab-discount"
                        class="settings-tab px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-400 border-b-2 border-transparent hover:text-orange-600 dark:hover:text-orange-400 transition">
                        <i class="fas fa-ticket-alt mr-2"></i>Discount Codes
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Slider Images Tab -->
                <div id="content-slider" class="tab-content">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Hero Slider Images</h2>
                        <p class="text-gray-600 dark:text-gray-400">Manage images displayed in the hero section slider</p>
                    </div>

                    <div class="space-y-4">
                        <!-- Slider Image 1 -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-32 h-20 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden flex-shrink-0">
                                    <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                                        alt="Slider 1" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">Slider Image
                                            1</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Order: 1</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button
                                            class="px-3 py-1.5 bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900/30 transition text-sm font-semibold">
                                            <i class="fas fa-edit mr-1"></i>Change Image
                                        </button>
                                        <button
                                            class="px-3 py-1.5 bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition text-sm font-semibold">
                                            <i class="fas fa-trash mr-1"></i>Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Slider Image 2 -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-32 h-20 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden flex-shrink-0">
                                    <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                                        alt="Slider 2" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">Slider Image
                                            2</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Order: 2</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button
                                            class="px-3 py-1.5 bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900/30 transition text-sm font-semibold">
                                            <i class="fas fa-edit mr-1"></i>Change Image
                                        </button>
                                        <button
                                            class="px-3 py-1.5 bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition text-sm font-semibold">
                                            <i class="fas fa-trash mr-1"></i>Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add New Image Button -->
                        <button
                            class="w-full border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 hover:border-orange-500 dark:hover:border-orange-500 transition text-center">
                            <i class="fas fa-plus text-2xl text-gray-400 dark:text-gray-500 mb-2"></i>
                            <div class="text-sm font-semibold text-gray-600 dark:text-gray-400">Add New Slider Image</div>
                        </button>
                    </div>
                </div>

                <!-- Social Media Links Tab -->
                <div id="content-social" class="tab-content hidden">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Social Media Links</h2>
                        <p class="text-gray-600 dark:text-gray-400">Manage social media links displayed on the website</p>
                    </div>

                    <div class="space-y-4">
                        <!-- Facebook -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center text-white">
                                    <i class="fab fa-facebook-f text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <label
                                        class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Facebook</label>
                                    <input type="url" value="https://facebook.com/abrajstay"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                </div>
                            </div>
                        </div>

                        <!-- Twitter -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-sky-500 rounded-lg flex items-center justify-center text-white">
                                    <i class="fab fa-twitter text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <label
                                        class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Twitter</label>
                                    <input type="url" value="https://twitter.com/abrajstay"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                </div>
                            </div>
                        </div>

                        <!-- Instagram -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-purple-600 to-pink-600 rounded-lg flex items-center justify-center text-white">
                                    <i class="fab fa-instagram text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <label
                                        class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Instagram</label>
                                    <input type="url" value="https://instagram.com/abrajstay"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                </div>
                            </div>
                        </div>

                        <!-- LinkedIn -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-blue-700 rounded-lg flex items-center justify-center text-white">
                                    <i class="fab fa-linkedin-in text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <label
                                        class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">LinkedIn</label>
                                    <input type="url" value="https://linkedin.com/company/abrajstay"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                </div>
                            </div>
                        </div>

                        <!-- WhatsApp -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center text-white">
                                    <i class="fab fa-whatsapp text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <label
                                        class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">WhatsApp</label>
                                    <input type="text" value="+966 50 123 4567"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Abraj Commission Tab -->
                <div id="content-commission" class="tab-content hidden">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Abraj Commission</h2>
                        <p class="text-gray-600 dark:text-gray-400">Manage the commission percentage added to all hotel
                            bookings</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div
                            class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                            <form action="{{ route('admin.settings.update') }}" method="POST">
                                @csrf
                                <div class="mb-6">
                                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                        Commission Percentage (%)
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="commission_percentage" step="0.01" min="0"
                                            max="100"
                                            value="{{ \App\Models\Setting::get('commission_percentage', 0) }}"
                                            class="w-full px-4 py-2 pr-12 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                                            %
                                        </div>
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                        This value will be added to the base room price fetched from TBO.
                                    </p>
                                </div>

                                <button type="submit"
                                    class="w-full py-2.5 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition font-semibold">
                                    Update Commission
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Tab -->
                <div id="content-contact" class="tab-content hidden">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Contact Information</h2>
                        <p class="text-gray-600 dark:text-gray-400">Manage contact details displayed on the website</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Phone -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                <i class="fas fa-phone mr-2 text-orange-600"></i>Phone Number
                            </label>
                            <input type="tel" value="+966 11 123 4567"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>

                        <!-- Email -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                <i class="fas fa-envelope mr-2 text-orange-600"></i>Email Address
                            </label>
                            <input type="email" value="info@abrajstay.com"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>

                        <!-- Address -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                <i class="fas fa-map-marker-alt mr-2 text-orange-600"></i>Address
                            </label>
                            <textarea rows="3"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">Riyadh, Saudi Arabia</textarea>
                        </div>

                        <!-- Working Hours -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                <i class="fas fa-clock mr-2 text-orange-600"></i>Working Hours
                            </label>
                            <input type="text" value="Sunday - Thursday: 9:00 AM - 6:00 PM"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>

                        <!-- Support Email -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                <i class="fas fa-headset mr-2 text-orange-600"></i>Support Email
                            </label>
                            <input type="email" value="support@abrajstay.com"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Discount Codes Tab -->
        <div id="content-discount" class="tab-content hidden">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Discount Codes</h2>
                <p class="text-gray-600 dark:text-gray-400">Manage one-time use discount codes</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Create Code Form -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Create New Code</h3>
                        <form action="{{ route('admin.discount-codes.store') }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Code
                                        (Unique)</label>
                                    <input type="text" name="code" required value="{{ old('code') }}"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 @error('code') border-red-500 @enderror">
                                    @error('code')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Discount
                                        Percentage (%)</label>
                                    <input type="number" name="discount_percentage" required min="1"
                                        max="100" step="0.01" value="{{ old('discount_percentage') }}"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 @error('discount_percentage') border-red-500 @enderror">
                                    @error('discount_percentage')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Start
                                        Date</label>
                                    <input type="datetime-local" name="start_date" required
                                        value="{{ old('start_date') }}"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 @error('start_date') border-red-500 @enderror">
                                    @error('start_date')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">End
                                        Date</label>
                                    <input type="datetime-local" name="end_date" required value="{{ old('end_date') }}"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 @error('end_date') border-red-500 @enderror">
                                    @error('end_date')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit"
                                    class="w-full py-2.5 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition font-semibold">
                                    <i class="fas fa-plus mr-2"></i>Create Code
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Codes List -->
                <div class="lg:col-span-2">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-700/50">
                                    <th
                                        class="px-4 py-3 text-sm font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700">
                                        Code</th>
                                    <th
                                        class="px-4 py-3 text-sm font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700">
                                        Discount</th>
                                    <th
                                        class="px-4 py-3 text-sm font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700">
                                        Validity</th>
                                    <th
                                        class="px-4 py-3 text-sm font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700">
                                        Used?</th>
                                    <th
                                        class="px-4 py-3 text-sm font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($discountCodes as $code)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                        <td
                                            class="px-4 py-3 text-sm text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 font-mono">
                                            {{ $code->code }}</td>
                                        <td
                                            class="px-4 py-3 text-sm text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700">
                                            {{ $code->discount_percentage }}%</td>
                                        <td
                                            class="px-4 py-3 text-sm text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700">
                                            <div class="text-xs">From: {{ $code->start_date->format('Y-m-d H:i') }}</div>
                                            <div class="text-xs">To: {{ $code->end_date->format('Y-m-d H:i') }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-sm border-b border-gray-200 dark:border-gray-700">
                                            @if ($code->is_used)
                                                <span class="px-2 py-1 bg-red-100 text-red-600 rounded text-xs">Used
                                                    ({{ $code->used_at->format('Y-m-d') }})
                                                </span>
                                            @else
                                                <span
                                                    class="px-2 py-1 bg-green-100 text-green-600 rounded text-xs">Available</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm border-b border-gray-200 dark:border-gray-700">
                                            <form action="{{ route('admin.discount-codes.destroy', $code) }}"
                                                method="POST" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 transition">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                            No discount codes found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Save Button -->
        <div
            class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 flex justify-end">
            <button
                class="px-6 py-2.5 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition shadow-lg font-semibold">
                <i class="fas fa-save mr-2"></i>Save Changes
            </button>
        </div>
    </div>
    </div>

    @push('scripts')
        <script>
            function switchTab(tabName) {
                // Hide all tab contents
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });

                // Remove active class from all tabs
                document.querySelectorAll('.settings-tab').forEach(tab => {
                    tab.classList.remove('active', 'text-orange-600', 'dark:text-orange-400', 'border-orange-600',
                        'dark:border-orange-400');
                    tab.classList.add('text-gray-600', 'dark:text-gray-400', 'border-transparent');
                });

                // Show selected tab content
                document.getElementById('content-' + tabName).classList.remove('hidden');

                // Add active class to selected tab
                const activeTab = document.getElementById('tab-' + tabName);
                activeTab.classList.add('active', 'text-orange-600', 'dark:text-orange-400', 'border-orange-600',
                    'dark:border-orange-400');
                activeTab.classList.remove('text-gray-600', 'dark:text-gray-400', 'border-transparent');
            }

            // Live commission calculation example
            document.addEventListener('DOMContentLoaded', function() {
                const commissionInput = document.querySelector('input[name="commission_percentage"]');
                if (commissionInput) {
                    commissionInput.addEventListener('input', function() {
                        const percentage = parseFloat(this.value) || 0;
                        const basePrice = 1000;
                        const commissionAmount = basePrice * (percentage / 100);
                        const finalPrice = basePrice + commissionAmount;

                        document.getElementById('exampleCommission').textContent = percentage.toFixed(2);
                        document.getElementById('exampleCommissionAmount').textContent = commissionAmount
                            .toFixed(2) + ' SAR';
                        document.getElementById('exampleFinalPrice').textContent = finalPrice.toFixed(2) +
                            ' SAR';
                    });
                }

                // Handle Tab persistence from Hash
                const hash = window.location.hash;
                if (hash) {
                    const tabName = hash.replace('#', '');
                    if (document.getElementById('tab-' + tabName)) {
                        switchTab(tabName);
                    }
                }
            });
        </script>
    @endpush
@endsection
