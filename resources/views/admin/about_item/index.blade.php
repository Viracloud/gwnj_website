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
                    <label class="label-custom">Main Image (first photo of the slideshow)</label>
                    <div><img src="{{ asset('uploads/'.$about_item->image) }}" alt="" class="w_200"></div>
                </div>
                <div class="mb-3">
                    <label class="label-custom">Change Main Image</label>
                    <div><input type="file" name="image" class="form-control-custom" accept="image/jpeg,image/png,image/webp"></div>
                    <small class="text-muted">More photos can be added in the “Slideshow photos” box below.</small>
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
                    <label class="label-custom">Video link <small>- YouTube, Instagram, Facebook or Vimeo link (or a YouTube id). Plays on the site only when clicked.</small></label>
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
                    <textarea name="heading_fa" class="form-control-custom" dir="rtl">{{ $about_item->heading_fa }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="label-custom">Text</label>
                    <textarea name="text_fa" class="form-control-custom h_150" dir="rtl">{{ $about_item->text_fa }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="label-custom">Subheading</label>
                    <input type="text" name="subheading_fa" value="{{ $about_item->subheading_fa }}" class="form-control-custom" dir="rtl">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Experience Text</label>
                    <input type="text" name="experience_text_fa" value="{{ $about_item->experience_text_fa }}" class="form-control-custom" dir="rtl">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Phone Text</label>
                    <input type="text" name="phone_text_fa" value="{{ $about_item->phone_text_fa }}" class="form-control-custom" dir="rtl">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Button Text</label>
                    <input type="text" name="button_text_fa" value="{{ $about_item->button_text_fa }}" class="form-control-custom" dir="rtl">
                </div>

                <div>
                    @include('admin.partials.extra-locales', ['table' => 'about_items', 'item' => ($about_item ?? null), 'fields' => ['heading' => 'textarea', 'text' => 'textarea', 'subheading' => 'input', 'experience_text' => 'input', 'phone_text' => 'input', 'button_text' => 'input']])
                    <button type="submit" class="btn-admin-primary">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Extra photos for the about slideshow (separate forms: forms can't be nested) --}}
<div class="card-custom-p mt-4">
    <h5 class="fw-semibold mb-1">Slideshow photos</h5>
    <p class="text-muted mb-3" style="font-size:.875rem">
        These photos rotate together with the main image in the “Who we are” block on the home and About pages.
        With no extra photos, only the main image is shown. Landscape photos (about 4:3) look best.
    </p>

    <form action="{{ route('admin_about_item_image_store') }}" method="post" enctype="multipart/form-data" class="d-flex flex-wrap gap-2 align-items-center mb-4">
        @csrf
        <input type="file" name="images[]" multiple class="form-control-custom" style="max-width:420px" accept="image/jpeg,image/png,image/webp" required>
        <button type="submit" class="btn-admin-primary">Add photos</button>
    </form>

    @if($slides->count())
    <div class="d-flex flex-wrap gap-3">
        @foreach($slides as $slide)
        <div style="width:200px;border:1px solid var(--gray-200);border-radius:10px;overflow:hidden;background:#fff">
            <img src="{{ asset('uploads/'.$slide->image) }}" alt="" style="width:100%;height:130px;object-fit:cover;display:block">
            <div class="p-2 d-flex gap-2 align-items-center">
                <form action="{{ route('admin_about_item_image_update', $slide->id) }}" method="post" class="d-flex gap-1 align-items-center">
                    @csrf
                    <input type="number" name="sort_order" value="{{ $slide->sort_order }}" min="0" class="form-control-custom" style="width:70px;padding:.3rem .5rem" title="Order">
                    <button type="submit" class="btn btn-sm btn-light" title="Save order"><i class="fa-solid fa-check"></i></button>
                </form>
                <form action="{{ route('admin_about_item_image_delete', $slide->id) }}" method="post" class="ms-auto" onsubmit="return confirm('Remove this photo from the slideshow?')">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove"><i class="fa-solid fa-trash"></i></button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <p class="text-muted mb-0" style="font-size:.875rem">No extra photos yet.</p>
    @endif
</div>
@endsection