@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Settings - Footer</h1>
</div>

<form action="{{ route('admin_setting_footer_update') }}" method="post">
    @csrf
    <div class="row g-4">
        <div class="col-12 col-xl-12">

            <h6 class="cus-heading">Social Links</h6>
            <div class="card-custom-p mb-4">
                <div class="mb-3">
                    <label class="label-custom">Facebook URL</label>
                    <input type="text" name="footer_facebook" value="{{ $setting->footer_facebook }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Twitter URL</label>
                    <input type="text" name="footer_twitter" value="{{ $setting->footer_twitter }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">LinkedIn URL</label>
                    <input type="text" name="footer_linkedin" value="{{ $setting->footer_linkedin }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Instagram URL</label>
                    <input type="text" name="footer_instagram" value="{{ $setting->footer_instagram }}" class="form-control-custom">
                </div>
            </div>

            <h6 class="cus-heading">Contact Information</h6>
            <div class="card-custom-p mb-4">
                <div class="mb-3">
                    <label class="label-custom">Phone</label>
                    <input type="text" name="footer_phone" value="{{ $setting->footer_phone }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Email</label>
                    <input type="text" name="footer_email" value="{{ $setting->footer_email }}" class="form-control-custom">
                </div>
            </div>

            <h6 class="cus-heading">About Text</h6>
            <div class="card-custom-p mb-4">
                <div class="mb-3">
                    <label class="label-custom">About Text</label>
                    <textarea name="footer_about_text" class="form-control-custom" rows="4">{{ $setting->footer_about_text }}</textarea>
                </div>
                <hr>
                <h6 class="cus-heading">دری (second language)</h6>
                <div class="mb-3">
                    <label class="label-custom">About Text</label>
                    <textarea dir="rtl" name="footer_about_text_sv" class="form-control-custom" rows="4">{{ $setting->footer_about_text_sv }}</textarea>
                </div>
            </div>

            <h6 class="cus-heading">Copyright</h6>
            <div class="card-custom-p mb-4">
                <div class="mb-3">
                    <label class="label-custom">Copyright Text</label>
                    <input type="text" name="footer_copyright" value="{{ $setting->footer_copyright }}" class="form-control-custom">
                </div>
                <hr>
                <h6 class="cus-heading">دری (second language)</h6>
                <div class="mb-3">
                    <label class="label-custom">Copyright Text</label>
                    <input type="text" dir="rtl" name="footer_copyright_sv" value="{{ $setting->footer_copyright_sv }}" class="form-control-custom">
                </div>
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
