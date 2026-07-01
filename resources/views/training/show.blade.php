@extends('layouts.app')
@section('title', $experiment->name)

@push('head')
<style>
    #code-editor { min-height: 400px; font-size: 13px; line-height: 1.6; }
</style>
@endpush

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6">

    {{-- Header --}}
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div>
            <a href="{{ route('training.index') }}" class="mb-3 flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white">
                <i class="fa-solid fa-arrow-right"></i> {{ __('messages.training') }}
            </a>
            <h1 class="text-3xl font-black">{{ $experiment->name }}</h1>
            <div class="mt-2 flex flex-wrap items-center gap-2">
                <span class="text-sm text-slate-500 dark:text-slate-400">{{ $experiment->architecture?->name }}</span>
                <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $experiment->framework === 'pytorch' ? 'bg-orange-500/10 text-orange-600 dark:text-orange-400' : 'bg-blue-500/10 text-blue-600 dark:text-blue-400' }}">
                    {{ strtoupper($experiment->framework) }}
                </span>
                <span class="rounded-full px-2.5 py-1 text-xs font-bold
                    {{ $experiment->status==='completed' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' :
                       ($experiment->status==='running'   ? 'bg-blue-500/10 text-blue-600 dark:text-blue-400' :
                       ($experiment->status==='failed'    ? 'bg-red-500/10 text-red-600 dark:text-red-400' :
                                                             'bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-slate-300')) }}">
                    {{ __('messages.' . $experiment->status) }}
                </span>
            </div>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('training.download', $experiment) }}"
               class="flex items-center gap-2 rounded-2xl bg-emerald-600 px-4 py-2.5 text-sm font-black text-white hover:bg-emerald-500 transition-colors">
                <i class="fa-solid fa-download"></i> تحميل .py
            </a>
            <a href="{{ route('training.edit', $experiment) }}"
               class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 dark:border-white/10 dark:bg-white/5 dark:text-slate-200 dark:hover:bg-white/10 transition-colors">
                <i class="fa-solid fa-pen"></i> تعديل
            </a>
            <form action="{{ route('training.run', $experiment) }}" method="POST">
                @csrf
                <button class="flex items-center gap-2 rounded-2xl bg-gradient-to-r from-cyan-600 to-blue-600 px-4 py-2.5 text-sm font-black text-white shadow-lg shadow-cyan-500/25 hover:from-cyan-500 hover:to-blue-500 transition-all">
                    <i class="fa-solid fa-play"></i> تشغيل
                </button>
            </form>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Code Panel --}}
        <div class="space-y-4 lg:col-span-2">

            {{-- Tabs --}}
            <div class="flex gap-1 rounded-2xl border border-slate-200 bg-slate-100 p-1 dark:border-white/10 dark:bg-white/5">
                <button onclick="switchTab('generated')" id="tab-generated"
                        class="tab-btn flex-1 rounded-xl px-4 py-2 text-sm font-bold text-slate-500 transition dark:text-slate-400">
                    <i class="fa-solid fa-wand-magic-sparkles ms-1"></i> الكود المُولَّد
                </button>
                <button onclick="switchTab('custom')" id="tab-custom"
                        class="tab-btn flex-1 rounded-xl px-4 py-2 text-sm font-bold text-slate-500 transition dark:text-slate-400">
                    <i class="fa-solid fa-code ms-1"></i> كودي المخصص
                </button>
            </div>

            {{-- Generated Code (always dark — code blocks stay dark for readability) --}}
            <div id="panel-generated" class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-950 dark:border-white/10">
                <div class="flex items-center justify-between border-b border-white/10 bg-slate-900 px-5 py-3">
                    <span class="text-sm font-bold text-slate-300">
                        <i class="fa-brands fa-python ms-1 text-yellow-400"></i> {{ $experiment->name }}.py
                    </span>
                    <button onclick="copyCode()" class="flex items-center gap-1.5 text-xs text-slate-400 hover:text-white transition-colors">
                        <i class="fa-regular fa-copy"></i> نسخ
                    </button>
                </div>
                <div class="max-h-[600px] overflow-auto p-5">
                    <pre><code id="code-display" class="language-python">{{ $experiment->generated_code }}</code></pre>
                </div>
            </div>

            {{-- Custom Code Editor --}}
            <div id="panel-custom" class="hidden overflow-hidden rounded-2xl border border-slate-200 bg-slate-950 dark:border-white/10">
                <div class="flex items-center justify-between border-b border-white/10 bg-slate-900 px-5 py-3">
                    <span class="text-sm font-bold text-slate-300">
                        <i class="fa-solid fa-pen ms-1 text-cyan-400"></i> تحرير الكود
                    </span>
                    <button onclick="saveCustomCode()" id="save-btn"
                            class="flex items-center gap-1.5 rounded-lg bg-cyan-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-cyan-500 transition-colors">
                        <i class="fa-solid fa-floppy-disk"></i> حفظ
                    </button>
                </div>
                <textarea id="code-editor"
                          class="w-full resize-none bg-transparent p-5 font-mono text-sm text-slate-200 focus:outline-none"
                          dir="ltr" spellcheck="false">{{ $experiment->custom_code ?? $experiment->generated_code }}</textarea>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">

            {{-- Hyperparameters --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-5 dark:border-white/10 dark:bg-white/5">
                <h3 class="mb-4 flex items-center gap-2 font-black text-amber-600 dark:text-amber-400">
                    <i class="fa-solid fa-sliders"></i> Hyperparameters
                </h3>
                @php $hp = $experiment->hyperparameters ?? []; @endphp
                <div class="space-y-2">
                    @foreach(['epochs'=>'Epochs','batch_size'=>'Batch Size','learning_rate'=>'Learning Rate','optimizer'=>'Optimizer','loss_function'=>'Loss'] as $key => $label)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500 dark:text-slate-400">{{ $label }}</span>
                        <span class="font-mono font-bold text-slate-800 dark:text-slate-200">{{ $hp[$key] ?? '—' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Dataset --}}
            @if($experiment->dataset)
            <div class="rounded-3xl border border-slate-200 bg-white p-5 dark:border-white/10 dark:bg-white/5">
                <h3 class="mb-3 flex items-center gap-2 font-black text-purple-600 dark:text-purple-400">
                    <i class="fa-solid fa-database"></i> مجموعة البيانات
                </h3>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $experiment->dataset->name }}</p>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $experiment->dataset->formattedSize() }}</p>
                <p class="mt-0.5 text-xs text-slate-400 dark:text-slate-500">{{ $experiment->dataset->type }}</p>
            </div>
            @endif

            {{-- Notes --}}
            @if($experiment->notes)
            <div class="rounded-3xl border border-slate-200 bg-white p-5 dark:border-white/10 dark:bg-white/5">
                <h3 class="mb-3 flex items-center gap-2 font-black text-emerald-600 dark:text-emerald-400">
                    <i class="fa-solid fa-note-sticky"></i> ملاحظات
                </h3>
                <p class="text-sm leading-relaxed text-slate-600 dark:text-slate-300">{{ $experiment->notes }}</p>
            </div>
            @endif

            {{-- Runs History --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-5 dark:border-white/10 dark:bg-white/5">
                <h3 class="mb-3 flex items-center gap-2 font-black text-blue-600 dark:text-blue-400">
                    <i class="fa-solid fa-history"></i> سجل التشغيل
                </h3>
                @if($experiment->runs->isEmpty())
                <p class="text-sm text-slate-400 dark:text-slate-500">لم يتم التشغيل بعد</p>
                @else
                <div class="space-y-2">
                    @foreach($experiment->runs->take(5) as $run)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500 dark:text-slate-400">#{{ $run->id }}</span>
                        <span class="rounded-full px-2 py-0.5 text-xs font-bold
                            {{ $run->status==='completed' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' :
                               ($run->status==='running'  ? 'bg-blue-500/10 text-blue-600 dark:text-blue-400' :
                                                             'bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-slate-300') }}">
                            {{ $run->status }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-5 dark:border-white/10 dark:bg-white/5">
                <h3 class="mb-3 font-black text-slate-500 dark:text-slate-400">إجراءات</h3>
                <div class="space-y-1">
                    <a href="{{ route('training.edit', $experiment) }}"
                       class="flex items-center gap-2 rounded-xl px-2 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-cyan-600 dark:text-slate-300 dark:hover:bg-white/5 dark:hover:text-cyan-400 transition-colors">
                        <i class="fa-solid fa-pen w-4 text-center"></i> تعديل الإعدادات
                    </a>
                    <a href="{{ route('training.download', $experiment) }}"
                       class="flex items-center gap-2 rounded-xl px-2 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-emerald-600 dark:text-slate-300 dark:hover:bg-white/5 dark:hover:text-emerald-400 transition-colors">
                        <i class="fa-solid fa-download w-4 text-center"></i> تحميل ملف Python
                    </a>
                    <form action="{{ route('training.destroy', $experiment) }}" method="POST" onsubmit="return confirm('حذف هذه التجربة نهائياً؟')">
                        @csrf @method('DELETE')
                        <button class="flex w-full items-center gap-2 rounded-xl px-2 py-2 text-start text-sm text-slate-500 hover:bg-red-50 hover:text-red-500 dark:text-slate-400 dark:hover:bg-red-500/10 transition-colors">
                            <i class="fa-solid fa-trash w-4 text-center"></i> حذف التجربة
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function switchTab(tab) {
    document.getElementById('panel-generated').classList.toggle('hidden', tab !== 'generated');
    document.getElementById('panel-custom').classList.toggle('hidden', tab !== 'custom');
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('bg-white','dark:bg-white/10','text-slate-900','dark:text-white','shadow-sm');
        b.classList.add('text-slate-500','dark:text-slate-400');
    });
    const active = document.getElementById('tab-' + tab);
    active.classList.remove('text-slate-500','dark:text-slate-400');
    active.classList.add('bg-white','dark:bg-white/10','text-slate-900','dark:text-white','shadow-sm');
}

function copyCode() {
    const code = document.getElementById('code-display').textContent;
    navigator.clipboard.writeText(code).then(() => {
        const btn = event.target.closest('button');
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-check"></i> تم النسخ';
        setTimeout(() => btn.innerHTML = original, 2000);
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
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
            body: JSON.stringify({ custom_code: code }),
        });
        btn.innerHTML = res.ok ? '<i class="fa-solid fa-check"></i> محفوظ' : '<i class="fa-solid fa-xmark"></i> خطأ';
    } catch {
        btn.innerHTML = '<i class="fa-solid fa-xmark"></i> خطأ';
    }
    setTimeout(() => { btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> حفظ'; }, 2500);
}

switchTab('generated');
</script>
@endpush
@endsection
