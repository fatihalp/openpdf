<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Support\ToolCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $locale = (string) $request->input('locale', config('app.locale'));

        if (! in_array($locale, ToolCatalog::locales(), true)) {
            $locale = config('app.locale');
        }

        $request->session()->put('locale', $locale);

        return redirect()->back();
    }
}
