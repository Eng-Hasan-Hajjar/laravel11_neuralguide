<!DOCTYPE html>
<html lang="ar" dir="rtl" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') — NeuralGuide Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' }</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        pre, code { font-family: 'Courier New', monospace; direction: ltr; }

        /* ── Sidebar link ── */
        .side-link {
            position: relative;
            display: flex; align-items: center; gap: .75rem;
            padding: .6rem .75rem;
            border-radius: .9rem;
            color: #94a3b8; /* slate-400 */
            font-size: .875rem; font-weight: 600;
            transition: all .18s ease;
        }
        .side-link:hover {
            background: rgba(255,255,255,.06);
            color: #e2e8f0;
        }
        .side-link:hover .side-icon {
            transform: scale(1.08);
        }
        .side-link.active {
            background: linear-gradient(90deg, rgba(6,182,212,.14), rgba(6,182,212,.03));
            color: #22d3ee; /* cyan-400 */
        }
        .side-link.active::before {
            content: '';
            position: absolute;
            right: -1px; top: 50%; transform: translateY(-50%);
            width: 3px; height: 60%;
            border-radius: 9999px;
            background: linear-gradient(180deg, #22d3ee, #0891b2);
        }
        .side-icon {
            display: flex; align-items: center; justify-content: center;
            width: 30px; height: 30px; border-radius: .65rem;
            font-size: .8rem; flex-shrink: 0;
            transition: transform .18s ease;
        }
        .side-badge {
            margin-right: auto;
            font-size: .65rem; font-weight: 800;
            padding: .1rem .45rem;
            border-radius: 9999px;
            background: rgba(255,255,255,.08);
            color: #cbd5e1;
        }
        .side-link.active .side-badge {
            background: rgba(34,211,238,.15);
            color: #22d3ee;
        }
        .side-section-title {
            font-size: .68rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: .12em;
            color: #475569; /* slate-600 */
            padding: 0 .9rem;
            margin-bottom: .5rem;
        }
    </style>
    @stack('head')
</head>
<body class="bg-slate-950 text-white min-h-screen flex">

{{-- ═══ Sidebar ═══ --}}
<aside class="w-72 shrink-0 bg-slate-900/60 border-l border-white/10 flex flex-col min-h-screen sticky top-0">

    {{-- Logo --}}
    <div class="px-5 py-5 border-b border-white/10">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-purple-600 rounded-2xl flex items-center justify-center text-sm font-black shadow-lg shadow-cyan-500/20">NG</div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="font-black text-white text-base">NeuralGuide</span>
                    <span class="text-[10px] bg-cyan-600/20 text-cyan-400 border border-cyan-500/30 px-2 py-0.5 rounded-full font-bold">Admin</span>
                </div>
                <p class="text-[11px] text-slate-500 mt-0.5">لوحة التحكم الإدارية</p>
            </div>
        </a>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 px-3.5 py-5 space-y-6 overflow-y-auto">

        {{-- الرئيسية --}}
        <div>
            <p class="side-section-title">الرئيسية</p>
            <div class="space-y-0.5">
                <a href="{{ route('admin.dashboard') }}" class="side-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="side-icon bg-cyan-500/15 text-cyan-400"><i class="fa-solid fa-gauge-high"></i></span>
                    لوحة التحكم
                </a>
            </div>
        </div>

        {{-- المحتوى --}}
        <div>
            <p class="side-section-title">المحتوى</p>
            <div class="space-y-0.5">
                <a href="{{ route('admin.architectures.index') }}" class="side-link {{ request()->routeIs('admin.architectures.*') ? 'active' : '' }}">
                    <span class="side-icon bg-amber-500/15 text-amber-400"><i class="fa-solid fa-brain"></i></span>
                    المعماريات
                    @isset($stats['architectures'])<span class="side-badge">{{ $stats['architectures'] }}</span>@endisset
                </a>
                <a href="{{ route('admin.categories.index') }}" class="side-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <span class="side-icon bg-fuchsia-500/15 text-fuchsia-400"><i class="fa-solid fa-tags"></i></span>
                    الفئات
                    @isset($stats['categories'])<span class="side-badge">{{ $stats['categories'] }}</span>@endisset
                </a>
            </div>
        </div>

        {{-- المستخدمون --}}
        <div>
            <p class="side-section-title">المستخدمون</p>
            <div class="space-y-0.5">
                <a href="{{ route('admin.users.index') }}" class="side-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <span class="side-icon bg-purple-500/15 text-purple-400"><i class="fa-solid fa-users"></i></span>
                    المستخدمون
                    @isset($stats['users'])<span class="side-badge">{{ $stats['users'] }}</span>@endisset
                </a>
            </div>
        </div>

        {{-- منصة التدريب --}}
        <div>
            <p class="side-section-title">منصة التدريب</p>
            <div class="space-y-0.5">
                <a href="{{ route('training.index') }}" class="side-link {{ request()->routeIs('training.index') || request()->routeIs('training.show') || request()->routeIs('training.create') ? 'active' : '' }}">
                    <span class="side-icon bg-blue-500/15 text-blue-400"><i class="fa-solid fa-flask"></i></span>
                    تجارب التدريب
                    @isset($stats['experiments'])<span class="side-badge">{{ $stats['experiments'] }}</span>@endisset
                </a>
                <a href="{{ route('training.datasets.index') }}" class="side-link {{ request()->routeIs('training.datasets.*') ? 'active' : '' }}">
                    <span class="side-icon bg-emerald-500/15 text-emerald-400"><i class="fa-solid fa-database"></i></span>
                    مجموعات البيانات
                </a>
            </div>
        </div>
    </nav>

    {{-- User --}}
    <div class="px-4 py-4 border-t border-white/10">
        <div class="flex items-center gap-3 rounded-2xl bg-white/[.03] p-2.5">
            <div class="w-9 h-9 bg-gradient-to-br from-cyan-500 to-purple-600 rounded-full flex items-center justify-center text-xs font-black shrink-0">
                {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold truncate">{{ auth()->user()->name ?? '' }}</p>
                <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email ?? '' }}</p>
            </div>
        </div>
        <div class="mt-2 flex gap-2">
            <a href="{{ route('home') }}" target="_blank"
               class="flex-1 text-center text-xs font-bold text-slate-400 hover:text-white bg-white/[.04] hover:bg-white/10 rounded-xl py-2 transition-colors">
                <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i> الموقع
            </a>
            <form action="{{ route('logout') }}" method="POST" class="flex-1">
                @csrf
                <button class="w-full text-xs font-bold text-red-400/80 hover:text-red-400 bg-red-500/[.06] hover:bg-red-500/10 rounded-xl py-2 transition-colors">
                    <i class="fa-solid fa-arrow-right-from-bracket ms-1"></i> خروج
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- ═══ Main Content ═══ --}}
<div class="flex-1 flex flex-col min-h-screen">
    {{-- Top bar --}}
    <header class="h-16 bg-slate-900/50 backdrop-blur border-b border-white/10 flex items-center px-8 gap-4 sticky top-0 z-10">
        <div class="flex-1">
            <h1 class="text-lg font-bold">@yield('title', 'لوحة التحكم')</h1>
        </div>
    </header>

    {{-- Alerts --}}
    <div class="px-8 pt-4">
        @if(session('status'))
            <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-xl px-4 py-3 mb-4 flex items-center gap-3">
                <i class="fa-solid fa-circle-check"></i> {{ session('status') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 rounded-xl px-4 py-3 mb-4">
                <ul class="space-y-1">@foreach($errors->all() as $e)<li class="flex items-center gap-2"><i class="fa-solid fa-circle-exclamation text-xs"></i> {{ $e }}</li>@endforeach</ul>
            </div>
        @endif
    </div>

    <main class="flex-1 px-8 py-4">
        @yield('content')
    </main>

    <footer class="text-center text-slate-600 text-xs py-4">
        NeuralGuide Admin © {{ date('Y') }}
    </footer>
</div>

@stack('scripts')
</body>
</html>
