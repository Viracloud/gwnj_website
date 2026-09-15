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
            ->orderBy('order')
            ->with(['children' => function ($q) {
                $q->where('status', 'Show')->orderBy('order');
            }])
            ->get());
        view()->share('global_footer_items', MenuItem::where('status', 'Show')
            ->where('show_in_footer', true)
            ->orderBy('order')
            ->get());

        Paginator::useBootstrapFive();
    }
}
