@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead"><div class="shell">
    <span class="kicker">{{ __('Events') }}</span>
    <h1>{{ tf($event,'title') }}</h1>
</div></div>

<section class="shell"><div class="doc">
    <div class="doc-main">
        @if($event->image)
        <div class="photo photo-banner">
            <img src="{{ asset('uploads/'.$event->image) }}" alt="{{ tf($event,'title') }}">

            @if($event->youtube_id && ($event_video = video_embed($event->youtube_id)))
            {!! video_facade($event_video, 'vf-overlay') !!}
            @endif
        </div>
        @endif

        <div class="doc-meta">
            @if($event->category)
            <span><b>{{ __('Type') }}:</b> <a href="{{ route('events', ['category' => $event->category->slug]) }}" style="color:inherit">{{ tf($event->category,'name') }}</a></span>
            @endif
            @if(tf($event,'event_location'))
            <span><b>{{ __('Location') }}:</b> {{ tf($event,'event_location') }}</span>
            @endif
            @if($event->event_date)
            <span><b>{{ __('Date') }}:</b> {{ fdate($event->event_date, 'd F Y') }}</span>
            @endif
            @if($event->event_time)
            <span><b>{{ __('Time') }}:</b> {{ $event->event_time }}</span>
            @endif
            @if($event->ticket_price)
            <span><b>{{ __('Ticket') }}:</b> {{ $event->ticket_price }}</span>
            @endif
        </div>

        <div style="margin-bottom:var(--s6)">
            @include('front.partials.share', ['share_url' => url()->current(), 'share_title' => tf($event,'title')])
        </div>

        @if(tf($event,'event_location_detail'))
        <p>{!! nl2br(e(tf($event,'event_location_detail'))) !!}</p>
        @endif

        @if(tf($event,'description'))
        <h2>{{ __('About this event') }}</h2>
        <div>{!! clean_html(tf($event,'description')) !!}</div>
        @endif

        @if($all_faqs->count())
        <h2>{{ __('Frequently asked questions') }}</h2>
        <div class="accordion" id="eventFaq">
            @foreach($all_faqs as $faq)
            <div class="accordion-item">
                <h2 class="accordion-header" id="faqHead{{ $faq->id }}">
                    <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                        data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $faq->id }}"
                        aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="faqCollapse{{ $faq->id }}">
                        {{ tf($faq,'question') }}
                    </button>
                </h2>
                <div id="faqCollapse{{ $faq->id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                    aria-labelledby="faqHead{{ $faq->id }}" data-bs-parent="#eventFaq">
                    <div class="accordion-body">{!! nl2br(e(tf($faq,'answer'))) !!}</div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <aside>
        <div class="aside-box">
            <h3>{{ __('Take action') }}</h3>
            <div class="in">
                <ul class="linklist">
                    @if($event->event_date)
                    <li><b>{{ fdate($event->event_date, 'd F Y') }}@if($event->event_time) &middot; {{ $event->event_time }}@endif</b>
                        <span>{{ __('Date & time') }}</span></li>
                    @endif
                    @if(tf($event,'event_location'))
                    <li><b>{{ tf($event,'event_location') }}</b>
                        <span>{{ __('Location') }}</span></li>
                    @endif
                    @if($event->ticket_price)
                    <li><b>{{ $event->ticket_price }}</b>
                        <span>{{ __('Ticket price') }}</span></li>
                    @endif
                </ul>
                @if($event->button_text && $event->button_link)
                <a class="btn btn-signal" href="{{ $event->button_link }}">{{ tf($event,'button_text') }}</a>
                @endif
            </div>
        </div>
    </aside>
</div></section>
@endsection
