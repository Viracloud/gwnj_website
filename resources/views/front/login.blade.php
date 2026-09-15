@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<section class="shell" style="max-width:640px;padding-block:var(--s8)">
    <div class="auth-wrap">
        <span class="kicker">{{ is_rtl() ? 'ورود' : 'Account access' }}</span>
        <h1>{{ tf($page_data,'login_page_title') }}</h1>
        <p class="sub">{{ is_rtl() ? 'برای دسترسی به حساب کاربری خود وارد شوید.' : 'Sign in to access your account.' }}</p>
        <div class="form-card">
            <form action="{{ route('login_submit') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ is_rtl() ? 'آدرس ایمیل' : 'Email address' }}</label>
                    <input name="email" type="text" class="form-control" placeholder="{{ is_rtl() ? 'آدرس ایمیل' : 'Email address' }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ is_rtl() ? 'رمز عبور' : 'Password' }}</label>
                    <input name="password" type="password" class="form-control" placeholder="{{ is_rtl() ? 'رمز عبور' : 'Password' }}">
                </div>
                <div class="d-flex justify-content-end align-items-center mb-4">
                    <a href="{{ route('forget_password') }}" class="text-decoration-none text-muted small">{{ is_rtl() ? 'رمز عبور را فراموش کرده‌اید؟' : 'Lost your password?' }}</a>
                </div>
                <button type="submit" class="btn btn-brand btn-lg">{{ is_rtl() ? 'ورود' : 'Sign in' }}</button>
            </form>
        </div>
        <p class="below">{{ is_rtl() ? 'حساب کاربری ندارید؟' : "Don't have an account?" }} <a href="{{ route('register') }}">{{ is_rtl() ? 'ثبت‌نام' : 'Register' }}</a></p>
    </div>
</section>
@endsection
