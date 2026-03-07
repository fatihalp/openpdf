<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ConversionController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\LegalPageController;
use App\Http\Controllers\Web\LocaleController;
use App\Http\Controllers\Web\SitemapXmlController;
use App\Http\Controllers\Web\ToolPageController;
use App\Support\ToolCatalog;
use Illuminate\Support\Facades\Route;

$localePattern = implode('|', ToolCatalog::locales());
$toolPattern = implode('|', array_map(
    static fn (string $toolKey): string => ToolCatalog::toolSlug($toolKey),
    array_keys(ToolCatalog::all())
));

Route::get('/', HomeController::class)->name('home');
Route::get('/sitemap.xml', SitemapXmlController::class)->name('sitemap.xml');
Route::get('/{locale}', HomeController::class)
    ->where('locale', $localePattern)
    ->name('home.localized');

Route::get('/{locale}/{toolSlug}/{seoSlug}', [ToolPageController::class, 'show'])
    ->where('locale', $localePattern)
    ->where('toolSlug', $toolPattern)
    ->name('tools.show');

Route::get('/{locale}/privacy-policy', [LegalPageController::class, 'privacy'])
    ->where('locale', $localePattern)
    ->name('legal.privacy');

Route::get('/{locale}/terms-of-service', [LegalPageController::class, 'terms'])
    ->where('locale', $localePattern)
    ->name('legal.terms');

Route::get('/{locale}/{siteMapSlug}', [ToolPageController::class, 'siteMapPage'])
    ->where('locale', $localePattern)
    ->name('site-map.show');

Route::post('/locale', LocaleController::class)->name('locale.switch');

Route::prefix('api')->group(function (): void {
    Route::get('/auth/session', [AuthController::class, 'session'])->name('api.auth.session');
    Route::post('/auth/google', [AuthController::class, 'google'])->name('api.auth.google');
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('api.auth.logout');

    Route::post('/conversions', [ConversionController::class, 'store'])->name('api.conversions.store');
    Route::get('/conversions/{task}', [ConversionController::class, 'show'])->name('api.conversions.show');
    Route::get('/conversions/{task}/download', [ConversionController::class, 'download'])->name('api.conversions.download');
});
