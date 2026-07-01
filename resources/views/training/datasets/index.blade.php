@extends('layouts.app')
@section('title', __('messages.datasets'))

@section('content')
<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <a href="{{ route('training.index') }}" class="mb-3 flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white">
                <i class="fa-solid fa-arrow-right"></i> {{ __('messages.training') }}
            </a>
            <h1 class="text-3xl font-black">🗄 {{ __('messages.datasets') }}</h1>
        </div>
        <a href="{{ route('training.datasets.create') }}"
           class="flex items-center gap-2 rounded-2xl bg-gradient-to-r from-purple-600 to-blue-600 px-5 py-2.5 text-sm font-black text-white shadow-lg shadow-purple-500/25 hover:from-purple-500 hover:to-blue-500 transition-all">
            <i class="fa-solid fa-upload"></i> {{ __('messages.upload_dataset') }}
        </a>
    </div>

    @if($datasets->isEmpty())
    <div class="rounded-3xl border border-dashed border-slate-300 bg-slate-50 py-20 text-center dark:border-white/15 dark:bg-white/[.02]">
        <i class="fa-solid fa-database mb-4 block text-5xl text-slate-300 dark:text-slate-700"></i>
        <p class="text-xl font-black text-slate-600 dark:text-slate-300">لا توجد مجموعات بيانات</p>
        <p class="mt-1 mb-6 text-slate-400 dark:text-slate-500">ارفع مجموعتك الأولى لبدء التدريب</p>
        <a href="{{ route('training.datasets.create') }}"
           class="rounded-2xl bg-purple-600 px-6 py-3 font-black text-white hover:bg-purple-500 transition-colors">
            رفع الآن
        </a>
    </div>
    @else
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50 text-slate-500 dark:border-white/10 dark:bg-white/5 dark:text-slate-400">
                    <th class="px-5 py-3 text-start font-bold">الاسم</th>
                    <th class="px-5 py-3 text-start font-bold">النوع</th>
                    <th class="px-5 py-3 text-start font-bold">الحجم</th>
                    <th class="px-5 py-3 text-start font-bold">التاريخ</th>
                    <th class="px-5 py-3 text-start font-bold">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @foreach($datasets as $ds)
                <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                    <td class="px-5 py-3">
                        <p class="font-bold text-slate-800 dark:text-slate-200">{{ $ds->name }}</p>
                        <p class="max-w-xs truncate text-xs text-slate-400">{{ $ds->file_name }}</p>
                    </td>
                    <td class="px-5 py-3">
                        <span class="rounded-full bg-slate-100 px-2 py-1 text-xs font-bold text-slate-600 dark:bg-white/10 dark:text-slate-300">{{ $ds->type }}</span>
                    </td>
                    <td class="px-5 py-3 text-slate-500 dark:text-slate-400">{{ $ds->formattedSize() }}</td>
                    <td class="px-5 py-3 text-slate-500 dark:text-slate-400">{{ $ds->created_at->format('Y-m-d') }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('training.create') }}?dataset_id={{ $ds->id }}" class="text-xs font-bold text-cyan-600 hover:underline dark:text-cyan-400">استخدام</a>
                            <form action="{{ route('training.datasets.destroy', $ds) }}" method="POST" onsubmit="return confirm('حذف مجموعة البيانات؟')">
                                @csrf @method('DELETE')
                                <button class="text-xs font-bold text-red-500 hover:underline">حذف</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $datasets->links() }}</div>
    @endif
</div>
@endsection
