@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Settings - Layout Direction</h1>
</div>
<p class="text-muted mb-4">Controls the <strong>public website</strong> only (English/LTR vs Dari/RTL). This admin panel always stays English/LTR regardless of this setting.</p>

<form action="{{ route('admin_setting_layout_direction_update') }}" method="post">
    @csrf
    <div class="row g-4">
        <div class="col-12 col-xl-12">
            <div class="card-custom-p mb-4">
                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <label class="label-custom">Website Language Direction</label>
                        <select name="layout_direction" class="form-control-custom">
                            <option value="ltr" {{ $setting->layout_direction == 'ltr' ? 'selected' : '' }}>LTR</option>
                            <option value="rtl" {{ $setting->layout_direction == 'rtl' ? 'selected' : '' }}>RTL</option>
                        </select>
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
