<div class="aside-box">
    <h3>{{ __('My account') }}</h3>
    <div class="in">
        <ul class="linklist">
            <li><a href="{{ route('dashboard') }}" style="{{ request()->routeIs('dashboard') ? 'color:var(--brand)' : '' }}"><i class="fa-regular fa-user"></i> {{ __('Dashboard') }}</a></li>
            <li><a href="{{ route('profile') }}" style="{{ request()->routeIs('profile') ? 'color:var(--brand)' : '' }}"><i class="fa-regular fa-pen-to-square"></i> {{ __('Profile') }}</a></li>
            <li><form action="{{ route('logout') }}" method="post" class="m-0">@csrf<button type="submit" style="color:var(--signal);border:0;background:none;padding:0;font:inherit;cursor:pointer"><i class="fa-solid fa-right-from-bracket"></i> {{ __('Log out') }}</button></form></li>
        </ul>
    </div>
</div>
