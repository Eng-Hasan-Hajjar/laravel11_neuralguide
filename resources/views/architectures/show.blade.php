@extends('layouts.app')

@section('content')

<section class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-2xl shadow-slate-200/70 dark:border-white/10 dark:bg-white/5 dark:shadow-none">

    <div class="grid gap-8 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="mb-4 flex flex-wrap gap-2">
                <span class="rounded-full bg-cyan-500/10 px-4 py-2 text-sm font-black text-cyan-700 dark:text-cyan-300">
                    {{ $architecture->year }}
                </span>

                <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-black dark:bg-white/10">
                    {{ $architecture->difficulty }}
                </span>
            </div>

            <h1 class="text-5xl font-black">{{ $architecture->name }}</h1>

            <p class="mt-5 max-w-3xl text-xl leading-9 text-slate-600 dark:text-slate-300">
                {{ $architecture->short_description }}
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ $architecture->paper_url }}"
                   target="_blank"
                   class="rounded-2xl bg-cyan-500 px-6 py-3 font-black text-white shadow-lg shadow-cyan-500/25">
                    {{ __('messages.open_paper') }}
                </a>

                <a href="{{ route('architectures.index') }}"
                   class="rounded-2xl border border-slate-300 px-6 py-3 font-black hover:bg-slate-100 dark:border-white/15 dark:hover:bg-white/10">
                    {{ __('messages.all_architectures') }}
                </a>
            </div>
        </div>

        <div class="space-y-4">
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 dark:border-white/10 dark:bg-[#07111f]">
                <div class="text-sm text-slate-500 dark:text-slate-400">{{ __('messages.difficulty') }}</div>
                <div class="mt-1 text-xl font-black">{{ $architecture->difficulty }}</div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 dark:border-white/10 dark:bg-[#07111f]">
                <div class="text-sm text-slate-500 dark:text-slate-400">{{ __('messages.data') }}</div>
                <div class="mt-1 font-bold">{{ $architecture->data_requirement }}</div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 dark:border-white/10 dark:bg-[#07111f]">
                <div class="text-sm text-slate-500 dark:text-slate-400">{{ __('messages.compute') }}</div>
                <div class="mt-1 font-bold">{{ $architecture->compute_requirement }}</div>
            </div>
        </div>
    </div>

</section>

<section class="mt-8 grid gap-6 lg:grid-cols-3">

    <aside class="space-y-6">
        <div class="rounded-3xl border border-cyan-500/20 bg-cyan-500/10 p-6">
            <h3 class="text-xl font-black">{{ __('messages.research_paper') }}</h3>
            <p class="mt-4 text-sm leading-7 text-slate-600 dark:text-slate-300">
                {{ $architecture->paper_title }}
            </p>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <h3 class="text-xl font-black">{{ __('messages.frameworks') ?? 'Frameworks' }}</h3>
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach(($architecture->frameworks ?? []) as $framework)
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-sm font-bold dark:bg-white/10">
                        {{ $framework }}
                    </span>
                @endforeach
            </div>
        </div>
    </aside>

    <div class="space-y-6 lg:col-span-2">

        <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <h2 class="text-2xl font-black">{{ __('messages.scientific_description') }}</h2>
            <p class="mt-4 leading-9 text-slate-600 dark:text-slate-300">
                {{ $architecture->description }}
            </p>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <h2 class="text-2xl font-black">{{ __('messages.best_for') }}</h2>
            <p class="mt-4 leading-9 text-slate-600 dark:text-slate-300">
                {{ $architecture->best_for }}
            </p>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <h2 class="text-2xl font-black">{{ __('messages.limitations') }}</h2>
            <p class="mt-4 leading-9 text-slate-600 dark:text-slate-300">
                {{ $architecture->limitations }}
            </p>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <h2 class="text-2xl font-black">{{ __('messages.recommended_settings') }}</h2>
            <pre class="mt-4 overflow-auto rounded-2xl bg-[#020617] p-5 text-sm leading-7 text-cyan-100"><code>{{ $architecture->recommended_settings }}</code></pre>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <h2 class="text-2xl font-black">{{ __('messages.pytorch_example') }}</h2>
            <pre class="mt-4 overflow-auto rounded-2xl bg-[#020617] p-5 text-sm leading-7 text-cyan-100"><code>{{ $architecture->pytorch_example }}</code></pre>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <h2 class="text-2xl font-black">{{ __('messages.tensorflow_example') }}</h2>
            <pre class="mt-4 overflow-auto rounded-2xl bg-[#020617] p-5 text-sm leading-7 text-cyan-100"><code>{{ $architecture->tensorflow_example }}</code></pre>
        </div>

    </div>
</section>

@endsection