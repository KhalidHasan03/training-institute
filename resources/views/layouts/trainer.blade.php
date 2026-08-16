<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Trainer Portal' }} — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-100 font-sans" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen">

        {{-- Mobile backdrop --}}
        <div x-show="sidebarOpen" x-cloak
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-navy-950/50 backdrop-blur-sm lg:hidden"></div>

        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full transform flex-col bg-navy-950 shadow-2xl transition-transform duration-200 lg:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

            {{-- Brand header --}}
            <div class="relative flex h-16 shrink-0 items-center justify-between border-b border-white/10 px-5">
                <div class="pointer-events-none absolute inset-0 bg-grid-lines opacity-40"></div>
                <div class="relative flex w-full items-center justify-between">
                    <a href="{{ route('trainer.dashboard') }}" class="flex items-center gap-2">
                        <span class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-brand-600 to-accent-600 text-white shadow-glow">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                            </svg>
                        </span>
                        <span class="font-display text-sm font-extrabold text-white">
                            {{ strtok(config('app.name'), ' ') }}
                            <span class="text-gradient">{{ strstr(config('app.name'), ' ') ?: 'Panel' }}</span>
                        </span>
                    </a>
                    <button @click="sidebarOpen = false" class="-mr-1 rounded-lg p-1.5 text-slate-400 transition-colors hover:bg-white/10 hover:text-white lg:hidden" aria-label="Close menu">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 overflow-y-auto px-3 py-5">
                <p class="px-3 pb-2 text-[10px] font-semibold uppercase tracking-widest text-slate-500">Management</p>
                <div class="space-y-1">
                    <x-trainer.nav-link href="{{ route('trainer.dashboard') }}" :active="request()->routeIs('trainer.dashboard')" icon="home">Dashboard</x-trainer.nav-link>
                    <x-trainer.nav-link href="{{ route('trainer.batches') }}" :active="request()->routeIs('trainer.batches')" icon="inbox">Batches</x-trainer.nav-link>
                    <x-trainer.nav-link href="{{ route('trainer.sessions') }}" :active="request()->routeIs('trainer.sessions')" icon="calendar">Class Sessions</x-trainer.nav-link>
                    <x-trainer.nav-link href="{{ route('trainer.attendance') }}" :active="request()->routeIs('trainer.attendance')" icon="clipboard">Attendance</x-trainer.nav-link>
                    <x-trainer.nav-link href="{{ route('trainer.exams') }}" :active="request()->routeIs('trainer.exams')" icon="chart">Exams</x-trainer.nav-link>
                    <x-trainer.nav-link href="{{ route('trainer.students') }}" :active="request()->routeIs('trainer.students')" icon="user">Students</x-trainer.nav-link>
                    <x-trainer.nav-link href="{{ route('trainer.profile') }}" :active="request()->routeIs('trainer.profile')" icon="settings">Profile</x-trainer.nav-link>
                </div>
            </nav>

            {{-- Footer actions --}}
            <div class="shrink-0 space-y-1 border-t border-white/10 p-4">
                <p class="px-3 pb-1 text-[10px] font-semibold uppercase tracking-widest text-slate-500">Account</p>
                <a href="{{ route('public.home') }}" class="flex items-center gap-2 rounded-xl px-3 py-2.5 text-sm text-slate-400 transition-colors hover:bg-white/10 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75" /></svg>
                    Visit Website
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-2 rounded-xl px-3 py-2.5 text-sm text-slate-400 transition-colors hover:bg-red-500/10 hover:text-red-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" /></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main --}}
        <div class="flex min-h-screen flex-col lg:pl-64">

            {{-- Top bar --}}
            <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-slate-200 bg-white/90 px-4 backdrop-blur sm:px-6">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = true" class="-ml-1 rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden" aria-label="Open menu">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
                    </button>
                    <h2 class="hidden font-display text-base font-bold text-slate-900 sm:block">Trainer Portal</h2>
                </div>

                <div class="flex items-center gap-3">
                    @include('partials.trainer.batch-switcher')
                </div>

                <div class="relative" x-data="{ userMenu: false }">
                    <button @click="userMenu = !userMenu" class="flex items-center gap-3 rounded-xl p-1.5 pr-3 hover:bg-slate-100">
                        <span class="grid h-9 w-9 place-items-center rounded-full bg-gradient-to-br from-brand-500 to-accent-600 text-sm font-bold text-white shadow-glow">
                            {{ strtoupper(substr(auth()->user()->trainer?->name ?? auth()->user()->name, 0, 1)) }}
                        </span>
                        <span class="hidden text-left sm:block">
                            <span class="block max-w-[10rem] truncate text-sm font-semibold text-slate-800">{{ auth()->user()->trainer?->name ?? auth()->user()->name }}</span>
                            <span class="block text-xs text-slate-400">{{ auth()->user()->trainer?->expertise ?? 'Trainer' }}</span>
                        </span>
                    </button>
                    <div x-show="userMenu" x-cloak @click.outside="userMenu = false" class="absolute right-0 mt-2 w-56 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-lift">
                        <a href="{{ route('trainer.profile') }}" class="flex items-center gap-2 px-4 py-3 text-sm text-slate-700 hover:bg-slate-50">My Profile</a>
                        <a href="{{ route('public.home') }}" class="flex items-center gap-2 px-4 py-3 text-sm text-slate-700 hover:bg-slate-50">Visit Website</a>
                        <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100">
                            @csrf
                            <button type="submit" class="flex w-full items-center px-4 py-3 text-left text-sm text-red-600 hover:bg-red-50">Logout</button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>