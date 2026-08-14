<div>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="font-display text-2xl font-bold text-slate-900">Materials</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $this->batch?->name ? 'Batch ' . $this->batch->name : 'No active batch' }}</p>
        </div>
        @if ($this->batch)
            <span class="badge badge-blue">{{ $materials->count() }} items</span>
        @endif
    </div>

    @if (! $this->batch)
        <div class="mt-6">
            <x-student.empty-state title="No active enrollment" description="Enroll in a course to access learning materials.">
                <a href="{{ route('public.courses') }}" class="btn-primary">Browse Courses</a>
            </x-student.empty-state>
        </div>
    @else
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($materials as $material)
                <div class="card p-5">
                    <div class="flex items-start gap-3">
                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-brand-50 text-brand-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                                @if ($material->type === 'video')
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                                @elseif ($material->type === 'archive')
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                @endif
                            </svg>
                        </span>
                        <div class="min-w-0">
                            <h4 class="text-sm font-semibold text-slate-800">{{ $material->title }}</h4>
                            <p class="mt-0.5 text-xs text-slate-400">{{ ucfirst($material->type) }}</p>
                        </div>
                    </div>
                    @if ($material->description)
                        <p class="mt-3 line-clamp-2 text-sm text-slate-500">{{ $material->description }}</p>
                    @endif
                    <div class="mt-4 border-t border-slate-100 pt-3">
                        @if ($material->file_path)
                            <a href="{{ Storage::url($material->file_path) }}" target="_blank" class="btn-secondary w-full text-xs">Download</a>
                        @elseif ($material->external_url)
                            <a href="{{ $material->external_url }}" target="_blank" class="btn-outline w-full text-xs">Open Link</a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="sm:col-span-2 lg:col-span-3">
                    <x-student.empty-state title="No materials yet" description="Your trainer hasn't shared any materials for this batch yet." />
                </div>
            @endforelse
        </div>
    @endif
</div>
