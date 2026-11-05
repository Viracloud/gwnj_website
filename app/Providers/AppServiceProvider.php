<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\Setting;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\CustomPage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->applySiteLanguages();

        // On a fresh install the tables don't exist until "php artisan migrate" has run, and that
        // command boots this provider too: share empty values then instead of failing.
        try {
            $setting = Setting::where('id', 1)->first();
            $menu = Menu::where('id', 1)->first();
            $custom_pages = CustomPage::orderBy('title')->get();
            $nav = MenuItem::whereNull('parent_id')
                ->where('status', 'Show')
                ->linkVisible()
                ->orderBy('order')
                // each level comes with its linked page and visible sub-items, so building the menu
                // doesn't query the database once per item
                ->with(['customPage', 'children' => function ($q) {
                    $q->where('status', 'Show')->linkVisible()->orderBy('order')
                        ->with(['customPage', 'visibleChildren.customPage']);
                }])
                ->get();
            $footer_items = MenuItem::where('status', 'Show')
                ->linkVisible()
                ->where('show_in_footer', true)
                ->orderBy('order')
                ->with('customPage')
                ->get();
        } catch (\Illuminate\Database\QueryException $e) {
            if (!$this->app->runningInConsole()) {
                throw $e;
            }
            $setting = $menu = null;
            $custom_pages = $nav = $footer_items = collect();
        }
        view()->share('global_setting', $setting);
        // Menu::id(1) is kept only for the auth-aware Login/Dashboard button (see nav.blade.php),
        // not for the main navigation — that now lives in menu_items (see MenuItem / AdminMenuController).
        view()->share('global_menu', $menu);
        view()->share('global_custom_pages', $custom_pages);
        view()->share('global_nav', $nav);
        view()->share('global_footer_items', $footer_items);

        // Form rate limits, counted separately for each form (route) and visitor IP.
        // (Plain "throttle:N,1" would share one counter across every form.)
        foreach ([3, 5, 10] as $perMinute) {
            \Illuminate\Support\Facades\RateLimiter::for('forms'.$perMinute, fn (\Illuminate\Http\Request $request) =>
                \Illuminate\Cache\RateLimiting\Limit::perMinute($perMinute)
                    ->by(($request->route()?->getName() ?? $request->path()).'|'.$request->ip()));
        }

        // Sign-in: per IP, and per account so password guessing spread over many IPs is stopped too.
        \Illuminate\Support\Facades\RateLimiter::for('login', fn (\Illuminate\Http\Request $request) => [
            \Illuminate\Cache\RateLimiting\Limit::perMinute(5)
                ->by('ip|'.($request->route()?->getName() ?? '').'|'.$request->ip()),
            \Illuminate\Cache\RateLimiting\Limit::perHour(10)
                ->by('acct|'.($request->route()?->getName() ?? '').'|'.strtolower(trim((string) $request->input('email')))),
        ]);

        // Links to public pages built outside a /{locale}/ request (admin panel, e-mails)
        // use the default language; the SetLocale middleware overrides this per request.
        \Illuminate\Support\Facades\URL::defaults(['locale' => default_locale()]);

        Paginator::useBootstrapFive();
    }

    /**
     * Languages from Settings → Languages (the site_languages table) replace the .env ones
     * in config('locales.*'). Routes are registered after this, so their {locale} pattern
     * follows the table too. Without the table (before migrating) .env stays in charge.
     */
    private function applySiteLanguages(): void
    {
        try {
            $rows = \App\Models\SiteLanguage::orderBy('sort_order')->orderBy('id')->get();
        } catch (\Throwable $e) {
            return;
        }
        if ($rows->isEmpty()) {
            return;
        }

        $base = config('locales.base', 'en');
        $known = config('locales.supported', []);
        $supported = [];
        foreach ($rows as $row) {
            $supported[$row->code] = [
                'name' => $row->name,
                'native' => $row->native,
                'dir' => $row->dir === 'rtl' ? 'rtl' : 'ltr',
                // Technical codes are not edited in the panel: kept for the built-in languages.
                'hreflang' => $known[$row->code]['hreflang'] ?? $row->code,
                'og' => $known[$row->code]['og'] ?? $row->code,
                'date' => $known[$row->code]['date'] ?? $row->code,
            ];
        }
        if (!isset($supported[$base]) && isset($known[$base])) {
            $supported = [$base => $known[$base]] + $supported;
        }

        $enabled = $rows->where('status', 'published')->pluck('code')->all();
        if (!in_array($base, $enabled, true)) {
            array_unshift($enabled, $base);
        }
        $default = optional($rows->first(fn ($r) => $r->is_default && in_array($r->code, $enabled, true)))->code;

        config([
            'locales.supported' => $supported,
            'locales.enabled' => array_values($enabled),
            'locales.prepare' => $rows->where('status', 'prepare')->pluck('code')->values()->all(),
            'locales.default' => $default ?: '',
        ]);
    }
}
