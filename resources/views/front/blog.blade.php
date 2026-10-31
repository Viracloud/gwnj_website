@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')

<div class="pagehead"><div class="shell">
    <h1>{{ tf($page_data,'page_title') }}</h1>
</div></div>

<section class="shell">
    @if($posts->count())
    <div class="cards">
        @foreach($posts as $post)
        <a class="card" href="{{ route('post', slug_for($post)) }}">
            <div class="photo"><img src="{{ asset('uploads/'.$post->image) }}" alt="{{ tf($post,'title') }}"></div>
            @include('front.partials.post-meta')
            <h3>{{ tf($post,'title') }}</h3>
            @if(tf($post,'excerpt'))<p>{{ plain_excerpt(tf($post,'excerpt'), 130) }}</p>@endif
            <span class="readmore">{{ hc('news_readmore') }}</span>
        </a>
        @endforeach
    </div>
    {{ $posts->links() }}
    @else
    <p style="text-align:center;color:var(--muted)">{{ __('No news posts yet.') }}</p>
    @endif
</section>

@endsection
