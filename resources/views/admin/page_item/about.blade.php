@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Page About Item</h1>
</div>

<form action="{{ route('admin_page_about_update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-12 col-xl-12">

            <h6 class="cus-heading">About Section</h6>
            <div class="card-custom-p mb-4">
                <div class="mb-3">
                    <label class="label-custom">Status *</label>
                    <select name="about_status" class="form-control-custom">
                        <option value="Show" {{ $page_data->about_status == 'Show' ? 'selected' : '' }}>Show</option>
                        <option value="Hide" {{ $page_data->about_status == 'Hide' ? 'selected' : '' }}>Hide</option>
                    </select>
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
