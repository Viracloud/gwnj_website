@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
@php
    $slide = $sliders->first();
    // Hero side collage: other slider images first, then gallery, post and event photos (max 3, no repeats).
    $hero_collage = $sliders->skip(1)->pluck('image')
        ->merge($photos->pluck('image'))
        ->merge($posts->pluck('image'))
        ->merge($events->pluck('image'))
        ->filter()->reject(fn($img) => $slide && $img === $slide->image)
        ->unique()->take(3)->values();
    $hero_event = $events->first(fn($e) => $e->event_date && \Carbon\Carbon::parse($e->event_date)->endOfDay()->isFuture());
    $hero_side = $hero_collage->count() || $hero_event;
@endphp

<div class="hero{{ $hero_side ? ' has-side' : '' }}">
    @if($slide && $slide->image)
    <div class="hero-photo"><img src="{{ asset('uploads/'.$slide->image) }}" alt=""></div>
    @endif
    <div class="shell"><div class="hero-grid">
    <div class="inner">
        <span class="org">{{ preg_replace('/[\s·•|\-–—]+$/u', '', hc('hero_org')) }}</span>
        <h1>{{ $slide ? tf($slide, 'heading') : (hc('hero_heading')) }}</h1>
        <p class="lede">{{ $slide ? tf($slide, 'text') : (hc('hero_text')) }}</p>
        <div class="hero-actions">
            @if($slide && $slide->button_text && $slide->button_link)
            <a class="btn btn-signal btn-lg" href="{{ $slide->button_link }}">{{ tf($slide, 'button_text') }}</a>
            @else
            <a class="btn btn-signal btn-lg" href="{{ route('donate') }}">{{ hc('hero_btn1') }}</a>
            @endif
            @if($global_menu->menu_event_status == 'Show')
            <a class="btn btn-ghost btn-lg" href="{{ route('events') }}">{{ hc('hero_btn2') }}</a>
            @endif
            @if($global_menu->menu_about_status == 'Show')
            <a class="btn btn-ghost btn-lg" href="{{ route('about') }}">{{ hc('hero_btn3') }}</a>
            @endif
        </div>
    </div>
    @if($hero_side)
    <div class="hero-side{{ $hero_collage->count() ? '' : ' no-photos' }}">
        @if($hero_collage->count())
        <div class="hc-stack">
            @foreach($hero_collage as $i => $img)
            <figure class="hc-ph hc-{{ $i + 1 }}"><img src="{{ asset('uploads/'.$img) }}" alt="" loading="lazy"></figure>
            @endforeach
        </div>
        @if(hc_on('show_stats') && hc('stats_1_num'))
        <div class="hc-badge"><b>{{ hc('stats_1_num') }}</b><span>{{ hc('stats_1_label') }}</span></div>
        @endif
        @endif
        @if($hero_event)
        <a class="hc-event" href="{{ route('event', slug_for($hero_event)) }}">
            <span class="hc-date">
                <b>{{ fdate($hero_event->event_date, 'd') }}</b>
                <small>{{ fdate($hero_event->event_date, 'M') }}</small>
            </span>
            <span class="hc-info">
                <span class="hc-kicker">{{ hc('next_chip') }}</span>
                <strong>{{ tf($hero_event,'title') }}</strong>
                @if(tf($hero_event,'event_location'))<small>{{ tf($hero_event,'event_location') }}</small>@endif
            </span>
        </a>
        @endif
    </div>
    @endif
    </div></div>
</div>

@if(hc_on('show_stats'))
<div class="statbar"><div class="shell"><div class="statbar-grid">
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
@endif
@if($page_data->about_status == 'Show' && $about_item)
<section class="shell"><div class="feature">
    <div class="feature-media">
        <div class="photo">@include('front.partials.about-slides', ['alt' => ''])</div>
        <div class="feature-badge"><b>{{ hc('about_badge_num') }}</b><span>{{ hc('about_badge_label') }}</span></div>
    </div>
    <div class="feature-body">
        @if(tf($about_item,'subheading'))<span class="kicker">{{ tf($about_item,'subheading') }}</span>@endif
        <h2>{{ tf($about_item,'heading') }}</h2>
        <p class="lead">{{ tf($about_item,'text') }}</p>
        @php
            $about_btn_link = $about_item->button_link;
            $about_btn_dup = $about_btn_link && \Illuminate\Support\Str::endsWith(rtrim($about_btn_link, '/'), '/about');
        @endphp
        <div class="feature-actions">
            @if($about_item->button_text && $about_btn_link)
            <a class="btn btn-brand" href="{{ $about_btn_link }}">{{ tf($about_item,'button_text') }}</a>
            @elseif($global_menu->menu_about_status == 'Show')
            <a class="btn btn-brand" href="{{ route('about') }}">{{ hc('about_more') }}</a>
            @endif
            @if($global_menu->menu_about_status == 'Show' && $about_item->button_text && $about_btn_link && !$about_btn_dup)
            <a class="more" href="{{ route('about') }}">{{ hc('about_more') }}</a>
            @endif
        </div>
    </div>
