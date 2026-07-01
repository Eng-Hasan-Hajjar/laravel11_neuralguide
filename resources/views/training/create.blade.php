@extends('layouts.app')
@section('title', __('messages.new_experiment'))

@section('content')
<div class="mx-auto max-w-3xl px-4 py-10 sm:px-6">
    <a href="{{ route('training.index') }}" class="mb-4 flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white">
        <i class="fa-solid fa-arrow-right"></i> {{ __('messages.training') }}
    </a>
    <h1 class="mb-1 text-3xl font-black">🧪 {{ __('messages.new_experiment') }}</h1>
    <p class="mb-8 text-sm text-slate-500 dark:text-slate-400">اضبط الإعدادات وسيُولَّد كود Python تلقائياً</p>

    <form action="{{ route('training.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- الاسم --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <h2 class="mb-4 flex items-center gap-2 font-black text-cyan-600 dark:text-cyan-400">
                <i class="fa-solid fa-tag"></i> معلومات التجربة
            </h2>
            <div>
                <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">اسم التجربة <span class="text-red-500">*</span></label>
                <input name="name" value="{{ old('name') }}" required
                       placeholder="مثال: تدريب ResNet على CIFAR-10"
                       class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/20 dark:border-white/10 dark:bg-white/5 dark:focus:bg-white/10">
            </div>
        </div>

        {{-- المعمارية والبيانات --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <h2 class="mb-4 flex items-center gap-2 font-black text-purple-600 dark:text-purple-400">
                <i class="fa-solid fa-brain"></i> المعمارية ومجموعة البيانات
            </h2>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">المعمارية <span class="text-red-500">*</span></label>
                    <select name="architecture_id" required
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/20 dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:bg-white/10">
                        <option value="" class="dark:bg-slate-900">اختر معمارية...</option>
                        @foreach($architectures as $arch)
                        <option value="{{ $arch->id }}" class="dark:bg-slate-900" @selected(old('architecture_id', $selected?->id) == $arch->id)>
                            {{ $arch->name }} ({{ __('messages.difficulty_' . $arch->difficulty) }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">مجموعة البيانات (اختياري)</label>
                    <select name="dataset_id"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/20 dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:bg-white/10">
                        <option value="" class="dark:bg-slate-900">بدون مجموعة بيانات</option>
                        @foreach($datasets as $ds)
                        <option value="{{ $ds->id }}" class="dark:bg-slate-900" @selected(old('dataset_id') == $ds->id)>
                            {{ $ds->name }} ({{ $ds->formattedSize() }})
                        </option>
                        @endforeach
                    </select>
                    <a href="{{ route('training.datasets.create') }}" class="mt-1.5 inline-block text-xs font-bold text-cyan-600 hover:underline dark:text-cyan-400">
                        + رفع مجموعة بيانات جديدة
                    </a>
                </div>
            </div>
        </div>

        {{-- Hyperparameters --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <h2 class="mb-4 flex items-center gap-2 font-black text-amber-600 dark:text-amber-400">
                <i class="fa-solid fa-sliders"></i> Hyperparameters
            </h2>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">إطار العمل</label>
                    <select name="framework" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:outline-none dark:border-white/10 dark:bg-white/5 dark:text-white">
                        <option value="pytorch"    class="dark:bg-slate-900" @selected(old('framework')=='pytorch')>🔥 PyTorch</option>
                        <option value="tensorflow" class="dark:bg-slate-900" @selected(old('framework')=='tensorflow')>🌊 TensorFlow / Keras</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">Optimizer</label>
                    <select name="optimizer" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:outline-none dark:border-white/10 dark:bg-white/5 dark:text-white">
                        <option value="adam"    class="dark:bg-slate-900" @selected(old('optimizer','adam')=='adam')>Adam</option>
                        <option value="adamw"   class="dark:bg-slate-900" @selected(old('optimizer')=='adamw')>AdamW</option>
                        <option value="sgd"     class="dark:bg-slate-900" @selected(old('optimizer')=='sgd')>SGD</option>
                        <option value="rmsprop" class="dark:bg-slate-900" @selected(old('optimizer')=='rmsprop')>RMSprop</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">Epochs</label>
                    <input type="number" name="epochs" value="{{ old('epochs', 20) }}" min="1" max="1000"
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:outline-none dark:border-white/10 dark:bg-white/5">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">Batch Size</label>
                    <input type="number" name="batch_size" value="{{ old('batch_size', 32) }}" min="1" max="2048"
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:outline-none dark:border-white/10 dark:bg-white/5">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">Learning Rate</label>
                    <input type="number" name="learning_rate" value="{{ old('learning_rate', 0.001) }}" step="0.0001" min="0.000001"
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:outline-none dark:border-white/10 dark:bg-white/5">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">Loss Function</label>
                    <select name="loss_function" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:outline-none dark:border-white/10 dark:bg-white/5 dark:text-white">
                        <option value="cross_entropy" class="dark:bg-slate-900" @selected(old('loss_function','cross_entropy')=='cross_entropy')>Cross Entropy</option>
                        <option value="mse" class="dark:bg-slate-900" @selected(old('loss_function')=='mse')>MSE</option>
                        <option value="bce" class="dark:bg-slate-900" @selected(old('loss_function')=='bce')>BCE</option>
                        <option value="nll" class="dark:bg-slate-900" @selected(old('loss_function')=='nll')>NLL</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- ملاحظات --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <h2 class="mb-4 flex items-center gap-2 font-black text-emerald-600 dark:text-emerald-400">
                <i class="fa-solid fa-note-sticky"></i> ملاحظات (اختياري)
            </h2>
            <textarea name="notes" rows="3" placeholder="أهداف التجربة، الفرضيات، ملاحظات..."
                      class="w-full resize-none rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:outline-none dark:border-white/10 dark:bg-white/5">{{ old('notes') }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="flex flex-1 items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-cyan-600 to-blue-600 py-3.5 font-black text-white shadow-lg shadow-cyan-500/25 hover:from-cyan-500 hover:to-blue-500 transition-all">
                <i class="fa-solid fa-wand-magic-sparkles"></i> إنشاء وتوليد الكود
            </button>
            <a href="{{ route('training.index') }}"
               class="rounded-2xl border border-slate-200 bg-white px-6 py-3.5 font-bold text-slate-600 hover:bg-slate-50 dark:border-white/10 dark:bg-white/5 dark:text-slate-300 dark:hover:bg-white/10 transition-colors">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
