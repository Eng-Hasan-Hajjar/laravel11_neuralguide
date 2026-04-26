@extends('layouts.app')
@section('title','تسجيل الدخول')
@section('content')
<form method="POST" action="{{ route('login.store') }}" class="max-w-md mx-auto rounded-3xl bg-white text-slate-900 p-6 grid gap-4">@csrf
<h1 class="text-2xl font-extrabold">تسجيل الدخول</h1>
<input name="email" type="email" placeholder="البريد" class="rounded-xl" value="{{ old('email') }}">
<input name="password" type="password" placeholder="كلمة المرور" class="rounded-xl">
<button class="rounded-xl bg-slate-950 text-white py-3">دخول</button>
<a href="{{ route('register') }}">إنشاء حساب باحث</a>
</form>
@endsection
