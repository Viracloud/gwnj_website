<a class="card" href="{{ route('member', slug_for($member)) }}">
    <div class="photo"><img src="{{ asset('uploads/'.$member->image) }}" alt="{{ tf($member,'name') }}"></div>
    @if(tf($member,'designation'))<span class="dt">{{ tf($member,'designation') }}</span>@endif
    <h3>{{ tf($member,'name') }}</h3>
    @if(tf($member,'short_description'))<p>{{ plain_excerpt(tf($member,'short_description'), 130) }}</p>@endif
    <span class="readmore">{{ is_rtl() ? 'مشاهدهٔ معرفی کامل' : 'View profile' }}</span>
</a>
