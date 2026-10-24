@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Page User Item</h1>
</div>

<form action="{{ route('admin_page_user_update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-12 col-xl-12">

            <h6 class="cus-heading">Page Section</h6>
            <div class="card-custom-p mb-4">
                <div class="mb-3">
                    <label class="label-custom">Dashboard Page Title</label>
                    <input type="text" name="dashboard_page_title" value="{{ $page_data->dashboard_page_title }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Profile Page Title</label>
                    <input type="text" name="profile_page_title" value="{{ $page_data->profile_page_title }}" class="form-control-custom">
                </div>
                <hr>
                <h6 class="cus-heading">دری (second language)</h6>
                <div class="mb-3">
                    <label class="label-custom">Dashboard Page Title</label>
                    <input type="text" dir="rtl" name="dashboard_page_title_fa" value="{{ $page_data->dashboard_page_title_fa }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Profile Page Title</label>
                    <input type="text" dir="rtl" name="profile_page_title_fa" value="{{ $page_data->profile_page_title_fa }}" class="form-control-custom">
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
                    @include('admin.partials.extra-locales', ['table' => 'page_user_items', 'item' => ($page_data ?? null), 'fields' => ['dashboard_page_title' => 'input', 'profile_page_title' => 'input']])
                    <button type="submit" class="btn-admin-primary">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
