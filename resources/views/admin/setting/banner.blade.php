@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Settings - Banner</h1>
</div>

<form action="{{ route('admin_setting_banner_update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-12 col-xl-12">

            <h6 class="cus-heading">Banner</h6>
            <div class="card-custom-p mb-4">
                <div class="mb-3">
                    <label class="label-custom">Existing Banner</label>
                    <div>
                        @if($setting->banner)
                            <img src="{{ asset('uploads/'.$setting->banner) }}" alt="Banner" style="max-height:150px;">
                        @else
                            <span class="text-muted">No banner uploaded</span>
                        @endif
                    </div>
                </div>
                <div class="mb-3">
                    <label class="label-custom">Upload New Banner</label>
                    <input type="file" name="banner" class="form-control-custom">
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
