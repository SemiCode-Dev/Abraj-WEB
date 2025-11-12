@extends('layouts.app')

@section('title', 'حجز الفنادق - أفضل العروض والخدمات')

@section('content')
<!-- Top Banner with Stats -->
<section class="bg-gradient-to-r from-orange-500 via-orange-600 to-blue-900 text-white py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between text-sm">
            <div class="flex items-center space-x-reverse space-x-6">
                <div class="flex items-center">
                    <i class="fas fa-users text-lg ml-2"></i>
                    <span>أكثر من <strong>2 مليون</strong> عميل سعيد</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-hotel text-lg ml-2"></i>
                    <span>أكثر من <strong>50,000</strong> فندق حول العالم</span>
                </div>
            </div>
            <div class="flex items-center space-x-reverse space-x-4 mt-2 md:mt-0">
                <a href="#" class="flex items-center hover:opacity-80 transition" title="العربية">
                    <img src="https://flagcdn.com/w20/sa.png" alt="العربية" class="w-5 h-4 rounded">
                </a>
                <span class="text-white/50">|</span>
                <a href="#" class="flex items-center hover:opacity-80 transition" title="English">
                    <img src="https://flagcdn.com/w20/gb.png" alt="English" class="w-5 h-4 rounded">
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Hero Section with Enhanced Search -->
<section id="home" class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white py-16 md:py-24 overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80')] bg-cover bg-center opacity-20"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/60"></div>
    </div>
    
    <!-- Floating Elements -->
    <div class="absolute top-20 right-20 w-72 h-72 bg-orange-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
    <div class="absolute bottom-20 left-20 w-72 h-72 bg-blue-900 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h1 class="text-5xl md:text-7xl font-extrabold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-white via-blue-100 to-cyan-200">
                ابحث عن فندقك المثالي
            </h1>
            <p class="text-xl md:text-2xl text-slate-300 mb-2">أفضل العروض والخدمات في مكان واحد</p>
            <p class="text-slate-400">احجز الآن ووفر حتى 40% على إقامتك</p>
        </div>
        
        <!-- Enhanced Search Box -->
        <div class="bg-white rounded-3xl shadow-2xl p-6 max-w-6xl mx-auto">
            <!-- Search Form -->
            <form action="{{ route('hotels.search') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <!-- Destination with Autocomplete -->
                <div class="md:col-span-2 relative">
                    <label class="block text-gray-700 text-xs font-bold mb-2 uppercase">
                        <i class="fas fa-map-marker-alt text-orange-500 ml-1"></i>
                        الوجهة / الفندق
                    </label>
                    <input type="text" name="destination" placeholder="أين تريد الذهاب؟" required
                           class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 text-lg transition">
                    <div class="absolute left-4 top-11 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                
                <!-- Check-in -->
                <div class="relative">
                    <label class="block text-gray-700 text-xs font-bold mb-2 uppercase">
                        <i class="fas fa-calendar-alt text-orange-500 ml-1"></i>
                        الوصول
                    </label>
                    <input type="date" name="check_in" required
                           class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 text-lg">
                </div>
                
                <!-- Check-out -->
                <div class="relative">
                    <label class="block text-gray-700 text-xs font-bold mb-2 uppercase">
                        <i class="fas fa-calendar-check text-orange-500 ml-1"></i>
                        المغادرة
                    </label>
                    <input type="date" name="check_out" required
                           class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 text-lg">
                </div>
                
                <!-- Guests & Rooms -->
                <div class="relative">
                    <label class="block text-gray-700 text-xs font-bold mb-2 uppercase">
                        <i class="fas fa-users text-orange-500 ml-1"></i>
                        الضيوف / الغرف
                    </label>
                    <div class="relative">
                        <select name="guests" class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 text-lg">
                            <option value="1">1 ضيف</option>
                            <option value="2" selected>2 ضيوف</option>
                            <option value="3">3 ضيوف</option>
                            <option value="4">4 ضيوف</option>
                            <option value="5">5+ ضيوف</option>
                        </select>
                    </div>
                </div>
                
                <!-- Search Button -->
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-4 rounded-xl font-bold text-lg hover:from-orange-600 hover:to-orange-700 transition transform hover:scale-105 shadow-lg flex items-center justify-center">
                        <i class="fas fa-search ml-2"></i>
                        ابحث
                    </button>
                </div>
            </form>
            
            <!-- Quick Filters -->
            <div class="px-4 pb-4 flex flex-wrap gap-2">
                <span class="text-xs text-gray-500 font-semibold">ابحث بسرعة:</span>
                <a href="#" class="px-3 py-1 bg-gray-100 hover:bg-orange-50 text-gray-700 hover:text-orange-600 rounded-full text-xs font-medium transition">
                    <i class="fas fa-fire text-orange-500 ml-1"></i> عروض اليوم
                </a>
                <a href="#" class="px-3 py-1 bg-gray-100 hover:bg-orange-50 text-gray-700 hover:text-orange-600 rounded-full text-xs font-medium transition">
                    <i class="fas fa-star text-yellow-500 ml-1"></i> فنادق 5 نجوم
                </a>
                <a href="#" class="px-3 py-1 bg-gray-100 hover:bg-orange-50 text-gray-700 hover:text-orange-600 rounded-full text-xs font-medium transition">
                    <i class="fas fa-swimming-pool text-blue-500 ml-1"></i> مع مسبح
                </a>
                <a href="#" class="px-3 py-1 bg-gray-100 hover:bg-orange-50 text-gray-700 hover:text-orange-600 rounded-full text-xs font-medium transition">
                    <i class="fas fa-wifi text-purple-500 ml-1"></i> واي فاي مجاني
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Trust Badges -->
<section class="bg-white border-b border-gray-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-center gap-8 md:gap-12">
            <div class="flex items-center text-gray-600">
                <i class="fas fa-shield-check text-2xl text-orange-600 ml-3"></i>
                <div>
                    <div class="font-bold text-sm">حجز آمن</div>
                    <div class="text-xs text-gray-500">SSL مشفر</div>
                </div>
            </div>
            <div class="flex items-center text-gray-600">
                <i class="fas fa-money-bill-wave text-2xl text-orange-600 ml-3"></i>
                <div>
                    <div class="font-bold text-sm">أفضل سعر</div>
                    <div class="text-xs text-gray-500">ضمان السعر</div>
                </div>
            </div>
            <div class="flex items-center text-gray-600">
                <i class="fas fa-headset text-2xl text-orange-600 ml-3"></i>
                <div>
                    <div class="font-bold text-sm">دعم 24/7</div>
                    <div class="text-xs text-gray-500">متاح دائماً</div>
                </div>
            </div>
            <div class="flex items-center text-gray-600">
                <i class="fas fa-undo text-2xl text-orange-600 ml-3"></i>
                <div>
                    <div class="font-bold text-sm">إلغاء مجاني</div>
                    <div class="text-xs text-gray-500">حتى 24 ساعة</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Flash Deals with Countdown -->
