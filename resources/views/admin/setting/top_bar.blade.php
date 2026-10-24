@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Settings - Top Bar</h1>
</div>

<form action="{{ route('admin_setting_top_bar_update') }}" method="post">
    @csrf
    <div class="row g-4">
        <div class="col-12 col-xl-12">

            <h6 class="cus-heading">Contact Information</h6>
            <div class="card-custom-p mb-4">
                <div class="mb-3">
                    <label class="label-custom">Email</label>
                    <input type="text" name="top_bar_email" value="{{ $setting->top_bar_email }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Phone</label>
                    <input type="text" name="top_bar_phone" value="{{ $setting->top_bar_phone }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Address</label>
                    <input type="text" name="top_bar_address" value="{{ $setting->top_bar_address }}" class="form-control-custom">
                </div>
                <hr>
                <h6 class="cus-heading">دری (second language)</h6>
                <div class="mb-3">
                    <label class="label-custom">Address</label>
                    <input type="text" dir="rtl" name="top_bar_address_fa" value="{{ $setting->top_bar_address_fa }}" class="form-control-custom">
                </div>
            </div>

            <h6 class="cus-heading">Social Links</h6>
            <div class="card-custom-p mb-4">
                <div class="mb-3">
                    <label class="label-custom">Facebook URL</label>
                    <input type="text" name="top_bar_facebook" value="{{ $setting->top_bar_facebook }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Twitter URL</label>
                    <input type="text" name="top_bar_twitter" value="{{ $setting->top_bar_twitter }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">LinkedIn URL</label>
                    <input type="text" name="top_bar_linkedin" value="{{ $setting->top_bar_linkedin }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Instagram URL</label>
                    <input type="text" name="top_bar_instagram" value="{{ $setting->top_bar_instagram }}" class="form-control-custom">
                </div>
            </div>

            <div class="card-custom-p mb-4">
                <div>
                    @include('admin.partials.extra-locales', ['table' => 'settings', 'item' => ($setting ?? null), 'fields' => ['top_bar_address' => 'input']])
                    <button type="submit" class="btn-admin-primary">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
