@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead"><div class="shell">
    <span class="kicker">{{ __('Contact') }}</span>
    <h1>{{ tf($page_data,'page_title') }}</h1>
</div></div>

<section class="shell">
    <div class="doc">
        <div class="doc-main">
            <div class="form-card">
                <h2 style="margin-top:0">{{ __('Send us a message') }}</h2>
                <form action="{{ route('contact_submit') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">{{ __('Full name') }} *</label>
                        <input type="text" class="form-control" name="name" placeholder="{{ __('Enter your full name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Email address') }} *</label>
                        <input type="email" class="form-control" name="email" placeholder="{{ __('Enter your email address') }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">{{ __('Message') }} *</label>
                        <textarea class="form-control" name="message" rows="5" placeholder="{{ __('Write your message') }}" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-signal btn-lg" style="width:100%">
                        {{ __('Send message') }}
                    </button>
                </form>
            </div>
        </div>

        <aside>
            <div class="aside-box">
                <h3>{{ __('Get in touch') }}</h3>
                <ul class="linklist">
                    @if($page_data->phone_number)
                    <li><a href="tel:{{ $page_data->phone_number }}" dir="ltr" style="unicode-bidi:isolate">{{ $page_data->phone_number }}</a>
                        <span>{{ __('Phone') }}</span></li>
                    @endif
                    @if($page_data->email_address)
                    <li><a href="mailto:{{ $page_data->email_address }}" dir="ltr" style="unicode-bidi:isolate">{{ $page_data->email_address }}</a>
                        <span>{{ __('Email') }}</span></li>
                    @endif
                    @if(tf($page_data,'location'))
                    <li>{{ tf($page_data,'location') }}
                        <span>{{ __('Location') }}</span></li>
                    @endif
                </ul>
            </div>

            @if($page_data->map_iframe_code)
            <div class="map-embed">
                {!! clean_map_embed($page_data->map_iframe_code) !!}
            </div>
            @endif
        </aside>
    </div>
</section>
@endsection
