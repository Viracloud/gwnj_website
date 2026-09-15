@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead"><div class="shell">
    @if(tf($about_item,'subheading'))<span class="kicker">{{ tf($about_item,'subheading') }}</span>@endif
    <h1>{{ is_rtl() ? 'درباره ما' : 'About us' }}</h1>
</div></div>

@if($page_data->about_status == 'Show' && $about_item)
<section class="shell" style="padding-block:var(--s8)">
    <div class="feature">
        <div class="photo">
            <img src="{{ asset('uploads/'.$about_item->image) }}" alt="{{ tf($about_item,'heading') }}">
            @if($about_item->youtube_id != '')
            <a href="https://www.youtube.com/watch?v={{ $about_item->youtube_id }}" class="glightbox" aria-label="{{ is_rtl() ? 'پخش ویدیو' : 'Play video' }}"
                style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;z-index:2">
                <span style="width:64px;height:64px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;box-shadow:var(--shadow)">
                    <i class="fa-solid fa-play" style="color:var(--brand)"></i>
                </span>
            </a>
            @endif
        </div>
        <div>
            <h2>{{ tf($about_item,'heading') }}</h2>
            <p>{{ tf($about_item,'text') }}</p>

            @if($about_item->experience_year != '' || tf($about_item,'experience_text'))
            <div class="aside-box" style="max-width:280px">
                <div class="in" style="display:flex;align-items:center;gap:var(--s4)">
                    @if($about_item->experience_year != '')
                    <b style="font-family:var(--font-ui);font-size:2.4rem;font-weight:800;color:var(--brand)">{{ $about_item->experience_year }}+</b>
                    @endif
                    @if(tf($about_item,'experience_text'))
                    <span style="font-size:var(--t-sm);color:var(--muted)">{{ tf($about_item,'experience_text') }}</span>
                    @endif
                </div>
            </div>
            @endif

            <div class="row">
                @if($about_item->button_link != '' && $about_item->button_text != '')
                <a class="btn btn-signal" href="{{ $about_item->button_link }}">{{ tf($about_item,'button_text') }}</a>
                @endif
                @if($about_item->phone_number != '')
                <a class="btn btn-out" href="tel:{{ $about_item->phone_number }}">
                    <i class="fa-solid fa-phone-volume"></i>
                    @if(tf($about_item,'phone_text'))
                        {{ tf($about_item,'phone_text') }}
                    @else
                        <bdi dir="ltr">{{ $about_item->phone_number }}</bdi>
                    @endif
                </a>
                @endif
            </div>
        </div>
    </div>
</section>
@endif
@endsection
