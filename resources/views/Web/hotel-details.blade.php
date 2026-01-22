@extends('Web.layouts.app')

@section('title', __('Hotel Details') . ' - ' . __('Book Hotels - Best Offers and Services'))

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        /* Custom Flatpickr Styling to match Radisson/User Request */
        .flatpickr-calendar {
            border-radius: 16px;
            box-shadow: none !important;
            border: none !important;
            font-family: inherit;
            padding: 0;
            background: white;
            position: relative !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            display: block !important;
        }

        /* Desktop-specific styles for large gap */
        @media (min-width: 768px) {
            .flatpickr-calendar {
                width: auto !important;
            }

            .flatpickr-months {
                display: flex !important;
                justify-content: center !important;
                gap: 150px !important;
                padding: 0 10px;
                position: relative;
            }

            .flatpickr-days {
                display: flex !important;
                justify-content: center !important;
                gap: 150px !important;
                width: 100% !important;
            }

            .dayContainer {
                width: 350px !important;
                min-width: 350px !important;
                max-width: 350px !important;
                overflow: visible !important;
            }

            .flatpickr-month {
                width: 350px !important;
            }

            .flatpickr-weekdays {
                display: flex !important;
                justify-content: center !important;
                gap: 150px !important;
                width: 100% !important;
            }

            .flatpickr-weekdaycontainer {
                width: 350px !important;
                display: flex !important;
            }
        }

        @media (max-width: 767px) {
            .flatpickr-months .flatpickr-month {
                width: 100% !important;
            }

            .flatpickr-days {
                width: 100% !important;
            }

            .dayContainer {
                max-width: 100% !important;
                width: 100% !important;
                min-width: auto !important;
            }

            .flatpickr-rContainer {
                width: 100% !important;
            }

            .flatpickr-innerContainer {
                width: 100% !important;
                display: block !important;
            }

            .flatpickr-weekdays {
                width: 100% !important;
            }

            .flatpickr-weekdaycontainer {
                width: 100% !important;
            }
        }

        .flatpickr-month {
            background: transparent;
            color: #111827;
            fill: #111827;
            height: 50px;
        }

        .flatpickr-current-month {
            font-size: 1.1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .flatpickr-weekday {
            font-weight: 500;
            color: #9ca3af;
            font-size: 0.85rem;
        }

        .flatpickr-day {
            border-radius: 50% !important;
            height: 42px;
            line-height: 42px;
            width: 14.2857%;
            max-width: none;
            font-weight: 500;
            border: 2px solid transparent !important;
            margin: 2px 0;
        }

        .flatpickr-day.inRange {
            background: #fff7ed !important;
            border-color: #fff7ed !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            color: #ea580c !important;
        }

        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange {
            background: #ea580c !important;
            border-color: #ea580c !important;
            color: white !important;
            border-radius: 50% !important;
            z-index: 2;
        }

        .flatpickr-calendar.rangeMode {
            width: 100% !important;
        }

        .flatpickr-calendar .flatpickr-innerContainer {
            margin-top: 10px;
        }

        [dir="rtl"] .flatpickr-calendar {
            direction: rtl;
        }

        .flatpickr-day.flatpickr-disabled,
        .flatpickr-day.flatpickr-disabled:hover {
            color: #cbd5e1 !important;
            opacity: 1 !important;
            cursor: not-allowed;
        }

        @media (max-width: 768px) {
            #calendarModal {
                min-width: 320px !important;
                width: 95vw;
                padding: 10px;
            }

            .flatpickr-calendar.rangeMode {
                width: 100% !important;
                flex-direction: column !important;
            }
        }

        /* Scrollbar hide utility */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #ea580c;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #d9480f;
        }
    </style>
@endpush

