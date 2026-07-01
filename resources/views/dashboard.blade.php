@extends('layouts.app')
@section('title', __('messages.my_dashboard'))

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6">

    {{-- Header --}}
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black">{{ __('messages.my_dashboard') }}</h1>
            <p class="mt-1 text-slate-500 dark:text-slate-400">أهلاً، {{ auth()->user()->name }}</p>
        </div>
        <a href="{{ route('training.create') }}"
           class="flex items-center gap-2 rounded-2xl bg-gradient-to-r from-cyan-600 to-blue-600 px-5 py-2.5 text-sm font-black text-white shadow-lg shadow-cyan-500/25 hover:from-cyan-500 hover:to-blue-500 transition-all">
            <i class="fa-solid fa-plus"></i> تجربة تدريب جديدة
        </a>
    </div>

    {{-- Quick Stats --}}
    <div class="mb-8 grid grid-cols-2 gap-3 sm:grid-cols-4">
        @php
        $qstats = [
            ['الاستشارات',     $suggestions->total(),                 'fa-wand-magic-sparkles', 'cyan'],
            ['المفضلة',        $favorites->count(),                    'fa-heart',               'pink'],
            ['الملاحظات',      $notes->count(),                       'fa-note-sticky',         'amber'],
            ['تجارب التدريب',  auth()->user()->experiments()->count(), 'fa-flask',               'purple'],
        ];
        @endphp
        @foreach($qstats as [$label, $val, $icon, $color])
        <div class="rounded-2xl border border-slate-200 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="mb-2 flex h-9 w-9 items-center justify-center rounded-xl bg-{{ $color }}-500/10">
                <i class="fa-solid {{ $icon }} text-{{ $color }}-500 text-sm"></i>
            </div>
            <p class="text-2xl font-black">{{ $val }}</p>
            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $label }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid gap-6 lg:grid-cols-3">

        {{-- ── Suggestions History ── --}}
        <div class="lg:col-span-2 rounded-3xl border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4 dark:border-white/10">
                <h2 class="font-black flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-cyan-500"></i> آخر الاستشارات
                </h2>
                <span class="text-xs text-slate-400">{{ $suggestions->total() }} استشارة</span>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($suggestions as $sug)
                <a href="{{ route('suggestions.show', $sug) }}"
                   class="flex items-start gap-3 px-6 py-4 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-cyan-500/10">
                        <i class="fa-solid fa-brain text-cyan-500 text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="truncate text-sm font-medium">{{ Str::limit($sug->problem_text, 60) }}</p>
                        <p class="mt-0.5 text-xs text-slate-400">
                            {{ $sug->detected_domain }} · {{ $sug->architectures->count() }} توصية · {{ $sug->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <i class="fa-solid fa-chevron-left text-xs text-slate-300 dark:text-slate-600 mt-1"></i>
                </a>
                @empty
                <div class="py-12 text-center">
                    <i class="fa-solid fa-wand-magic-sparkles text-3xl text-slate-200 dark:text-slate-700"></i>
                    <p class="mt-3 text-sm text-slate-400">لا توجد استشارات بعد</p>
                    <a href="{{ route('home') }}" class="mt-2 block text-sm font-bold text-cyan-500 hover:underline">ابدأ باستشارتك الأولى</a>
                </div>
                @endforelse
            </div>
            @if($suggestions->hasPages())
            <div class="border-t border-slate-100 px-6 py-4 dark:border-white/10">{{ $suggestions->links() }}</div>
            @endif
        </div>

        {{-- ── Sidebar ── --}}
        <div class="space-y-4">

            {{-- Favorites --}}
            <div class="rounded-3xl border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 dark:border-white/10">
                    <h2 class="font-black text-sm flex items-center gap-2">
                        <i class="fa-solid fa-heart text-pink-500"></i> المفضلة
                    </h2>
                    <span class="text-xs text-slate-400">{{ $favorites->count() }}</span>
                </div>
                <div class="p-3 space-y-1">
                    @forelse($favorites as $fav)
                    <a href="{{ route('architectures.show', $fav) }}"
                       class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <i class="fa-solid fa-brain text-xs text-cyan-500"></i>
                        <span class="font-medium truncate">{{ $fav->name }}</span>
                        <span class="ms-auto text-xs text-slate-400">{{ $fav->difficulty }}</span>
                    </a>
                    @empty
                    <p class="py-4 text-center text-xs text-slate-400">لا يوجد محفوظات</p>
                    @endforelse
                </div>
            </div>

            {{-- Research Notes --}}
            <div class="rounded-3xl border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 dark:border-white/10">
                    <h2 class="font-black text-sm flex items-center gap-2">
                        <i class="fa-solid fa-note-sticky text-amber-500"></i> ملاحظاتي
                    </h2>
                    <a href="{{ route('notes.create') }}" class="text-xs font-bold text-cyan-500 hover:underline">+ إضافة</a>
                </div>
                <div class="p-3 space-y-1">
                    @forelse($notes as $note)
                    <a href="{{ route('notes.show', $note) }}"
                       class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <i class="fa-regular fa-note-sticky text-xs text-amber-500"></i>
                        <span class="font-medium truncate">{{ $note->title }}</span>
                    </a>
                    @empty
                    <p class="py-4 text-center text-xs text-slate-400">لا توجد ملاحظات</p>
                    @endforelse
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-4 dark:border-white/10 dark:bg-white/5">
                <h2 class="mb-3 font-black text-sm text-slate-500">روابط سريعة</h2>
                <div class="space-y-1">
                    @foreach([
                        [route('training.index'),         'fa-flask',         'تجارب التدريب',   'text-purple-500'],
                        [route('training.datasets.index'),'fa-database',      'مجموعات البيانات','text-blue-500'],
                        [route('architectures.index'),    'fa-brain',         'المعماريات',      'text-cyan-500'],
                    ] as [$href, $icon, $label, $color])
                    <a href="{{ $href }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-sm hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <i class="fa-solid {{ $icon }} w-4 text-center {{ $color }}"></i>
                        <span class="font-medium">{{ $label }}</span>
                        <i class="fa-solid fa-arrow-left ms-auto text-xs text-slate-300"></i>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
