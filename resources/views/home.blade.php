@extends('layouts.app')
@section('title','NeuralGuide - دليل الشبكات العصبية الذكية')
@section('content')
<section class="grid items-center gap-10 py-10 lg:grid-cols-[1.05fr_.95fr]">
  <div>
    <div class="mb-5 inline-flex rounded-full border border-cyan-300/20 bg-cyan-300/10 px-4 py-2 text-sm text-cyan-100">منصة عربية أكاديمية لاختيار معماريات الشبكات العصبية</div>
    <h1 class="text-5xl font-black leading-tight md:text-7xl">اكتب فكرتك، <span class="text-cyan-300">نقترح لك</span> أفضل دماغ صناعي.</h1>
    <p class="mt-6 max-w-2xl text-xl leading-9 text-slate-300">NeuralGuide يحلل وصف المشكلة ويقترح نماذج تعلم عميق مناسبة مع التعليل العلمي، أمثلة كود، الأوراق البحثية، ومتطلبات البيانات والحوسبة.</p>
  </div>
  <form method="POST" action="{{ route('suggestions.store') }}" class="rounded-[2rem] border border-white/10 bg-white/[.07] p-6 shadow-2xl shadow-cyan-950/30 backdrop-blur">
    @csrf
    <label class="text-lg font-black">ما المشكلة التي تريد حلها؟</label>
    <textarea name="problem" required rows="8" class="mt-4 w-full rounded-3xl border border-white/10 bg-slate-950/70 p-5 text-lg leading-8 text-white outline-none ring-cyan-300/40 placeholder:text-slate-500 focus:ring-4" placeholder="مثال: أريد تصنيف صور طبية، أو توليد نص عربي، أو كشف احتيال بنكي..."></textarea>
    <button class="mt-4 w-full rounded-3xl bg-cyan-400 px-6 py-4 text-lg font-black text-slate-950 shadow-lg shadow-cyan-500/20 hover:bg-cyan-300">حلّل الفكرة الآن</button>
  </form>
</section>

<section class="grid gap-4 md:grid-cols-3">
  @foreach(['ترشيح علمي مفسّر','أكواد PyTorch وKeras','قاعدة معرفية قابلة للتوسع'] as $item)
  <div class="rounded-3xl border border-white/10 bg-white/[.06] p-6">
    <div class="mb-4 h-12 w-12 rounded-2xl bg-cyan-300/20"></div>
    <h3 class="text-2xl font-black">{{ $item }}</h3>
    <p class="mt-3 leading-8 text-slate-400">مصمم للباحثين والطلاب والمطورين الذين يريدون قرارًا أسرع وأكثر وضوحًا.</p>
  </div>
  @endforeach
</section>
@endsection
