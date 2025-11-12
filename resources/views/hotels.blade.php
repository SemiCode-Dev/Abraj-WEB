@extends('layouts.app')

@section('title', 'الفنادق المتاحة - حجز الفنادق')

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-r from-orange-500 via-orange-600 to-blue-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2">الفنادق المتاحة</h1>
                <p class="text-orange-100">
                    <i class="fas fa-map-marker-alt ml-2"></i>
                    {{ request('destination', 'جميع الوجهات') }}
                </p>
            </div>
            <div class="hidden md:block bg-white/20 backdrop-blur-sm px-6 py-4 rounded-xl">
                <div class="text-sm text-orange-100 mb-1">تاريخ الإقامة</div>
                <div class="font-bold">
                    {{ request('check_in', '--') }} → {{ request('check_out', '--') }}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filters & Results -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Sidebar Filters -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">تصفية النتائج</h3>
                    
                    <!-- Price Range -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-900 mb-4">نطاق السعر</h4>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" class="w-5 h-5 text-orange-600 rounded">
                                <span class="mr-3 text-gray-700">أقل من 200 ريال</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="w-5 h-5 text-orange-600 rounded">
                                <span class="mr-3 text-gray-700">200 - 400 ريال</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="w-5 h-5 text-orange-600 rounded">
                                <span class="mr-3 text-gray-700">400 - 600 ريال</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="w-5 h-5 text-orange-600 rounded">
                                <span class="mr-3 text-gray-700">أكثر من 600 ريال</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Star Rating -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-900 mb-4">التصنيف</h4>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" class="w-5 h-5 text-orange-600 rounded">
                                <span class="mr-3 flex text-yellow-500">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="w-5 h-5 text-orange-600 rounded">
                                <span class="mr-3 flex text-yellow-500">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="w-5 h-5 text-orange-600 rounded">
                                <span class="mr-3 flex text-yellow-500">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Amenities -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-900 mb-4">المرافق</h4>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" class="w-5 h-5 text-orange-600 rounded">
                                <span class="mr-3 text-gray-700"><i class="fas fa-wifi text-orange-600 ml-2"></i> واي فاي مجاني</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="w-5 h-5 text-orange-600 rounded">
                                <span class="mr-3 text-gray-700"><i class="fas fa-swimming-pool text-orange-600 ml-2"></i> مسبح</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="w-5 h-5 text-orange-600 rounded">
                                <span class="mr-3 text-gray-700"><i class="fas fa-utensils text-orange-600 ml-2"></i> مطعم</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="w-5 h-5 text-orange-600 rounded">
                                <span class="mr-3 text-gray-700"><i class="fas fa-spa text-orange-600 ml-2"></i> سبا</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="w-5 h-5 text-orange-600 rounded">
                                <span class="mr-3 text-gray-700"><i class="fas fa-dumbbell text-orange-600 ml-2"></i> جيم</span>
                            </label>
                        </div>
                    </div>
                    
                    <button class="w-full bg-orange-600 text-white py-3 rounded-xl font-semibold hover:bg-orange-700 transition">
                        تطبيق التصفية
                    </button>
                </div>
            </div>
            
            <!-- Hotels List -->
            <div class="lg:col-span-3">
                <!-- Sort Bar -->
                <div class="bg-white rounded-xl shadow-md p-4 mb-6 flex items-center justify-between">
                    <div class="text-gray-700">
                        <span class="font-semibold">تم العثور على</span>
                        <span class="text-orange-600 font-bold">24 فندق</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-gray-600 text-sm">ترتيب حسب:</span>
                        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 text-gray-900">
                            <option>الأكثر شعبية</option>
                            <option>الأقل سعراً</option>
                            <option>الأعلى سعراً</option>
                            <option>الأعلى تقييماً</option>
                        </select>
                    </div>
                </div>
                
                <!-- Hotels Grid -->
                <div class="space-y-6">
                    @for($i = 1; $i <= 6; $i++)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-0">
                            <!-- Hotel Image -->
                            <div class="relative h-64 md:h-full min-h-[250px]">
                                <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                     alt="فندق" class="w-full h-full object-cover">
                                <div class="absolute top-4 left-4 flex gap-2">
                                    <div class="bg-white px-3 py-1 rounded-full text-sm font-bold text-gray-900 shadow-lg">
                                        <i class="fas fa-star text-yellow-500 ml-1"></i> 4.{{ 5 + $i }}
                                    </div>
                                    @if($i % 2 == 0)
                                    <div class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                        <i class="fas fa-fire ml-1"></i> خصم 30%
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Hotel Info -->
                            <div class="md:col-span-2 p-6 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <h3 class="text-2xl font-bold text-gray-900 mb-1">فندق الفخامة الدولي {{ $i }}</h3>
                                            <p class="text-gray-600 flex items-center mb-2">
                                                <i class="fas fa-map-marker-alt text-orange-600 ml-2"></i>
                                                {{ request('destination', 'الرياض') }}، المملكة العربية السعودية
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg font-semibold">
                                            <i class="fas fa-wifi ml-1"></i> واي فاي
                                        </span>
                                        <span class="px-3 py-1 bg-green-50 text-green-700 text-xs rounded-lg font-semibold">
                                            <i class="fas fa-swimming-pool ml-1"></i> مسبح
                                        </span>
                                        <span class="px-3 py-1 bg-purple-50 text-purple-700 text-xs rounded-lg font-semibold">
                                            <i class="fas fa-utensils ml-1"></i> مطعم
                                        </span>
                                        <span class="px-3 py-1 bg-orange-50 text-orange-700 text-xs rounded-lg font-semibold">
                                            <i class="fas fa-parking ml-1"></i> موقف سيارات
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center mb-4">
                                        <div class="flex text-yellow-500 text-sm ml-2">
                                            @for($j = 0; $j < 5; $j++)
                                            <i class="fas fa-star"></i>
                                            @endfor
                                        </div>
                                        <span class="text-sm text-gray-500 mr-2">({{ 100 + $i * 20 }} تقييم)</span>
                                        <span class="text-sm text-gray-500">•</span>
                                        <span class="text-sm text-orange-600 font-semibold mr-2">إلغاء مجاني</span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                    <div>
                                        <div class="flex items-baseline">
                                            <span class="text-3xl font-extrabold text-orange-600">{{ 200 + $i * 50 }}</span>
                                            <span class="text-gray-500 text-sm mr-2">ريال</span>
                                        </div>
                                        <div class="text-xs text-gray-400">/ ليلة • شامل الضرائب</div>
                                    </div>
                                    <a href="{{ route('hotel.details', ['id' => $i]) }}?check_in={{ request('check_in') }}&check_out={{ request('check_out') }}&guests={{ request('guests') }}" 
                                       class="bg-gradient-to-r from-orange-600 to-orange-600 text-white px-8 py-3 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition shadow-lg">
                                        عرض الغرف
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
                
                <!-- Pagination -->
                <div class="mt-8 flex justify-center">
                    <div class="flex gap-2">
                        <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-orange-50 hover:border-orange-600 transition">السابق</button>
                        <button class="px-4 py-2 bg-orange-600 text-white rounded-lg">1</button>
                        <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-orange-50 hover:border-orange-600 transition">2</button>
                        <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-orange-50 hover:border-orange-600 transition">3</button>
                        <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-orange-50 hover:border-orange-600 transition">التالي</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

