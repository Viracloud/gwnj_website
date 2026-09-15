<div class="topbar">
    <div class="shell">
        <a href="{{ route('blog') }}">{{ is_rtl() ? 'مرکز رسانه' : 'Media centre' }}</a>
        <a href="{{ route('donate') }}">{{ is_rtl() ? 'شفافیت' : 'Transparency' }}</a>
        <a href="{{ route('contact') }}">{{ is_rtl() ? 'تماس با ما' : 'Contact' }}</a>
        <span class="sp"></span>
        <div class="lang-switch dropdown">
            <button type="button" class="lang-switch-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-globe2" aria-hidden="true"></i>
                <span>{{ is_rtl() ? 'دری' : 'English' }}</span>
                <i class="fa-solid fa-chevron-down" aria-hidden="true" style="font-size:.6rem"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item {{ !is_rtl() ? 'active' : '' }}" href="{{ route('switch_lang', 'en') }}">English</a></li>
                <li><a class="dropdown-item {{ is_rtl() ? 'active' : '' }}" href="{{ route('switch_lang', 'fa') }}">دری</a></li>
            </ul>
        </div>
        <button type="button" class="exit" onclick="window.location.replace('https://www.google.com')">{{ is_rtl() ? 'خروج سریع' : 'Quick exit' }}</button>
    </div>
</div>
