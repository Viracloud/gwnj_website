@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')

<div class="pagehead"><div class="shell">
    <span class="kicker">{{ is_rtl() ? 'دسته‌بندی' : 'Category' }}</span>
    <h1>{{ tf($category,'name') }}</h1>
    @if($category->description)
    <p style="color:var(--muted);max-width:60ch;margin-top:var(--s3)">{{ $category->description }}</p>
    @endif
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
    <p style="text-align:center;color:var(--muted)">{{ is_rtl() ? 'در این دسته‌بندی هنوز خبری وجود ندارد.' : 'No posts found in this category yet.' }}</p>
    @endif
</section>

@endsection
