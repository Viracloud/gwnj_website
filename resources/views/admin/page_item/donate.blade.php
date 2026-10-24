@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Page Donate Item</h1>
</div>

<form action="{{ route('admin_page_donate_update') }}" method="post" enctype="multipart/form-data">
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
                    <label class="label-custom">Heading</label>
                    <input type="text" name="heading" value="{{ $page_data->heading }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Description</label>
                    <textarea name="description" class="form-control-custom" rows="5">{{ $page_data->description }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="label-custom">Preset Amounts</label>
                    <input type="text" name="preset_amounts" value="{{ $page_data->preset_amounts }}" class="form-control-custom" placeholder="10,25,50,100,250">
                    <small class="text-muted">Comma-separated list of quick-select amounts shown on the donate form.</small>
                </div>
                <div class="mb-3">
                    <label class="label-custom">Goal Amount (optional)</label>
                    <input type="number" step="0.01" name="goal_amount" value="{{ $page_data->goal_amount }}" class="form-control-custom" placeholder="Leave empty to hide the progress bar">
                </div>
                <hr>
                <h6 class="cus-heading">دری (second language)</h6>
                <div class="mb-3">
                    <label class="label-custom">Page Title</label>
                    <input type="text" dir="rtl" name="page_title_fa" value="{{ $page_data->page_title_fa }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Heading</label>
                    <input type="text" dir="rtl" name="heading_fa" value="{{ $page_data->heading_fa }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Description</label>
                    <textarea dir="rtl" name="description_fa" class="form-control-custom" rows="5">{{ $page_data->description_fa }}</textarea>
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
                    @include('admin.partials.extra-locales', ['table' => 'page_donate_items', 'item' => ($page_data ?? null), 'fields' => ['page_title' => 'input', 'heading' => 'input', 'description' => 'textarea']])
                    <button type="submit" class="btn-admin-primary">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
