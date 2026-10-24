@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead"><div class="shell">
    <span class="kicker">{{ __('My account') }}</span>
    <h1>{{ tf($page_data,'dashboard_page_title') }}</h1>
</div></div>

<section class="shell" style="padding-block:var(--s8)">
    <div class="doc">
        <div class="doc-main">
            @php $u = Auth::guard('web')->user(); @endphp
            <div class="form-card" style="margin-bottom:var(--s5)">
                <h2 style="margin-top:0">{{ __('Membership status') }}</h2>

                @if($u->membership_status === 'pending')
                <p class="chip urgent" style="display:inline-block">{{ __('Under review') }}</p>
                <p>{{ __('Your membership application has been submitted and is awaiting admin review.') }}</p>

                @elseif($u->membership_status === 'rejected')
                <p class="chip plain" style="display:inline-block">{{ __('Not approved') }}</p>
                @if($u->membership_rejected_reason)
                <p>{{ $u->membership_rejected_reason }}</p>
                @endif

                @elseif($u->isApprovedMember())
                <p class="chip done" style="display:inline-block">{{ __('Approved member') }}</p>
                <p>{{ __('Membership ID') }}: <b>{{ $u->membership_id }}</b></p>

                    @if($u->dues_paid)
                    <p class="text-muted" style="margin-bottom:var(--s5)">{{ __('Membership dues are paid. Thank you!') }}</p>
                    @elseif($u->isDuesExempt())
                    <p class="text-muted" style="margin-bottom:var(--s5)">{{ __('Members based in Iran, Afghanistan or Pakistan are exempt from membership dues.') }}</p>
                    @elseif($global_setting->membership_dues_amount)
                    <p class="text-muted" style="margin-bottom:var(--s3)">{{ __('Members based outside Iran, Afghanistan and Pakistan must pay annual membership dues. Your card is issued once this is paid.') }}</p>
                    <div style="margin-bottom:var(--s5)">
                        <a class="btn btn-out" href="{{ route('membership_dues_pay') }}">
                            {{ __('Pay membership dues') }}
                            ({{ $global_setting->paypal_currency ?: 'USD' }} {{ number_format($global_setting->membership_dues_amount, 2) }})
                        </a>
                    </div>
                    @endif

                    @if($u->cardIsExpired())
                    <p class="text-muted" style="margin-bottom:var(--s3)">
                        {{ __('Your membership card expired on :date. Cards are valid for one year and can be renewed.', ['date' => fdate($u->card_expires_at, 'j F Y')]) }}
                    </p>
                    @if(!$u->duesPaidForRenewal())
                    <a class="btn btn-out" href="{{ route('membership_dues_pay') }}">
                        {{ __("Pay this year's membership dues") }}
                        ({{ $global_setting->paypal_currency ?: 'USD' }} {{ number_format($global_setting->membership_dues_amount, 2) }})
                    </a>
                    @else
                    <form action="{{ route('membership_card_request') }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-brand">{{ __('Request card renewal') }}</button>
                    </form>
                    @endif

                    @elseif($u->card_status === 'issued')
                    <a class="btn btn-brand" href="{{ route('membership_card') }}">{{ __('View / print my membership card') }}</a>
                    @if($u->card_expires_at)
                    <p class="text-muted" style="margin-top:var(--s2);font-size:var(--t-sm)">{{ __('Valid until :date', ['date' => fdate($u->card_expires_at, 'j F Y')]) }}</p>
                    @endif

                    @elseif($u->card_status === 'requested')
                    <p class="text-muted">{{ __('Your membership card request is awaiting admin approval.') }}</p>

                    @elseif(!$u->profileIsComplete())
                    <p class="text-muted">{{ __('Complete your profile (photo, name, surname, phone, address, country, state, city, zip) before requesting a membership card.') }}</p>
                    <a class="more" href="{{ route('profile') }}">{{ __('Complete profile') }}</a>

                    @elseif(!$u->canReceiveCard())
                    <p class="text-muted">{{ __('Please pay your annual membership dues before requesting a card.') }}</p>

                    @else
                    <form action="{{ route('membership_card_request') }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-brand">{{ __('Request membership card') }}</button>
                    </form>
                    @endif

                @else
                <p class="text-muted">{{ __("You're not a network member yet.") }}</p>
                <a class="more" href="{{ route('register') }}">{{ __('Apply for membership') }}</a>
                @endif
            </div>

            <div class="form-card">
                <h2 style="margin-top:0">{{ __('Welcome') }}, {{ Auth::guard('web')->user()->name }}!</h2>

                <div class="row single-contact-information align-items-center">
                    <div class="col-lg-4">
                        <div class="profile-info">{{ __('Registration date and time') }}</div>
                    </div>
                    <div class="col-lg-8">
                        <div class="profile-info">
                            {{ Auth::guard('web')->user()->created_at->format('d M Y h:i A') }}
                        </div>
                    </div>
                </div>

                <div class="row single-contact-information align-items-center mt-2">
                    <div class="col-lg-4">
                        <div class="profile-info">{{ __('Name') }}</div>
                    </div>
                    <div class="col-lg-8">
                        <div class="profile-info">{{ Auth::guard('web')->user()->name }}</div>
                    </div>
                </div>

                <div class="row single-contact-information align-items-center mt-2">
                    <div class="col-lg-4">
                        <div class="profile-info">{{ __('Email') }}</div>
                    </div>
                    <div class="col-lg-8">
                        <div class="profile-info" style="color:var(--brand)">{{ Auth::guard('web')->user()->email }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            @include('user.sidebar')
        </div>
    </div>
</section>
@endsection
