@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Settings - Favicon</h1>
</div>

<form action="{{ route('admin_setting_favicon_update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-12 col-xl-12">

            <h6 class="cus-heading">Favicon</h6>
            <div class="card-custom-p mb-4">
                <div class="mb-3">
                    <label class="label-custom">Existing Favicon</label>
                    <div>
                        @if($setting->favicon)
                            <img src="{{ asset('uploads/'.$setting->favicon) }}" alt="Favicon" style="max-height:50px;">
                        @else
                            <span class="text-muted">No favicon uploaded</span>
                        @endif
                    </div>
                </div>
                <div class="mb-3">
                    <label class="label-custom">Upload New Favicon</label>
                    <input type="file" name="favicon" class="form-control-custom">
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
