@extends('layouts.admin')
@section('title', $category->exists ? 'تعديل فئة' : 'إضافة فئة')

@section('content')
<div class="max-w-xl">
    <h2 class="text-2xl font-black mb-6">{{ $category->exists ? 'تعديل: '.$category->name : 'إضافة فئة جديدة' }}</h2>

    <form action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
          method="POST"
          class="bg-slate-800/50 border border-white/10 rounded-2xl p-6 space-y-5">
        @csrf
        @if($category->exists) @method('PUT') @endif

        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">الاسم <span class="text-red-400">*</span></label>
            <input name="name" value="{{ old('name', $category->name) }}" required
                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Slug (اختياري)</label>
            <input name="slug" value="{{ old('slug', $category->slug) }}"
                   placeholder="يُولَّد تلقائياً من الاسم"
                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm font-mono focus:outline-none focus:border-cyan-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">الوصف</label>
            <textarea name="description" rows="3"
                      class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500 resize-none">{{ old('description', $category->description) }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Icon (Font Awesome)</label>
                <input name="icon" value="{{ old('icon', $category->icon) }}" placeholder="brain, eye, chart-line"
                       class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">اللون</label>
                <input name="color" value="{{ old('color', $category->color) }}" placeholder="cyan, purple, amber"
                       class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500">
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-cyan-600 hover:bg-cyan-500 px-6 py-2.5 rounded-xl font-bold text-sm transition">
                {{ $category->exists ? 'حفظ التغييرات' : 'إضافة الفئة' }}
            </button>
            <a href="{{ route('admin.categories.index') }}" class="bg-slate-700 hover:bg-slate-600 px-6 py-2.5 rounded-xl font-bold text-sm transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
