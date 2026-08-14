<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1c1d45">
    <title>@yield('title', config('app.name')) — {{ strtok(config('app.name'), ' ') }}</title>
    <meta name="description" content="@yield('meta_description', 'Premium instructor-led training institute. Master in-demand skills with hands-on courses, real projects and industry mentors.')">
    <script>
        (function () {
            try {
                var saved = localStorage.getItem('theme');
                var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (saved === 'dark' || (!saved && prefersDark)) {
                    document.documentElement.classList.add('dark');
                }
            } catch (e) {}
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-white font-sans text-slate-800 dark:bg-navy-950 dark:text-slate-300">

    <x-public.header />

    <main>
        @yield('content')
    </main>

    <x-public.footer />

    @livewireScripts
    @stack('scripts')
</body>
</html>