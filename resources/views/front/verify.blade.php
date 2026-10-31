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
        @if($user->photo)
        <img class="verify-photo" src="{{ asset('uploads/'.$user->photo) }}" alt="{{ $user->fullName() }}">
        @endif
        <p class="mb-1"><b>{{ $user->fullName() }}</b></p>
        <p class="text-muted">{{ __('Membership ID') }}: {{ $user->membership_id }}</p>
        <p class="text-muted">{{ __('Expired on') }}: {{ fdate($user->card_expires_at, 'j F Y') }}</p>
    </div>
    @elseif($user)
    <div class="verify-result verify-ok">
        <div class="verify-icon"><i class="fa-solid fa-circle-check"></i></div>
        <h2>{{ __('Valid membership') }}</h2>
        @if($user->photo)
        <img class="verify-photo" src="{{ asset('uploads/'.$user->photo) }}" alt="{{ $user->fullName() }}">
        <p class="verify-hint">{{ __('Compare the card holder’s face with this photo.') }}</p>
        @endif
        <p class="mb-1"><b>{{ $user->fullName() }}</b></p>
        <p class="text-muted">{{ __('Membership ID') }}: {{ $user->membership_id }}</p>
        <p class="text-muted">{{ __('Active member since') }} {{ optional($user->membership_approved_at)->format('Y') }}</p>
        @if($user->card_expires_at)
        <p class="text-muted">{{ __('Card valid until') }}: {{ fdate($user->card_expires_at, 'j F Y') }}</p>
        @endif
    </div>
    @else
    <div class="verify-result verify-fail">
        <div class="verify-icon"><i class="fa-solid fa-circle-xmark"></i></div>
        <h2>{{ __('Not a valid or active membership card') }}</h2>
    </div>
    @endif
</section>
@endsection
