@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Page Login Item</h1>
</div>

<form action="{{ route('admin_page_login_update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-12 col-xl-12">

            <h6 class="cus-heading">Page Section</h6>
            <div class="card-custom-p mb-4">
                <div class="mb-3">
                    <label class="label-custom">Login Page Title</label>
                    <input type="text" name="login_page_title" value="{{ $page_data->login_page_title }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Register Page Title</label>
                    <input type="text" name="register_page_title" value="{{ $page_data->register_page_title }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Forget Password Page Title</label>
                    <input type="text" name="forget_password_page_title" value="{{ $page_data->forget_password_page_title }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Reset Password Page Title</label>
                    <input type="text" name="reset_password_page_title" value="{{ $page_data->reset_password_page_title }}" class="form-control-custom">
                </div>
                <hr>
                <h6 class="cus-heading">دری (second language)</h6>
                <div class="mb-3">
                    <label class="label-custom">Login Page Title</label>
                    <input type="text" dir="rtl" name="login_page_title_sv" value="{{ $page_data->login_page_title_sv }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Register Page Title</label>
                    <input type="text" dir="rtl" name="register_page_title_sv" value="{{ $page_data->register_page_title_sv }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Forget Password Page Title</label>
                    <input type="text" dir="rtl" name="forget_password_page_title_sv" value="{{ $page_data->forget_password_page_title_sv }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Reset Password Page Title</label>
                    <input type="text" dir="rtl" name="reset_password_page_title_sv" value="{{ $page_data->reset_password_page_title_sv }}" class="form-control-custom">
                </div>
            </div>

            <h6 class="cus-heading">SEO Section</h6>
            <div class="card-custom-p mb-4">
                <div class="mb-3">
                    <label class="label-custom">Title</label>
                    <input type="text" name="seo_title" value="{{ $page_data->seo_title }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Meta Description</label>
                    <textarea name="seo_meta_description" class="form-control-custom">{{ $page_data->seo_meta_description }}</textarea>
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
