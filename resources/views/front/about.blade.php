@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead"><div class="shell">
    @if(tf($about_item,'subheading'))<span class="kicker">{{ tf($about_item,'subheading') }}</span>@endif
    <h1>{{ __('About us') }}</h1>
</div></div>

@if($page_data->about_status == 'Show' && $about_item)
<section class="shell" style="padding-block:var(--s8)">
    <div class="feature">
        <div class="feature-media">
            <div class="photo">
                @include('front.partials.about-slides', ['alt' => tf($about_item,'heading')])
                @if($about_item->youtube_id != '' && ($about_video = video_embed($about_item->youtube_id)))
                {!! video_facade($about_video, 'vf-overlay') !!}
                @endif
            </div>
            @if($about_item->experience_year != '')
            <div class="feature-badge">
                <b>{{ $about_item->experience_year }}+</b>
                @if(tf($about_item,'experience_text'))<span>{{ tf($about_item,'experience_text') }}</span>@endif
            </div>
            @endif
        </div>
        <div class="feature-body">
            @if(tf($about_item,'subheading'))<span class="kicker">{{ tf($about_item,'subheading') }}</span>@endif
            <h2>{{ tf($about_item,'heading') }}</h2>
            <p class="lead">{{ tf($about_item,'text') }}</p>
            <div class="feature-actions">
                @if($about_item->button_link != '' && $about_item->button_text != '')
                <a class="btn btn-brand" href="{{ $about_item->button_link }}">{{ tf($about_item,'button_text') }}</a>
                @endif
                @if($about_item->phone_number != '')
                <a class="btn btn-out" href="tel:{{ $about_item->phone_number }}">
                    <i class="fa-solid fa-phone-volume"></i>
                    @if(tf($about_item,'phone_text'))
                        {{ tf($about_item,'phone_text') }}
                    @else
                        <bdi dir="ltr">{{ $about_item->phone_number }}</bdi>
                    @endif
                </a>
                @endif
            </div>
        </div>
    </div>
</section>
@endif

@php
    $about_icons = [
        '<path d="M12 21s-7-4.4-7-10a7 7 0 0114 0c0 5.6-7 10-7 10z"/><circle cx="12" cy="11" r="2.5"/>',
        '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.6 3 2.6 15 0 18M12 3c-2.6 3-2.6 15 0 18"/>',
        '<path d="M3 11l9-6 9 6-9 6-9-6z"/><path d="M6 13v5c0 1 2.7 2 6 2s6-1 6-2v-5"/>',
        '<path d="M12 21s-7-4.3-7-10a7 7 0 0114 0c0 5.7-7 10-7 10z"/><circle cx="12" cy="11" r="2.6"/>',
        '<path d="M3 6h18M3 12h12M3 18h8"/><circle cx="19" cy="16" r="3"/>',
        '<path d="M4 20V5l8-2 8 2v15"/><path d="M4 20h16M9 20v-6h6v6"/>',
        '<circle cx="9" cy="8" r="3"/><circle cx="17" cy="10" r="2.4"/><path d="M3 20c0-3.3 2.7-5 6-5s6 1.7 6 5M15.5 20c0-2.3 1.4-3.6 3.5-3.6s2.5 1 2.5 3.6"/>',
    ];
    $about_fallback_desc = [
        'members' => __('Meet our board, secretary-general, executive teams and country coordinators.'),
    ];
@endphp
@if($about_links->count())
<section class="shell">
    <div class="sec-head">
        <div class="txt">
            <span class="kicker">{{ __('Learn more') }}</span>
            <h2>{{ __('Get to know GWJN') }}</h2>
        </div>
    </div>
    <div class="areas wrap">
        @foreach($about_links as $i => $link)
        <a class="area" href="{{ $link->resolvedUrl() }}">
            <div class="ic"><svg viewBox="0 0 24 24">{!! $about_icons[$i % count($about_icons)] !!}</svg></div>
            <h3>{{ $link->label() }}</h3>
            <p>
                @if($link->link_type === 'custom_page' && $link->customPage && tf($link->customPage, 'content'))
                    {{ plain_excerpt(tf($link->customPage, 'content'), 90) }}
                @elseif($link->link_type === 'route' && isset($about_fallback_desc[$link->route_name]))
                    {{ $about_fallback_desc[$link->route_name] }}
                @endif
            </p>
        </a>
        @endforeach
    </div>
