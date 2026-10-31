@extends('front.layouts.master')

{{-- opens on a light surface, so the header keeps its solid background --}}
@section('body_class', 'no-float')

@section('banner')
@endsection

@section('content')
<section class="shell" style="max-width:640px;padding-block:var(--s8)">
    <div class="auth-wrap">
        <span class="kicker">{{ __('Membership invitation') }}</span>
        <h1>{{ __('Your invitation to join GWJN') }}</h1>
        <p class="sub">{{ __('GWJN has invited you to become a member. Complete the form below to accept.') }}</p>
        <div class="form-card">
            <form action="{{ route('invite_submit', $invitation->token) }}" method="post">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ __('Name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ $invitation->name }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Email') }}</label>
                    <input type="email" class="form-control" value="{{ $invitation->email }}" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Password') }}</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">{{ __('Confirm password') }}</label>
                    <input type="password" name="confirm_password" class="form-control" required>
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
                    <label class="form-check-label" for="agree_bylaws">
                        {{ __('I accept the network\'s goals and agree to abide by the') }}
                        @php $bylaws_page = \App\Models\CustomPage::where('slug', 'bylaws')->first(); @endphp
                        @if($bylaws_page && $bylaws_page->isVisible())
                        <a href="{{ route('custom_page', slug_for($bylaws_page)) }}" target="_blank" rel="noopener">{{ __('bylaws') }}</a>{{ __('.') }}
                        @else
                        {{ __('network\'s by-laws.') }}
                        @endif
                    </label>
                </div>
                <button type="submit" class="btn btn-brand btn-lg">{{ __('Accept invitation') }}</button>
            </form>
        </div>
    </div>
</section>
@endsection
