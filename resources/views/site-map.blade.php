<!doctype html>
<html lang="{{ $locale }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} | OpenPDF</title>
    <meta name="description" content="{{ $description }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    @foreach ($alternateUrls as $alternateUrl)
    <link rel="alternate" hreflang="{{ $alternateUrl['locale'] }}" href="{{ $alternateUrl['url'] }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ $alternateUrls[0]['url'] ?? $canonicalUrl }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body
    class="bg-apple-bg text-apple-text font-sans antialiased min-h-screen flex flex-col selection:bg-apple-blue selection:text-white">
    @include('partials.public-header', ['headerMenu' => $headerMenu, 'locale' => $locale, 'localeLinks' => $localeLinks])

    <main class="flex-grow py-8 md:py-10">
        <div class="max-w-[980px] mx-auto px-4 sm:px-6">
            <section class="rounded-[28px] border border-apple-border/40 bg-white p-6 md:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div class="max-w-3xl">
                        <h1 class="text-3xl md:text-4xl font-bold tracking-tight text-apple-text">{{ $title }}</h1>
                        <p class="mt-3 text-base md:text-lg text-apple-muted leading-relaxed">{{ $description }}</p>
                    </div>
                    <a href="{{ $siteMapXmlUrl }}"
                        class="inline-flex items-center justify-center rounded-full border border-apple-border/50 px-4 py-2 text-sm font-semibold text-apple-text hover:border-apple-blue hover:text-apple-blue transition-colors">
                        sitemap.xml
                    </a>
                </div>
            </section>

            <div class="mt-6 grid gap-6 lg:grid-cols-[minmax(0,1.8fr)_minmax(280px,1fr)]">
                <section
                    class="rounded-[28px] border border-apple-border/40 bg-white p-6 md:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                    <h2 class="text-2xl font-bold tracking-tight text-apple-text">
                        {{ __('openpdf.sitemap.tools_heading', [], $locale) }}
                    </h2>
                    <ul class="mt-5 divide-y divide-apple-border/40">
                        @foreach ($tools as $tool)
                        <li class="py-4 first:pt-0 last:pb-0">
                            <a href="{{ $tool['url'] }}"
                                class="text-lg font-semibold text-apple-text hover:text-apple-blue transition-colors">
                                {{ $tool['title'] }}
                            </a>
                            <p class="mt-1 text-sm md:text-base text-apple-muted leading-relaxed">
                                {{ $tool['description'] }}
                            </p>
                        </li>
                        @endforeach
                    </ul>
                </section>

                <aside
                    class="rounded-[28px] border border-apple-border/40 bg-white p-6 md:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                    <h2 class="text-2xl font-bold tracking-tight text-apple-text">
                        {{ __('openpdf.sitemap.languages_heading', [], $locale) }}
                    </h2>
                    <div class="mt-5 grid grid-cols-2 gap-3">
                        @foreach ($localeLinks as $item)
                        <a href="{{ $item['url'] }}"
                            class="rounded-2xl border border-apple-border/40 px-4 py-3 hover:border-apple-blue/40 hover:bg-black/[0.02] transition-colors {{ $item['code'] === $locale ? 'border-apple-blue/50 text-apple-blue' : 'text-apple-text' }}">
                            <span class="block text-sm font-semibold">{{ $item['label'] }}</span>
                            <span class="mt-1 block text-[11px] uppercase text-apple-muted">{{ $item['code'] }}</span>
                        </a>
                        @endforeach
                    </div>
                </aside>
            </div>
        </div>
    </main>

    @include('partials.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="{{ asset('js/theme-switcher.js') }}"></script>
</body>

</html>
