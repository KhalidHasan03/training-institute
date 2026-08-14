@props(['active' => false, 'href' => '#'])

<a href="{{ $href }}"
   class="group relative inline-flex items-center gap-1.5 text-sm font-medium transition-colors duration-200 {{ $active ? 'text-brand-700' : 'text-slate-600 hover:text-brand-700' }}">
    <span class="relative">
        {{ $slot }}
        <span class="absolute -bottom-1.5 left-0 right-0 h-0.5 origin-left rounded-full bg-gradient-to-r from-brand-600 to-accent-500 transition-transform duration-300 {{ $active ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}"></span>
    </span>
</a>