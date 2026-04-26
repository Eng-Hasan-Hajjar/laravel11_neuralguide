@extends('layouts.app')
@section('title','نتائج الترشيح')
@section('content')
<h1 class="text-3xl font-extrabold mb-3">نتائج الترشيح</h1>
<div class="rounded-2xl bg-white/10 p-5 mb-6">
 <b>المجال المكتشف:</b> {{ $suggestion->detected_domain }}
 <p class="text-slate-300 mt-2">{{ $suggestion->problem_text }}</p>
</div>
<div class="space-y-4">
@foreach($suggestion->architectures as $a)
 <div class="rounded-3xl bg-white/10 p-6">
  <div class="flex items-center justify-between gap-4"><h2 class="text-2xl font-bold">#{{ $a->pivot->rank }} — {{ $a->name }}</h2><span class="rounded-full bg-cyan-600 px-3 py-1">Score {{ $a->pivot->score }}</span></div>
  <p class="mt-3 text-slate-200">{{ $a->pivot->reason }}</p>
  <p class="mt-3 text-slate-300">{{ $a->short_description }}</p>
  <div class="mt-4 flex gap-3"><a class="rounded-xl bg-white text-slate-900 px-4 py-2 font-bold" href="{{ route('architectures.show',$a) }}">التفاصيل والكود</a></div>
 </div>
@endforeach
</div>
@endsection
