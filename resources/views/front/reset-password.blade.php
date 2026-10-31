@extends('front.layouts.master')

{{-- opens on a light surface, so the header keeps its solid background --}}
@section('body_class', 'no-float')

@section('banner')
@endsection

@section('content')
<section class="shell" style="max-width:640px;padding-block:var(--s8)">
    <div class="auth-wrap">
        <span class="kicker">{{ __('Password reset') }}</span>
        <h1>{{ tf($page_data,'reset_password_page_title') }}</h1>
        <p class="sub">{{ __('Choose a new password for your account.') }}</p>
        <div class="form-card">
            <form action="{{ route('reset_password_submit',[$token,$email]) }}" method="post">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ __('New password') }}</label>
                    <input name="password" type="password" class="form-control" placeholder="{{ __('New password') }}">
                </div>
                <div class="mb-4">
                    <label class="form-label">{{ __('Confirm password') }}</label>
                    <input name="confirm_password" type="password" class="form-control" placeholder="{{ __('Confirm password') }}">
                </div>
                <button type="submit" class="btn btn-brand btn-lg">{{ __('Submit') }}</button>
            </form>
        </div>
    </div>
</section>
@endsection
