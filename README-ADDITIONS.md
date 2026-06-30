# NeuralGuide — ملفات الاستكمال والإضافات

## الملفات الجديدة والمستكملة في هذه الحزمة

### 📁 هيكل الحزمة
```
NeuralGuide-Complete/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/
│   │   │   ├── AdminDashboardController.php   ← لوحة تحكم إدارية كاملة
│   │   │   ├── CategoryAdminController.php    ← CRUD الفئات
│   │   │   └── UserAdminController.php        ← CRUD المستخدمين
│   │   └── Training/
│   │       ├── TrainingExperimentController.php  ← ✨ جديد: تجارب التدريب
│   │       └── DatasetController.php             ← ✨ جديد: رفع البيانات
│   ├── Models/
│   │   ├── Category.php
│   │   ├── TrainingExperiment.php   ← ✨ نموذج التجارب
│   │   ├── TrainingDataset.php      ← ✨ نموذج مجموعات البيانات
│   │   └── TrainingRun.php          ← ✨ سجل التشغيل
│   ├── Policies/
│   │   ├── TrainingExperimentPolicy.php
│   │   └── TrainingDatasetPolicy.php
│   └── Services/
│       └── PythonCodeGeneratorService.php  ← ✨ مولّد كود Python
├── database/migrations/
│   ├── 2025_01_01_000002_create_categories_table.php
│   ├── 2025_01_01_000010_create_training_datasets_table.php
│   ├── 2025_01_01_000011_create_training_experiments_table.php
│   └── 2025_01_01_000012_create_training_runs_table.php
├── resources/views/
│   ├── layouts/
│   │   └── admin.blade.php         ← Layout مخصص للإدارة
│   ├── admin/
│   │   ├── dashboard/index.blade.php
│   │   ├── categories/{index,form}.blade.php
│   │   └── users/{index,edit}.blade.php
│   └── training/
│       ├── index.blade.php         ← قائمة التجارب
│       ├── create.blade.php        ← إنشاء تجربة
│       ├── show.blade.php          ← عرض الكود + محرر
│       ├── edit.blade.php
│       └── datasets/{index,create,show}.blade.php
└── routes/web.php                  ← مسارات كاملة ومحدّثة
```

---

## 🚀 تعليمات التثبيت

### 1. انسخ الملفات
```bash
# من جذر مشروع NeuralGuide الموجود
cp -r NeuralGuide-Complete/app/* app/
cp -r NeuralGuide-Complete/database/migrations/* database/migrations/
cp -r NeuralGuide-Complete/resources/views/* resources/views/
cp NeuralGuide-Complete/routes/web.php routes/web.php
```

### 2. تشغيل الـ Migrations
```bash
php artisan migrate
```

### 3. تسجيل الـ Policies في AppServiceProvider
```php
// app/Providers/AppServiceProvider.php
use App\Models\TrainingExperiment;
use App\Models\TrainingDataset;
use App\Policies\TrainingExperimentPolicy;
use App\Policies\TrainingDatasetPolicy;

public function boot(): void
{
    Gate::policy(TrainingExperiment::class, TrainingExperimentPolicy::class);
    Gate::policy(TrainingDataset::class, TrainingDatasetPolicy::class);
}
```

### 4. تسجيل الـ Service في AppServiceProvider (اختياري)
```php
$this->app->singleton(PythonCodeGeneratorService::class);
```

---

## ✨ الميزات الجديدة: منصة تدريب الشبكات العصبية

### للمستخدم (/training)
| الرابط | الوظيفة |
|--------|---------|
| `/training` | قائمة تجارب التدريب |
| `/training/create` | إنشاء تجربة جديدة مع اختيار المعمارية |
| `/training/{id}` | عرض التجربة + الكود المُولَّد + محرر |
| `/training/{id}/download` | تحميل ملف `.py` |
| `/training/datasets` | مجموعات البيانات |
| `/training/datasets/create` | رفع مجموعة بيانات (100MB) |

### للإدارة (/admin)
| الرابط | الوظيفة |
|--------|---------|
| `/admin` | لوحة تحكم شاملة مع إحصاءات |
| `/admin/architectures` | CRUD كامل للمعماريات |
| `/admin/categories` | CRUD الفئات |
| `/admin/users` | عرض وتعديل وحذف المستخدمين |

---

## 🔄 سير العمل الكامل (مثل Kaggle)

```
1. المستخدم يصف مشكلته → NeuralGuide يقترح معمارية
2. ينقر "ابدأ تجربة تدريب" من صفحة المعمارية
3. يرفع بياناته (CSV / ZIP / JSON)
4. يضبط: Epochs, Batch Size, LR, Optimizer
5. النظام يُولّد كود Python كامل وجاهز للتشغيل
6. يُعدّل الكود في المحرر المدمج إذا أراد
7. يحمّل ملف .py ويشغّله محلياً أو في Colab
8. يحفظ ملاحظاته ونتائجه في النظام
```
