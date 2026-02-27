<!doctype html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/openpdf.css') }}">
</head>
<body class="openpdf-home">
    @include('partials.public-header', ['headerMenu' => $headerMenu])

    <main class="op-home-main">
        <div class="container">
            <section class="op-home-slogan">
                <h1>{{ __('openpdf.home.slogan_title', [], $locale) }}</h1>
                <p>{{ __('openpdf.home.slogan_subtitle', [], $locale) }}</p>
            </section>

            <div class="op-tools-grid">
                @php
                    $toolIcons = [
                        'pdf_to_word' => 'bi-file-earmark-word',
                        'pdf_to_excel' => 'bi-file-earmark-excel',
                        'pdf_to_jpg' => 'bi-filetype-jpg',
                        'compress_pdf' => 'bi-file-zip',
                        'merge_pdf' => 'bi-files',
                        'split_pdf' => 'bi-scissors',
                        'word_to_pdf' => 'bi-file-earmark-pdf',
                        'excel_to_pdf' => 'bi-file-earmark-pdf',
                        'jpg_to_pdf' => 'bi-images',
                    ];
                @endphp
                @foreach ($tools as $tool)
                    <a href="{{ $tool['url'] }}" class="op-tool-card">
                        <span class="op-tool-icon">
                            <i class="bi {{ $toolIcons[$tool['key']] ?? 'bi-file-earmark-text' }}"></i>
                        </span>
                        <h2>{{ $tool['title'] }}</h2>
                        <p>{{ $tool['description'] }}</p>
                    </a>
                @endforeach
            </div>

            <section class="op-home-donate">
                <a class="op-home-donate-btn" href="https://github.com/sponsors/fatihalp">
                    <i class="bi bi-heart-fill"></i>
                    <span>Donate on GitHub Sponsors</span>
                </a>
            </section>
        </div>
    </main>

    @include('partials.floating-controls', ['locale' => $locale, 'localeLinks' => $localeLinks])

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="{{ asset('js/theme-switcher.js') }}"></script>
</body>
</html>
