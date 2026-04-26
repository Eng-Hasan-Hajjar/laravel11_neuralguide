@extends('layouts.app')

@section('content')

<section class="relative overflow-hidden rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-2xl shadow-slate-200/70 dark:border-white/10 dark:bg-white/5 dark:shadow-none lg:p-12">

    <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_right,rgba(6,182,212,.18),transparent_35%),radial-gradient(circle_at_bottom_left,rgba(59,130,246,.16),transparent_35%)]"></div>

    <div class="grid items-center gap-12 lg:grid-cols-2">

        <div>
            <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-cyan-500/20 bg-cyan-500/10 px-5 py-2 text-sm font-black text-cyan-700 dark:text-cyan-300">
                <span class="h-2 w-2 rounded-full bg-cyan-500"></span>
                {{ __('messages.hero_kicker') }}
            </div>

           <h1 class="max-w-4xl text-5xl font-black leading-[1.18] tracking-tight text-slate-950 dark:text-white md:text-7xl">
                {{ __('messages.hero_title') }}
            </h1>

            <p class="mt-6 max-w-2xl text-lg leading-9 text-slate-600 dark:text-slate-300">
                {{ __('messages.hero_subtitle') }}
            </p>

            <div class="mt-8 grid gap-3 sm:grid-cols-3">
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 dark:border-white/10 dark:bg-[#07111f]">
                    <div class="text-3xl font-black text-cyan-600 dark:text-cyan-300">32+</div>
                    <div class="mt-1 text-sm font-bold text-slate-500 dark:text-slate-400">{{ __('messages.stats_architectures') }}</div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 dark:border-white/10 dark:bg-[#07111f]">
                    <div class="text-3xl font-black text-cyan-600 dark:text-cyan-300">9+</div>
                    <div class="mt-1 text-sm font-bold text-slate-500 dark:text-slate-400">{{ __('messages.stats_categories') }}</div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 dark:border-white/10 dark:bg-[#07111f]">
                    <div class="text-3xl font-black text-cyan-600 dark:text-cyan-300">AI</div>
                    <div class="mt-1 text-sm font-bold text-slate-500 dark:text-slate-400">{{ __('messages.stats_recommendations') }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('suggestions.store') }}" class="mt-8 rounded-[2rem] border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-[#07111f]">
                @csrf

                <textarea name="problem"
                          rows="5"
                          class="w-full resize-none rounded-3xl border border-slate-200 bg-white p-5 text-base leading-8 outline-none transition focus:border-cyan-400 focus:ring-4 focus:ring-cyan-500/10 dark:border-white/10 dark:bg-[#050816]"
                          placeholder="{{ __('messages.problem_placeholder') }}"></textarea>

                <div class="mt-4 flex flex-wrap items-center gap-3">
                    <button class="rounded-2xl bg-cyan-500 px-8 py-4 font-black text-white shadow-lg shadow-cyan-500/25 transition hover:-translate-y-0.5 hover:bg-cyan-400">
                        {{ __('messages.start') }}
                    </button>

                    <a href="{{ route('architectures.index') }}"
                       class="rounded-2xl border border-slate-300 bg-white px-8 py-4 font-black transition hover:-translate-y-0.5 hover:bg-slate-100 dark:border-white/15 dark:bg-white/5 dark:hover:bg-white/10">
                        {{ __('messages.browse') }}
                    </a>
                </div>
            </form>
        </div>

        <div class="relative">
            <div class="absolute -inset-5 -z-10 rounded-[3rem] bg-cyan-500/10 blur-3xl"></div>

            <div class="rounded-[2.5rem] border border-slate-200 bg-slate-50 p-5 dark:border-white/10 dark:bg-[#07111f]">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <div class="text-sm font-black text-cyan-600 dark:text-cyan-300">Neural Ranking</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ __('messages.research_grade') }}</div>
                    </div>

                    <div class="rounded-full bg-emerald-500/10 px-4 py-2 text-xs font-black text-emerald-600 dark:text-emerald-300">
                        Live Expert System
                    </div>
                </div>

                @php
                    $items = [
                        ['Transformer', '96%', 'NLP · Generation · Attention'],
                        ['EfficientNet', '92%', 'Vision · Classification · Accuracy'],
                        ['Graph Neural Network', '88%', 'Graph AI · Relations · Nodes'],
                        ['Diffusion Model', '84%', 'Images · Generation · Creativity'],
                    ];
                @endphp

                <div class="space-y-4">
                    @foreach($items as $index => $item)
                        <div class="group rounded-3xl border border-slate-200 bg-white p-5 transition hover:-translate-y-1 hover:border-cyan-400 dark:border-white/10 dark:bg-white/5">
                            <div class="flex items-center justify-between gap-5">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-500/10 font-black text-cyan-600 dark:text-cyan-300">
                                        {{ $index + 1 }}
                                    </div>

                                    <div>
                                        <div class="text-xl font-black">{{ $item[0] }}</div>
                                        <div class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $item[2] }}</div>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <div class="text-2xl font-black text-cyan-600 dark:text-cyan-300">{{ $item[1] }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ __('messages.score') }}</div>
                                </div>
                            </div>

                            <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-white/10">
                                <div class="h-full rounded-full bg-cyan-500" style="width: {{ $item[1] }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</section>

