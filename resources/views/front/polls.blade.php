@extends('front.layouts.master')

@section('banner')
@endsection

@php $rtl = is_rtl(); @endphp

@section('content')
<div class="pagehead"><div class="shell">
    <span class="kicker">{{ __('Have your say') }}</span>
    <h1>{{ __('Polls & surveys') }}</h1>
    <p class="lede">{{ __('Take part in the network’s polls and see the overall results. Votes are anonymous.') }}</p>
</div></div>

<section class="shell">
    @if($polls->count())
    <div class="poll-list">
        @foreach($polls as $poll)
        @php $open = $poll->isOpen(); @endphp
        <a class="poll-card {{ $open ? 'is-open' : '' }}" href="{{ route('poll', $poll->slug) }}">
            <div class="poll-card-head">
                <span class="poll-icon"><i class="fa-solid {{ $open ? 'fa-square-poll-vertical' : 'fa-chart-simple' }}"></i></span>
                @if($open)
                <span class="chip done poll-live">{{ __('Open') }}</span>
                @else
                <span class="chip plain">{{ __('Closed') }}</span>
                @endif
            </div>
            <h3>{{ tf($poll, 'title') }}</h3>
            @if(tf($poll, 'description'))<p>{{ \Illuminate\Support\Str::limit(tf($poll, 'description'), 150) }}</p>@endif
            <ul class="poll-meta">
                <li><i class="fa-solid fa-users"></i>{{ trans_choice(':count participant|:count participants', $poll->totalResponses(), ['count' => local_digits($poll->totalResponses())]) }}</li>
                @if($poll->questions_count)
                <li><i class="fa-regular fa-circle-question"></i>{{ trans_choice(':count question|:count questions', $poll->questions_count, ['count' => local_digits($poll->questions_count)]) }}</li>
                @endif
                @if($open && $poll->ends_at)
                <li><i class="fa-regular fa-calendar"></i>{{ __('Open until :date', ['date' => fdate($poll->ends_at, 'j F Y')]) }}</li>
                @endif
            </ul>
            <span class="poll-card-cta">
                {{ $open ? __('Take part') : __('See results') }}
                <i class="fa-solid fa-arrow-{{ $rtl ? 'left' : 'right' }}"></i>
            </span>
        </a>
        @endforeach
    </div>
    {{ $polls->links() }}
    @else
    <div class="poll-empty">
        <span class="poll-icon"><i class="fa-solid fa-square-poll-vertical"></i></span>
        <p>{{ __('There are no polls at the moment.') }}</p>
    </div>
    @endif
</section>
@endsection
