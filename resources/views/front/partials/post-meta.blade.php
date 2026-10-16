<div class="post-meta">
    @if($post->post_category)<span class="chip">{{ tf($post->post_category,'name') }}</span>@endif
    <span class="dt">{{ fdate($post->published_at ?? $post->created_at, 'j F Y') }}@if($post->author_name) · {{ $post->author_name }}@endif</span>
</div>
