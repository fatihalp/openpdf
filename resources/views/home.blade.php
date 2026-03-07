<!doctype html>
<html lang="{{ $locale }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    @foreach ($alternateUrls as $alternateUrl)
    <link rel="alternate" hreflang="{{ $alternateUrl['locale'] }}" href="{{ $alternateUrl['url'] }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ $alternateUrls[0]['url'] ?? $canonicalUrl }}">
    @vite(['resources/css/app.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body
    class="bg-apple-bg text-apple-text font-sans antialiased min-h-screen flex flex-col selection:bg-apple-blue selection:text-white">

    @include('partials.public-header', ['headerMenu' => $headerMenu, 'locale' => $locale, 'localeLinks' => $localeLinks])

    <main class="flex-grow">
        <!-- Hero Section -->
        <section class="max-w-[980px] mx-auto px-4 sm:px-6 pt-8 pb-6 md:pt-10 md:pb-8 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-5xl font-bold tracking-tight text-apple-text mb-4">
                {{ __('openpdf.home.slogan_title', [], $locale) }}
            </h1>
            <p class="text-lg md:text-xl text-apple-muted max-w-3xl mx-auto leading-relaxed">
                {{ __('openpdf.home.slogan_subtitle', [], $locale) }}
            </p>
        </section>

        <!-- Tools Grid -->
        <section class="max-w-[980px] mx-auto px-4 sm:px-6 pb-12 md:pb-14">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
                @php
                $toolIcons = [
                'pdf_to_word' => ['icon' => 'bi-file-word', 'color' => 'text-blue-500', 'bg' => 'bg-blue-50'],
                'pdf_to_excel' => ['icon' => 'bi-file-excel', 'color' => 'text-green-600', 'bg' => 'bg-green-50'],
                'pdf_to_jpg' => ['icon' => 'bi-file-image', 'color' => 'text-yellow-500', 'bg' => 'bg-yellow-50'],
                'compress_pdf' => ['icon' => 'bi-arrows-angle-contract', 'color' => 'text-red-500', 'bg' =>
                'bg-red-50'],
                'merge_pdf' => ['icon' => 'bi-file-earmark-plus', 'color' => 'text-purple-500', 'bg' => 'bg-purple-50'],
                'split_pdf' => ['icon' => 'bi-layout-split', 'color' => 'text-orange-500', 'bg' => 'bg-orange-50'],
                'word_to_pdf' => ['icon' => 'bi-file-pdf', 'color' => 'text-blue-500', 'bg' => 'bg-blue-50'],
                'excel_to_pdf' => ['icon' => 'bi-file-pdf', 'color' => 'text-green-600', 'bg' => 'bg-green-50'],
                'jpg_to_pdf' => ['icon' => 'bi-file-pdf', 'color' => 'text-yellow-500', 'bg' => 'bg-yellow-50'],
                ];
                @endphp

                @foreach ($tools as $tool)
                @php
                $iconData = $toolIcons[$tool['key']] ?? ['icon' => 'bi-file-earmark-text', 'color' => 'text-gray-500',
                'bg' => 'bg-gray-50'];
                @endphp
                <a href="{{ $tool['url'] }}"
                    class="group bg-white rounded-2xl p-6 border border-apple-border/40 hover:border-transparent hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 flex flex-col h-full">
                    <div
                        class="mb-5 {{ $iconData['bg'] }} w-14 h-14 rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                        <i class="bi {{ $iconData['icon'] }} {{ $iconData['color'] }} text-2xl"></i>
                    </div>
                    <h2
                        class="text-xl font-bold text-apple-text mb-2 leading-tight group-hover:text-apple-blue transition-colors">
                        {{ $tool['title'] }}</h2>
                    <p class="text-sm text-apple-muted leading-relaxed line-clamp-3">
                        {{ $tool['description'] }}
                    </p>
                </a>
                @endforeach
            </div>

        </section>
    </main>

    @include('partials.footer')
</body>

</html>
