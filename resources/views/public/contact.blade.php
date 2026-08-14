@extends('layouts.public')

@section('title', 'Contact Us')

@section('content')
    <x-public.page-hero
        :eyebrow="'Contact'"
        :title="'We\'d love to <span class=&quot;text-gradient&quot;>hear from you</span>'"
        subtitle="Questions about a course, enrollment or partnership? Reach out."
    />

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-3">
            <div class="reveal space-y-5 lg:col-span-1">
                @foreach ([
                    ['House 12, Road 5, Dhanmondi, Dhaka', 'Visit us', 'map-pin'],
                    ['+880 1234 567890', 'Call us', 'phone'],
                    ['hello@traininginstitute.com', 'Email us', 'envelope'],
                ] as [$value, $label, $icon])
                    <div class="card flex items-start gap-4 p-5 transition-all duration-200 hover:border-brand-300/70 hover:shadow-soft dark:hover:border-brand-400/40">
                        <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-brand-600 to-accent-600 text-white shadow-glow">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                                @if ($icon === 'map-pin')
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                @elseif ($icon === 'phone')
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                @endif
                            </svg>
                        </span>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-widest text-brand-600 dark:text-brand-400">{{ $label }}</p>
                            <p class="mt-1 font-medium text-navy-900 dark:text-white">{{ $value }}</p>
                        </div>
                    </div>
                @endforeach
                <div class="relative overflow-hidden rounded-3xl bg-brand-gradient p-7 text-white shadow-glow-lg">
                    <div class="pointer-events-none absolute inset-0 bg-grid-lines opacity-50"></div>
                    <div class="pointer-events-none absolute -right-10 -top-10 h-32 w-32 rounded-full bg-white/10 blur-2xl"></div>
                    <div class="relative">
                        <h3 class="font-display text-lg font-bold">Office hours</h3>
                        <p class="mt-2 text-sm text-brand-100">Saturday – Thursday</p>
                        <p class="text-sm text-brand-100">9:00 AM – 8:00 PM</p>
                    </div>
                </div>
            </div>

            <div class="reveal reveal-delay-1 lg:col-span-2">
                <div class="card !rounded-3xl p-8 shadow-lift lg:p-10">
                    @if (session('success'))
                        <div class="mb-6 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 ring-1 ring-emerald-600/20">
                            {{ session('success') }}
                        </div>
                    @endif

                    <h2 class="font-display text-2xl font-bold text-navy-900 dark:text-white">Send us a message</h2>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">We usually respond within one business day.</p>

                    <form method="POST" action="{{ route('public.contact.store') }}" class="mt-8 grid gap-5 sm:grid-cols-2">
                        @csrf
                        <div>
                            <label class="label" for="name">Name</label>
                            <input class="input" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label" for="email">Email</label>
                            <input class="input" id="email" name="email" type="email" value="{{ old('email') }}" required>
                            @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="label" for="subject">Subject</label>
                            <input class="input" id="subject" name="subject" value="{{ old('subject') }}" required>
                            @error('subject') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="label" for="message">Message</label>
                            <textarea class="input" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                            @error('message') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <button type="submit" class="btn-primary group gap-2 px-7 py-3">
                                Send Message
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" /></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection