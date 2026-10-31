@extends('front.layouts.master')

{{-- opens on a light surface, so the header keeps its solid background --}}
@section('body_class', 'no-float')

@section('banner')
@endsection

@section('content')
<section class="shell" style="max-width:640px;padding-block:var(--s8)">
    <div class="auth-wrap">
        <span class="kicker">{{ __('Account access') }}</span>
        <h1>{{ tf($page_data,'login_page_title') }}</h1>
        <p class="sub">{{ __('Sign in to access your account.') }}</p>
        <div class="form-card">
            <form action="{{ route('login_submit') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ __('Email address') }}</label>
                    <input name="email" type="text" class="form-control" placeholder="{{ __('Email address') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Password') }}</label>
                    <input name="password" type="password" class="form-control" placeholder="{{ __('Password') }}">
                </div>
                <div class="d-flex justify-content-end align-items-center mb-4">
                    <a href="{{ route('forget_password') }}" class="text-decoration-none text-muted small">{{ __('Lost your password?') }}</a>
                </div>
                <button type="submit" class="btn btn-brand btn-lg">{{ __('Sign in') }}</button>
            </form>
        </div>
        <p class="below">{{ __("Don't have an account?") }} <a href="{{ route('register') }}">{{ __('Register') }}</a></p>
    </div>
</section>
@endsection
