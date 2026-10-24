@extends('admin.layouts.master')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="page-heading mb-0">
        <h1>Campaigns</h1>
    </div>
    <a href="" class="btn-admin-primary" data-bs-toggle="modal" data-bs-target="#modal_add">
        <i class="fa-solid fa-plus me-1"></i>Add Item
    </a>
</div>
<p class="text-muted mb-4">Each campaign is its own page with a status, goal, call-to-action button and (optionally) an impact number. One campaign can be featured on the home page banner at a time.</p>

@php
    $status_options = ['active' => 'Active', 'won' => 'Won', 'closed' => 'Closed'];
@endphp

<!-- Add Modal -->
<div class="modal fade" id="modal_add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fw-semibold">Add Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-custom">
                <form action="{{ route('admin_campaign_store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="label-custom">Photo * <small>- used as the page's featured image and, if featured, as the home page banner background</small></label>
                        <input type="file" class="form-control-custom" name="image" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Status *</label>
                        <select name="status" class="form-control-custom" required>
                            @foreach($status_options as $val => $lbl)
                            <option value="{{ $val }}">{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Category <small>- manage the list under Campaigns → Campaign Categories</small></label>
                        <select name="campaign_category_id" class="form-control-custom">
                            <option value="">— No category —</option>
                            @foreach($campaign_categories as $cc)
                            <option value="{{ $cc->id }}">{{ $cc->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <h6 class="fw-semibold mt-4 mb-2">English</h6>
                    <div class="mb-3">
                        <label class="label-custom">Title *</label>
                        <input type="text" class="form-control-custom" name="title">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Slug *</label>
                        <input type="text" class="form-control-custom" name="slug">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Slug (Dari) <small>- optional, auto-generated from the Dari title</small></label>
                        <input type="text" class="form-control-custom" name="slug_fa" dir="rtl">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Summary <small>- shown on cards and at the top of the page</small></label>
                        <textarea class="form-control-custom" name="excerpt" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Goal / ask <small>- what this campaign is demanding, e.g. "Repeal the movement ban"</small></label>
                        <input type="text" class="form-control-custom" name="goal">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Full description</label>
                        <textarea id="editor1" class="form-control-custom tinymce-editor" name="content" rows="4"></textarea>
                    </div>

                    <h6 class="fw-semibold mt-4 mb-2">دری (second language)</h6>
                    <div class="mb-3">
                        <label class="label-custom">Title</label>
                        <input type="text" class="form-control-custom" name="title_fa" dir="rtl">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Summary</label>
                        <textarea class="form-control-custom" name="excerpt_fa" rows="2" dir="rtl"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Goal / ask</label>
                        <input type="text" class="form-control-custom" name="goal_fa" dir="rtl">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Full description</label>
                        <textarea id="editor1_fa" class="form-control-custom tinymce-editor" name="content_fa" rows="4" dir="rtl"></textarea>
                    </div>

                    <h6 class="fw-semibold mt-4 mb-2">Call to action</h6>
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label class="label-custom">Button text (English)</label>
                            <input type="text" class="form-control-custom" name="cta_text" placeholder="e.g. Sign the petition">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="label-custom">Button text (Dari)</label>
                            <input type="text" class="form-control-custom" name="cta_text_fa" dir="rtl">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Button link <small>- external petition link, or leave empty to link to Get Involved</small></label>
                        <input type="text" class="form-control-custom" name="cta_link" placeholder="https://...">
                    </div>

                    <h6 class="fw-semibold mt-4 mb-2">Impact number (optional)</h6>
                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <label class="label-custom">Number</label>
                            <input type="text" class="form-control-custom" name="stat_number" placeholder="e.g. 14">
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label class="label-custom">Label (English)</label>
                            <input type="text" class="form-control-custom" name="stat_label" placeholder="e.g. countries active">
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label class="label-custom">Label (Dari)</label>
                            <input type="text" class="form-control-custom" name="stat_label_fa" dir="rtl">
                        </div>
                    </div>

                    <h6 class="fw-semibold mt-4 mb-2">Other</h6>
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label class="label-custom">Countries (English) <small>- comma separated</small></label>
                            <input type="text" class="form-control-custom" name="countries">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="label-custom">Countries (Dari)</label>
                            <input type="text" class="form-control-custom" name="countries_fa" dir="rtl">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="label-custom">Started on</label>
                            <input type="date" class="form-control-custom" name="published_date">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="label-custom">Display order <small>- smaller number shows first</small></label>
                            <input type="number" min="0" class="form-control-custom" name="sort_order" value="0">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="label-custom">Feature on home page banner?</label>
                            <select class="form-control-custom" name="show_on_home">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                            <small class="text-muted">Only one campaign should be featured at a time - choosing Yes here does not automatically turn it off for others.</small>
                        </div>
                    </div>

                    <h6 class="fw-semibold mt-4 mb-2">SEO</h6>
                    <div class="mb-3">
                        <label class="label-custom">SEO Title</label>
                        <input type="text" class="form-control-custom" name="seo_title">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">SEO Description</label>
                        <textarea class="form-control-custom" name="seo_meta_description" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        @include('admin.partials.extra-locales', ['table' => 'campaigns', 'item' => null, 'fields' => ['slug' => 'input', 'title' => 'input', 'excerpt' => 'textarea', 'goal' => 'input', 'content' => 'editor', 'cta_text' => 'input', 'stat_label' => 'input', 'countries' => 'input']])
                        <button type="submit" class="btn-admin-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- // Add Modal -->


<div class="card-custom-p5">
    <table id="dataTable" class="table-dt stripe hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Title</th>
                <th>Status</th>
                <th>Featured on home?</th>
                <th>Order</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($campaigns as $campaign)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    @if($campaign->image)
                    <img src="{{ asset('uploads/'.$campaign->image) }}" alt="" class="w_200">
                    @endif
                </td>
                <td>{{ $campaign->title }}</td>
                <td>
                    @php $badge = ['active' => 'bg-success', 'won' => 'bg-primary', 'closed' => 'bg-secondary'][$campaign->status] ?? 'bg-secondary'; @endphp
                    <span class="badge {{ $badge }}">{{ $status_options[$campaign->status] ?? $campaign->status }}</span>
                </td>
                <td>
                    @if($campaign->show_on_home)
                    <span class="badge bg-success">Yes</span>
                    @else
                    <span class="badge bg-danger">No</span>
                    @endif
                </td>
                <td>{{ $campaign->sort_order }}</td>
                <td>
                    <div class="d-flex align-items-center gap-1">
                        <a class="btn-icon btn-icon-edit btn-edit-row" data-bs-toggle="modal" data-bs-target="#modal_edit_{{ $loop->iteration }}"><i class="fa-solid fa-pen-to-square text-xs"></i></a>
                        <a class="btn-icon btn-icon-delete" data-bs-toggle="modal" data-bs-target="#modal_delete_{{ $loop->iteration }}"><i class="fa-solid fa-trash text-xs"></i></a>
                    </div>
                </td>
            </tr>
            <!-- Edit Modal -->
            <div class="modal fade" id="modal_edit_{{ $loop->iteration }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content modal-content-custom">
                        <div class="modal-header modal-header-custom">
                            <h5 class="modal-title fw-semibold">Edit Item</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body modal-body-custom">
                            <form action="{{ route('admin_campaign_update', $campaign->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @if($campaign->image)
                                <div class="mb-3">
                                    <label class="label-custom">Existing Photo</label>
                                    <div><img src="{{ asset('uploads/'.$campaign->image) }}" alt="" class="w_150"></div>
                                </div>
                                @endif
                                <div class="mb-3">
                                    <label class="label-custom">Change Photo</label>
                                    <input type="file" class="form-control-custom" name="image" accept="image/*">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Status *</label>
                                    <select name="status" class="form-control-custom" required>
                                        @foreach($status_options as $val => $lbl)
                                        <option value="{{ $val }}" {{ $campaign->status === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Category</label>
                                    <select name="campaign_category_id" class="form-control-custom">
                                        <option value="">— No category —</option>
                                        @foreach($campaign_categories as $cc)
                                        <option value="{{ $cc->id }}" {{ $campaign->campaign_category_id == $cc->id ? 'selected' : '' }}>{{ $cc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <h6 class="fw-semibold mt-4 mb-2">English</h6>
                                <div class="mb-3">
                                    <label class="label-custom">Title *</label>
                                    <input type="text" class="form-control-custom" name="title" value="{{ $campaign->title }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Slug *</label>
                                    <input type="text" class="form-control-custom" name="slug" value="{{ $campaign->slug }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Slug (Dari) <small>- optional</small></label>
                                    <input type="text" class="form-control-custom" name="slug_fa" dir="rtl" value="{{ $campaign->slug_fa }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Summary</label>
                                    <textarea class="form-control-custom" name="excerpt" rows="2">{{ $campaign->excerpt }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Goal / ask</label>
                                    <input type="text" class="form-control-custom" name="goal" value="{{ $campaign->goal }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Full description</label>
                                    <textarea id="editor2_{{ $campaign->id }}" class="form-control-custom tinymce-editor" name="content" rows="4">{{ $campaign->content }}</textarea>
                                </div>

                                <h6 class="fw-semibold mt-4 mb-2">دری (second language)</h6>
                                <div class="mb-3">
                                    <label class="label-custom">Title</label>
                                    <input type="text" class="form-control-custom" name="title_fa" value="{{ $campaign->title_fa }}" dir="rtl">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Summary</label>
                                    <textarea class="form-control-custom" name="excerpt_fa" rows="2" dir="rtl">{{ $campaign->excerpt_fa }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Goal / ask</label>
                                    <input type="text" class="form-control-custom" name="goal_fa" value="{{ $campaign->goal_fa }}" dir="rtl">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Full description</label>
                                    <textarea id="editor2_{{ $campaign->id }}_fa" class="form-control-custom tinymce-editor" name="content_fa" rows="4" dir="rtl">{{ $campaign->content_fa }}</textarea>
                                </div>

                                <h6 class="fw-semibold mt-4 mb-2">Call to action</h6>
                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <label class="label-custom">Button text (English)</label>
                                        <input type="text" class="form-control-custom" name="cta_text" value="{{ $campaign->cta_text }}">
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="label-custom">Button text (Dari)</label>
                                        <input type="text" class="form-control-custom" name="cta_text_fa" value="{{ $campaign->cta_text_fa }}" dir="rtl">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Button link</label>
                                    <input type="text" class="form-control-custom" name="cta_link" value="{{ $campaign->cta_link }}">
                                </div>

                                <h6 class="fw-semibold mt-4 mb-2">Impact number (optional)</h6>
                                <div class="row">
                                    <div class="col-lg-4 mb-3">
                                        <label class="label-custom">Number</label>
                                        <input type="text" class="form-control-custom" name="stat_number" value="{{ $campaign->stat_number }}">
                                    </div>
                                    <div class="col-lg-4 mb-3">
                                        <label class="label-custom">Label (English)</label>
                                        <input type="text" class="form-control-custom" name="stat_label" value="{{ $campaign->stat_label }}">
                                    </div>
                                    <div class="col-lg-4 mb-3">
                                        <label class="label-custom">Label (Dari)</label>
                                        <input type="text" class="form-control-custom" name="stat_label_fa" value="{{ $campaign->stat_label_fa }}" dir="rtl">
                                    </div>
                                </div>

                                <h6 class="fw-semibold mt-4 mb-2">Other</h6>
                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <label class="label-custom">Countries (English)</label>
                                        <input type="text" class="form-control-custom" name="countries" value="{{ $campaign->countries }}">
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="label-custom">Countries (Dari)</label>
                                        <input type="text" class="form-control-custom" name="countries_fa" value="{{ $campaign->countries_fa }}" dir="rtl">
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="label-custom">Started on</label>
                                        <input type="date" class="form-control-custom" name="published_date" value="{{ optional($campaign->published_date)->format('Y-m-d') }}">
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="label-custom">Display order</label>
                                        <input type="number" min="0" class="form-control-custom" name="sort_order" value="{{ $campaign->sort_order }}">
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="label-custom">Feature on home page banner?</label>
                                        <select class="form-control-custom" name="show_on_home">
                                            <option value="0" {{ !$campaign->show_on_home ? 'selected' : '' }}>No</option>
                                            <option value="1" {{ $campaign->show_on_home ? 'selected' : '' }}>Yes</option>
                                        </select>
                                    </div>
                                </div>

                                <h6 class="fw-semibold mt-4 mb-2">SEO</h6>
                                <div class="mb-3">
                                    <label class="label-custom">SEO Title</label>
                                    <input type="text" class="form-control-custom" name="seo_title" value="{{ $campaign->seo_title }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">SEO Description</label>
                                    <textarea class="form-control-custom" name="seo_meta_description" rows="2">{{ $campaign->seo_meta_description }}</textarea>
                                </div>

                                <div class="mb-3">
                                    @include('admin.partials.extra-locales', ['table' => 'campaigns', 'item' => ($campaign ?? null), 'fields' => ['slug' => 'input', 'title' => 'input', 'excerpt' => 'textarea', 'goal' => 'input', 'cta_text' => 'input', 'stat_label' => 'input', 'countries' => 'input']])
                                    <button type="submit" class="btn-admin-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // Edit Modal -->

            <!-- Delete Modal -->
            <div class="modal fade" id="modal_delete_{{ $loop->iteration }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content modal-content-custom">
                        <div class="modal-header modal-header-custom">
                            <h5 class="modal-title fw-semibold">Delete Item</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body modal-body-custom">
                            <form action="{{ route('admin_campaign_delete', $campaign->id) }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <p>Are you sure you want to delete this item?</p>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn-admin-delete">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // Delete Modal -->
            @endforeach
        </tbody>
    </table>
</div>
@endsection
