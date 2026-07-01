@extends('layouts.app')
@section('title', $dataset->name)

@section('content')
<div class="mx-auto max-w-3xl px-4 py-10 sm:px-6">
    <a href="{{ route('training.datasets.index') }}" class="mb-6 flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white">
        <i class="fa-solid fa-arrow-right"></i> {{ __('messages.datasets') }}
    </a>

    <div class="mb-8 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black">{{ $dataset->name }}</h1>
            <p class="mt-1 text-slate-500 dark:text-slate-400">{{ $dataset->description }}</p>
        </div>
        <a href="{{ route('training.create') }}?dataset_id={{ $dataset->id }}"
           class="flex items-center gap-2 rounded-2xl bg-cyan-600 px-5 py-2.5 text-sm font-black text-white hover:bg-cyan-500 transition-colors">
            <i class="fa-solid fa-flask"></i> استخدام في تجربة
        </a>
    </div>

    <div class="mb-6 grid gap-4 md:grid-cols-2">
        @php
        $info = [
            ['النوع', $dataset->type, 'fa-file'],
            ['نوع المهمة', $dataset->task_type ?? '—', 'fa-bullseye'],
            ['الحجم', $dataset->formattedSize(), 'fa-weight-hanging'],
            ['اسم الملف', $dataset->file_name, 'fa-file-code'],
            ['تاريخ الرفع', $dataset->created_at->format('Y-m-d H:i'), 'fa-calendar'],
        ];
        @endphp
        @foreach($info as [$label, $value, $icon])
        <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 dark:border-white/10 dark:bg-white/5">
            <i class="fa-solid {{ $icon }} w-4 text-center text-cyan-500"></i>
            <div class="min-w-0">
                <p class="text-xs text-slate-400">{{ $label }}</p>
                <p class="truncate text-sm font-bold text-slate-800 dark:text-slate-200">{{ $value }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <form action="{{ route('training.datasets.destroy', $dataset) }}" method="POST" onsubmit="return confirm('حذف مجموعة البيانات نهائياً؟')">
        @csrf @method('DELETE')
        <button class="flex items-center gap-2 text-sm font-bold text-red-500 hover:underline transition-colors">
            <i class="fa-solid fa-trash"></i> حذف المجموعة
        </button>
    </form>
</div>
@endsection
