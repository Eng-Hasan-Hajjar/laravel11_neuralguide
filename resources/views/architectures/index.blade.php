@extends('layouts.app')
@section('title', __('messages.all_architectures'))

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-black">{{ __('messages.all_architectures') }}</h1>
        <p class="mt-1 text-slate-500 dark:text-slate-400">{{ $architectures->total() }} {{ __('messages.stats_architectures') }}</p>
    </div>

    <div class="flex gap-8">

        {{-- ── Sidebar Filters ── --}}
        <aside class="hidden w-56 shrink-0 lg:block">
            <form method="GET" id="filterForm">
                <input type="hidden" name="q" value="{{ request('q') }}">

                {{-- Difficulty --}}
                <div class="mb-6">
                    <h3 class="mb-3 text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">{{ __('messages.difficulty') }}</h3>
                    <div class="space-y-1.5">
                        @foreach(['beginner','intermediate','advanced','research'] as $val)
                        <label class="flex cursor-pointer items-center gap-2.5 rounded-xl px-2 py-1.5 text-sm hover:bg-slate-100 dark:hover:bg-white/5">
                            <input type="radio" name="difficulty" value="{{ $val }}"
                                   {{ request('difficulty') === $val ? 'checked' : '' }}
                                   class="accent-cyan-500"
                                   onchange="this.form.submit()">
                            <span class="font-medium text-slate-700 dark:text-slate-300">{{ __('messages.difficulty_' . $val) }}</span>
                        </label>
                        @endforeach
                        @if(request('difficulty'))
                        <a href="{{ request()->fullUrlWithoutQuery('difficulty') }}" class="block text-xs text-cyan-500 px-2 hover:underline">× {{ __('messages.back') }}</a>
                        @endif
                    </div>
                </div>

                {{-- Categories --}}
                <div class="mb-6">
                    <h3 class="mb-3 text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">{{ __('messages.stats_categories') }}</h3>
                    <div class="space-y-1.5">
                        @foreach($categories as $cat)
                        <label class="flex cursor-pointer items-center gap-2.5 rounded-xl px-2 py-1.5 text-sm hover:bg-slate-100 dark:hover:bg-white/5">
                            <input type="radio" name="category" value="{{ $cat->slug }}"
                                   {{ request('category') === $cat->slug ? 'checked' : '' }}
                                   class="accent-cyan-500"
                                   onchange="this.form.submit()">
                            <span class="font-medium text-slate-700 dark:text-slate-300">{{ $cat->name }}</span>
                        </label>
                        @endforeach
                        @if(request('category'))
                        <a href="{{ request()->fullUrlWithoutQuery('category') }}" class="block text-xs text-cyan-500 px-2 hover:underline">× {{ __('messages.back') }}</a>
                        @endif
                    </div>
                </div>
            </form>
        </aside>

        {{-- ── Main Content ── --}}
        <div class="flex-1 min-w-0">

            {{-- Search --}}
            <form method="GET" class="mb-6 flex gap-2">
                @if(request('difficulty'))<input type="hidden" name="difficulty" value="{{ request('difficulty') }}">@endif
                @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute start-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="q" value="{{ request('q') }}"
                           placeholder="{{ __('messages.search') }}..."
                           class="w-full rounded-2xl border border-slate-200 bg-white py-3 ps-10 pe-4 text-sm focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/20 dark:border-white/10 dark:bg-white/5">
                </div>
                <button class="rounded-2xl bg-cyan-600 px-5 py-3 text-sm font-bold text-white hover:bg-cyan-500 transition-colors">
                    {{ __('messages.search') }}
                </button>
            </form>

            {{-- Grid --}}
            @if($architectures->isEmpty())
            <div class="rounded-3xl border border-dashed border-slate-300 py-20 text-center dark:border-white/10">
                <i class="fa-solid fa-search text-4xl text-slate-300 dark:text-slate-600"></i>
                <p class="mt-3 font-bold text-slate-500">{{ __('messages.search') }} — {{ __('messages.description') }} 0</p>
                <a href="{{ route('architectures.index') }}" class="mt-2 block text-sm text-cyan-500 hover:underline">{{ __('messages.all_architectures') }}</a>
            </div>
            @else
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @foreach($architectures as $arch)
                <a href="{{ route('architectures.show', $arch) }}"
                   class="group flex flex-col rounded-2xl border border-slate-200 bg-white p-5 transition hover:-translate-y-0.5 hover:border-cyan-400 hover:shadow-lg hover:shadow-cyan-500/10 dark:border-white/10 dark:bg-white/5 dark:hover:border-cyan-500/40">

                    <div class="mb-3 flex items-center justify-between gap-2">
                        <span class="@php
                            echo match($arch->difficulty) {
                                'beginner'     => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
                                'intermediate' => 'bg-blue-500/10 text-blue-600 dark:text-blue-400',
                                'advanced'     => 'bg-purple-500/10 text-purple-600 dark:text-purple-400',
                                'research'     => 'bg-red-500/10 text-red-600 dark:text-red-400',
                                default        => 'bg-slate-100 text-slate-600',
                            }
                        @endphp rounded-lg px-2.5 py-1 text-xs font-bold">
                            {{ __('messages.difficulty_' . $arch->difficulty) }}
                        </span>
                        @if($arch->year)
                        <span class="text-xs text-slate-400">{{ $arch->year }}</span>
                        @endif
                    </div>

                    <h3 class="mb-1.5 font-black text-base group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">{{ $arch->name }}</h3>

                    <p class="flex-1 text-sm leading-relaxed text-slate-600 line-clamp-2 dark:text-slate-400">
                        {{ $arch->short_description }}
                    </p>

                    @if(!empty($arch->frameworks))
                    <div class="mt-3 flex flex-wrap gap-1">
                        @foreach(array_slice($arch->frameworks, 0, 3) as $fw)
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-500 dark:bg-white/10 dark:text-slate-400">{{ $fw }}</span>
                        @endforeach
                    </div>
                    @endif
                </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">{{ $architectures->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
