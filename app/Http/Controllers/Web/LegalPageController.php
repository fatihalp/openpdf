<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Support\ToolCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LegalPageController extends Controller
{
    public function privacy(Request $request, string $locale): View|RedirectResponse
    {
        return $this->showPage($request, $locale, 'privacy');
    }

    public function terms(Request $request, string $locale): View|RedirectResponse
    {
        return $this->showPage($request, $locale, 'terms');
    }

    private function showPage(Request $request, string $locale, string $page): View|RedirectResponse
    {
        if (! ToolCatalog::isLocale($locale)) {
            abort(404);
        }

        if (! in_array($page, ['privacy', 'terms'], true)) {
            abort(404);
        }

        app()->setLocale($locale);
        $request->session()->put('locale', $locale);

        $alternateUrls = collect(ToolCatalog::locales())
            ->map(fn (string $code): array => [
                'locale' => $code,
                'url' => url($this->pageUrl($code, $page)),
            ])
            ->values()
            ->all();

        $localeLinks = collect(ToolCatalog::locales())
            ->map(fn (string $code): array => [
                'code' => $code,
                'label' => trans("openpdf.locales.$code", [], $locale),
                'url' => $this->pageUrl($code, $page),
            ])
            ->values()
            ->all();

        return view('legal', [
            'locale' => $locale,
            'pageKey' => $page,
            'pageTitle' => trans("openpdf.legal.$page.page_title", [], $locale),
            'pageDescription' => trans("openpdf.legal.$page.page_description", [], $locale),
            'pageEyebrow' => trans('openpdf.legal.eyebrow', [], $locale),
            'effectiveDate' => trans("openpdf.legal.$page.effective_date", [], $locale),
            'sections' => trans("openpdf.legal.$page.sections", [], $locale),
            'canonicalUrl' => url($this->pageUrl($locale, $page)),
            'alternateUrls' => $alternateUrls,
            'headerMenu' => ToolCatalog::headerMenu($locale),
            'localeLinks' => $localeLinks,
        ]);
    }

    private function pageUrl(string $locale, string $page): string
    {
        return match ($page) {
            'privacy' => route('legal.privacy', ['locale' => $locale], false),
            'terms' => route('legal.terms', ['locale' => $locale], false),
        };
    }
}
