@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead"><div class="shell">
    <span class="kicker">{{ $menu_parent ? $menu_parent->label() : (is_rtl() ? 'صفحه' : 'Page') }}</span>
    <h1>{{ tf($page,'title') }}</h1>
</div></div>

<section class="shell">
    @if($sibling_pages->count() > 1)
    <div class="doc">
        <div class="doc-main">
            @if($page->image)
            <div class="photo plain" style="aspect-ratio:16/8;margin-bottom:var(--s6)"><img src="{{ asset('uploads/'.$page->image) }}" alt="{{ tf($page,'title') }}"></div>
            @endif
            <div class="rich-content">
                {!! tf($page,'content') !!}
            </div>
        </div>
        <aside>
            <div class="aside-box">
                <h3>{{ $menu_parent->label() }}</h3>
                <div class="in">
                    <ul class="linklist">
                        @foreach($sibling_pages as $sibling)
                        <li>
                            @if($sibling->link_type === 'custom_page' && $sibling->customPage && $sibling->customPage->id === $page->id)
                            <a href="{{ $sibling->resolvedUrl() }}" style="color:var(--brand)">{{ $sibling->label() }}</a>
                            @else
                            <a href="{{ $sibling->resolvedUrl() }}">{{ $sibling->label() }}</a>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </aside>
    </div>
    @else
    @if($page->image)
    <div class="photo plain" style="aspect-ratio:21/8;margin-bottom:var(--s6)"><img src="{{ asset('uploads/'.$page->image) }}" alt="{{ tf($page,'title') }}"></div>
    @endif
    <div class="rich-content">
        {!! tf($page,'content') !!}
    </div>
    @endif
</section>
@endsection
