# NeuralGuide — دليل الشبكات العصبية الذكية

مشروع Laravel 12 عربي/RTL يساعد الباحثين والمبرمجين والطلاب على اختيار أنسب معمارية شبكة عصبية لمشكلتهم، مع تعليل علمي وأمثلة PyTorch/TensorFlow وروابط أوراق بحثية وتقدير حجم البيانات والجهد.


## التشغيل السريع
```bash
composer create-project laravel/laravel NeuralGuide "^12.0"
cd NeuralGuide
# انسخ ملفات هذا المجلد فوق مشروع Laravel الجديد
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run dev
php artisan serve
```

## دخول لوحة الإدارة
بعد seed:
- admin@neuralguide.test
- password

## ملاحظات
- الكود يستخدم Laravel 12 وPHP 8.2+.
- واجهة Blade عربية RTL مع Tailwind عبر Vite.
- نظام الترشيح Rule-based Expert System قابل للتبديل لاحقًا بخدمة LLM.
- لا يتطلب Jetstream/Filament حتى يعمل فورًا؛ يمكن إضافتهما لاحقًا.










# 1. إنشاء مشروع Laravel جديد 
composer create-project laravel/laravel NeuralGuide "^12.0"  
# 2. نسخ ملفات المشروع cd NeuralGuide 
# 3. تثبيت التبعيات 
composer install npm install  
# 4. إعداد البيئة 
cp .env.example .env php artisan key:generate  
# 5. تهيئة قاعدة البيانات 
php artisan migrate --seed  
# 6. تجميع الأصول 
npm run build   
# إنتاج # أو: 
npm run dev  
  # 7. تشغيل الخادم 
  php artisan serve 
  # افتح: 
  http://localhost:8000