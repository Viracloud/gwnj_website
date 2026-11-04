<?php

/*
|--------------------------------------------------------------------------
| Website languages
|--------------------------------------------------------------------------
|
| Languages are managed in the admin panel (Settings → Languages, table
| site_languages): AppServiceProvider replaces the values below with the table's.
| The values here (and SITE_LOCALES / SITE_LOCALES_PREPARE / SITE_DEFAULT_LOCALE
| in .env) only apply before that table exists, and fill it when it is created.
|
| English ("en") is the base language: its text lives in the plain columns
| (title, content ...). Other languages use <column>_<code> (title_fa, title_fr ...),
| and fall back to English when a translation is empty.
|
| Hreflang, Open Graph and date settings of the built-in languages are kept from here.
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
