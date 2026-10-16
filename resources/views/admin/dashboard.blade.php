@extends('admin.layouts.master')
@section('content')
@php
    $me = Auth::guard('admin')->user();
    $can_edit = $me->canEditContent();
    $hour = (int) now()->format('G');
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');

    // Only show the attention items this person is allowed to open.
    $attention = [
        ['count' => $pending['comments'], 'label' => 'comments waiting for approval', 'icon' => 'fa-comments', 'url' => route('admin_post_comments'), 'show' => true],
        ['count' => $pending['replies'], 'label' => 'comment replies waiting for approval', 'icon' => 'fa-reply', 'url' => route('admin_post_replies'), 'show' => true],
        ['count' => $pending['applications'], 'label' => 'new membership applications', 'icon' => 'fa-user-plus', 'url' => route('admin_membership_applications'), 'show' => $me->isSuperAdmin()],
        ['count' => $pending['cards'], 'label' => 'membership card requests', 'icon' => 'fa-id-card', 'url' => route('admin_membership_card_requests'), 'show' => $me->isSuperAdmin()],
    ];
    $attention = array_filter($attention, fn($a) => $a['show'] && $a['count'] > 0);

    $actions = [
        ['label' => 'Write a post', 'icon' => 'fa-pen-to-square', 'url' => route('admin_post_index').'#add', 'tone' => 'purple'],
        ['label' => 'Add an event', 'icon' => 'fa-calendar-plus', 'url' => route('admin_event_index').'#add', 'tone' => 'orange'],
        ['label' => 'Start a campaign', 'icon' => 'fa-bullhorn', 'url' => route('admin_campaign_index').'#add', 'tone' => 'red'],
        ['label' => 'Upload a photo', 'icon' => 'fa-image', 'url' => route('admin_photo_index').'#add', 'tone' => 'green'],
        ['label' => 'Add a publication', 'icon' => 'fa-book-open', 'url' => route('admin_publication_index').'#add', 'tone' => 'blue'],
        ['label' => 'Create a page', 'icon' => 'fa-file-circle-plus', 'url' => route('admin_custom_page_index').'#add', 'tone' => 'gray'],
    ];
@endphp

<!-- Greeting -->
<div class="dash-hero">
    <div>
        <h1>{{ $greeting }}, {{ $me->name }} 👋</h1>
        <p>{{ now()->format('l, j F Y') }} · Signed in as <b>{{ $me->roleLabel() }}</b></p>
    </div>
    @if($can_edit)
    <a href="{{ route('admin_post_index') }}#add" class="dash-hero-btn"><i class="fa-solid fa-plus"></i> Write a post</a>
    @endif
</div>

<!-- Needs attention -->
<div class="dash-block">
    <h2 class="dash-title"><i class="fa-solid fa-bell"></i> Needs your attention</h2>
    @if(count($attention))
    <div class="dash-attn-list">
        @foreach($attention as $a)
        <a href="{{ $a['url'] }}" class="dash-attn">
            <span class="dash-attn-ic"><i class="fa-solid {{ $a['icon'] }}"></i></span>
            <span class="dash-attn-txt"><b>{{ $a['count'] }}</b> {{ $a['label'] }}</span>
            <span class="dash-attn-go">Review <i class="fa-solid fa-arrow-right"></i></span>
        </a>
        @endforeach
    </div>
    @else
    <div class="dash-allgood"><i class="fa-solid fa-circle-check"></i> All caught up — nothing is waiting for you right now.</div>
    @endif
</div>

<!-- Quick actions -->
@if($can_edit)
<div class="dash-block">
    <h2 class="dash-title"><i class="fa-solid fa-bolt"></i> What would you like to do?</h2>
    <div class="dash-actions">
        @foreach($actions as $act)
        <a href="{{ $act['url'] }}" class="dash-action tone-{{ $act['tone'] }}">
            <span class="dash-action-ic"><i class="fa-solid {{ $act['icon'] }}"></i></span>
            <span>{{ $act['label'] }}</span>
        </a>
        @endforeach
    </div>
</div>
@endif

