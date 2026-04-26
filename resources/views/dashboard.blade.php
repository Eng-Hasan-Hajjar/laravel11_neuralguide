@extends('layouts.app')
@section('title','لوحة الباحث')
@section('content')
<h1 class="text-3xl font-extrabold mb-6">لوحة الباحث / المتعلم</h1>
<h2 class="text-xl font-bold mb-3">سجل الاستعلامات</h2>
<div class="space-y-3">
@foreach($suggestions as $s)
 <a href="{{ route('suggestions.show',$s) }}" class="block rounded-2xl bg-white/10 p-4 hover:bg-white/15">
  <b>{{ $s->detected_domain }}</b><p class="text-slate-300">{{ str($s->problem_text)->limit(160) }}</p>
 </a>
@endforeach
</div>
<div class="mt-5">{{ $suggestions->links() }}</div>
@endsection
