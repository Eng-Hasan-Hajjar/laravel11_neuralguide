@extends('layouts.app')

@section('content')

<section class="rounded-[2rem] border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/60 dark:border-white/10 dark:bg-white/5 dark:shadow-none">

    <div class="mb-8">
        <div class="mb-4 inline-flex rounded-full bg-cyan-500/10 px-4 py-2 text-sm font-black text-cyan-700 dark:text-cyan-300">
            نتيجة التحليل الذكي
        </div>

        <h1 class="text-4xl font-black">المعماريات المقترحة لمشروعك</h1>

        <p class="mt-4 leading-8 text-slate-600 dark:text-slate-300">
            {{ $suggestion->problem_text }}
        </p>

        <div class="mt-6 rounded-3xl bg-slate-50 p-5 leading-8 text-slate-700 dark:bg-[#07111f] dark:text-slate-300">
            {{ $suggestion->metadata['analysis'] ?? 'تم تحليل المشكلة بنجاح.' }}
        </div>

        <div class="mt-4 inline-flex rounded-full border border-slate-200 px-4 py-2 text-sm font-black text-slate-600 dark:border-white/10 dark:text-slate-300">
            المجال المكتشف: {{ $suggestion->detected_domain }}
        </div>
    </div>

    <div class="grid gap-6">
        @forelse($suggestion->architectures as $architecture)
            <a href="{{ route('architectures.show', $architecture) }}"
               class="group rounded-[2rem] border border-slate-200 bg-slate-50 p-6 transition hover:-translate-y-1 hover:border-cyan-400 dark:border-white/10 dark:bg-[#07111f]">

                <div class="flex flex-col justify-between gap-5 md:flex-row md:items-center">
                    <div>
                        <div class="mb-3 flex flex-wrap gap-2">
                            <span class="rounded-full bg-cyan-500/10 px-3 py-1 text-sm font-black text-cyan-700 dark:text-cyan-300">
                                الترتيب: {{ $architecture->pivot->rank }}
                            </span>

                            <span class="rounded-full bg-emerald-500/10 px-3 py-1 text-sm font-black text-emerald-700 dark:text-emerald-300">
                                الملاءمة: {{ $architecture->pivot->score }}%
                            </span>

                            <span class="rounded-full bg-slate-200 px-3 py-1 text-sm font-black text-slate-600 dark:bg-white/10 dark:text-slate-300">
                                {{ $architecture->difficulty }}
                            </span>
                        </div>

                        <h2 class="text-3xl font-black group-hover:text-cyan-600 dark:group-hover:text-cyan-300">
                            {{ $architecture->name }}
                        </h2>

                        <p class="mt-3 leading-8 text-slate-600 dark:text-slate-300">
                            {{ $architecture->short_description }}
                        </p>

                        <p class="mt-3 leading-8 text-slate-500 dark:text-slate-400">
                            {{ $architecture->pivot->reason }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-cyan-500 px-6 py-3 font-black text-white">
                        عرض التفاصيل
                    </div>
                </div>
            </a>
        @empty
            <div class="rounded-3xl border border-red-200 bg-red-50 p-6 text-red-700">
                لم يتم العثور على معماريات مناسبة. تأكد من وجود بيانات داخل جدول architectures.
            </div>
        @endforelse
    </div>

</section>

@endsection