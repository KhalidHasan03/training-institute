@php
    $footerCourses = \App\Models\Course::where('status', 'active')
        ->orderBy('title')
        ->take(6)
        ->get(['slug', 'title']);
@endphp

<footer class="relative overflow-hidden bg-navy-950 text-slate-300">
    <div class="pointer-events-none absolute inset-0 bg-grid-lines opacity-60"></div>
    <div class="pointer-events-none absolute -left-40 top-0 h-96 w-96 rounded-full bg-brand-600/20 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-32 bottom-0 h-80 w-80 rounded-full bg-accent-600/20 blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
            <div class="lg:col-span-1">
                <a href="{{ route('public.home') }}" class="flex items-center gap-2.5">
                    <span class="grid h-10 w-10 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-accent-600 text-white shadow-glow">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                        </svg>
                    </span>
                    <span class="font-display text-lg font-extrabold text-white">
                        {{ strtok(config('app.name'), ' ') }}
                        <span class="text-gradient">{{ strstr(config('app.name'), ' ') ?: 'Institute' }}</span>
                    </span>
                </a>
                <p class="mt-4 max-w-xs text-sm text-slate-400">
                    Premium instructor-led training that turns ambition into career-ready skills. Learn by building real projects.
                </p>
                <div class="mt-5 flex gap-3">
                    @foreach (['facebook', 'linkedin', 'youtube'] as $social)
                        <a href="#" aria-label="{{ ucfirst($social) }}" class="grid h-9 w-9 place-items-center rounded-lg border border-white/10 bg-white/5 text-slate-400 transition-colors hover:border-brand-500/50 hover:bg-brand-600/20 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4.5 w-4.5">
                                @if ($social === 'facebook')
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21C7.029 21 3 16.971 3 12S7.029 3 12 3s9 4.029 9 9-4.029 9-9 9Zm2.25-9.75v-1.5a1.5 1.5 0 0 1 3 0M7.5 12a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z" />
                                @elseif ($social === 'linkedin')
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 15V9m3.75 6v-3.75a1.5 1.5 0 0 1 3 0V15M7.5 6.75h.008v.008H7.5V6.75Z" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21c0-3 2-4.5 4.5-4.5V21m-9 0v-4.5A2.25 2.25 0 0 1 9.75 14.25h.75" />
                                @endif
                            </svg>
                        </a>
                    @endforeach
                </div>
            </div>

            <div>
                <h4 class="font-display text-sm font-bold uppercase tracking-widest text-white">Explore</h4>
                <ul class="mt-4 space-y-3 text-sm">
                    <li><a href="{{ route('public.courses') }}" class="transition-colors hover:text-white">Courses</a></li>
                    <li><a href="{{ route('public.trainers') }}" class="transition-colors hover:text-white">Trainers</a></li>
                    <li><a href="{{ route('public.about') }}" class="transition-colors hover:text-white">About Us</a></li>
                    <li><a href="{{ route('public.contact') }}" class="transition-colors hover:text-white">Contact</a></li>
                    <li><a href="{{ route('public.certificates.verify') }}" class="transition-colors hover:text-white">Verify Certificate</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-display text-sm font-bold uppercase tracking-widest text-white">Programs</h4>
                @if ($footerCourses->count())
                    <ul class="mt-4 space-y-3 text-sm">
                        @foreach ($footerCourses as $fc)
                            <li>
                                <a href="{{ route('public.courses.show', $fc->slug) }}" class="inline-flex items-center gap-1.5 transition-colors hover:text-white">
                                    <span class="h-1 w-1 rounded-full bg-brand-400"></span>
                                    {{ $fc->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <ul class="mt-4 space-y-3 text-sm text-slate-400">
                        <li>Programs coming soon</li>
                    </ul>
                @endif
            </div>

            <div>
                <h4 class="font-display text-sm font-bold uppercase tracking-widest text-white">Contact</h4>
                <ul class="mt-4 space-y-3 text-sm text-slate-400">
                    <li class="flex items-start gap-2.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="mt-0.5 h-4 w-4 shrink-0 text-brand-400"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                        House 12, Road 5, Dhanmondi, Dhaka
                    </li>
                    <li>
                        <a href="tel:+8801234567890" class="flex items-center gap-2.5 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4 shrink-0 text-brand-400"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" /></svg>
                            +880 1234 567890
                        </a>
                    </li>
                    <li>
                        <a href="mailto:hello@traininginstitute.com" class="flex items-center gap-2.5 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4 shrink-0 text-brand-400"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                            hello@traininginstitute.com
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-6 text-sm text-slate-500 md:flex-row">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <div class="flex gap-6">
                <a href="{{ route('public.about') }}" class="hover:text-slate-300">Privacy</a>
                <a href="{{ route('public.about') }}" class="hover:text-slate-300">Terms</a>
            </div>
        </div>
    </div>
</footer>