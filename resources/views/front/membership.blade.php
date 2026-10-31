@extends('front.layouts.master')

@section('banner')
@endsection

@php
    $rtl = is_rtl();
    $dues = $setting && $setting->membership_dues_amount
        ? (($setting->paypal_currency ?: 'USD').' '.number_format($setting->membership_dues_amount, 2))
        : null;
    $steps = [
        ['icon' => 'fa-user-plus',
         't' => __('Register and choose a membership type'),
         'd' => __('Fill in the registration form, choose your membership type and accept the network’s by-laws.')],
        ['icon' => 'fa-envelope-circle-check',
         't' => __('Confirm your email'),
         'd' => __('Open the link we email you to activate your account.')],
        ['icon' => 'fa-user-check',
         't' => __('Application review'),
         'd' => __('Network officers review your application. You are told the result by email.')],
        ['icon' => 'fa-id-badge',
         't' => __('Complete your profile'),
         'd' => __('Once approved, add your photo and contact details to your profile — they appear on your membership card.')],
        ['icon' => 'fa-hand-holding-dollar',
         't' => __('Pay the annual membership dues'),
         'd' => $dues
            ? __('The annual dues are :amount, paid online. Members based in Iran, Afghanistan and Pakistan are exempt.', ['amount' => $dues])
            : (__('Where annual dues apply, they are paid online. Members based in Iran, Afghanistan and Pakistan are exempt.'))],
        ['icon' => 'fa-address-card',
         't' => __('Receive your digital membership card'),
         'd' => __('Request your card from your dashboard. Once an administrator approves it, the card is issued with your membership ID and a QR code, ready to print.')],
    ];
@endphp

@section('content')
<div class="pagehead"><div class="shell">
    <h1>{{ __('How to become a member') }}</h1>
    <p class="lede">
        {{ __('Membership is open to everyone committed to justice, equality and freedom for women. Follow the steps below.') }}
    </p>
</div></div>

<section class="shell">
    <ol class="m-steps">
        @foreach($steps as $i => $s)
        <li class="m-step">
            <span class="m-num">{{ local_digits($i + 1) }}</span>
            <span class="m-ic"><i class="fa-solid {{ $s['icon'] }}"></i></span>
            <h3>{{ $s['t'] }}</h3>
            <p>{{ $s['d'] }}</p>
        </li>
        @endforeach
    </ol>

    <div class="m-grid">
        <div class="m-box">
            <h2><i class="fa-solid fa-calendar-check"></i> {{ __('Card validity') }}</h2>
            <ul>
                <li>{{ __('Each membership card is valid for one year from the date it is issued. Issue and expiry dates are printed on the card.') }}</li>
                <li>{{ __('When it expires, request a renewal from your dashboard. Members who pay dues first pay the new year’s dues.') }}</li>
                <li>{{ __('An expired card shows as “expired” when it is verified.') }}</li>
            </ul>
        </div>
        <div class="m-box">
            <h2><i class="fa-solid fa-qrcode"></i> {{ __('Verifying a card') }}</h2>
            <ul>
                <li>{{ __('Every card carries a QR code. Scanning it opens the network’s official page, which shows whether the card is valid, expired or not recognised.') }}</li>
                <li>{{ __('Cards are issued only after approval by network officers and cannot be transferred to another person.') }}</li>
            </ul>
        </div>
        <div class="m-box">
            <h2><i class="fa-solid fa-users"></i> {{ __('Membership types') }}</h2>
            <ul class="m-types">
                <li>{{ __('Regular member') }}</li>
                <li>{{ __('Founding member') }}</li>
                <li>{{ __('Honorary member') }}</li>
                <li>{{ __('Affiliate member') }}</li>
                <li>{{ __('Organizational member') }}</li>
            </ul>
        </div>
    </div>

    <div class="m-cta">
        @if(Auth::guard('web')->check())
        <a class="btn btn-brand btn-lg" href="{{ route('dashboard') }}">{{ __('Go to my dashboard') }}</a>
        @else
        <a class="btn btn-brand btn-lg" href="{{ route('register') }}">{{ __('Apply for membership') }}</a>
        <a class="more" href="{{ route('login') }}">{{ __('Already registered? Log in') }}</a>
        @endif
    </div>
</section>
@endsection
