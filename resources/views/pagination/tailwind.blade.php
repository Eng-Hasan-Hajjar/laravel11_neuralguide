@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="mt-12">
        <div class="rounded-[2rem] border border-slate-200 bg-white/90 p-5 shadow-xl shadow-slate-200/60 backdrop-blur-xl dark:border-white/10 dark:bg-white/5 dark:shadow-none">

            <div class="mb-5 text-center text-sm font-bold text-slate-500 dark:text-slate-400">
                @if(app()->getLocale() === 'ar')
                    عرض {{ $paginator->firstItem() }} إلى {{ $paginator->lastItem() }} من أصل {{ $paginator->total() }} نتيجة
                @else
                    Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
                @endif
            </div>

            <div class="flex flex-wrap items-center justify-center gap-2">

                @if ($paginator->onFirstPage())
                    <span class="cursor-not-allowed rounded-2xl border border-slate-200 px-4 py-2 text-sm font-black text-slate-400 dark:border-white/10">
                        {{ __('pagination.previous') }}
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}"
                       class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-black text-slate-700 transition hover:border-cyan-400 hover:text-cyan-600 dark:border-white/10 dark:bg-white/5 dark:text-white dark:hover:text-cyan-300">
                        {{ __('pagination.previous') }}
                    </a>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="px-3 py-2 text-slate-400">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="rounded-2xl bg-cyan-500 px-4 py-2 text-sm font-black text-white shadow-lg shadow-cyan-500/25">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}"
                                   class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-black text-slate-700 transition hover:border-cyan-400 hover:text-cyan-600 dark:border-white/10 dark:bg-white/5 dark:text-white dark:hover:text-cyan-300">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}"
                       class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-black text-slate-700 transition hover:border-cyan-400 hover:text-cyan-600 dark:border-white/10 dark:bg-white/5 dark:text-white dark:hover:text-cyan-300">
                        {{ __('pagination.next') }}
                    </a>
                @else
                    <span class="cursor-not-allowed rounded-2xl border border-slate-200 px-4 py-2 text-sm font-black text-slate-400 dark:border-white/10">
                        {{ __('pagination.next') }}
                    </span>
                @endif

            </div>
        </div>
    </nav>
@endif