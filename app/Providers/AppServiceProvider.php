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
        $setting = Setting::where('id', 1)->first();
        view()->share('global_setting', $setting);
        // Menu::id(1) is kept only for the auth-aware Login/Dashboard button (see nav.blade.php),
        // not for the main navigation — that now lives in menu_items (see MenuItem / AdminMenuController).
        view()->share('global_menu', Menu::where('id', 1)->first());
        view()->share('global_custom_pages', CustomPage::orderBy('title')->get());
        view()->share('global_nav', MenuItem::whereNull('parent_id')
            ->where('status', 'Show')
            ->linkVisible()
            ->orderBy('order')
            ->with(['children' => function ($q) {
                $q->where('status', 'Show')->linkVisible()->orderBy('order');
            }])
            ->get());
        view()->share('global_footer_items', MenuItem::where('status', 'Show')
            ->linkVisible()
            ->where('show_in_footer', true)
            ->orderBy('order')
            ->get());

        // Form rate limits, counted separately for each form (route) and visitor IP.
        // (Plain "throttle:N,1" would share one counter across every form.)
        foreach ([3, 5, 10] as $perMinute) {
            \Illuminate\Support\Facades\RateLimiter::for('forms'.$perMinute, fn (\Illuminate\Http\Request $request) =>
                \Illuminate\Cache\RateLimiting\Limit::perMinute($perMinute)
                    ->by(($request->route()?->getName() ?? $request->path()).'|'.$request->ip()));
        }

        // Links to public pages built outside a /{locale}/ request (admin panel, e-mails)
        // use the default language; the SetLocale middleware overrides this per request.
        \Illuminate\Support\Facades\URL::defaults(['locale' => default_locale()]);

        Paginator::useBootstrapFive();
    }
}
