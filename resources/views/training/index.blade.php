@extends('layouts.app')
@section('title', __('messages.training'))

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6">

    {{-- Header --}}
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black">🧪 {{ __('messages.training') }}</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">أنشئ تجربة، اختر معمارية، ولّد كود Python، وحمّله</p>
        </div>
        <a href="{{ route('training.create') }}"
           class="flex items-center gap-2 rounded-2xl bg-gradient-to-r from-cyan-600 to-blue-600 px-5 py-2.5 text-sm font-black text-white shadow-lg shadow-cyan-500/25 hover:from-cyan-500 hover:to-blue-500 transition-all">
            <i class="fa-solid fa-plus"></i> {{ __('messages.new_experiment') }}
        </a>
    </div>

    {{-- Quick Links --}}
    <div class="mb-8 grid grid-cols-2 gap-3 md:grid-cols-4">
        @php
        $links = [
            [route('training.create'), 'fa-plus', 'تجربة جديدة', 'cyan'],
            [route('training.datasets.index'), 'fa-database', __('messages.datasets'), 'purple'],
            [route('architectures.index'), 'fa-brain', __('messages.architectures'), 'amber'],
            [route('dashboard'), 'fa-gauge', __('messages.my_dashboard'), 'emerald'],
        ];
        @endphp
        @foreach($links as [$href, $icon, $label, $color])
        <a href="{{ $href }}"
           class="group rounded-2xl border border-slate-200 bg-white p-4 text-center transition hover:-translate-y-0.5 hover:border-{{ $color }}-400 hover:shadow-lg dark:border-white/10 dark:bg-white/5 dark:hover:border-{{ $color }}-500/40">
            <i class="fa-solid {{ $icon }} mb-2 block text-xl text-{{ $color }}-500 transition-transform group-hover:scale-110"></i>
            <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $label }}</p>
        </a>
        @endforeach
    </div>

    {{-- Experiments Grid --}}
    @if($experiments->isEmpty())
    <div class="rounded-3xl border border-dashed border-slate-300 bg-slate-50 py-20 text-center dark:border-white/15 dark:bg-white/[.02]">
        <i class="fa-solid fa-flask mb-4 block text-5xl text-slate-300 dark:text-slate-700"></i>
        <p class="text-xl font-black text-slate-600 dark:text-slate-300">لا توجد تجارب بعد</p>
        <p class="mt-1 mb-6 text-slate-400 dark:text-slate-500">ابدأ بإنشاء تجربتك الأولى</p>
        <a href="{{ route('training.create') }}"
           class="rounded-2xl bg-cyan-600 px-6 py-3 font-black text-white hover:bg-cyan-500 transition-colors">
            ابدأ الآن
        </a>
    </div>
    @else
    <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
        @foreach($experiments as $exp)
        <div class="group rounded-2xl border border-slate-200 bg-white p-5 transition hover:-translate-y-0.5 hover:border-cyan-400 hover:shadow-lg dark:border-white/10 dark:bg-white/5 dark:hover:border-cyan-500/40">
            <div class="mb-3 flex items-start justify-between">
                <span class="rounded-full px-2.5 py-1 text-xs font-bold
                    {{ $exp->status==='completed' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' :
                       ($exp->status==='running'   ? 'bg-blue-500/10 text-blue-600 dark:text-blue-400' :
                       ($exp->status==='failed'    ? 'bg-red-500/10 text-red-600 dark:text-red-400' :
                                                      'bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-slate-300')) }}">
                    {{ __('messages.' . $exp->status) }}
                </span>
                <span class="text-xs text-slate-400">{{ $exp->created_at->diffForHumans() }}</span>
            </div>

            <h3 class="mb-1 font-black text-base group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">{{ $exp->name }}</h3>
            <p class="mb-1 text-sm text-slate-500 dark:text-slate-400">{{ $exp->architecture?->name }}</p>
            <p class="mb-4 text-xs text-slate-400 dark:text-slate-500">
                {{ strtoupper($exp->framework) }} ·
                epochs: {{ $exp->hyperparameters['epochs'] ?? '?' }} ·
                lr: {{ $exp->hyperparameters['learning_rate'] ?? '?' }}
            </p>

            <div class="flex gap-2">
                <a href="{{ route('training.show', $exp) }}"
                   class="flex-1 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-center text-xs font-bold text-slate-700 hover:border-cyan-400 hover:text-cyan-600 dark:border-white/10 dark:bg-white/5 dark:text-slate-200 dark:hover:border-cyan-500/40 transition-colors">
                    عرض
                </a>
                <a href="{{ route('training.download', $exp) }}"
                   class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 hover:border-emerald-400 hover:text-emerald-600 dark:border-white/10 dark:bg-white/5 dark:text-slate-300 transition-colors">
                    <i class="fa-solid fa-download"></i>
                </a>
                <form action="{{ route('training.destroy', $exp) }}" method="POST" onsubmit="return confirm('حذف التجربة؟')">
                    @csrf @method('DELETE')
                    <button class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-red-500 hover:border-red-400 hover:bg-red-50 dark:border-white/10 dark:bg-white/5 dark:hover:bg-red-500/10 transition-colors">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-8">{{ $experiments->links() }}</div>
    @endif
</div>
@endsection
