@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead"><div class="shell">
    <span class="kicker">{{ $member->category ? tf($member->category,'name') : (__('Team')) }}</span>
    <h1>{{ tf($member,'name') }}</h1>
</div></div>

<section class="shell" style="padding-block:var(--s8)">
    <div class="doc">
        <div class="doc-main">
            <div class="photo" style="max-width:380px;margin-bottom:var(--s5)">
                <img src="{{ asset('uploads/'.$member->image) }}" alt="{{ tf($member,'name') }}">
            </div>
            @if(tf($member,'designation'))
            <span class="kicker">{{ tf($member,'designation') }}</span>
            @endif
            <div>{!! clean_html(tf($member,'description')) ?: nl2br(e(tf($member,'short_description'))) !!}</div>

            @if($all_awards->count())
            <h2>{{ __('Awards & recognition') }}</h2>
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
            @php $show_pe = $member->show_contact; @endphp
            @if(($show_pe && ($member->phone != '' || $member->email != '')) || $member->experience != '' || $member->facebook != '' || $member->twitter != '' || $member->linkedin != '' || $member->instagram != '')
            <div class="aside-box">
                <h3>{{ __('Contact') }}</h3>
                <div class="in">
                    @if(($show_pe && ($member->phone != '' || $member->email != '')) || $member->experience != '')
                    <ul class="linklist">
                        @if($show_pe && $member->phone != '')
                        <li><a href="tel:{{ $member->phone }}" dir="ltr" style="unicode-bidi:isolate">{{ $member->phone }}</a><span>{{ __('Phone') }}</span></li>
                        @endif
                        @if($show_pe && $member->email != '')
                        <li><a href="mailto:{{ $member->email }}" dir="ltr" style="unicode-bidi:isolate">{{ $member->email }}</a><span>{{ __('Email') }}</span></li>
                        @endif
                        @if($member->experience != '')
                        <li>{{ $member->experience }}<span>{{ __('Experience') }}</span></li>
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
