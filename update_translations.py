import json
import os

def update_json(file_path, updates):
    if not os.path.exists(file_path):
        print(f"File not found: {file_path}")
        return
    
    with open(file_path, 'r', encoding='utf-8') as f:
        try:
            data = json.load(f)
        except json.JSONDecodeError:
            print(f"Error decoding JSON in {file_path}")
            return
            
    data.update(updates)
    
    with open(file_path, 'w', encoding='utf-8') as f:
        json.dump(data, f, ensure_ascii=False, indent=4)
    print(f"Updated {file_path}")

en_updates = {
    "Flash Deals": "Amazing Deals",
    "Popular Destinations": "Featured Destinations",
    "Featured Hotels": "Hotels That Inspire You",
    "5,000+ Happy Customers": "5,000+ Happy Customers",
    "50,000+ Hotels Worldwide": "50,000+ Hotels Worldwide",
    "The Travelers' Destination": "The Travelers' Destination",
    "Dare to live the life you've always wanted": "Live the Experience You've Always Dreamed Of",
    "Discover how you can offset your adventure's carbon emissions and support the sustainable initiatives practiced by our operators worldwide.": "Enjoy curated travel experiences, flexible options, and trusted support every step of the way.",
    "Great 24/7 Support": "24/7 Customer Support",
    "We are here to help, before, during, and even after your trip.": "We're here for you before, during, and throughout your journey",
    "Secure Booking": "Secure Booking",
    "SSL Encrypted": "SSL Encrypted",
    "Discover the best tourist destinations with the best prices": "Discover Top Destinations at Special Rates",
    "Book Hotels - Best Offers and Services": "Your Perfect Stay Starts Here",
    "Choose from the best recommended hotels": "Handpicked Hotels & Exclusive Deals",
    "Book Now and Save up to 40%": "Save up to 40% on Your Booking",
    "Get in Touch": "We'd Love to Hear From You",
    "We'd love to hear from you. Send us a message and we'll respond as soon as possible.": "Send us a message and we'll get back to you as soon as possible",
    "Address": "Find Us",
    "Book your transfer service and enjoy comfortable transportation.": "Reliable transportation within cities and between destinations, with comfort and safety.",
    "Discover our amazing travel packages": "Carefully designed travel packages for individuals and families",
    "Apply for your visa with us and get professional assistance.": "Fast and easy visa services with full tracking until issuance",
    "Abraj Stay is a specialized platform offering premium travel options and 24/7 support.": "Abraj Stay is a specialized platform offering premium travel options and 24/7 support.",
    "We provide you with the best services for booking hotels around the world at the best prices and exclusive offers.": "Abraj Stay is a specialized platform offering premium travel options and 24/7 support."
}

ar_updates = {
    "Flash Deals": "عروض مختاره",
    "Popular Destinations": "وجهات مميزة",
    "Trending": "ترند",
    "Popular": "شائع",
    "Featured Hotels": "إقامات ممیزه",
    "5,000+ Happy Customers": "+5,000 عميل سعيد",
    "50,000+ Hotels Worldwide": "فندق حول العالم +50,000",
    "The Travelers' Destination": "وجهة المسافرين",
    "Dare to live the life you've always wanted": "عش الحياة التي طالما حلمت بها",
    "Discover how you can offset your adventure's carbon emissions and support the sustainable initiatives practiced by our operators worldwide.": "نقدم لك تجارب سفر مميزة، مع خيارات مرنة ودعم موثوق يرافقك في كل خطوة من رحلتك.",
    "Great 24/7 Support": "دعم فني على مدار الساعة",
    "We are here to help, before, during, and even after your trip.": "نحن معك دائماً، قبل رحلتك وأدناه وحتى بعد عودتك.",
    "Secure Booking": "حجز آمن",
    "SSL Encrypted": "SSL مشفر",
    "Discover the best tourist destinations with the best prices": "اكتشف أشهر الوجهات السياحية بأسعار مميزة",
    "Book Hotels - Best Offers and Services": "إقامتك المثالية تبدأ من هنا",
    "Choose from the best recommended hotels": "أفضل الفنادق والعروض الحصرية",
    "Book Now and Save up to 40%": "احصل على خصومات تصل حتى 40%",
    "Get in Touch": "نود أن نسمع منك",
    "We'd love to hear from you. Send us a message and we'll respond as soon as possible.": "تواصل معنا الآن وسنقوم بمساعدتك في التخطيط في أسرع وقت",
    "Address": "تجدنا هنا",
    "Book your transfer service and enjoy comfortable transportation.": "خدمات نقل موثوقة داخل المدن وبين الوجهات، لتستمتع براحة وأمان.",
    "Discover our amazing travel packages": "باقات سياحية مصممة بعناية لتناسب الأفراد والعائلات",
    "Apply for your visa with us and get professional assistance.": "خدمة إصدار التأشيرة بسرعة وسهولة مع متابعة حتى الاستلام",
    "We provide you with the best services for booking hotels around the world at the best prices and exclusive offers.": "نقدم لك تجربة متميزة بخيارات متنوعة ودعم على مدار الساعة",
    "Why Choose Us": "نحن شركة رائدة متخصصة في الخدمات السياحية وحجوزات الفنادق"
}

update_json('c:/xampp/htdocs/Projects/abraj/Abraj-WEB/lang/en.json', en_updates)
update_json('c:/xampp/htdocs/Projects/abraj/Abraj-WEB/lang/ar.json', ar_updates)
