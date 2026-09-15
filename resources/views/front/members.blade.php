@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead"><div class="shell">
    <span class="kicker">{{ is_rtl() ? 'تیم' : 'Team' }}</span>
    <h1>{{ tf($page_data,'page_title') }}</h1>
</div></div>

<section class="shell" style="padding-block:var(--s8)">
    <div class="cards">
        @foreach($members as $member)
        <a class="card" href="{{ route('member', $member->slug) }}">
            <div class="photo"><img src="{{ asset('uploads/'.$member->image) }}" alt="{{ $member->name }}"></div>
            <span class="dt">{{ tf($member,'designation') }}</span>
            <h3>{{ $member->name }}</h3>
        </a>
        @endforeach
    </div>

    @if($members->hasPages())
    <div style="margin-top:var(--s7)">
        {{ $members->links() }}
    </div>
    @endif
</section>
@endsection
