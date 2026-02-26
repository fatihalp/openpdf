<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($urls as $link)
    <url>
        <loc>{{ $link }}</loc>
        <lastmod>{{ now()->toIso8601String() }}</lastmod>
    </url>
@endforeach
</urlset>
