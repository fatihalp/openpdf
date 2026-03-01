<?php

namespace App\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ToolCatalog
{
    public static function all(): array
    {
        return config('openpdf.tools', []);
    }

    public static function locales(): array
    {
        return config('openpdf.locales', ['en']);
    }

    public static function defaultLocale(): string
    {
        $default = (string) config('app.locale', 'en');

        return self::isLocale($default) ? $default : (self::locales()[0] ?? 'en');
    }

    public static function isLocale(string $locale): bool
    {
        return in_array($locale, self::locales(), true);
    }

    public static function defaultToolKey(): string
    {
        return (string) array_key_first(self::all());
    }

    public static function visitorLimits(): array
    {
        $limits = config('openpdf.visitor_limits', []);

        return [
            'max_files' => (int) Arr::get($limits, 'max_files', 100),
            'max_bytes' => (int) Arr::get($limits, 'max_bytes', 100 * 1024 * 1024),
        ];
    }

    public static function isValid(string $toolKey): bool
    {
        return array_key_exists($toolKey, self::all());
    }

    public static function tool(string $toolKey): array
    {
        return self::all()[$toolKey] ?? [];
    }

    public static function toolSlug(string $toolKey): string
    {
        return Str::of($toolKey)->replace('_', '-')->toString();
    }

    public static function toolKeyFromSlug(string $toolSlug): ?string
    {
        $key = Str::of($toolSlug)->replace('-', '_')->toString();

        return self::isValid($key) ? $key : null;
    }

    public static function seoSuffixSlug(string $locale): string
    {
        $slugs = config('openpdf.route_slugs.free_converter', []);
        $defaultLocale = self::defaultLocale();

        $localeSlug = (string) Arr::get($slugs, $locale, '');
        if ($localeSlug !== '') {
            return $localeSlug;
        }

        $fallbackSlug = (string) Arr::get($slugs, $defaultLocale, 'free-converter');
        $translated = (string) trans('openpdf.routes.free_converter', [], $locale ?: $defaultLocale);

        return $translated !== '' && $translated !== 'openpdf.routes.free_converter'
            ? $translated
            : $fallbackSlug;
    }

    public static function siteMapSlug(string $locale): string
    {
        $slugs = config('openpdf.route_slugs.site_map', []);
        $defaultLocale = self::defaultLocale();

        $localeSlug = (string) Arr::get($slugs, $locale, '');
        if ($localeSlug !== '') {
            return $localeSlug;
        }

        $fallbackSlug = (string) Arr::get($slugs, $defaultLocale, 'site-map');
        $translated = (string) trans('openpdf.routes.site_map', [], $locale ?: $defaultLocale);

        return $translated !== '' && $translated !== 'openpdf.routes.site_map'
            ? $translated
            : $fallbackSlug;
    }

    public static function toolUrl(string $locale, string $toolKey): string
    {
        return sprintf('/%s/%s/%s', $locale, self::toolSlug($toolKey), self::seoSuffixSlug($locale));
    }

    public static function siteMapUrl(string $locale): string
    {
        return sprintf('/%s/%s', $locale, self::siteMapSlug($locale));
    }

    public static function homeUrl(string $locale): string
    {
        return sprintf('/%s', $locale);
    }

    public static function localized(string $locale): array
    {
        $localized = [];

        foreach (self::all() as $toolKey => $toolConfig) {
            $localized[] = [
                'key' => $toolKey,
                'slug' => self::toolSlug($toolKey),
                'url' => self::toolUrl($locale, $toolKey),
                'mode' => $toolConfig['mode'],
                'accept_extensions' => $toolConfig['accept_extensions'],
                'accept_mime' => $toolConfig['accept_mime'],
                'title' => trans("openpdf.tools.$toolKey.title", [], $locale),
                'description' => trans("openpdf.tools.$toolKey.description", [], $locale),
                'seo' => trans("openpdf.tools.$toolKey.seo", [], $locale),
            ];
        }

        return $localized;
    }

    public static function headerMenu(string $locale): array
    {
        $tools = self::localized($locale);
        $toolMap = collect($tools)->keyBy('key');

        $convertKeys = [
            'pdf_to_word',
            'pdf_to_excel',
            'pdf_to_jpg',
            'word_to_pdf',
            'excel_to_pdf',
            'jpg_to_pdf',
        ];

        return [
            'home_url' => self::homeUrl($locale),
            'merge_url' => self::toolUrl($locale, 'merge_pdf'),
            'split_url' => self::toolUrl($locale, 'split_pdf'),
            'compress_url' => self::toolUrl($locale, 'compress_pdf'),
            'all_tools_url' => self::siteMapUrl($locale),
            'merge_label' => trans('openpdf.header.merge_pdf', [], $locale),
            'split_label' => trans('openpdf.header.split_pdf', [], $locale),
            'compress_label' => trans('openpdf.header.compress_pdf', [], $locale),
            'convert_label' => trans('openpdf.header.convert_pdf', [], $locale),
            'all_tools_label' => trans('openpdf.header.all_pdf_tools', [], $locale),
            'convert_menu' => collect($convertKeys)
                ->map(fn (string $key) => $toolMap->get($key))
                ->filter()
                ->values()
                ->all(),
            'all_tools_menu' => $tools,
            'login_url' => '/admin/login',
            'signup_url' => '/admin/login',
        ];
    }
}
