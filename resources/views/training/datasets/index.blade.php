{{-- resources/views/training/datasets/index.blade.php --}}
@extends('layouts.app')
@section('title', 'مجموعات البيانات')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <a href="{{ route('training.index') }}" class="text-slate-400 hover:text-white text-sm flex items-center gap-2 mb-3">
                <i class="fa-solid fa-arrow-right"></i> تجارب التدريب
            </a>
            <h1 class="text-3xl font-black">🗄 مجموعات البيانات</h1>
        </div>
        <a href="{{ route('training.datasets.create') }}"
           class="bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-500 hover:to-blue-500 px-5 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2">
            <i class="fa-solid fa-upload"></i> رفع مجموعة
        </a>
    </div>

    @if($datasets->isEmpty())
    <div class="text-center py-20 bg-slate-800/30 rounded-3xl border border-dashed border-white/20">
        <i class="fa-solid fa-database text-5xl text-slate-600 mb-4 block"></i>
        <p class="text-xl font-bold text-slate-300">لا توجد مجموعات بيانات</p>
        <p class="text-slate-500 mt-1 mb-6">ارفع مجموعتك الأولى لبدء التدريب</p>
        <a href="{{ route('training.datasets.create') }}"
           class="bg-purple-600 hover:bg-purple-500 px-6 py-3 rounded-xl font-bold transition">
            رفع الآن
        </a>
    </div>
    @else
    <div class="bg-slate-800/50 border border-white/10 rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-white/5 border-b border-white/10 text-slate-400 text-right">
                    <th class="px-5 py-3 font-medium">الاسم</th>
                    <th class="px-5 py-3 font-medium">النوع</th>
                    <th class="px-5 py-3 font-medium">الحجم</th>
                    <th class="px-5 py-3 font-medium">التاريخ</th>
                    <th class="px-5 py-3 font-medium">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($datasets as $ds)
                <tr class="border-b border-white/5 hover:bg-white/5 transition">
                    <td class="px-5 py-3">
                        <p class="font-medium">{{ $ds->name }}</p>
                        <p class="text-xs text-slate-500 truncate max-w-xs">{{ $ds->file_name }}</p>
                    </td>
                    <td class="px-5 py-3">
                        <span class="text-xs bg-slate-700 px-2 py-1 rounded-full">{{ $ds->type }}</span>
                    </td>
                    <td class="px-5 py-3 text-slate-400">{{ $ds->formattedSize() }}</td>
                    <td class="px-5 py-3 text-slate-400">{{ $ds->created_at->format('Y-m-d') }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('training.create') }}?dataset_id={{ $ds->id }}"
                               class="text-cyan-400 hover:text-cyan-300 text-xs">استخدام</a>
                            <form action="{{ route('training.datasets.destroy', $ds) }}" method="POST"
                                  onsubmit="return confirm('حذف مجموعة البيانات؟')">
                                @csrf @method('DELETE')
                                <button class="text-red-400 hover:text-red-300 text-xs">حذف</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $datasets->links() }}</div>
    @endif
</div>
@endsection
