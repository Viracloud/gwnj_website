@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')

<div class="pagehead"><div class="shell">
    <span class="kicker">{{ is_rtl() ? 'جستجو' : 'Search' }}</span>
    <h1>{{ is_rtl() ? 'نتایج جستجو برای «'.$search_text.'»' : 'Search results for "'.$search_text.'"' }}</h1>
</div></div>

<section class="shell">
    @if($posts->count())
    <div class="cards">
        @foreach($posts as $post)
        <a class="card" href="{{ route('post', $post->slug) }}">
            <div class="photo"><img src="{{ asset('uploads/'.$post->image) }}" alt="{{ tf($post,'title') }}"></div>
            <span class="dt">{{ optional($post->published_at ?? $post->created_at)->format('j F Y') }}</span>
            <h3>{{ tf($post,'title') }}</h3>
            @if(tf($post,'excerpt'))<p>{{ \Illuminate\Support\Str::limit(tf($post,'excerpt'), 130) }}</p>@endif
        </a>
        @endforeach
    </div>
    {{ $posts->links() }}
    @else
    <p style="text-align:center;color:var(--muted);padding-block:var(--s6)">
        {{ is_rtl() ? 'هیچ نتیجه‌ای برای «'.$search_text.'» یافت نشد.' : 'No results found for "'.$search_text.'".' }}
    </p>
    @endif
</section>

@endsection
