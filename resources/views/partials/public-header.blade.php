@php
$activeToolKey = $activeToolKey ?? null;
$headerMenu = $headerMenu ?? [];
$convertMenu = $headerMenu['convert_menu'] ?? [];
$homeUrl = $headerMenu['home_url'] ?? '/';
$allToolsUrl = $headerMenu['all_tools_url'] ?? '/';
$mergeLabel = $headerMenu['merge_label'] ?? 'Merge PDF';
$splitLabel = $headerMenu['split_label'] ?? 'Split PDF';
$compressLabel = $headerMenu['compress_label'] ?? 'Compress PDF';
$convertLabel = $headerMenu['convert_label'] ?? 'Convert PDF';
$allToolsLabel = $headerMenu['all_tools_label'] ?? 'All PDF Tools';
$locale = $locale ?? app()->getLocale();
$localeLinks = $localeLinks ?? [];
@endphp

<header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-apple-border/50">
    <div class="max-w-[980px] mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-20">
            <a href="{{ $homeUrl }}"
                class="flex flex-shrink-0 items-center justify-center gap-2 hover:opacity-80 transition-opacity">
                <span
                    style="font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'SF Pro Text', 'Helvetica Neue', Arial, sans-serif; font-weight: 700; font-size: 1.35rem; letter-spacing: -0.02em; color: var(--op-text);">
                    OpenPDF
                </span>
            </a>

            <nav class="hidden md:flex flex-1 items-center justify-center space-x-1 lg:space-x-2">
                <a href="{{ $headerMenu['merge_url'] ?? '/' }}"
                    class="px-3 py-2.5 text-[13px] lg:text-sm font-bold tracking-wide rounded-lg hover:bg-black/5 transition-colors {{ $activeToolKey === 'merge_pdf' ? 'text-apple-blue' : 'text-apple-text' }}">{{ $mergeLabel }}</a>
                <a href="{{ $headerMenu['split_url'] ?? $allToolsUrl }}"
                    class="px-3 py-2.5 text-[13px] lg:text-sm font-bold tracking-wide rounded-lg hover:bg-black/5 transition-colors {{ $activeToolKey === 'split_pdf' ? 'text-apple-blue' : 'text-apple-text' }}">{{ $splitLabel }}</a>
                <a href="{{ $headerMenu['compress_url'] ?? '/' }}"
                    class="px-3 py-2.5 text-[13px] lg:text-sm font-bold tracking-wide rounded-lg hover:bg-black/5 transition-colors {{ $activeToolKey === 'compress_pdf' ? 'text-apple-blue' : 'text-apple-text' }}">{{ $compressLabel }}</a>

                <div class="relative group">
                    <button
                        class="flex items-center gap-1.5 px-3 py-2.5 text-[13px] lg:text-sm font-bold tracking-wide text-apple-text rounded-lg hover:bg-black/5 transition-colors">
                        {{ $convertLabel }}
                        <i class="bi bi-chevron-down text-[10px] mt-0.5"></i>
                    </button>
                    <div
                        class="absolute left-1/2 -translate-x-1/2 mt-1 w-64 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-apple-border/30 overflow-hidden z-50">
                        <div class="py-2">
                            @foreach ($convertMenu as $tool)
                            <a href="{{ $tool['url'] }}"
                                class="block px-5 py-2.5 text-sm font-medium text-apple-text hover:bg-black/5 hover:text-apple-blue transition-colors">{{
                                $tool['title'] }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <a href="{{ $allToolsUrl }}"
                    class="px-3 py-2.5 text-[13px] lg:text-sm font-bold tracking-wide rounded-lg hover:bg-black/5 transition-colors {{ $activeToolKey === null ? 'text-apple-blue' : 'text-apple-text' }}">{{ $allToolsLabel }}</a>
            </nav>

            @if (count($localeLinks))
            <div class="flex items-center justify-end w-[92px] sm:w-[108px] md:w-[188px] shrink-0">
                <details class="relative">
                    <summary
                        class="op-locale-summary flex items-center gap-2 px-3 py-2.5 text-[13px] lg:text-sm font-bold tracking-wide text-apple-text rounded-lg hover:bg-black/5 transition-colors cursor-pointer">
                        <i class="bi bi-globe2 text-[14px]"></i>
                        <span>{{ strtoupper($locale) }}</span>
                        <i class="bi bi-chevron-down text-[10px] mt-0.5"></i>
                    </summary>
                    <div
                        class="absolute right-0 mt-2 w-[min(92vw,28rem)] sm:w-[28rem] max-h-[min(70vh,26rem)] overflow-y-auto rounded-2xl bg-white shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-apple-border/30 z-50">
                        <div class="grid grid-cols-3 gap-px bg-apple-border/20 p-px">
                            @foreach ($localeLinks as $item)
                            <a href="{{ $item['url'] }}"
                                class="min-w-0 bg-white px-3 py-3 text-left text-xs sm:text-sm font-medium hover:bg-black/5 transition-colors {{ $item['code'] === $locale ? 'text-apple-blue' : 'text-apple-text' }}">
                                <span class="block truncate">{{ $item['label'] }}</span>
                                <span class="mt-1 block text-[10px] uppercase text-apple-muted">{{ $item['code']
                                    }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </details>
            </div>
            @else
            <div class="w-[92px] sm:w-[108px] md:w-[188px] shrink-0"></div>
            @endif
        </div>
    </div>
</header>
