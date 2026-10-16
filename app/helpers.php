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

if (!function_exists('slug_for')) {
    /** URL slug for the active language: Dari slug in Dari mode when set, else the English one. */
    function slug_for($model): ?string
    {
        if (!$model) {
            return null;
        }
        if (is_rtl() && trim((string) ($model->slug_sv ?? '')) !== '') {
            return $model->slug_sv;
        }
        return $model->slug;
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

        if (!is_rtl()) {
            return $c->format($format);
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

if (!function_exists('resolve_slug_sv')) {
    /**
     * Dari slug for saving: the typed one, else the existing one, else generated
     * from the Dari title. Always unique within the table (against slug and slug_sv).
     */
    function resolve_slug_sv(string $table, ?string $typed, ?string $source, $id = null): ?string
    {
        $existing = $id ? \Illuminate\Support\Facades\DB::table($table)->where('id', $id)->value('slug_sv') : null;
        $slug = persian_slug($typed) ?: ($existing ?: persian_slug($source));
        if ($slug === '') {
            return null;
        }
        $base = $slug;
        $i = 2;
        while (\Illuminate\Support\Facades\DB::table($table)
            ->where(fn ($q) => $q->where('slug', $slug)->orWhere('slug_sv', $slug))
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
     * Always unique within the table (against slug and slug_sv).
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
            ->where(fn ($q) => $q->where('slug', $slug)->orWhere('slug_sv', $slug))
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

        if (is_rtl()) {
            $v = $row->value_sv ?? null;
            return ($v !== null && trim($v) !== '') ? $v : ($def['fa'] ?? ($def['en'] ?? ''));
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
                $event = \App\Models\Event::where('slug', $slug)->orWhere('slug_sv', $slug)->first();
                if (!$event) return null;
                return [
                    'title' => tf($event, 'title'),
                    'description' => plain_excerpt(tf($event, 'description'), 160) ?: tf($event, 'event_location'),
                    'image' => $event->image,
                ];
            },
            'post' => function ($slug) {
                $post = \App\Models\Post::where('slug', $slug)->orWhere('slug_sv', $slug)->first();
                if (!$post) return null;
                return [
                    'title' => tf($post, 'seo_title') ?: tf($post, 'title'),
                    'description' => tf($post, 'seo_meta_description') ?: plain_excerpt(tf($post, 'excerpt'), 160),
                    'image' => $post->image,
                ];
            },
            'campaign' => function ($slug) {
                $campaign = \App\Models\Campaign::where('slug', $slug)->orWhere('slug_sv', $slug)->first();
                if (!$campaign) return null;
                return [
                    'title' => tf($campaign, 'seo_title') ?: tf($campaign, 'title'),
                    'description' => tf($campaign, 'seo_meta_description') ?: plain_excerpt(tf($campaign, 'excerpt'), 160),
                    'image' => $campaign->image,
                ];
            },
            'member' => function ($slug) {
                $member = \App\Models\Member::where('slug', $slug)->orWhere('slug_sv', $slug)->first();
                if (!$member) return null;
                return [
                    'title' => tf($member, 'name') . ($member->designation ? ' — ' . tf($member, 'designation') : ''),
                    'description' => plain_excerpt(tf($member, 'designation'), 160),
                    'image' => $member->image,
                ];
            },
            'custom_page' => function ($slug) {
                $page = \App\Models\CustomPage::where('slug', $slug)->orWhere('slug_sv', $slug)->first();
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
                'campaigns' => is_rtl() ? 'کمپین‌ها' : 'Campaigns',
                'publications' => is_rtl() ? 'گزارش‌ها و انتشارات' : 'Reports and publications',
                'search' => is_rtl() ? 'جستجو' : 'Search',
                'category' => is_rtl() ? 'دسته‌بندی' : 'Category',
                'archive' => is_rtl() ? 'بایگانی' : 'Archive',
                'tag' => is_rtl() ? 'برچسب' : 'Tag',
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
            'canonical' => url()->current(),
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
        $urls = collect([
            ['type' => 'Homepage', 'loc' => url('/'), 'priority' => '1.0', 'lastmod' => null],
        ]);

        if (!$setting || $setting->sitemap_include_static) {
            $priority = $setting->sitemap_priority_static ?? '0.7';
            $static_routes = [
                'about', 'gallery', 'faq', 'publications', 'events', 'campaigns',
                'team-members', 'blog', 'terms-of-use', 'privacy-policy', 'contact', 'donate',
            ];
            foreach ($static_routes as $path) {
                $urls->push(['type' => 'Static', 'loc' => url('/' . $path), 'priority' => $priority, 'lastmod' => null]);
            }
        }

        if (!$setting || $setting->sitemap_include_events) {
            $priority = $setting->sitemap_priority_events ?? '0.6';
            foreach (\App\Models\Event::all() as $event) {
                $urls->push(['type' => 'Event', 'loc' => url('/event/' . slug_for($event)), 'lastmod' => $event->updated_at, 'priority' => $priority]);
            }
        }

        if (!$setting || $setting->sitemap_include_posts) {
            $priority = $setting->sitemap_priority_posts ?? '0.6';
            foreach (\App\Models\Post::all() as $post) {
                $urls->push(['type' => 'Post', 'loc' => url('/post/' . slug_for($post)), 'lastmod' => $post->updated_at, 'priority' => $priority]);
            }
        }

        if (!$setting || $setting->sitemap_include_campaigns) {
            $priority = $setting->sitemap_priority_campaigns ?? '0.6';
            foreach (\App\Models\Campaign::all() as $campaign) {
                $urls->push(['type' => 'Campaign', 'loc' => url('/campaign/' . slug_for($campaign)), 'lastmod' => $campaign->updated_at, 'priority' => $priority]);
            }
        }

        if (!$setting || $setting->sitemap_include_members) {
            $priority = $setting->sitemap_priority_members ?? '0.5';
            foreach (\App\Models\Member::all() as $member) {
                $urls->push(['type' => 'Team member', 'loc' => url('/team-member/' . slug_for($member)), 'lastmod' => $member->updated_at, 'priority' => $priority]);
            }
        }

        if (!$setting || $setting->sitemap_include_pages) {
            $priority = $setting->sitemap_priority_pages ?? '0.5';
            foreach (\App\Models\CustomPage::all() as $page) {
                $urls->push(['type' => 'Page', 'loc' => url('/page/' . slug_for($page)), 'lastmod' => $page->updated_at, 'priority' => $priority]);
            }
        }

        return $urls;
    }
}
