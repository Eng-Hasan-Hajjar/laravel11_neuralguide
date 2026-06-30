@extends('layouts.app')
@section('title', $dataset->name)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <a href="{{ route('training.datasets.index') }}" class="text-slate-400 hover:text-white text-sm flex items-center gap-2 mb-6">
        <i class="fa-solid fa-arrow-right"></i> مجموعات البيانات
    </a>

    <div class="flex items-start justify-between mb-8 gap-4 flex-wrap">
        <div>
            <h1 class="text-3xl font-black">{{ $dataset->name }}</h1>
            <p class="text-slate-400 mt-1">{{ $dataset->description }}</p>
        </div>
        <a href="{{ route('training.create') }}?dataset_id={{ $dataset->id }}"
           class="bg-cyan-600 hover:bg-cyan-500 px-5 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2">
            <i class="fa-solid fa-flask"></i> استخدام في تجربة
        </a>
    </div>

    <div class="grid md:grid-cols-2 gap-4 mb-6">
        @php
        $info = [
            ['label'=>'النوع','value'=>$dataset->type,'icon'=>'fa-file'],
            ['label'=>'نوع المهمة','value'=>$dataset->task_type ?? '—','icon'=>'fa-bullseye'],
            ['label'=>'الحجم','value'=>$dataset->formattedSize(),'icon'=>'fa-weight-hanging'],
            ['label'=>'اسم الملف','value'=>$dataset->file_name,'icon'=>'fa-file-code'],
            ['label'=>'تاريخ الرفع','value'=>$dataset->created_at->format('Y-m-d H:i'),'icon'=>'fa-calendar'],
        ];
        @endphp
        @foreach($info as $item)
        <div class="bg-slate-800/50 border border-white/10 rounded-xl px-4 py-3 flex items-center gap-3">
            <i class="fa-solid {{ $item['icon'] }} text-cyan-400 w-4"></i>
            <div>
                <p class="text-xs text-slate-500">{{ $item['label'] }}</p>
                <p class="font-medium text-sm truncate">{{ $item['value'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <form action="{{ route('training.datasets.destroy', $dataset) }}" method="POST"
          onsubmit="return confirm('حذف مجموعة البيانات نهائياً؟')">
        @csrf @method('DELETE')
        <button class="text-red-400 hover:text-red-300 text-sm flex items-center gap-2 transition">
            <i class="fa-solid fa-trash"></i> حذف المجموعة
        </button>
    </form>
</div>
@endsection
