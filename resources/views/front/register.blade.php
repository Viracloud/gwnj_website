@extends('front.layouts.master')

{{-- opens on a light surface, so the header keeps its solid background --}}
@section('body_class', 'no-float')

@section('banner')
@endsection

@section('content')
<section class="shell" style="max-width:640px;padding-block:var(--s8)">
    <div class="auth-wrap">
        <span class="kicker">{{ __('Join the network') }}</span>
        <h1>{{ tf($page_data,'register_page_title') }}</h1>
        <p class="sub">{{ __('Create an account to get started.') }}</p>
        <div class="form-card">
            <form action="{{ route('register_submit') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ __('Name') }}</label>
                    <input type="text" name="name" class="form-control" placeholder="{{ __('Name') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Email') }}</label>
                    <input type="email" name="email" class="form-control" placeholder="{{ __('Email') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Password') }}</label>
                    <input type="password" name="password" class="form-control" placeholder="{{ __('Password') }}" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">{{ __('Confirm password') }}</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="{{ __('Confirm password') }}" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">{{ __('Membership type') }}</label>
                    <select name="membership_type" class="form-control" required>
                        <option value="regular">{{ __('Regular member') }}</option>
                        <option value="founding">{{ __('Founding member') }}</option>
                        <option value="honorary">{{ __('Honorary member') }}</option>
                        <option value="affiliate">{{ __('Affiliate member') }}</option>
                        <option value="organizational">{{ __('Organizational member') }}</option>
                    </select>
                </div>
                <div class="mb-4 form-check">
                    <input type="checkbox" name="agree_bylaws" id="agree_bylaws" class="form-check-input" required>
                    @php $bylaws_page = \App\Models\CustomPage::where('slug', 'bylaws')->first(); @endphp
                    <label class="form-check-label" for="agree_bylaws">
                        {{ __('I accept the network\'s goals and agree to abide by the') }}
                        @if($bylaws_page && $bylaws_page->isVisible())
                        <a href="{{ route('custom_page', slug_for($bylaws_page)) }}" target="_blank" rel="noopener">{{ __('bylaws') }}</a>{{ __('.') }}
                        @else
                        {{ __('network\'s by-laws.') }}
                        @endif
                    </label>
                </div>
                <p class="text-muted" style="font-size:var(--t-xs)">{{ __('Registering does not grant membership immediately - your application is reviewed after you verify your email.') }}</p>
                <button type="submit" class="btn btn-brand btn-lg">{{ __('Submit membership application') }}</button>
            </form>
        </div>
        <p class="below">{{ __('Already have an account?') }} <a href="{{ route('login') }}">{{ __('Login') }}</a></p>
    </div>
</section>
@endsection
