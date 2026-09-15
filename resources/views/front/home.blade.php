@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
@php
    $slide = $sliders->first();
@endphp

<div class="hero">
    @if($slide && $slide->image)
    <div class="hero-photo"><img src="{{ asset('uploads/'.$slide->image) }}" alt=""></div>
    @endif
    <img class="hero-emblem" src="{{ asset('uploads/'.$global_setting->logo_1) }}" alt="">
    <div class="shell"><div class="inner">
        <span class="org">{{ is_rtl() ? 'شبکه جهانی عدالت برای زنان · تأسیس ۲۰۲۱' : "GLOBAL WOMEN'S JUSTICE NETWORK · EST. 2021" }}</span>
        <h1>{{ $slide ? tf($slide, 'heading') : (is_rtl() ? 'دفاع از عدالت، برابری، آزادی و حقوق زنان.' : "Advocating for justice, equality, freedom and the rights of women.") }}</h1>
        <p class="lede">{{ $slide ? tf($slide, 'text') : (is_rtl() ? 'شبکه‌ای بین‌المللی از مدافعان حقوق بشر زنان که در چهارده کشور کمپاین، اقدام عمومی و پیگیری حقوقی سازمان‌دهی می‌کند.' : 'An international network of women human rights defenders, organising campaigns, public actions and legal advocacy across fourteen countries.') }}</p>
        <div class="hero-actions">
            @if($slide && $slide->button_text && $slide->button_link)
            <a class="btn btn-signal btn-lg" href="{{ $slide->button_link }}">{{ tf($slide, 'button_text') }}</a>
            @else
            <a class="btn btn-signal btn-lg" href="{{ route('donate') }}">{{ is_rtl() ? 'عضویت در شبکه' : 'Join us' }}</a>
            @endif
            @if($global_menu->menu_event_status == 'Show')
            <a class="btn btn-ghost btn-lg" href="{{ route('events') }}">{{ is_rtl() ? 'رویدادهای پیش رو' : 'Upcoming events' }}</a>
            @endif
            @if($global_menu->menu_about_status == 'Show')
            <a class="btn btn-ghost btn-lg" href="{{ route('about') }}">{{ is_rtl() ? 'درباره ما' : 'About us' }}</a>
            @endif
        </div>
    </div></div>
</div>

<div class="statbar"><div class="shell"><div class="statbar-grid">
    <div class="stat">
        <div class="ic"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.6 3 2.6 15 0 18M12 3c-2.6 3-2.6 15 0 18"/></svg></div>
        <b data-count="14">14</b>
        <span class="lb">{{ is_rtl() ? 'کشور' : 'Countries' }}</span>
        <span class="cp">{{ is_rtl() ? 'در چهار قاره' : 'Across four continents' }}</span>
    </div>
    <div class="stat">
        <div class="ic"><svg viewBox="0 0 24 24"><path d="M12 21s-7-4.4-7-10a7 7 0 0114 0c0 5.6-7 10-7 10z"/><circle cx="12" cy="11" r="2.5"/></svg></div>
        <b data-count="26">26</b>
        <span class="lb">{{ is_rtl() ? 'شهر' : 'Cities' }}</span>
        <span class="cp">{{ is_rtl() ? 'با اعضای فعال' : 'With active members' }}</span>
    </div>
    <div class="stat">
        <div class="ic"><svg viewBox="0 0 24 24"><path d="M3 11v3l11 5V6L3 11z"/><path d="M14 8.5a3.5 3.5 0 010 8"/><path d="M6 15v4h3"/></svg></div>
        <b data-count="38">38</b>
        <span class="lb">{{ is_rtl() ? 'اقدام عمومی' : 'Public actions' }}</span>
        <span class="cp">{{ is_rtl() ? 'از سال ۲۰۲۱' : 'Held since 2021' }}</span>
    </div>
    <div class="stat">
        <div class="ic"><svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3"/><circle cx="17" cy="10" r="2.3"/><path d="M3 20c0-3.3 2.7-5 6-5s6 1.7 6 5M15.5 20c0-2.3 1.4-3.6 3.5-3.6s2.5 1 2.5 3.6"/></svg></div>
        <b data-count="61">61</b>
        <span class="lb">{{ is_rtl() ? 'سازمان شریک' : 'Partner organisations' }}</span>
        <span class="cp">{{ is_rtl() ? 'امضاکنندهٔ اعلامیهٔ ما' : 'Signed our declaration' }}</span>
    </div>
</div></div></div>

@if($page_data->about_status == 'Show' && $about_item)
<section class="shell"><div class="feature">
    <div class="photo"><img src="{{ asset('uploads/'.$about_item->image) }}" alt=""></div>
    <div>
        @if(tf($about_item,'subheading'))<span class="kicker">{{ tf($about_item,'subheading') }}</span>@endif
        <h2>{{ tf($about_item,'heading') }}</h2>
        <p>{{ tf($about_item,'text') }}</p>
        <div class="row">
            @if($about_item->button_text && $about_item->button_link)
            <a class="btn btn-signal" href="{{ $about_item->button_link }}">{{ tf($about_item,'button_text') }}</a>
            @endif
            @if($global_menu->menu_about_status == 'Show')
            <a class="more" href="{{ route('about') }}">{{ is_rtl() ? 'مطالعهٔ کامل' : 'Read more about us' }}</a>
            @endif
        </div>
    </div>
</div></section>
@endif

@php
    $campaigns_item = \App\Models\MenuItem::where('label', 'Campaigns')->where('link_type', 'custom_page')->first();
@endphp
@if($campaigns_item && $campaigns_item->customPage)
<section class="shell">
    <div class="st-feature">
        <div>
            <span class="ref">{{ is_rtl() ? 'کمپاین فعلی' : 'Current campaign' }}</span>
            <h3>{{ tf($campaigns_item->customPage, 'title') }}</h3>
            <p>{{ \Illuminate\Support\Str::limit(strip_tags(tf($campaigns_item->customPage, 'content')), 220) }}</p>
            <a class="btn btn-ghost" href="{{ $campaigns_item->resolvedUrl() }}">{{ is_rtl() ? 'مطالعهٔ کامل کمپاین' : 'Read the full campaign' }}</a>
        </div>
        <div class="sig-count">
            <b data-count="14">14</b>
            <span>{{ is_rtl() ? 'کشور در حال فعالیت' : 'countries active' }}</span>
        </div>
    </div>
</section>
@endif

@if($events->count())
@php
    $next = $events->first();
@endphp
<div class="band-action"><div class="shell">
    <div class="dateblock">
        <span class="d">{{ \Carbon\Carbon::parse($next->event_date)->format('d') }}</span>
        <span class="m">{{ strtoupper(\Carbon\Carbon::parse($next->event_date)->format('M')) }}</span>
    </div>
    <div>
        <span class="chip urgent">{{ is_rtl() ? 'نزدیک‌ترین اقدام عمومی' : 'Next public action' }}</span>
        <h3>{{ tf($next,'title') }}</h3>
        <div class="loc">{{ tf($next,'event_location') }}@if($next->event_time) &middot; {{ $next->event_time }}@endif</div>
    </div>
    <a class="btn btn-signal btn-lg" href="{{ route('event', $next->slug) }}">{{ $next->button_text ? tf($next,'button_text') : (is_rtl() ? 'پیوستن' : 'Join') }}</a>
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
@endphp
@if($work_areas->count())
<section class="shell">
    <div class="sec-head">
        <div class="txt">
            <span class="kicker">{{ is_rtl() ? 'کار ما' : 'What we do' }}</span>
            <h2>{{ is_rtl() ? 'حوزه‌های کاری ما' : 'Areas of work' }}</h2>
            <p>{{ is_rtl() ? 'هر حوزه، مستندسازی، بسیج عمومی و پیگیری در نهادهای بین‌المللی را در کنار هم دارد.' : 'Each area combines documentation, public mobilisation and advocacy before international bodies.' }}</p>
        </div>
        @if($our_work_group)<a class="more" href="{{ route('about') }}">{{ is_rtl() ? 'همهٔ فعالیت‌ها' : 'All our work' }}</a>@endif
    </div>
    <div class="areas">
        @foreach($work_areas as $i => $area)
        <a class="area {{ $loop->last ? 'last' : '' }}" href="{{ $area->resolvedUrl() }}">
            <div class="ic"><svg viewBox="0 0 24 24">{!! $work_icons[$i % count($work_icons)] !!}</svg></div>
            <h3>{{ $area->label() }}</h3>
            <p>
                @if($area->link_type === 'custom_page' && $area->customPage)
                    {{ \Illuminate\Support\Str::limit(strip_tags(tf($area->customPage, 'content')), 80) }}
                @else
                    {{ is_rtl() ? 'مشاهدهٔ جزئیات' : 'Learn more' }}
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
            <span class="kicker">{{ is_rtl() ? 'تیم ما' : 'Our people' }}</span>
            <h2>{{ tf($page_data,'team_heading') ?: (is_rtl() ? 'هماهنگ‌کنندگان شبکه' : 'Network coordinators') }}</h2>
        </div>
        @if($global_menu->menu_team_member_status == 'Show')
        <a class="more" href="{{ route('members') }}">{{ is_rtl() ? 'همهٔ اعضا' : 'All members' }}</a>
        @endif
    </div>
    <div class="cards">
        @foreach($members->take(3) as $member)
        <a class="card" href="{{ route('member', $member->slug) }}">
            <div class="photo"><img src="{{ asset('uploads/'.$member->image) }}" alt="{{ $member->name }}"></div>
            <span class="dt">{{ tf($member,'designation') }}</span>
            <h3>{{ $member->name }}</h3>
        </a>
        @endforeach
    </div>