<!-- Overview -->
<div class="dash-block">
    <h2 class="dash-title"><i class="fa-solid fa-chart-simple"></i> Website at a glance</h2>
    <div class="dash-stats">
        <a href="{{ route('admin_post_index') }}" class="dash-stat"><i class="fa-solid fa-newspaper"></i><b>{{ number_format($counts['posts']) }}</b><span>Posts</span></a>
        <a href="{{ route('admin_event_index') }}" class="dash-stat"><i class="fa-solid fa-calendar-days"></i><b>{{ number_format($counts['upcoming_events']) }}</b><span>Upcoming events</span></a>
        <a href="{{ route('admin_campaign_index') }}" class="dash-stat"><i class="fa-solid fa-bullhorn"></i><b>{{ number_format($counts['campaigns']) }}</b><span>Campaigns</span></a>
        <a href="{{ route('admin_publication_index') }}" class="dash-stat"><i class="fa-solid fa-book-open"></i><b>{{ number_format($counts['publications']) }}</b><span>Publications</span></a>
        <a href="{{ route('admin_photo_index') }}" class="dash-stat"><i class="fa-solid fa-images"></i><b>{{ number_format($counts['photos']) }}</b><span>Gallery photos</span></a>
        <a href="{{ route('admin_custom_page_index') }}" class="dash-stat"><i class="fa-solid fa-file-lines"></i><b>{{ number_format($counts['pages']) }}</b><span>Pages</span></a>
    </div>
    @if($me->isSuperAdmin())
    <div class="dash-stats dash-stats-people">
        <a href="{{ route('admin_membership_members') }}" class="dash-stat"><i class="fa-solid fa-id-card"></i><b>{{ number_format($total_members) }}</b><span>Approved members</span></a>
        <a href="{{ route('admin_user_index') }}" class="dash-stat"><i class="fa-solid fa-users"></i><b>{{ number_format($total_users) }}</b><span>Registered users</span></a>
        <a href="{{ route('admin_subscriber_index') }}" class="dash-stat"><i class="fa-solid fa-envelope-open-text"></i><b>{{ number_format($total_subscribers) }}</b><span>Newsletter subscribers</span></a>
        <a href="{{ route('admin_donation_index') }}" class="dash-stat"><i class="fa-solid fa-hand-holding-heart"></i><b>{{ number_format($total_raised, 2) }}</b><span>Raised from {{ number_format($total_donations) }} donations</span></a>
    </div>
    @endif
</div>

<!-- Recent content -->
<div class="row g-3">
    <div class="col-12 col-lg-6">
        <div class="dash-panel">
            <div class="dash-panel-head">
                <h2 class="dash-title mb-0"><i class="fa-solid fa-calendar-days"></i> Coming up</h2>
                <a href="{{ route('admin_event_index') }}">All events</a>
            </div>
            @forelse($upcoming_events as $ev)
            <div class="dash-row">
                <span class="dash-date"><b>{{ \Carbon\Carbon::parse($ev->event_date)->format('j') }}</b>{{ \Carbon\Carbon::parse($ev->event_date)->format('M') }}</span>
                <div class="dash-row-txt">
                    <b>{{ $ev->title }}</b>
                    <small>{{ $ev->event_location }}@if($ev->event_time) · {{ $ev->event_time }}@endif</small>
                </div>
                <a href="{{ route('event', slug_for($ev)) }}" target="_blank" class="dash-row-link" title="View on website"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
            </div>
            @empty
            <p class="dash-empty">No upcoming events.@if($can_edit) <a href="{{ route('admin_event_index') }}#add">Add one</a>@endif</p>
            @endforelse
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <div class="dash-panel">
            <div class="dash-panel-head">
                <h2 class="dash-title mb-0"><i class="fa-solid fa-newspaper"></i> Latest posts</h2>
                <a href="{{ route('admin_post_index') }}">All posts</a>
            </div>
            @forelse($latest_posts as $post)
            <div class="dash-row">
                @if($post->image)
                <img class="dash-thumb" src="{{ asset('uploads/'.$post->image) }}" alt="">
                @else
                <span class="dash-thumb"></span>
                @endif
                <div class="dash-row-txt">
                    <b>{{ $post->title }}</b>
                    <small>{{ $post->created_at ? $post->created_at->format('j M Y') : '' }}</small>
                </div>
                <a href="{{ route('post', slug_for($post)) }}" target="_blank" class="dash-row-link" title="View on website"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
            </div>
            @empty
            <p class="dash-empty">No posts yet.@if($can_edit) <a href="{{ route('admin_post_index') }}#add">Write the first one</a>@endif</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Where do I...? -->
<div class="dash-panel mt-3">
    <h2 class="dash-title"><i class="fa-solid fa-life-ring"></i> Where do I…?</h2>
    <div class="dash-help">
        <div><b>Change the home page cover photo or headline</b><span>Website Pages → Home Page → Cover Image &amp; Headline</span></div>
        <div><b>Turn a home page section on or off</b><span>Website Pages → Home Page → Texts &amp; Sections On/Off</span></div>
        <div><b>Add a new page (e.g. under “About”)</b><span>Website Pages → Pages → All Pages, then choose its menu</span></div>
        <div><b>Change the title shown on the Events / News page</b><span>Website Pages → Page Titles &amp; Texts</span></div>
        @if($me->isSuperAdmin())
        <div><b>Change the logo or colours</b><span>Settings → Look &amp; Branding</span></div>
        <div><b>Add another person to this admin panel</b><span>Settings → Admin Accounts</span></div>
        @endif
    </div>
    <p class="dash-tip"><i class="fa-solid fa-magnifying-glass"></i> Tip: use the search box at the top to jump to any section by name.</p>
</div>
@endsection
