<div>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="font-display text-2xl font-bold text-slate-900">Payments</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $this->batch?->name ? 'Batch ' . $this->batch->name : 'No active batch' }}</p>
        </div>
        @if ($payments->count())
            <span class="badge badge-blue">{{ $payments->count() }} transactions</span>
        @endif
    </div>

    @if ($this->enrollment)
        <div class="mt-6 grid gap-4 sm:grid-cols-3">
            <div class="card p-5">
                <p class="text-xs text-slate-400">Final fee</p>
                <p class="mt-1 font-display text-2xl font-bold text-slate-900">৳{{ number_format($this->enrollment->final_fee) }}</p>
                <p class="mt-1 text-xs text-slate-500">After ৳{{ number_format($this->enrollment->discount) }} discount</p>
            </div>
            <div class="card p-5">
                <p class="text-xs text-slate-400">Total paid</p>
                <p class="mt-1 font-display text-2xl font-bold text-emerald-600">৳{{ number_format($this->enrollment->paid) }}</p>
                <p class="mt-1 text-xs text-slate-500">{{ $payments->count() }} payment{{ $payments->count() === 1 ? '' : 's' }} made</p>
            </div>
            <div class="card p-5">
                <p class="text-xs text-slate-400">Due</p>
                <p class="mt-1 font-display text-2xl font-bold {{ $this->enrollment->due > 0 ? 'text-red-600' : 'text-emerald-600' }}">৳{{ number_format($this->enrollment->due) }}</p>
                @if ($this->enrollment->due > 0)
                    <a href="{{ route('public.contact') }}" class="btn-primary mt-2 w-full text-xs">Pay at Office</a>
                    <p class="mt-2 text-center text-[11px] text-slate-400">Payments are recorded by our accounts team.</p>
                @else
                    <p class="mt-2 text-xs font-medium text-emerald-600">All paid up</p>
                @endif
            </div>
        </div>
    @endif

    <div class="card mt-6 overflow-hidden">
        <div class="border-b border-slate-100 px-6 py-4">
            <h3 class="font-display text-base font-bold text-slate-900">Payment History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-3 font-semibold">Date</th>
                        <th class="px-6 py-3 font-semibold">Description</th>
                        <th class="px-6 py-3 font-semibold">Method</th>
                        <th class="px-6 py-3 font-semibold">Transaction ID</th>
                        <th class="px-6 py-3 font-semibold">Amount</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($payments as $payment)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3.5 font-medium text-slate-700">{{ $payment->payment_date->format('d M Y') }}</td>
                            <td class="px-6 py-3.5 text-slate-600">{{ $payment->enrollment?->batch?->name ? 'Batch ' . $payment->enrollment->batch->name : 'Course payment' }}</td>
                            <td class="px-6 py-3.5 text-slate-600">{{ $payment->payment_method }}</td>
                            <td class="px-6 py-3.5 font-mono text-xs text-slate-500">{{ $payment->transaction_id ?? '—' }}</td>
                            <td class="px-6 py-3.5 font-semibold text-slate-800">৳{{ number_format((float) $payment->amount) }}</td>
                            <td class="px-6 py-3.5">
                                <span class="badge {{ $payment->status === 'completed' ? 'badge-green' : 'badge-amber' }}">{{ ucfirst($payment->status) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-10 text-center text-slate-400">No payment records yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
