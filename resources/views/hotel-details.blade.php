@extends('layouts.app')

@section('title', 'تفاصيل الفندق - حجز الفنادق')

@section('content')
<!-- Hotel Header -->
<section class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('hotels.search') }}?destination={{ request('destination') }}&check_in={{ request('check_in') }}&check_out={{ request('check_out') }}&guests={{ request('guests') }}" 
               class="text-white/80 hover:text-white transition">
                <i class="fas fa-arrow-right ml-2"></i> العودة للقائمة
            </a>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold mb-4">فندق الفخامة الدولي {{ $hotelId }}</h1>
        <div class="flex items-center gap-4 flex-wrap">
            <div class="flex items-center">
                <i class="fas fa-map-marker-alt ml-2"></i>
                <span>{{ request('destination', 'الرياض') }}، المملكة العربية السعودية</span>
            </div>
            <div class="flex items-center">
                <div class="flex text-yellow-500 ml-2">
                    @for($i = 0; $i < 5; $i++)
                    <i class="fas fa-star"></i>
                    @endfor
                </div>
                <span class="mr-2">4.8</span>
                <span class="text-white/70">(245 تقييم)</span>
            </div>
        </div>
    </div>
</section>

<!-- Hotel Images Gallery -->
<section class="py-8 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2 md:row-span-2">
                <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" 
                     alt="فندق" class="w-full h-full object-cover rounded-2xl">
            </div>
            <div>
                <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                     alt="فندق" class="w-full h-48 object-cover rounded-2xl">
            </div>
            <div>
                <img src="https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                     alt="فندق" class="w-full h-48 object-cover rounded-2xl">
            </div>
            <div>
                <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                     alt="فندق" class="w-full h-48 object-cover rounded-2xl">
            </div>
            <div>
                <img src="https://images.unsplash.com/photo-1564501049412-61c2a3083791?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                     alt="فندق" class="w-full h-48 object-cover rounded-2xl">
            </div>
        </div>
    </div>
</section>

