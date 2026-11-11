@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
@php
    $about_video = ($about_item && $about_item->youtube_id) ? video_embed($about_item->youtube_id) : null;

    // Every slide from the admin panel; without any, one slide from the home-page texts.
    $hero_slides = $sliders->count() ? $sliders->values() : collect([null]);

    // The last few words of each headline are set in the light accent colour.
    $hero_split = function ($title) {
        $words = preg_split('/\s+/u', trim((string) $title));
        $tail = count($words) > 4 ? implode(' ', array_splice($words, -3)) : '';
        return [implode(' ', $words), $tail];
    };

    $arrow = '<svg class="arw" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';
@endphp

{{-- ===== Hero ===== --}}
<div class="hero" @if($hero_slides->count() > 1) data-hero-slider aria-roledescription="carousel" @endif>
    @foreach($hero_slides as $k => $slide)
    @php [$hero_head, $hero_tail] = $hero_split($slide ? tf($slide, 'heading') : hc('hero_heading')); @endphp
    <div class="hero-slide{{ $k === 0 ? ' is-active' : '' }}" @if($hero_slides->count() > 1) aria-roledescription="slide" aria-label="{{ local_digits($k + 1) }} / {{ local_digits($hero_slides->count()) }}" @endif @if($k > 0) aria-hidden="true" @endif>
        @if($slide && $slide->image)
        <div class="hero-photo"><img src="{{ asset('uploads/'.$slide->image) }}" alt="" @if($k > 0) loading="lazy" @endif></div>
        @endif
        <div class="shell">
            <div class="inner">
                @if($k === 0)
                <h1 class="hero-h">{{ $hero_head }} @if($hero_tail)<em>{{ $hero_tail }}</em>@endif</h1>
                @else
                <h2 class="hero-h">{{ $hero_head }} @if($hero_tail)<em>{{ $hero_tail }}</em>@endif</h2>
                @endif
                <p class="lede">{{ $slide ? tf($slide, 'text') : hc('hero_text') }}</p>
                <div class="hero-actions">
                    @if($slide && $slide->button_text && $slide->button_link)
                    <a class="btn btn-brand btn-lg" href="{{ $slide->button_link }}">{{ tf($slide, 'button_text') }} {!! $arrow !!}</a>
                    @else
                    <a class="btn btn-brand btn-lg" href="{{ route('donate') }}">{{ hc('hero_btn1') }} {!! $arrow !!}</a>
                    @endif
                    @if($about_video)
                    <button type="button" class="btn btn-ghost btn-lg hero-play" data-video-embed="{{ $about_video['embed'] }}" data-video-portrait="{{ $about_video['portrait'] ? '1' : '0' }}">
                        <span class="play-ic" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M9 7l8 5-8 5z"/></svg></span>
                        {{ __('Watch video') }}
                    </button>
                    @elseif($global_menu->menu_event_status == 'Show')
                    <a class="btn btn-ghost btn-lg" href="{{ route('events') }}">{{ hc('hero_btn2') }}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach

    @if($hero_slides->count() > 1)
    <div class="hero-nav">
        <button type="button" class="hero-arrow prev" aria-label="{{ __('Previous') }}">{!! $arrow !!}</button>
        <div class="hero-dots">
            @foreach($hero_slides as $k => $slide)
            <button type="button" class="{{ $k === 0 ? 'is-active' : '' }}" aria-label="{{ local_digits($k + 1) }} / {{ local_digits($hero_slides->count()) }}"></button>
            @endforeach
        </div>
        <button type="button" class="hero-arrow next" aria-label="{{ __('Next') }}">{!! $arrow !!}</button>
    </div>
    @endif
</div>

{{-- ===== Stats ===== --}}
@if(hc_on('show_stats'))
<div class="statbar"><div class="shell"><div class="statbar-grid">
    <div class="stat">
        <div class="ic"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.6 3 2.6 15 0 18M12 3c-2.6 3-2.6 15 0 18"/></svg></div>
        <div class="stat-txt">
        <b>{{ local_digits(hc('stats_1_num')) }}</b>
        <span class="lb">{{ hc('stats_1_label') }}</span>
        <span class="cp">{{ hc('stats_1_caption') }}</span>
        </div>
    </div>
    <div class="stat">
        <div class="ic"><svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3"/><circle cx="17" cy="10" r="2.3"/><path d="M3 20c0-3.3 2.7-5 6-5s6 1.7 6 5M15.5 20c0-2.3 1.4-3.6 3.5-3.6s2.5 1 2.5 3.6"/></svg></div>
        <div class="stat-txt">
        <b>{{ local_digits(hc('stats_2_num')) }}</b>
        <span class="lb">{{ hc('stats_2_label') }}</span>
        <span class="cp">{{ hc('stats_2_caption') }}</span>
        </div>
    </div>
    <div class="stat">
        <div class="ic"><svg viewBox="0 0 24 24"><rect x="3.5" y="5" width="17" height="15" rx="2"/><path d="M3.5 10h17M8 3v4M16 3v4"/></svg></div>
        <div class="stat-txt">
        <b>{{ local_digits(hc('stats_3_num')) }}</b>
        <span class="lb">{{ hc('stats_3_label') }}</span>
        <span class="cp">{{ hc('stats_3_caption') }}</span>
        </div>
    </div>
    <div class="stat">
        <div class="ic"><svg viewBox="0 0 24 24"><path d="M7 12l3-3 3 2 4-4"/><path d="M3 13l4 4 3-2 3 3 3-3 5-1"/><path d="M14 7h3v3"/></svg></div>
        <div class="stat-txt">
        <b>{{ local_digits(hc('stats_4_num')) }}</b>
        <span class="lb">{{ hc('stats_4_label') }}</span>
        <span class="cp">{{ hc('stats_4_caption') }}</span>
        </div>
    </div>
</div></div></div>
@endif

{{-- ===== About ===== --}}
@if($page_data->about_status == 'Show' && $about_item)
<section class="shell"><div class="feature">
    <div class="feature-media{{ hc('about_badge_num') ? ' has-badge' : '' }}">
        <div class="photo">@include('front.partials.about-slides', ['alt' => ''])</div>
        @if(hc('about_badge_num'))
        <div class="feature-badge">
            <span class="fb-ic" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 21V4"/><path d="M5 4h11l-2 4 2 4H5"/></svg></span>
            <span class="fb-txt"><b>{{ local_digits(hc('about_badge_num')) }}</b><span>{{ hc('about_badge_label') }}</span></span>
        </div>
        @endif
        @if($about_video)
        <button type="button" class="feature-play" data-video-embed="{{ $about_video['embed'] }}" data-video-portrait="{{ $about_video['portrait'] ? '1' : '0' }}" aria-label="{{ __('Watch video') }}">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 7l8 5-8 5z"/></svg>
        </button>
        @endif
    </div>
    <div class="feature-body">
        @if(tf($about_item,'subheading'))<span class="kicker">{{ tf($about_item,'subheading') }}</span>@endif
        <h2>{{ tf($about_item,'heading') }}</h2>
        <p class="lead">{{ tf($about_item,'text') }}</p>
        @if($about_item->button_text && $about_item->button_link)
        <a class="more" href="{{ $about_item->button_link }}">{{ tf($about_item,'button_text') }}</a>
        @elseif($global_menu->menu_about_status == 'Show')
        <a class="more" href="{{ route('about') }}">{{ hc('about_more') }}</a>
        @endif
    </div>
</div></section>
@endif

{{-- ===== Current campaign ===== --}}
@php
    $featured_campaign = \App\Models\Campaign::where('show_on_home', true)->orderBy('sort_order')->first()
        ?? \App\Models\Campaign::where('status', 'active')->orderBy('sort_order')->orderBy('id', 'desc')->first();
@endphp
@if($featured_campaign && hc_on('show_campaign'))
<section class="shell">
    <div class="st-feature{{ $featured_campaign->image ? ' has-bg' : '' }}">
        @if($featured_campaign->image)
        <img class="st-bg" src="{{ asset('uploads/'.$featured_campaign->image) }}" alt="">
        @endif
        <div>
            <span class="ref">{{ hc('campaign_kicker') }}</span>
            <h3>{{ tf($featured_campaign,'title') }}</h3>
            <p>{{ tf($featured_campaign,'excerpt') ?: plain_excerpt(tf($featured_campaign,'content'), 160) }}</p>
            <a class="btn btn-ghost" href="{{ route('campaign', slug_for($featured_campaign)) }}">{{ hc('campaign_button') }} {!! $arrow !!}</a>
        </div>
        @if($featured_campaign->stat_number)
        <div class="sig-count">
            <b>{{ local_digits($featured_campaign->stat_number) }}</b>
            <span>{{ tf($featured_campaign,'stat_label') }}</span>
        </div>
        @endif
    </div>
</section>
@endif

{{-- ===== Areas of work ===== --}}
@php
    $work_icons = [
        '<path d="M12 3v18M5 8h14M7 8l-3 6h6zM17 8l-3 6h6z"/>',
        '<path d="M3 11v3l11 5V6L3 11z"/><path d="M14 8.5a3.5 3.5 0 010 8"/>',
        '<rect x="3.5" y="5" width="17" height="15" rx="2"/><path d="M3.5 10h17M8 3v4M16 3v4"/>',
        '<path d="M6 3h9l4 4v14H6z"/><path d="M14 3v5h5M9 13h7M9 17h5"/>',
        '<path d="M4 19V9M10 19V5M16 19v-7M21 19H3"/>',
        '<rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="9" cy="10" r="2"/><path d="M21 16l-5-5-8 8"/>',
        '<path d="M4 5h12a3 3 0 013 3v11H7a3 3 0 01-3-3z"/><path d="M8 9h7M8 13h5"/>',
    ];
    $work_areas = collect();
    $our_work_group = \App\Models\MenuItem::where('label', 'Our Work')->whereNull('parent_id')->first();
    $news_media_group = \App\Models\MenuItem::where('label', 'News & Media')->whereNull('parent_id')->first();
    // children and linked page loaded with the list, not one query per card
    if ($our_work_group) $work_areas = $work_areas->merge($our_work_group->visibleChildren()->withCount('children')->with('customPage')->get());
    if ($news_media_group) $work_areas = $work_areas->merge($news_media_group->visibleChildren()->withCount('children')->with('customPage')->get());
    $work_areas = $work_areas->reject(fn($i) => $i->children_count > 0)->values();
    $work_fallback_desc = [
        'campaigns' => __('Public mobilisation, legal advocacy and coordinated pressure.'),
        'events' => __('Upcoming protests, conferences and meetings.'),
        'blog' => __('Recent statements and updates from the network.'),
        'gallery' => __('Photos and videos from our activities.'),
        'publications' => __('Annual reports, policy briefs and evidence documents.'),
    ];
