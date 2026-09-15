<?php

use App\Models\Setting;

if (!function_exists('site_locale')) {
    /**
     * The active front-end locale: the visitor's own session choice
     * (set via the header language switcher) if they made one, otherwise
     * the admin's site-wide default (Setting::layout_direction).
     */
    function site_locale(): string
    {
        static $locale = null;
        if ($locale === null) {
            if (session()->has('site_lang') && in_array(session('site_lang'), ['en', 'fa'])) {
                $locale = session('site_lang');
            } else {
                $setting = Setting::where('id', 1)->first();
                $locale = ($setting && $setting->layout_direction == 'rtl') ? 'fa' : 'en';
            }
        }
        return $locale;
    }
}

if (!function_exists('is_rtl')) {
    function is_rtl(): bool
    {
        return site_locale() === 'fa';
    }
}

if (!function_exists('tf')) {
    /**
     * Return the Dari (_sv) value of a field when the site is in RTL mode
     * and that value is filled in, otherwise fall back to the base field.
     */
    function tf($model, string $field): ?string
    {
        if (!$model) {
            return null;
        }

        if (is_rtl()) {
            $sv = $model->{$field . '_sv'} ?? null;
            if ($sv !== null && trim($sv) !== '') {
                return $sv;
            }
        }

        return $model->{$field} ?? null;
    }
}
