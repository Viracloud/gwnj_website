@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Settings - Page Cover</h1>
</div>

<form action="{{ route('admin_setting_banner_update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-12 col-xl-12">

            <h6 class="cus-heading">Cover image for all inner pages</h6>
            <div class="card-custom-p mb-4">
                <p class="text-muted">Shown behind the title at the top of every inner page (About, News, Events, Publications, Contact, etc.). A dark shade is added automatically so the title stays readable. Recommended: a wide photo, at least 1600px wide.</p>
                <div class="mb-3">
                    <label class="label-custom">Current cover</label>
                    <div>
                        @if($setting->banner && file_exists(public_path('uploads/'.$setting->banner)))
                            <img src="{{ asset('uploads/'.$setting->banner) }}" alt="Cover" style="max-height:150px;border-radius:8px;">
                            <label class="d-block mt-2"><input type="checkbox" name="remove_banner" value="1"> Remove cover (use the plain light header)</label>
                        @else
                            <span class="text-muted">No cover uploaded - inner pages use the plain light header.</span>
                        @endif
                    </div>
                </div>
                <div class="mb-3">
                    <label class="label-custom">Upload new cover</label>
                    <input type="file" name="banner" class="form-control-custom" accept="image/*">
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
