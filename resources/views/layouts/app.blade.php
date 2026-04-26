<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','NeuralGuide')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { theme: { extend: { fontFamily: { tajawal: ['Tajawal','sans-serif'] } } } }
  </script>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="font-tajawal min-h-screen bg-[#07111f] text-slate-100 selection:bg-cyan-400/30">
<div class="fixed inset-0 -z-10 overflow-hidden">
  <div class="absolute -top-24 right-[-8rem] h-96 w-96 rounded-full bg-cyan-500/20 blur-3xl"></div>
  <div class="absolute top-28 left-[-8rem] h-96 w-96 rounded-full bg-fuchsia-500/10 blur-3xl"></div>
  <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(34,211,238,.12),transparent_35%),linear-gradient(rgba(255,255,255,.035)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,.035)_1px,transparent_1px)] bg-[size:100%_100%,44px_44px,44px_44px]"></div>
</div>
<nav class="sticky top-0 z-30 border-b border-white/10 bg-[#07111f]/75 backdrop-blur-xl">
  <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
    <a href="{{ route('home') }}" class="group flex items-center gap-3">
      <span class="grid h-11 w-11 place-items-center rounded-2xl bg-cyan-400 text-slate-950 shadow-lg shadow-cyan-500/20 font-black">NG</span>
      <span>
        <span class="block text-xl font-black tracking-wide">NeuralGuide</span>
        <span class="text-xs text-slate-400">دليل الشبكات العصبية الذكية</span>
      </span>
    </a>
    <div class="flex items-center gap-2 rounded-full border border-white/10 bg-white/5 p-1 text-sm">
      <a class="rounded-full px-4 py-2 text-slate-200 hover:bg-white/10" href="{{ route('architectures.index') }}">المعماريات</a>
      @auth
        <a class="rounded-full px-4 py-2 text-slate-200 hover:bg-white/10" href="{{ route('dashboard') }}">لوحتي</a>
        @if(auth()->user()->is_admin ?? false)<a class="rounded-full px-4 py-2 text-slate-200 hover:bg-white/10" href="{{ route('admin.architectures.index') }}">الإدارة</a>@endif
        <form method="POST" action="{{ route('logout') }}">@csrf <button class="rounded-full px-4 py-2 text-slate-200 hover:bg-rose-500/20">خروج</button></form>
      @else
        <a class="rounded-full bg-cyan-400 px-4 py-2 font-bold text-slate-950 hover:bg-cyan-300" href="{{ route('login') }}">دخول</a>
      @endauth
    </div>
  </div>
</nav>
<main class="mx-auto max-w-7xl px-4 py-8">@yield('content')</main>
<footer class="mt-10 border-t border-white/10 bg-white/[.03] py-8 text-center text-sm text-slate-400">اكتب فكرتك.. نختار لك أفضل دماغ صناعي يناسبها!</footer>
</body>
</html>
