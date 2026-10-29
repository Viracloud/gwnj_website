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
    @php $tones = ['navy', 'orange', 'green', 'sand']; @endphp
    <div class="ptiles-grid">
        @foreach($polls as $poll)
        @include('front.partials.poll-tile', ['tone' => $loop->last ? 'brand' : $tones[$loop->index % 4]])
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
