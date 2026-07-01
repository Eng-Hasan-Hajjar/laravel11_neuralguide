@extends('layouts.app')
@section('title', __('messages.upload_dataset'))

@section('content')
<div class="mx-auto max-w-2xl px-4 py-10 sm:px-6">
    <a href="{{ route('training.datasets.index') }}" class="mb-6 flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white">
        <i class="fa-solid fa-arrow-right"></i> {{ __('messages.datasets') }}
    </a>
    <h1 class="mb-2 text-3xl font-black">📤 {{ __('messages.upload_dataset') }}</h1>
    <p class="mb-8 text-sm text-slate-500 dark:text-slate-400">الحد الأقصى 100 ميجابايت · CSV أو صور أو JSON</p>

    <form action="{{ route('training.datasets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div class="space-y-4 rounded-3xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <div>
                <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">اسم المجموعة <span class="text-red-500">*</span></label>
                <input name="name" value="{{ old('name') }}" required
                       placeholder="مثال: CIFAR-10 Training Set"
                       class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:outline-none dark:border-white/10 dark:bg-white/5">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">الوصف</label>
                <textarea name="description" rows="2" placeholder="وصف مختصر لمحتوى المجموعة..."
                          class="w-full resize-none rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:outline-none dark:border-white/10 dark:bg-white/5">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">نوع الملف</label>
                    <select name="type" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:outline-none dark:border-white/10 dark:bg-white/5 dark:text-white">
                        <option value="csv" class="dark:bg-slate-900">CSV</option>
                        <option value="images" class="dark:bg-slate-900">Images (ZIP)</option>
                        <option value="json" class="dark:bg-slate-900">JSON</option>
                        <option value="custom" class="dark:bg-slate-900">أخرى</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">نوع المهمة</label>
                    <input name="task_type" value="{{ old('task_type') }}" placeholder="classification, regression..."
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:outline-none dark:border-white/10 dark:bg-white/5">
                </div>
            </div>
        </div>

        {{-- File Upload Zone --}}
        <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <label class="mb-3 block text-sm font-bold text-slate-700 dark:text-slate-300">الملف <span class="text-red-500">*</span></label>
            <label for="file-input"
                   class="block cursor-pointer rounded-2xl border-2 border-dashed border-slate-300 p-10 text-center transition hover:border-cyan-400 hover:bg-cyan-50/50 dark:border-white/15 dark:hover:border-cyan-500/40 dark:hover:bg-cyan-500/5">
                <i class="fa-solid fa-cloud-arrow-up mb-3 block text-4xl text-slate-300 dark:text-slate-600"></i>
                <p class="font-bold text-slate-600 dark:text-slate-300">اسحب الملف هنا أو انقر للاختيار</p>
                <p class="mt-1 text-sm text-slate-400 dark:text-slate-500">CSV, JSON, ZIP, NPY · حتى 100 MB</p>
                <p id="file-name" class="mt-2 hidden text-sm font-bold text-cyan-600 dark:text-cyan-400"></p>
            </label>
            <input id="file-input" name="file" type="file" required class="hidden" accept=".csv,.json,.zip,.npy,.pkl,.h5">
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="flex flex-1 items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-purple-600 to-blue-600 py-3.5 font-black text-white shadow-lg shadow-purple-500/25 hover:from-purple-500 hover:to-blue-500 transition-all">
                <i class="fa-solid fa-upload"></i> رفع المجموعة
            </button>
            <a href="{{ route('training.datasets.index') }}"
               class="rounded-2xl border border-slate-200 bg-white px-6 py-3.5 font-bold text-slate-600 hover:bg-slate-50 dark:border-white/10 dark:bg-white/5 dark:text-slate-300 dark:hover:bg-white/10 transition-colors">
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
