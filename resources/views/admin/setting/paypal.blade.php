@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Settings - PayPal</h1>
</div>

<form action="{{ route('admin_setting_paypal_update') }}" method="post">
    @csrf
    <div class="row g-4">
        <div class="col-12 col-xl-12">

            <h6 class="cus-heading">PayPal API Credentials</h6>
            <div class="card-custom-p mb-4">
                <div class="mb-3">
                    <label class="label-custom">Mode</label>
                    <select name="paypal_mode" class="form-control-custom">
                        <option value="sandbox" {{ $setting->paypal_mode == 'sandbox' ? 'selected' : '' }}>Sandbox (testing)</option>
                        <option value="live" {{ $setting->paypal_mode == 'live' ? 'selected' : '' }}>Live (real payments)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="label-custom">Client ID</label>
                    <input type="text" name="paypal_client_id" value="{{ $setting->paypal_client_id }}" class="form-control-custom" autocomplete="off">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Client Secret</label>
                    <input type="text" name="paypal_client_secret" value="{{ $setting->paypal_client_secret }}" class="form-control-custom" autocomplete="off">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Currency</label>
                    <input type="text" name="paypal_currency" value="{{ $setting->paypal_currency ?: 'USD' }}" class="form-control-custom" placeholder="USD">
                    <small class="text-muted">3-letter code supported by PayPal, e.g. USD, EUR, GBP.</small>
                </div>
                <p class="text-muted mb-0 small">
                    Get these from your PayPal Developer dashboard &rarr; Apps &amp; Credentials
                    (<code>developer.paypal.com</code>). Use the Sandbox keys with Sandbox mode, Live keys with Live mode.
                </p>
            </div>

            <div class="card-custom-p mb-4">
                <div>
                    <button type="submit" class="btn-admin-primary">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