</div></section>
@endif

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
            <p>{{ tf($featured_campaign,'excerpt') ?: plain_excerpt(tf($featured_campaign,'content'), 220) }}</p>
            <a class="btn btn-ghost" href="{{ route('campaign', slug_for($featured_campaign)) }}">{{ hc('campaign_button') }}</a>
        </div>
        @if($featured_campaign->stat_number)
        <div class="sig-count">
            <b>{{ $featured_campaign->stat_number }}</b>
            <span>{{ tf($featured_campaign,'stat_label') }}</span>
        </div>
        @endif
    </div>
</section>
@endif

@if($events->count() && hc_on('show_next'))
@php
    $next = $events->first();
@endphp
<div class="band-action"><div class="shell">
    <div class="dateblock">
        <span class="d">{{ fdate($next->event_date, 'j') }}</span>
        <span class="m">{{ strtoupper(fdate($next->event_date, 'M')) }}</span>
    </div>
    <div>
        <span class="chip urgent">{{ hc('next_chip') }}</span>
        <h3>{{ tf($next,'title') }}</h3>
        <div class="loc">{{ tf($next,'event_location') }}@if($next->event_time) &middot; {{ $next->event_time }}@endif</div>
    </div>
    <a class="btn btn-signal btn-lg" href="{{ route('event', slug_for($next)) }}">{{ $next->button_text ? tf($next,'button_text') : (hc('next_btn')) }}</a>
</div></div>
@endif

@php
    $work_icons = [
        '<path d="M12 21s-7-4.3-7-10a7 7 0 0114 0c0 5.7-7 10-7 10z"/><circle cx="12" cy="11" r="2.6"/>',
        '<path d="M12 3v18M5 8h14M7 8l-3 6h6zM17 8l-3 6h6z"/>',
        '<circle cx="9" cy="8" r="3"/><circle cx="17" cy="10" r="2.4"/><path d="M3 20c0-3.3 2.7-5 6-5s6 1.7 6 5M15.5 20c0-2.3 1.4-3.6 3.5-3.6s2.5 1 2.5 3.6"/>',
        '<path d="M4 20V5l8-2 8 2v15"/><path d="M4 20h16M9 20v-6h6v6"/>',
        '<path d="M3 6h18M3 12h12M3 18h8"/><circle cx="19" cy="16" r="3"/>',
        '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.6 3 2.6 15 0 18M12 3c-2.6 3-2.6 15 0 18"/>',
    ];
    $work_areas = collect();
    $our_work_group = \App\Models\MenuItem::where('label', 'Our Work')->whereNull('parent_id')->first();
    $news_media_group = \App\Models\MenuItem::where('label', 'News & Media')->whereNull('parent_id')->first();
    if ($our_work_group) $work_areas = $work_areas->merge($our_work_group->visibleChildren()->get());
    if ($news_media_group) $work_areas = $work_areas->merge($news_media_group->visibleChildren()->get());
    $work_areas = $work_areas->reject(fn($i) => $i->children()->count() > 0)->values();
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
    <div class="sec-head">
        <div class="txt">
            <span class="kicker">{{ hc('work_kicker') }}</span>
            <h2>{{ hc('work_heading') }}</h2>
            <p>{{ hc('work_desc') }}</p>
        </div>
        @if($our_work_group)<a class="more" href="{{ route('about') }}">{{ hc('work_more') }}</a>@endif
    </div>
    @php $area_cols = (int) ceil($work_areas->count() / max(1, ceil($work_areas->count() / 4))); @endphp
    <div class="areas" style="--area-cols:{{ $area_cols }}">
        @foreach($work_areas as $i => $area)
        <a class="area {{ $loop->last ? 'last' : '' }}" href="{{ $area->resolvedUrl() }}">
            <div class="ic"><svg viewBox="0 0 24 24">{!! $work_icons[$i % count($work_icons)] !!}</svg></div>
            <h3>{{ $area->label() }}</h3>
            <p>
                @if($area->link_type === 'custom_page' && $area->customPage && tf($area->customPage, 'content'))
                    {{ plain_excerpt(tf($area->customPage, 'content'), 90) }}
                @elseif($area->link_type === 'route' && isset($work_fallback_desc[$area->route_name]))
                    {{ $work_fallback_desc[$area->route_name] }}
                @else
                    {{ hc('work_card') }}
                @endif
            </p>
        </a>
        @endforeach
    </div>
