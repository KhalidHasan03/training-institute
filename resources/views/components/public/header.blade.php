@php
    $latestAnnouncement = \App\Models\Announcement::published()
        ->where('audience', 'all')
        ->latest('published_at')
        ->first();
    $loggedInUser = auth()->user();
@endphp

@if ($latestAnnouncement)
    <div class="relative z-50 overflow-hidden bg-gradient-to-r from-navy-900 via-brand-900 to-accent-700 px-4 py-2.5 text-center">
        <div class="pointer-events-none absolute inset-0 bg-grid-lines opacity-40"></div>
        <p class="relative mx-auto flex max-w-5xl items-center justify-center gap-2 text-sm text-white/90">
            <span class="hidden h-1.5 w-1.5 shrink-0 animate-blink rounded-full bg-accent-400 sm:inline-block"></span>
            <span class="truncate font-medium">{{ $latestAnnouncement->title }}</span>
            <a href="{{ route('public.courses') }}" class="shrink-0 font-semibold text-brand-200 underline-offset-2 hover:text-white hover:underline">
                Explore →
            </a>
        </p>
    </div>
@endif

<nav class="sticky top-0 z-40 border-b border-transparent bg-white/80 backdrop-blur-xl transition-all duration-300" x-data="{ open: false, scrolled: false }" @scroll.window="scrolled = window.scrollY > 8" :class="scrolled ? 'border-slate-200/80 bg-white/90 shadow-soft' : 'border-transparent bg-white/80'">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <a href="{{ route('public.home') }}" class="group flex items-center gap-2.5">
            <span class="relative grid h-10 w-10 place-items-center overflow-hidden rounded-xl bg-gradient-to-br from-brand-600 via-brand-700 to-accent-600 text-white shadow-glow transition-transform duration-200 group-hover:scale-105">
                <span class="absolute inset-0 animate-shine bg-gradient-to-r from-transparent via-white/30 to-transparent"></span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="relative h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                </svg>
            </span>
            <span class="font-display text-lg font-extrabold tracking-tight">
                <span class="text-navy-900">{{ strtok(config('app.name'), ' ') }}</span>
                <span class="text-gradient">{{ strstr(config('app.name'), ' ') ?: 'Institute' }}</span>
            </span>
        </a>

        <div class="hidden items-center gap-7 lg:flex">
            <x-public.nav-link href="{{ route('public.home') }}" :active="request()->routeIs('public.home')">Home</x-public.nav-link>
            <x-public.nav-link href="{{ route('public.courses') }}" :active="request()->routeIs('public.courses*')">Courses</x-public.nav-link>
            <x-public.nav-link href="{{ route('public.trainers') }}" :active="request()->routeIs('public.trainers')">Trainers</x-public.nav-link>
            <x-public.nav-link href="{{ route('public.about') }}" :active="request()->routeIs('public.about')">About</x-public.nav-link>
            <x-public.nav-link href="{{ route('public.contact') }}" :active="request()->routeIs('public.contact')">Contact</x-public.nav-link>
        </div>

        <div class="hidden items-center gap-3 lg:flex">
            <button
                type="button"
                onclick="toggleTheme()"
                class="grid h-10 w-10 place-items-center rounded-xl border border-slate-200 bg-white text-slate-600 transition-colors hover:border-brand-300 hover:text-brand-600 dark:border-white/10 dark:bg-white/5 dark:text-slate-300 dark:hover:border-brand-400/40 dark:hover:text-brand-300"
                aria-label="Toggle dark mode"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="hidden h-5 w-5 dark:block"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" /></svg>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 dark:hidden"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" /></svg>
            </button>
            @auth
                @if ($loggedInUser->isStudent() && $loggedInUser->student)
                    <a href="{{ route('student.dashboard') }}" class="btn-primary">Student Portal</a>
                @elseif ($loggedInUser->isAdmin() || $loggedInUser->isTrainer())
                    <a href="{{ route('filament.admin.pages.dashboard') }}" class="btn-primary">Dashboard</a>
                @else
                    <a href="{{ route('student.dashboard') }}" class="btn-outline">My Account</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn-secondary">Sign in</a>
                <a href="{{ route('register') }}" class="btn-primary">
                    Enroll Now
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                </a>
            @endauth
        </div>

        <button @click="open = !open" class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden" aria-label="Toggle menu">
            <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
            <svg x-show="open" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div x-show="open" x-cloak class="border-t border-slate-100 bg-white px-4 py-4 lg:hidden">
        <div class="flex flex-col gap-4">
            <x-public.nav-link href="{{ route('public.home') }}">Home</x-public.nav-link>
            <x-public.nav-link href="{{ route('public.courses') }}">Courses</x-public.nav-link>
            <x-public.nav-link href="{{ route('public.trainers') }}">Trainers</x-public.nav-link>
            <x-public.nav-link href="{{ route('public.about') }}">About</x-public.nav-link>
            <x-public.nav-link href="{{ route('public.contact') }}">Contact</x-public.nav-link>
            <div class="mt-2 flex gap-3">
                @auth
                    <a href="{{ $loggedInUser->isStudent() ? route('student.dashboard') : route('filament.admin.pages.dashboard') }}" class="btn-primary flex-1">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary flex-1">Sign in</a>
                    <a href="{{ route('register') }}" class="btn-primary flex-1">Enroll Now</a>
                @endauth
            </div>
            <button
                type="button"
                onclick="toggleTheme()"
                class="flex items-center justify-center gap-2 rounded-xl border border-slate-200 py-2.5 text-sm font-medium text-slate-600 hover:text-brand-600 dark:border-white/10 dark:text-slate-300"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="hidden h-5 w-5 dark:block"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" /></svg>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 dark:hidden"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" /></svg>
                <span class="dark:hidden">Dark mode</span><span class="hidden dark:inline">Light mode</span>
            </button>
        </div>
    </div>
</nav>