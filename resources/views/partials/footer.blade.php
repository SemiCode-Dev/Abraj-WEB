<!-- Footer -->
<footer class="bg-gray-50 border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- About -->
            <div>
                <div class="mb-4">
                    @if(file_exists(public_path('images/abraj-stay-logo.png')))
                        <img src="{{ asset('images/abraj-stay-logo.png') }}" alt="ABRAJ STAY" class="h-10 w-auto">
                    @else
                        <h3 class="text-xl font-bold">
                            <span class="text-orange-500">A</span><span class="text-blue-900">BRAJ</span> <span class="text-orange-500">STAY</span>
                        </h3>
                    @endif
                </div>
                <p class="text-gray-600 text-sm leading-relaxed">
                    نوفر لك أفضل الخدمات لحجز الفنادق في جميع أنحاء العالم بأفضل الأسعار والعروض الحصرية.
                </p>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h4 class="font-semibold mb-4 text-center text-gray-900">روابط سريعة</h4>
                <ul class="space-y-2 text-sm text-gray-600 text-center">
                    <li><a href="#" class="hover:text-orange-500 transition">حجز فندق</a></li>
                    <li><a href="#" class="hover:text-orange-500 transition">العروض الخاصة</a></li>
                    <li><a href="#" class="hover:text-orange-500 transition">الوجهات الشعبية</a></li>
                    <li><a href="#" class="hover:text-orange-500 transition">دليل السفر</a></li>
                </ul>
            </div>
            
            <!-- Support -->
            <div>
                <h4 class="font-semibold mb-4 text-center text-gray-900">الدعم</h4>
                <ul class="space-y-2 text-sm text-gray-600 text-center">
                    <li><a href="#" class="hover:text-orange-500 transition">الأسئلة الشائعة</a></li>
                    <li><a href="#" class="hover:text-orange-500 transition">اتصل بنا</a></li>
                    <li><a href="#" class="hover:text-orange-500 transition">سياسة الخصوصية</a></li>
                    <li><a href="#" class="hover:text-orange-500 transition">شروط الاستخدام</a></li>
                </ul>
            </div>
            
            <!-- Social Media -->
            <div>
                <h4 class="font-semibold mb-4 text-center text-gray-900">تابعنا</h4>
                <div class="flex space-x-reverse space-x-4 justify-center">
                    <a href="#" class="w-10 h-10 bg-white border border-gray-300 rounded-full flex items-center justify-center hover:bg-orange-500 hover:border-orange-500 hover:text-white text-gray-700 transition">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white border border-gray-300 rounded-full flex items-center justify-center hover:bg-orange-500 hover:border-orange-500 hover:text-white text-gray-700 transition">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white border border-gray-300 rounded-full flex items-center justify-center hover:bg-orange-500 hover:border-orange-500 hover:text-white text-gray-700 transition">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white border border-gray-300 rounded-full flex items-center justify-center hover:bg-orange-500 hover:border-orange-500 hover:text-white text-gray-700 transition">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="border-t border-gray-300 mt-8 pt-8 text-center text-sm text-gray-600">
            <div class="flex items-center justify-center mb-2">
                @if(file_exists(public_path('images/abraj-stay-logo.png')))
                    <img src="{{ asset('images/abraj-stay-logo.png') }}" alt="ABRAJ STAY" class="h-8 w-auto">
                @else
                    <span class="font-bold">
                        <span class="text-orange-500">A</span><span class="text-blue-900">BRAJ</span> <span class="text-orange-500">STAY</span>
                    </span>
                @endif
            </div>
            <p>&copy; {{ date('Y') }} ABRAJ STAY. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</footer>

