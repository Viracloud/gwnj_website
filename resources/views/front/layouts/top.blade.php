<div class="topbar">
    <div class="shell">
        @foreach([1, 2, 3] as $n)
            @php $tb_text = trim(hc('topbar_'.$n.'_text')); @endphp
            @if($tb_text !== '' && $tb_text !== '-')
        <a class="tb-link" href="{{ hc_link('topbar_'.$n.'_link') }}">{{ $tb_text }}</a>
            @endif
        @endforeach
        <span class="sp"></span>
        <div class="lang-switch dropdown">
            <button type="button" class="lang-switch-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-globe" aria-hidden="true"></i>
                <span>{{ locale_meta()['native'] }}</span>
                <i class="fa-solid fa-chevron-down" aria-hidden="true" style="font-size:.6rem"></i>
            </button>
            {{-- Same page in every switched-on language --}}
            <ul class="dropdown-menu dropdown-menu-end">
                @foreach(alternate_urls() as $alt_locale => $alt_url)
                <li><a class="dropdown-item {{ $alt_locale === site_locale() ? 'active' : '' }}" href="{{ $alt_url }}" hreflang="{{ locale_meta($alt_locale)['hreflang'] }}" lang="{{ locale_meta($alt_locale)['hreflang'] }}">{{ locale_meta($alt_locale)['native'] }}</a></li>
                @endforeach
            </ul>
        </div>
        @if($global_menu->menu_login_status == 'Show')
            @if(Auth::guard('web')->check())
            <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
            @else
            <a href="{{ route('login') }}">{{ tf($global_menu, 'menu_login_name') }}</a>
            @endif
        @endif
        <button type="button" class="exit" onclick="window.location.replace('https://www.google.com')"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 4h3a2 2 0 012 2v12a2 2 0 01-2 2h-3M10 17l-5-5 5-5M5 12h11"/></svg>{{ __('Quick exit') }}</button>
    </div>
</div>
