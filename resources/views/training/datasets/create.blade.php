@extends('layouts.app')
@section('title', 'رفع مجموعة بيانات')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <a href="{{ route('training.datasets.index') }}" class="text-slate-400 hover:text-white text-sm flex items-center gap-2 mb-6">
        <i class="fa-solid fa-arrow-right"></i> مجموعات البيانات
    </a>
    <h1 class="text-3xl font-black mb-2">📤 رفع مجموعة بيانات</h1>
    <p class="text-slate-400 text-sm mb-8">الحد الأقصى 100 ميجابايت · CSV أو صور أو JSON</p>

    <form action="{{ route('training.datasets.store') }}" method="POST" enctype="multipart/form-data"
          class="space-y-5">
        @csrf

        <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">اسم المجموعة <span class="text-red-400">*</span></label>
                <input name="name" value="{{ old('name') }}" required
                       placeholder="مثال: CIFAR-10 Training Set"
                       class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">الوصف</label>
                <textarea name="description" rows="2"
                          placeholder="وصف مختصر لمحتوى المجموعة..."
                          class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500 resize-none">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">نوع الملف</label>
                    <select name="type" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500">
                        <option value="csv">CSV</option>
                        <option value="images">Images (ZIP)</option>
                        <option value="json">JSON</option>
                        <option value="custom">أخرى</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">نوع المهمة</label>
                    <input name="task_type" value="{{ old('task_type') }}"
                           placeholder="classification, regression..."
                           class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500">
                </div>
            </div>
        </div>

        {{-- File Upload Zone --}}
        <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-6">
            <label class="block text-sm font-medium text-slate-300 mb-3">الملف <span class="text-red-400">*</span></label>
            <label for="file-input"
                   class="upload-zone cursor-pointer border-2 border-dashed border-white/20 hover:border-cyan-500 rounded-2xl p-10 text-center block transition">
                <i class="fa-solid fa-cloud-arrow-up text-4xl text-slate-500 mb-3 block"></i>
                <p class="font-bold text-slate-300">اسحب الملف هنا أو انقر للاختيار</p>
                <p class="text-sm text-slate-500 mt-1">CSV, JSON, ZIP, NPY · حتى 100 MB</p>
                <p id="file-name" class="text-sm text-cyan-400 mt-2 hidden"></p>
            </label>
            <input id="file-input" name="file" type="file" required class="hidden"
                   accept=".csv,.json,.zip,.npy,.pkl,.h5">
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="flex-1 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-500 hover:to-blue-500 py-3 rounded-xl font-black transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-upload"></i> رفع المجموعة
            </button>
            <a href="{{ route('training.datasets.index') }}"
               class="bg-slate-700 hover:bg-slate-600 px-6 py-3 rounded-xl font-bold transition">
                إلغاء
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('file-input').addEventListener('change', function () {
    const name = document.getElementById('file-name');
    if (this.files[0]) {
        name.textContent = '✓ ' + this.files[0].name;
        name.classList.remove('hidden');
    }
});
</script>
@endpush
@endsection
