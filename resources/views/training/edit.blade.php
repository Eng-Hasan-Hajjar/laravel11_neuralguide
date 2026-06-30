@extends('layouts.app')
@section('title', 'تعديل التجربة')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <a href="{{ route('training.show', $experiment) }}" class="text-slate-400 hover:text-white text-sm flex items-center gap-2 mb-6">
        <i class="fa-solid fa-arrow-right"></i> العودة للتجربة
    </a>
    <h1 class="text-3xl font-black mb-6">✏️ تعديل: {{ $experiment->name }}</h1>

    <form action="{{ route('training.update', $experiment) }}" method="POST" class="space-y-6">
        @csrf @method('PUT')

        <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-6">
            <h2 class="font-bold mb-4 text-cyan-400">معلومات التجربة</h2>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">الاسم</label>
                <input name="name" value="{{ old('name', $experiment->name) }}" required
                       class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500">
            </div>
        </div>

        <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-6">
            <h2 class="font-bold mb-4 text-amber-400">Hyperparameters</h2>
            @php $hp = $experiment->hyperparameters ?? []; @endphp
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Framework</label>
                    <select name="framework" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500">
                        <option value="pytorch"    @selected(old('framework',$experiment->framework)==='pytorch')>🔥 PyTorch</option>
                        <option value="tensorflow" @selected(old('framework',$experiment->framework)==='tensorflow')>🌊 TensorFlow</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Optimizer</label>
                    <select name="optimizer" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500">
                        @foreach(['adam','adamw','sgd','rmsprop'] as $opt)
                        <option value="{{ $opt }}" @selected(old('optimizer',$hp['optimizer']??'adam')===$opt)>{{ strtoupper($opt) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Epochs</label>
                    <input type="number" name="epochs" value="{{ old('epochs',$hp['epochs']??20) }}"
                           class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Batch Size</label>
                    <input type="number" name="batch_size" value="{{ old('batch_size',$hp['batch_size']??32) }}"
                           class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Learning Rate</label>
                    <input type="number" step="0.0001" name="learning_rate" value="{{ old('learning_rate',$hp['learning_rate']??0.001) }}"
                           class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Loss Function</label>
                    <select name="loss_function" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500">
                        @foreach(['cross_entropy'=>'Cross Entropy','mse'=>'MSE','bce'=>'BCE','nll'=>'NLL'] as $v => $l)
                        <option value="{{ $v }}" @selected(old('loss_function',$hp['loss_function']??'cross_entropy')===$v)>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-6">
            <label class="block text-sm font-medium text-slate-300 mb-1.5">ملاحظات</label>
            <textarea name="notes" rows="3"
                      class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500 resize-none">{{ old('notes',$experiment->notes) }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="flex-1 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 py-3 rounded-xl font-black transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-rotate"></i> تحديث وإعادة توليد الكود
            </button>
            <a href="{{ route('training.show', $experiment) }}"
               class="bg-slate-700 hover:bg-slate-600 px-6 py-3 rounded-xl font-bold transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
