@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead"><div class="shell">
    <h1>{{ __('Membership card status') }}</h1>
</div></div>

<section class="shell" style="padding-block:var(--s8);max-width:560px">
    @if($user && $user->cardIsExpired())
    <div class="verify-result verify-expired">
        <div class="verify-icon"><i class="fa-solid fa-circle-exclamation"></i></div>
        <h2>{{ __('This card has expired') }}</h2>
        <p class="text-muted">{{ __('Membership ID') }}: <b dir="ltr">{{ $user->membership_id }}</b></p>
        <p class="text-muted">{{ __('Expired on') }}: {{ fdate($user->card_expires_at, 'j F Y') }}</p>
    </div>
    @elseif($user)
    <div class="verify-result verify-ok">
        <div class="verify-icon"><i class="fa-solid fa-circle-check"></i></div>
        <h2>{{ __('Valid membership') }}</h2>
        <p class="text-muted">{{ __('Membership ID') }}: <b dir="ltr">{{ $user->membership_id }}</b></p>
        @if($user->card_expires_at)
        <p class="text-muted">{{ __('Card valid until') }}: {{ fdate($user->card_expires_at, 'j F Y') }}</p>
        @endif
        <p class="verify-hint">{{ __('Check that this ID matches the one printed on the card.') }}</p>
    </div>
    @else
    <div class="verify-result verify-fail">
        <div class="verify-icon"><i class="fa-solid fa-circle-xmark"></i></div>
        <h2>{{ __('Not a valid or active membership card') }}</h2>
    </div>
    @endif
</section>
@endsection
