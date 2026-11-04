<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Adds standard browser security headers to every response:
 * no framing by other sites (clickjacking), no MIME sniffing,
 * a conservative referrer policy, and HTTPS-only once the site runs on HTTPS.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $headers = $response->headers;
        $headers->set('X-Frame-Options', 'SAMEORIGIN');
        $headers->set('X-Content-Type-Options', 'nosniff');
        $headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=(self "https://www.paypal.com")');
        $headers->set('Cross-Origin-Opener-Policy', 'same-origin-allow-popups');

        if ($request->isSecure()) {
            $headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Admin and member pages hold personal data: never let browsers or proxies cache them,
        // so nothing can be recovered from a shared or seized device after logout or Quick exit.
        // Public pages live under /{locale}/..., hence the "*/" patterns.
        if ($request->is('admin', 'admin/*', '*/dashboard', '*/profile', '*/membership/*', '*/invite/*',
            '*/reset-password/*', '*/verify/*', '*/register_verify/*', '*/subscriber/verify/*', '*/login', '*/register')) {
            $headers->set('Cache-Control', 'no-store, private');
        }
        // Personal pages and one-time links stay out of search engines.
        if ($request->is('*/membership/*', '*/invite/*', '*/reset-password/*', '*/verify/*', '*/register_verify/*', '*/subscriber/verify/*', '*/dashboard', '*/profile')) {
            $headers->set('X-Robots-Tag', 'noindex, nofollow');
        }

        $headers->remove('X-Powered-By');
        if (!headers_sent()) {
            header_remove('X-Powered-By');
        }

        return $response;
    }
}
