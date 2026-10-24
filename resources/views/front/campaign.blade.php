@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
@php
    $chip = ['active' => 'urgent', 'won' => 'done', 'closed' => 'plain'][$campaign->status] ?? 'plain';
    $status_label = $campaign->status === 'active' ? (__('Active'))
        : ($campaign->status === 'won' ? (__('Won')) : (__('Closed')));
    $cta_text = tf($campaign,'cta_text') ?: (__('Get involved'));
    $cta_link = $campaign->cta_link ?: url('/page/get-involved');
@endphp
<div class="pagehead"><div class="shell">
    <span class="kicker">{{ __('Campaigns') }}</span>
    <h1>{{ tf($campaign,'title') }}</h1>
    @if(tf($campaign,'excerpt'))
    <p class="lede">{{ tf($campaign,'excerpt') }}</p>
    @endif
</div></div>

<section class="shell">
    <div class="doc">
        <div class="doc-main">
            @if($campaign->image)
            <div class="photo photo-banner"><img src="{{ asset('uploads/'.$campaign->image) }}" alt="{{ tf($campaign,'title') }}"></div>
            @endif

            <div class="doc-meta">
                <span class="chip {{ $chip }}">{{ $status_label }}</span>
                @if($campaign->category)
                <a class="chip plain" href="{{ route('campaigns', ['category' => $campaign->category->slug]) }}" style="text-decoration:none">{{ tf($campaign->category,'name') }}</a>
                @endif
                @if(tf($campaign,'countries'))<span>{{ tf($campaign,'countries') }}</span>@endif
                @if($campaign->published_date)<span>{{ __('Since') }} {{ fdate($campaign->published_date, 'F Y') }}</span>@endif
            </div>

            <div style="margin-bottom:var(--s6)">
                @include('front.partials.share', ['share_url' => url()->current(), 'share_title' => tf($campaign,'title')])
            </div>

            @if(tf($campaign,'goal'))
            <div class="callout">
                <span class="kicker">{{ __('Campaign goal') }}</span>
                <p>{{ tf($campaign,'goal') }}</p>
            </div>
            @endif

            <div class="rich-content">
                {!! clean_html(tf($campaign,'content')) !!}
            </div>

            <div class="cta-row">
                <a class="btn btn-signal btn-lg" href="{{ $cta_link }}" @if($campaign->cta_link) target="_blank" rel="noopener" @endif>{{ $cta_text }}</a>
                <a class="more" href="{{ route('campaigns') }}">{{ __('All campaigns') }}</a>
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
                <h3>{{ __('Other campaigns') }}</h3>
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
