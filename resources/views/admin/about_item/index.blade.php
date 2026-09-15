@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>About Item</h1>
</div>

<form action="{{ route('admin_about_item_update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-12 col-xl-12">
            <div class="card-custom-p">
                <div class="mb-3">
                    <label class="label-custom">Existing Image</label>
                    <div><img src="{{ asset('uploads/'.$about_item->image) }}" alt="" class="w_200"></div>
                </div>
                <div class="mb-3">
                    <label class="label-custom">Change Image</label>
                    <div><input type="file" name="image" class="form-control-custom"></div>
                </div>
                <h6 class="fw-semibold mt-4 mb-2">English</h6>
                <div class="mb-3">
                    <label class="label-custom">Heading *</label>
                    <textarea name="heading" class="form-control-custom">{{ $about_item->heading }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="label-custom">Text *</label>
                    <textarea name="text" class="form-control-custom h_150">{{ $about_item->text }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="label-custom">Subheading</label>
                    <input type="text" name="subheading" value="{{ $about_item->subheading }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Experience Year</label>
                    <input type="text" name="experience_year" value="{{ $about_item->experience_year }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Experience Text</label>
                    <input type="text" name="experience_text" value="{{ $about_item->experience_text }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Youtube Id</label>
                    <input type="text" name="youtube_id" value="{{ $about_item->youtube_id }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Phone Number</label>
                    <input type="text" name="phone_number" value="{{ $about_item->phone_number }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Phone Text</label>
                    <input type="text" name="phone_text" value="{{ $about_item->phone_text }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Button Text</label>
                    <input type="text" name="button_text" value="{{ $about_item->button_text }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Button Link</label>
                    <input type="text" name="button_link" value="{{ $about_item->button_link }}" class="form-control-custom">
                </div>

                <h6 class="fw-semibold mt-4 mb-2">دری (second language)</h6>
                <div class="mb-3">
                    <label class="label-custom">Heading</label>
                    <textarea name="heading_sv" class="form-control-custom" dir="rtl">{{ $about_item->heading_sv }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="label-custom">Text</label>
                    <textarea name="text_sv" class="form-control-custom h_150" dir="rtl">{{ $about_item->text_sv }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="label-custom">Subheading</label>
                    <input type="text" name="subheading_sv" value="{{ $about_item->subheading_sv }}" class="form-control-custom" dir="rtl">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Experience Text</label>
                    <input type="text" name="experience_text_sv" value="{{ $about_item->experience_text_sv }}" class="form-control-custom" dir="rtl">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Phone Text</label>
                    <input type="text" name="phone_text_sv" value="{{ $about_item->phone_text_sv }}" class="form-control-custom" dir="rtl">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Button Text</label>
                    <input type="text" name="button_text_sv" value="{{ $about_item->button_text_sv }}" class="form-control-custom" dir="rtl">
                </div>

                <div>
                    <button type="submit" class="btn-admin-primary">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection