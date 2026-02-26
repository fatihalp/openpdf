<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ConversionController;
use App\Http\Controllers\Web\LocaleController;
use App\Http\Controllers\Web\SitemapXmlController;
use App\Http\Controllers\Web\ToolPageController;
use App\Support\ToolCatalog;
use Illuminate\Support\Facades\Route;

Route::get('/', [ToolPageController::class, 'home'])->name('home');
Route::get('/sitemap.xml', SitemapXmlController::class)->name('sitemap.xml');

$localePattern = implode('|', ToolCatalog::locales());
$toolPattern = implode('|', array_map(
    static fn (string $toolKey): string => ToolCatalog::toolSlug($toolKey),
    array_keys(ToolCatalog::all())
));

Route::get('/{locale}/{toolSlug}/{seoSlug}', [ToolPageController::class, 'show'])
    ->where('locale', $localePattern)
    ->where('toolSlug', $toolPattern)
    ->name('tools.show');

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
