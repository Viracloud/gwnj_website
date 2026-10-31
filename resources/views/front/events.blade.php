@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead"><div class="shell">
    <h1>{{ tf($page_data,'page_title') }}</h1>
</div></div>

<section class="shell">
    @if($event_categories->count())
    <div class="filters">
        <a href="{{ route('events') }}" class="{{ !request('category') ? 'active' : '' }}">{{ __('All') }}</a>
        @foreach($event_categories as $cat)
        <a href="{{ route('events', ['category' => $cat->slug]) }}" class="{{ request('category') == $cat->slug ? 'active' : '' }}">{{ tf($cat,'name') }}</a>
        @endforeach
    </div>
    @endif
    @if($events->count())
    <ul class="events">
        @foreach($events as $event)
        @include('front.partials.event-row')
        @endforeach
    </ul>
    <div class="mt-5">{{ $events->links() }}</div>
    @else
    <p>{{ __('There are no events to show right now.') }}</p>
    @endif
</section>
@endsection