<section id="offers" class="py-16 bg-gradient-to-br from-orange-50 via-red-50 to-pink-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-4xl font-extrabold text-gray-900 mb-2">عروض فلاش</h2>
                <p class="text-gray-600">عروض محدودة بوقت - احجز قبل فوات الأوان!</p>
            </div>
            <div class="hidden md:flex items-center bg-white px-6 py-3 rounded-full shadow-lg">
                <i class="fas fa-clock text-red-600 ml-2"></i>
                <span class="text-sm font-bold text-gray-700">ينتهي خلال:</span>
                <span class="text-xl font-bold text-red-600 mr-3" id="countdown">23:45:12</span>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Deal 1 -->
            <div class="group relative bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="relative h-56 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1564501049412-61c2a3083791?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="عرض دبي" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute top-0 right-0 bg-gradient-to-br from-red-600 to-pink-600 text-white px-4 py-2 rounded-bl-2xl font-bold text-lg shadow-lg">
                        -40%
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                        <div class="text-white text-sm font-semibold">دبي، الإمارات</div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xl font-bold text-gray-900">فندق برج العرب</h3>
                        <div class="flex items-center bg-yellow-100 px-2 py-1 rounded-lg">
                            <i class="fas fa-star text-yellow-500 text-xs ml-1"></i>
                            <span class="text-xs font-bold text-gray-900">4.9</span>
                        </div>
                    </div>
                    <div class="flex items-center mb-4">
                        <span class="text-3xl font-extrabold text-orange-600">250</span>
                        <span class="text-gray-500 text-sm mr-2">ريال</span>
                        <span class="text-gray-400 line-through text-sm">420 ريال</span>
                    </div>
                    <div class="flex items-center text-xs text-gray-600 mb-4">
                        <i class="fas fa-map-marker-alt ml-1"></i>
                        <span>وسط المدينة • 2.5 كم من الشاطئ</span>
                    </div>
                    <a href="#" class="block w-full bg-gradient-to-r from-orange-600 to-orange-600 text-white text-center py-3 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition">
                        احجز الآن
                    </a>
                </div>
            </div>
            
            <!-- Deal 2 -->
            <div class="group relative bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="relative h-56 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="عرض الرياض" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute top-0 right-0 bg-gradient-to-br from-green-600 to-orange-600 text-white px-4 py-2 rounded-bl-2xl font-bold text-lg shadow-lg">
                        -35%
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                        <div class="text-white text-sm font-semibold">الرياض، السعودية</div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xl font-bold text-gray-900">فندق الفيصلية</h3>
                        <div class="flex items-center bg-yellow-100 px-2 py-1 rounded-lg">
                            <i class="fas fa-star text-yellow-500 text-xs ml-1"></i>
                            <span class="text-xs font-bold text-gray-900">4.8</span>
                        </div>
                    </div>
                    <div class="flex items-center mb-4">
                        <span class="text-3xl font-extrabold text-orange-600">180</span>
                        <span class="text-gray-500 text-sm mr-2">ريال</span>
                        <span class="text-gray-400 line-through text-sm">280 ريال</span>
                    </div>
                    <div class="flex items-center text-xs text-gray-600 mb-4">
                        <i class="fas fa-map-marker-alt ml-1"></i>
                        <span>الحي الدبلوماسي • قريب من المطار</span>
                    </div>
                    <a href="#" class="block w-full bg-gradient-to-r from-orange-600 to-orange-600 text-white text-center py-3 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition">
                        احجز الآن
                    </a>
                </div>
            </div>
            
            <!-- Deal 3 -->
            <div class="group relative bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="relative h-56 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="عرض جدة" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute top-0 right-0 bg-gradient-to-br from-purple-600 to-pink-600 text-white px-4 py-2 rounded-bl-2xl font-bold text-lg shadow-lg">
                        -45%
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                        <div class="text-white text-sm font-semibold">جدة، السعودية</div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xl font-bold text-gray-900">فندق الكورنيش</h3>
                        <div class="flex items-center bg-yellow-100 px-2 py-1 rounded-lg">
                            <i class="fas fa-star text-yellow-500 text-xs ml-1"></i>
                            <span class="text-xs font-bold text-gray-900">4.7</span>
                        </div>
                    </div>
                    <div class="flex items-center mb-4">
                        <span class="text-3xl font-extrabold text-orange-600">200</span>
                        <span class="text-gray-500 text-sm mr-2">ريال</span>
                        <span class="text-gray-400 line-through text-sm">365 ريال</span>
                    </div>
                    <div class="flex items-center text-xs text-gray-600 mb-4">
                        <i class="fas fa-map-marker-alt ml-1"></i>
                        <span>الكورنيش • إطلالة على البحر</span>
                    </div>
                    <a href="#" class="block w-full bg-gradient-to-r from-orange-600 to-orange-600 text-white text-center py-3 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition">
                        احجز الآن
                    </a>
                </div>
            </div>
            
            <!-- Deal 4 -->
            <div class="group relative bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="relative h-56 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="عرض أبوظبي" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute top-0 right-0 bg-gradient-to-br from-blue-600 to-cyan-600 text-white px-4 py-2 rounded-bl-2xl font-bold text-lg shadow-lg">
                        -30%
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                        <div class="text-white text-sm font-semibold">أبوظبي، الإمارات</div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xl font-bold text-gray-900">فندق الإمارات</h3>
                        <div class="flex items-center bg-yellow-100 px-2 py-1 rounded-lg">
                            <i class="fas fa-star text-yellow-500 text-xs ml-1"></i>
                            <span class="text-xs font-bold text-gray-900">4.9</span>
                        </div>
                    </div>
                    <div class="flex items-center mb-4">
                        <span class="text-3xl font-extrabold text-orange-600">320</span>
                        <span class="text-gray-500 text-sm mr-2">ريال</span>
                        <span class="text-gray-400 line-through text-sm">460 ريال</span>
                    </div>
                    <div class="flex items-center text-xs text-gray-600 mb-4">
                        <i class="fas fa-map-marker-alt ml-1"></i>
                        <span>جزيرة ياس • قريب من المتنزهات</span>
                    </div>
                    <a href="#" class="block w-full bg-gradient-to-r from-orange-600 to-orange-600 text-white text-center py-3 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition">
                        احجز الآن
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Destinations - Enhanced -->
<section id="destinations" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-extrabold text-gray-900 mb-3">الوجهات الأكثر شعبية</h2>
            <p class="text-gray-600 text-lg">اكتشف أفضل الوجهات السياحية مع أفضل الأسعار</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            <div class="group relative cursor-pointer">
                <div class="relative h-80 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1512453979798-5ea266f8880c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="مكة المكرمة" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6">
                        <h3 class="text-2xl font-bold text-white mb-1">مكة المكرمة</h3>
                        <p class="text-white/90 text-sm mb-3">150+ فندق متاح</p>
                        <div class="flex items-center text-white text-sm">
                            <span>من 120 ريال</span>
                            <i class="fas fa-arrow-left mr-2 text-xs"></i>
                        </div>
                    </div>
                    <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-white text-xs font-semibold">
                        <i class="fas fa-fire text-orange-400 ml-1"></i> رائج
                    </div>
                </div>
            </div>
            
            <div class="group relative cursor-pointer">
                <div class="relative h-80 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1582719508461-905c673771fd?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="المدينة المنورة" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6">
                        <h3 class="text-2xl font-bold text-white mb-1">المدينة المنورة</h3>
                        <p class="text-white/90 text-sm mb-3">120+ فندق متاح</p>
                        <div class="flex items-center text-white text-sm">
                            <span>من 100 ريال</span>
                            <i class="fas fa-arrow-left mr-2 text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="group relative cursor-pointer">
                <div class="relative h-80 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="الطائف" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6">
                        <h3 class="text-2xl font-bold text-white mb-1">الطائف</h3>
                        <p class="text-white/90 text-sm mb-3">80+ فندق متاح</p>
                        <div class="flex items-center text-white text-sm">
                            <span>من 150 ريال</span>
                            <i class="fas fa-arrow-left mr-2 text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="group relative cursor-pointer">
                <div class="relative h-80 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1512453979798-5ea266f8880c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="أبها" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6">
                        <h3 class="text-2xl font-bold text-white mb-1">أبها</h3>
                        <p class="text-white/90 text-sm mb-3">60+ فندق متاح</p>
                        <div class="flex items-center text-white text-sm">
                            <span>من 180 ريال</span>
                            <i class="fas fa-arrow-left mr-2 text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Hotels - Premium Design -->