@section('content')
    <!-- Hotel Header -->
    <section class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('city.hotels', array_merge(['cityCode' => $hotelDetails['HotelDetails'][0]['CityCode'] ?? 'RUH', 'locale' => app()->getLocale()], request()->only(['CheckIn', 'CheckOut', 'PaxRooms', 'check_in', 'check_out']))) }}"
                    class="text-white/80 hover:text-white transition">
                    <i
                        class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                    {{ __('Back to List') }}
                </a>
            </div>
            @php
                $hotel = $hotelDetails['HotelDetails'][0] ?? null;
            @endphp
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                @if ($hotel && isset($hotel['HotelName']))
                    {{ $hotel['HotelName'] }}
                @else
                    {{ __('International Luxury Hotel') }} {{ $hotelId }}
                @endif
            </h1>
            <div class="flex items-center gap-4 flex-wrap">
                @if ($hotel && isset($hotel['Address']))
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                        <span>{{ $hotel['Address'] }}@if (isset($hotel['CityName']))
                                , {{ $hotel['CityName'] }}
                                @endif @if (isset($hotel['CountryName']))
                                    , {{ $hotel['CountryName'] }}
                                @endif
                        </span>
                    </div>
                @else
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                        <span>{{ request('destination', __('Riyadh')) }}, {{ __('Saudi Arabia') }}</span>
                    </div>
                @endif
                @if ($hotel && isset($hotel['HotelRating']))
                    <div class="flex items-center">
                        <div class="flex text-yellow-500 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}">
                            @for ($i = 0; $i < $hotel['HotelRating']; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                        </div>
                        <span
                            class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">{{ $hotel['HotelRating'] }}</span>
                    </div>
                @else
                    <div class="flex items-center">
                        <div class="flex text-yellow-500 {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}">
                            @for ($i = 0; $i < 5; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                        </div>
                        <span class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">4.8</span>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Hotel Images Gallery -->
    <section class="py-8 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @php
                $hotel = $hotelDetails['HotelDetails'][0] ?? null;
                $images = [];
                if ($hotel && isset($hotel['Images']) && is_array($hotel['Images'])) {
                    // Images is an array of URLs
                    foreach ($hotel['Images'] as $imgUrl) {
                        $images[] = ['ImageUrl' => $imgUrl];
                    }
                } elseif ($hotel && isset($hotel['Image']) && !empty($hotel['Image'])) {
                    // Single Image field
                    $images[] = ['ImageUrl' => $hotel['Image']];
                } elseif ($hotel && isset($hotel['ImageUrls']) && is_array($hotel['ImageUrls'])) {
                    $images = $hotel['ImageUrls'];
                }
                // Default images if none from API
                if (empty($images)) {
                    $images = [
                        [
                            'ImageUrl' =>
                                'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                        ],
                        [
                            'ImageUrl' =>
                                'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                        ],
                        [
                            'ImageUrl' =>
                                'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                        ],
                        [
                            'ImageUrl' =>
                                'https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                        ],
                        [
                            'ImageUrl' =>
                                'https://images.unsplash.com/photo-1564501049412-61c2a3083791?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                        ],
                    ];
                }
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4" id="hotelGallery">
                @foreach ($images as $index => $image)
                    @php
                        $imageUrl = $image['ImageUrl'] ?? (is_string($image) ? $image : '');
                    @endphp
                    @if ($index == 0)
                        <div class="md:col-span-2 md:row-span-2 cursor-pointer"
                            onclick="openImageModal({{ $index }})">
                            <img src="{{ $imageUrl }}" alt="{{ $hotel['HotelName'] ?? 'فندق' }}"
                                class="w-full h-[400px] object-cover rounded-2xl hover:opacity-90 transition">
                        </div>
                    @elseif($index < 5)
                        <div class="cursor-pointer" onclick="openImageModal({{ $index }})">
                            <img src="{{ $imageUrl }}" alt="{{ $hotel['HotelName'] ?? 'فندق' }}"
                                class="w-full h-48 object-cover rounded-2xl hover:opacity-90 transition">
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center">
        <div class="relative w-full h-full flex items-center justify-center p-4">
            <!-- Close Button -->
            <button onclick="closeImageModal()"
                class="absolute top-4 {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} text-white hover:text-orange-500 transition text-3xl z-10">
                <i class="fas fa-times"></i>
            </button>

            <!-- Previous Button -->
            <button onclick="changeImage(-1)"
                class="absolute {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }} top-1/2 transform -translate-y-1/2 text-white hover:text-orange-500 transition text-4xl z-10 bg-black bg-opacity-50 rounded-full w-12 h-12 flex items-center justify-center">
                <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}"></i>
            </button>

            <!-- Next Button -->
            <button onclick="changeImage(1)"
                class="absolute {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} top-1/2 transform -translate-y-1/2 text-white hover:text-orange-500 transition text-4xl z-10 bg-black bg-opacity-50 rounded-full w-12 h-12 flex items-center justify-center">
                <i class="fas fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}"></i>
            </button>

            <!-- Image Container -->
            <div class="max-w-7xl w-full h-full flex items-center justify-center">
                <img id="modalImage" src="" alt="فندق" class="max-w-full max-h-full object-contain rounded-lg">
            </div>

            <!-- Image Counter -->
            <div
                class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white bg-black bg-opacity-50 px-4 py-2 rounded-full text-sm">
                <span id="imageCounter">1 / 5</span>
            </div>
        </div>
    </div>

    <!-- Hotel Info & Rooms -->
    <section class="py-8 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Hotel Details -->
                <div class="lg:col-span-2">
                    <!-- Description -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('About the Hotel') }}</h2>
                        @php
                            $hotel = $hotelDetails['HotelDetails'][0] ?? null;
                        @endphp
                        @if ($hotel && isset($hotel['Description']))
                            <div class="text-gray-700 leading-relaxed">
                                {!! $hotel['Description'] !!}
                            </div>
                        @else
                            <p class="text-gray-700 leading-relaxed mb-4">
                                {{ __('Hotel Description 1') }}
                            </p>
                            <p class="text-gray-700 leading-relaxed">
                                {{ __('Hotel Description 2') }}
                            </p>
                        @endif
                    </div>

                    <!-- Amenities -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Facilities and Services') }}</h2>
                        @php
                            $hotel = $hotelDetails['HotelDetails'][0] ?? null;
                            $facilities = $hotel['HotelFacilities'] ?? [];
                            $totalFacilities = count($facilities);
                            $showLimit = 10;
                            $hasMore = $totalFacilities > $showLimit;
                        @endphp
                        @if (!empty($facilities) && is_array($facilities))
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="facilitiesList">
                                @foreach ($facilities as $index => $facility)
                                    <div
                                        class="flex items-center facility-item {{ $index >= $showLimit ? 'hidden' : '' }}">
                                        <i
                                            class="fas fa-check-circle text-orange-600 text-lg {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                                        <span class="text-gray-700">{{ $facility }}</span>
                                    </div>
                                @endforeach
                            </div>
                            @if ($hasMore)
                                <div class="mt-4 text-center">
                                    <button type="button" id="showMoreFacilities"
                                        class="text-orange-600 hover:text-orange-700 font-semibold transition">
                                        <i
                                            class="fas fa-chevron-down {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                                        {{ __('Show More') }} ({{ $totalFacilities - $showLimit }} {{ __('more') }})
                                    </button>
                                    <button type="button" id="showLessFacilities"
                                        class="text-orange-600 hover:text-orange-700 font-semibold transition hidden">
                                        <i
                                            class="fas fa-chevron-up {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                                        {{ __('Show Less') }}
                                    </button>
                                </div>
                            @endif
                        @else
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div class="flex items-center">
                                    <i
                                        class="fas fa-wifi text-orange-600 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                                    <span class="text-gray-700">{{ __('Free WiFi') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i
                                        class="fas fa-swimming-pool text-orange-600 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                                    <span class="text-gray-700">{{ __('Pool') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i
                                        class="fas fa-utensils text-orange-600 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                                    <span class="text-gray-700">{{ __('Restaurant') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i
                                        class="fas fa-spa text-orange-600 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                                    <span class="text-gray-700">{{ __('Spa') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i
                                        class="fas fa-dumbbell text-orange-600 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                                    <span class="text-gray-700">{{ __('Gym') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i
                                        class="fas fa-parking text-orange-600 text-xl {{ app()->getLocale() === 'ar' ? 'ml-3' : 'mr-3' }}"></i>
                                    <span class="text-gray-700">{{ __('Parking') }}</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Date Selection & Availability Check -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Select Dates to View Availability') }}
                        </h2>

                        <form id="availabilityForm" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <!-- Check-in & Check-out Container -->
                            <div class="relative md:col-span-2 z-[10]" id="dateRangeContainer">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Check-in -->
                                    <div class="relative">
                                        <label
                                            class="block text-gray-700 dark:text-gray-300 text-xs font-bold mb-2 uppercase">
                                            <i
                                                class="fas fa-calendar-alt text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                            {{ __('Check In') }}
                                        </label>
                                        <div class="relative">
                                            <input type="text" id="checkInDisplay" readonly
                                                placeholder="{{ __('Check In') }}"
                                                class="w-full px-4 py-3 border-2 border-gray-100 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100 font-medium cursor-pointer bg-white">
                                            <input type="hidden" name="check_in" id="checkInInput"
                                                value="{{ $checkIn }}">
                                        </div>
                                    </div>

                                    <!-- Check-out -->
                                    <div class="relative">
                                        <label
                                            class="block text-gray-700 dark:text-gray-300 text-xs font-bold mb-2 uppercase">
                                            <i
                                                class="fas fa-calendar-check text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                            {{ __('Check Out') }}
                                        </label>
                                        <div class="relative">
                                            <input type="text" id="checkOutDisplay" readonly
                                                placeholder="{{ __('Check Out') }}"
                                                class="w-full px-4 py-3 border-2 border-gray-100 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 dark:text-gray-100 font-medium cursor-pointer bg-white">
                                            <input type="hidden" name="check_out" id="checkOutInput"
                                                value="{{ $checkOut }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Manual Calendar Modal -->
                                <div id="calendarModal"
                                    class="absolute left-1/2 -translate-x-1/2 z-[40] hidden top-full mt-2 w-screen max-w-[90vw] md:w-auto">
                                    <div
                                        class="bg-white rounded-3xl shadow-2xl p-6 border border-gray-100 w-full md:w-[850px] max-w-full overflow-hidden">
                                        <div id="calendarAnchor"></div>
                                        <div class="p-3 border-t border-gray-100 flex justify-end">
                                            <button type="button" id="closeCalendar"
                                                class="bg-orange-500 text-white px-6 py-2 rounded-lg font-bold hover:bg-orange-600 transition">
                                                {{ __('Done') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Rooms & Guests -->
                            <div class="relative z-[20]">
                                <label class="block text-gray-700 dark:text-gray-300 text-xs font-bold mb-2 uppercase">
                                    <i
                                        class="fas fa-users text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ __('Rooms & Guests') }}
                                </label>

                                <div id="guestsSelectorTrigger"
                                    class="w-full px-4 py-3 border-2 border-gray-100 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-xl cursor-pointer bg-white flex items-center justify-between select-none text-gray-900 font-medium">
                                    <span id="guestsSummary" class="truncate text-sm">1 {{ __('Room') }}, 2
                                        {{ __('Adults') }}</span>
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>

                                <div id="guestsDropdown"
                                    class="absolute z-50 mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl hidden p-6 w-[320px] md:w-[400px] {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }}">
                                    <div id="roomsContainer"
                                        class="space-y-4 max-h-60 overflow-y-auto custom-scrollbar mb-4 pl-[5px]">
                                        <!-- Rooms will be rendered here by JS -->
                                    </div>

                                    <div
                                        class="flex justify-between items-center pt-2 border-t border-gray-100 dark:border-gray-700">
                                        <button type="button" id="addRoomBtn"
                                            class="text-orange-600 text-sm font-bold hover:text-orange-700 flex items-center">
                                            <i
                                                class="fas fa-plus {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                            {{ __('Add Room') }}
                                        </button>
                                        <button type="button" id="doneBtn"
                                            class="bg-orange-600 text-white px-6 py-2 rounded-lg text-sm font-bold hover:bg-orange-700 transition">
                                            {{ __('Done') }}
                                        </button>
                                    </div>
                                </div>

                                <div id="hiddenGuestInputs">
                                    <!-- Hidden inputs will be rendered here by JS -->
                                </div>
                            </div>

                            <div class="flex items-end">
                                <button type="button" id="checkAvailabilityBtn"
                                    class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-3.5 rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 transition shadow-lg text-sm">
                                    <i class="fas fa-search {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                                    {{ __('Check Availability') }}
                                </button>
                            </div>
                        </form>
                        <div id="availabilityMessage" class="hidden mt-2 p-3 rounded-lg text-sm"></div>
                    </div>

                    <!-- Extra Features Selection -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Extra Features') }}</h2>
                        <p class="text-gray-600 text-sm mb-4">{{ __('Select extra features for your stay') }}</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <label
                                class="flex items-center p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                                <input type="checkbox" name="extra_features[]" value="late_checkout"
                                    class="w-5 h-5 text-orange-600 rounded">
                                <span class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">
                                    <i
                                        class="fas fa-clock text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ __('Late Check-out') }}
                                </span>
                            </label>
                            <label
                                class="flex items-center p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                                <input type="checkbox" name="extra_features[]" value="early_checkin"
                                    class="w-5 h-5 text-orange-600 rounded">
                                <span class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">
                                    <i
                                        class="fas fa-early-bird text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ __('Early Check-in') }}
                                </span>
                            </label>
                            <label
                                class="flex items-center p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                                <input type="checkbox" name="extra_features[]" value="extra_bed"
                                    class="w-5 h-5 text-orange-600 rounded">
                                <span class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">
                                    <i
                                        class="fas fa-bed text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ __('Extra Bed') }}
                                </span>
                            </label>
                            <label
                                class="flex items-center p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                                <input type="checkbox" name="extra_features[]" value="baby_cot"
                                    class="w-5 h-5 text-orange-600 rounded">
                                <span class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">
                                    <i
                                        class="fas fa-baby text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ __('Baby Cot') }}
                                </span>
                            </label>
                            <label
                                class="flex items-center p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                                <input type="checkbox" name="extra_features[]" value="room_upgrade"
                                    class="w-5 h-5 text-orange-600 rounded">
                                <span class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">
                                    <i
                                        class="fas fa-arrow-up text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ __('Room Upgrade') }}
                                </span>
                            </label>
                            <label
                                class="flex items-center p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                                <input type="checkbox" name="extra_features[]" value="spa_package"
                                    class="w-5 h-5 text-orange-600 rounded">
                                <span class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">
                                    <i
                                        class="fas fa-spa text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ __('Spa Package') }}
                                </span>
                            </label>
                            <label
                                class="flex items-center p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 transition">
                                <input type="checkbox" name="extra_features[]" value="dinner_package"
                                    class="w-5 h-5 text-orange-600 rounded">
                                <span class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }} text-sm text-gray-700">
                                    <i
                                        class="fas fa-utensils text-orange-500 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                    {{ __('Dinner Package') }}
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Available Rooms -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Available Rooms') }}</h2>

                        <div id="roomsContainer" class="space-y-6">
                            <div id="noRoomsMessage" class="hidden text-center py-8 text-gray-500">
                                <i class="fas fa-calendar-times text-4xl mb-4"></i>
                                <p class="text-lg">{{ __('No rooms available for selected dates') }}</p>
                                <p class="text-sm mt-2">{{ __('Please select check-in and check-out dates') }}</p>
                            </div>

                            <div id="roomsList" class="space-y-6">
                                @if (isset($availableRooms) && count($availableRooms) > 0)
                                    @foreach ($availableRooms as $index => $room)
                                        <div
                                            class="border-2 border-gray-200 rounded-2xl p-6 hover:border-orange-500 transition">
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                                <!-- Room Image -->
                                                <div class="relative h-48 md:h-full min-h-[200px]">
                                                    <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                                                        alt="غرفة" class="w-full h-full object-cover rounded-xl">
                                                </div>

                                                <!-- Room Details -->
                                                <div class="md:col-span-2">
                                                    <div class="flex items-start justify-between mb-3">
                                                        <div>
                                                            <h3 class="text-xl font-bold text-gray-900 mb-2">
                                                                {{ is_array($room['Name']) ? $room['Name'][0] : $room['Name'] }}
                                                            </h3>
                                                            <div class="flex flex-wrap gap-2 mb-3">
                                                                <span
                                                                    class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg">
                                                                    <i
                                                                        class="fas fa-bed {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                                                    {{ __('Room') }}
                                                                </span>
                                                                @if (isset($room['Amenities']))
                                                                    @foreach (array_slice($room['Amenities'] ?? [], 0, 3) as $amenity)
                                                                        <span
                                                                            class="px-2 py-1 bg-green-50 text-green-700 text-xs rounded-lg">
                                                                            <i
                                                                                class="fas fa-check {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                                                            {{ $amenity }}
                                                                        </span>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="flex items-center justify-between pt-4 border-t border-gray-200">
                                                        <div>
                                                            <div class="flex items-baseline">
                                                                <span class="text-3xl font-extrabold text-orange-600">
                                                                    {{ $room['TotalFare'] ?? ($room['Price']['PublishedPrice'] ?? 0) }}
                                                                </span>
                                                                <span
                                                                    class="text-gray-500 text-sm {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">
                                                                    {{ isset($currency) ? $currency : request('currency', 'USD') }}
                                                                </span>
                                                            </div>
                                                            <div class="text-xs text-gray-400">/ {{ __('Total Price') }}
                                                                • {{ __('including taxes') }}</div>
                                                        </div>
                                                        <a href="{{ route('reservation') }}?hotel_id={{ $hotelId }}&booking_code={{ $room['BookingCode'] ?? '' }}&check_in={{ request('check_in') }}&check_out={{ request('check_out') }}&guests={{ request('guests') }}"
                                                            class="bg-gradient-to-r from-orange-600 to-orange-600 text-white px-8 py-3 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition shadow-lg">
                                                            {{ __('Book Now') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-8 text-gray-500">
                                        <i class="fas fa-calendar-times text-4xl mb-4"></i>
                                        <p class="text-lg">{{ __('No rooms available for selected dates') }}</p>
                                    </div>
                                @endif
                            </div>


                        </div>
                    </div>
                </div>

                <!-- Hotel Info Sidebar -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24 space-y-6">
                        <!-- Hotel Quick Info -->
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">{{ __('Hotel Information') }}</h3>
                            @php
                                $hotel = $hotelDetails['HotelDetails'][0] ?? null;
                            @endphp
                            <div class="space-y-4">
                                <!-- Location -->
                                <div class="flex items-start">
                                    <div
                                        class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-map-marker-alt text-orange-600"></i>
                                    </div>
                                    <div class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }}">
                                        <div class="font-semibold text-gray-900 mb-1">{{ __('Location') }}</div>
                                        <p class="text-sm text-gray-600">
                                            @if ($hotel && isset($hotel['Address']))
                                                {{ $hotel['Address'] }}@if (isset($hotel['PinCode']))
                                                    , {{ $hotel['PinCode'] }}
                                                @endif
                                                <br>
                                                @if (isset($hotel['CityName']))
                                                    {{ $hotel['CityName'] }},
                                                @endif
                                                @if (isset($hotel['CountryName']))
                                                    {{ $hotel['CountryName'] }}
                                                @endif
                                            @else
                                                {{ request('destination', __('Riyadh')) }}, {{ __('Saudi Arabia') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Rating -->
                                <div class="flex items-start">
                                    <div
                                        class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-star text-orange-600"></i>
                                    </div>
                                    <div class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }}">
                                        <div class="font-semibold text-gray-900 mb-1">{{ __('Rating') }}</div>
                                        <div class="flex items-center">
                                            <div
                                                class="flex text-yellow-500 text-sm {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}">
                                                @for ($i = 0; $i < ($hotel['HotelRating'] ?? 5); $i++)
                                                    <i class="fas fa-star"></i>
                                                @endfor
                                            </div>
                                            <span class="text-sm text-gray-600">{{ $hotel['HotelRating'] ?? '4.8' }}
                                                @if (isset($hotel['HotelRating']))
                                                    {{ __('Stars') }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Check-in/out Times -->
                                @if ($hotel && (isset($hotel['CheckInTime']) || isset($hotel['CheckOutTime'])))
                                    <div class="flex items-start">
                                        <div
                                            class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-clock text-orange-600"></i>
                                        </div>
                                        <div class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }}">
                                            <div class="font-semibold text-gray-900 mb-1">
                                                {{ __('Check-in / Check-out') }}</div>
                                            <p class="text-sm text-gray-600">
                                                {{ __('Check-in') }}: {{ $hotel['CheckInTime'] ?? '2:00 PM' }}<br>
                                                {{ __('Check-out') }}: {{ $hotel['CheckOutTime'] ?? '12:00 PM' }}
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Website -->
                                @if ($hotel && isset($hotel['HotelWebsiteUrl']))
                                    <div class="flex items-start">
                                        <div
                                            class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-globe text-orange-600"></i>
                                        </div>
                                        <div class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }}">
                                            <div class="font-semibold text-gray-900 mb-1">{{ __('Website') }}</div>
                                            <p class="text-sm text-gray-600">
                                                <a href="{{ $hotel['HotelWebsiteUrl'] }}" target="_blank"
                                                    class="hover:text-orange-600 break-all">{{ $hotel['HotelWebsiteUrl'] }}</a>
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Hotel Policies -->
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">{{ __('Hotel Policies') }}</h3>

                            <div class="space-y-4">
                                <!-- Cancellation Policy -->
                                <div class="bg-orange-50 border border-orange-200 rounded-xl p-4">
                                    <div class="flex items-start">
                                        <i
                                            class="fas fa-check-circle text-orange-600 text-lg {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }} mt-1"></i>
                                        <div>
                                            <div class="font-semibold text-orange-900 mb-1 text-sm">
                                                {{ __('Free Cancellation') }}</div>
                                            <div class="text-xs text-orange-700">
                                                {{ __('You can cancel for free up to 24 hours before arrival') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Policy -->
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                    <div class="flex items-start">
                                        <i
                                            class="fas fa-credit-card text-blue-600 text-lg {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }} mt-1"></i>
                                        <div>
                                            <div class="font-semibold text-blue-900 mb-1 text-sm">
                                                {{ __('Payment Policy') }}</div>
                                            <div class="text-xs text-blue-700">
                                                {{ __('Pay at the hotel or online payment available') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pet Policy -->
                                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                    <div class="flex items-start">
                                        <i
                                            class="fas fa-paw text-gray-600 text-lg {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }} mt-1"></i>
                                        <div>
                                            <div class="font-semibold text-gray-900 mb-1 text-sm">{{ __('Pet Policy') }}
                                            </div>
                                            <div class="text-xs text-gray-700">{{ __('Pets are not allowed') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Hotel -->
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">{{ __('Contact Hotel') }}</h3>
                            @php
                                $hotel = $hotelDetails['HotelDetails'][0] ?? null;
                            @endphp
                            <div class="space-y-4">
                                <!-- Phone -->
                                @if ($hotel && isset($hotel['PhoneNumber']))
                                    <div class="flex items-start">
                                        <div
                                            class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-phone text-orange-600"></i>
                                        </div>
                                        <div class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }}">
                                            <div class="font-semibold text-gray-900 mb-1">{{ __('Phone') }}</div>
                                            <p class="text-sm text-gray-600">
                                                <a href="tel:{{ $hotel['PhoneNumber'] }}"
                                                    class="hover:text-orange-600">{{ $hotel['PhoneNumber'] }}</a>
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Email -->
                                @if ($hotel && isset($hotel['Email']))
                                    <div class="flex items-start">
                                        <div
                                            class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-envelope text-orange-600"></i>
                                        </div>
                                        <div class="{{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }}">
                                            <div class="font-semibold text-gray-900 mb-1">{{ __('Email') }}</div>
                                            <p class="text-sm text-gray-600">
                                                <a href="mailto:{{ $hotel['Email'] }}"
                                                    class="hover:text-orange-600 break-all">{{ $hotel['Email'] }}</a>
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @if (app()->getLocale() === 'ar')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ar.js"></script>
    @endif
    <script>
        // Hotel Images Gallery - Get images from API or use defaults
        @php
            $hotel = $hotelDetails['HotelDetails'][0] ?? null;
            $apiImages = [];
            if ($hotel && isset($hotel['Images']) && is_array($hotel['Images'])) {
                // Images is an array of URLs
                $apiImages = array_slice($hotel['Images'], 0, 5);
            } elseif ($hotel && isset($hotel['Image']) && !empty($hotel['Image'])) {
                // Single Image field
                $apiImages = [$hotel['Image']];
            }
            $defaultImages = ['https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80', 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80', 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80', 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80', 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'];
            $hotelImagesArray = !empty($apiImages) ? $apiImages : $defaultImages;
        @endphp
        const hotelImages = @json($hotelImagesArray);

        let currentImageIndex = 0;

        function openImageModal(index) {
            currentImageIndex = index;
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const imageCounter = document.getElementById('imageCounter');

            modalImage.src = hotelImages[index];
            imageCounter.textContent = `${index + 1} / ${hotelImages.length}`;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function changeImage(direction) {
            currentImageIndex += direction;

            if (currentImageIndex < 0) {
                currentImageIndex = hotelImages.length - 1;
            } else if (currentImageIndex >= hotelImages.length) {
                currentImageIndex = 0;
            }

            const modalImage = document.getElementById('modalImage');
            const imageCounter = document.getElementById('imageCounter');

            modalImage.src = hotelImages[currentImageIndex];
            imageCounter.textContent = `${currentImageIndex + 1} / ${hotelImages.length}`;
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            } else if (e.key === 'ArrowLeft') {
                changeImage(-1);
            } else if (e.key === 'ArrowRight') {
                changeImage(1);
            }
        });

        // Close modal when clicking outside image
        document.getElementById('imageModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // -----------------------------------------------------------------------------
        // DATE RANGE PICKER (FLATPICKR)
        // -----------------------------------------------------------------------------
        const checkInDisplay = document.getElementById('checkInDisplay');
        const checkOutDisplay = document.getElementById('checkOutDisplay');
        const checkInInput = document.getElementById('checkInInput');
        const checkOutInput = document.getElementById('checkOutInput');
        const calendarModal = document.getElementById('calendarModal');
        const closeCalendarBtn = document.getElementById('closeCalendar');
        const dateRangeContainer = document.getElementById('dateRangeContainer');

        const isRTL = document.documentElement.dir === 'rtl';
        const localeTag = "{{ app()->getLocale() }}";

        const formatDate = (date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        };

        const config = {
            mode: "range",
            minDate: "today",
            inline: true,
            appendTo: document.getElementById('calendarAnchor'),
            dateFormat: "Y-m-d",
            showMonths: window.innerWidth < 768 ? 1 : 2,
            locale: localeTag === 'ar' ? flatpickr.l10ns.ar : flatpickr.l10ns.default,
            disableMobile: true,
            defaultDate: [checkInInput.value, checkOutInput.value].filter(v => v),
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    const startDate = selectedDates[0];
                    checkInInput.value = instance.formatDate(startDate, "Y-m-d");
                    checkInDisplay.value = instance.formatDate(startDate, isRTL ? "l j F Y" : "D, M j, Y");
                }
                if (selectedDates.length === 2) {
                    const endDate = selectedDates[1];
                    checkOutInput.value = instance.formatDate(endDate, "Y-m-d");
                    checkOutDisplay.value = instance.formatDate(endDate, isRTL ? "l j F Y" : "D, M j, Y");
                } else {
                    checkOutInput.value = '';
                    checkOutDisplay.value = '';
                }
            }
        };

        const fp = flatpickr(checkInDisplay, config);

        // Pre-fill display inputs if we have initial values
        if (checkInInput.value) {
            const d = new Date(checkInInput.value);
            checkInDisplay.value = fp.formatDate(d, isRTL ? "l j F Y" : "D, M j, Y");
        }
        if (checkOutInput.value) {
            const d = new Date(checkOutInput.value);
            checkOutDisplay.value = fp.formatDate(d, isRTL ? "l j F Y" : "D, M j, Y");
        }

        window.addEventListener('resize', () => {
            const newShowMonths = window.innerWidth < 768 ? 1 : 2;
            if (fp.config.showMonths !== newShowMonths) {
                fp.set('showMonths', newShowMonths);
            }
        });

        function toggleCalendar(show = true) {
            if (show) {
                calendarModal.classList.remove('hidden');
                dateRangeContainer.style.zIndex = '150';
            } else {
                calendarModal.classList.add('hidden');
                dateRangeContainer.style.zIndex = '100';
            }
        }

        checkInDisplay.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleCalendar(true);
        });
        checkOutDisplay.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleCalendar(true);
        });
        closeCalendarBtn.addEventListener('click', () => toggleCalendar(false));

        document.addEventListener('click', (e) => {
            if (!calendarModal.contains(e.target) && !checkInDisplay.contains(e.target) && !checkOutDisplay
                .contains(e.target)) {
                toggleCalendar(false);
            }
        });

        // -----------------------------------------------------------------------------
        // MULTI-ROOM GUEST SELECTOR
        // -----------------------------------------------------------------------------
        const guestsTrigger = document.getElementById('guestsSelectorTrigger');
        const guestsDropdown = document.getElementById('guestsDropdown');
        const guestsRoomsContainer = document.getElementById('roomsContainer');
        const addRoomBtn = document.getElementById('addRoomBtn');
        const doneBtn = document.getElementById('doneBtn');
        const hiddenInputsContainer = document.getElementById('hiddenGuestInputs');
        const guestsSummary = document.getElementById('guestsSummary');

        let rooms = @json($paxRooms ?? [['Adults' => 2, 'Children' => 0, 'ChildrenAges' => []]]);
        // Normalize keys
        rooms = rooms.map(r => ({
            adults: r.Adults || r.adults || 1,
            children: r.Children || r.children || 0,
            childrenAges: r.ChildrenAges || r.childrenAges || []
        }));

        function renderRooms() {
            guestsRoomsContainer.innerHTML = '';
            hiddenInputsContainer.innerHTML = '';
            let totalAdults = 0;
            let totalChildren = 0;

            rooms.forEach((room, index) => {
                totalAdults += room.adults;
                totalChildren += room.children;

                const roomEl = document.createElement('div');
                roomEl.className =
                    'room-item border-b border-gray-100 dark:border-gray-700 pb-4 last:border-0 mb-4';

                let childAgesHtml = '';
                if (room.children > 0) {
                    childAgesHtml += `<div class="mt-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('Child Ages') }}</label>
                        <div class="grid grid-cols-3 gap-2">`;
                    room.childrenAges.forEach((age, ageIndex) => {
                        let options = '';
                        for (let i = 0; i <= 12; i++) {
                            options += `<option value="${i}" ${age == i ? 'selected' : ''}>${i}</option>`;
                        }
                        childAgesHtml += `
                            <div class="flex flex-col">
                                <label class="text-[10px] text-gray-500 mb-0.5">{{ __('Child') }} ${ageIndex + 1}</label>
                                <select class="child-age-select w-full bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-xs border border-gray-200 dark:border-gray-600 rounded-md p-1" 
                                    data-room-index="${index}" data-age-index="${ageIndex}">${options}</select>
                            </div>`;
                    });
                    childAgesHtml += `</div></div>`;
                }

                roomEl.innerHTML = `
                    <div class="flex justify-between items-center mb-2">
                        <h4 class="font-bold text-sm text-gray-900 dark:text-white">{{ __('Room') }} ${index + 1}</h4>
                        ${index > 0 ? `<button type="button" class="remove-room-btn text-red-500 text-xs hover:text-red-700" data-index="${index}">{{ __('Remove') }}</button>` : ''}
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block text-xs text-gray-500 mb-1">{{ __('Adults') }}</label>
                            <div class="flex items-center border border-gray-200 dark:border-gray-600 rounded-lg">
                                <button type="button" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-l-lg decrease-adults" data-index="${index}">-</button>
                                <span class="flex-1 text-center text-sm font-bold text-gray-900 dark:text-white">${room.adults}</span>
                                <button type="button" class="w-8 h-8 flex items-center justify-center text-orange-500 hover:bg-orange-50 rounded-r-lg increase-adults" data-index="${index}">+</button>
                            </div>
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs text-gray-500 mb-1">{{ __('Children') }}</label>
                            <div class="flex items-center border border-gray-200 dark:border-gray-600 rounded-lg">
                                <button type="button" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-l-lg decrease-children" data-index="${index}">-</button>
                                <span class="flex-1 text-center text-sm font-bold text-gray-900 dark:text-white">${room.children}</span>
                                <button type="button" class="w-8 h-8 flex items-center justify-center text-orange-500 hover:bg-orange-50 rounded-r-lg increase-children" data-index="${index}">+</button>
                            </div>
                            <span class="text-[10px] text-gray-400 block mt-1 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                                {{ app()->getLocale() == 'ar' ? 'من 0 إلى 12 سنة' : 'From 0 to 12 years' }}
                            </span>
                        </div>
                    </div>
                    ${childAgesHtml}`;
                guestsRoomsContainer.appendChild(roomEl);

                // Add to hidden inputs
                hiddenInputsContainer.innerHTML +=
                    `<input type="hidden" name="PaxRooms[${index}][Adults]" value="${room.adults}">`;
                hiddenInputsContainer.innerHTML +=
                    `<input type="hidden" name="PaxRooms[${index}][Children]" value="${room.children}">`;
                room.childrenAges.forEach(age => {
                    hiddenInputsContainer.innerHTML +=
                        `<input type="hidden" name="PaxRooms[${index}][ChildrenAges][]" value="${age}">`;
                });
            });

            // Update Summary
            const roomTxt = rooms.length === 1 ? "{{ __('Room') }}" : "{{ __('Rooms') }}";
            const adultTxt = totalAdults === 1 ? "{{ __('Adult') }}" : "{{ __('Adults') }}";
            const childTxt = totalChildren === 1 ? "{{ __('Child') }}" : "{{ __('Children') }}";

            let summary = `${rooms.length} ${roomTxt}, ${totalAdults} ${adultTxt}`;
            if (totalChildren > 0) summary += `, ${totalChildren} ${childTxt}`;
            guestsSummary.textContent = summary;
        }

        guestsTrigger.addEventListener('click', (e) => {
            e.stopPropagation();
            guestsDropdown.classList.toggle('hidden');
        });
        guestsDropdown.addEventListener('click', (e) => e.stopPropagation());
        document.addEventListener('click', () => guestsDropdown.classList.add('hidden'));
        doneBtn.addEventListener('click', () => guestsDropdown.classList.add('hidden'));

        addRoomBtn.addEventListener('click', () => {
            if (rooms.length < 5) {
                rooms.push({
                    adults: 1,
                    children: 0,
                    childrenAges: []
                });
                renderRooms();
            }
        });

        guestsRoomsContainer.addEventListener('click', (e) => {
            const index = parseInt(e.target.dataset.index);
            if (isNaN(index)) return;
            if (e.target.classList.contains('increase-adults') && rooms[index].adults < 10) rooms[index].adults++;
            else if (e.target.classList.contains('decrease-adults') && rooms[index].adults > 1) rooms[index]
                .adults--;
            else if (e.target.classList.contains('increase-children') && rooms[index].children < 6) {
                rooms[index].children++;
                rooms[index].childrenAges.push(0);
            } else if (e.target.classList.contains('decrease-children') && rooms[index].children > 0) {
                rooms[index].children--;
                rooms[index].childrenAges.pop();
            } else if (e.target.classList.contains('remove-room-btn')) rooms.splice(index, 1);
            renderRooms();
        });

        guestsRoomsContainer.addEventListener('change', (e) => {
            if (e.target.classList.contains('child-age-select')) {
                const ri = parseInt(e.target.dataset.roomIndex);
                const ai = parseInt(e.target.dataset.ageIndex);
                rooms[ri].childrenAges[ai] = parseInt(e.target.value);
            }
        });

        renderRooms();

        // -----------------------------------------------------------------------------
        // SEARCH LOGIC
        // -----------------------------------------------------------------------------
        async function searchRooms() {
            const checkIn = checkInInput.value;
            const checkOut = checkOutInput.value;

            const roomsList = document.getElementById('roomsList');
            const noRoomsMessage = document.getElementById('noRoomsMessage');
            const availabilityMessage = document.getElementById('availabilityMessage');
            const checkAvailabilityBtn = document.getElementById('checkAvailabilityBtn');

            if (!checkIn || !checkOut) {
                availabilityMessage.textContent = '{{ __('Please select check-in and check-out dates') }}';
                availabilityMessage.className = 'mt-2 p-3 rounded-lg text-sm bg-red-50 text-red-600';
                availabilityMessage.classList.remove('hidden');
                return;
            }

            // Update URL
            const url = new URL(window.location.href);
            url.searchParams.set('check_in', checkIn);
            url.searchParams.set('check_out', checkOut);
            let totalGuests = rooms.reduce((acc, r) => acc + r.adults + r.children, 0);
            url.searchParams.set('guests', totalGuests);
            window.history.replaceState({}, '', url);

            // UI Feedback
            if (checkAvailabilityBtn) {
                checkAvailabilityBtn.disabled = true;
                checkAvailabilityBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>{{ __('Loading...') }}';
            }
            if (roomsList) roomsList.innerHTML = '';
            if (noRoomsMessage) noRoomsMessage.classList.add('hidden');
            availabilityMessage.textContent = '{{ __('Searching for availability...') }}';
            availabilityMessage.className = 'mt-2 p-3 rounded-lg text-sm bg-blue-50 text-blue-600';
            availabilityMessage.classList.remove('hidden');

            const searchData = {
                CheckIn: checkIn,
                CheckOut: checkOut,
                HotelCodes: '{{ $hotelId }}',
                GuestNationality: 'SA',
                PaxRooms: rooms.map(r => ({
                    Adults: r.adults,
                    Children: r.children,
                    ChildrenAges: r.childrenAges
                }))
            };

            try {
                const response = await fetch("{{ route('hotel.search') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(searchData)
                });

                const data = await response.json();

                if (!response.ok || (data.Status && data.Status.Code !== 200)) {
                    throw new Error('No availability found');
                }

                displayRooms(data, checkIn, checkOut, totalGuests);
            } catch (err) {
                console.error(err);
                availabilityMessage.textContent = '{{ __('No rooms available for these dates.') }}';
                availabilityMessage.className = 'mt-2 p-3 rounded-lg text-sm bg-gray-100 text-gray-600';
                if (noRoomsMessage) noRoomsMessage.classList.remove('hidden');
                if (roomsList) roomsList.classList.add('hidden');
            } finally {
                if (checkAvailabilityBtn) {
                    checkAvailabilityBtn.disabled = false;
                    checkAvailabilityBtn.innerHTML =
                        '<i class="fas fa-search mr-2"></i>{{ __('Check Availability') }}';
                }
            }
        }

        function displayRooms(data, checkIn, checkOut, guests) {
            const roomsList = document.getElementById('roomsList');
            const noRoomsMessage = document.getElementById('noRoomsMessage');
            const availabilityMessage = document.getElementById('availabilityMessage');

            let hotels = data.HotelResult || data.Hotels || [];
            let allRooms = [];
            hotels.forEach(hotel => {
                if (hotel.Rooms) hotel.Rooms.forEach(room => allRooms.push({
                    ...room,
                    Currency: hotel.Currency || 'USD'
                }));
            });

            if (allRooms.length === 0) {
                if (noRoomsMessage) noRoomsMessage.classList.remove('hidden');
                if (roomsList) roomsList.classList.add('hidden');
                availabilityMessage.textContent = '{{ __('No rooms available.') }}';
                availabilityMessage.className = 'mt-2 p-3 rounded-lg text-sm bg-gray-100 text-gray-600';
                return;
            }

            roomsList.innerHTML = '';
            roomsList.classList.remove('hidden');
            if (noRoomsMessage) noRoomsMessage.classList.add('hidden');

            allRooms.forEach((room, index) => {
                const card = createRoomCard(room, index, checkIn, checkOut, guests);
                if (card) roomsList.appendChild(card);
            });

            availabilityMessage.textContent = `{{ __('Found') }} ${allRooms.length} {{ __('Available Rooms') }}`;
            availabilityMessage.className = 'mt-2 p-3 rounded-lg text-sm bg-green-50 text-green-600';

            // Update summary
            const nights = Math.ceil((new Date(checkOut) - new Date(checkIn)) / (86400000));
            updateBookingSummary(checkIn, checkOut, guests, nights);
        }

        function createRoomCard(room, index, checkIn, checkOut, guests) {
            const div = document.createElement('div');
            div.className =
                'border-2 border-gray-100 rounded-2xl p-6 hover:border-orange-500 transition shadow-sm bg-white mb-6';

            const roomName = room.Name?.[0] || room.Name || room.RoomName || `{{ __('Room') }} ${index+1}`;
            const price = parseFloat(room.TotalFare || room.Rate?.Amount || room.Price?.Amount || 0);
            const nights = Math.ceil((new Date(checkOut) - new Date(checkIn)) / (86400000));
            const perNight = nights > 0 ? (price / nights).toFixed(2) : price.toFixed(2);
            const currency = room.Currency || 'USD';

            // Serialize PaxRooms for the link
            let paxParams = '';
            rooms.forEach((r, rIdx) => {
                paxParams += `&PaxRooms[${rIdx}][Adults]=${r.adults}&PaxRooms[${rIdx}][Children]=${r.children}`;
                if (r.childrenAges && r.childrenAges.length > 0) {
                    r.childrenAges.forEach((age, aIdx) => {
                        paxParams += `&PaxRooms[${rIdx}][ChildrenAges][${aIdx}]=${age}`;
                    });
                }
            });

            div.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="h-48 md:h-full">
                        <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&w=400" class="w-full h-full object-cover rounded-xl shadow-inner">
                    </div>
                    <div class="md:col-span-2 flex flex-col justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">${roomName}</h3>
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg">${room.MealType || 'Room Only'}</span>
                                ${room.IsRefundable ? '<span class="px-2 py-1 bg-green-50 text-green-700 text-xs rounded-lg">{{ __('Refundable') }}</span>' : ''}
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div>
                                <div class="flex items-baseline">
                                    <span class="text-3xl font-extrabold text-orange-600">${perNight}</span>
                                    <span class="text-gray-500 text-sm ml-2">${currency}</span>
                                </div>
                                <div class="text-xs text-gray-400">{{ __('per night') }}</div>
                                ${nights > 1 ? `<div class="text-xs text-gray-500 mt-1">{{ __('Total') }}: ${price.toFixed(2)} ${currency}</div>` : ''}
                            </div>
                            <a href="{{ route('reservation') }}?hotel_id={{ $hotelId }}&CheckIn=${checkIn}&CheckOut=${checkOut}&guests=${guests}${paxParams}&booking_code=${encodeURIComponent(room.BookingCode || '')}&total_fare=${price}&currency=${currency}&room_name=${encodeURIComponent(roomName)}" 
                               class="bg-orange-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-orange-700 transition shadow-lg">
                                {{ __('Book Now') }}
                            </a>
                        </div>
                    </div>
                </div>`;
            return div;
        }

        function updateBookingSummary(checkIn, checkOut, guests, nights) {
            // Placeholder if needed to update sidebar
        }

        document.getElementById('checkAvailabilityBtn')?.addEventListener('click', searchRooms);

        document.addEventListener('DOMContentLoaded', () => {
            const hasDates = checkInInput.value && checkOutInput.value;
            if (hasDates) setTimeout(searchRooms, 500);

            // Facilities toggle
            const showMoreBtn = document.getElementById('showMoreFacilities');
            const showLessBtn = document.getElementById('showLessFacilities');
            const facilityItems = document.querySelectorAll('.facility-item');
            if (showMoreBtn) {
                showMoreBtn.onclick = () => {
                    facilityItems.forEach(i => i.classList.remove('hidden'));
                    showMoreBtn.classList.add('hidden');
                    showLessBtn.classList.remove('hidden');
                };
                showLessBtn.onclick = () => {
                    facilityItems.forEach((i, idx) => {
                        if (idx >= 10) i.classList.add('hidden');
                    });
                    showLessBtn.classList.add('hidden');
                    showMoreBtn.classList.remove('hidden');
                };
            }
        });
    </script>
@endpush
