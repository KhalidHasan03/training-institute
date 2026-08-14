@props(['title', 'description', 'action' => null])

<div class="card flex flex-col items-center justify-center p-12 text-center">
    <span class="grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 text-slate-400">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z" />
        </svg>
    </span>
    <h3 class="mt-4 font-display text-lg font-bold text-slate-900">{{ $title }}</h3>
    <p class="mt-2 max-w-sm text-sm text-slate-500">{{ $description }}</p>
    @if ($slot->isNotEmpty())
        <div class="mt-5">{{ $slot }}</div>
    @elseif ($action)
        <div class="mt-5">{{ $action }}</div>
    @endif
</div>