<!-- Hotel Info & Rooms -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Hotel Details -->
            <div class="lg:col-span-2">
                <!-- Description -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">عن الفندق</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        يقع فندق الفخامة الدولي في قلب المدينة ويوفر إقامة فاخرة مع إطلالات خلابة. يتميز الفندق بموقعه الاستراتيجي القريب من أهم المعالم السياحية والتجارية، مما يجعله الخيار الأمثل للمسافرين.
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        يوفر الفندق مجموعة واسعة من المرافق والخدمات المميزة بما في ذلك مسبح خارجي، مركز لياقة بدنية، مطاعم متعددة، وخدمة الواي فاي المجانية في جميع أنحاء الفندق.
                    </p>
                </div>
                
                <!-- Amenities -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">المرافق والخدمات</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="flex items-center">
                            <i class="fas fa-wifi text-orange-600 text-xl ml-3"></i>
                            <span class="text-gray-700">واي فاي مجاني</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-swimming-pool text-orange-600 text-xl ml-3"></i>
                            <span class="text-gray-700">مسبح</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-utensils text-orange-600 text-xl ml-3"></i>
                            <span class="text-gray-700">مطعم</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-spa text-orange-600 text-xl ml-3"></i>
                            <span class="text-gray-700">سبا</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-dumbbell text-orange-600 text-xl ml-3"></i>
                            <span class="text-gray-700">جيم</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-parking text-orange-600 text-xl ml-3"></i>
                            <span class="text-gray-700">موقف سيارات</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-concierge-bell text-orange-600 text-xl ml-3"></i>
                            <span class="text-gray-700">خدمة الغرف</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt text-orange-600 text-xl ml-3"></i>
                            <span class="text-gray-700">خزنة</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-air-conditioner text-orange-600 text-xl ml-3"></i>
                            <span class="text-gray-700">تكييف</span>
                        </div>
                    </div>
                </div>
                
                <!-- Available Rooms -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">الغرف المتاحة</h2>
                    
                    <div class="space-y-6">
                        @for($i = 1; $i <= 3; $i++)
                        <div class="border-2 border-gray-200 rounded-2xl p-6 hover:border-orange-500 transition">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Room Image -->
                                <div class="relative h-48 md:h-full min-h-[200px]">
                                    <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                         alt="غرفة" class="w-full h-full object-cover rounded-xl">
                                    @if($i == 1)
                                    <div class="absolute top-4 right-4 bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold">
                                        الأكثر حجزاً
                                    </div>
                                    @endif
                                </div>
                                
                                <!-- Room Details -->
                                <div class="md:col-span-2">
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900 mb-2">
                                                @if($i == 1) غرفة ديلوكس @elseif($i == 2) غرفة سوبريور @else غرفة فاخرة @endif
                                            </h3>
                                            <div class="flex flex-wrap gap-2 mb-3">
                                                <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg">
                                                    <i class="fas fa-bed ml-1"></i> سرير كينج
                                                </span>
                                                <span class="px-2 py-1 bg-green-50 text-green-700 text-xs rounded-lg">
                                                    <i class="fas fa-users ml-1"></i> {{ 2 + $i }} أشخاص
                                                </span>
                                                <span class="px-2 py-1 bg-purple-50 text-purple-700 text-xs rounded-lg">
                                                    <i class="fas fa-ruler-combined ml-1"></i> {{ 25 + $i * 5 }} م²
                                                </span>
                                            </div>
                                            <p class="text-gray-600 text-sm mb-4">
                                                @if($i == 1)
                                                غرفة واسعة مع إطلالة على المدينة، تتضمن سرير كينج، تلفزيون بشاشة مسطحة، وميني بار.
                                                @elseif($i == 2)
                                                غرفة فاخرة مع شرفة خاصة وإطلالة على المسبح، تتضمن منطقة جلوس منفصلة.
                                                @else
                                                جناح فاخر مع غرفة معيشة منفصلة، إطلالة بانورامية، وجاكوزي خاص.
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                        <div>
                                            <div class="flex items-baseline">
                                                <span class="text-3xl font-extrabold text-orange-600">{{ 300 + $i * 100 }}</span>
                                                <span class="text-gray-500 text-sm mr-2">ريال</span>
                                            </div>
                                            <div class="text-xs text-gray-400">/ ليلة • شامل الضرائب</div>
                                            @if($i == 1)
                                            <div class="text-xs text-orange-600 font-semibold mt-1">
                                                <i class="fas fa-gift ml-1"></i> إفطار مجاني
                                            </div>
                                            @endif
                                        </div>
                                        <a href="{{ route('reservation') }}?hotel_id={{ $hotelId }}&room_type={{ $i }}&check_in={{ request('check_in') }}&check_out={{ request('check_out') }}&guests={{ request('guests') }}" 
                                           class="bg-gradient-to-r from-orange-600 to-orange-600 text-white px-8 py-3 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition shadow-lg">
                                            احجز الآن
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
            
            <!-- Booking Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">ملخص الحجز</h3>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between text-gray-700">
                            <span>تاريخ الوصول:</span>
                            <span class="font-semibold">{{ request('check_in', '--') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>تاريخ المغادرة:</span>
                            <span class="font-semibold">{{ request('check_out', '--') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>عدد الليالي:</span>
                            <span class="font-semibold">2 ليلة</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>عدد الضيوف:</span>
                            <span class="font-semibold">{{ request('guests', '2') }} ضيوف</span>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4 mb-6">
                        <div class="flex justify-between text-lg font-bold text-gray-900 mb-2">
                            <span>السعر الإجمالي:</span>
                            <span class="text-orange-600">600 ريال</span>
                        </div>
                        <div class="text-xs text-gray-500">شامل الضرائب والرسوم</div>
                    </div>
                    
                    <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-orange-600 text-xl ml-3 mt-1"></i>
                            <div>
                                <div class="font-semibold text-orange-900 mb-1">إلغاء مجاني</div>
                                <div class="text-xs text-orange-700">يمكنك الإلغاء مجاناً حتى 24 ساعة قبل الوصول</div>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('reservation') }}?hotel_id={{ $hotelId }}&check_in={{ request('check_in') }}&check_out={{ request('check_out') }}&guests={{ request('guests') }}" 
                       class="block w-full bg-gradient-to-r from-orange-600 to-orange-600 text-white text-center py-4 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition shadow-lg">
                        احجز الآن
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

