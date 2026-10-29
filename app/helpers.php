<?php

use App\Models\Setting;

/* =====================================================================
   LANGUAGES
   Languages are configured in config/locales.php and switched on with
   SITE_LOCALES in .env. Every public page lives under /{locale}/...
   ===================================================================== */

if (!function_exists('enabled_locales')) {
    /** Codes of the switched-on languages, e.g. ['en', 'fa']. */
    function enabled_locales(): array
    {
        return config('locales.enabled', ['en']);
    }
}

if (!function_exists('extra_locales')) {
    /**
     * Languages edited with the generic "other languages" fields in the admin panel:
     * switched-on or in-preparation languages other than English and Dari
     * (Dari keeps its own dedicated fields in every form).
     */
    function extra_locales(): array
    {
        $codes = array_unique(array_merge(config('locales.enabled', []), config('locales.prepare', [])));
        return array_values(array_filter($codes, fn ($c) => !in_array($c, ['en', 'fa'], true)
            && array_key_exists($c, config('locales.supported'))));
    }
}

if (!function_exists('locale_meta')) {
    /** Settings of one language (name, native name, dir, hreflang ...). */
    function locale_meta(?string $code = null): array
    {
        $code ??= site_locale();
        return config('locales.supported.'.$code, config('locales.supported.en'));
    }
}

if (!function_exists('base_locale')) {
    /** The language stored in the plain columns (title, content ...). */
    function base_locale(): string
    {
        return config('locales.base', 'en');
    }
}

if (!function_exists('default_locale')) {
    /** Language for "/" when the visitor has no preference: SITE_DEFAULT_LOCALE, else the admin's direction setting. */
    function default_locale(): string
    {
        static $default = null;
        if ($default === null) {
            $configured = config('locales.default');
            if ($configured && in_array($configured, enabled_locales(), true)) {
                $default = $configured;
            } else {
                try {
                    $setting = Setting::where('id', 1)->first();
                } catch (\Throwable $e) {
                    $setting = null;
                }
                $default = ($setting && $setting->layout_direction == 'rtl' && in_array('fa', enabled_locales(), true)) ? 'fa' : base_locale();
            }
        }
        return $default;
    }
}

if (!function_exists('preferred_locale')) {
    /** The visitor's last chosen language (if still switched on), else the default. */
    function preferred_locale($request = null): string
    {
        $request ??= request();
        $saved = $request->hasSession() ? $request->session()->get('site_lang') : null;
        return in_array($saved, enabled_locales(), true) ? $saved : default_locale();
    }
}

if (!function_exists('site_locale')) {
    /** The active front-end language (set from the URL by the SetLocale middleware). */
    function site_locale(): string
    {
        $l = app()->getLocale();
        return in_array($l, enabled_locales(), true) ? $l : default_locale();
    }
}

if (!function_exists('is_rtl')) {
    /** Right-to-left layout for the active language (Dari). */
    function is_rtl(): bool
    {
        return (locale_meta()['dir'] ?? 'ltr') === 'rtl';
    }
}

if (!function_exists('tf')) {
    /**
     * A translatable field in the active language: "<field>_<locale>" when it is
     * filled in, otherwise the base (English) field.
     */
    function tf($model, string $field, ?string $locale = null): ?string
    {
        if (!$model) {
            return null;
        }
        $locale ??= site_locale();
        if ($locale !== base_locale()) {
            $v = $model->{$field.'_'.$locale} ?? null;
            if ($v !== null && trim((string) $v) !== '') {
                return $v;
            }
        }
        return $model->{$field} ?? null;
    }
}

if (!function_exists('slug_for')) {
    /** URL slug for a language: "slug_<locale>" when set, else the English slug. */
    function slug_for($model, ?string $locale = null): ?string
    {
        if (!$model) {
            return null;
        }
        $locale ??= site_locale();
        if ($locale !== base_locale()) {
            $s = trim((string) ($model->{'slug_'.$locale} ?? ''));
            if ($s !== '') {
                return $s;
            }
        }
        return $model->slug;
    }
}

if (!function_exists('slug_columns')) {
    /** Slug columns that exist on a table: slug, slug_fa, slug_fr ... (cached). */
    function slug_columns(string $table): array
    {
        static $cache = [];
        if (!isset($cache[$table])) {
            $cols = ['slug'];
            foreach (array_keys(config('locales.supported')) as $code) {
                if ($code !== base_locale() && \Illuminate\Support\Facades\Schema::hasColumn($table, 'slug_'.$code)) {
                    $cols[] = 'slug_'.$code;
                }
            }
            $cache[$table] = $cols;
        }
        return $cache[$table];
    }
}

