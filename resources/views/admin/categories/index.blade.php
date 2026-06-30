{{-- resources/views/admin/categories/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'إدارة الفئات')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-black">الفئات</h2>
    <a href="{{ route('admin.categories.create') }}"
       class="bg-cyan-600 hover:bg-cyan-500 px-5 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> إضافة فئة
    </a>
</div>

<div class="bg-slate-800/50 border border-white/10 rounded-2xl overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-white/5 border-b border-white/10 text-slate-400 text-right">
                <th class="px-5 py-3 font-medium">الاسم</th>
                <th class="px-5 py-3 font-medium">Slug</th>
                <th class="px-5 py-3 font-medium">المعماريات</th>
                <th class="px-5 py-3 font-medium">إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $cat)
            <tr class="border-b border-white/5 hover:bg-white/5 transition">
                <td class="px-5 py-3 font-medium">
                    @if($cat->icon)<i class="fa-solid fa-{{ $cat->icon }} ml-1 text-cyan-400"></i>@endif
                    {{ $cat->name }}
                </td>
                <td class="px-5 py-3 text-slate-400 font-mono text-xs">{{ $cat->slug }}</td>
                <td class="px-5 py-3 text-slate-400">{{ $cat->architectures_count ?? 0 }}</td>
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.categories.edit', $cat) }}" class="text-cyan-400 hover:text-cyan-300">تعديل</a>
                        <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST"
                              onsubmit="return confirm('حذف الفئة؟')">
                            @csrf @method('DELETE')
                            <button class="text-red-400 hover:text-red-300">حذف</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center py-12 text-slate-500">لا توجد فئات</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $categories->links() }}</div>
@endsection
