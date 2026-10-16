<!DOCTYPE html>
<html class="no-js" lang="{{ is_rtl() ? 'fa' : 'en' }}" @if(is_rtl()) dir="rtl" @endif>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ is_rtl() ? 'کارت عضویت' : 'Membership card' }} - {{ $user->fullName() }}</title>
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
        body{background:var(--v100,#f3f0ff);padding:var(--s7) var(--s5)}
        .admin-card-bar{max-width:800px;margin:0 auto var(--s6);display:flex;align-items:center;justify-content:space-between;gap:var(--s4)}
    </style>
</head>
<body>
    <div class="admin-card-bar no-print">
        <a class="more" href="{{ route('admin_membership_members') }}">&larr; {{ is_rtl() ? 'بازگشت به فهرست اعضا' : 'Back to Members' }}</a>
        <button type="button" class="btn btn-brand" onclick="window.print()">{{ is_rtl() ? 'چاپ کارت' : 'Print card' }}</button>
    </div>

    <div style="max-width:800px;margin:0 auto">
        @include('front.partials.membership-card', ['user' => $user])
    </div>
</body>
</html>
