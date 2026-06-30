@extends('layouts.app')
@section('title', 'تجربة تدريب جديدة')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('training.index') }}" class="text-slate-400 hover:text-white text-sm flex items-center gap-2 mb-4">
            <i class="fa-solid fa-arrow-right"></i> العودة للتجارب
        </a>
        <h1 class="text-3xl font-black">🧪 تجربة تدريب جديدة</h1>
        <p class="text-slate-400 mt-1 text-sm">اضبط الإعدادات وسيُولَّد كود Python تلقائياً</p>
    </div>

    <form action="{{ route('training.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- الاسم --}}
        <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-6">
            <h2 class="font-bold mb-4 text-cyan-400 flex items-center gap-2">
                <i class="fa-solid fa-tag"></i> معلومات التجربة
            </h2>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">اسم التجربة <span class="text-red-400">*</span></label>
                <input name="name" value="{{ old('name') }}" required
                       placeholder="مثال: تدريب ResNet على CIFAR-10"
                       class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500">
            </div>
        </div>

        {{-- المعمارية والبيانات --}}
        <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-6">
            <h2 class="font-bold mb-4 text-purple-400 flex items-center gap-2">
                <i class="fa-solid fa-brain"></i> المعمارية ومجموعة البيانات
            </h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">المعمارية <span class="text-red-400">*</span></label>
                    <select name="architecture_id" required
                            class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500">
                        <option value="">اختر معمارية...</option>
                        @foreach($architectures as $arch)
                        <option value="{{ $arch->id }}" @selected(old('architecture_id', $selected?->id) == $arch->id)>
                            {{ $arch->name }} ({{ $arch->difficulty }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">مجموعة البيانات (اختياري)</label>
                    <select name="dataset_id"
                            class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500">
                        <option value="">بدون مجموعة بيانات</option>
                        @foreach($datasets as $ds)
                        <option value="{{ $ds->id }}" @selected(old('dataset_id') == $ds->id)>
                            {{ $ds->name }} ({{ $ds->formattedSize() }})
                        </option>
                        @endforeach
                    </select>
                    <a href="{{ route('training.datasets.create') }}" class="text-xs text-cyan-400 hover:underline mt-1 inline-block">
                        + رفع مجموعة بيانات جديدة
                    </a>
                </div>
            </div>
        </div>

        {{-- Hyperparameters --}}
        <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-6">
            <h2 class="font-bold mb-4 text-amber-400 flex items-center gap-2">
                <i class="fa-solid fa-sliders"></i> Hyperparameters
            </h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">إطار العمل</label>
                    <select name="framework" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500">
                        <option value="pytorch"     @selected(old('framework')=='pytorch')>🔥 PyTorch</option>
                        <option value="tensorflow"  @selected(old('framework')=='tensorflow')>🌊 TensorFlow / Keras</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Optimizer</label>
                    <select name="optimizer" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500">
                        <option value="adam"  @selected(old('optimizer','adam')=='adam')>Adam</option>
                        <option value="adamw" @selected(old('optimizer')=='adamw')>AdamW</option>
                        <option value="sgd"   @selected(old('optimizer')=='sgd')>SGD</option>
                        <option value="rmsprop" @selected(old('optimizer')=='rmsprop')>RMSprop</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Epochs</label>
                    <input type="number" name="epochs" value="{{ old('epochs', 20) }}" min="1" max="1000"
                           class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Batch Size</label>
                    <input type="number" name="batch_size" value="{{ old('batch_size', 32) }}" min="1" max="2048"
                           class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Learning Rate</label>
                    <input type="number" name="learning_rate" value="{{ old('learning_rate', 0.001) }}" step="0.0001" min="0.000001"
                           class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Loss Function</label>
                    <select name="loss_function" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500">
                        <option value="cross_entropy" @selected(old('loss_function','cross_entropy')=='cross_entropy')>Cross Entropy</option>
                        <option value="mse"     @selected(old('loss_function')=='mse')>MSE</option>
                        <option value="bce"     @selected(old('loss_function')=='bce')>BCE</option>
                        <option value="nll"     @selected(old('loss_function')=='nll')>NLL</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- ملاحظات --}}
        <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-6">
            <h2 class="font-bold mb-4 text-emerald-400 flex items-center gap-2">
                <i class="fa-solid fa-note-sticky"></i> ملاحظات (اختياري)
            </h2>
            <textarea name="notes" rows="3" placeholder="أهداف التجربة، الفرضيات، ملاحظات..."
                      class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500 resize-none">{{ old('notes') }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="flex-1 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 py-3 rounded-xl font-black transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-wand-magic-sparkles"></i> إنشاء وتوليد الكود
            </button>
            <a href="{{ route('training.index') }}"
               class="bg-slate-700 hover:bg-slate-600 px-6 py-3 rounded-xl font-bold transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
