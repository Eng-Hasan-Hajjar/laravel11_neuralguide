


{{-- resources/views/admin/architectures/index.blade.php --}}
@extends('layouts.admin') {{-- غيّر إلى layouts.app إذا كان هذا هو اسم التخطيط الأساسي في نظامك --}}
@section('title', 'إدارة المعماريات')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-black text-white">المعماريات</h2>
    <a href="{{ route('admin.architectures.create') }}"
       class="bg-cyan-600 hover:bg-cyan-500 px-5 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2 text-white shadow-lg shadow-cyan-500/20">
        <i class="fa-solid fa-plus"></i> إضافة معمارية
    </a>
</div>

{{-- رسالة النجاح --}}
@if(session('status'))
<div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl mb-4 flex items-center gap-2">
    <i class="fa-solid fa-circle-check"></i>
    <span>{{ session('status') }}</span>
</div>
@endif

<div class="bg-slate-800/50 border border-white/10 rounded-2xl overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-white/5 border-b border-white/10 text-slate-400 text-right">
                <th class="px-5 py-3 font-medium">الاسم</th>
                <th class="px-5 py-3 font-medium">السنة</th>
                <th class="px-5 py-3 font-medium">الحالة</th>
                <th class="px-5 py-3 font-medium">إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($architectures as $a)
            <tr class="border-b border-white/5 hover:bg-white/5 transition group">
                <td class="px-5 py-3 font-medium text-white">
                    <i class="fa-solid fa-building ml-1 text-cyan-400"></i>
                    {{ $a->name }}
                </td>
                <td class="px-5 py-3 text-slate-400 font-mono text-xs">
                    {{ $a->year }}
                </td>
                <td class="px-5 py-3">
                    @if($a->is_published)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            <i class="fa-solid fa-check"></i> منشور
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-500/10 text-slate-400 border border-slate-500/20">
                            <i class="fa-solid fa-xmark"></i> غير منشور
                        </span>
                    @endif
                </td>
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.architectures.edit', $a) }}" 
                           class="text-cyan-400 hover:text-cyan-300 transition flex items-center gap-1.5" title="تعديل">
                            <i class="fa-solid fa-pen-to-square"></i> تعديل
                        </a>
                        <form action="{{ route('admin.architectures.destroy', $a) }}" method="POST"
                              onsubmit="return confirm('هل أنت متأكد من حذف هذه المعمارية؟ لا يمكن التراجع عن هذا الإجراء.')" 
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="text-red-400 hover:text-red-300 transition flex items-center gap-1.5" title="حذف">
                                <i class="fa-solid fa-trash"></i> حذف
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-12 text-slate-500">
                    <i class="fa-solid fa-folder-open text-4xl mb-3 opacity-30"></i>
                    <p class="text-sm">لا توجد معماريات مضافة بعد</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $architectures->links() }}
</div>
@endsection