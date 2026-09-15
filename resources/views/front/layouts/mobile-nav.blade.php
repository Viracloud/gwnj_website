<div class="offcanvas offcanvas-end text-white drawer-nav-style" tabindex="-1" id="drawer-navigation">
    <div class="offcanvas-header border-bottom border-secondary">
        <img src="{{ asset('uploads/'.$global_setting->logo_1) }}" alt="{{ config('app.name') }}">
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column">
            @foreach($global_nav as $item)
                @if($item->children->count())
                <li class="mobile-nav-item nav-item">
                    <a class="nav-link text-white d-flex justify-content-between align-items-center cursor-pointer"
                        data-bs-toggle="collapse" data-bs-target="#mobileSub{{ $item->id }}" role="button" aria-expanded="false">
                        {{ $item->label() }}
                        <i class="fa-solid fa-angle-down small transition-icon"></i>
                    </a>
                    <div class="collapse" id="mobileSub{{ $item->id }}">
                        <ul class="list-unstyled mb-0 ms-3 ps-3">
                            @foreach($item->children as $child)
                            <li><a class="text-decoration-none text-white d-block py-2 small" href="{{ $child->resolvedUrl() }}" @if($child->open_new_tab) target="_blank" rel="noopener" @endif>{{ $child->label() }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </li>
                @else
                <li class="nav-item"><a class="nav-link text-white {{ $item->isActive() ? 'active' : '' }}" href="{{ $item->resolvedUrl() }}" @if($item->open_new_tab) target="_blank" rel="noopener" @endif>{{ $item->label() }}</a></li>
                @endif
            @endforeach

            @if($global_menu->menu_login_status == 'Show')
                @if(Auth::guard('web')->check())
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('dashboard') }}">{{ is_rtl() ? 'داشبورد' : 'Dashboard' }}</a></li>
                @else
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('login') }}">{{ is_rtl() ? $global_menu->menu_login_name_sv : $global_menu->menu_login_name }}</a></li>
                @endif
            @endif
        </ul>
    </div>
</div>
