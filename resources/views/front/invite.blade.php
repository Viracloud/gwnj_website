@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<section class="shell" style="max-width:640px;padding-block:var(--s8)">
    <div class="auth-wrap">
        <span class="kicker">{{ is_rtl() ? 'دعوت به عضویت' : 'Membership invitation' }}</span>
        <h1>{{ is_rtl() ? 'دعوت شما به شبکهٔ جهانی عدالت برای زنان' : 'Your invitation to join GWJN' }}</h1>
        <p class="sub">{{ is_rtl() ? 'شبکهٔ جهانی عدالت برای زنان از شما دعوت کرده تا عضو شوید. برای تکمیل، فرم زیر را پر کنید.' : 'GWJN has invited you to become a member. Complete the form below to accept.' }}</p>
        <div class="form-card">
            <form action="{{ route('invite_submit', $invitation->token) }}" method="post">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ is_rtl() ? 'نام' : 'Name' }}</label>
                    <input type="text" name="name" class="form-control" value="{{ $invitation->name }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ is_rtl() ? 'ایمیل' : 'Email' }}</label>
                    <input type="email" class="form-control" value="{{ $invitation->email }}" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ is_rtl() ? 'رمز عبور' : 'Password' }}</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">{{ is_rtl() ? 'تکرار رمز عبور' : 'Confirm password' }}</label>
                    <input type="password" name="confirm_password" class="form-control" required>
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
                <button type="submit" class="btn btn-brand btn-lg">{{ is_rtl() ? 'پذیرش دعوت' : 'Accept invitation' }}</button>
            </form>
        </div>
    </div>
</section>
@endsection
