@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<section class="shell" style="max-width:640px;padding-block:var(--s8)">
    <div class="auth-wrap">
        <span class="kicker">{{ is_rtl() ? 'بازیابی رمز عبور' : 'Password reset' }}</span>
        <h1>{{ tf($page_data,'reset_password_page_title') }}</h1>
        <p class="sub">{{ is_rtl() ? 'رمز عبور جدید خود را وارد کنید.' : 'Choose a new password for your account.' }}</p>
        <div class="form-card">
            <form action="{{ route('reset_password_submit',[$token,$email]) }}" method="post">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ is_rtl() ? 'رمز عبور جدید' : 'New password' }}</label>
                    <input name="password" type="password" class="form-control" placeholder="{{ is_rtl() ? 'رمز عبور جدید' : 'New password' }}">
                </div>
                <div class="mb-4">
                    <label class="form-label">{{ is_rtl() ? 'تکرار رمز عبور' : 'Confirm password' }}</label>
                    <input name="confirm_password" type="password" class="form-control" placeholder="{{ is_rtl() ? 'تکرار رمز عبور' : 'Confirm password' }}">
                </div>
                <button type="submit" class="btn btn-brand btn-lg">{{ is_rtl() ? 'ارسال' : 'Submit' }}</button>
            </form>
        </div>
    </div>
</section>
@endsection
