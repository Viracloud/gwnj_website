@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead"><div class="shell">
    <span class="kicker">{{ is_rtl() ? 'حساب کاربری' : 'My account' }}</span>
    <h1>{{ tf($page_data,'profile_page_title') }}</h1>
</div></div>

<section class="shell" style="padding-block:var(--s8)">
    <div class="doc">
        <div class="doc-main">
            <div class="form-card">
                <h2 style="margin-top:0">{{ is_rtl() ? 'ویرایش پروفایل' : 'Edit profile' }}</h2>
                <form action="{{ route('profile_update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 mb-4">
                            <label class="form-label">{{ is_rtl() ? 'عکس (برای کارت عضویت)' : 'Photo (used on your membership card)' }} *</label>
                            <div class="d-flex align-items-center gap-3">
                                @if(Auth::guard('web')->user()->photo)
                                <img src="{{ asset('uploads/'.Auth::guard('web')->user()->photo) }}" alt="" style="width:64px;height:64px;border-radius:50%;object-fit:cover;border:1px solid var(--rule)">
                                @endif
                                <input type="file" name="photo" class="form-control" accept="image/*" style="max-width:320px">
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label">{{ is_rtl() ? 'نام' : 'First name' }} *</label>
                            <input type="text" name="name" class="form-control" value="{{ Auth::guard('web')->user()->name }}">
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label">{{ is_rtl() ? 'تخلص' : 'Surname' }} *</label>
                            <input type="text" name="surname" class="form-control" value="{{ Auth::guard('web')->user()->surname }}">
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label">{{ is_rtl() ? 'ایمیل' : 'Email' }} *</label>
                            <input type="email" name="email" class="form-control" value="{{ Auth::guard('web')->user()->email }}">
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label">{{ is_rtl() ? 'تلفن' : 'Phone' }} *</label>
                            <input type="text" name="phone" class="form-control" value="{{ Auth::guard('web')->user()->phone }}">
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label">{{ is_rtl() ? 'آدرس' : 'Address' }} *</label>
                            <input type="text" name="address" class="form-control" value="{{ Auth::guard('web')->user()->address }}">
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label">{{ is_rtl() ? 'کشور' : 'Country' }} *</label>
                            <input type="text" name="country" class="form-control" value="{{ Auth::guard('web')->user()->country }}">
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label">{{ is_rtl() ? 'ایالت/استان' : 'State' }} *</label>
                            <input type="text" name="state" class="form-control" value="{{ Auth::guard('web')->user()->state }}">
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label">{{ is_rtl() ? 'شهر' : 'City' }} *</label>
                            <input type="text" name="city" class="form-control" value="{{ Auth::guard('web')->user()->city }}">
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label">{{ is_rtl() ? 'کد پستی' : 'Zip' }} *</label>
                            <input type="text" name="zip" class="form-control" value="{{ Auth::guard('web')->user()->zip }}">
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label">{{ is_rtl() ? 'رمز عبور جدید' : 'New password' }}</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label">{{ is_rtl() ? 'تکرار رمز عبور' : 'Confirm password' }}</label>
                            <input type="password" name="confirm_password" class="form-control">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-brand btn-lg">{{ is_rtl() ? 'ذخیرهٔ تغییرات' : 'Save changes' }}</button>
                </form>
            </div>
        </div>

        <div>
            @include('user.sidebar')
        </div>
    </div>
</section>
@endsection
