@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Leadership & Governance Page</h1>
</div>

<form action="{{ route('admin_page_team_update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-12 col-xl-12">

            <h6 class="cus-heading">Page Section</h6>
            <div class="card-custom-p mb-4">
                <div class="mb-3">
                    <label class="label-custom">Page Title</label>
                    <input type="text" name="page_title" value="{{ $page_data->page_title }}" class="form-control-custom">
                </div>
                <hr>
                <h6 class="cus-heading">دری (second language)</h6>
                <div class="mb-3">
                    <label class="label-custom">Page Title</label>
                    <input type="text" dir="rtl" name="page_title_sv" value="{{ $page_data->page_title_sv }}" class="form-control-custom">
                </div>
            </div>

            <h6 class="cus-heading">Organizational structure - explanation</h6>
            <div class="card-custom-p mb-4">
                <p class="text-muted small">Shown at the top of the Leadership & Governance page, above the people. Explain how the organisation is structured (who governs, how decisions are made, how chapters relate to the board). Leave empty to hide it. Use a blank line between paragraphs.</p>
                <div class="mb-3">
                    <label class="label-custom">Explanation (English)</label>
                    <textarea name="intro" rows="7" class="form-control-custom">{{ $page_data->intro }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="label-custom">توضیح ساختار (دری)</label>
                    <textarea dir="rtl" name="intro_sv" rows="7" class="form-control-custom">{{ $page_data->intro_sv }}</textarea>
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
