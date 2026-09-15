@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Page Home Item</h1>
</div>

<form action="{{ route('admin_page_home_update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-12 col-xl-12">

            <h6 class="cus-heading">About Section</h6>
            <div class="card-custom-p mb-4">
                <div class="mb-3">
                    <label class="label-custom">Status *</label>
                    <select name="about_status" class="form-control-custom">
                        <option value="Show" {{ $page_data->about_status == 'Show' ? 'selected' : '' }}>Show</option>
                        <option value="Hide" {{ $page_data->about_status == 'Hide' ? 'selected' : '' }}>Hide</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <h6 class="cus-heading">Team Section</h6>
                    <div class="card-custom-p mb-4">
                        <div class="mb-3">
                            <label class="label-custom">Subheading</label>
                            <input type="text" name="team_subheading" value="{{ $page_data->team_subheading }}" class="form-control-custom">
                        </div>
                        <div class="mb-3">
                            <label class="label-custom">Heading</label>
                            <textarea name="team_heading" class="form-control-custom">{{ $page_data->team_heading }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="label-custom">Status *</label>
                            <select name="team_status" class="form-control-custom">
                                <option value="Show" {{ $page_data->team_status == 'Show' ? 'selected' : '' }}>Show</option>
                                <option value="Hide" {{ $page_data->team_status == 'Hide' ? 'selected' : '' }}>Hide</option>
                            </select>
                        </div>
                        <hr>
                        <h6 class="cus-heading">دری (second language)</h6>
                        <div class="mb-3">
                            <label class="label-custom">Subheading</label>
                            <input type="text" dir="rtl" name="team_subheading_sv" value="{{ $page_data->team_subheading_sv }}" class="form-control-custom">
                        </div>
                        <div class="mb-3">
                            <label class="label-custom">Heading</label>
                            <textarea dir="rtl" name="team_heading_sv" class="form-control-custom">{{ $page_data->team_heading_sv }}</textarea>
                        </div>
                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-lg-6">
                    <h6 class="cus-heading">Event Section</h6>
                    <div class="card-custom-p mb-4">
                        <div class="mb-3">
                            <label class="label-custom">Subheading</label>
                            <input type="text" name="event_subheading" value="{{ $page_data->event_subheading }}" class="form-control-custom">
                        </div>
                        <div class="mb-3">
                            <label class="label-custom">Heading</label>
                            <textarea name="event_heading" class="form-control-custom">{{ $page_data->event_heading }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="label-custom">Status *</label>
                            <select name="event_status" class="form-control-custom">
                                <option value="Show" {{ $page_data->event_status == 'Show' ? 'selected' : '' }}>Show</option>
                                <option value="Hide" {{ $page_data->event_status == 'Hide' ? 'selected' : '' }}>Hide</option>
                            </select>
                        </div>
                        <hr>
                        <h6 class="cus-heading">دری (second language)</h6>
                        <div class="mb-3">
                            <label class="label-custom">Subheading</label>
                            <input type="text" dir="rtl" name="event_subheading_sv" value="{{ $page_data->event_subheading_sv }}" class="form-control-custom">
                        </div>
                        <div class="mb-3">
                            <label class="label-custom">Heading</label>
                            <textarea dir="rtl" name="event_heading_sv" class="form-control-custom">{{ $page_data->event_heading_sv }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h6 class="cus-heading">Blog Section</h6>
                    <div class="card-custom-p mb-4">
                        <div class="mb-3">
                            <label class="label-custom">Subheading</label>
                            <input type="text" name="blog_subheading" value="{{ $page_data->blog_subheading }}" class="form-control-custom">
                        </div>
                        <div class="mb-3">
                            <label class="label-custom">Heading</label>
                            <textarea name="blog_heading" class="form-control-custom">{{ $page_data->blog_heading }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="label-custom">Status *</label>
                            <select name="blog_status" class="form-control-custom">
                                <option value="Show" {{ $page_data->blog_status == 'Show' ? 'selected' : '' }}>Show</option>
                                <option value="Hide" {{ $page_data->blog_status == 'Hide' ? 'selected' : '' }}>Hide</option>
                            </select>
                        </div>
                        <hr>
                        <h6 class="cus-heading">دری (second language)</h6>
                        <div class="mb-3">
                            <label class="label-custom">Subheading</label>
                            <input type="text" dir="rtl" name="blog_subheading_sv" value="{{ $page_data->blog_subheading_sv }}" class="form-control-custom">
                        </div>
                        <div class="mb-3">
                            <label class="label-custom">Heading</label>
                            <textarea dir="rtl" name="blog_heading_sv" class="form-control-custom">{{ $page_data->blog_heading_sv }}</textarea>
                        </div>
                    </div>
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
                    <button type="submit" class="btn-admin-primary">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection