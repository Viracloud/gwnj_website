@extends('front.layouts.master')

@section('banner')
@endsection

@php
    $post_cat = $post->post_category;
    $tags = array_filter(array_map('trim', explode(',', (string) $post->tags)));
@endphp

@section('content')

<div class="pagehead"><div class="shell">
    <span class="kicker">{{ $post_cat ? tf($post_cat,'name') : (is_rtl() ? 'اخبار' : 'News') }}</span>
    <h1>{{ tf($post,'title') }}</h1>
</div></div>

<section class="shell">
<div class="doc">
    <div class="doc-main">

        @if($post->image)
        <div class="photo" style="aspect-ratio:16/9;margin-bottom:var(--s6)">
            <img src="{{ asset('uploads/'.$post->image) }}" alt="{{ tf($post,'title') }}">
        </div>
        @endif

        <div class="doc-meta">
            @if($post->author_name)<span>{{ is_rtl() ? 'نویسنده:' : 'By' }} <b>{{ $post->author_name }}</b></span>@endif
            <span>{{ fdate($post->published_at ?? $post->created_at, 'j F Y') }}</span>
            @if($post_cat)
            <span><a href="{{ route('category', slug_for($post_cat)) }}" style="color:inherit;text-decoration:none">{{ tf($post_cat,'name') }}</a></span>
            @endif
        </div>

        <div style="margin-bottom:var(--s6)">
            @include('front.partials.share', ['share_url' => url()->current(), 'share_title' => tf($post,'title')])
        </div>

        <div class="rich-content">
            {!! tf($post,'description') !!}
        </div>

        @if(count($tags))
        <div style="margin-top:var(--s6);display:flex;flex-wrap:wrap;gap:.5rem;align-items:center">
            <span style="font-family:var(--font-ui);font-size:var(--t-xs);font-weight:700;color:var(--muted)">{{ is_rtl() ? 'برچسب‌ها:' : 'Tags:' }}</span>
            @foreach($tags as $t)
            <a class="chip plain" href="{{ route('tag',$t) }}" style="text-decoration:none">{{ $t }}</a>
            @endforeach
        </div>
        @endif

        <div style="margin-top:var(--s8)">
            <h2>{{ $total_approved_comments }} {{ is_rtl() ? 'دیدگاه' : ($total_approved_comments == 1 ? 'Comment' : 'Comments') }}</h2>

            @forelse($comments as $comment)
            <div class="comment-block">
                <div class="who">{{ $comment->name }}</div>
                <div class="when">{{ fdate($comment->created_at, 'j F Y') }}</div>
                <p>{!! nl2br(e($comment->comment)) !!}</p>
                <a class="reply-link" href="javascript:void(0)" onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.toggle('d-none')">{{ is_rtl() ? 'پاسخ' : 'Reply' }}</a>

                @php
                    $replies = \App\Models\Reply::where('comment_id',$comment->id)->where('status','Approved')->orderBy('id','asc')->get();
                @endphp
                @foreach($replies as $reply)
                <div class="comment-block" style="margin-top:var(--s3);margin-bottom:0;border-inline-start:3px solid var(--v200)">
                    <div class="who">{{ $reply->name }} @if($reply->reply_by == 'Admin')<span class="chip plain" style="margin-inline-start:.4rem">{{ is_rtl() ? 'ادمین' : 'Admin' }}</span>@endif</div>
                    <div class="when">{{ fdate($reply->created_at, 'j F Y') }}</div>
                    <p>{!! nl2br(e($reply->reply)) !!}</p>
                </div>
                @endforeach

                <form action="{{ route('reply_store') }}" method="post" id="reply-form-{{ $comment->id }}" class="d-none" style="margin-top:var(--s4)">
                    @csrf
                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                    <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="name" placeholder="{{ is_rtl() ? 'نام شما *' : 'Your Name *' }}" required>
                        </div>
                        <div class="col-md-6">
                            <input type="email" class="form-control" name="email" placeholder="{{ is_rtl() ? 'ایمیل شما *' : 'Your Email *' }}" required>
                        </div>
                        <div class="col-12">
                            <textarea class="form-control" rows="4" name="reply" placeholder="{{ is_rtl() ? 'پاسخ خود را بنویسید *' : 'Write your Reply *' }}"></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-brand">{{ is_rtl() ? 'ارسال پاسخ' : 'Submit Reply' }}</button>
                        </div>
                    </div>
                </form>
            </div>
            @empty
            <p style="color:var(--muted)">{{ is_rtl() ? 'هنوز دیدگاهی ثبت نشده است.' : 'No comments yet.' }}</p>
            @endforelse
        </div>

        <div class="form-card" style="margin-top:var(--s6)">
            <h2 style="margin-top:0">{{ is_rtl() ? 'دیدگاه بگذارید' : 'Leave a Reply' }}</h2>
            <p style="color:var(--muted);font-family:var(--font-ui);font-size:var(--t-sm);margin-bottom:var(--s5)">
                {{ is_rtl() ? 'ایمیل شما منتشر نخواهد شد. موارد ستاره‌دار الزامی‌اند.' : 'Your email address will not be published. Required fields are marked *' }}
            </p>
            <form action="{{ route('comment_store') }}" method="post">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="name" placeholder="{{ is_rtl() ? 'نام شما *' : 'Your Name *' }}" required>
                    </div>
                    <div class="col-md-6">
                        <input type="email" class="form-control" name="email" placeholder="{{ is_rtl() ? 'ایمیل شما *' : 'Your Email *' }}" required>
                    </div>
                    <div class="col-12">
                        <textarea class="form-control" rows="5" name="comment" placeholder="{{ is_rtl() ? 'دیدگاه خود را بنویسید *' : 'Write your Comment *' }}"></textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-brand">{{ is_rtl() ? 'ارسال دیدگاه' : 'Post Comment' }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div>
        <div class="aside-box">
            <h3>{{ is_rtl() ? 'جستجو' : 'Search' }}</h3>
            <div class="in">
                <form action="{{ route('search') }}" method="get" style="display:flex;gap:.5rem">
                    <input type="text" class="form-control" name="search_text" placeholder="{{ is_rtl() ? 'جستجوی اخبار...' : 'Search news...' }}">
                    <button type="submit" class="btn btn-out" style="flex-shrink:0"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
            </div>
        </div>

        <div class="aside-box">
            <h3>{{ is_rtl() ? 'اخبار اخیر' : 'Recent posts' }}</h3>
            <ul class="linklist">
                @foreach($recent_posts as $recent_post)
                <li><a href="{{ route('post', slug_for($recent_post)) }}">{{ tf($recent_post,'title') }}</a>
                    <span>{{ fdate($recent_post->published_at ?? $recent_post->created_at, 'j F Y') }}</span></li>
                @endforeach
            </ul>
        </div>

        <div class="aside-box">
            <h3>{{ is_rtl() ? 'دسته‌بندی‌ها' : 'Categories' }}</h3>
            <ul class="linklist count">
                @foreach($post_categopries as $post_category)
                <li><a href="{{ route('category', slug_for($post_category)) }}">{{ tf($post_category,'name') }}</a>
                    <span>{{ $post_category->posts_count }} {{ is_rtl() ? 'خبر' : 'posts' }}</span></li>
                @endforeach
            </ul>
        </div>

        @php
            $all_months = App\Models\Post::selectRaw('DATE_FORMAT(created_at, "%M %Y") as month, COUNT(*) as post_count')
                ->groupBy('month')
                ->orderByRaw('MIN(created_at) DESC')
                ->get();
        @endphp
        @if($all_months->count())
        <div class="aside-box">
            <h3>{{ is_rtl() ? 'بایگانی' : 'Archives' }}</h3>
            <ul class="linklist count">
                @foreach($all_months as $m)
                <li><a href="{{ route('archive', ['month' => $m->month]) }}">{{ fdate($m->month, 'F Y') }}</a>
                    <span>{{ $m->post_count }} {{ is_rtl() ? 'خبر' : 'posts' }}</span></li>
                @endforeach
            </ul>
        </div>
        @endif

        @php
            $all_tags = App\Models\Post::pluck('tags')->toArray();
            $tags_array = [];
            foreach($all_tags as $t_row) {
                foreach(explode(',', (string) $t_row) as $t_split) {
                    $t_trim = trim($t_split);
                    if($t_trim && !in_array($t_trim, $tags_array)) {
                        $tags_array[] = $t_trim;
                    }
                }
            }
        @endphp
        @if(count($tags_array))
        <div class="aside-box">
            <h3>{{ is_rtl() ? 'برچسب‌های محبوب' : 'Popular Tags' }}</h3>
            <div class="in" style="display:flex;flex-wrap:wrap;gap:.5rem">
                @foreach($tags_array as $t)
                <a class="chip plain" href="{{ route('tag', $t) }}" style="text-decoration:none">{{ $t }}</a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
</section>

@endsection
