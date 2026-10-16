@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead"><div class="shell">
    <span class="kicker">{{ is_rtl() ? 'حساب کاربری' : 'My account' }}</span>
    <h1>{{ tf($page_data,'dashboard_page_title') }}</h1>
</div></div>

<section class="shell" style="padding-block:var(--s8)">
    <div class="doc">
        <div class="doc-main">
            @php $u = Auth::guard('web')->user(); @endphp
            <div class="form-card" style="margin-bottom:var(--s5)">
                <h2 style="margin-top:0">{{ is_rtl() ? 'وضعیت عضویت' : 'Membership status' }}</h2>

                @if($u->membership_status === 'pending')
                <p class="chip urgent" style="display:inline-block">{{ is_rtl() ? 'در انتظار بررسی' : 'Under review' }}</p>
                <p>{{ is_rtl() ? 'درخواست عضویت شما ثبت شده و در انتظار بررسی مدیر است.' : 'Your membership application has been submitted and is awaiting admin review.' }}</p>

                @elseif($u->membership_status === 'rejected')
                <p class="chip plain" style="display:inline-block">{{ is_rtl() ? 'پذیرفته نشد' : 'Not approved' }}</p>
                @if($u->membership_rejected_reason)
                <p>{{ $u->membership_rejected_reason }}</p>
                @endif

                @elseif($u->isApprovedMember())
                <p class="chip done" style="display:inline-block">{{ is_rtl() ? 'عضو تأییدشده' : 'Approved member' }}</p>
                <p>{{ is_rtl() ? 'شناسهٔ عضویت' : 'Membership ID' }}: <b>{{ $u->membership_id }}</b></p>

                    @if($u->dues_paid)
                    <p class="text-muted" style="margin-bottom:var(--s5)">{{ is_rtl() ? 'حق عضویت پرداخت شده است. سپاسگزاریم!' : 'Membership dues are paid. Thank you!' }}</p>
                    @elseif($u->isDuesExempt())
                    <p class="text-muted" style="margin-bottom:var(--s5)">{{ is_rtl() ? 'اعضای مقیم ایران، افغانستان و پاکستان از پرداخت حق عضویت معاف هستند.' : 'Members based in Iran, Afghanistan or Pakistan are exempt from membership dues.' }}</p>
                    @elseif($global_setting->membership_dues_amount)
                    <p class="text-muted" style="margin-bottom:var(--s3)">{{ is_rtl() ? 'اعضای مقیم خارج از ایران، افغانستان و پاکستان باید سالانه حق عضویت بپردازند. کارت عضویت پس از پرداخت صادر می‌شود.' : 'Members based outside Iran, Afghanistan and Pakistan must pay annual membership dues. Your card is issued once this is paid.' }}</p>
                    <div style="margin-bottom:var(--s5)">
                        <a class="btn btn-out" href="{{ route('membership_dues_pay') }}">
                            {{ is_rtl() ? 'پرداخت حق عضویت' : 'Pay membership dues' }}
                            ({{ $global_setting->paypal_currency ?: 'USD' }} {{ number_format($global_setting->membership_dues_amount, 2) }})
                        </a>
                    </div>
                    @endif

                    @if($u->card_status === 'issued')
                    <a class="btn btn-brand" href="{{ route('membership_card') }}">{{ is_rtl() ? 'مشاهده / چاپ کارت عضویت' : 'View / print my membership card' }}</a>

                    @elseif($u->card_status === 'requested')
                    <p class="text-muted">{{ is_rtl() ? 'درخواست کارت عضویت شما در انتظار تأیید مدیر است.' : 'Your membership card request is awaiting admin approval.' }}</p>

                    @elseif(!$u->profileIsComplete())
                    <p class="text-muted">{{ is_rtl() ? 'برای درخواست کارت عضویت، ابتدا پروفایل خود (عکس، نام، تخلص، تلفن، آدرس، کشور، ایالت، شهر، کد پستی) را تکمیل کنید.' : 'Complete your profile (photo, name, surname, phone, address, country, state, city, zip) before requesting a membership card.' }}</p>
                    <a class="more" href="{{ route('profile') }}">{{ is_rtl() ? 'تکمیل پروفایل' : 'Complete profile' }}</a>

                    @elseif(!$u->canReceiveCard())
                    <p class="text-muted">{{ is_rtl() ? 'برای درخواست کارت عضویت، ابتدا حق عضویت سالانه را پرداخت کنید.' : 'Please pay your annual membership dues before requesting a card.' }}</p>

                    @else
                    <form action="{{ route('membership_card_request') }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-brand">{{ is_rtl() ? 'درخواست کارت عضویت' : 'Request membership card' }}</button>
                    </form>
                    @endif

                @else
                <p class="text-muted">{{ is_rtl() ? 'شما هنوز عضو شبکه نیستید.' : "You're not a network member yet." }}</p>
                <a class="more" href="{{ route('register') }}">{{ is_rtl() ? 'درخواست عضویت' : 'Apply for membership' }}</a>
                @endif
            </div>

            <div class="form-card">
                <h2 style="margin-top:0">{{ is_rtl() ? 'خوش آمدید' : 'Welcome' }}, {{ Auth::guard('web')->user()->name }}!</h2>

                <div class="row single-contact-information align-items-center">
                    <div class="col-lg-4">
                        <div class="profile-info">{{ is_rtl() ? 'تاریخ و ساعت ثبت‌نام' : 'Registration date and time' }}</div>
                    </div>
                    <div class="col-lg-8">
                        <div class="profile-info">
                            {{ Auth::guard('web')->user()->created_at->format('d M Y h:i A') }}
                        </div>
                    </div>
                </div>

                <div class="row single-contact-information align-items-center mt-2">
                    <div class="col-lg-4">
                        <div class="profile-info">{{ is_rtl() ? 'نام' : 'Name' }}</div>
                    </div>
                    <div class="col-lg-8">
                        <div class="profile-info">{{ Auth::guard('web')->user()->name }}</div>
                    </div>
                </div>

                <div class="row single-contact-information align-items-center mt-2">
                    <div class="col-lg-4">
                        <div class="profile-info">{{ is_rtl() ? 'ایمیل' : 'Email' }}</div>
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
