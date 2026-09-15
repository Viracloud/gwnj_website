<!DOCTYPE html>
<html class="no-js" lang="{{ is_rtl() ? 'fa' : 'en' }}" @if(is_rtl()) dir="rtl" @endif>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    @if(Request::is('/'))
        <title>{{ tf($page_data,'seo_title') }}</title>
        <meta name="description" content="{{ tf($page_data,'seo_meta_description') }}">
    @endif

    @if(Request::is('about'))
        <title>{{ tf($page_data,'seo_title') }}</title>
        <meta name="description" content="{{ tf($page_data,'seo_meta_description') }}">
    @endif

    @if(Request::is('services') || Request::is('service/*'))
        <title>{{ tf($page_data,'seo_title') }}</title>
        <meta name="description" content="{{ tf($page_data,'seo_meta_description') }}">
    @endif

    @if(Request::is('projects') || Request::is('project/*'))
        <title>{{ tf($page_data,'seo_title') }}</title>
        <meta name="description" content="{{ tf($page_data,'seo_meta_description') }}">
    @endif

    @if(Request::is('events') || Request::is('event/*'))
        <title>{{ tf($page_data,'seo_title') }}</title>
        <meta name="description" content="{{ tf($page_data,'seo_meta_description') }}">
    @endif

    @if(Request::is('blog') || Request::is('post/*'))
        <title>{{ tf($page_data,'seo_title') }}</title>
        <meta name="description" content="{{ tf($page_data,'seo_meta_description') }}">
    @endif

    @if(Request::is('team-members') || Request::is('team-member/*'))
        <title>{{ tf($page_data,'seo_title') }}</title>
        <meta name="description" content="{{ tf($page_data,'seo_meta_description') }}">
    @endif

    @if(Request::is('gallery'))
        <title>{{ tf($page_data,'seo_title') }}</title>
        <meta name="description" content="{{ tf($page_data,'seo_meta_description') }}">
    @endif

    @if(Request::is('faq'))
        <title>{{ tf($page_data,'seo_title') }}</title>
        <meta name="description" content="{{ tf($page_data,'seo_meta_description') }}">
    @endif

    @if(Request::is('login') || Request::is('register') || Request::is('forget-password') || Request::is('reset-password/*'))
        <title>{{ tf($page_data,'seo_title') }}</title>
        <meta name="description" content="{{ tf($page_data,'seo_meta_description') }}">
    @endif

    @if(Request::is('dashboard') || Request::is('profile'))
        <title>{{ tf($page_data,'seo_title') }}</title>
        <meta name="description" content="{{ tf($page_data,'seo_meta_description') }}">
    @endif

    @if(Request::is('terms-of-use'))
        <title>{{ tf($page_data,'seo_title') }}</title>
        <meta name="description" content="{{ tf($page_data,'seo_meta_description') }}">
    @endif

    @if(Request::is('privacy-policy'))
        <title>{{ tf($page_data,'seo_title') }}</title>
        <meta name="description" content="{{ tf($page_data,'seo_meta_description') }}">
    @endif
    
    @if(Request::is('contact'))
        <title>{{ tf($page_data,'seo_title') }}</title>
        <meta name="description" content="{{ tf($page_data,'seo_meta_description') }}">
    @endif

    @if(Request::is('donate'))
        <title>{{ tf($page_data,'seo_title') }}</title>
        <meta name="description" content="{{ tf($page_data,'seo_meta_description') }}">
    @endif

    @if(Request::is('page/*'))
        @php
            $slug = request()->segment(2);
            $custom_page = App\Models\CustomPage::where('slug', $slug)->first();
        @endphp
        <title>{{ tf($custom_page,'seo_title') }}</title>
        <meta name="description" content="{{ tf($custom_page,'seo_meta_description') }}">
    @endif

    <link rel="icon" type="image/png" href="{{ asset('uploads/'.$global_setting->favicon) }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700;800&family=Newsreader:ital,opsz,wght@0,6..72,300;0,6..72,400;0,6..72,500;0,6..72,600;1,6..72,300;1,6..72,400&family=Vazirmatn:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @include('front.layouts.styles')

    <style>
        :root{
            --ink:{{ $global_setting->theme_ink ?: '#000000' }};
            --brand:{{ $global_setting->theme_color ?: '#8A3FFF' }};
            --signal:{{ $global_setting->theme_signal ?: '#C94B18' }};
            --hope:{{ $global_setting->theme_hope ?: '#2E8B57' }};
        }
    </style>

</head>
<body class="gwjn">
    @include('front.layouts.top')
    @include('front.layouts.nav')
    @include('front.layouts.mobile-nav')

    @yield('banner')
    <main>
        @yield('content')
    </main>
    @include('front.layouts.footer')
    @include('front.layouts.scripts')
    @stack('scripts')

</body>
</html>