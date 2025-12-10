@extends('Web.layouts.app')

@section('title', __('Packages'))

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white py-16 md:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold mb-4">{{ __('Packages') }}</h1>
            <p class="text-xl md:text-2xl text-slate-300">{{ __('Discover our amazing travel packages') }}</p>
        </div>
    </section>

    <!-- Packages Grid -->
    <section class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($packages->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($packages as $package)
                        <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                            <!-- Package Image -->
                            <div class="relative h-64 overflow-hidden">
                                <img src="{{ $package->image ? asset('storage/'.$package->image) : 'https://images.unsplash.com/photo-1551884170-09fb70a3a2ed?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}" 
                                     alt="{{ $package->locale_title }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                @if($package->price)
                                    <div class="absolute top-4 {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} bg-orange-500 text-white px-4 py-2 rounded-lg font-bold">
                                        {{ number_format($package->price, 2) }} {{ __('SAR') }}
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Package Content -->
                            <div class="p-6">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                    {{ $package->locale_title }}
                                </h3>
                                
                                @if($package->locale_description)
                                    <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-3">
                                        {{ Str::limit($package->locale_description, 120) }}
                                    </p>
                                @endif

                                @if($package->locale_duration)
                                    <div class="flex items-center text-gray-500 dark:text-gray-400 mb-4">
                                        <i class="fas fa-clock {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                                        <span>{{ $package->locale_duration }}</span>
                                    </div>
                                @endif

                                <a href="{{ route('package.details', ['id' => $package->id, 'locale' => app()->getLocale()]) }}" 
                                   class="block w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white text-center py-3 rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 transition">
                                    {{ __('View Details') }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <i class="fas fa-box-open text-6xl text-gray-400 mb-4"></i>
                    <p class="text-xl text-gray-600 dark:text-gray-400">{{ __('No packages available at the moment') }}</p>
                </div>
            @endif
        </div>
    </section>
@endsection

