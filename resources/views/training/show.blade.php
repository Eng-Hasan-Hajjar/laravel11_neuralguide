@extends('layouts.app')
@section('title', $experiment->name)

@push('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/python.min.js"></script>
<style>
    .hljs { background: transparent; padding: 0; }
    #code-editor { min-height: 400px; font-size: 13px; line-height: 1.6; }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('training.index') }}" class="text-slate-400 hover:text-white text-sm flex items-center gap-2 mb-3">
                <i class="fa-solid fa-arrow-right"></i> تجارب التدريب
            </a>
            <h1 class="text-3xl font-black">{{ $experiment->name }}</h1>
            <div class="flex items-center gap-3 mt-2 flex-wrap">
                <span class="text-sm text-slate-400">{{ $experiment->architecture?->name }}</span>
                <span class="text-xs px-2.5 py-1 rounded-full
                    {{ $experiment->framework === 'pytorch' ? 'bg-orange-500/20 text-orange-400' : 'bg-blue-500/20 text-blue-400' }}">
                    {{ strtoupper($experiment->framework) }}
                </span>
                <span class="text-xs px-2.5 py-1 rounded-full
                    {{ $experiment->status==='completed' ? 'bg-emerald-500/20 text-emerald-400' :
                       ($experiment->status==='running'   ? 'bg-blue-500/20 text-blue-400' :
                       ($experiment->status==='failed'    ? 'bg-red-500/20 text-red-400' : 'bg-slate-700 text-slate-300')) }}">
                    {{ $experiment->statusLabel() }}
                </span>
            </div>
        </div>

        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('training.download', $experiment) }}"
               class="bg-emerald-600 hover:bg-emerald-500 px-4 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2">
                <i class="fa-solid fa-download"></i> تحميل .py
            </a>
            <a href="{{ route('training.edit', $experiment) }}"
               class="bg-slate-700 hover:bg-slate-600 px-4 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2">
                <i class="fa-solid fa-pen"></i> تعديل
            </a>
            <form action="{{ route('training.run', $experiment) }}" method="POST">
                @csrf
                <button class="bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 px-4 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-play"></i> تشغيل
                </button>
            </form>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Code Panel --}}
        <div class="lg:col-span-2 space-y-4">
            {{-- Tabs --}}
            <div class="flex gap-1 bg-slate-800/50 border border-white/10 rounded-2xl p-1">
                <button onclick="switchTab('generated')" id="tab-generated"
                        class="flex-1 py-2 px-4 rounded-xl text-sm font-bold transition tab-btn active-tab">
                    <i class="fa-solid fa-wand-magic-sparkles ml-1"></i> الكود المُولَّد
                </button>
                <button onclick="switchTab('custom')" id="tab-custom"
                        class="flex-1 py-2 px-4 rounded-xl text-sm font-bold transition tab-btn">
                    <i class="fa-solid fa-code ml-1"></i> كودي المخصص
                </button>
            </div>

            {{-- Generated Code --}}
            <div id="panel-generated" class="bg-slate-900 border border-white/10 rounded-2xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-3 bg-slate-800 border-b border-white/10">
                    <span class="text-sm font-bold text-slate-300">
                        <i class="fa-brands fa-python text-yellow-400 ml-1"></i>
                        {{ $experiment->name }}.py
                    </span>
                    <button onclick="copyCode()" class="text-xs text-slate-400 hover:text-white flex items-center gap-1.5 transition">
                        <i class="fa-regular fa-copy"></i> نسخ
                    </button>
                </div>
                <div class="overflow-auto max-h-[600px] p-5">
                    <pre><code id="code-display" class="language-python">{{ $experiment->generated_code }}</code></pre>
                </div>
            </div>

            {{-- Custom Code Editor --}}
            <div id="panel-custom" class="hidden bg-slate-900 border border-white/10 rounded-2xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-3 bg-slate-800 border-b border-white/10">
                    <span class="text-sm font-bold text-slate-300">
                        <i class="fa-solid fa-pen text-cyan-400 ml-1"></i> تحرير الكود
                    </span>
                    <button onclick="saveCustomCode()" id="save-btn"
                            class="bg-cyan-600 hover:bg-cyan-500 px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5">
                        <i class="fa-solid fa-floppy-disk"></i> حفظ
                    </button>
                </div>
                <textarea id="code-editor"
                          class="w-full bg-transparent text-slate-200 p-5 resize-none focus:outline-none font-mono text-sm"
                          dir="ltr" spellcheck="false">{{ $experiment->custom_code ?? $experiment->generated_code }}</textarea>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            {{-- Hyperparameters --}}
            <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-5">
                <h3 class="font-bold text-amber-400 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-sliders"></i> Hyperparameters
                </h3>
                @php $hp = $experiment->hyperparameters ?? []; @endphp
                <div class="space-y-2">
                    @foreach(['epochs'=>'Epochs','batch_size'=>'Batch Size','learning_rate'=>'Learning Rate','optimizer'=>'Optimizer','loss_function'=>'Loss'] as $key => $label)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-400">{{ $label }}</span>
                        <span class="font-mono font-bold text-slate-200">{{ $hp[$key] ?? '—' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Dataset --}}
            @if($experiment->dataset)
            <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-5">
                <h3 class="font-bold text-purple-400 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-database"></i> مجموعة البيانات
                </h3>
                <p class="font-medium text-sm">{{ $experiment->dataset->name }}</p>
                <p class="text-xs text-slate-400 mt-1">{{ $experiment->dataset->formattedSize() }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ $experiment->dataset->type }}</p>
            </div>
            @endif

            {{-- Notes --}}
            @if($experiment->notes)
            <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-5">
                <h3 class="font-bold text-emerald-400 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-note-sticky"></i> ملاحظات
                </h3>
                <p class="text-sm text-slate-300 leading-relaxed">{{ $experiment->notes }}</p>
            </div>
            @endif

            {{-- Runs History --}}
            <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-5">
                <h3 class="font-bold text-blue-400 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-history"></i> سجل التشغيل
                </h3>
                @if($experiment->runs->isEmpty())
                <p class="text-sm text-slate-500">لم يتم التشغيل بعد</p>
                @else
                <div class="space-y-2">
                    @foreach($experiment->runs->take(5) as $run)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-400">#{{ $run->id }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full
                            {{ $run->status==='completed' ? 'bg-emerald-500/20 text-emerald-400' :
                               ($run->status==='running'  ? 'bg-blue-500/20 text-blue-400' : 'bg-slate-700 text-slate-300') }}">
                            {{ $run->status }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-5">
                <h3 class="font-bold mb-3 text-slate-300">إجراءات</h3>
                <div class="space-y-2">
                    <a href="{{ route('training.edit', $experiment) }}"
                       class="flex items-center gap-2 text-sm text-slate-300 hover:text-cyan-400 transition py-1">
                        <i class="fa-solid fa-pen w-4"></i> تعديل الإعدادات
                    </a>
                    <a href="{{ route('training.download', $experiment) }}"
                       class="flex items-center gap-2 text-sm text-slate-300 hover:text-emerald-400 transition py-1">
                        <i class="fa-solid fa-download w-4"></i> تحميل ملف Python
                    </a>
                    <form action="{{ route('training.destroy', $experiment) }}" method="POST"
                          onsubmit="return confirm('حذف هذه التجربة نهائياً؟')">
                        @csrf @method('DELETE')
                        <button class="flex items-center gap-2 text-sm text-slate-400 hover:text-red-400 transition py-1 w-full text-right">
                            <i class="fa-solid fa-trash w-4"></i> حذف التجربة
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
hljs.highlightAll();

function switchTab(tab) {
    document.getElementById('panel-generated').classList.toggle('hidden', tab !== 'generated');
    document.getElementById('panel-custom').classList.toggle('hidden', tab !== 'custom');
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('bg-slate-700', 'text-white'));
    document.getElementById('tab-' + tab).classList.add('bg-slate-700', 'text-white');
}

function copyCode() {
    const code = document.getElementById('code-display').textContent;
    navigator.clipboard.writeText(code).then(() => {
        const btn = event.target.closest('button');
        btn.innerHTML = '<i class="fa-solid fa-check"></i> تم النسخ';
        setTimeout(() => btn.innerHTML = '<i class="fa-regular fa-copy"></i> نسخ', 2000);
    });
}

async function saveCustomCode() {
    const btn = document.getElementById('save-btn');
    const code = document.getElementById('code-editor').value;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جارٍ الحفظ...';

    try {
        const res = await fetch("{{ route('training.update-code', $experiment) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            },
            body: JSON.stringify({ custom_code: code }),
        });
        if (res.ok) {
            btn.innerHTML = '<i class="fa-solid fa-check"></i> محفوظ';
        } else {
            btn.innerHTML = '<i class="fa-solid fa-xmark"></i> خطأ';
        }
    } catch {
        btn.innerHTML = '<i class="fa-solid fa-xmark"></i> خطأ';
    }

    setTimeout(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> حفظ';
    }, 2500);
}

// Init tab
document.getElementById('tab-generated').classList.add('bg-slate-700', 'text-white');
</script>
@endpush
@endsection
