@extends('layouts.app')
@section('title', 'تجارب التدريب')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black">🧪 تجارب التدريب</h1>
            <p class="text-slate-400 mt-1 text-sm">أنشئ تجربة، اختر معمارية، ولّد كود Python، وحمّله</p>
        </div>
        <a href="{{ route('training.create') }}"
           class="bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 px-5 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> تجربة جديدة
        </a>
    </div>

    {{-- Quick Links --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8">
        <a href="{{ route('training.create') }}"
           class="bg-cyan-500/10 border border-cyan-500/30 hover:border-cyan-400 rounded-2xl p-4 text-center transition group">
            <i class="fa-solid fa-plus text-cyan-400 text-xl mb-2 group-hover:scale-110 transition-transform block"></i>
            <p class="text-sm font-medium">تجربة جديدة</p>
        </a>
        <a href="{{ route('training.datasets.index') }}"
           class="bg-purple-500/10 border border-purple-500/30 hover:border-purple-400 rounded-2xl p-4 text-center transition group">
            <i class="fa-solid fa-database text-purple-400 text-xl mb-2 group-hover:scale-110 transition-transform block"></i>
            <p class="text-sm font-medium">مجموعات البيانات</p>
        </a>
        <a href="{{ route('architectures.index') }}"
           class="bg-amber-500/10 border border-amber-500/30 hover:border-amber-400 rounded-2xl p-4 text-center transition group">
            <i class="fa-solid fa-brain text-amber-400 text-xl mb-2 group-hover:scale-110 transition-transform block"></i>
            <p class="text-sm font-medium">استعرض المعماريات</p>
        </a>
        <a href="{{ route('dashboard') }}"
           class="bg-emerald-500/10 border border-emerald-500/30 hover:border-emerald-400 rounded-2xl p-4 text-center transition group">
            <i class="fa-solid fa-gauge text-emerald-400 text-xl mb-2 group-hover:scale-110 transition-transform block"></i>
            <p class="text-sm font-medium">لوحتي</p>
        </a>
    </div>

    {{-- Experiments Grid --}}
    @if($experiments->isEmpty())
    <div class="text-center py-20 bg-slate-800/30 rounded-3xl border border-dashed border-white/20">
        <i class="fa-solid fa-flask text-5xl text-slate-600 mb-4 block"></i>
        <p class="text-xl font-bold text-slate-300">لا توجد تجارب بعد</p>
        <p class="text-slate-500 mt-1 mb-6">ابدأ بإنشاء تجربتك الأولى</p>
        <a href="{{ route('training.create') }}"
           class="bg-cyan-600 hover:bg-cyan-500 px-6 py-3 rounded-xl font-bold transition">
            ابدأ الآن
        </a>
    </div>
    @else
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($experiments as $exp)
        <div class="bg-slate-800/50 border border-white/10 hover:border-cyan-500/40 rounded-2xl p-5 transition group">
            <div class="flex items-start justify-between mb-3">
                <span class="text-xs px-2.5 py-1 rounded-full font-medium
                    {{ $exp->status==='completed' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' :
                       ($exp->status==='running'   ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' :
                       ($exp->status==='failed'    ? 'bg-red-500/20 text-red-400 border border-red-500/30' :
                                                      'bg-slate-700 text-slate-300')) }}">
                    {{ $exp->statusLabel() }}
                </span>
                <span class="text-xs text-slate-500">{{ $exp->created_at->diffForHumans() }}</span>
            </div>

            <h3 class="font-bold text-base mb-1 group-hover:text-cyan-400 transition">{{ $exp->name }}</h3>
            <p class="text-sm text-slate-400 mb-1">{{ $exp->architecture?->name }}</p>
            <p class="text-xs text-slate-500 mb-4">
                {{ strtoupper($exp->framework) }} ·
                epochs: {{ $exp->hyperparameters['epochs'] ?? '?' }} ·
                lr: {{ $exp->hyperparameters['learning_rate'] ?? '?' }}
            </p>

            <div class="flex gap-2">
                <a href="{{ route('training.show', $exp) }}"
                   class="flex-1 text-center bg-slate-700 hover:bg-cyan-600/20 hover:border-cyan-500 border border-white/10 px-3 py-2 rounded-xl text-xs font-bold transition">
                    عرض
                </a>
                <a href="{{ route('training.download', $exp) }}"
                   class="text-center bg-slate-700 hover:bg-slate-600 border border-white/10 px-3 py-2 rounded-xl text-xs font-bold transition">
                    <i class="fa-solid fa-download"></i>
                </a>
                <form action="{{ route('training.destroy', $exp) }}" method="POST"
                      onsubmit="return confirm('حذف التجربة؟')">
                    @csrf @method('DELETE')
                    <button class="text-center bg-slate-700 hover:bg-red-600/20 hover:border-red-500 border border-white/10 px-3 py-2 rounded-xl text-xs font-bold transition">
                        <i class="fa-solid fa-trash text-red-400"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $experiments->links() }}</div>
    @endif
</div>
@endsection
