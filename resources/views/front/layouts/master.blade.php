<!DOCTYPE html>
<html class="no-js" lang="{{ locale_meta()['hreflang'] }}" dir="{{ locale_meta()['dir'] }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    @php $seo = resolve_seo(); @endphp
    <title>{{ $seo['title'] }}</title>
    <meta name="description" content="{{ $seo['description'] }}">
    <link rel="canonical" href="{{ $seo['canonical'] }}">
    {{-- Every language version of this page, so Google indexes and serves each one --}}
    @php $alternates = hreflang_urls(); @endphp
    @foreach($alternates as $alt_locale => $alt_url)
    <link rel="alternate" hreflang="{{ locale_meta($alt_locale)['hreflang'] }}" href="{{ $alt_url }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ $alternates[base_locale()] ?? reset($alternates) }}">
    <meta property="og:locale" content="{{ locale_meta()['og'] }}">
    @foreach($alternates as $alt_locale => $alt_url)@if($alt_locale !== site_locale())
    <meta property="og:locale:alternate" content="{{ locale_meta($alt_locale)['og'] }}">
    @endif @endforeach
    <meta name="robots" content="{{ $seo['noindex'] ? 'noindex,nofollow' : 'index,follow' }}">
    @if($global_setting->seo_google_verification)
    <meta name="google-site-verification" content="{{ $global_setting->seo_google_verification }}">
    @endif

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Global Women's Justice Network">
    <meta property="og:title" content="{{ $seo['title'] }}">
    <meta property="og:description" content="{{ $seo['description'] }}">
    <meta property="og:url" content="{{ $seo['canonical'] }}">
    @if($seo['image'])<meta property="og:image" content="{{ $seo['image'] }}">@endif
    <meta name="twitter:card" content="{{ $seo['image'] ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $seo['title'] }}">
    <meta name="twitter:description" content="{{ $seo['description'] }}">
    @if($seo['image'])<meta name="twitter:image" content="{{ $seo['image'] }}">@endif

    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'NGO',
        'name' => "Global Women's Justice Network",
        'alternateName' => 'GWJN',
        'url' => url('/'),
        'logo' => asset('uploads/'.$global_setting->logo_1),
        'sameAs' => array_values(array_filter([
            $global_setting->footer_facebook ?? null,
            $global_setting->footer_twitter ?? null,
            $global_setting->footer_linkedin ?? null,
            $global_setting->footer_instagram ?? null,
        ])),
    ], JSON_UNESCAPED_SLASHES) !!}
    </script>

    @if($global_setting->seo_analytics_id)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $global_setting->seo_analytics_id }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $global_setting->seo_analytics_id }}');
    </script>
    @endif

    <link rel="icon" type="image/png" href="{{ asset('uploads/'.$global_setting->favicon) }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Vazirmatn:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @include('front.layouts.styles')

    @php $page_cover = $global_setting->banner && file_exists(public_path('uploads/'.$global_setting->banner)); @endphp

    <style>
        :root{
            --ink:{{ $global_setting->theme_ink ?: '#000000' }};
            --brand:{{ $global_setting->theme_color ?: '#8A3FFF' }};
            --signal:{{ $global_setting->theme_signal ?: '#C94B18' }};
            --hope:{{ $global_setting->theme_hope ?: '#2E8B57' }};
            @if($page_cover)--page-cover:url('{{ asset('uploads/'.$global_setting->banner) }}');@endif
        }
    </style>

</head>
<body class="gwjn{{ $page_cover ? ' has-page-cover' : '' }}">
    @include('front.layouts.top')
    @include('front.layouts.nav')
    @include('front.layouts.mobile-nav')

    @yield('banner')
    <main>
        @yield('content')
    </main>
    @include('front.layouts.footer')
    <button type="button" class="scroll-top" id="scrollTop" aria-label="{{ __('Back to top') }}" title="{{ __('Back to top') }}"><span class="scroll-top-in"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 19V5M5 12l7-7 7 7"/></svg></span></button>
    @include('front.layouts.scripts')
    @stack('scripts')
    <script>
    (function(){
        var b = document.getElementById('scrollTop'); if(!b) return;
        function update(){
            var y = window.pageYOffset || document.documentElement.scrollTop;
            var h = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            b.style.setProperty('--p', (h > 0 ? Math.min(100, y / h * 100) : 0) + '%');
            b.classList.toggle('show', y > 300);
        }
        window.addEventListener('scroll', update, {passive:true});
        window.addEventListener('resize', update);
        b.addEventListener('click', function(){ window.scrollTo({top:0, behavior:'smooth'}); });
        update();
    })();
    </script>

</body>
</html>