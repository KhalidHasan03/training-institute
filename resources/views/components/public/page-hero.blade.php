@props(['eyebrow', 'title', 'subtitle' => null, 'items' => [], 'statSuffixes' => []])

<section class="relative overflow-hidden bg-navy-950 pb-16 pt-16 sm:pb-20 sm:pt-20">
    <div class="pointer-events-none absolute inset-0 bg-grid-lines opacity-60"></div>
    <div class="pointer-events-none absolute -left-40 -top-32 h-80 w-80 animate-aurora rounded-full bg-gradient-to-br from-brand-600/40 to-brand-400/20 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-32 -bottom-32 h-80 w-80 animate-aurora rounded-full bg-gradient-to-br from-accent-600/40 to-brand-500/20 blur-3xl" style="animation-delay: -9s"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="eyebrow-dark reveal">{{ $eyebrow }}</span>
            <h1 class="reveal reveal-delay-1 mt-5 font-display text-3xl font-extrabold text-white sm:text-5xl">
                {!! $title !!}
            </h1>
            @if ($subtitle)
                <p class="reveal reveal-delay-2 mt-5 max-w-2xl text-lg leading-relaxed text-slate-300">{{ $subtitle }}</p>
            @endif
            @if (count($items))
                <div class="reveal reveal-delay-3 mt-8 flex flex-wrap gap-3">
                    @foreach ($items as $index => [$stat, $label])
                        <div class="rounded-2xl border border-white/10 bg-white/5 px-5 py-3 backdrop-blur">
                            <p class="font-display text-2xl font-extrabold text-gradient" data-count="{{ $stat }}" data-count-suffix="{{ $statSuffixes[$index] ?? '' }}">0</p>
                            <p class="mt-0.5 text-xs text-slate-400">{{ $label }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-brand-500/50 to-transparent"></div>
</section>