@endphp
@if($work_areas->count() && hc_on('show_work'))
<section class="shell">
    <div class="sec-head areas-head">
        <div class="txt">
            @if(hc('work_kicker'))<span class="kicker">{{ hc('work_kicker') }}</span>@endif
            <h2>{{ hc('work_heading') }}</h2>
            <p>{{ hc('work_desc') }}</p>
        </div>
        @if($our_work_group)<a class="more" href="{{ route('about') }}">{{ hc('work_more') }}</a>@endif
    </div>
    @php
        // The first row carries photographs: each area's own image where it has one, then the gallery.
        $area_route_img = array_filter([
            'events' => optional($events->first())->image,
            'blog' => optional($posts->first())->image,
            'campaigns' => optional($featured_campaign)->image,
        ]);
        $area_pool = $photos->pluck('image')->merge($posts->pluck('image'))->merge($events->pluck('image'))->filter()->unique()->values();
    @endphp
    <div class="areas">
        @foreach($work_areas as $i => $area)
        @php
            $area_img = null;
            if ($i < 4) {
                // The photo chosen for the card in the admin panel; else one from the area's own content
                // (its page's gallery, or its latest event / post / campaign); else an unused pool image.
                $area_img = $area->card_image
                    ?: $area->customPage?->images()->first()?->image
                    ?: ($area_route_img[$area->route_name ?? ''] ?? null);
                if (!$area->card_image && (!$area_img || in_array($area_img, $area_used ?? [], true))) {
                    $area_img = $area_pool->first(fn($p) => !in_array($p, $area_used ?? [], true) && !in_array($p, $area_route_img, true))
                        ?? $area_pool->first(fn($p) => !in_array($p, $area_used ?? [], true));
                }
                if ($area_img) $area_used[] = $area_img;
            }
        @endphp
        <a class="area{{ $area_img ? ' has-img' : '' }}{{ $loop->last ? ' last' : '' }}" href="{{ $area->resolvedUrl() }}">
            @if($area_img)<span class="area-img"><img src="{{ asset('uploads/'.$area_img) }}" alt="" loading="lazy"></span>@endif
            <span class="area-body">
                <span class="ic"><svg viewBox="0 0 24 24" aria-hidden="true">{!! $work_icons[$i % count($work_icons)] !!}</svg></span>
                <h3>{{ $area->label() }}</h3>
                <p>
                    @if($area->link_type === 'custom_page' && $area->customPage && tf($area->customPage, 'content'))
                        {{ plain_excerpt(tf($area->customPage, 'content'), 60) }}
                    @elseif($area->link_type === 'route' && isset($work_fallback_desc[$area->route_name]))
                        {{ $work_fallback_desc[$area->route_name] }}
                    @else
                        {{ hc('work_card') }}
                    @endif
                </p>
                <span class="area-go">{!! $arrow !!}</span>
            </span>
        </a>
        @endforeach
    </div>
</section>
@endif

{{-- ===== Events ===== --}}
@php
    $show_news = $page_data->blog_status == 'Show' && $posts->count();
    $show_events = $events->count() > 0;
@endphp
@if($show_events)
<section class="shell events-band">
    <div class="ev-intro">
        @if($global_setting->banner)<img class="ev-deco" src="{{ asset('uploads/'.$global_setting->banner) }}" alt="" aria-hidden="true">@endif
        @if(hc('events_kicker'))<span class="kicker">{{ hc('events_kicker') }}</span>@endif
        <h2>{{ tf($page_data,'event_heading') ?: hc('events_heading') }}</h2>
        <a class="more" href="{{ route('events') }}">{{ hc('events_more') }}</a>
    </div>
    <ul class="ev-list">
        @foreach($events->take(3) as $event)
        @php $ev_past = strtotime($event->event_date) && strtotime($event->event_date) < strtotime('today'); @endphp
        <li>
            <a href="{{ route('event', slug_for($event)) }}">
                <span class="me-date"><b>{{ fdate($event->event_date, 'j') }}</b><small>{{ fdate($event->event_date, 'M') }}</small></span>
                @if($event->image)<span class="me-thumb"><img src="{{ asset('uploads/'.$event->image) }}" alt="" loading="lazy"></span>@endif
                <span class="me-body">
                    <span class="ev-tag{{ $ev_past ? ' past' : '' }}">{{ $ev_past ? __('Past') : __('Upcoming') }}</span>
                    <span class="me-title">{{ tf($event,'title') }}</span>
                    <span class="me-meta">
                        @if(tf($event,'event_location'))<span><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-7-4.4-7-10a7 7 0 0114 0c0 5.6-7 10-7 10z"/><circle cx="12" cy="11" r="2.5"/></svg>{{ tf($event,'event_location') }}</span>@endif
                        @if($event->event_time)<span><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg><bdi>{{ local_digits($event->event_time) }}</bdi></span>@endif
                    </span>
                </span>
                <span class="ev-more">{{ hc('events_details') }} {!! $arrow !!}</span>
            </a>
        </li>
        @endforeach
    </ul>
