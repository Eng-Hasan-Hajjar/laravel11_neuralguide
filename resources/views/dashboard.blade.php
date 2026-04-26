@extends('layouts.app')

@section('content')

<div class="mb-10">
    <div class="mb-4 inline-flex rounded-full border border-cyan-500/20 bg-cyan-500/10 px-4 py-2 text-sm font-black text-cyan-700 dark:text-cyan-300">
        {{ __('messages.my_dashboard') }}
    </div>

    <h1 class="text-5xl font-black">{{ __('messages.dashboard') }}</h1>
    <p class="mt-4 text-slate-600 dark:text-slate-300">
        {{ __('messages.tagline') }}
    </p>
</div>

<div class="grid gap-6 md:grid-cols-3">
    <div class="rounded-3xl border border-slate-200 bg-white p-8 dark:border-white/10 dark:bg-white/5">
        <div class="text-4xl font-black">{{ $suggestionsCount ?? 0 }}</div>
        <div class="mt-2 text-slate-500 dark:text-slate-400">{{ __('messages.stats_recommendations') }}</div>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-8 dark:border-white/10 dark:bg-white/5">
        <div class="text-4xl font-black">{{ $favoritesCount ?? 0 }}</div>
        <div class="mt-2 text-slate-500 dark:text-slate-400">{{ __('messages.featured') }}</div>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-8 dark:border-white/10 dark:bg-white/5">
        <div class="text-4xl font-black">{{ $architecturesCount ?? 0 }}</div>
        <div class="mt-2 text-slate-500 dark:text-slate-400">{{ __('messages.stats_architectures') }}</div>
    </div>
</div>

@endsection