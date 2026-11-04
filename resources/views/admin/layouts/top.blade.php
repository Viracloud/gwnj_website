<header class="topbar">
    <div class="d-flex align-items-center gap-3">
        <button onclick="toggleSidebar()" class="topbar-btn d-lg-none">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="topbar-search hidden-mobile">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="adminSearch" placeholder="Find a section… (e.g. events, logo)" autocomplete="off">
            <div id="adminSearchResults" class="topbar-search-results"></div>
        </div>
    </div>
    <div class="d-flex align-items-center gap-3">
        <a href="{{ url('/') }}" target="_blank" class="btn-primary-custom"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View Website</a>
        <div class="position-relative" id="userDropdown">
            <button onclick="$('#userMenu').toggleClass('show')" class="user-dropdown-btn">
                <img src="{{ asset('uploads/'.Auth::guard('admin')->user()->photo) }}" alt="{{ Auth::guard('admin')->user()->name }}">
                <span class="hidden-mobile">{{ Auth::guard('admin')->user()->name }}</span>
                <i class="fa-solid fa-chevron-down"></i>
            </button>
            <div id="userMenu" class="user-dropdown-menu">
                <a href="{{ route('admin_profile') }}" class="text-dark-custom">
                    <i class="fa-regular fa-user"></i>Profile
                </a>
                <form action="{{ route('admin_logout') }}" method="post" class="m-0">
                    @csrf
                    <button type="submit" class="text-danger-custom" style="display:flex;align-items:center;width:100%;border:0;background:none;font:inherit;text-align:start;cursor:pointer">
                        <i class="fa-solid fa-right-from-bracket"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>