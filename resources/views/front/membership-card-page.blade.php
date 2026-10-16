@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead no-print"><div class="shell">
    <span class="kicker">{{ is_rtl() ? 'حساب کاربری' : 'My account' }}</span>
    <h1>{{ is_rtl() ? 'کارت عضویت من' : 'My Membership Card' }}</h1>
</div></div>

<section class="shell" style="padding-block:var(--s8)">
    @include('front.partials.membership-card', ['user' => $user])

    <div class="row no-print" style="margin-top:var(--s6)">
        <button type="button" class="btn btn-brand" onclick="window.print()">{{ is_rtl() ? 'چاپ کارت' : 'Print card' }}</button>
        <a class="more" href="{{ route('dashboard') }}">{{ is_rtl() ? 'بازگشت به داشبورد' : 'Back to dashboard' }}</a>
    </div>
</section>
@endsection
