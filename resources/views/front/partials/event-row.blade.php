@php
    $ev_date = $event->event_date ? \Carbon\Carbon::parse($event->event_date)->startOfDay() : null;
    $ev_days = $ev_date ? (int) now()->startOfDay()->diffInDays($ev_date, false) : null;
    $ev_num = fn($n) => local_digits($n);
    if ($ev_days === null) {
        $ev_status = null;
    } elseif ($ev_days < 0) {
        $ev_status = __('Past');
    } elseif ($ev_days === 0) {
        $ev_status = __('Today');
    } elseif ($ev_days === 1) {
        $ev_status = __('Tomorrow');
    } else {
        $ev_status = __('In :count days', ['count' => $ev_num($ev_days)]);
    }
@endphp
<li class="event{{ $event->image ? ' has-img' : '' }}{{ ($ev_days !== null && $ev_days < 0) ? ' is-past' : '' }}">
    <div class="leaf">
        <span class="d">{{ fdate($event->event_date, 'j') }}</span>
        <span class="m">{{ fdate($event->event_date, 'M') }}</span>
        <span class="y">{{ fdate($event->event_date, 'Y') }}</span>
    </div>
    @if($event->image)
    <div class="ev-thumb"><img src="{{ asset('uploads/'.$event->image) }}" alt="" loading="lazy"></div>
    @endif
    <div class="ev-body">
        @if($ev_status || $event->category)
        <div class="ev-tags">
            @if($ev_status)<span class="ev-status">{{ $ev_status }}</span>@endif
            @if($event->category)<span class="ev-cat">{{ tf($event->category,'name') }}</span>@endif
        </div>
        @endif
        <h3><a href="{{ route('event', slug_for($event)) }}">{{ tf($event,'title') }}</a></h3>
        <div class="ev-meta">
            @if(tf($event,'event_location'))
            <span><svg viewBox="0 0 24 24"><path d="M12 21s-7-4.4-7-10a7 7 0 0114 0c0 5.6-7 10-7 10z"/><circle cx="12" cy="11" r="2.5"/></svg>{{ tf($event,'event_location') }}</span>
            @endif
            @if($event->event_time)
            <span><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg><bdi>{{ $event->event_time }}</bdi></span>
            @endif
        </div>
    </div>
    <span class="ev-go" aria-hidden="true">{{ hc('events_details') }}<svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg></span>
</li>
