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
                <div class="mb-4">
                    <label class="form-label">{{ is_rtl() ? 'نوع عضویت' : 'Membership type' }}</label>
                    <select name="membership_type" class="form-control" required>
                        <option value="regular">{{ is_rtl() ? 'عضو عادی' : 'Regular member' }}</option>
                        <option value="founding">{{ is_rtl() ? 'عضو مؤسس' : 'Founding member' }}</option>
                        <option value="honorary">{{ is_rtl() ? 'عضو افتخاری' : 'Honorary member' }}</option>
                        <option value="affiliate">{{ is_rtl() ? 'عضو وابسته' : 'Affiliate member' }}</option>
                        <option value="organizational">{{ is_rtl() ? 'عضو سازمانی' : 'Organizational member' }}</option>
                    </select>
                </div>
                <div class="mb-4 form-check">
                    <input type="checkbox" name="agree_bylaws" id="agree_bylaws" class="form-check-input" required>
                    <label class="form-check-label" for="agree_bylaws">
                        {{ is_rtl() ? 'اهداف شبکه را می‌پذیرم و موافقم که' : 'I accept the network\'s goals and agree to abide by the' }}
                        <a href="{{ url('/page/bylaws') }}" target="_blank" rel="noopener">{{ is_rtl() ? 'اساسنامه' : 'bylaws' }}</a>{{ is_rtl() ? ' را رعایت کنم.' : '.' }}
                    </label>
                </div>
                <p class="text-muted" style="font-size:var(--t-xs)">{{ is_rtl() ? 'ثبت‌نام به معنای عضویت فوری نیست؛ درخواست شما پس از تأیید ایمیل بررسی می‌شود.' : 'Registering does not grant membership immediately - your application is reviewed after you verify your email.' }}</p>
                <button type="submit" class="btn btn-brand btn-lg">{{ is_rtl() ? 'ارسال درخواست عضویت' : 'Submit membership application' }}</button>
            </form>
        </div>
        <p class="below">{{ is_rtl() ? 'قبلاً حساب کاربری دارید؟' : 'Already have an account?' }} <a href="{{ route('login') }}">{{ is_rtl() ? 'ورود' : 'Login' }}</a></p>
    </div>
</section>
@endsection