<section class="grid gap-6 py-12 md:grid-cols-3">
    <div class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/60 dark:border-white/10 dark:bg-white/5 dark:shadow-none">
        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-cyan-500/10 text-2xl">🧠</div>
        <h3 class="text-2xl font-black">Rule-based Engine</h3>
        <p class="mt-3 leading-8 text-slate-600 dark:text-slate-300">
            يحلل كلمات المشكلة ويربطها بالمعماريات المناسبة حسب المجال، البيانات، الكلفة، والدقة.
        </p>
    </div>

    <div class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/60 dark:border-white/10 dark:bg-white/5 dark:shadow-none">
        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-cyan-500/10 text-2xl">📚</div>
        <h3 class="text-2xl font-black">Research Knowledge Base</h3>
        <p class="mt-3 leading-8 text-slate-600 dark:text-slate-300">
            قاعدة معرفة تحتوي على المعمارية، سنة النشر، الورقة البحثية، الاستخدامات، القيود، وأمثلة الكود.
        </p>
    </div>

    <div class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/60 dark:border-white/10 dark:bg-white/5 dark:shadow-none">
        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-cyan-500/10 text-2xl">⚙️</div>
        <h3 class="text-2xl font-black">Implementation Ready</h3>
        <p class="mt-3 leading-8 text-slate-600 dark:text-slate-300">
            يقدم إعدادات مقترحة وأمثلة PyTorch و TensorFlow لتقليل وقت البحث والبدء العملي.
        </p>
    </div>
</section>

<section class="py-6">
    <div class="mb-8 flex flex-col justify-between gap-4 md:flex-row md:items-end">
        <div>
            <div class="mb-3 text-sm font-black text-cyan-600 dark:text-cyan-300">{{ __('messages.browse') }}</div>
            <h2 class="text-4xl font-black">{{ __('messages.featured') }}</h2>
            <p class="mt-3 text-slate-500 dark:text-slate-400">
                أشهر المعماريات المناسبة للرؤية الحاسوبية، اللغة، السلاسل الزمنية، والرسوم البيانية.
            </p>
        </div>

        <a href="{{ route('architectures.index') }}"
           class="rounded-2xl border border-slate-300 bg-white px-6 py-3 font-black transition hover:bg-slate-100 dark:border-white/15 dark:bg-white/5 dark:hover:bg-white/10">
            {{ __('messages.all_architectures') }}
        </a>
    </div>

    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach($architectures ?? [] as $architecture)
            <a href="{{ route('architectures.show', $architecture) }}"
               class="group rounded-[2rem] border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/60 transition hover:-translate-y-1 hover:border-cyan-400 dark:border-white/10 dark:bg-white/5 dark:shadow-none">

                <div class="mb-6 flex justify-between text-sm">
                    <span class="rounded-full bg-cyan-500/10 px-3 py-1 font-black text-cyan-700 dark:text-cyan-300">
                        {{ $architecture->difficulty }}
                    </span>

                    <span class="rounded-full bg-slate-100 px-3 py-1 font-black text-slate-500 dark:bg-white/10 dark:text-slate-300">
                        {{ $architecture->year }}
                    </span>
                </div>

                <h3 class="text-2xl font-black group-hover:text-cyan-600 dark:group-hover:text-cyan-300">
                    {{ $architecture->name }}
                </h3>

                <p class="mt-4 min-h-[88px] leading-8 text-slate-600 dark:text-slate-300">
                    {{ $architecture->short_description }}
                </p>

                <div class="mt-6 flex items-center justify-between border-t border-slate-200 pt-5 dark:border-white/10">
                    <span class="font-black text-cyan-600 dark:text-cyan-300">
                        {{ __('messages.view_details') }}
                    </span>

                    <span class="transition group-hover:translate-x-1 rtl:group-hover:-translate-x-1">
                        →
                    </span>
                </div>
            </a>
        @endforeach
    </div>
</section>

@endsection