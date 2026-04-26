<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.app_name') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>

    <style>
        html[dir="rtl"] body,
        html[dir="rtl"] input,
        html[dir="rtl"] textarea,
        html[dir="rtl"] button,
        html[dir="rtl"] select {
            font-family: "IBM Plex Sans Arabic", Tahoma, Arial, sans-serif;
        }

        html[dir="ltr"] body,
        html[dir="ltr"] input,
        html[dir="ltr"] textarea,
        html[dir="ltr"] button,
        html[dir="ltr"] select {
            font-family: "Inter", Arial, sans-serif;
        }

        pre,
        code {
            font-family: "JetBrains Mono", Consolas, monospace;
        }
    </style>


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">


</head>

<body class="min-h-screen bg-[#f6f8fc] text-slate-950 dark:bg-[#050816] dark:text-white">

    <div
        class="fixed inset-0 -z-10 dark:bg-[radial-gradient(circle_at_top_right,#155e75_0,transparent_35%),radial-gradient(circle_at_bottom_left,#312e81_0,transparent_30%)]">
    </div>

    <header
        class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/85 backdrop-blur-xl dark:border-white/10 dark:bg-[#07111f]/85">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">

            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-cyan-400 to-blue-600 text-sm font-black text-white shadow-lg shadow-cyan-500/20">
                    NG
                </div>
                <div>
                    <div class="text-lg font-black tracking-tight">{{ __('messages.app_name') }}</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ __('messages.tagline') }}</div>
                </div>
            </a>

            <nav class="flex items-center gap-2 text-sm font-bold">
                <a href="{{ route('architectures.index') }}"
                    class="rounded-full px-4 py-2 hover:bg-slate-100 dark:hover:bg-white/10">
                    {{ __('messages.architectures') }}
                </a>

                @auth
                    <a href="{{ route('dashboard') }}"
                        class="rounded-full px-4 py-2 hover:bg-slate-100 dark:hover:bg-white/10">
                        {{ __('messages.my_dashboard') }}
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="rounded-full px-4 py-2 hover:bg-slate-100 dark:hover:bg-white/10">
                            {{ __('messages.logout') }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="rounded-full px-4 py-2 hover:bg-slate-100 dark:hover:bg-white/10">
                        {{ __('messages.login') }}
                    </a>
                @endauth

                <a href="{{ route('locale.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}"
                    class="rounded-full border border-slate-300 bg-white px-4 py-2 hover:bg-slate-100 dark:border-white/15 dark:bg-white/5 dark:hover:bg-white/10">
                    {{ app()->getLocale() === 'ar' ? __('messages.english') : __('messages.arabic') }}
                </a>

                <button onclick="toggleTheme()"
                    class="rounded-full border border-slate-300 bg-white px-4 py-2 hover:bg-slate-100 dark:border-white/15 dark:bg-white/5 dark:hover:bg-white/10">
                    {{ __('messages.theme') }}
                </button>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-6 py-10">
        @yield('content')
    </main>

    <footer
        class="mt-16 border-t border-slate-200 py-8 text-center text-sm text-slate-500 dark:border-white/10 dark:text-slate-400">
        {{ __('messages.tagline') }}
    </footer>

    <script>
        function toggleTheme() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem(
                'theme',
                document.documentElement.classList.contains('dark') ? 'dark' : 'light'
            );
        }
    </script>

</body>

</html>