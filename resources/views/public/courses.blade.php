@extends('layouts.public')

@section('title', 'Courses')

@section('content')
    <x-public.page-hero
        :eyebrow="'Our Programs'"
        :title="'Browse <span class=&quot;text-gradient&quot;>all courses</span>'"
        subtitle="Choose a program, pick a batch that fits your schedule, and start learning today."
    />

    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        @if ($courses->count())
            <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($courses as $course)
                    <div class="reveal reveal-delay-{{ min($loop->iteration % 4, 4) }}">
                        <x-public.course-card :course="$course" />
                    </div>
                @endforeach
            </div>
            <div class="mt-12">
                {{ $courses->links() }}
            </div>
        @else
            <div class="reveal card mx-auto max-w-md p-10 text-center !rounded-3xl">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor" class="mx-auto h-12 w-12 text-slate-300">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
                <h2 class="mt-4 font-display text-lg font-bold text-navy-900 dark:text-white">No courses yet</h2>
                <p class="mt-2 text-sm text-slate-500">New programs are being added. Check back soon.</p>
            </div>
        @endif
    </section>
@endsection