if (!function_exists('where_slug')) {
    /** Add "matches the slug in any language" to a query. */
    function where_slug($query, string $slug)
    {
        $table = $query->getModel()->getTable();
        return $query->where(function ($q) use ($table, $slug) {
            foreach (slug_columns($table) as $col) {
                $q->orWhere($col, $slug);
            }
        });
    }
}

if (!function_exists('localized_url')) {
    /** The current page (or a given URL) in another language: swaps the /{locale}/ prefix. */
    function localized_url(string $locale, ?string $url = null): string
    {
        $url ??= request()->fullUrl();
        $base = rtrim(url('/'), '/');
        $rest = substr($url, strlen($base));
        $rest = preg_replace('#^/('.implode('|', array_map('preg_quote', array_keys(config('locales.supported')))).')(?=/|\?|$)#', '', $rest);
        return $base.'/'.$locale.$rest;
    }
}

if (!function_exists('set_alternates')) {
    /**
     * Pages whose address differs per language (translated slugs) register their
     * exact address in each language here, for the language switcher and hreflang.
     */
    function set_alternates(array $map): void
    {
        app()->instance('page.alternates', $map);
    }
}

if (!function_exists('set_model_alternates')) {
    /** set_alternates() for a model page: route name + the model's slug in each language. */
    function set_model_alternates(string $route, $model, string $param = 'slug'): void
    {
        $map = [];
        foreach (enabled_locales() as $l) {
            $map[$l] = route($route, ['locale' => $l, $param => slug_for($model, $l)]);
        }
        set_alternates($map);
        // Only languages the item is actually translated into are announced to search engines.
        app()->instance('page.translated', array_values(array_filter(enabled_locales(), fn ($l) => is_translated($model, $l))));
    }
}

if (!function_exists('is_translated')) {
    /**
     * Whether a content item has its own text in a language (English always counts).
     * Untranslated items still open in that language - showing the English text - but
     * are not offered to Google as a separate language version (no duplicate content).
     */
    function is_translated($model, string $locale): bool
    {
        if ($locale === base_locale()) {
            return true;
        }
        $attributes = $model->getAttributes();
        $field = array_key_exists('title', $attributes) ? 'title' : (array_key_exists('name', $attributes) ? 'name' : null);
        return $field === null || trim((string) ($attributes[$field.'_'.$locale] ?? '')) !== '';
    }
}

if (!function_exists('hreflang_urls')) {
    /** The language versions of this page that are announced to search engines (hreflang). */
    function hreflang_urls(): array
    {
        $all = alternate_urls();
        if (!app()->bound('page.translated')) {
            return $all;
        }
        return array_intersect_key($all, array_flip(app('page.translated')));
    }
}

if (!function_exists('canonical_url')) {
    /** This page's canonical address: itself, or the English version when it has no translation here. */
    function canonical_url(): string
    {
        $all = alternate_urls();
        $versions = hreflang_urls();
        return $versions[site_locale()] ?? $all[base_locale()] ?? url()->current();
    }
}

if (!function_exists('alternate_urls')) {
    /** This page's address in every switched-on language (used for hreflang and the switcher). */
    function alternate_urls(): array
    {
        $registered = app()->bound('page.alternates') ? app('page.alternates') : [];
        $map = [];
        foreach (enabled_locales() as $l) {
            $map[$l] = $registered[$l] ?? localized_url($l, url()->current());
        }
        return $map;
    }
}

if (!function_exists('fa_digits')) {
    /** Western digits → Persian digits (used for Dari). */
    function fa_digits($value): string
    {
        return strtr((string) $value, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴',
            '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']);
    }
}

if (!function_exists('local_digits')) {
    /** Digits in the active language's script. */
    function local_digits($value): string
    {
        return site_locale() === 'fa' ? fa_digits($value) : (string) $value;
    }
}

if (!function_exists('persian_slug')) {
    /**
     * Builds a URL slug straight from the Dari/Persian title itself - same letters
     * the admin typed, just turned into a valid slug (spaces to dashes, stray
     * punctuation stripped). It must NOT transliterate into Latin letters: a
     * transliterated slug like "hmkary-jdyd" is not the Persian title anymore,
     * it's a phonetic guess at it, which is wrong for a Dari-language page.
     */
    function persian_slug(?string $text): string
    {
        $text = mb_strtolower(trim((string) $text));
        $text = str_replace(["\u{200C}", "\u{200B}"], '-', $text);

        $text = preg_replace('/[\s_]+/u', '-', $text);
        $text = preg_replace('/[^\p{L}\p{N}\-]+/u', '', $text);
        return trim(preg_replace('/-+/u', '-', $text), '-');
    }
}