</section>
@endif

<div class="statbar statbar-inline"><div class="shell"><div class="statbar-grid">
    <div class="stat">
        <div class="ic"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.6 3 2.6 15 0 18M12 3c-2.6 3-2.6 15 0 18"/></svg></div>
        <b>{{ hc('stats_1_num') }}</b>        <span class="lb">{{ hc('stats_1_label') }}</span>
        <span class="cp">{{ hc('stats_1_caption') }}</span>
    </div>
    <div class="stat">
        <div class="ic"><svg viewBox="0 0 24 24"><path d="M12 21s-7-4.4-7-10a7 7 0 0114 0c0 5.6-7 10-7 10z"/><circle cx="12" cy="11" r="2.5"/></svg></div>
        <b>{{ hc('stats_2_num') }}</b>
        <span class="lb">{{ hc('stats_2_label') }}</span>
        <span class="cp">{{ hc('stats_2_caption') }}</span>
    </div>
    <div class="stat">
        <div class="ic"><svg viewBox="0 0 24 24"><path d="M3 11v3l11 5V6L3 11z"/><path d="M14 8.5a3.5 3.5 0 010 8"/><path d="M6 15v4h3"/></svg></div>
        <b>{{ hc('stats_3_num') }}</b>
        <span class="lb">{{ hc('stats_3_label') }}</span>
        <span class="cp">{{ hc('stats_3_caption') }}</span>
    </div>
    <div class="stat">
        <div class="ic"><svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3"/><circle cx="17" cy="10" r="2.3"/><path d="M3 20c0-3.3 2.7-5 6-5s6 1.7 6 5M15.5 20c0-2.3 1.4-3.6 3.5-3.6s2.5 1 2.5 3.6"/></svg></div>
        <b>{{ hc('stats_4_num') }}</b>
        <span class="lb">{{ hc('stats_4_label') }}</span>
        <span class="cp">{{ hc('stats_4_caption') }}</span>
    </div>
</div></div></div>

@if($members->count())
<section class="cream"><div class="shell">
    <div class="sec-head">
        <div class="txt">
            <span class="kicker">{{ hc('team_kicker') }}</span>
            <h2>{{ __('Our leadership') }}</h2>
        </div>
        <a class="more" href="{{ route('members') }}">{{ hc('team_more') }}</a>
    </div>
    <div class="cards people">
        @foreach($members->take(6) as $member)
        <a class="card" href="{{ route('member', slug_for($member)) }}">
            <div class="photo"><img src="{{ asset('uploads/'.$member->image) }}" alt="{{ tf($member,'name') }}"></div>
            <span class="dt">{{ tf($member,'designation') }}</span>
            <h3>{{ tf($member,'name') }}</h3>
        </a>
        @endforeach
    </div>
</div></section>
@endif

<section class="shell">
    <div class="sec-head"><div class="txt">
        <span class="kicker">{{ hc('paths_kicker') }}</span>
        <h2>{{ hc('paths_heading') }}</h2>
    </div></div>
    <div class="paths">
        <div class="path">
            <h3>{{ hc('paths_1_title') }}</h3>
            <p>{{ hc('paths_1_text') }}</p>
            <a class="btn" href="{{ hc_link('paths_1_link') }}">{{ hc('paths_1_btn') }}</a></div>
        <div class="path">
            <h3>{{ hc('paths_2_title') }}</h3>
            <p>{{ hc('paths_2_text') }}</p>
            <a class="btn" href="{{ hc_link('paths_2_link') }}">{{ hc('paths_2_btn') }}</a></div>
        <div class="path">
            <h3>{{ hc('paths_3_title') }}</h3>
            <p>{{ hc('paths_3_text') }}</p>
            <a class="btn" href="{{ hc_link('paths_3_link') }}">{{ hc('paths_3_btn') }}</a></div>
    </div>
</section>
@endsection
