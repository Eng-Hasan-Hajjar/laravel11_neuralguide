@extends('layouts.app')
@section('title','إنشاء حساب')
@section('content')
<form method="POST" action="{{ route('register.store') }}" class="max-w-md mx-auto rounded-3xl bg-white text-slate-900 p-6 grid gap-4">@csrf
<h1 class="text-2xl font-extrabold">إنشاء حساب باحث</h1>
<input name="name" placeholder="الاسم" class="rounded-xl" value="{{ old('name') }}">
<input name="email" type="email" placeholder="البريد" class="rounded-xl" value="{{ old('email') }}">
<input name="affiliation" placeholder="الجهة/الجامعة" class="rounded-xl" value="{{ old('affiliation') }}">
<input name="password" type="password" placeholder="كلمة المرور" class="rounded-xl">
<input name="password_confirmation" type="password" placeholder="تأكيد كلمة المرور" class="rounded-xl">
<button class="rounded-xl bg-slate-950 text-white py-3">تسجيل</button>
</form>
@endsection