</div></section>
@endif

@if($events->count())
<section class="{{ ($page_data->team_status == 'Show' && $members->count()) ? '' : 'cream' }}"><div class="shell">
    <div class="sec-head">
        <div class="txt">
            <span class="kicker">{{ is_rtl() ? 'رویدادها' : 'Events' }}</span>
            <h2>{{ tf($page_data,'event_heading') ?: (is_rtl() ? 'اعتراضات، کنفرانس‌ها و نشست‌ها' : 'Protests, conferences and meetings') }}</h2>
        </div>
        <a class="more" href="{{ route('events') }}">{{ is_rtl() ? 'تقویم کامل رویدادها' : 'Full events calendar' }}</a>
    </div>
    <ul class="events">
        @foreach($events->take(4) as $event)
        <li class="event">
            <div class="leaf">
                <span class="d">{{ \Carbon\Carbon::parse($event->event_date)->format('d') }}</span>
                <span class="m">{{ strtoupper(\Carbon\Carbon::parse($event->event_date)->format('M')) }}</span>
            </div>
            <div>
                <h3><a href="{{ route('event', $event->slug) }}" style="color:inherit;text-decoration:none">{{ tf($event,'title') }}</a></h3>
                <div class="loc">{{ tf($event,'event_location') }}@if($event->event_time) &middot; {{ $event->event_time }}@endif</div>
            </div>
            <a class="btn btn-out" href="{{ route('event', $event->slug) }}">{{ is_rtl() ? 'جزئیات' : 'Details' }}</a>
        </li>
        @endforeach
    </ul>
</div></section>
@endif

@if($photos->count())
<section class="shell">
    <div class="sec-head">
        <div class="txt">
            <span class="kicker">{{ is_rtl() ? 'گالری' : 'Gallery' }}</span>
            <h2>{{ is_rtl() ? 'فعالیت‌های اخیر' : 'Recent activities' }}</h2>
        </div>
        <a class="more" href="{{ route('gallery') }}">{{ is_rtl() ? 'عکس‌ها و ویدیوها' : 'Photos & videos' }}</a>
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

@if($publications->count())
<section class="cream"><div class="shell">
    <div class="sec-head">
        <div class="txt">
            <span class="kicker">{{ is_rtl() ? 'انتشارات' : 'Publications' }}</span>
            <h2>{{ is_rtl() ? 'گزارش‌های اخیر' : 'Recently published reports' }}</h2>
        </div>
        <a class="more" href="{{ route('publications') }}">{{ is_rtl() ? 'همهٔ گزارش‌ها' : 'All publications' }}</a>
    </div>
    <ul class="records">
        @foreach($publications as $pub)
        <li class="record">
            <div class="meta">
                <b>{{ $pub->published_date ? \Carbon\Carbon::parse($pub->published_date)->format('Y') : '' }}</b>
                <span>{{ $pub->category ? tf($pub->category,'name') : '' }}</span>
            </div>
            <div>
                <h3>{{ tf($pub, 'title') }}</h3>
                @if(tf($pub, 'description'))
                <p>{{ \Illuminate\Support\Str::limit(strip_tags(tf($pub, 'description')), 120) }}</p>
                @endif
            </div>
            @if($pub->file)
            <a class="go" href="{{ asset('uploads/'.$pub->file) }}" target="_blank" rel="noopener">{{ is_rtl() ? 'دانلود PDF' : 'Download PDF' }}</a>
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
            <span class="kicker">{{ is_rtl() ? 'اخبار و به‌روزرسانی‌ها' : 'News & updates' }}</span>
            <h2>{{ tf($page_data,'blog_heading') ?: (is_rtl() ? 'تازه‌ترین‌های شبکه' : 'Latest from the network') }}</h2>
        </div>
        <a class="more" href="{{ route('blog') }}">{{ is_rtl() ? 'همهٔ اخبار' : 'All news' }}</a>
    </div>
    <div class="cards">
        @foreach($posts as $post)
        <a class="card" href="{{ route('post', $post->slug) }}">
            <div class="photo"><img src="{{ asset('uploads/'.$post->image) }}" alt="{{ $post->title }}"></div>
            <span class="dt">{{ optional($post->published_at ?? $post->created_at)->format('j F Y') }}</span>
            <h3>{{ tf($post,'title') }}</h3>
            @if(tf($post,'excerpt'))<p>{{ \Illuminate\Support\Str::limit(tf($post,'excerpt'), 110) }}</p>@endif
        </a>
        @endforeach
    </div>
