@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
@php
    $chip = ['active' => 'urgent', 'won' => 'done', 'closed' => 'plain'][$campaign->status] ?? 'plain';
    $status_label = $campaign->status === 'active' ? (is_rtl() ? 'در حال اجرا' : 'Active')
        : ($campaign->status === 'won' ? (is_rtl() ? 'موفق' : 'Won') : (is_rtl() ? 'بسته‌شده' : 'Closed'));
    $cta_text = tf($campaign,'cta_text') ?: (is_rtl() ? 'مشارکت کنید' : 'Get involved');
    $cta_link = $campaign->cta_link ?: url('/page/get-involved');
@endphp
<div class="pagehead"><div class="shell">
    <span class="kicker">{{ is_rtl() ? 'کمپاین‌ها' : 'Campaigns' }}</span>
    <h1>{{ tf($campaign,'title') }}</h1>
    @if(tf($campaign,'excerpt'))
    <p class="lede">{{ tf($campaign,'excerpt') }}</p>
    @endif
</div></div>

<section class="shell">
    <div class="doc">
        <div class="doc-main">
            @if($campaign->image)
            <div class="photo" style="aspect-ratio:16/8;margin-bottom:var(--s6)"><img src="{{ asset('uploads/'.$campaign->image) }}" alt="{{ tf($campaign,'title') }}"></div>
            @endif

            <div class="doc-meta">
                <span class="chip {{ $chip }}">{{ $status_label }}</span>
                @if(tf($campaign,'countries'))<span>{{ tf($campaign,'countries') }}</span>@endif
                @if($campaign->published_date)<span>{{ is_rtl() ? 'از' : 'Since' }} {{ fdate($campaign->published_date, 'F Y') }}</span>@endif
            </div>

            <div style="margin-bottom:var(--s6)">
                @include('front.partials.share', ['share_url' => url()->current(), 'share_title' => tf($campaign,'title')])
            </div>

            @if(tf($campaign,'goal'))
            <div class="callout">
                <span class="kicker">{{ is_rtl() ? 'هدف کمپاین' : 'Campaign goal' }}</span>
                <p>{{ tf($campaign,'goal') }}</p>
            </div>
            @endif

            <div class="rich-content">
                {!! tf($campaign,'content') !!}
            </div>

            <div class="row" style="margin-top:var(--s6)">
                <a class="btn btn-signal btn-lg" href="{{ $cta_link }}" @if($campaign->cta_link) target="_blank" rel="noopener" @endif>{{ $cta_text }}</a>
                <a class="more" href="{{ route('campaigns') }}">{{ is_rtl() ? 'همهٔ کمپاین‌ها' : 'All campaigns' }}</a>
            </div>
        </div>

        <aside>
            @if($campaign->stat_number)
            <div class="aside-box">
                <div class="in" style="text-align:center">
                    <b style="display:block;font-size:var(--t-2xl);font-weight:800;color:var(--brand)">{{ $campaign->stat_number }}</b>
                    @if(tf($campaign,'stat_label'))<span style="font-family:var(--font-ui);font-size:var(--t-xs);color:var(--muted)">{{ tf($campaign,'stat_label') }}</span>@endif
                </div>
            </div>
            @endif

            @if($other_campaigns->count())
            <div class="aside-box">
                <h3>{{ is_rtl() ? 'کمپاین‌های دیگر' : 'Other campaigns' }}</h3>
                <div class="in">
                    <ul class="linklist">
                        @foreach($other_campaigns as $oc)
                        <li><a href="{{ route('campaign', slug_for($oc)) }}">{{ tf($oc,'title') }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </aside>
    </div>
</section>
@endsection
