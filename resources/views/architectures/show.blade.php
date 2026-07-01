@extends('layouts.app')
@section('title', $architecture->name)

@section('content')
<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6">

    {{-- Breadcrumb --}}
    <nav class="mb-6 flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
        <a href="{{ route('architectures.index') }}" class="hover:text-cyan-500">{{ __('messages.architectures') }}</a>
        <i class="fa-solid fa-chevron-left text-xs"></i>
        <span class="text-slate-800 font-semibold dark:text-white">{{ $architecture->name }}</span>
    </nav>

    {{-- Header --}}
    <div class="mb-8 rounded-3xl border border-slate-200 bg-white p-8 dark:border-white/10 dark:bg-white/5">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <div class="mb-3 flex flex-wrap gap-2">
                    <span class="rounded-full border border-cyan-500/30 bg-cyan-500/10 px-3 py-1 text-xs font-bold text-cyan-600 dark:text-cyan-400">
                        {{ $architecture->difficulty }}
                    </span>
                    @foreach($architecture->categories as $cat)
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600 dark:bg-white/10 dark:text-slate-300">
                        {{ $cat->name }}
                    </span>
                    @endforeach
                    @if($architecture->year)
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500 dark:bg-white/10">
                        {{ $architecture->year }}
                    </span>
                    @endif
                </div>
                <h1 class="text-4xl font-black">{{ $architecture->name }}</h1>
                <p class="mt-2 text-lg text-slate-600 dark:text-slate-300">{{ $architecture->short_description }}</p>
            </div>

            <div class="flex flex-col gap-2 sm:flex-row">
                @if($architecture->arxiv_url || $architecture->paper_url)
                <a href="{{ $architecture->arxiv_url ?? $architecture->paper_url }}" target="_blank"
                   class="flex items-center gap-2 rounded-2xl border border-slate-200 px-4 py-2.5 text-sm font-bold hover:border-slate-300 dark:border-white/10 dark:hover:border-white/20">
                    <i class="fa-solid fa-file-lines text-red-400"></i> {{ __('messages.open_paper') }}
                </a>
                @endif
                @auth
                <form action="{{ route('favorites.toggle', $architecture) }}" method="POST">
                    @csrf
                    <button class="flex items-center gap-2 rounded-2xl border border-slate-200 px-4 py-2.5 text-sm font-bold hover:border-pink-400 hover:text-pink-500 dark:border-white/10 dark:hover:border-pink-500/40 transition-colors">
                        <i class="fa-{{ auth()->user()->favorites->contains($architecture->id) ? 'solid text-pink-500' : 'regular' }} fa-heart"></i>
                        {{ auth()->user()->favorites->contains($architecture->id) ? 'محفوظ' : 'حفظ' }}
                    </button>
                </form>
                @endauth
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">

        {{-- ── Left column (description + code) ── --}}
        <div class="space-y-6 lg:col-span-2">

            {{-- Scientific Description --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
                <h2 class="mb-4 flex items-center gap-2 text-lg font-black">
                    <i class="fa-solid fa-microscope text-cyan-500"></i>
                    {{ __('messages.scientific_description') }}
                </h2>
                <div class="prose prose-slate dark:prose-invert max-w-none text-sm leading-relaxed">
                    {!! nl2br(e($architecture->description)) !!}
                </div>
            </div>

            {{-- Best For / Limitations --}}
            @if($architecture->best_for || $architecture->limitations)
            <div class="grid gap-4 sm:grid-cols-2">
                @if($architecture->best_for)
                <div class="rounded-3xl border border-emerald-500/30 bg-emerald-500/5 p-5">
                    <h3 class="mb-3 flex items-center gap-2 font-black text-emerald-700 dark:text-emerald-400">
                        <i class="fa-solid fa-circle-check"></i> {{ __('messages.best_for') }}
                    </h3>
                    <p class="text-sm leading-relaxed text-slate-700 dark:text-slate-300">{{ $architecture->best_for }}</p>
                </div>
                @endif
                @if($architecture->limitations)
                <div class="rounded-3xl border border-amber-500/30 bg-amber-500/5 p-5">
                    <h3 class="mb-3 flex items-center gap-2 font-black text-amber-700 dark:text-amber-400">
                        <i class="fa-solid fa-triangle-exclamation"></i> {{ __('messages.limitations') }}
                    </h3>
                    <p class="text-sm leading-relaxed text-slate-700 dark:text-slate-300">{{ $architecture->limitations }}</p>
                </div>
                @endif
            </div>
            @endif

            {{-- Recommended Settings --}}
            @if($architecture->recommended_settings)
            <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
                <h2 class="mb-4 flex items-center gap-2 text-lg font-black">
                    <i class="fa-solid fa-sliders text-purple-500"></i>
                    {{ __('messages.recommended_settings') }}
                </h2>
                <pre class="rounded-2xl bg-slate-950 p-4 text-xs text-green-400 overflow-x-auto"><code>{{ $architecture->recommended_settings }}</code></pre>
            </div>
            @endif

            {{-- PyTorch Example --}}
            @if($architecture->pytorch_example)
            <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="flex items-center gap-2 text-lg font-black">
                        <i class="fa-brands fa-python text-orange-500"></i>
                        {{ __('messages.pytorch_example') }}
                    </h2>
                    @auth
                    <a href="{{ route('training.create', ['architecture_id' => $architecture->id]) }}"
                       class="rounded-xl bg-orange-500/10 border border-orange-500/30 px-3 py-1.5 text-xs font-bold text-orange-600 hover:bg-orange-500/20 dark:text-orange-400 transition-colors">
                        <i class="fa-solid fa-flask me-1"></i> ابدأ تجربة تدريب
                    </a>
                    @endauth
                </div>
                <pre class="overflow-x-auto rounded-2xl text-sm"><code class="language-python">{{ $architecture->pytorch_example }}</code></pre>
            </div>
            @endif

            {{-- TensorFlow Example --}}
            @if($architecture->tensorflow_example)
            <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
                <h2 class="mb-4 flex items-center gap-2 text-lg font-black">
                    <i class="fa-brands fa-google text-blue-500"></i>
                    {{ __('messages.tensorflow_example') }}
                </h2>
                <pre class="overflow-x-auto rounded-2xl text-sm"><code class="language-python">{{ $architecture->tensorflow_example }}</code></pre>
            </div>
            @endif

            {{-- Comments --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
                <h2 class="mb-5 flex items-center gap-2 text-lg font-black">
                    <i class="fa-solid fa-comments text-slate-400"></i>
                    التعليقات ({{ $architecture->comments->where('is_approved', true)->count() }})
                </h2>

                @auth
                <form action="{{ route('comments.store') }}" method="POST" class="mb-6">
                    @csrf
                    <input type="hidden" name="architecture_id" value="{{ $architecture->id }}">
                    <textarea name="body" rows="3" required minlength="10"
                              placeholder="أضف تعليقك أو ملاحظتك..."
                              class="w-full rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm focus:border-cyan-500 focus:outline-none dark:border-white/10 dark:bg-white/5 resize-none"></textarea>
                    <div class="mt-2 flex items-center gap-2">
                        <label class="text-xs text-slate-500">التقييم:</label>
                        @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer text-lg">
                            <input type="radio" name="rating" value="{{ $i }}" class="sr-only">
                            <span class="text-slate-300 hover:text-amber-400">★</span>
                        </label>
                        @endfor
                        <button class="ms-auto rounded-xl bg-cyan-600 px-4 py-2 text-sm font-bold text-white hover:bg-cyan-500 transition-colors">إضافة تعليق</button>
                    </div>
                </form>
                @else
                <p class="mb-5 text-sm text-slate-500">
                    <a href="{{ route('login') }}" class="text-cyan-500 font-bold hover:underline">سجّل دخولك</a>
                    لإضافة تعليق.
                </p>
                @endauth

                <div class="space-y-4">
                    @forelse($architecture->comments->where('is_approved', true) as $comment)
                    <div class="flex gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 text-xs font-black text-white">
                            {{ substr($comment->user->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold">{{ $comment->user->name }}</span>
                                @if($comment->rating)
                                <span class="text-amber-400 text-xs">{{ str_repeat('★', $comment->rating) }}</span>
                                @endif
                                <span class="text-xs text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="mt-1 text-sm leading-relaxed text-slate-700 dark:text-slate-300">{{ $comment->body }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-sm text-slate-400 py-6">لا توجد تعليقات بعد — كن أول من يعلّق!</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ── Right Sidebar ── --}}
        <div class="space-y-4">

            {{-- Meta --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-5 dark:border-white/10 dark:bg-white/5">
                <h3 class="mb-4 font-black text-sm uppercase tracking-widest text-slate-400">تفاصيل المعمارية</h3>
                <div class="space-y-3">
                    @if($architecture->data_requirement)
                    <div>
                        <p class="text-xs font-bold text-slate-400 mb-0.5">{{ __('messages.data') }}</p>
                        <p class="text-sm text-slate-700 dark:text-slate-300">{{ $architecture->data_requirement }}</p>
                    </div>
                    @endif
                    @if($architecture->compute_requirement)
                    <div>
                        <p class="text-xs font-bold text-slate-400 mb-0.5">{{ __('messages.compute') }}</p>
                        <p class="text-sm text-slate-700 dark:text-slate-300">{{ $architecture->compute_requirement }}</p>
                    </div>
                    @endif
                    @if(!empty($architecture->frameworks))
                    <div>
                        <p class="text-xs font-bold text-slate-400 mb-1.5">Frameworks</p>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($architecture->frameworks as $fw)
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600 dark:bg-white/10 dark:text-slate-300">{{ $fw }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if(!empty($architecture->tags))
                    <div>
                        <p class="text-xs font-bold text-slate-400 mb-1.5">Tags</p>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($architecture->tags as $tag)
                            <span class="rounded-full bg-cyan-500/10 px-2.5 py-1 text-xs font-semibold text-cyan-600 dark:text-cyan-400">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Training CTA --}}
            @auth
            <div class="rounded-3xl border border-cyan-500/30 bg-gradient-to-br from-cyan-500/10 to-blue-500/10 p-5">
                <h3 class="mb-2 font-black text-cyan-700 dark:text-cyan-400">
                    <i class="fa-solid fa-flask me-1"></i> دُرِّب هذه المعمارية
                </h3>
                <p class="mb-4 text-xs leading-relaxed text-slate-600 dark:text-slate-400">
                    أنشئ تجربة تدريب، ارفع بياناتك، واحصل على كود Python جاهز للتشغيل.
                </p>
                <a href="{{ route('training.create', ['architecture_id' => $architecture->id]) }}"
                   class="block rounded-2xl bg-gradient-to-r from-cyan-600 to-blue-600 py-2.5 text-center text-sm font-black text-white hover:from-cyan-500 hover:to-blue-500 transition-all">
                    ابدأ التدريب
                </a>
            </div>
            @else
            <div class="rounded-3xl border border-slate-200 bg-white p-5 text-center dark:border-white/10 dark:bg-white/5">
                <p class="mb-3 text-sm text-slate-500">سجّل دخولك لبدء تجربة التدريب</p>
                <a href="{{ route('register') }}" class="block rounded-2xl bg-cyan-600 py-2.5 text-sm font-black text-white hover:bg-cyan-500 transition-colors">
                    إنشاء حساب مجاناً
                </a>
            </div>
            @endauth
        </div>
    </div>
</div>
@endsection
