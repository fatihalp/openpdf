<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Support\ToolCatalog;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $locale = app()->getLocale();
        $user = $request->user();

        $config = [
            'domain' => config('openpdf.domain'),
            'locale' => $locale,
            'i18n' => trans('openpdf'),
            'locales' => collect(ToolCatalog::locales())
                ->map(fn (string $code) => [
                    'code' => $code,
                    'label' => trans("openpdf.locales.$code"),
                ])
                ->values()
                ->all(),
            'limits' => ToolCatalog::visitorLimits(),
            'googleClientId' => config('services.google.client_id'),
            'tools' => ToolCatalog::localized($locale),
            'auth' => [
                'logged_in' => $user !== null,
                'provider' => $user?->google_id ? 'google' : ($user ? 'local' : 'visitor'),
                'name' => $user?->name,
                'email' => $user?->email,
            ],
        ];

        return view('home', [
            'appConfigJson' => json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]);
    }
}
