@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead"><div class="shell">
    <span class="kicker">{{ is_rtl() ? 'کار ما' : 'Our Work' }}</span>
    <h1>{{ is_rtl() ? 'کمپاین‌ها' : 'Campaigns' }}</h1>
    <p class="lede">{{ is_rtl() ? 'کمپاین‌های بین‌المللی که بسیج عمومی، پیگیری حقوقی و فشار هماهنگ را با هم ترکیب می‌کنند.' : 'International campaigns combining public mobilisation, legal advocacy and coordinated pressure.' }}</p>
</div></div>

<section class="shell">
    <div class="filters">
        <a href="{{ route('campaigns') }}" class="{{ !request('status') ? 'active' : '' }}">{{ is_rtl() ? 'همه' : 'All' }}</a>
        <a href="{{ route('campaigns', ['status' => 'active']) }}" class="{{ request('status') == 'active' ? 'active' : '' }}">{{ is_rtl() ? 'در حال اجرا' : 'Active' }}</a>
        <a href="{{ route('campaigns', ['status' => 'won']) }}" class="{{ request('status') == 'won' ? 'active' : '' }}">{{ is_rtl() ? 'موفق' : 'Won' }}</a>
        <a href="{{ route('campaigns', ['status' => 'closed']) }}" class="{{ request('status') == 'closed' ? 'active' : '' }}">{{ is_rtl() ? 'بسته‌شده' : 'Closed' }}</a>
    </div>

    @if($campaigns->count())
    <div class="cards">
        @foreach($campaigns as $campaign)
        @php $chip = ['active' => 'urgent', 'won' => 'done', 'closed' => 'plain'][$campaign->status] ?? 'plain'; @endphp
        <a class="card" href="{{ route('campaign', slug_for($campaign)) }}">
            @if($campaign->image)
            <div class="photo"><img src="{{ asset('uploads/'.$campaign->image) }}" alt="{{ tf($campaign,'title') }}"></div>
            @endif
            <span class="chip {{ $chip }}">
                {{ $campaign->status === 'active' ? (is_rtl() ? 'در حال اجرا' : 'Active') : ($campaign->status === 'won' ? (is_rtl() ? 'موفق' : 'Won') : (is_rtl() ? 'بسته‌شده' : 'Closed')) }}
            </span>
            <h3>{{ tf($campaign,'title') }}</h3>
            @if(tf($campaign,'excerpt'))<p>{{ plain_excerpt(tf($campaign,'excerpt'), 120) }}</p>@endif
            <span class="readmore">{{ is_rtl() ? 'مطالعهٔ کامل' : 'Read more' }}</span>
        </a>
        @endforeach
    </div>
    {{ $campaigns->links() }}
    @else
    <p style="color:var(--muted)">{{ is_rtl() ? 'در این دسته کمپاینی وجود ندارد.' : 'No campaigns in this category yet.' }}</p>
    @endif
</section>
@endsection
