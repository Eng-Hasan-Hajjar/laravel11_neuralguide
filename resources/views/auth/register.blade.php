@extends('layouts.app')
@section('title', __('messages.register'))

@section('content')
<div class="flex min-h-[80vh] items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">

        <div class="mb-8 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-600 text-xl font-black text-white shadow-xl shadow-cyan-500/25">
                NG
            </div>
            <h1 class="text-2xl font-black">{{ __('messages.register') }}</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">انضم إلى {{ __('messages.app_name') }} مجاناً</p>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/50 dark:border-white/10 dark:bg-slate-900 dark:shadow-none">
            <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">الاسم الكامل</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/20 dark:border-white/10 dark:bg-white/5 @error('name') border-red-400 @enderror">
                    @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">البريد الإلكتروني</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/20 dark:border-white/10 dark:bg-white/5 @error('email') border-red-400 @enderror">
                    @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">الانتماء (اختياري)</label>
                    <input type="text" name="affiliation" value="{{ old('affiliation') }}"
                           placeholder="جامعة، شركة، مؤسسة..."
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:bg-white focus:outline-none dark:border-white/10 dark:bg-white/5">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">كلمة المرور</label>
                    <input type="password" name="password" required minlength="8"
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/20 dark:border-white/10 dark:bg-white/5 @error('password') border-red-400 @enderror">
                    @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">تأكيد كلمة المرور</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/20 dark:border-white/10 dark:bg-white/5">
                </div>

                <button type="submit"
                        class="w-full rounded-2xl bg-gradient-to-r from-cyan-600 to-blue-600 py-3 text-sm font-black text-white shadow-lg shadow-cyan-500/25 hover:from-cyan-500 hover:to-blue-500 transition-all">
                    إنشاء الحساب
                </button>
            </form>
        </div>

        <p class="mt-5 text-center text-sm text-slate-500 dark:text-slate-400">
            لديك حساب؟
            <a href="{{ route('login') }}" class="font-bold text-cyan-600 hover:underline dark:text-cyan-400">
                {{ __('messages.login') }}
            </a>
        </p>
    </div>
</div>
@endsection
