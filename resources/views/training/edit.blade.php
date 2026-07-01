@extends('layouts.app')
@section('title', 'تعديل التجربة')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-10 sm:px-6">
    <a href="{{ route('training.show', $experiment) }}" class="mb-4 flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white">
        <i class="fa-solid fa-arrow-right"></i> العودة للتجربة
    </a>
    <h1 class="mb-8 text-3xl font-black">✏️ تعديل: {{ $experiment->name }}</h1>

    <form action="{{ route('training.update', $experiment) }}" method="POST" class="space-y-6">
        @csrf @method('PUT')

        <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <h2 class="mb-4 font-black text-cyan-600 dark:text-cyan-400">معلومات التجربة</h2>
            <div>
                <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">الاسم</label>
                <input name="name" value="{{ old('name', $experiment->name) }}" required
                       class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:outline-none dark:border-white/10 dark:bg-white/5">
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <h2 class="mb-4 font-black text-amber-600 dark:text-amber-400">Hyperparameters</h2>
            @php $hp = $experiment->hyperparameters ?? []; @endphp
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">Framework</label>
                    <select name="framework" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:outline-none dark:border-white/10 dark:bg-white/5 dark:text-white">
                        <option value="pytorch"    class="dark:bg-slate-900" @selected(old('framework',$experiment->framework)==='pytorch')>🔥 PyTorch</option>
                        <option value="tensorflow" class="dark:bg-slate-900" @selected(old('framework',$experiment->framework)==='tensorflow')>🌊 TensorFlow</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">Optimizer</label>
                    <select name="optimizer" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:outline-none dark:border-white/10 dark:bg-white/5 dark:text-white">
                        @foreach(['adam','adamw','sgd','rmsprop'] as $opt)
                        <option value="{{ $opt }}" class="dark:bg-slate-900" @selected(old('optimizer',$hp['optimizer']??'adam')===$opt)>{{ strtoupper($opt) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">Epochs</label>
                    <input type="number" name="epochs" value="{{ old('epochs',$hp['epochs']??20) }}"
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:outline-none dark:border-white/10 dark:bg-white/5">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">Batch Size</label>
                    <input type="number" name="batch_size" value="{{ old('batch_size',$hp['batch_size']??32) }}"
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:outline-none dark:border-white/10 dark:bg-white/5">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">Learning Rate</label>
                    <input type="number" step="0.0001" name="learning_rate" value="{{ old('learning_rate',$hp['learning_rate']??0.001) }}"
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:outline-none dark:border-white/10 dark:bg-white/5">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">Loss Function</label>
                    <select name="loss_function" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:outline-none dark:border-white/10 dark:bg-white/5 dark:text-white">
                        @foreach(['cross_entropy'=>'Cross Entropy','mse'=>'MSE','bce'=>'BCE','nll'=>'NLL'] as $v => $l)
                        <option value="{{ $v }}" class="dark:bg-slate-900" @selected(old('loss_function',$hp['loss_function']??'cross_entropy')===$v)>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">ملاحظات</label>
            <textarea name="notes" rows="3"
                      class="w-full resize-none rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:outline-none dark:border-white/10 dark:bg-white/5">{{ old('notes',$experiment->notes) }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="flex flex-1 items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-cyan-600 to-blue-600 py-3.5 font-black text-white shadow-lg shadow-cyan-500/25 hover:from-cyan-500 hover:to-blue-500 transition-all">
                <i class="fa-solid fa-rotate"></i> تحديث وإعادة توليد الكود
            </button>
            <a href="{{ route('training.show', $experiment) }}"
               class="rounded-2xl border border-slate-200 bg-white px-6 py-3.5 font-bold text-slate-600 hover:bg-slate-50 dark:border-white/10 dark:bg-white/5 dark:text-slate-300 dark:hover:bg-white/10 transition-colors">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
