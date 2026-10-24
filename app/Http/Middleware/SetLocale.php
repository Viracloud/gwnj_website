<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applies the language from the URL prefix (/en/..., /fa/...):
 * sets the app locale, makes route() generate links in the same language,
 * remembers the choice, and removes {locale} from the route parameters so
 * controllers keep their normal signatures (e.g. event($slug)).
 */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $route = $request->route();
        $locale = $route?->parameter('locale');

        if (!in_array($locale, config('locales.enabled'), true)) {
            abort(404);
        }

        app()->setLocale($locale);
        URL::defaults(['locale' => $locale]);
        $route->forgetParameter('locale');

        if ($request->hasSession() && $request->session()->get('site_lang') !== $locale) {
            $request->session()->put('site_lang', $locale);
        }

        return $next($request);
    }
}
