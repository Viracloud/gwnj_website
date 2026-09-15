<div class="aside-box">
    <h3>{{ is_rtl() ? 'حساب کاربری' : 'My account' }}</h3>
    <div class="in">
        <ul class="linklist">
            <li><a href="{{ route('dashboard') }}" style="{{ request()->routeIs('dashboard') ? 'color:var(--brand)' : '' }}"><i class="fa-regular fa-user"></i> {{ is_rtl() ? 'داشبورد' : 'Dashboard' }}</a></li>
            <li><a href="{{ route('profile') }}" style="{{ request()->routeIs('profile') ? 'color:var(--brand)' : '' }}"><i class="fa-regular fa-pen-to-square"></i> {{ is_rtl() ? 'ویرایش پروفایل' : 'Profile' }}</a></li>
            <li><a href="{{ route('logout') }}" style="color:var(--signal)"><i class="fa-solid fa-right-from-bracket"></i> {{ is_rtl() ? 'خروج' : 'Log out' }}</a></li>
        </ul>
    </div>
</div>
