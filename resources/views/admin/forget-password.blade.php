@extends('admin.layouts.master')
@section('content')
<!-- Logo -->
<div class="text-center mb-4">
    <h1 class="login-title">Forgot Password</h1>
    <p class="login-subtitle">Enter your email and we'll send you a reset link.</p>
</div>

<!-- Form -->
<form action="{{ route('admin_forget_password_submit') }}" method="post">
    @csrf
    <div class="mb-3">
        <label class="label-custom">Email Address</label>
        <div class="login-input-wrap">
            <i class="fa-regular fa-envelope"></i>
            <input name="email" type="email" class="form-control-custom" placeholder="you@example.com">
        </div>
    </div>
    <button type="submit" class="btn-login w-100">Send Reset Link</button>
</form>

<p class="text-center text-sm text-gray-500 mt-4 mb-0">
    <a href="{{ route('admin_login') }}" class="text-primary-600 text-decoration-none fw-medium"><i class="fa-solid fa-arrow-left me-1"></i>Back to Login</a>
</p>
@endsection