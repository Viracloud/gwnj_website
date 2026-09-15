@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead"><div class="shell">
    <span class="kicker">{{ is_rtl() ? 'انتشارات' : 'Publications' }}</span>
    <h1>{{ is_rtl() ? 'گزارش‌ها و انتشارات' : 'Reports and publications' }}</h1>
</div></div>

<section class="shell">
    @if($publication_categories->count())
    <div class="filters">
        <a href="{{ route('publications') }}" class="{{ !request('category') ? 'active' : '' }}">{{ is_rtl() ? 'همه' : 'All' }}</a>
        @foreach($publication_categories as $cat)
        <a href="{{ route('publications', ['category' => $cat->slug]) }}" class="{{ request('category') == $cat->slug ? 'active' : '' }}">{{ tf($cat,'name') }}</a>
        @endforeach
    </div>
    @endif
    @if($publications->count())
    <ul class="records">
        @foreach($publications as $pub)
        <li class="record">
            <div class="meta">
                <b>{{ $pub->published_date ? \Carbon\Carbon::parse($pub->published_date)->format('Y') : '' }}</b>
                <span>{{ $pub->category ? tf($pub->category,'name') : '' }}</span>
            </div>
            <div>
                <h3>{{ tf($pub, 'title') }}</h3>
                @if(tf($pub, 'description'))
                <p>{{ \Illuminate\Support\Str::limit(strip_tags(tf($pub, 'description')), 160) }}</p>
                @endif
            </div>
            @if($pub->file)
            <a class="go" href="{{ asset('uploads/'.$pub->file) }}" target="_blank" rel="noopener">{{ is_rtl() ? 'دانلود PDF' : 'Download PDF' }}</a>
            @endif
        </li>
        @endforeach
    </ul>
    {{ $publications->links() }}
    @else
    <p style="color:var(--muted)">{{ is_rtl() ? 'هنوز گزارشی منتشر نشده است.' : 'No publications have been posted yet.' }}</p>
    @endif
</section>
@endsection
