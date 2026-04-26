@extends('layouts.app')
@section('title','المعماريات')
@section('content')
<div class="mb-8 flex flex-col justify-between gap-4 md:flex-row md:items-end">
  <div>
    <h1 class="text-4xl font-black md:text-6xl">موسوعة المعماريات</h1>
    <p class="mt-3 text-lg text-slate-400">استعرض النماذج العصبية حسب المجال والصعوبة ومتطلبات البيانات.</p>
  </div>
  <form class="flex gap-2" method="GET">
    <input name="q" value="{{ request('q') }}" class="w-72 rounded-2xl border border-white/10 bg-white/10 px-4 py-3 outline-none focus:ring-4 focus:ring-cyan-300/30" placeholder="ابحث عن CNN, Transformer...">
    <button class="rounded-2xl bg-cyan-400 px-5 py-3 font-black text-slate-950">بحث</button>
  </form>
</div>
<div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
@foreach($architectures as $architecture)
  <a href="{{ route('architectures.show',$architecture) }}" class="group rounded-[1.7rem] border border-white/10 bg-white/[.06] p-6 shadow-xl shadow-slate-950/20 transition hover:-translate-y-1 hover:border-cyan-300/40 hover:bg-white/[.09]">
    <div class="mb-4 flex items-center justify-between gap-3">
      <span class="rounded-full bg-cyan-300/10 px-3 py-1 text-xs text-cyan-100">{{ $architecture->difficulty }}</span>
      <span class="text-sm text-slate-400">{{ $architecture->year }}</span>
    </div>
    <h2 class="text-2xl font-black group-hover:text-cyan-200">{{ $architecture->name }}</h2>
    <p class="mt-3 line-clamp-3 leading-8 text-slate-400">{{ $architecture->short_description }}</p>
    <div class="mt-5 text-sm font-bold text-cyan-300">عرض التفاصيل ←</div>
  </a>
@endforeach
</div>
<div class="mt-8">{{ $architectures->links() }}</div>
@endsection
