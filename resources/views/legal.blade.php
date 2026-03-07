<!doctype html>
<html lang="{{ $locale }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }} | OpenPDF</title>
    <meta name="description" content="{{ $pageDescription }}">
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

    <main class="flex-grow py-8 md:py-10">
        <div class="max-w-[980px] mx-auto px-4 sm:px-6">
            <section class="rounded-[28px] border border-apple-border/40 bg-white p-6 md:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-apple-blue">{{ $pageEyebrow }}</p>
                <h1 class="mt-3 text-3xl md:text-4xl font-bold tracking-tight text-apple-text">{{ $pageTitle }}</h1>
                <p class="mt-3 max-w-3xl text-base md:text-lg text-apple-muted leading-relaxed">{{ $pageDescription }}</p>
                <p class="mt-4 inline-flex rounded-full bg-black/[0.04] px-4 py-2 text-sm font-medium text-apple-muted">
                    {{ $effectiveDate }}
                </p>
            </section>

            <section class="mt-6 grid gap-4">
                @foreach ($sections as $section)
                <article class="rounded-[24px] border border-apple-border/40 bg-white p-6 md:p-7 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                    <h2 class="text-xl md:text-2xl font-bold tracking-tight text-apple-text">{{ $section['title'] }}</h2>
                    <p class="mt-3 text-sm md:text-base leading-relaxed text-apple-muted">{{ $section['body'] }}</p>
                </article>
                @endforeach
            </section>
        </div>
    </main>

    @include('partials.footer')
</body>

</html>
