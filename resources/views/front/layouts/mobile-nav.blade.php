<div class="offcanvas offcanvas-end text-white drawer-nav-style" tabindex="-1" id="drawer-navigation">
    <div class="offcanvas-header border-bottom border-secondary">
        <img src="{{ asset('uploads/'.$global_setting->logo_1) }}" alt="{{ config('app.name') }}">
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column">
            @foreach($global_nav as $item)
                @if($item->children->count())
                @php $item_url = $item->resolvedUrl(); @endphp
                <li class="mobile-nav-item nav-item">
                    <div class="d-flex justify-content-between align-items-center">
                        @if($item_url !== '#')
                        <a class="nav-link text-white flex-grow-1 {{ $item->isActive() ? 'active' : '' }}" href="{{ $item_url }}">{{ $item->label() }}</a>
                        @else
                        <span class="nav-link text-white flex-grow-1">{{ $item->label() }}</span>
                        @endif
                        <button type="button" class="mobile-caret-btn text-white" data-bs-toggle="collapse" data-bs-target="#mobileSub{{ $item->id }}" aria-expanded="false" aria-label="{{ __('Open submenu') }}">
                            <i class="fa-solid fa-angle-down small transition-icon"></i>
                        </button>
                    </div>
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

        </ul>
    </div>
</div>