</section>
@endif

{{-- ===== Latest news: a featured story beside a short list ===== --}}
@if($show_news)
@php
    $news_lead = $posts->first();
    $news_rest = $posts->slice(1)->take(3);
    $cal = '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3.5" y="5" width="17" height="15" rx="2"/><path d="M3.5 10h17M8 3v4M16 3v4"/></svg>';
@endphp
<section class="shell news-feat">
    <div class="nf-head">
        <h2>{{ tf($page_data,'blog_heading') ?: hc('news_heading') }}</h2>
        <a class="more" href="{{ route('blog') }}">{{ hc('news_more') }}</a>
    </div>
    <div class="nf-grid{{ $news_rest->count() ? '' : ' single' }}">
        @if($news_rest->count())
        <ul class="nf-list">
            @foreach($news_rest as $post)
            <li>
                <a href="{{ route('post', slug_for($post)) }}">
                    <span class="nf-thumb"><img src="{{ asset('uploads/'.$post->image) }}" alt="" loading="lazy"></span>
                    <span class="nf-body">
                        @if($post->post_category)<span class="nf-cat">{{ tf($post->post_category,'name') }}</span>@endif
                        <span class="nf-title">{{ tf($post,'title') }}</span>
                        @if(tf($post,'excerpt'))<span class="nf-sum">{{ plain_excerpt(tf($post,'excerpt'), 90) }}</span>@endif
                        <span class="nf-date">{!! $cal !!}{{ fdate($post->published_at ?? $post->created_at, 'j F Y') }}</span>
                    </span>
                </a>
            </li>
            @endforeach
        </ul>
        @endif
        <a class="nf-lead" href="{{ route('post', slug_for($news_lead)) }}">
            <img src="{{ asset('uploads/'.$news_lead->image) }}" alt="" loading="lazy">
            <span class="nf-badge">{{ __('Featured') }}</span>
            <span class="nf-lead-body">
                <span class="nf-lead-title">{{ tf($news_lead,'title') }}</span>
                @if(tf($news_lead,'excerpt'))<span class="nf-lead-sum">{{ plain_excerpt(tf($news_lead,'excerpt'), 140) }}</span>@endif
                <span class="nf-go">{{ hc('news_readmore') }} {!! $arrow !!}</span>
            </span>
        </a>
    </div>
</section>
@endif

{{-- ===== Leadership ===== --}}
@if($page_data->team_status == 'Show' && $members->count())
<section class="shell">
    <div class="sec-head">
        <div class="txt">
            @if(hc('team_kicker'))<span class="kicker">{{ hc('team_kicker') }}</span>@endif
            <h2>{{ tf($page_data,'team_heading') ?: (hc('team_heading')) }}</h2>
        </div>
        @if($global_menu->menu_team_member_status == 'Show')
        <a class="more" href="{{ route('members') }}">{{ hc('team_more') }}</a>
        @endif
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
</section>
@endif

{{-- ===== Gallery ===== --}}
@if($photos->count() && hc_on('show_gallery'))
<section class="shell">
    <div class="sec-head">
        <div class="txt">
            @if(hc('gallery_kicker'))<span class="kicker">{{ hc('gallery_kicker') }}</span>@endif
            <h2>{{ hc('gallery_heading') }}</h2>
        </div>
        <a class="more" href="{{ route('gallery') }}">{{ hc('gallery_more') }}</a>
    </div>
    <div class="gallery">
        @foreach($photos as $photo)
        <a class="photo" href="{{ asset('uploads/'.$photo->image) }}" data-glightbox><img src="{{ asset('uploads/'.$photo->image) }}" alt="{{ tf($photo,'caption') }}">
            @if(tf($photo,'caption'))<span class="cap">{{ tf($photo,'caption') }}</span>@endif
        </a>
        @endforeach
    </div>
</section>
@endif

