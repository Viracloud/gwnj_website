@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Page Privacy Item</h1>
</div>

<form action="{{ route('admin_page_privacy_update') }}" method="post" enctype="multipart/form-data">
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
                    <label class="label-custom">Page Content</label>
                    <textarea id="editor1" name="page_content" class="form-control-custom" rows="10">{{ $page_data->page_content }}</textarea>
                </div>
                <hr>
                <h6 class="cus-heading">دری (second language)</h6>
                <div class="mb-3">
                    <label class="label-custom">Page Title</label>
                    <input type="text" dir="rtl" name="page_title_fa" value="{{ $page_data->page_title_fa }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Page Content</label>
                    <textarea id="privacy_page_content_fa" dir="rtl" name="page_content_fa" class="form-control-custom tinymce-editor" rows="10">{{ $page_data->page_content_fa }}</textarea>
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
                    @include('admin.partials.extra-locales', ['table' => 'page_privacy_items', 'item' => ($page_data ?? null), 'fields' => ['page_title' => 'input', 'page_content' => 'editor']])
                    <button type="submit" class="btn-admin-primary">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
