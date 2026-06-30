@extends('layouts.admin')
@section('title', 'إدارة المستخدمين')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-black">إدارة المستخدمين</h2>
    <span class="text-slate-400 text-sm">{{ $users->total() }} مستخدم</span>
</div>

{{-- Search --}}
<form method="GET" class="flex gap-3 mb-6">
    <input name="q" value="{{ request('q') }}"
           placeholder="بحث بالاسم أو البريد..."
           class="flex-1 bg-slate-800 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500">
    <select name="role" class="bg-slate-800 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none">
        <option value="">كل الأدوار</option>
        <option value="user"  @selected(request('role')=='user')>مستخدم</option>
        <option value="admin" @selected(request('role')=='admin')>مدير</option>
    </select>
    <button class="bg-cyan-600 hover:bg-cyan-500 px-5 py-2.5 rounded-xl text-sm font-bold transition">بحث</button>
</form>

<div class="bg-slate-800/50 border border-white/10 rounded-2xl overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-white/5 border-b border-white/10 text-slate-400 text-right">
                <th class="px-5 py-3 font-medium">#</th>
                <th class="px-5 py-3 font-medium">المستخدم</th>
                <th class="px-5 py-3 font-medium">الدور</th>
                <th class="px-5 py-3 font-medium">التسجيل</th>
                <th class="px-5 py-3 font-medium">إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr class="border-b border-white/5 hover:bg-white/5 transition">
                <td class="px-5 py-3 text-slate-500">{{ $user->id }}</td>
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-cyan-500 to-purple-600 rounded-full flex items-center justify-center text-xs font-black">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-medium">{{ $user->name }}</p>
                            <p class="text-xs text-slate-400">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-3">
                    <span class="text-xs px-2.5 py-1 rounded-full {{ $user->role === 'admin' ? 'bg-purple-500/20 text-purple-400' : 'bg-slate-700 text-slate-300' }}">
                        {{ $user->role === 'admin' ? 'مدير' : 'مستخدم' }}
                    </span>
                </td>
                <td class="px-5 py-3 text-slate-400">{{ $user->created_at->format('Y-m-d') }}</td>
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-cyan-400 hover:text-cyan-300">تعديل</a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                              onsubmit="return confirm('حذف المستخدم؟')">
                            @csrf @method('DELETE')
                            <button class="text-red-400 hover:text-red-300">حذف</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center py-12 text-slate-500">لا يوجد مستخدمون</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $users->links() }}</div>
@endsection
