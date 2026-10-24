<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomPage extends Model
{
    use \App\Models\Concerns\SavesExtraLocales;

    public function images()
    {
        return $this->hasMany(CustomPageImage::class)->orderBy('sort_order')->orderBy('id');
    }

    /** Hidden pages are kept but not shown on the website, in menus or in the sitemap. */
    public function isVisible(): bool
    {
        return ($this->status ?? 'Show') !== 'Hide';
    }

    public function scopeVisible($query)
    {
        // Until the "status" migration has run on a server, every page counts as visible
        // (this also keeps `php artisan migrate` working, since menus are loaded at boot).
        static $has_status = null;
        $has_status ??= \Illuminate\Support\Facades\Schema::hasColumn('custom_pages', 'status');
        if (!$has_status) {
            return $query;
        }
        return $query->where(fn ($q) => $q->where('status', 'Show')->orWhereNull('status'));
    }
}
