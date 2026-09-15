@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Page Contact Item</h1>
</div>

<form action="{{ route('admin_page_contact_update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-12 col-xl-12">

            <h6 class="cus-heading">Page Section</h6>
            <div class="card-custom-p mb-4">
                <div class="mb-3">
                    <label class="label-custom">Page Title</label>
                    <input type="text" name="page_title" value="{{ $page_data->page_title }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Phone Number</label>
                    <textarea name="phone_number" class="form-control-custom" rows="3">{{ $page_data->phone_number }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="label-custom">Email Address</label>
                    <textarea name="email_address" class="form-control-custom" rows="3">{{ $page_data->email_address }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="label-custom">Location</label>
                    <textarea name="location" class="form-control-custom" rows="3">{{ $page_data->location }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="label-custom">Map Iframe Code</label>
                    <textarea name="map_iframe_code" class="form-control-custom" rows="3">{{ $page_data->map_iframe_code }}</textarea>
                </div>
                <hr>
                <h6 class="cus-heading">دری (second language)</h6>
                <div class="mb-3">
                    <label class="label-custom">Page Title</label>
                    <input type="text" dir="rtl" name="page_title_sv" value="{{ $page_data->page_title_sv }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Location</label>
                    <textarea dir="rtl" name="location_sv" class="form-control-custom" rows="3">{{ $page_data->location_sv }}</textarea>
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