if (!function_exists('to_jalali')) {
    /** Gregorian -> Solar Hijri [year, month, day]. */
    function to_jalali(int $gy, int $gm, int $gd): array
    {
        $g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
        $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
        $days = 355666 + (365 * $gy) + intdiv($gy2 + 3, 4) - intdiv($gy2 + 99, 100)
            + intdiv($gy2 + 399, 400) + $gd + $g_d_m[$gm - 1];
        $jy = -1595 + (33 * intdiv($days, 12053));
        $days %= 12053;
        $jy += 4 * intdiv($days, 1461);
        $days %= 1461;
        if ($days > 365) {
            $jy += intdiv($days - 1, 365);
            $days = ($days - 1) % 365;
        }
        if ($days < 186) {
            $jm = 1 + intdiv($days, 31);
            $jd = 1 + ($days % 31);
        } else {
            $jm = 7 + intdiv($days - 186, 30);
            $jd = 1 + (($days - 186) % 30);
        }
        return [$jy, $jm, $jd];
    }
}

if (!function_exists('fdate')) {
    /**
     * Locale-aware date. English: normal Carbon format.
     * Dari: Solar Hijri calendar, Afghan month names, Persian digits.
     * Supports the tokens d j m n M F Y y.
     */
    function fdate($date, string $format = 'j F Y'): string
    {
        if (!$date) {
            return '';
        }
        $c = $date instanceof \Carbon\Carbon || $date instanceof \Carbon\CarbonImmutable
            ? $date : \Carbon\Carbon::parse($date);

        $calendar = locale_meta()['date'] ?? 'en';
        if ($calendar === 'en') {
            return $c->format($format);
        }
        if ($calendar !== 'jalali') {
            // French / German month and day names.
            return $c->copy()->locale($calendar)->translatedFormat($format);
        }

        [$jy, $jm, $jd] = to_jalali((int) $c->format('Y'), (int) $c->format('n'), (int) $c->format('j'));
        $months = ['حمل', 'ثور', 'جوزا', 'سرطان', 'اسد', 'سنبله', 'میزان', 'عقرب', 'قوس', 'جدی', 'دلو', 'حوت'];
        $map = [
            'd' => sprintf('%02d', $jd), 'j' => $jd,
            'm' => sprintf('%02d', $jm), 'n' => $jm,
            'M' => $months[$jm - 1], 'F' => $months[$jm - 1],
            'Y' => $jy, 'y' => substr((string) $jy, -2),
        ];
        $out = '';
        foreach (preg_split('//u', $format, -1, PREG_SPLIT_NO_EMPTY) as $ch) {
            $out .= array_key_exists($ch, $map) ? $map[$ch] : $ch;
        }
        return strtr($out, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴',
            '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']);
    }
}

