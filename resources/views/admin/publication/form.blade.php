@php
    $pub = $pub ?? null;
    $val = fn($field, $default = '') => old($field, $pub->{$field} ?? $default);
@endphp
<div class="row">
    <div class="col-lg-6 mb-3">
        <label class="label-custom">Slug *</label>
        <input type="text" name="slug" value="{{ $val('slug') }}" class="form-control-custom" placeholder="e.g. annual-report-2026" required>
    </div>
    <div class="col-lg-3 mb-3">
        <label class="label-custom">Category *</label>
        <select name="publication_category_id" class="form-control-custom" required>
            <option value="">Select Category</option>
            @foreach($publication_categories as $cat)
            <option value="{{ $cat->id }}" {{ (string) $val('publication_category_id') === (string) $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        @if($publication_categories->isEmpty())
        <small class="text-danger d-block mt-1">No categories yet — create one first under Publications &gt; Categories.</small>
        @endif
    </div>
    <div class="col-lg-3 mb-3">
        <label class="label-custom">Published Date</label>
        <input type="date" name="published_date" value="{{ $val('published_date') ? \Carbon\Carbon::parse($val('published_date'))->format('Y-m-d') : '' }}" class="form-control-custom">
    </div>

    <h6 class="fw-semibold mt-2 mb-2">English</h6>
    <div class="col-lg-12 mb-3">
        <label class="label-custom">Title *</label>
        <input type="text" name="title" value="{{ $val('title') }}" class="form-control-custom" required>
    </div>
    <div class="col-lg-12 mb-3">
        <label class="label-custom">Description</label>
        <textarea class="form-control-custom" name="description" rows="3">{{ $val('description') }}</textarea>
    </div>

    <h6 class="fw-semibold mt-2 mb-2">دری (second language)</h6>
    <div class="col-lg-12 mb-3">
        <label class="label-custom">Title</label>
        <input type="text" name="title_sv" value="{{ $val('title_sv') }}" class="form-control-custom" dir="rtl">
    </div>
    <div class="col-lg-12 mb-3">
        <label class="label-custom">Description</label>
        <textarea class="form-control-custom" name="description_sv" rows="3" dir="rtl">{{ $val('description_sv') }}</textarea>
    </div>

    <div class="col-lg-6 mb-3">
        <label class="label-custom">Cover image</label>
        <input type="file" name="cover_image" class="form-control-custom">
        @if($pub && $pub->cover_image)
        <small class="text-muted d-block mt-1">Current: {{ $pub->cover_image }}</small>
        @endif
    </div>
    <div class="col-lg-6 mb-3">
        <label class="label-custom">PDF file</label>
        <input type="file" name="file" class="form-control-custom" accept="application/pdf">
        @if($pub && $pub->file)
        <small class="text-muted d-block mt-1">Current: <a href="{{ asset('uploads/'.$pub->file) }}" target="_blank">{{ $pub->file }}</a></small>
        @endif
    </div>

    <div class="col-lg-6 mb-3">
        <label class="label-custom">Status</label>
        <select name="status" class="form-control-custom">
            <option value="Show" {{ $val('status','Show') == 'Show' ? 'selected' : '' }}>Show</option>
            <option value="Hide" {{ $val('status') == 'Hide' ? 'selected' : '' }}>Hide</option>
        </select>
    </div>
</div>
