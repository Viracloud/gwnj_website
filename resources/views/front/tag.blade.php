@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')

<div class="pagehead"><div class="shell">
    <span class="kicker">{{ is_rtl() ? 'برچسب' : 'Tag' }}</span>
    <h1>{{ $tag }}</h1>
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
    <p style="text-align:center;color:var(--muted)">{{ is_rtl() ? 'با این برچسب خبری یافت نشد.' : 'No posts found with this tag.' }}</p>
    @endif
</section>

@endsection
