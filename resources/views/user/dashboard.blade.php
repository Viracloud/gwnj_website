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
