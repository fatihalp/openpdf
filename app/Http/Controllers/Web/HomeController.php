<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Support\ToolCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request, ?string $locale = null): View|RedirectResponse
    {
        if ($request->query->has('lang')) {
            $requestedLocale = (string) $request->query('lang');

            if (ToolCatalog::isLocale($requestedLocale)) {
                $request->session()->put('locale', $requestedLocale);

                return redirect(ToolCatalog::homeUrl($requestedLocale), 301);
            }
        }

        $locale = is_string($locale) ? $locale : app()->getLocale();

        if (! ToolCatalog::isLocale($locale)) {
            $locale = ToolCatalog::defaultLocale();
        }

        app()->setLocale($locale);
        $request->session()->put('locale', $locale);

        if ($request->route('locale') === null) {
            return redirect(ToolCatalog::homeUrl($locale), 301);
        }

        $tools = ToolCatalog::localized($locale);
        $alternateUrls = collect(ToolCatalog::locales())
            ->map(fn (string $code): array => [
                'locale' => $code,
                'url' => url(ToolCatalog::homeUrl($code)),
            ])
            ->values()
            ->all();
        $localeLinks = collect(ToolCatalog::locales())
            ->map(fn (string $code): array => [
                'code' => $code,
                'label' => trans("openpdf.locales.$code", [], $locale),
                'url' => ToolCatalog::homeUrl($code),
            ])
            ->values()
            ->all();

        return view('home', [
            'locale' => $locale,
            'domain' => config('openpdf.domain'),
            'title' => trans('openpdf.meta.title', [], $locale),
            'description' => trans('openpdf.meta.description', [], $locale),
            'tools' => $tools,
            'localeLinks' => $localeLinks,
            'canonicalUrl' => url(ToolCatalog::homeUrl($locale)),
            'alternateUrls' => $alternateUrls,
            'siteMapUrl' => ToolCatalog::siteMapUrl($locale),
            'headerMenu' => ToolCatalog::headerMenu($locale),
        ]);
    }
}
