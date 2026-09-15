@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead"><div class="shell">
    <span class="kicker">{{ is_rtl() ? 'رویدادها' : 'Events' }}</span>
    <h1>{{ tf($page_data,'page_title') }}</h1>
</div></div>

<section class="shell">
    @if($events->count())
    <ul class="events">
        @foreach($events as $event)
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
    <div class="mt-5">{{ $events->links() }}</div>
    @else
    <p>{{ is_rtl() ? 'در حال حاضر رویدادی برای نمایش وجود ندارد.' : 'There are no events to show right now.' }}</p>
    @endif
</section>
@endsection
