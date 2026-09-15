@extends('admin.layouts.master')
@section('content')
<!-- Logo -->
<div class="text-center mb-4">
    <h1 class="login-title">Reset Password</h1>
    <p class="login-subtitle">Enter your new password below.</p>
</div>

<!-- Form -->
<form action="{{ route('admin_reset_password_submit',[$token,$email]) }}" method="post">
    @csrf
    <div class="mb-3">
        <label class="label-custom">New Password</label>
        <div class="login-input-wrap">
            <i class="fa-solid fa-lock"></i>
            <input type="password" name="password" class="form-control-custom" placeholder="********">
        </div>
    </div>
    <div class="mb-3">
        <label class="label-custom">Confirm Password</label>
        <div class="login-input-wrap">
            <i class="fa-solid fa-lock"></i>
            <input type="password" name="confirm_password" class="form-control-custom" placeholder="********">
        </div>
    </div>
    <button type="submit" class="btn-login w-100">Reset Password</button>
</form>
@endsection