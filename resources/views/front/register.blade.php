@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<section class="shell" style="max-width:640px;padding-block:var(--s8)">
    <div class="auth-wrap">
        <span class="kicker">{{ is_rtl() ? 'عضویت' : 'Join the network' }}</span>
        <h1>{{ tf($page_data,'register_page_title') }}</h1>
        <p class="sub">{{ is_rtl() ? 'برای ایجاد حساب کاربری فرم زیر را تکمیل کنید.' : 'Create an account to get started.' }}</p>
        <div class="form-card">
            <form action="{{ route('register_submit') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ is_rtl() ? 'نام' : 'Name' }}</label>
                    <input type="text" name="name" class="form-control" placeholder="{{ is_rtl() ? 'نام' : 'Name' }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ is_rtl() ? 'ایمیل' : 'Email' }}</label>
                    <input type="email" name="email" class="form-control" placeholder="{{ is_rtl() ? 'ایمیل' : 'Email' }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ is_rtl() ? 'رمز عبور' : 'Password' }}</label>
                    <input type="password" name="password" class="form-control" placeholder="{{ is_rtl() ? 'رمز عبور' : 'Password' }}" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">{{ is_rtl() ? 'تکرار رمز عبور' : 'Confirm password' }}</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="{{ is_rtl() ? 'تکرار رمز عبور' : 'Confirm password' }}" required>
                </div>
                <button type="submit" class="btn btn-brand btn-lg">{{ is_rtl() ? 'ثبت‌نام' : 'Register' }}</button>
            </form>
        </div>
        <p class="below">{{ is_rtl() ? 'قبلاً حساب کاربری دارید؟' : 'Already have an account?' }} <a href="{{ route('login') }}">{{ is_rtl() ? 'ورود' : 'Login' }}</a></p>
    </div>
</section>
@endsection
