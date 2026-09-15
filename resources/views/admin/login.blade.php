@extends('admin.layouts.master')
@section('content')
<!-- Logo -->
<div class="text-center mb-4">
    <h1 class="login-title">Admin Login</h1>
    <p class="login-subtitle">Sign in to your account</p>
</div>

<!-- Form -->
<form action="{{ route('admin_login_submit') }}" method="post">
    @csrf
    <div class="mb-3">
        <label class="label-custom">Email Address</label>
        <div class="login-input-wrap">
            <i class="fa-regular fa-envelope"></i>
            <input name="email" type="email" class="form-control-custom" placeholder="you@example.com">
        </div>
    </div>
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <label class="form-label text-sm fw-medium text-gray-700 mb-0">Password</label>
        </div>
        <div class="login-input-wrap">
            <i class="fa-solid fa-lock"></i>
            <input name="password" type="password" class="form-control-custom" placeholder="••••••••">
        </div>
    </div>
    <div class="mb-3">
        <a href="{{ route('admin_forget_password') }}" class="text-sm text-primary-600 text-decoration-none">Forgot password?</a>
    </div>

    <button type="submit" class="btn-login w-100">Sign In</button>
</form>
@endsection