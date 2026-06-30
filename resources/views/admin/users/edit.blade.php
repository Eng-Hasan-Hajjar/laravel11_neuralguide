@extends('layouts.admin')
@section('title', 'تعديل مستخدم')

@section('content')
<div class="max-w-xl">
    <h2 class="text-2xl font-black mb-6">تعديل: {{ $user->name }}</h2>

    <form action="{{ route('admin.users.update', $user) }}" method="POST"
          class="bg-slate-800/50 border border-white/10 rounded-2xl p-6 space-y-5">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">الاسم</label>
            <input name="name" value="{{ old('name', $user->name) }}" required
                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">البريد الإلكتروني</label>
            <input name="email" type="email" value="{{ old('email', $user->email) }}" required
                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">الدور</label>
            <select name="role" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500">
                <option value="user"  @selected(old('role',$user->role)==='user')>مستخدم</option>
                <option value="admin" @selected(old('role',$user->role)==='admin')>مدير</option>
            </select>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-cyan-600 hover:bg-cyan-500 px-6 py-2.5 rounded-xl font-bold text-sm transition">
                حفظ التغييرات
            </button>
            <a href="{{ route('admin.users.index') }}" class="bg-slate-700 hover:bg-slate-600 px-6 py-2.5 rounded-xl font-bold text-sm transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
