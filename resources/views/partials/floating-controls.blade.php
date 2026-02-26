@php
    $locale = $locale ?? app()->getLocale();
    $localeLinks = $localeLinks ?? [];
@endphp

<div class="op-fab-stack">
    <button type="button" class="op-theme-btn" data-theme-toggle aria-label="Toggle theme">
        <i class="bi bi-circle-half"></i>
    </button>

    <div class="dropup op-lang-fab">
        <button class="op-lang-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-globe2"></i>
            <span>{{ strtoupper($locale) }}</span>
        </button>
        <div class="dropdown-menu dropdown-menu-end op-lang-menu">
            <div class="op-lang-grid">
                @foreach ($localeLinks as $item)
                    <a class="op-lang-item {{ $item['code'] === $locale ? 'active' : '' }}" href="{{ $item['url'] }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
