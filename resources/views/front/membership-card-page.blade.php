@extends('front.layouts.master')

{{-- opens on a light surface, so the header keeps its solid background --}}
@section('body_class', 'no-float')

@section('banner')
@endsection

@section('content')
<div class="pagehead no-print"><div class="shell">
    <span class="kicker">{{ __('My account') }}</span>
    <h1>{{ __('My Membership Card') }}</h1>
</div></div>

<section class="shell" style="padding-block:var(--s8)">
    @include('front.partials.membership-card', ['user' => $user])

    <div class="row no-print" style="margin-top:var(--s6)">
        <button type="button" class="btn btn-brand" onclick="window.print()">{{ __('Print card') }}</button>
        <a class="more" href="{{ route('dashboard') }}">{{ __('Back to dashboard') }}</a>
    </div>
</section>
@endsection
