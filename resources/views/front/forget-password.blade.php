@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<section class="shell" style="max-width:640px;padding-block:var(--s8)">
    <div class="auth-wrap">
        <span class="kicker">{{ is_rtl() ? 'بازیابی رمز عبور' : 'Password reset' }}</span>
        <h1>{{ tf($page_data,'forget_password_page_title') }}</h1>
        <p class="sub">{{ is_rtl() ? 'آدرس ایمیل خود را وارد کنید تا پیوند بازیابی برای شما ارسال شود.' : 'Enter your email address and we will send you a reset link.' }}</p>
        <div class="form-card">
            <form action="{{ route('forget_password_submit') }}" method="post">
                @csrf
                <div class="mb-4">
                    <label class="form-label">{{ is_rtl() ? 'آدرس ایمیل' : 'Email address' }}</label>
                    <input type="text" class="form-control" name="email" placeholder="{{ is_rtl() ? 'آدرس ایمیل' : 'Email address' }}">
                </div>
                <button type="submit" class="btn btn-brand btn-lg">{{ is_rtl() ? 'ارسال' : 'Submit' }}</button>
            </form>
        </div>
        <p class="below"><a href="{{ route('login') }}">{{ is_rtl() ? 'بازگشت به صفحهٔ ورود' : 'Back to login' }}</a></p>
    </div>
</section>
@endsection
