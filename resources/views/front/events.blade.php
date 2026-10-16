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
        @include('front.partials.event-row')
        @endforeach
    </ul>
    <div class="mt-5">{{ $events->links() }}</div>
    @else
    <p>{{ is_rtl() ? 'در حال حاضر رویدادی برای نمایش وجود ندارد.' : 'There are no events to show right now.' }}</p>
    @endif
</section>
@endsection
