<?php

/*
|--------------------------------------------------------------------------
| Website languages
|--------------------------------------------------------------------------
|
| Every language the site *can* use is listed under "supported".
| Which ones are switched on is controlled by SITE_LOCALES in .env, e.g.
|
|     SITE_LOCALES=en,fa          (current)
|     SITE_LOCALES=en,fa,fr,de    (after French and German are approved)
|
| English ("en") is the base language: its text lives in the plain columns
| (title, content ...). Other languages use <column>_<code> (title_fa, title_fr ...),
| and fall back to English when a translation is empty.
|
| After changing SITE_LOCALES on a server run:  php artisan config:clear
*/

$enabled = array_values(array_filter(array_map('trim', explode(',', (string) env('SITE_LOCALES', 'en,fa')))));

return [

    'base' => 'en',

    'supported' => [
        'en' => ['name' => 'English',  'native' => 'English',  'dir' => 'ltr', 'hreflang' => 'en',    'og' => 'en_US', 'date' => 'en'],
        'fa' => ['name' => 'Dari',     'native' => 'دری',      'dir' => 'rtl', 'hreflang' => 'fa-AF', 'og' => 'fa_AF', 'date' => 'jalali'],
        'fr' => ['name' => 'French',   'native' => 'Français', 'dir' => 'ltr', 'hreflang' => 'fr',    'og' => 'fr_FR', 'date' => 'fr'],
        'de' => ['name' => 'German',   'native' => 'Deutsch',  'dir' => 'ltr', 'hreflang' => 'de',    'og' => 'de_DE', 'date' => 'de'],
    ],

    // Switched-on languages (always includes the base language).
    'enabled' => array_values(array_unique(array_merge(['en'], $enabled))),

    // Languages being prepared: their translation fields appear in the admin panel
    // so content can be translated BEFORE the language is published, e.g.
    //     SITE_LOCALES_PREPARE=fr,de
    'prepare' => array_values(array_filter(array_map('trim', explode(',', (string) env('SITE_LOCALES_PREPARE', ''))))),

    // Language used for "/" and for old addresses without a language prefix
    // when the visitor has no saved preference. Empty = follow the admin
    // "Language direction" setting (RTL → fa, otherwise en).
    'default' => env('SITE_DEFAULT_LOCALE', ''),
];
