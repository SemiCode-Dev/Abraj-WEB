@extends('Web.layouts.app')

@section('title', __('Profile') . ' - ABRAJ STAY')

@section('content')
<section class="bg-gradient-to-r from-orange-500 via-orange-600 to-blue-900 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ __('Profile') }}</h1>
        <p class="text-orange-100 text-lg max-w-2xl mx-auto">
            {{ __('Manage your account settings and preferences') }}
        </p>
    </div>
</section>

<section class="py-16 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
            <!-- Profile Header -->
            <div class="text-center mb-8">
                <label for="imageInput" class="cursor-pointer inline-block">
                    @if($user->image)
                        <img src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->name }}" id="imagePreview" class="w-32 h-32 rounded-full mx-auto border-4 border-orange-500 mb-4 object-cover hover:opacity-80 transition">
                    @else
                        <div id="imagePreview" class="w-32 h-32 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white text-4xl font-bold mx-auto border-4 border-orange-500 mb-4 hover:opacity-80 transition cursor-pointer">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </label>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $user->name }}</h2>
                <p class="text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">{{ __('Click on image to change') }}</p>
            </div>

            <!-- Profile Form -->
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="profileForm">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                        {{ __('Full Name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                        {{ __('Email Address') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                        {{ __('Phone Number') }}
                    </label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                        {{ __('Profile Image') }}
                    </label>
                    <input type="file" name="image" id="imageInput" accept="image/*" 
                           class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Maximum file size: 2MB. Allowed types: jpeg, png, jpg, gif') }}</p>
                </div>

                <!-- Password Section -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ __('Change Password') }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ __('Leave blank if you dont want to change your password') }}</p>

                    <!-- New Password -->
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                            {{ __('New Password') }}
                        </label>
                        <input type="password" name="password" 
                               class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">
                            {{ __('Confirm New Password') }}
                        </label>
                        <input type="password" name="password_confirmation" 
                               class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex gap-4 pt-6">
                    <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl hover:from-orange-600 hover:to-orange-700 transition font-semibold shadow-lg">
                        {{ __('Update Profile') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    // Preview image before upload
    document.getElementById('imageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('imagePreview');
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    // Replace div with img
                    const img = document.createElement('img');
                    img.id = 'imagePreview';
                    img.src = e.target.result;
                    img.className = 'w-32 h-32 rounded-full mx-auto border-4 border-orange-500 mb-4 object-cover hover:opacity-80 transition';
                    preview.parentNode.replaceChild(img, preview);
                }
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
