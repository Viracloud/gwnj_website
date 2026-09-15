@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Settings - Theme Colors</h1>
</div>

<form action="{{ route('admin_setting_theme_color_update') }}" method="post">
    @csrf
    <div class="row g-4">
        <div class="col-12 col-xl-12">
            <div class="card-custom-p mb-4">
                <p class="text-muted small mb-4">These four colors drive the entire site's color scheme (buttons, gradients, headings, accents) — front-end and this admin panel both update automatically when you change them.</p>
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label class="label-custom">Brand Color (Primary)</label>
                        <input type="text" name="theme_color" value="{{ $setting->theme_color }}" class="form-control-custom jscolor">
                        <small class="text-muted">Main brand purple — used for links, buttons, highlights.</small>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="label-custom">Ink Color (Text / Dark)</label>
                        <input type="text" name="theme_ink" value="{{ $setting->theme_ink }}" class="form-control-custom jscolor">
                        <small class="text-muted">Main text color and darkest backgrounds (hero, footer).</small>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="label-custom">Signal Color (Urgent / CTA)</label>
                        <input type="text" name="theme_signal" value="{{ $setting->theme_signal }}" class="form-control-custom jscolor">
                        <small class="text-muted">Used for urgent labels and primary call-to-action buttons.</small>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="label-custom">Hope Color (Success / Achieved)</label>
                        <input type="text" name="theme_hope" value="{{ $setting->theme_hope }}" class="form-control-custom jscolor">
                        <small class="text-muted">Used for "achieved" / positive-outcome labels.</small>
                    </div>
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
