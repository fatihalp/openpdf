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

<body class="site-map-body">
    @include('partials.public-header', ['headerMenu' => $headerMenu])

    <main class="py-4">
        <div class="container">
            <div class="bg-white border rounded-4 p-4 mb-4">
                <h1>{{ $title }}</h1>
                <p>{{ $description }}</p>
                <p><a href="{{ $siteMapXmlUrl }}">sitemap.xml</a></p>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="bg-white border rounded-4 p-4 h-100">
                        <h2>{{ __('openpdf.sitemap.tools_heading', [], $locale) }}</h2>
                        <ul class="list-group list-group-flush">
                            @foreach ($tools as $tool)
                            <li class="list-group-item px-0">
                                <a href="{{ $tool['url'] }}">{{ $tool['title'] }}</a>
                                <div class="text-muted small">{{ $tool['description'] }}</div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="bg-white border rounded-4 p-4 h-100">
                        <h2>{{ __('openpdf.sitemap.languages_heading', [], $locale) }}</h2>
                        <ul class="list-group list-group-flush">
                            @foreach ($localeLinks as $item)
                            <li class="list-group-item px-0">
                                <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                                <div class="text-muted small">{{ $item['code'] }}</div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('partials.footer')
    @include('partials.floating-controls', ['locale' => $locale, 'localeLinks' => $localeLinks])
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="{{ asset('js/theme-switcher.js') }}"></script>
</body>

</html>
