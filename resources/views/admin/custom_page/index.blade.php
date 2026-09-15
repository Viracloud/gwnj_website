@extends('admin.layouts.master')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="page-heading mb-0">
        <h1>Custom Pages</h1>
    </div>
    <a href="" class="btn-admin-primary" data-bs-toggle="modal" data-bs-target="#modal_add">
        <i class="fa-solid fa-plus me-1"></i>Add Item
    </a>
</div>

<!-- Add Modal -->
<div class="modal fade" id="modal_add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fw-semibold">Add Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-custom">
                <form action="{{ route('admin_custom_page_store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="label-custom">Slug *</label>
                        <input type="text" class="form-control-custom" name="slug">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Featured image</label>
                        <input type="file" class="form-control-custom" name="image">
                    </div>

                    <h6 class="fw-semibold mt-4 mb-2">English</h6>
                    <div class="mb-3">
                        <label class="label-custom">Title *</label>
                        <input type="text" class="form-control-custom" name="title">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Content *</label>
                        <textarea id="editor1" class="form-control-custom" name="content" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">SEO Title</label>
                        <input type="text" class="form-control-custom" name="seo_title">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">SEO Description</label>
                        <textarea class="form-control-custom" name="seo_meta_description" rows="3"></textarea>
                    </div>

                    <h6 class="fw-semibold mt-4 mb-2">دری (second language)</h6>
                    <div class="mb-3">
                        <label class="label-custom">Title</label>
                        <input type="text" class="form-control-custom" name="title_sv" dir="rtl">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Content</label>
                        <textarea id="editor1_sv" class="form-control-custom tinymce-editor" name="content_sv" rows="4" dir="rtl"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">SEO Title</label>
                        <input type="text" class="form-control-custom" name="seo_title_sv" dir="rtl">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">SEO Description</label>
                        <textarea class="form-control-custom" name="seo_meta_description_sv" rows="3" dir="rtl"></textarea>
                    </div>

                    <div class="mb-3">
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
                <th>Title</th>
                <th>Slug</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($custom_pages as $custom_page)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $custom_page->title }}</td>
                <td>{{ $custom_page->slug }}</td>
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
                            <form action="{{ route('admin_custom_page_update', $custom_page->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="label-custom">Slug *</label>
                                    <input type="text" class="form-control-custom" name="slug" value="{{ $custom_page->slug }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Featured image</label>
                                    <input type="file" class="form-control-custom" name="image">
                                    @if($custom_page->image)
                                    <small class="text-muted d-block mt-1">Current: {{ $custom_page->image }}</small>
                                    @endif
                                </div>

                                <h6 class="fw-semibold mt-4 mb-2">English</h6>
                                <div class="mb-3">
                                    <label class="label-custom">Title *</label>
                                    <input type="text" class="form-control-custom" name="title" value="{{ $custom_page->title }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Content *</label>
                                    <textarea id="editor2_{{ $custom_page->id }}" class="form-control-custom tinymce-editor" name="content" rows="4">{{ $custom_page->content }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">SEO Title</label>
                                    <input type="text" class="form-control-custom" name="seo_title" value="{{ $custom_page->seo_title }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">SEO Description</label>
                                    <textarea class="form-control-custom" name="seo_meta_description" rows="3">{{ $custom_page->seo_meta_description }}</textarea>
                                </div>

                                <h6 class="fw-semibold mt-4 mb-2">دری (second language)</h6>
                                <div class="mb-3">
                                    <label class="label-custom">Title</label>
                                    <input type="text" class="form-control-custom" name="title_sv" value="{{ $custom_page->title_sv }}" dir="rtl">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Content</label>
                                    <textarea id="editor2_{{ $custom_page->id }}_sv" class="form-control-custom tinymce-editor" name="content_sv" rows="4" dir="rtl">{{ $custom_page->content_sv }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">SEO Title</label>
                                    <input type="text" class="form-control-custom" name="seo_title_sv" value="{{ $custom_page->seo_title_sv }}" dir="rtl">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">SEO Description</label>
                                    <textarea class="form-control-custom" name="seo_meta_description_sv" rows="3" dir="rtl">{{ $custom_page->seo_meta_description_sv }}</textarea>
                                </div>

                                <div class="mb-3">
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
                            <form action="{{ route('admin_custom_page_delete', $custom_page->id) }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <p>
                                        Are you sure you want to delete this item?
                                    </p>
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
