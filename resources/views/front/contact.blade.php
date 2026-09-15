@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead"><div class="shell">
    <span class="kicker">{{ is_rtl() ? 'تماس با ما' : 'Contact' }}</span>
    <h1>{{ tf($page_data,'page_title') }}</h1>
</div></div>

<section class="shell">
    <div class="doc">
        <div class="doc-main">
            <div class="form-card">
                <h2 style="margin-top:0">{{ is_rtl() ? 'برای ما پیام بفرستید' : 'Send us a message' }}</h2>
                <form action="{{ route('contact_submit') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">{{ is_rtl() ? 'نام کامل' : 'Full name' }} *</label>
                        <input type="text" class="form-control" name="name" placeholder="{{ is_rtl() ? 'نام کامل خود را وارد کنید' : 'Enter your full name' }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ is_rtl() ? 'ایمیل' : 'Email address' }} *</label>
                        <input type="email" class="form-control" name="email" placeholder="{{ is_rtl() ? 'ایمیل خود را وارد کنید' : 'Enter your email address' }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">{{ is_rtl() ? 'پیام' : 'Message' }} *</label>
                        <textarea class="form-control" name="message" rows="5" placeholder="{{ is_rtl() ? 'پیام خود را بنویسید' : 'Write your message' }}" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-signal btn-lg" style="width:100%">
                        {{ is_rtl() ? 'ارسال پیام' : 'Send message' }}
                    </button>
                </form>
            </div>
        </div>

        <aside>
            <div class="aside-box">
                <h3>{{ is_rtl() ? 'تماس با ما' : 'Get in touch' }}</h3>
                <ul class="linklist">
                    @if($page_data->phone_number)
                    <li><a href="tel:{{ $page_data->phone_number }}" dir="ltr" style="unicode-bidi:isolate">{{ $page_data->phone_number }}</a>
                        <span>{{ is_rtl() ? 'تلفن' : 'Phone' }}</span></li>
                    @endif
                    @if($page_data->email_address)
                    <li><a href="mailto:{{ $page_data->email_address }}" dir="ltr" style="unicode-bidi:isolate">{{ $page_data->email_address }}</a>
                        <span>{{ is_rtl() ? 'ایمیل' : 'Email' }}</span></li>
                    @endif
                    @if(tf($page_data,'location'))
                    <li>{{ tf($page_data,'location') }}
                        <span>{{ is_rtl() ? 'آدرس' : 'Location' }}</span></li>
                    @endif
                </ul>
            </div>

            @if($page_data->map_iframe_code)
            <div class="map-embed">
                {!! $page_data->map_iframe_code !!}
            </div>
            @endif
        </aside>
    </div>
</section>
@endsection
