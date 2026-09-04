{{-- resources/views/admin/architectures/form.blade.php (يمكن استخدامه في create و edit) --}}
@extends('layouts.admin')
@section('title', ($architecture->exists ? 'تعديل' : 'إضافة') . ' معمارية')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.architectures.index') }}" class="text-slate-400 hover:text-white transition">
            <i class="fa-solid fa-arrow-right-long text-xl"></i>
        </a>
        <h2 class="text-2xl font-black text-white">
            <i class="fa-solid fa-microchip text-cyan-400 ml-2"></i>
            {{ $architecture->exists ? 'تعديل' : 'إضافة' }} معمارية
        </h2>
    </div>
</div>

{{-- عرض أخطاء التحقق --}}
@if ($errors->any())
<div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl mb-6 flex items-start gap-3">
    <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
    <div>
        <p class="font-bold mb-1">يوجد أخطاء في النموذج:</p>
        <ul class="list-disc list-inside text-sm space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<form method="POST" 
      action="{{ $architecture->exists ? route('admin.architectures.update', $architecture) : route('admin.architectures.store') }}" 
      class="space-y-6">
    @csrf
    @if($architecture->exists) @method('PUT') @endif

    {{-- 1. المعلومات الأساسية --}}
    <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-6 space-y-5">
        <h3 class="text-lg font-bold text-white border-b border-white/10 pb-3 flex items-center gap-2">
            <i class="fa-solid fa-circle-info text-cyan-400"></i> المعلومات الأساسية
        </h3>

        <div class="grid md:grid-cols-2 gap-5">
            {{-- الاسم --}}
            <div>
                <label class="block text-slate-300 text-sm font-medium mb-2">
                    <i class="fa-solid fa-signature text-cyan-400 ml-1"></i> اسم المعمارية
                </label>
                <input name="name" value="{{ old('name', $architecture->name) }}" 
                       placeholder="مثال: Transformer" 
                       class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 outline-none transition">
            </div>

            {{-- السنة --}}
            <div>
                <label class="block text-slate-300 text-sm font-medium mb-2">
                    <i class="fa-solid fa-calendar text-cyan-400 ml-1"></i> سنة الإصدار
                </label>
                <input type="number" name="year" value="{{ old('year', $architecture->year) }}" 
                       placeholder="2024" 
                       class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 outline-none transition">
            </div>
        </div>

        {{-- الوصف القصير --}}
        <div>
            <label class="block text-slate-300 text-sm font-medium mb-2">
                <i class="fa-solid fa-quote-right text-cyan-400 ml-1"></i> وصف قصير (يظهر في البطاقات)
            </label>
            <textarea name="short_description" rows="2" 
                      placeholder="وصف مختصر جداً للمعمارية..." 
                      class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 outline-none transition resize-none">{{ old('short_description', $architecture->short_description) }}</textarea>
        </div>

        <div class="grid md:grid-cols-2 gap-5">
            {{-- مستوى الصعوبة --}}
            <div>
                <label class="block text-slate-300 text-sm font-medium mb-2">
                    <i class="fa-solid fa-chart-line text-cyan-400 ml-1"></i> مستوى الصعوبة
                </label>
                <select name="difficulty" 
                        class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 outline-none transition appearance-none">
                    @foreach(['beginner' => 'مبتدئ', 'intermediate' => 'متوسط', 'advanced' => 'متقدم', 'research' => 'بحثي'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('difficulty', $architecture->difficulty) == $value)>
                            {{ $label }} ({{ $value }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- حالة النشر --}}
            <div>
                <label class="block text-slate-300 text-sm font-medium mb-2">
                    <i class="fa-solid fa-globe text-cyan-400 ml-1"></i> حالة النشر
                </label>
                <label class="flex items-center gap-3 bg-slate-900/50 border border-white/10 rounded-xl px-4 py-2.5 cursor-pointer hover:bg-slate-900 transition">
                    <input type="checkbox" name="is_published" value="1" 
                           @checked(old('is_published', $architecture->is_published)) 
                           class="w-5 h-5 rounded accent-cyan-500 cursor-pointer">
                    <span class="text-white">نشر المعمارية في الموقع العام</span>
                </label>
            </div>
        </div>
    </div>

    {{-- 2. الوصف العلمي والتفاصيل التقنية --}}
    <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-6 space-y-5">
        <h3 class="text-lg font-bold text-white border-b border-white/10 pb-3 flex items-center gap-2">
            <i class="fa-solid fa-book-open text-cyan-400"></i> الوصف العلمي والتفاصيل التقنية
        </h3>

        {{-- الوصف الكامل --}}
        <div>
            <label class="block text-slate-300 text-sm font-medium mb-2">الوصف العلمي الكامل</label>
            <textarea name="description" rows="5" 
                      placeholder="شرح تفصيلي لآلية عمل المعمارية..." 
                      class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 outline-none transition">{{ old('description', $architecture->description) }}</textarea>
        </div>

        <div class="grid md:grid-cols-3 gap-5">
            {{-- مناسب لـ --}}
            <div>
                <label class="block text-slate-300 text-sm font-medium mb-2">
                    <i class="fa-solid fa-bullseye text-cyan-400 ml-1"></i> مناسب لـ
                </label>
                <textarea name="best_for" rows="3" 
                          placeholder="أنواع المهام المناسبة..." 
                          class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 outline-none transition resize-none">{{ old('best_for', $architecture->best_for) }}</textarea>
            </div>

            {{-- القيود --}}
            <div>
                <label class="block text-slate-300 text-sm font-medium mb-2">
                    <i class="fa-solid fa-triangle-exclamation text-cyan-400 ml-1"></i> القيود
                </label>
                <textarea name="limitations" rows="3" 
                          placeholder="المحددات ونقاط الضعف..." 
                          class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 outline-none transition resize-none">{{ old('limitations', $architecture->limitations) }}</textarea>
            </div>

            {{-- الإعدادات --}}
            <div>
                <label class="block text-slate-300 text-sm font-medium mb-2">
                    <i class="fa-solid fa-sliders text-cyan-400 ml-1"></i> الإعدادات المُوصى بها
                </label>
                <textarea name="recommended_settings" rows="3" 
                          placeholder="Hyperparameters موصى بها..." 
                          class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 outline-none transition resize-none">{{ old('recommended_settings', $architecture->recommended_settings) }}</textarea>
            </div>
        </div>
    </div>

    {{-- 3. الأمثلة البرمجية --}}
    <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-6 space-y-5">
        <h3 class="text-lg font-bold text-white border-b border-white/10 pb-3 flex items-center gap-2">
            <i class="fa-solid fa-code text-cyan-400"></i> الأمثلة البرمجية
        </h3>

        <div class="grid md:grid-cols-2 gap-5">
            <div>
                <label class="block text-slate-300 text-sm font-medium mb-2">
                    <i class="fa-brands fa-python text-orange-400 ml-1"></i> مثال PyTorch
                </label>
                <textarea name="pytorch_example" rows="6" dir="ltr"
                          placeholder="import torch..." 
                          class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-2.5 text-emerald-400 font-mono text-sm placeholder:text-slate-500 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 outline-none transition">{{ old('pytorch_example', $architecture->pytorch_example) }}</textarea>
            </div>

            <div>
                <label class="block text-slate-300 text-sm font-medium mb-2">
                    <i class="fa-solid fa-brain text-yellow-400 ml-1"></i> مثال TensorFlow
                </label>
                <textarea name="tensorflow_example" rows="6" dir="ltr"
                          placeholder="import tensorflow as tf..." 
                          class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-2.5 text-emerald-400 font-mono text-sm placeholder:text-slate-500 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 outline-none transition">{{ old('tensorflow_example', $architecture->tensorflow_example) }}</textarea>
            </div>
        </div>
    </div>

    {{-- 4. المراجع والوسوم --}}
    <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-6 space-y-5">
        <h3 class="text-lg font-bold text-white border-b border-white/10 pb-3 flex items-center gap-2">
            <i class="fa-solid fa-link text-cyan-400"></i> المراجع والوسوم
        </h3>

        <div class="grid md:grid-cols-2 gap-5">
            {{-- عنوان الورقة --}}
            <div>
                <label class="block text-slate-300 text-sm font-medium mb-2">
                    <i class="fa-solid fa-file-lines text-cyan-400 ml-1"></i> عنوان الورقة البحثية
                </label>
                <input name="paper_title" value="{{ old('paper_title', $architecture->paper_title) }}" 
                       placeholder="Attention Is All You Need" 
                       class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 outline-none transition">
            </div>

            {{-- arXiv --}}
            <div>
                <label class="block text-slate-300 text-sm font-medium mb-2">
                    <i class="fa-solid fa-arrow-up-right-from-square text-cyan-400 ml-1"></i> رابط arXiv
                </label>
                <input name="arxiv_url" value="{{ old('arxiv_url', $architecture->arxiv_url) }}" dir="ltr"
                       placeholder="https://arxiv.org/..." 
                       class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 outline-none transition">
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-5">
            {{-- Frameworks --}}
            <div>
                <label class="block text-slate-300 text-sm font-medium mb-2">
                    <i class="fa-solid fa-toolbox text-cyan-400 ml-1"></i> أطر العمل (Frameworks)
                </label>
                <input name="frameworks" 
                       value="{{ old('frameworks', implode(',', $architecture->frameworks ?? [])) }}" 
                       placeholder="PyTorch, TensorFlow, JAX (مفصولة بفواصل)" 
                       class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 outline-none transition">
            </div>

            {{-- Tags --}}
            <div>
                <label class="block text-slate-300 text-sm font-medium mb-2">
                    <i class="fa-solid fa-tags text-cyan-400 ml-1"></i> الوسوم (Tags)
                </label>
                <input name="tags" 
                       value="{{ old('tags', implode(',', $architecture->tags ?? [])) }}" 
                       placeholder="NLP, Vision, Audio (مفصولة بفواصل)" 
                       class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 outline-none transition">
            </div>
        </div>
    </div>

    {{-- 5. اختيار الفئات --}}
    <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-6 space-y-5">
        <h3 class="text-lg font-bold text-white border-b border-white/10 pb-3 flex items-center gap-2">
            <i class="fa-solid fa-folder-tree text-cyan-400"></i> اختيار الفئات
        </h3>

        @if(isset($categories) && $categories->count())
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($categories as $c)
            <label class="flex items-center gap-3 bg-slate-900/50 border border-white/10 rounded-xl px-4 py-3 cursor-pointer hover:bg-slate-900 hover:border-cyan-400/50 transition group">
                <input type="checkbox" name="category_ids[]" value="{{ $c->id }}" 
                       @checked(in_array($c->id, old('category_ids', $architecture->categories?->pluck('id')->all() ?? []))) 
                       class="w-5 h-5 rounded accent-cyan-500 cursor-pointer">
                <div class="flex-1">
                    <span class="text-white font-medium flex items-center gap-2">
                        @if($c->icon)
                            <i class="fa-solid fa-{{ $c->icon }} text-cyan-400 group-hover:text-cyan-300"></i>
                        @endif
                        {{ $c->name }}
                    </span>
                    <span class="text-xs text-slate-500 font-mono">{{ $c->slug }}</span>
                </div>
            </label>
            @endforeach
        </div>
        @else
        <p class="text-slate-400 text-sm text-center py-6">
            <i class="fa-solid fa-folder-open ml-2"></i>
            لا توجد فئات. 
            <a href="{{ route('admin.categories.create') }}" class="text-cyan-400 hover:text-cyan-300 font-medium">أضف فئة جديدة</a>
        </p>
        @endif
    </div>

    {{-- أزرار التحكم --}}
    <div class="flex items-center justify-between bg-slate-800/50 border border-white/10 rounded-2xl p-5">
        <a href="{{ route('admin.architectures.index') }}" 
           class="text-slate-400 hover:text-white transition flex items-center gap-2">
            <i class="fa-solid fa-arrow-right-long"></i> إلغاء والعودة
        </a>
        <button type="submit" 
                class="bg-cyan-600 hover:bg-cyan-500 px-8 py-3 rounded-xl font-bold text-sm text-white transition flex items-center gap-2 shadow-lg shadow-cyan-500/20">
            <i class="fa-solid fa-floppy-disk"></i> 
            {{ $architecture->exists ? 'حفظ التعديلات' : 'إضافة المعمارية' }}
        </button>
    </div>
</form>
@endsection