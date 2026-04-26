@extends('layouts.app')
@section('title',$architecture->name)
@section('content')
<section class="overflow-hidden rounded-[2rem] border border-white/10 bg-white/[.06] shadow-2xl shadow-cyan-950/30 backdrop-blur">
  <div class="grid gap-8 p-6 lg:grid-cols-[1fr_360px] lg:p-10">
    <div>
      <div class="mb-4 flex flex-wrap items-center gap-2">
        @foreach($architecture->categories ?? [] as $category)
          <span class="rounded-full border border-cyan-300/20 bg-cyan-300/10 px-3 py-1 text-xs text-cyan-100">{{ $category->name_ar ?? $category->name }}</span>
        @endforeach
        <span class="rounded-full border border-white/10 bg-white/10 px-3 py-1 text-xs">{{ $architecture->year }}</span>
      </div>
      <h1 class="text-4xl font-black leading-tight md:text-6xl">{{ $architecture->name }}</h1>
      <p class="mt-5 max-w-3xl text-xl leading-9 text-slate-300">{{ $architecture->short_description }}</p>
      <div class="mt-8 flex flex-wrap gap-3">
        @if($architecture->arxiv_url || $architecture->paper_url)
          <a href="{{ $architecture->arxiv_url ?: $architecture->paper_url }}" target="_blank" class="rounded-2xl bg-cyan-400 px-5 py-3 font-extrabold text-slate-950 shadow-lg shadow-cyan-500/20">فتح الورقة البحثية</a>
        @endif
        <a href="{{ route('architectures.index') }}" class="rounded-2xl border border-white/10 bg-white/10 px-5 py-3 font-bold text-white hover:bg-white/15">كل المعماريات</a>
      </div>
    </div>
    <aside class="grid gap-3">
      <div class="rounded-3xl border border-white/10 bg-slate-950/50 p-5"><div class="text-sm text-slate-400">الصعوبة</div><div class="mt-1 text-2xl font-black">{{ $architecture->difficulty }}</div></div>
      <div class="rounded-3xl border border-white/10 bg-slate-950/50 p-5"><div class="text-sm text-slate-400">متطلبات البيانات</div><div class="mt-1 text-lg font-bold leading-8">{{ $architecture->data_requirement }}</div></div>
      <div class="rounded-3xl border border-white/10 bg-slate-950/50 p-5"><div class="text-sm text-slate-400">الحوسبة</div><div class="mt-1 text-lg font-bold leading-8">{{ $architecture->compute_requirement }}</div></div>
    </aside>
  </div>
</section>

<div class="mt-8 grid gap-6 lg:grid-cols-[1fr_360px]">
  <article class="space-y-6">
    @foreach([
      'الوصف العلمي' => $architecture->description,
      'مناسب لـ' => $architecture->best_for,
      'القيود' => $architecture->limitations,
    ] as $title => $body)
      <section class="rounded-3xl border border-white/10 bg-white/[.06] p-6 shadow-xl shadow-slate-950/20">
        <h2 class="mb-3 text-2xl font-black text-cyan-100">{{ $title }}</h2>
        <p class="text-lg leading-9 text-slate-300">{{ $body }}</p>
      </section>
    @endforeach

    <section class="rounded-3xl border border-white/10 bg-white/[.06] p-6">
      <h2 class="mb-3 text-2xl font-black text-cyan-100">الإعدادات المقترحة</h2>
      <pre class="overflow-x-auto rounded-2xl border border-white/10 bg-slate-950 p-5 text-left text-sm leading-7 text-cyan-100" dir="ltr"><code>{{ $architecture->recommended_settings }}</code></pre>
    </section>

    <section class="rounded-3xl border border-white/10 bg-white/[.06] p-6">
      <h2 class="mb-4 text-2xl font-black text-cyan-100">مثال PyTorch</h2>
      <pre class="overflow-x-auto rounded-2xl border border-white/10 bg-slate-950 p-5 text-left text-sm leading-7 text-emerald-100" dir="ltr"><code>{{ $architecture->pytorch_example }}</code></pre>
    </section>

    <section class="rounded-3xl border border-white/10 bg-white/[.06] p-6">
      <h2 class="mb-4 text-2xl font-black text-cyan-100">مثال TensorFlow/Keras</h2>
      <pre class="overflow-x-auto rounded-2xl border border-white/10 bg-slate-950 p-5 text-left text-sm leading-7 text-amber-100" dir="ltr"><code>{{ $architecture->tensorflow_example }}</code></pre>
    </section>
  </article>

  <aside class="space-y-4">
    <div class="rounded-3xl border border-cyan-300/20 bg-cyan-300/10 p-6">
      <h3 class="text-xl font-black text-cyan-100">ملاحظة بحثية</h3>
      <p class="mt-3 leading-8 text-slate-300">استخدم هذه البطاقة كبداية، ثم قارِنها بخصائص بياناتك وحجم الحوسبة المتاح قبل اعتماد النموذج.</p>
    </div>
    <div class="rounded-3xl border border-white/10 bg-white/[.06] p-6">
      <h3 class="text-xl font-black">الورقة</h3>
      <p class="mt-3 text-slate-300">{{ $architecture->paper_title ?: 'رابط الورقة الأصلية أو المرتبطة' }}</p>
    </div>
  </aside>
</div>
@endsection
