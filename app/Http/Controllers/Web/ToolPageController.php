<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Support\ToolCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ToolPageController extends Controller
{
    public function home(Request $request): RedirectResponse
    {
        $locale = app()->getLocale();

        if (! ToolCatalog::isLocale($locale)) {
            $locale = ToolCatalog::defaultLocale();
        }

        return redirect(ToolCatalog::toolUrl($locale, ToolCatalog::defaultToolKey()));
    }

    public function show(Request $request, string $locale, string $toolSlug, string $seoSlug): View|RedirectResponse
    {
        if (! ToolCatalog::isLocale($locale)) {
            abort(404);
        }

        $toolKey = ToolCatalog::toolKeyFromSlug($toolSlug);
        if (! $toolKey) {
            abort(404);
        }

        $canonicalSuffix = ToolCatalog::seoSuffixSlug($locale);
        if ($seoSlug !== $canonicalSuffix) {
            return redirect(ToolCatalog::toolUrl($locale, $toolKey), 301);
        }

        app()->setLocale($locale);
        $request->session()->put('locale', $locale);

        $config = $this->buildConfig($request, $locale, $toolKey);
        $alternateUrls = collect(ToolCatalog::locales())
            ->map(fn (string $code) => [
                'locale' => $code,
                'url' => url(ToolCatalog::toolUrl($code, $toolKey)),
            ])
            ->values()
            ->all();
        $localeLinks = collect(ToolCatalog::locales())
            ->map(fn (string $code) => [
                'code' => $code,
                'label' => trans("openpdf.locales.$code", [], $locale),
                'url' => ToolCatalog::toolUrl($code, $toolKey),
            ])
            ->values()
            ->all();

        return view('tool', [
            'appConfigJson' => json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'pageTitle' => trans("openpdf.tools.$toolKey.title", [], $locale).' | '.trans('openpdf.meta.title', [], $locale),
            'pageDescription' => trans("openpdf.tools.$toolKey.description", [], $locale).' '.trans("openpdf.tools.$toolKey.seo", [], $locale),
            'canonicalUrl' => url(ToolCatalog::toolUrl($locale, $toolKey)),
            'alternateUrls' => $alternateUrls,
            'headerMenu' => ToolCatalog::headerMenu($locale),
            'activeToolKey' => $toolKey,
            'locale' => $locale,
            'localeLinks' => $localeLinks,
        ]);
    }

    public function siteMapPage(Request $request, string $locale, string $siteMapSlug): View|RedirectResponse
    {
        if (! ToolCatalog::isLocale($locale)) {
            abort(404);
        }

        $canonicalSlug = ToolCatalog::siteMapSlug($locale);
        if ($siteMapSlug !== $canonicalSlug) {
            return redirect(ToolCatalog::siteMapUrl($locale), 301);
        }

        app()->setLocale($locale);
        $request->session()->put('locale', $locale);

        $tools = ToolCatalog::localized($locale);
        $alternateUrls = collect(ToolCatalog::locales())
            ->map(fn (string $code) => [
                'locale' => $code,
                'url' => url(ToolCatalog::siteMapUrl($code)),
            ])
            ->values()
            ->all();
        $localeLinks = collect(ToolCatalog::locales())
            ->map(fn (string $code) => [
                'code' => $code,
                'label' => trans("openpdf.locales.$code", [], $locale),
                'url' => ToolCatalog::siteMapUrl($code),
            ])
            ->values()
            ->all();

        return view('site-map', [
            'locale' => $locale,
            'tools' => $tools,
            'siteMapXmlUrl' => url('/sitemap.xml'),
            'title' => trans('openpdf.sitemap.title', [], $locale),
            'description' => trans('openpdf.sitemap.description', [], $locale),
            'homeUrl' => ToolCatalog::toolUrl($locale, ToolCatalog::defaultToolKey()),
            'canonicalUrl' => url(ToolCatalog::siteMapUrl($locale)),
            'alternateUrls' => $alternateUrls,
            'localeLinks' => $localeLinks,
            'domain' => config('openpdf.domain'),
            'headerMenu' => ToolCatalog::headerMenu($locale),
        ]);
    }

    private function buildConfig(Request $request, string $locale, string $selectedToolKey): array
    {
        $user = $request->user();
        $toolUrlsByLocale = [];
        $siteMapUrlsByLocale = [];

        foreach (ToolCatalog::locales() as $code) {
            $siteMapUrlsByLocale[$code] = ToolCatalog::siteMapUrl($code);

            foreach (array_keys(ToolCatalog::all()) as $toolKey) {
                $toolUrlsByLocale[$code][$toolKey] = ToolCatalog::toolUrl($code, $toolKey);
            }
        }

        return [
            'domain' => config('openpdf.domain'),
            'organization' => config('openpdf.organization'),
            'freeForever' => (bool) config('openpdf.free_forever', true),
            'locale' => $locale,
            'selectedToolKey' => $selectedToolKey,
            'seoSuffix' => ToolCatalog::seoSuffixSlug($locale),
            'siteMapUrl' => ToolCatalog::siteMapUrl($locale),
            'sitemapXmlUrl' => '/sitemap.xml',
            'homeUrl' => ToolCatalog::toolUrl($locale, ToolCatalog::defaultToolKey()),
            'donations' => collect(config('openpdf.donations', []))
                ->filter(static fn (array $item) => ! empty($item['url']))
                ->values()
                ->all(),
            'i18n' => trans('openpdf', [], $locale),
            'locales' => collect(ToolCatalog::locales())
                ->map(fn (string $code) => [
                    'code' => $code,
                    'label' => trans("openpdf.locales.$code", [], $locale),
                ])
                ->values()
                ->all(),
            'limits' => ToolCatalog::visitorLimits(),
            'googleClientId' => config('services.google.client_id'),
            'tools' => ToolCatalog::localized($locale),
            'toolUrlsByLocale' => $toolUrlsByLocale,
            'siteMapUrlsByLocale' => $siteMapUrlsByLocale,
            'auth' => [
                'logged_in' => $user !== null,
                'provider' => $user?->google_id ? 'google' : ($user ? 'local' : 'visitor'),
                'name' => $user?->name,
                'email' => $user?->email,
            ],
        ];
    }
}
