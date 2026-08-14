<x-filament-widgets::widget>
    <x-filament::section heading="Recent Payments">
        @forelse ($this->getPayments() as $payment)
            <div class="flex items-center justify-between gap-4 py-2.5 @if(!$loop->last) border-b border-gray-100 @endif">
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-gray-800">{{ $payment->student->name }}</p>
                    <p class="truncate text-xs text-gray-500">
                        {{ $payment->payment_method }} · <span class="uppercase">{{ $payment->transaction_id }}</span>
                    </p>
                </div>
                <div class="shrink-0 text-right">
                    <p class="text-sm font-medium text-emerald-600">+{{ number_format($payment->amount) }} BDT</p>
                    <p class="text-xs text-gray-400">{{ $payment->payment_date->format('d M Y') }}</p>
                </div>
            </div>
        @empty
            <div class="py-8 text-center">
                <x-heroicon-o-banknotes class="mx-auto h-8 w-8 text-gray-300" />
                <p class="mt-2 text-sm text-gray-500">No payments recorded yet.</p>
            </div>
        @endforelse
    </x-filament::section>
</x-filament-widgets::widget>