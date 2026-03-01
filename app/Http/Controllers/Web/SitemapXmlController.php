<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Support\ToolCatalog;
use Illuminate\Http\Response;

class SitemapXmlController extends Controller
{
    public function __invoke(): Response
    {
        $urls = [];

        foreach (ToolCatalog::locales() as $locale) {
            $urls[] = url(ToolCatalog::homeUrl($locale));

            foreach (array_keys(ToolCatalog::all()) as $toolKey) {
                $urls[] = url(ToolCatalog::toolUrl($locale, $toolKey));
            }

            $urls[] = url(ToolCatalog::siteMapUrl($locale));
        }

        $xml = view('sitemap-xml', [
            'urls' => array_values(array_unique($urls)),
        ])->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }
}
