@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead"><div class="shell">
    <span class="kicker">{{ __('My account') }}</span>
    <h1>{{ tf($page_data,'profile_page_title') }}</h1>
</div></div>

<section class="shell" style="padding-block:var(--s8)">
    <div class="doc">
        <div class="doc-main">
            <div class="form-card">
                <h2 style="margin-top:0">{{ __('Edit profile') }}</h2>
                <form action="{{ route('profile_update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 mb-4">
                            <label class="form-label">{{ __('Photo (used on your membership card)') }} *</label>
                            <div class="d-flex align-items-center gap-3">
                                @if(Auth::guard('web')->user()->photo)
                                <img src="{{ asset('uploads/'.Auth::guard('web')->user()->photo) }}" alt="" style="width:64px;height:64px;border-radius:50%;object-fit:cover;border:1px solid var(--rule)">
                                @endif
                                <input type="file" name="photo" class="form-control" accept="image/*" style="max-width:320px">
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label">{{ __('First name') }} *</label>
                            <input type="text" name="name" class="form-control" value="{{ Auth::guard('web')->user()->name }}">
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label">{{ __('Surname') }} *</label>
                            <input type="text" name="surname" class="form-control" value="{{ Auth::guard('web')->user()->surname }}">
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label">{{ __('Email') }} *</label>
                            <input type="email" name="email" class="form-control" value="{{ Auth::guard('web')->user()->email }}">
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label">{{ __('Phone') }} *</label>
                            <input type="text" name="phone" class="form-control" value="{{ Auth::guard('web')->user()->phone }}">
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label">{{ __('Address') }} *</label>
                            <input type="text" name="address" class="form-control" value="{{ Auth::guard('web')->user()->address }}">
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label">{{ __('Country') }} *</label>
                            <input type="text" name="country" class="form-control" value="{{ Auth::guard('web')->user()->country }}">
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label">{{ __('State') }} *</label>
                            <input type="text" name="state" class="form-control" value="{{ Auth::guard('web')->user()->state }}">
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label">{{ __('City') }} *</label>
                            <input type="text" name="city" class="form-control" value="{{ Auth::guard('web')->user()->city }}">
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label">{{ __('Zip') }} *</label>
                            <input type="text" name="zip" class="form-control" value="{{ Auth::guard('web')->user()->zip }}">
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label">{{ __('New password') }}</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label">{{ __('Confirm password') }}</label>
                            <input type="password" name="confirm_password" class="form-control">
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label">{{ __('Current password') }}</label>
                            <input type="password" name="current_password" class="form-control" autocomplete="current-password">
                            <small class="text-muted">{{ __('Needed only to change your e-mail or password.') }}</small>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-brand btn-lg">{{ __('Save changes') }}</button>
                </form>
            </div>

            <div class="form-card" style="margin-top:var(--s5);border:1px solid color-mix(in srgb,var(--signal) 35%,transparent)">
                <h2 style="margin-top:0;color:var(--signal)">{{ __('Delete account') }}</h2>
                <p class="text-muted">{{ __('Deleting your account permanently removes your profile, photo and membership, including your membership card. This cannot be undone.') }}</p>
                <form action="{{ route('account_delete') }}" method="post" onsubmit="return confirm(@js(__('Are you sure? Your account will be deleted permanently.')))">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">{{ __('Current password') }} *</label>
                            <input type="password" name="delete_password" class="form-control" autocomplete="current-password" required>
                        </div>
                        <div class="col-12 mb-4">
                            <label class="d-flex gap-2 align-items-start" style="cursor:pointer">
                                <input type="checkbox" name="confirm_delete" value="1" required style="margin-top:.3rem">
                                <span>{{ __('I understand that my account will be deleted permanently.') }}</span>
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-lg" style="background:var(--signal);color:#fff">{{ __('Delete my account permanently') }}</button>
                </form>
            </div>
        </div>

        <div>
            @include('user.sidebar')
        </div>
    </div>
</section>
@endsection
