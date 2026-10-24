<!DOCTYPE html>
<html class="no-js" lang="{{ locale_meta()['hreflang'] }}" dir="{{ locale_meta()['dir'] }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ __('Membership card') }} - {{ $user->fullName() }}</title>
    <link rel="icon" type="image/png" href="{{ asset('uploads/'.$global_setting->favicon) }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Vazirmatn:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
        <a class="more" href="{{ route('admin_membership_members') }}">&larr; {{ __('Back to Members') }}</a>
        <button type="button" class="btn btn-brand" onclick="window.print()">{{ __('Print card') }}</button>
    </div>

    <div style="max-width:800px;margin:0 auto">
        @include('front.partials.membership-card', ['user' => $user])
    </div>
</body>
</html>
