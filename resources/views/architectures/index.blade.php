@extends('layouts.app')

@section('content')

<div class="mb-10 flex flex-col justify-between gap-6 md:flex-row md:items-end">
    <div>
        <div class="mb-4 inline-flex rounded-full border border-cyan-500/20 bg-cyan-500/10 px-4 py-2 text-sm font-black text-cyan-700 dark:text-cyan-300">
            {{ __('messages.all_architectures') }}
        </div>

        <h1 class="text-5xl font-black">{{ __('messages.architectures') }}</h1>
        <p class="mt-4 max-w-2xl text-slate-600 dark:text-slate-300">
            {{ __('messages.tagline') }}
        </p>
    </div>

    <form method="GET" action="{{ route('architectures.index') }}" class="flex gap-3">
        <input name="q"
               value="{{ request('q') }}"
               placeholder="{{ __('messages.search') }}"
               class="rounded-2xl border border-slate-200 bg-white px-5 py-3 outline-none focus:border-cyan-400 dark:border-white/10 dark:bg-white/5">

        <button class="rounded-2xl bg-cyan-500 px-6 py-3 font-black text-white">
            {{ __('messages.search') }}
        </button>
    </form>
</div>

<div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
    @foreach($architectures as $architecture)
        <a href="{{ route('architectures.show', $architecture) }}"
           class="group rounded-[2rem] border border-slate-200 bg-white p-6 shadow-lg shadow-slate-200/60 transition hover:-translate-y-1 hover:shadow-xl dark:border-white/10 dark:bg-white/5 dark:shadow-none">

            <div class="mb-6 flex items-center justify-between text-sm">
                <span class="rounded-full bg-cyan-500/10 px-3 py-1 font-bold text-cyan-700 dark:text-cyan-300">
                    {{ $architecture->difficulty }}
                </span>
                <span class="text-slate-500 dark:text-slate-400">{{ $architecture->year }}</span>
            </div>

            <h2 class="text-2xl font-black group-hover:text-cyan-600 dark:group-hover:text-cyan-300">
                {{ $architecture->name }}
            </h2>

            <p class="mt-4 leading-8 text-slate-600 dark:text-slate-300">
                {{ $architecture->short_description }}
            </p>

            <div class="mt-6 flex flex-wrap gap-2">
                @foreach(($architecture->tags ?? []) as $tag)
                    <span class="rounded-full border border-slate-200 px-3 py-1 text-xs dark:border-white/10">
                        {{ $tag }}
                    </span>
                @endforeach
            </div>

            <div class="mt-6 font-black text-cyan-600 dark:text-cyan-300">
                {{ __('messages.view_details') }}
            </div>
        </a>
    @endforeach
</div>

<div class="mt-10">
    {{ $architectures->links('vendor.pagination.tailwind') }}   
</div>

@endsection