</section>
@endif

@if($page_data->team_status == 'Show' && $members->count())
<section class="cream"><div class="shell">
    <div class="sec-head">
        <div class="txt">
            <span class="kicker">{{ hc('team_kicker') }}</span>
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
</div></section>
@endif

@if($events->count())
<section class="{{ ($page_data->team_status == 'Show' && $members->count()) ? '' : 'cream' }}"><div class="shell">
    <div class="sec-head">
        <div class="txt">
            <span class="kicker">{{ hc('events_kicker') }}</span>
            <h2>{{ tf($page_data,'event_heading') ?: (hc('events_heading')) }}</h2>
        </div>
        <a class="more" href="{{ route('events') }}">{{ hc('events_more') }}</a>
    </div>
    <ul class="events">
        @foreach($events->take(4) as $event)
        @include('front.partials.event-row')
        @endforeach
    </ul>
</div></section>
@endif

@if($photos->count() && hc_on('show_gallery'))
<section class="shell">
    <div class="sec-head">
        <div class="txt">
            <span class="kicker">{{ hc('gallery_kicker') }}</span>
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

@if($publications->count() && hc_on('show_pubs'))
<section class="cream"><div class="shell">
    <div class="sec-head">
        <div class="txt">
            <span class="kicker">{{ hc('pubs_kicker') }}</span>
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
</div></section>
@endif

@if($page_data->blog_status == 'Show' && $posts->count())
<section class="shell">
    <div class="sec-head">
        <div class="txt">
            <span class="kicker">{{ hc('news_kicker') }}</span>
            <h2>{{ tf($page_data,'blog_heading') ?: (hc('news_heading')) }}</h2>
        </div>
        <a class="more" href="{{ route('blog') }}">{{ hc('news_more') }}</a>
    </div>
    <div class="cards">
        @foreach($posts as $post)
        <a class="card" href="{{ route('post', slug_for($post)) }}">
            <div class="photo"><img src="{{ asset('uploads/'.$post->image) }}" alt="{{ $post->title }}"></div>
            @include('front.partials.post-meta')
            <h3>{{ tf($post,'title') }}</h3>
            @if(tf($post,'excerpt'))<p>{{ plain_excerpt(tf($post,'excerpt'), 110) }}</p>@endif
            <span class="readmore">{{ hc('news_readmore') }}</span>
        </a>
        @endforeach
    </div>
</section>
@endif

@if(hc_on('show_paths'))
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
@endif

@php
    $home_polls = \Illuminate\Support\Facades\Schema::hasTable('polls')
        ? \App\Models\Poll::published()->where('show_on_home', true)
            ->orderByRaw("CASE WHEN status = 'open' THEN 0 ELSE 1 END")->orderBy('sort_order')->orderBy('id', 'desc')->take(3)->get()
        : collect();
@endphp
@if($home_polls->count())
<section class="shell">
    <div class="sec-head">
        <div class="txt">
            <span class="kicker">{{ __('Have your say') }}</span>
            <h2>{{ __('Polls & surveys') }}</h2>
        </div>
        <a class="more" href="{{ route('polls') }}">{{ __('All polls') }}</a>
    </div>
    <div class="poll-list">
        @foreach($home_polls as $poll)
        <a class="poll-card" href="{{ route('poll', $poll->slug) }}">
            <div class="poll-card-top">
                <span class="chip {{ $poll->isOpen() ? 'done' : 'plain' }}">{{ $poll->isOpen() ? (__('Open')) : (__('Closed')) }}</span>
            </div>
            <h3>{{ tf($poll, 'title') }}</h3>
            @if(tf($poll, 'description'))<p>{{ \Illuminate\Support\Str::limit(tf($poll, 'description'), 130) }}</p>@endif
            <span class="readmore">{{ $poll->isOpen() ? (__('Take part')) : (__('See results')) }}</span>
        </a>
        @endforeach
    </div>
</section>
@endif

@php
    $home_partners = \App\Models\Partner::where('status', 'Show')->orderBy('sort_order')->orderBy('id')->get();
    $partnerships_group = \App\Models\MenuItem::where('label', 'Partnerships')->whereNull('parent_id')->first();
    $request_form_item = $partnerships_group ? $partnerships_group->children->firstWhere('label', 'Partnership Request Form') : null;
@endphp
@if($home_partners->count() && hc_on('show_partners'))
<section class="shell">
    <div class="sec-head">
        <div class="txt">
            <span class="kicker">{{ hc('partners_kicker') }}</span>
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
@endsection
