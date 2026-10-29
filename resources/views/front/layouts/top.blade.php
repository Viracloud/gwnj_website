<div class="topbar">
    <div class="shell">
        <a class="tb-link" href="{{ route('blog') }}">{{ __('Media centre') }}</a>
        <a class="tb-link" href="{{ route('donate') }}">{{ __('Transparency') }}</a>
        <a class="tb-link" href="{{ route('contact') }}">{{ __('Contact') }}</a>
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
        <button type="button" class="exit" onclick="window.location.replace('https://www.google.com')">{{ __('Quick exit') }}</button>
    </div>
</div>