if (!function_exists('resolve_slug_fa')) {
    /**
     * Dari slug for saving: the typed one, else the existing one, else generated
     * from the Dari title. Always unique within the table (against slug and slug_fa).
     */
    function resolve_slug_fa(string $table, ?string $typed, ?string $source, $id = null): ?string
    {
        $existing = $id ? \Illuminate\Support\Facades\DB::table($table)->where('id', $id)->value('slug_fa') : null;
        $slug = persian_slug($typed) ?: ($existing ?: persian_slug($source));
        if ($slug === '') {
            return null;
        }
        $base = $slug;
        $i = 2;
        while (\Illuminate\Support\Facades\DB::table($table)
            ->where(function ($q) use ($table, $slug) { foreach (slug_columns($table) as $col) { $q->orWhere($col, $slug); } })
            ->when($id, fn ($q) => $q->where('id', '!=', $id))
            ->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}

if (!function_exists('resolve_slug')) {
    /**
     * English slug for saving: the typed one, else the existing one, else generated
     * from the English title. Admins no longer have to type a slug by hand - it's
     * derived from the title automatically, same as the Dari slug already is; the
     * field stays editable only for when a specific custom URL is wanted.
     * Always unique within the table (against slug and slug_fa).
     */
    function resolve_slug(string $table, ?string $typed, ?string $source, $id = null): ?string
    {
        $existing = $id ? \Illuminate\Support\Facades\DB::table($table)->where('id', $id)->value('slug') : null;
        $slug = \Illuminate\Support\Str::slug((string) $typed) ?: ($existing ?: \Illuminate\Support\Str::slug((string) $source));
        if ($slug === '') {
            return null;
        }
        $base = $slug;
        $i = 2;
        while (\Illuminate\Support\Facades\DB::table($table)
            ->where(function ($q) use ($table, $slug) { foreach (slug_columns($table) as $col) { $q->orWhere($col, $slug); } })
            ->when($id, fn ($q) => $q->where('id', '!=', $id))
            ->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}

if (!function_exists('plain_excerpt')) {
    /** Plain-text preview of possibly-HTML content: tags stripped, entities (&zwnj; &nbsp; ...) decoded. */
    function plain_excerpt(?string $html, int $limit = 120): string
    {
        $text = html_entity_decode(strip_tags((string) $html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = str_replace("\u{00A0}", ' ', $text);
        $text = trim(preg_replace('/\s+/u', ' ', $text));
        return \Illuminate\Support\Str::limit($text, $limit);
    }
}

if (!function_exists('home_content_fields')) {
    /** Flat list of every field defined in config/home_content.php. */
    function home_content_fields(): array
    {
        static $flat = null;
        if ($flat === null) {
            $flat = [];
            foreach (config('home_content.sections', []) as $section) {
                foreach ($section['fields'] as $key => $def) {
                    $flat[$key] = $def;
                }
            }
        }
        return $flat;
    }
}

if (!function_exists('home_content_rows')) {
    function home_content_rows(): array
    {
        static $rows = null;
        if ($rows === null) {
            try {
                $rows = \App\Models\HomeText::all()->keyBy('key')->all();
            } catch (\Throwable $e) {
                $rows = [];
            }
        }
        return $rows;
    }
}

if (!function_exists('hc')) {
    /** Home-page text for the active language: admin value, else the default from config/home_content.php. */
    function hc(string $key): string
    {
        $def = home_content_fields()[$key] ?? null;
        $row = home_content_rows()[$key] ?? null;

        if (($def['type'] ?? null) === 'shared') {
            $v = $row->value ?? null;
            return ($v !== null && trim($v) !== '') ? $v : ($def['en'] ?? '');
        }

        // Active language: admin text, then the built-in default for that language,
        // then the English admin text, then the English default.
        $locale = site_locale();
        if ($locale !== base_locale()) {
            $v = $row->{'value_'.$locale} ?? null;
            if ($v !== null && trim($v) !== '') {
                return $v;
            }
            if (!empty($def[$locale])) {
                return $def[$locale];
            }
        }
        $v = $row->value ?? null;
        return ($v !== null && trim($v) !== '') ? $v : ($def['en'] ?? '');
    }
}

if (!function_exists('hc_link')) {
    function hc_link(string $key): string
    {
        $row = home_content_rows()[$key] ?? null;
        $v = trim((string) ($row->value ?? ''));
        if ($v !== '') {
            return preg_match('#^(https?:)?//#i', $v) ? $v : url($v);
        }
        $route = home_content_fields()[$key]['route'] ?? null;
        return $route && \Illuminate\Support\Facades\Route::has($route) ? route($route) : '#';
    }
}

if (!function_exists('hc_on')) {
    /** Is a home-page section switched on (toggles default to on). */
    function hc_on(string $toggle): bool
    {
        $row = home_content_rows()[$toggle] ?? null;
        return ($row->value ?? '1') !== '0';
    }
}

if (!function_exists('membership_qr_svg')) {
    /** Inline SVG QR code (no extra HTTP request) encoding the given verification URL. */
    function membership_qr_svg(string $data, int $size = 160): string
    {
        $builder = new \Endroid\QrCode\Builder\Builder(
            writer: new \Endroid\QrCode\Writer\SvgWriter(),
            data: $data,
            size: $size,
            margin: 6,
        );

        return $builder->build()->getString();
    }
}

if (!function_exists('resolve_seo')) {
    /**
     * Dynamically resolves title/description/image/canonical/indexability for the
     * CURRENT request, based on the matched route. Single item pages (event, post,
     * campaign, custom page) use their own record's seo_title/seo_meta_description
     * when set, falling back to their own title/excerpt - never a shared, duplicate
     * title across every item of that type. Listing pages fall back to their
     * PageXItem settings row. Everything else falls back to the sitewide default.
     * Called once from front.layouts.master - no per-controller wiring needed.
     */
    function resolve_seo(): array
    {
        $setting = Setting::first();
        $route = request()->route();
        $name = $route?->getName();

        $title = null;
        $description = null;
        $image = $setting?->seo_og_image ?: null;

        $item_resolvers = [
            'event' => function ($slug) {
                $event = where_slug(\App\Models\Event::query(), $slug)->first();
                if (!$event) return null;
                return [
                    'title' => tf($event, 'title'),
                    'description' => plain_excerpt(tf($event, 'description'), 160) ?: tf($event, 'event_location'),
                    'image' => $event->image,
                ];
            },
            'post' => function ($slug) {
                $post = where_slug(\App\Models\Post::query(), $slug)->first();
                if (!$post) return null;
                return [
                    'title' => tf($post, 'seo_title') ?: tf($post, 'title'),
                    'description' => tf($post, 'seo_meta_description') ?: plain_excerpt(tf($post, 'excerpt'), 160),
                    'image' => $post->image,
                ];
            },
            'campaign' => function ($slug) {
                $campaign = where_slug(\App\Models\Campaign::query(), $slug)->first();
                if (!$campaign) return null;
                return [
                    'title' => tf($campaign, 'seo_title') ?: tf($campaign, 'title'),
                    'description' => tf($campaign, 'seo_meta_description') ?: plain_excerpt(tf($campaign, 'excerpt'), 160),
                    'image' => $campaign->image,
                ];
            },
            'member' => function ($slug) {
                $member = where_slug(\App\Models\Member::query(), $slug)->first();
                if (!$member) return null;
                return [
                    'title' => tf($member, 'name') . ($member->designation ? ' — ' . tf($member, 'designation') : ''),
                    'description' => plain_excerpt(tf($member, 'designation'), 160),
                    'image' => $member->image,
                ];
            },
            'custom_page' => function ($slug) {
                $page = where_slug(\App\Models\CustomPage::query(), $slug)->first();
                if (!$page) return null;
                return [
                    'title' => tf($page, 'seo_title') ?: tf($page, 'title'),
                    'description' => tf($page, 'seo_meta_description') ?: plain_excerpt(tf($page, 'content'), 160),
                    'image' => null,
                ];
            },
        ];

        if ($name && isset($item_resolvers[$name])) {
            $slug = $route->parameter('slug') ?? $route->parameter('custom_page_id');
            $resolved = $slug ? $item_resolvers[$name]($slug) : null;
            if ($resolved) {
                $title = $resolved['title'];
                $description = $resolved['description'];
                $image = $resolved['image'] ?: $image;
            }
        }

        if (!$title) {
            $listing_models = [
                'home' => \App\Models\PageHomeItem::class,
                'about' => \App\Models\PageAboutItem::class,
                'events' => \App\Models\PageEventItem::class,
                'blog' => \App\Models\PageBlogItem::class,
                'members' => \App\Models\PageTeamItem::class,
                'gallery' => \App\Models\PageGalleryItem::class,
                'faq' => \App\Models\PageFaqItem::class,
                'login' => \App\Models\PageLoginItem::class,
                'register' => \App\Models\PageLoginItem::class,
                'dashboard' => \App\Models\PageUserItem::class,
                'profile' => \App\Models\PageUserItem::class,
                'terms' => \App\Models\PageTermsItem::class,
                'privacy' => \App\Models\PagePrivacyItem::class,
                'contact' => \App\Models\PageContactItem::class,
                'donate' => \App\Models\PageDonateItem::class,
            ];

            if ($name && isset($listing_models[$name])) {
                $row = $listing_models[$name]::first();
                if ($row) {
                    $title = tf($row, 'seo_title') ?: tf($row, 'page_title') ?: tf($row, 'heading');
                    $description = tf($row, 'seo_meta_description');
                }
            }
        }

        if (!$title) {
            $fallback_titles = [
                'campaigns' => __('Campaigns'),
                'publications' => __('Reports and publications'),
                'search' => __('Search'),
                'category' => __('Category'),
                'archive' => __('Archive'),
                'tag' => __('Tag'),
            ];
            $title = $fallback_titles[$name] ?? null;
        }

        $site_name = "Global Women's Justice Network";
        if (!$title) {
            $title = $site_name;
        } elseif (!str_contains($title, $site_name) && !str_contains($title, 'GWJN')) {
            $template = $setting?->seo_title_template ?: '%s — ' . $site_name;
            $title = str_contains($template, '%s') ? sprintf($template, $title) : $title . ' — ' . $site_name;
        }

        if (!$description) {
            $description = tf($setting, 'seo_default_description')
                ?: "An international network of women human rights defenders, organising campaigns, public actions and legal advocacy across fourteen countries.";
        }

        return [
            'title' => $title,
            'description' => plain_excerpt($description, 300),
            'image' => $image ? asset('uploads/' . $image) : null,
            'canonical' => canonical_url(),
            'noindex' => !($setting?->seo_robots_indexable),
        ];
    }
}

if (!function_exists('safe_mail')) {
    /**
     * Send a Websitemail without letting a broken mail server crash the request
     * (this site's SMTP has failed in production before - a contact form, donation
     * receipt or registration should never 500 just because mail delivery is down).
     */
    function safe_mail($to, string $subject, string $body): bool
    {
        try {
            \Mail::to($to)->send(new \App\Mail\Websitemail($subject, $body));
            return true;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('safe_mail: send failed', [
                'to' => is_string($to) ? $to : (is_object($to) && isset($to->email) ? $to->email : 'unknown'),
                'subject' => $subject,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}

if (!function_exists('sitemap_entries')) {
    /**
     * Builds the full list of sitemap URLs from live data - called by both the
     * public /sitemap.xml and the admin Sitemap settings preview, so what the
     * admin sees is exactly what search engines get. Every event/post/campaign/
     * member/page is queried fresh here, so anything added or edited in the
     * admin shows up the next time either is requested - no manual rebuild step.
     */
    function sitemap_entries(?Setting $setting): \Illuminate\Support\Collection
    {
        // One entry per page *per switched-on language*, each listing all its language
        // versions (hreflang), so Google indexes every language separately.
        $urls = collect();
        $locales = enabled_locales();

        $add = function (string $type, string $priority, $lastmod, \Closure $urlFor, $model = null) use (&$urls, $locales) {
            $alternates = [];
            foreach ($locales as $l) {
                if ($model === null || is_translated($model, $l)) {
                    $alternates[$l] = $urlFor($l);
                }
            }
            foreach ($alternates as $l => $loc) {
                $urls->push(['type' => $type, 'lang' => $l, 'loc' => $loc, 'priority' => $priority,
                             'lastmod' => $lastmod, 'alternates' => $alternates]);
            }
        };

        $add('Homepage', '1.0', null, fn ($l) => route('home', ['locale' => $l]));

        if (!$setting || $setting->sitemap_include_static) {
            $priority = $setting->sitemap_priority_static ?? '0.7';
            foreach (['about', 'gallery', 'faq', 'publications', 'events', 'campaigns', 'members', 'blog',
                      'terms', 'privacy', 'contact', 'donate', 'polls', 'membership_info', 'partners'] as $name) {
                if (\Illuminate\Support\Facades\Route::has($name)) {
                    $add('Static', (string) $priority, null, fn ($l) => route($name, ['locale' => $l]));
                }
            }
        }

        $models = [
            ['sitemap_include_events', 'sitemap_priority_events', '0.6', 'Event', 'event', fn () => \App\Models\Event::all()],
            ['sitemap_include_posts', 'sitemap_priority_posts', '0.6', 'Post', 'post', fn () => \App\Models\Post::all()],
            ['sitemap_include_campaigns', 'sitemap_priority_campaigns', '0.6', 'Campaign', 'campaign', fn () => \App\Models\Campaign::all()],
            ['sitemap_include_members', 'sitemap_priority_members', '0.5', 'Team member', 'member', fn () => \App\Models\Member::all()],
            ['sitemap_include_pages', 'sitemap_priority_pages', '0.5', 'Page', 'custom_page', fn () => \App\Models\CustomPage::visible()->get()],
        ];
        foreach ($models as [$include, $prio, $default_prio, $type, $route, $query]) {
            if ($setting && !$setting->{$include}) {
                continue;
            }
            $priority = (string) ($setting->{$prio} ?? $default_prio);
            foreach ($query() as $item) {
                $add($type, $priority, $item->updated_at, fn ($l) => route($route, ['locale' => $l, 'slug' => slug_for($item, $l)]), $item);
            }
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('polls')) {
            foreach (\App\Models\Poll::published()->get() as $poll) {
                $add('Poll', '0.5', $poll->updated_at, fn ($l) => route('poll', ['locale' => $l, 'slug' => $poll->slug]), $poll);
            }
        }

        return $urls;
    }
}


if (!function_exists('secure_token')) {
    /**
     * Unguessable token for email links (verification, password reset).
     * With $minutes, the expiry time is appended ("<random>.<unix-time>") so
     * the link can be rejected later without a separate database column.
     */
    function secure_token(?int $minutes = null): string
    {
        $token = \Illuminate\Support\Str::random(64);
        return $minutes ? $token.'.'.(time() + $minutes * 60) : $token;
    }
}

if (!function_exists('token_expired')) {
    /** True when a token made by secure_token($minutes) is past its expiry time. */
    function token_expired(?string $token): bool
    {
        if (!$token || !str_contains($token, '.')) {
            return true;
        }
        return (int) substr(strrchr($token, '.'), 1) < time();
    }
}

if (!function_exists('clean_html')) {
    /**
     * Sanitise rich-text HTML written in the admin editor before it is shown
     * on the public site: keeps normal formatting, links, images, tables and
     * video/map embeds from trusted hosts, but removes scripts, event-handler
     * attributes (onclick=...), javascript: links and other active content.
     */
    function clean_html(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        $remove_tags = ['script', 'style', 'object', 'embed', 'applet', 'form', 'input', 'button',
            'textarea', 'select', 'option', 'meta', 'link', 'base', 'frame', 'frameset', 'svg', 'math', 'template'];
        $iframe_hosts = ['www.youtube.com', 'youtube.com', 'www.youtube-nocookie.com', 'player.vimeo.com',
            'www.google.com', 'maps.google.com', 'www.facebook.com', 'www.instagram.com', 'instagram.com',
            'open.spotify.com', 'w.soundcloud.com'];

        $prev = libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8"><div id="__clean_root">'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        $xpath = new \DOMXPath($doc);

        foreach ($remove_tags as $tag) {
            foreach (iterator_to_array($xpath->query('//'.$tag)) as $node) {
                $node->parentNode?->removeChild($node);
            }
        }

        foreach (iterator_to_array($xpath->query('//iframe')) as $node) {
            $host = parse_url((string) $node->getAttribute('src'), PHP_URL_HOST);
            $scheme = parse_url((string) $node->getAttribute('src'), PHP_URL_SCHEME);
            if ($scheme !== 'https' || !in_array(strtolower((string) $host), $iframe_hosts, true)) {
                $node->parentNode?->removeChild($node);
            }
        }

        foreach (iterator_to_array($xpath->query('//*[@*]')) as $node) {
            foreach (iterator_to_array($node->attributes) as $attr) {
                $name = strtolower($attr->name);
                $value = preg_replace('/[\x00-\x20]+/', '', strtolower(html_entity_decode($attr->value)));
                if (str_starts_with($name, 'on') || $name === 'formaction' || $name === 'srcdoc') {
                    $node->removeAttribute($attr->name);
                } elseif (in_array($name, ['href', 'src', 'action', 'xlink:href', 'data', 'poster', 'background'], true)
                    && preg_match('/^(javascript|vbscript|data):/', $value)
                    && !preg_match('#^data:image/(png|jpe?g|gif|webp);#', $value)) {
                    $node->removeAttribute($attr->name);
                } elseif ($name === 'style' && preg_match('/expression\s*\(|javascript:|url\s*\(\s*[\'"]?\s*javascript/i', $attr->value)) {
                    $node->removeAttribute($attr->name);
                }
            }
            // Links that open a new tab must not get access to this page.
            if (strtolower($node->nodeName) === 'a' && strtolower($node->getAttribute('target')) === '_blank') {
                $node->setAttribute('rel', 'noopener noreferrer');
            }
        }

        // ---- Videos: nothing loads from YouTube / Facebook / Instagram until the visitor clicks ----
        $facade = function (array $video) use ($doc) {
            $frag = $doc->createDocumentFragment();
            $frag->appendXML(video_facade($video, 'vf-block'));
            return $frag;
        };
        // 1. Embedded players (e.g. inserted with the editor's "media" button) become click-to-play boxes.
        foreach (iterator_to_array($xpath->query('//iframe')) as $node) {
            if ($video = video_embed($node->getAttribute('src'))) {
                $node->parentNode?->replaceChild($facade($video), $node);
            }
        }
        // 2. A paragraph that only contains a video link becomes a click-to-play box.
        foreach (iterator_to_array($xpath->query('//p')) as $p) {
            $text = trim(preg_replace('/\s+/u', ' ', $p->textContent));
            $only_link = $xpath->query('.//*[not(self::a) and not(self::br) and not(self::span) and not(self::strong) and not(self::b)]', $p)->length === 0;
            if ($text !== '' && $only_link && preg_match('#^https?://\S+$#', $text) && ($video = video_embed($text))) {
                $p->parentNode?->replaceChild($facade($video), $p);
            }
        }
        // 3. Any other link to a video opens the player on the site instead of leaving it.
        foreach (iterator_to_array($xpath->query('//a[@href]')) as $a) {
            if ($video = video_embed($a->getAttribute('href'))) {
                $a->setAttribute('data-video-embed', $video['embed']);
                $a->setAttribute('data-video-portrait', $video['portrait'] ? '1' : '0');
                $a->setAttribute('class', trim($a->getAttribute('class').' video-link'));
            }
        }

        $root = $doc->getElementById('__clean_root');
        $out = '';
        foreach ($root ? $root->childNodes : [] as $child) {
            $out .= $doc->saveHTML($child);
        }
        return $out;
    }
}

if (!function_exists('clean_map_embed')) {
    /** Only allow a Google Maps (or OpenStreetMap) <iframe> in the contact page map field. */
    function clean_map_embed(?string $code): string
    {
        if (!$code || !preg_match('/<iframe[^>]+src=["\']([^"\']+)["\']/i', $code, $m)) {
            return '';
        }
        $src = html_entity_decode($m[1]);
        $host = strtolower((string) parse_url($src, PHP_URL_HOST));
        if (parse_url($src, PHP_URL_SCHEME) !== 'https'
            || !in_array($host, ['www.google.com', 'maps.google.com', 'www.openstreetmap.org'], true)) {
            return '';
        }
        return '<iframe src="'.e($src).'" width="100%" height="450" style="border:0" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
    }
}


if (!function_exists('video_embed')) {
    /**
     * Turn a YouTube / Instagram / Facebook / Vimeo link (or a bare YouTube id) into
     * the address of that platform's player, so the video can play inside the site.
     * Returns ['provider', 'label', 'embed', 'portrait'] or null when not recognised.
     */
    function video_embed(?string $url): ?array
    {
        $url = trim(html_entity_decode((string) $url));
        if ($url === '') {
            return null;
        }
        // A bare YouTube video id (e.g. from the "Youtube Id" admin field).
        if (preg_match('/^[A-Za-z0-9_-]{11}$/', $url)) {
            $url = 'https://www.youtube.com/watch?v='.$url;
        }
        $p = parse_url($url);
        if (!in_array(strtolower($p['scheme'] ?? ''), ['http', 'https'], true)) {
            return null;
        }
        $host = preg_replace('/^(www\.|m\.|mobile\.|web\.)/', '', strtolower($p['host'] ?? ''));
        $path = $p['path'] ?? '';
        parse_str($p['query'] ?? '', $q);

        // YouTube (privacy-enhanced player: no cookies until the visitor presses play)
        if (in_array($host, ['youtube.com', 'youtu.be', 'youtube-nocookie.com', 'music.youtube.com'], true)) {
            $id = null;
            $portrait = false;
            if ($host === 'youtu.be') {
                $id = trim($path, '/');
            } elseif (!empty($q['v'])) {
                $id = $q['v'];
            } elseif (preg_match('#^/(embed|shorts|live|v)/([A-Za-z0-9_-]{11})#', $path, $m)) {
                $id = $m[2];
                $portrait = $m[1] === 'shorts';
            }
            if ($id && preg_match('/^[A-Za-z0-9_-]{11}$/', $id)) {
                return ['provider' => 'youtube', 'label' => 'YouTube', 'portrait' => $portrait,
                        'embed' => 'https://www.youtube-nocookie.com/embed/'.$id.'?autoplay=1&rel=0'];
            }
            return null;
        }

        // Vimeo
        if (in_array($host, ['vimeo.com', 'player.vimeo.com'], true) && preg_match('#/(?:video/)?(\d{5,})#', $path, $m)) {
            return ['provider' => 'vimeo', 'label' => 'Vimeo', 'portrait' => false,
                    'embed' => 'https://player.vimeo.com/video/'.$m[1].'?autoplay=1&dnt=1'];
        }

        // Instagram posts, reels and IGTV
        if ($host === 'instagram.com' && preg_match('#^/(p|reel|reels|tv)/([A-Za-z0-9_-]+)#', $path, $m)) {
            $kind = $m[1] === 'reels' ? 'reel' : $m[1];
            return ['provider' => 'instagram', 'label' => 'Instagram', 'portrait' => true,
                    'embed' => 'https://www.instagram.com/'.$kind.'/'.$m[2].'/embed/'];
        }

        // Facebook videos, reels and watch links (public videos only — a Facebook limitation)
        if (in_array($host, ['facebook.com', 'fb.watch', 'fb.com'], true)) {
            $is_video = $host === 'fb.watch'
                || preg_match('#/(videos|reel|watch|share/v|share/r)\b#', $path)
                || !empty($q['v']);
            if (!$is_video) {
                return null;
            }
            if ($host !== 'fb.watch' && str_starts_with($path, '/plugins/video.php') && !empty($q['href'])) {
                $url = $q['href'];
            }
            $portrait = (bool) preg_match('#/(reel|share/r)\b#', $path);
            return ['provider' => 'facebook', 'label' => 'Facebook', 'portrait' => $portrait,
                    'embed' => 'https://www.facebook.com/plugins/video.php?href='.rawurlencode($url).'&show_text=false&autoplay=true'];
        }

        return null;
    }
}

if (!function_exists('video_facade')) {
    /**
     * HTML for a click-to-play video box. Nothing is loaded from YouTube / Facebook /
     * Instagram until the visitor presses play; the player then opens on the site.
     */
    function video_facade(array $video, string $class = ''): string
    {
        $label = __('Play video');
        return '<button type="button" class="video-facade vf-'.e($video['provider']).' '.e($class).'"'
            .' data-video-embed="'.e($video['embed']).'" data-video-portrait="'.($video['portrait'] ? '1' : '0').'"'
            .' aria-label="'.e($label.' — '.$video['label']).'">'
            .'<span class="vf-play" aria-hidden="true"></span>'
            .'<span class="vf-text"><b>'.e($label).'</b><small>'.e($video['label']).'</small></span>'
            .'</button>';
    }
}
