<x-filament-widgets::widget>
    <x-filament::section heading="Upcoming Classes">
        @forelse ($this->getSessions() as $session)
            <div class="flex items-center justify-between gap-4 py-2.5 @if(!$loop->last) border-b border-gray-100 @endif">
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-gray-800">
                        {{ $session->topic ?: $session->batch->course->title }}
                    </p>
                    <p class="truncate text-xs text-gray-500">
                        {{ $session->batch->name }} · {{ $session->batch->course->title }}
                    </p>
                </div>
                <div class="shrink-0 text-right">
                    <p class="text-sm font-medium text-gray-700">{{ $session->date->format('d M') }}</p>
                    <p class="text-xs text-gray-400">{{ $session->start_time?->format('g:i A') }}</p>
                </div>
            </div>
        @empty
            <div class="py-8 text-center">
                <x-heroicon-o-calendar-days class="mx-auto h-8 w-8 text-gray-300" />
                <p class="mt-2 text-sm text-gray-500">No upcoming classes.</p>
            </div>
        @endforelse
    </x-filament::section>
</x-filament-widgets::widget>