<section id="hotels" class="py-16 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-12">
            <div>
                <h2 class="text-4xl font-extrabold text-gray-900 mb-2">فنادق مميزة</h2>
                <p class="text-gray-600">اختر من بين أفضل الفنادق الموصى بها</p>
            </div>
            <div class="hidden md:flex gap-2">
                <button class="px-4 py-2 bg-orange-600 text-white rounded-lg font-semibold">الكل</button>
                <button class="px-4 py-2 bg-white text-gray-700 rounded-lg font-semibold hover:bg-gray-100">5 نجوم</button>
                <button class="px-4 py-2 bg-white text-gray-700 rounded-lg font-semibold hover:bg-gray-100">4 نجوم</button>
                <button class="px-4 py-2 bg-white text-gray-700 rounded-lg font-semibold hover:bg-gray-100">3 نجوم</button>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Hotel 1 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                <div class="relative h-64 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="فندق" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute top-4 left-4 flex gap-2">
                        <div class="bg-white px-3 py-1 rounded-full text-sm font-bold text-gray-900 shadow-lg">
                            <i class="fas fa-star text-yellow-500 ml-1"></i> 4.8
                        </div>
                        <div class="bg-orange-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                            <i class="fas fa-fire ml-1"></i> شعبي
                        </div>
                    </div>
                    <div class="absolute bottom-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-lg text-xs font-semibold text-gray-900">
                        <i class="fas fa-images ml-1"></i> 24 صورة
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">فندق الفخامة الدولي</h3>
                            <p class="text-gray-600 text-sm flex items-center">
                                <i class="fas fa-map-marker-alt text-orange-600 ml-1 text-xs"></i>
                                الرياض، المملكة العربية السعودية
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg font-semibold">
                            <i class="fas fa-wifi ml-1"></i> واي فاي
                        </span>
                        <span class="px-2 py-1 bg-green-50 text-green-700 text-xs rounded-lg font-semibold">
                            <i class="fas fa-swimming-pool ml-1"></i> مسبح
                        </span>
                        <span class="px-2 py-1 bg-purple-50 text-purple-700 text-xs rounded-lg font-semibold">
                            <i class="fas fa-utensils ml-1"></i> مطعم
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex text-yellow-500 text-sm ml-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-sm text-gray-500">(245 تقييم)</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div>
                            <span class="text-3xl font-extrabold text-orange-600">350</span>
                            <span class="text-gray-500 text-sm mr-1">ريال</span>
                            <div class="text-xs text-gray-400">/ ليلة</div>
                        </div>
                        <a href="#" class="bg-gradient-to-r from-orange-600 to-orange-600 text-white px-6 py-2 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition shadow-lg">
                            احجز الآن
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Hotel 2 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                <div class="relative h-64 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="فندق" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute top-4 left-4 flex gap-2">
                        <div class="bg-white px-3 py-1 rounded-full text-sm font-bold text-gray-900 shadow-lg">
                            <i class="fas fa-star text-yellow-500 ml-1"></i> 4.9
                        </div>
                        <div class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                            <i class="fas fa-tag ml-1"></i> خصم
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">فندق الراحة والاستجمام</h3>
                            <p class="text-gray-600 text-sm flex items-center">
                                <i class="fas fa-map-marker-alt text-orange-600 ml-1 text-xs"></i>
                                جدة، المملكة العربية السعودية
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg font-semibold">
                            <i class="fas fa-wifi ml-1"></i> واي فاي
                        </span>
                        <span class="px-2 py-1 bg-green-50 text-green-700 text-xs rounded-lg font-semibold">
                            <i class="fas fa-spa ml-1"></i> سبا
                        </span>
                        <span class="px-2 py-1 bg-purple-50 text-purple-700 text-xs rounded-lg font-semibold">
                            <i class="fas fa-dumbbell ml-1"></i> جيم
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex text-yellow-500 text-sm ml-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-sm text-gray-500">(189 تقييم)</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div>
                            <span class="text-3xl font-extrabold text-orange-600">280</span>
                            <span class="text-gray-500 text-sm mr-1">ريال</span>
                            <div class="text-xs text-gray-400">/ ليلة</div>
                        </div>
                        <a href="#" class="bg-gradient-to-r from-orange-600 to-orange-600 text-white px-6 py-2 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition shadow-lg">
                            احجز الآن
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Hotel 3 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                <div class="relative h-64 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="فندق" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute top-4 left-4 flex gap-2">
                        <div class="bg-white px-3 py-1 rounded-full text-sm font-bold text-gray-900 shadow-lg">
                            <i class="fas fa-star text-yellow-500 ml-1"></i> 4.7
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">فندق الإقامة المميزة</h3>
                            <p class="text-gray-600 text-sm flex items-center">
                                <i class="fas fa-map-marker-alt text-orange-600 ml-1 text-xs"></i>
                                دبي، الإمارات العربية المتحدة
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-lg font-semibold">
                            <i class="fas fa-wifi ml-1"></i> واي فاي
                        </span>
                        <span class="px-2 py-1 bg-green-50 text-green-700 text-xs rounded-lg font-semibold">
                            <i class="fas fa-swimming-pool ml-1"></i> مسبح
                        </span>
                        <span class="px-2 py-1 bg-orange-50 text-orange-700 text-xs rounded-lg font-semibold">
                            <i class="fas fa-car ml-1"></i> موقف
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex text-yellow-500 text-sm ml-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-sm text-gray-500">(312 تقييم)</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div>
                            <span class="text-3xl font-extrabold text-orange-600">420</span>
                            <span class="text-gray-500 text-sm mr-1">ريال</span>
                            <div class="text-xs text-gray-400">/ ليلة</div>
                        </div>
                        <a href="#" class="bg-gradient-to-r from-orange-600 to-orange-600 text-white px-6 py-2 rounded-xl font-bold hover:from-orange-700 hover:to-orange-700 transition shadow-lg">
                            احجز الآن
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Customer Reviews - Enhanced -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-extrabold text-gray-900 mb-3">ماذا يقول عملاؤنا</h2>
            <p class="text-gray-600 text-lg mb-6">آراء حقيقية من عملائنا المميزين</p>
            <div class="flex items-center justify-center gap-2">
                <div class="flex text-yellow-500 text-2xl">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <span class="text-xl font-bold text-gray-900 mr-2">4.8</span>
                <span class="text-gray-600">من 5.0 بناءً على 2,458 تقييم</span>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Review 1 -->
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition">
                <div class="flex items-center mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                        أ
                    </div>
                    <div class="mr-4 flex-1">
                        <h4 class="font-bold text-gray-900 mb-1">أحمد محمد</h4>
                        <div class="flex items-center gap-2">
                            <div class="flex text-yellow-500 text-sm">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-xs text-gray-500">منذ 3 أيام</span>
                        </div>
                    </div>
                </div>
                <p class="text-gray-700 leading-relaxed mb-4">
                    "تجربة رائعة! الموقع سهل الاستخدام والعروض ممتازة. استطعت حجز فندق رائع بأسعار مناسبة جداً. الخدمة سريعة والردود فورية."
                </p>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check-circle text-orange-600 ml-2"></i>
                    <span>حجز مؤكد</span>
                </div>
            </div>
            
            <!-- Review 2 -->
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition">
                <div class="flex items-center mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-pink-500 to-pink-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                        س
                    </div>
                    <div class="mr-4 flex-1">
                        <h4 class="font-bold text-gray-900 mb-1">سارة علي</h4>
                        <div class="flex items-center gap-2">
                            <div class="flex text-yellow-500 text-sm">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-xs text-gray-500">منذ أسبوع</span>
                        </div>
                    </div>
                </div>
                <p class="text-gray-700 leading-relaxed mb-4">
                    "خدمة ممتازة ودعم فني سريع. أنصح الجميع باستخدام هذا الموقع لحجز الفنادق. الأسعار منافسة والجودة عالية."
                </p>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check-circle text-orange-600 ml-2"></i>
                    <span>حجز مؤكد</span>
                </div>
            </div>
            
            <!-- Review 3 -->
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition">
                <div class="flex items-center mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                        خ
                    </div>
                    <div class="mr-4 flex-1">
                        <h4 class="font-bold text-gray-900 mb-1">خالد أحمد</h4>
                        <div class="flex items-center gap-2">
                            <div class="flex text-yellow-500 text-sm">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-xs text-gray-500">منذ أسبوعين</span>
                        </div>
                    </div>
                </div>
                <p class="text-gray-700 leading-relaxed mb-4">
                    "عروض متنوعة وأسعار منافسة. استطعت توفير الكثير من المال بفضل العروض الحصرية. الموقع احترافي جداً."
                </p>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check-circle text-orange-600 ml-2"></i>
                    <span>حجز مؤكد</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us - Enhanced -->
