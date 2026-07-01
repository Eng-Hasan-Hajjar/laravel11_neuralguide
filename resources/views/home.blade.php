@extends('layouts.app')
@section('title', __('messages.app_name'))

@section('content')

{{-- ══ Hero ══ --}}
<section class="relative overflow-hidden px-4 py-20 sm:px-6 sm:py-28">
    <div class="mx-auto max-w-4xl text-center">

        <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-cyan-500/30 bg-cyan-500/10 px-4 py-1.5 text-xs font-bold text-cyan-600 dark:text-cyan-400">
            <span class="relative flex h-2 w-2">
                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-cyan-400 opacity-75"></span>
                <span class="relative inline-flex h-2 w-2 rounded-full bg-cyan-500"></span>
            </span>
            {{ __('messages.hero_kicker') }}
        </div>

        <h1 class="mb-5 text-4xl font-black leading-tight tracking-tight sm:text-5xl lg:text-6xl">
            {{ __('messages.hero_title') }}
        </h1>

        <p class="mx-auto mb-10 max-w-2xl text-lg leading-relaxed text-slate-600 dark:text-slate-300">
            {{ __('messages.hero_subtitle') }}
        </p>

        {{-- Problem Form --}}
        <form action="{{ route('suggestions.store') }}" method="POST" class="mx-auto max-w-2xl">
            @csrf
            <div class="flex flex-col gap-3 rounded-3xl border border-slate-200 bg-white p-3 shadow-xl shadow-slate-200/50 dark:border-white/10 dark:bg-slate-900 dark:shadow-none sm:flex-row">
                <textarea
                    name="problem"
                    rows="2"
                    placeholder="{{ __('messages.problem_placeholder') }}"
                    required minlength="10"
                    class="flex-1 resize-none rounded-2xl bg-transparent px-4 py-3 text-sm placeholder-slate-400 focus:outline-none dark:placeholder-slate-500"
                >{{ old('problem') }}</textarea>
                <button type="submit"
                        class="shrink-0 rounded-2xl bg-gradient-to-r from-cyan-500 to-blue-600 px-6 py-3 text-sm font-black text-white shadow-lg shadow-cyan-500/25 hover:from-cyan-400 hover:to-blue-500 transition-all">
                    <i class="fa-solid fa-wand-magic-sparkles me-1.5"></i>
                    {{ __('messages.start') }}
                </button>
            </div>
            @error('problem')
            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </form>

        {{-- Stats --}}
        <div class="mt-12 flex flex-wrap justify-center gap-8">
            @php
            $stats = [
                [__('messages.stats_architectures'),    $architecturesCount,   'fa-brain', 'text-cyan-500'],
                [__('messages.stats_categories'),       $categories->count(),  'fa-tags',  'text-purple-500'],
                [__('messages.stats_recommendations'),  '∞',                   'fa-bolt',  'text-amber-500'],
            ];
            @endphp
            @foreach($stats as [$label, $val, $icon, $color])
            <div class="text-center">
                <div class="text-3xl font-black {{ $color }}">
                    <i class="fa-solid {{ $icon }} me-1 text-2xl"></i>{{ $val }}
                </div>
                <div class="mt-1 text-xs font-medium text-slate-500 dark:text-slate-400">{{ $label }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ How it works ══ --}}