{{-- ===== Publications ===== --}}
@if($publications->count() && hc_on('show_pubs'))
<section class="shell">
    <div class="sec-head">
        <div class="txt">
            @if(hc('pubs_kicker'))<span class="kicker">{{ hc('pubs_kicker') }}</span>@endif
            <h2>{{ hc('pubs_heading') }}</h2>
        </div>
        <a class="more" href="{{ route('publications') }}">{{ hc('pubs_more') }}</a>
    </div>
    <ul class="records">
        @foreach($publications as $pub)
        <li class="record">
            <div class="meta">
                <b>{{ $pub->published_date ? fdate($pub->published_date, 'Y') : '' }}</b>
                <span>{{ $pub->category ? tf($pub->category,'name') : '' }}</span>
            </div>
            <div>
                <h3>{{ tf($pub, 'title') }}</h3>
                @if(tf($pub, 'description'))
                <p>{{ plain_excerpt(tf($pub, 'description'), 120) }}</p>
                @endif
            </div>
            @if($pub->file)
            <a class="go" href="{{ asset('uploads/'.$pub->file) }}" target="_blank" rel="noopener">{{ hc('pubs_download') }}</a>
            @endif
        </li>
        @endforeach
    </ul>
</section>
@endif

{{-- ===== Polls ===== --}}
@php
    $home_polls = \Illuminate\Support\Facades\Schema::hasTable('polls')
        ? \App\Models\Poll::published()->where('show_on_home', true)
            ->withCount('responses')->orderByRaw("CASE WHEN status = 'open' THEN 0 ELSE 1 END")->orderBy('sort_order')->orderBy('id', 'desc')->take(10)->get()
        : collect();
@endphp
@if($home_polls->count())
@php $tones = ['navy', 'orange', 'green', 'sand']; $rtl = is_rtl(); @endphp
<section class="shell" data-ptrack>
    <div class="sec-head">
        <div class="txt">
            <h2>{{ hc('polls_heading') }}</h2>
        </div>
        <a class="more" href="{{ route('polls') }}">{{ hc('polls_more') }}</a>
        <div class="ptrack-nav">
            <button type="button" class="ptrack-btn" data-ptrack-prev aria-label="{{ __('Previous') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $rtl ? 'M9 6l6 6-6 6' : 'M15 6l-6 6 6 6' }}"/></svg></button>
            <button type="button" class="ptrack-btn" data-ptrack-next aria-label="{{ __('Next') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $rtl ? 'M15 6l-6 6 6 6' : 'M9 6l6 6-6 6' }}"/></svg></button>
        </div>
    </div>
    <div class="ptrack">
        @foreach($home_polls as $poll)
        @include('front.partials.poll-tile', ['tone' => $loop->last ? 'brand' : $tones[$loop->index % 4]])
        @endforeach
    </div>
</section>
@endif

{{-- ===== Partners ===== --}}
@php
    $home_partners = \App\Models\Partner::where('status', 'Show')->orderBy('sort_order')->orderBy('id')->get();
@endphp
@if($home_partners->count() && hc_on('show_partners'))
<section class="shell">
    <div class="sec-head">
        <div class="txt">
            @if(hc('partners_kicker'))<span class="kicker">{{ hc('partners_kicker') }}</span>@endif
            <h2>{{ hc('partners_heading') }}</h2>
            <p>{{ hc('partners_desc') }}</p>
        </div>
        <a class="more" href="{{ route('partners') }}">{{ hc('partners_more') }}</a>
    </div>
    <div class="partners">
        @foreach($home_partners as $p)
        <a class="plogo" href="{{ $p->website ?: route('partners') }}" @if($p->website) target="_blank" rel="noopener" @endif title="{{ tf($p,'name') }}">
            <img src="{{ asset('uploads/'.$p->logo) }}" alt="{{ tf($p,'name') }}">
        </a>
        @endforeach
    </div>
</section>
@endif

{{-- ===== Three ways to take part ===== --}}
@if(hc_on('show_paths'))
@include('front.partials.paths-band')
@endif
@endsection
