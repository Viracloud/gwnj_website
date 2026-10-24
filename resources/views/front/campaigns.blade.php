@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead"><div class="shell">
    <span class="kicker">{{ __('Our Work') }}</span>
    <h1>{{ __('Campaigns') }}</h1>
    <p class="lede">{{ __('International campaigns combining public mobilisation, legal advocacy and coordinated pressure.') }}</p>
</div></div>

<section class="shell">
    @php $cat_q = request('category') ? ['category' => request('category')] : []; $st_q = request('status') ? ['status' => request('status')] : []; @endphp
    <div class="filters">
        <a href="{{ route('campaigns', $cat_q) }}" class="{{ !request('status') ? 'active' : '' }}">{{ __('All') }}</a>
        <a href="{{ route('campaigns', ['status' => 'active'] + $cat_q) }}" class="{{ request('status') == 'active' ? 'active' : '' }}">{{ __('Active') }}</a>
        <a href="{{ route('campaigns', ['status' => 'won'] + $cat_q) }}" class="{{ request('status') == 'won' ? 'active' : '' }}">{{ __('Won') }}</a>
        <a href="{{ route('campaigns', ['status' => 'closed'] + $cat_q) }}" class="{{ request('status') == 'closed' ? 'active' : '' }}">{{ __('Closed') }}</a>
    </div>
    @if($campaign_categories->count())
    <div class="filters filters-sub">
        <span class="filters-label">{{ __('Topic:') }}</span>
        <a href="{{ route('campaigns', $st_q) }}" class="{{ !request('category') ? 'active' : '' }}">{{ __('All') }}</a>
        @foreach($campaign_categories as $cat)
        <a href="{{ route('campaigns', ['category' => $cat->slug] + $st_q) }}" class="{{ request('category') == $cat->slug ? 'active' : '' }}">{{ tf($cat,'name') }}</a>
        @endforeach
    </div>
    @endif

    @if($campaigns->count())
    <div class="cards">
        @foreach($campaigns as $campaign)
        @php $chip = ['active' => 'urgent', 'won' => 'done', 'closed' => 'plain'][$campaign->status] ?? 'plain'; @endphp
        <a class="card" href="{{ route('campaign', slug_for($campaign)) }}">
            @if($campaign->image)
            <div class="photo"><img src="{{ asset('uploads/'.$campaign->image) }}" alt="{{ tf($campaign,'title') }}"></div>
            @endif
            <div class="card-tags">
                <span class="chip {{ $chip }}">
                    {{ $campaign->status === 'active' ? (__('Active')) : ($campaign->status === 'won' ? (__('Won')) : (__('Closed'))) }}
                </span>
                @if($campaign->category)<span class="chip plain">{{ tf($campaign->category,'name') }}</span>@endif
            </div>
            <h3>{{ tf($campaign,'title') }}</h3>
            @if(tf($campaign,'excerpt'))<p>{{ plain_excerpt(tf($campaign,'excerpt'), 120) }}</p>@endif
            <span class="readmore">{{ __('Read more') }}</span>
        </a>
        @endforeach
    </div>
    {{ $campaigns->links() }}
    @else
    <p style="color:var(--muted)">{{ __('No campaigns in this category yet.') }}</p>
    @endif
</section>
@endsection