<section class="border-y border-slate-200 bg-slate-50 px-4 py-16 dark:border-white/10 dark:bg-white/[.02] sm:px-6">
    <div class="mx-auto max-w-5xl">
        <h2 class="mb-10 text-center text-2xl font-black">{{ __('messages.how_it_works_title') }}</h2>
        <div class="grid gap-6 sm:grid-cols-3">
            @php
            $steps = [
                ['1', __('messages.step1_title'), __('messages.step1_desc'), 'fa-keyboard', 'from-cyan-500 to-blue-600'],
                ['2', __('messages.step2_title'), __('messages.step2_desc'), 'fa-brain',    'from-purple-500 to-pink-500'],
                ['3', __('messages.step3_title'), __('messages.step3_desc'), 'fa-code',     'from-amber-500 to-orange-500'],
            ];
            @endphp
            @foreach($steps as [$num, $title, $desc, $icon, $gradient])
            <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br {{ $gradient }} text-lg font-black text-white shadow-lg">
                    <i class="fa-solid {{ $icon }}"></i>
                </div>
                <h3 class="mb-2 font-black">{{ $num }}. {{ $title }}</h3>
                <p class="text-sm leading-relaxed text-slate-600 dark:text-slate-400">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ Featured Architectures ══ --}}
<section class="px-4 py-16 sm:px-6">
    <div class="mx-auto max-w-7xl">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-black">{{ __('messages.featured') }}</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('messages.featured_subtitle') }}</p>
            </div>
            <a href="{{ route('architectures.index') }}"
               class="hidden rounded-xl border border-slate-200 px-4 py-2 text-sm font-bold text-slate-600 hover:bg-slate-50 dark:border-white/10 dark:text-slate-300 dark:hover:bg-white/5 sm:flex items-center gap-2">
                {{ __('messages.browse') }} <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($featured as $arch)
            <a href="{{ route('architectures.show', $arch) }}"
               class="group rounded-2xl border border-slate-200 bg-white p-5 transition hover:-translate-y-0.5 hover:border-cyan-400 hover:shadow-lg hover:shadow-cyan-500/10 dark:border-white/10 dark:bg-white/5 dark:hover:border-cyan-500/40">

                <div class="mb-3 flex items-start justify-between gap-2">
                    <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600 dark:bg-white/10 dark:text-slate-300">
                        {{ __('messages.difficulty_' . $arch->difficulty) }}
                    </span>
                    <span class="text-xs text-slate-400">{{ $arch->year }}</span>
                </div>

                <h3 class="mb-2 text-lg font-black group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">
                    {{ $arch->name }}
                </h3>

                <p class="text-sm leading-relaxed text-slate-600 line-clamp-2 dark:text-slate-400">
                    {{ $arch->short_description }}
                </p>

                @if($arch->frameworks)
                <div class="mt-3 flex flex-wrap gap-1.5">
                    @foreach(array_slice($arch->frameworks ?? [], 0, 3) as $fw)
                    <span class="rounded-full bg-cyan-500/10 px-2 py-0.5 text-[10px] font-bold text-cyan-600 dark:text-cyan-400">{{ $fw }}</span>
                    @endforeach
                </div>
                @endif
            </a>
            @endforeach
        </div>

        <div class="mt-6 text-center sm:hidden">
            <a href="{{ route('architectures.index') }}" class="text-sm font-bold text-cyan-600 dark:text-cyan-400">
                {{ __('messages.view_all_mobile') }}
            </a>
        </div>
    </div>
</section>

{{-- ══ CTA ══ --}}
@guest
<section class="mx-4 mb-16 overflow-hidden rounded-3xl bg-gradient-to-r from-cyan-600 to-blue-700 px-8 py-12 text-center text-white shadow-xl shadow-cyan-500/25 sm:mx-6">
    <h2 class="mb-3 text-3xl font-black">{{ __('messages.cta_title') }}</h2>
    <p class="mb-6 text-cyan-100">{{ __('messages.cta_subtitle') }}</p>
    <div class="flex flex-wrap justify-center gap-3">
        <a href="{{ route('register') }}" class="rounded-2xl bg-white px-6 py-3 text-sm font-black text-cyan-700 shadow hover:bg-cyan-50 transition-colors">
            {{ __('messages.cta_register') }}
        </a>
        <a href="{{ route('login') }}" class="rounded-2xl border border-white/30 px-6 py-3 text-sm font-bold text-white hover:bg-white/10 transition-colors">
            {{ __('messages.login') }}
        </a>
    </div>
</section>
@endguest

@endsection
