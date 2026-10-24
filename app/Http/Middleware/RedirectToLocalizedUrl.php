<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Old addresses without a language prefix (/events, /post/x, /verify/..., links in
 * old e-mails and printed QR codes) are permanently redirected (301) to the same
 * page with a language prefix, so nothing breaks and Google moves to the new URLs.
 *
 * Runs only when no route matched (it is the 404 fallback), so it never touches
 * the admin panel, robots.txt, sitemap.xml or files.
 */
class RedirectToLocalizedUrl
{
    public static function redirect(Request $request): ?Response
    {
        if (!in_array($request->method(), ['GET', 'HEAD'], true)) {
            return null;
        }
        $path = trim($request->path(), '/');
        $first = explode('/', $path)[0] ?? '';

        if (array_key_exists($first, config('locales.supported')) || $first === 'admin') {
            return null; // a language that is switched off, or a wrong admin URL: a real 404
        }

        $locale = preferred_locale($request);
        $target = url($locale.($path === '' ? '' : '/'.$path));
        if ($qs = $request->getQueryString()) {
            $target .= '?'.$qs;
        }

        // The bare home page depends on the visitor's preference, so it must not be cached as permanent.
        return redirect($target, $path === '' ? 302 : 301);
    }

    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}