<section id="about" class="py-16 bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 right-0 w-96 h-96 bg-orange-500 rounded-full mix-blend-multiply filter blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-900 rounded-full mix-blend-multiply filter blur-3xl"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-extrabold mb-4">لماذا تختارنا؟</h2>
            <p class="text-slate-300 text-lg">نوفر لك أفضل تجربة حجز مع ضمانات كاملة</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl group-hover:scale-110 transition duration-300">
                    <i class="fas fa-shield-alt text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">آمن ومضمون</h3>
                <p class="text-blue-100 leading-relaxed">حماية كاملة لبياناتك ومدفوعاتك مع تشفير SSL</p>
            </div>
            
            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl group-hover:scale-110 transition duration-300">
                    <i class="fas fa-tag text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">أفضل الأسعار</h3>
                <p class="text-blue-100 leading-relaxed">ضمان أفضل الأسعار في السوق أو استرداد الفرق</p>
            </div>
            
            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl group-hover:scale-110 transition duration-300">
                    <i class="fas fa-headset text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">دعم 24/7</h3>
                <p class="text-blue-100 leading-relaxed">فريق دعم متاح على مدار الساعة لمساعدتك</p>
            </div>
            
            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl group-hover:scale-110 transition duration-300">
                    <i class="fas fa-check-circle text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">حجز فوري</h3>
                <p class="text-blue-100 leading-relaxed">تأكيد فوري للحجوزات مع إشعارات فورية</p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    @keyframes blob {
        0% {
            transform: translate(0px, 0px) scale(1);
        }
        33% {
            transform: translate(30px, -50px) scale(1.1);
        }
        66% {
            transform: translate(-20px, 20px) scale(0.9);
        }
        100% {
            transform: translate(0px, 0px) scale(1);
        }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
</style>
@endpush

@push('scripts')
<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
    
    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Countdown timer
    function updateCountdown() {
        const now = new Date().getTime();
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        tomorrow.setHours(0, 0, 0, 0);
        const distance = tomorrow - now;
        
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        const countdownEl = document.getElementById('countdown');
        if (countdownEl) {
            countdownEl.textContent = 
                String(hours).padStart(2, '0') + ':' + 
                String(minutes).padStart(2, '0') + ':' + 
                String(seconds).padStart(2, '0');
        }
    }
    
    setInterval(updateCountdown, 1000);
    updateCountdown();
</script>
@endpush
