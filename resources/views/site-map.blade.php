<!doctype html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} | OpenPDF</title>
    <meta name="description" content="{{ $description }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/openpdf.css') }}">
</head>
<body class="site-map-body">
<header class="site-header sticky-top">
    <nav class="navbar py-2">
        <div class="container">
            <a class="brand" href="{{ $homeUrl }}">
                <span class="logo-mark">OP</span>
                <span class="brand-copy">
                    <strong>OpenPDF</strong>
                    <small>{{ $domain }}</small>
                </span>
            </a>
            <a class="btn btn-outline-dark btn-sm" href="{{ $homeUrl }}">{{ __('openpdf.footer.home', [], $locale) }}</a>
        </div>
    </nav>
</header>

<main class="section-block">
    <div class="container">
        <div class="seo-copy mb-4">
            <h1>{{ $title }}</h1>
            <p>{{ $description }}</p>
            <p><a href="{{ $siteMapXmlUrl }}">sitemap.xml</a></p>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="sitemap-card">
                    <h2>{{ __('openpdf.sitemap.tools_heading', [], $locale) }}</h2>
                    <ul class="sitemap-list">
                        @foreach ($tools as $tool)
                            <li>
                                <a href="{{ $tool['url'] }}">{{ $tool['title'] }}</a>
                                <span>{{ $tool['description'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="sitemap-card">
                    <h2>{{ __('openpdf.sitemap.languages_heading', [], $locale) }}</h2>
                    <ul class="sitemap-list">
                        @foreach ($localeLinks as $item)
                            <li>
                                <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                                <span>{{ $item['locale'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>

<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <p class="footer-brand">OpenPDF</p>
                <p>{{ __('openpdf.footer.about', [], $locale) }}</p>
            </div>
            <div class="footer-links">
                <a href="{{ $homeUrl }}">{{ __('openpdf.footer.home', [], $locale) }}</a>
                <a href="{{ $canonicalUrl }}">{{ __('openpdf.footer.site_map', [], $locale) }}</a>
                <a href="{{ $siteMapXmlUrl }}">{{ __('openpdf.footer.sitemap_xml', [], $locale) }}</a>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
