<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Support\ToolCatalog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $locale = app()->getLocale();
        $tools = ToolCatalog::localized($locale);
        $localeLinks = collect(ToolCatalog::locales())
            ->map(fn (string $code): array => [
                'code' => $code,
                'label' => trans("openpdf.locales.$code", [], $locale),
                'url' => url('/?lang='.$code),
            ])
            ->values()
            ->all();

        return view('landing', [
            'locale' => $locale,
            'domain' => config('openpdf.domain'),
            'title' => trans('openpdf.meta.title', [], $locale),
            'description' => trans('openpdf.meta.description', [], $locale),
            'tools' => $tools,
            'localeLinks' => $localeLinks,
            'siteMapUrl' => ToolCatalog::siteMapUrl($locale),
            'headerMenu' => ToolCatalog::headerMenu($locale),
        ]);
    }
}
