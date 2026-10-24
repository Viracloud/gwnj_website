<header class="masthead">
    <div class="shell" style="display:flex;align-items:center;gap:var(--s5);padding-block:var(--s3)">
        <a class="logo" href="{{ route('home') }}">
            <img src="{{ asset('uploads/'.$global_setting->logo_1) }}" alt="{{ config('app.name') }}">
            <span class="wm">
                <b>{{ __("Global Women's Justice Network") }}</b>
                <span>{{ __('EDUCATION · WORK · FREEDOM') }}</span>
            </span>
        </a>

        <nav class="main d-none d-lg-flex">
            <ul>
                @foreach($global_nav as $item)
                    @if($item->is_button)
                        {{-- button-style items render after the list, see below --}}
                    @elseif($item->children->count())
                    @php $item_url = $item->resolvedUrl(); @endphp
                    <li class="dropdown">
                        @if($item_url !== '#')
                        <a href="{{ $item_url }}" class="dropdown-parent-link {{ $item->isActive() ? 'here' : '' }}">{{ $item->label() }}</a>
                        <button type="button" class="dropdown-toggle dropdown-caret" data-bs-toggle="dropdown" aria-expanded="false" aria-label="{{ __('Open submenu') }}"></button>
                        @else
                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">{{ $item->label() }}</a>
                        @endif
                        <ul class="dropdown-menu">
                            @foreach($item->children as $child)
                                @if($child->visibleChildren->count())
                                <li class="dropdown-submenu">
                                    <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ $child->label() }}</a>
                                    <ul class="dropdown-menu">
                                        @foreach($child->visibleChildren as $grandchild)
                                        <li><a class="dropdown-item" href="{{ $grandchild->resolvedUrl() }}" @if($grandchild->open_new_tab) target="_blank" rel="noopener" @endif>{{ $grandchild->label() }}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                                @else
                                <li><a class="dropdown-item {{ $child->isActive() ? 'active' : '' }}" href="{{ $child->resolvedUrl() }}" @if($child->open_new_tab) target="_blank" rel="noopener" @endif>{{ $child->label() }}</a></li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                    @else
                    <li><a href="{{ $item->resolvedUrl() }}" class="{{ $item->isActive() ? 'here' : '' }}" @if($item->open_new_tab) target="_blank" rel="noopener" @endif>{{ $item->label() }}</a></li>
                    @endif
                @endforeach
            </ul>

            @foreach($global_nav as $item)
                @if($item->is_button)
                <a class="btn btn-brand" href="{{ $item->resolvedUrl() }}" @if($item->open_new_tab) target="_blank" rel="noopener" @endif>{{ $item->label() }}</a>
                @endif
            @endforeach
        </nav>

        <button class="navbar-toggler border-0 shadow-none d-lg-none ms-auto" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#drawer-navigation">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
</header>
