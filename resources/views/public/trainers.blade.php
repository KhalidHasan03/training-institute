@extends('layouts.public')

@section('title', 'Trainers')

@section('content')
    <x-public.page-hero
        :eyebrow="'Our Experts'"
        :title="'Meet your <span class=&quot;text-gradient&quot;>trainers</span>'"
        subtitle="Learn from senior practitioners who work on real products every day."
    />

    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        @if ($trainers->count())
            <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($trainers as $trainer)
                    <div class="reveal reveal-delay-{{ min($loop->iteration % 4, 4) }} group card overflow-hidden !rounded-3xl transition-all duration-300 hover:-translate-y-1.5 hover:border-brand-300/70 hover:shadow-glow dark:hover:border-brand-400/40">
                        <div class="relative aspect-square overflow-hidden bg-gradient-to-br from-navy-900 via-brand-900 to-accent-800">
                            @if ($trainer->photo)
                                <img src="{{ Storage::url($trainer->photo) }}" alt="{{ $trainer->name }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div class="pointer-events-none absolute inset-0 bg-grid-lines opacity-50"></div>
                                <div class="flex h-full w-full items-center justify-center">
                                    <span class="font-display text-6xl font-extrabold text-white/30 transition-colors duration-300 group-hover:text-white/50">{{ strtoupper(substr($trainer->name, 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="font-display text-lg font-bold text-navy-900 dark:text-white">{{ $trainer->name }}</h3>
                            <p class="mt-0.5 text-sm font-semibold text-gradient">{{ $trainer->expertise }}</p>
                            <p class="mt-3 text-sm leading-relaxed text-slate-500 line-clamp-3">{{ $trainer->bio }}</p>
                            <div class="mt-5 flex items-center gap-2.5 rounded-xl border border-brand-100 bg-brand-50/70 px-3.5 py-2.5 text-sm font-medium text-brand-700 dark:border-brand-400/20 dark:bg-brand-500/10 dark:text-brand-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                {{ $trainer->batches_count }} active batches
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="reveal card mx-auto max-w-md p-10 text-center !rounded-3xl">
                <h2 class="font-display text-lg font-bold text-navy-900 dark:text-white">No trainers listed yet</h2>
                <p class="mt-2 text-sm text-slate-500">The team is growing — check back soon.</p>
            </div>
        @endif
    </section>
@endsection