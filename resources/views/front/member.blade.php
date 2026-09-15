@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead"><div class="shell">
    <span class="kicker">{{ is_rtl() ? 'تیم' : 'Team' }}</span>
    <h1>{{ $member->name }}</h1>
</div></div>

<section class="shell" style="padding-block:var(--s8)">
    <div class="doc">
        <div class="doc-main">
            <div class="photo" style="max-width:380px;margin-bottom:var(--s5)">
                <img src="{{ asset('uploads/'.$member->image) }}" alt="{{ $member->name }}">
            </div>
            @if(tf($member,'designation'))
            <span class="kicker">{{ tf($member,'designation') }}</span>
            @endif
            <div>{!! tf($member,'description') ?: nl2br(e(tf($member,'short_description'))) !!}</div>

            @if($all_awards->count())
            <h2>{{ is_rtl() ? 'جوایز و افتخارات' : 'Awards & recognition' }}</h2>
            <div class="award-grid">
                @foreach($all_awards as $award)
                <div class="award-card">
                    <img src="{{ asset('uploads/'.$award->photo) }}" alt="{{ tf($award,'name') }}">
                    <span>{{ tf($award,'name') }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <div>
            @if($member->phone != '' || $member->email != '' || $member->experience != '' || $member->facebook != '' || $member->twitter != '' || $member->linkedin != '' || $member->instagram != '')
            <div class="aside-box">
                <h3>{{ is_rtl() ? 'تماس' : 'Contact' }}</h3>
                <div class="in">
                    @if($member->phone != '' || $member->email != '' || $member->experience != '')
                    <ul class="linklist">
                        @if($member->phone != '')
                        <li><a href="tel:{{ $member->phone }}" dir="ltr" style="unicode-bidi:isolate">{{ $member->phone }}</a><span>{{ is_rtl() ? 'تلفن' : 'Phone' }}</span></li>
                        @endif
                        @if($member->email != '')
                        <li><a href="mailto:{{ $member->email }}" dir="ltr" style="unicode-bidi:isolate">{{ $member->email }}</a><span>{{ is_rtl() ? 'ایمیل' : 'Email' }}</span></li>
                        @endif
                        @if($member->experience != '')
                        <li>{{ $member->experience }}<span>{{ is_rtl() ? 'سابقه' : 'Experience' }}</span></li>
                        @endif
                    </ul>
                    @endif
                    @if($member->facebook != '' || $member->twitter != '' || $member->linkedin != '' || $member->instagram != '')
                    <div style="display:flex;gap:var(--s3);margin-top:var(--s3)">
                        @if($member->facebook != '')<a href="{{ $member->facebook }}" target="_blank" rel="noopener" style="color:var(--brand);font-size:1.05rem"><i class="fab fa-facebook-f"></i></a>@endif
                        @if($member->twitter != '')<a href="{{ $member->twitter }}" target="_blank" rel="noopener" style="color:var(--brand);font-size:1.05rem"><i class="fab fa-twitter"></i></a>@endif
                        @if($member->linkedin != '')<a href="{{ $member->linkedin }}" target="_blank" rel="noopener" style="color:var(--brand);font-size:1.05rem"><i class="fab fa-linkedin-in"></i></a>@endif
                        @if($member->instagram != '')<a href="{{ $member->instagram }}" target="_blank" rel="noopener" style="color:var(--brand);font-size:1.05rem"><i class="fab fa-instagram"></i></a>@endif
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