</section>
@endif

<section class="shell">
    <div class="sec-head"><div class="txt">
        <span class="kicker">{{ is_rtl() ? 'مشارکت' : 'Get involved' }}</span>
        <h2>{{ is_rtl() ? 'سه راه برای مشارکت' : 'Three ways to take part' }}</h2>
    </div></div>
    <div class="paths">
        <div class="path">
            <h3>{{ is_rtl() ? 'عضویت در شبکه' : 'Become a member' }}</h3>
            <p>{{ is_rtl() ? 'به شعبهٔ کشوری بپیوندید و در تعیین کمپاین‌های بعدی سهیم شوید.' : 'Join a country chapter and help shape which campaigns we run next.' }}</p>
            <a class="btn" href="{{ route('register') }}">{{ is_rtl() ? 'درخواست عضویت' : 'Apply for membership' }}</a></div>
        <div class="path">
            <h3>{{ is_rtl() ? 'داوطلبی یا هماهنگی' : 'Volunteer or coordinate' }}</h3>
            <p>{{ is_rtl() ? 'ترجمه کنید، مستند کنید یا شبکه را در شهر خود هماهنگ کنید.' : 'Translate, document, organise a local action, or coordinate the network in your city.' }}</p>
            <a class="btn" href="{{ route('contact') }}">{{ is_rtl() ? 'داوطلب شوید' : 'Volunteer with us' }}</a></div>
        <div class="path">
            <h3>{{ is_rtl() ? 'حمایت از کار ما' : 'Support our work' }}</h3>
            <p>{{ is_rtl() ? 'کمک‌ها صرف کمپاین‌ها، سفر نمایندگان و مستندسازی مستقل می‌شود.' : 'Funds go to campaigns, delegate travel and independent documentation.' }}</p>
            <a class="btn" href="{{ route('donate') }}">{{ is_rtl() ? 'حمایت مالی' : 'Support our work' }}</a></div>
    </div>
</section>

@php
    $partnerships_group = \App\Models\MenuItem::where('label', 'Partnerships')->whereNull('parent_id')->first();
    $request_form_item = $partnerships_group ? $partnerships_group->children->firstWhere('label', 'Partnership Request Form') : null;
    $partner_links = $partnerships_group
        ? $partnerships_group->visibleChildren()->get()->reject(fn($p) => $p->id === optional($request_form_item)->id)
        : collect();
@endphp
@if($partner_links->count())
<section class="shell">
    <div class="sec-head">
        <div class="txt">
            <span class="kicker">{{ is_rtl() ? 'همکاری‌ها' : 'Partnerships' }}</span>
            <h2>{{ is_rtl() ? 'همکاری با سازمان‌ها و نهادها' : 'We work with organisations and institutions' }}</h2>
            <p>{{ is_rtl() ? 'با انواع مختلف نهادها همکاری می‌کنیم — از سازمان‌های حقوق بشری تا نهادهای بین‌المللی.' : 'We partner with different kinds of organisations, from human rights groups to international bodies.' }}</p>
        </div>
        <a class="more" href="{{ $request_form_item ? $request_form_item->resolvedUrl() : route('contact') }}">{{ is_rtl() ? 'درخواست همکاری' : 'Propose a partnership' }}</a>
    </div>
    <div class="partners">
        @foreach($partner_links as $p)
        <a class="plogo" href="{{ $p->resolvedUrl() }}">{{ $p->label() }}</a>
        @endforeach
    </div>
</section>
@endif
@endsection
