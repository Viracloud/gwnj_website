@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Settings - Logo</h1>
</div>

<form action="{{ route('admin_setting_logo_update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-12 col-xl-12">

            <h6 class="cus-heading">Logo 1</h6>
            <div class="card-custom-p mb-4">
                <div class="mb-3">
                    <label class="label-custom">Existing Logo 1</label>
                    <div>
                        @if($setting->logo_1)
                            <img src="{{ asset('uploads/'.$setting->logo_1) }}" alt="Logo 1" style="max-height:80px;">
                        @else
                            <span class="text-muted">No logo uploaded</span>
                        @endif
                    </div>
                </div>
                <div class="mb-3">
                    <label class="label-custom">Upload New Logo 1</label>
                    <input type="file" name="logo_1" class="form-control-custom">
                </div>
            </div>

            <h6 class="cus-heading">Logo 2</h6>
            <div class="card-custom-p mb-4">
                <div class="mb-3">
                    <label class="label-custom">Existing Logo 2</label>
                    <div>
                        @if($setting->logo_2)
                            <img src="{{ asset('uploads/'.$setting->logo_2) }}" alt="Logo 2" style="max-height:80px;">
                        @else
                            <span class="text-muted">No logo uploaded</span>
                        @endif
                    </div>
                </div>
                <div class="mb-3">
                    <label class="label-custom">Upload New Logo 2</label>
                    <input type="file" name="logo_2" class="form-control-custom">
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
