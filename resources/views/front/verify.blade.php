@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead"><div class="shell">
    <span class="kicker">{{ is_rtl() ? 'تأیید عضویت' : 'Membership Verification' }}</span>
    <h1>{{ is_rtl() ? 'وضعیت کارت عضویت' : 'Membership card status' }}</h1>
</div></div>

<section class="shell" style="padding-block:var(--s8);max-width:560px">
    @if($user)
    <div class="verify-result verify-ok">
        <div class="verify-icon"><i class="fa-solid fa-circle-check"></i></div>
        <h2>{{ is_rtl() ? 'عضویت معتبر است' : 'Valid membership' }}</h2>
        <p class="mb-1"><b>{{ $user->name }}</b></p>
        <p class="text-muted">{{ is_rtl() ? 'شناسهٔ عضویت' : 'Membership ID' }}: {{ $user->membership_id }}</p>
        <p class="text-muted">{{ is_rtl() ? 'عضو فعال از سال' : 'Active member since' }} {{ optional($user->membership_approved_at)->format('Y') }}</p>
    </div>
    @else
    <div class="verify-result verify-fail">
        <div class="verify-icon"><i class="fa-solid fa-circle-xmark"></i></div>
        <h2>{{ is_rtl() ? 'کارت معتبر یا فعال نیست' : 'Not a valid or active membership card' }}</h2>
    </div>
    @endif
</section>
@endsection
