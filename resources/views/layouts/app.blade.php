<!DOCTYPE html>
<html
    lang="{{ app()->getLocale() }}"
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
    class="{{ Cookie::get('theme','dark') === 'dark' ? 'dark' : '' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('messages.app_name')) — {{ __('messages.app_name') }}</title>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['IBM Plex Sans Arabic', 'Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'Consolas', 'monospace'],
                    }
                }
            }
        }
    </script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700;800;900&family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Highlight.js for code blocks --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">

    <style>
        body { font-family: 'IBM Plex Sans Arabic', 'Inter', sans-serif; }
        code, pre { font-family: 'JetBrains Mono', Consolas, monospace; }
        pre code.hljs { border-radius: 0.75rem; }
        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 9999px; }
        /* Transitions */
        * { transition-property: background-color, border-color, color; transition-duration: 150ms; }
    </style>

    @stack('head')
</head>

<body class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-white">

    {{-- ══ Background gradient (dark mode) ══ --}}
    <div class="pointer-events-none fixed inset-0 -z-10 dark:bg-[radial-gradient(ellipse_at_top_right,rgba(6,182,212,.08)_0,transparent_50%),radial-gradient(ellipse_at_bottom_left,rgba(99,102,241,.08)_0,transparent_50%)]"></div>

    {{-- ══ Navbar ══ --}}
    <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/90 backdrop-blur-xl dark:border-white/10 dark:bg-slate-950/90">
        <div class="mx-auto flex max-w-7xl items-center gap-4 px-4 py-3 sm:px-6">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2.5">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 text-xs font-black text-white shadow-lg shadow-cyan-500/25">
                    NG
                </div>
                <div class="hidden sm:block">
                    <p class="text-sm font-black leading-none tracking-tight">{{ __('messages.app_name') }}</p>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400">{{ __('messages.tagline') }}</p>
                </div>
            </a>

            {{-- Nav Links --}}
            <nav class="flex flex-1 items-center gap-1 text-sm font-semibold">
                <a href="{{ route('architectures.index') }}"
                   class="rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-white/10 dark:hover:text-white {{ request()->routeIs('architectures.*') ? 'bg-slate-100 text-slate-900 dark:bg-white/10 dark:text-white' : '' }}">
                    <i class="fa-solid fa-brain me-1 text-cyan-500"></i>
                    {{ __('messages.architectures') }}
                </a>

                @auth
                <a href="{{ route('training.index') }}"
                   class="rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-white/10 dark:hover:text-white {{ request()->routeIs('training.*') ? 'bg-slate-100 text-slate-900 dark:bg-white/10 dark:text-white' : '' }}">
                    <i class="fa-solid fa-flask me-1 text-purple-500"></i>
                    تجارب التدريب
                </a>
                @endauth
            </nav>

            {{-- Right Actions --}}
            <div class="flex shrink-0 items-center gap-2">

                {{-- Admin link --}}
                @auth
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}"
                   class="hidden rounded-lg border border-purple-500/30 bg-purple-500/10 px-3 py-1.5 text-xs font-bold text-purple-600 hover:bg-purple-500/20 dark:text-purple-400 sm:flex items-center gap-1">
                    <i class="fa-solid fa-shield-halved"></i> Admin
                </a>
                @endif
                @endauth

                {{-- Language switcher --}}
                <a href="{{ route('locale.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}"
                   class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs font-bold text-slate-600 hover:border-slate-300 dark:border-white/10 dark:bg-white/5 dark:text-slate-300 dark:hover:border-white/20">
                    {{ app()->getLocale() === 'ar' ? 'EN' : 'ع' }}
                </a>

                {{-- Theme toggle --}}
                <button onclick="toggleTheme()" id="themeBtn"
                        class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:border-slate-300 dark:border-white/10 dark:bg-white/5 dark:text-slate-300 dark:hover:border-white/20">
                    <i class="fa-solid fa-moon dark:hidden text-xs"></i>
                    <i class="fa-solid fa-sun hidden dark:inline text-xs"></i>
                </button>

                {{-- Auth --}}
                @auth
                <div class="relative group">
                    <button class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 text-xs font-black text-white">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </button>
                    <div class="absolute end-0 top-full mt-2 w-48 rounded-2xl border border-slate-200 bg-white p-1 shadow-xl dark:border-white/10 dark:bg-slate-900 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-white/10">
                            <i class="fa-solid fa-gauge w-4 text-center"></i> {{ __('messages.my_dashboard') }}
                        </a>
                        @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-white/10">
                            <i class="fa-solid fa-shield-halved w-4 text-center"></i> لوحة الإدارة
                        </a>
                        @endif
                        <div class="my-1 border-t border-slate-100 dark:border-white/10"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10">
                                <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i> {{ __('messages.logout') }}
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}"
                   class="rounded-lg bg-cyan-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-cyan-500 transition-colors">
                    {{ __('messages.login') }}
                </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- ══ Flash Messages ══ --}}
    @if(session('status') || session('success') || session('error') || $errors->any())
    <div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6" x-data="{ show: true }" x-show="show">
        @if(session('status') || session('success'))
        <div class="mb-3 flex items-center gap-3 rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-400">
            <i class="fa-solid fa-circle-check shrink-0"></i>
            {{ session('status') ?? session('success') }}
            <button onclick="this.closest('div').remove()" class="ms-auto"><i class="fa-solid fa-xmark"></i></button>
        </div>
        @endif
        @if(session('error'))
        <div class="mb-3 flex items-center gap-3 rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-700 dark:text-red-400">
            <i class="fa-solid fa-circle-exclamation shrink-0"></i>
            {{ session('error') }}
            <button onclick="this.closest('div').remove()" class="ms-auto"><i class="fa-solid fa-xmark"></i></button>
        </div>
        @endif
        @if($errors->any())
        <div class="mb-3 rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-700 dark:text-red-400">
            <div class="flex items-center gap-2 font-bold mb-1"><i class="fa-solid fa-triangle-exclamation"></i> يوجد أخطاء في النموذج:</div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif
    </div>
    @endif

    {{-- ══ Main Content ══ --}}
    <main>
        @yield('content')
    </main>

    {{-- ══ Footer ══ --}}
    <footer class="mt-20 border-t border-slate-200 dark:border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6">
            <div class="flex flex-col items-center gap-4 text-center sm:flex-row sm:justify-between sm:text-start">
                <div>
                    <p class="font-black text-slate-800 dark:text-white">{{ __('messages.app_name') }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('messages.tagline') }}</p>
                </div>
                <p class="text-xs text-slate-400">© {{ date('Y') }} NeuralGuide — جامعة حلب</p>
            </div>
        </div>
    </footer>

    {{-- ══ Scripts ══ --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/python.min.js"></script>
    <script>
        // Theme
        function toggleTheme() {
            const html = document.documentElement;
            const isDark = html.classList.toggle('dark');
            document.cookie = `theme=${isDark ? 'dark' : 'light'};path=/;max-age=31536000`;
        }

        // Highlight.js
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('pre code').forEach(el => hljs.highlightElement(el));
        });
    </script>
    @stack('scripts')
</body>
</html>
