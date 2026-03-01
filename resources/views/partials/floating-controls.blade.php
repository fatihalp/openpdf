@php
$locale = $locale ?? app()->getLocale();
$localeLinks = $localeLinks ?? [];
@endphp

<div class="op-fab-stack">
    <button type="button" class="op-theme-btn" data-theme-toggle aria-label="Toggle theme">
        <i class="bi bi-circle-half"></i>
    </button>

    <div class="relative group op-lang-fab">
        <button
            class="op-lang-btn flex items-center gap-1.5 px-3 py-2 text-[13px] font-bold tracking-wide text-apple-text rounded-lg hover:bg-black/5 transition-colors"
            type="button">
            <i class="bi bi-globe2"></i>
            <span>{{ strtoupper($locale) }}</span>
        </button>
        <div
            class="absolute bottom-full right-0 mb-2 w-64 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-bottom bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-apple-border/30 overflow-hidden z-50">
            <div class="py-2 grid grid-cols-2 gap-px bg-apple-border/10">
                @foreach ($localeLinks as $item)
                <a class="block px-4 py-2.5 text-sm font-medium text-apple-text bg-white hover:bg-black/5 hover:text-apple-blue transition-colors {{ $item['code'] === $locale ? 'text-apple-blue' : '' }}"
                    href="{{ $item['url'] }}">
                    {{ $item['label'] }}
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>