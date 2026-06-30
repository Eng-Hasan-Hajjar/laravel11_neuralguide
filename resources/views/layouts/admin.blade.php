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
        .sidebar-link { @apply flex items-center gap-3 px-4 py-2.5 rounded-xl text-slate-300 hover:bg-white/10 hover:text-white transition-all text-sm font-medium; }
        .sidebar-link.active { @apply bg-cyan-600/20 text-cyan-400 border border-cyan-500/30; }
        pre, code { font-family: 'Courier New', monospace; direction: ltr; }
    </style>
    @stack('head')
</head>
<body class="bg-slate-950 text-white min-h-screen flex">

{{-- ═══ Sidebar ═══ --}}
<aside class="w-64 shrink-0 bg-slate-900 border-l border-white/10 flex flex-col min-h-screen sticky top-0">
    {{-- Logo --}}
    <div class="px-6 py-5 border-b border-white/10">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <div class="w-8 h-8 bg-gradient-to-br from-cyan-500 to-purple-600 rounded-lg flex items-center justify-center text-xs font-black">NG</div>
            <span class="font-black text-white">NeuralGuide</span>
            <span class="text-xs bg-cyan-600/20 text-cyan-400 border border-cyan-500/30 px-2 py-0.5 rounded-full">Admin</span>
        </a>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest px-4 mb-3">الرئيسية</p>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-gauge-high w-4"></i> لوحة التحكم
        </a>

        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest px-4 mb-3 mt-5">المحتوى</p>
        <a href="{{ route('admin.architectures.index') }}" class="sidebar-link {{ request()->routeIs('admin.architectures.*') ? 'active' : '' }}">
            <i class="fa-solid fa-brain w-4"></i> المعماريات
        </a>
        <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="fa-solid fa-tags w-4"></i> الفئات
        </a>

        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest px-4 mb-3 mt-5">المستخدمون</p>
        <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fa-solid fa-users w-4"></i> المستخدمون
        </a>

        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest px-4 mb-3 mt-5">منصة التدريب</p>
        <a href="{{ route('training.index') }}" class="sidebar-link">
            <i class="fa-solid fa-flask w-4"></i> تجارب التدريب
        </a>
        <a href="{{ route('training.datasets.index') }}" class="sidebar-link">
            <i class="fa-solid fa-database w-4"></i> مجموعات البيانات
        </a>
    </nav>

    {{-- User --}}
    <div class="px-4 py-4 border-t border-white/10">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-gradient-to-br from-cyan-500 to-purple-600 rounded-full flex items-center justify-center text-xs font-black">
                {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold truncate">{{ auth()->user()->name ?? '' }}</p>
                <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email ?? '' }}</p>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="mt-3">
            @csrf
            <button class="w-full text-sm text-slate-400 hover:text-red-400 transition flex items-center gap-2 px-2">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> تسجيل الخروج
            </button>
        </form>
    </div>
</aside>

{{-- ═══ Main Content ═══ --}}
<div class="flex-1 flex flex-col min-h-screen">
    {{-- Top bar --}}
    <header class="h-16 bg-slate-900/50 backdrop-blur border-b border-white/10 flex items-center px-8 gap-4 sticky top-0 z-10">
        <div class="flex-1">
            <h1 class="text-lg font-bold">@yield('title', 'لوحة التحكم')</h1>
        </div>
        <a href="{{ route('home') }}" target="_blank" class="text-slate-400 hover:text-white text-sm flex items-center gap-2">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> عرض الموقع
        </a>
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
