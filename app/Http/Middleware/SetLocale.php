<?php

namespace App\Http\Middleware;

use App\Support\ToolCatalog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $allowed = ToolCatalog::locales();
        $routeLocale = $request->route('locale');
        $requested = is_string($routeLocale) ? $routeLocale : $request->query('lang');

        if (is_string($requested) && in_array($requested, $allowed, true)) {
            $request->session()->put('locale', $requested);
        }

        $locale = $request->session()->get('locale', config('app.locale'));
        if (! in_array($locale, $allowed, true)) {
            $locale = config('app.locale');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
