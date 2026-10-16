@extends('admin.layouts.master')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="page-heading mb-0">
        <h1>Posts</h1>
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
                <form action="{{ route('admin_post_store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="label-custom">Select Image *</label>
                        <input type="file" class="form-control-custom" name="image">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Post Category *</label>
                        <select name="post_category_id" class="form-control-custom">
                            <option value="">Select Category</option>
                            @foreach($post_categories as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Slug *</label>
                        <input type="text" class="form-control-custom" name="slug">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Slug (Dari) <small>- optional, auto-generated from the Dari title</small></label>
                        <input type="text" class="form-control-custom" name="slug_sv" dir="rtl">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Tags (Comma Separated)</label>
                        <input type="text" class="form-control-custom" name="tags">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Show on Home?</label>
                        <select class="form-control-custom" name="show_on_home">
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>

                    <h6 class="fw-semibold mt-4 mb-2">English</h6>
                    <div class="mb-3">
                        <label class="label-custom">Title *</label>
                        <input type="text" class="form-control-custom" name="title">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Excerpt</label>
                        <textarea class="form-control-custom" name="excerpt" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Description *</label>
                        <textarea id="editor1" class="form-control-custom" name="description" rows="4"></textarea>
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
                        <label class="label-custom">Excerpt</label>
                        <textarea class="form-control-custom" name="excerpt_sv" rows="2" dir="rtl"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Description</label>
                        <textarea id="editor1_sv" class="form-control-custom tinymce-editor" name="description_sv" rows="4" dir="rtl"></textarea>
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
                <th>Image</th>
                <th>Title</th>
                <th>Category</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $post)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <img src="{{ asset('uploads/'.$post->image) }}" alt="" class="w_200">
                </td>
                <td>{{ $post->title }}</td>
                <td>{{ $post->post_category->name }}</td>
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
                            <form action="{{ route('admin_post_update', $post->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="label-custom">Existing Image</label>
                                    <div>
                                        <img src="{{ asset('uploads/'.$post->image) }}" alt="" class="w_150">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Change Image</label>
                                    <input type="file" class="form-control-custom" name="image">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Post Category *</label>
                                    <select name="post_category_id" class="form-control-custom">
                                        <option value="">Select Category</option>
                                        @foreach($post_categories as $item)
                                        <option value="{{ $item->id }}" {{ $post->post_category_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Slug *</label>
                                    <input type="text" class="form-control-custom" name="slug" value="{{ $post->slug }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Slug (Dari) <small>- optional, auto-generated from the Dari title</small></label>
                                    <input type="text" class="form-control-custom" name="slug_sv" dir="rtl" value="{{ $post->slug_sv }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Tags (Comma Separated)</label>
                                    <input type="text" class="form-control-custom" name="tags" value="{{ $post->tags }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Show on Home?</label>
                                    <select class="form-control-custom" name="show_on_home">
                                        <option value="Yes" {{ $post->show_on_home == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ $post->show_on_home == 'No' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>

                                <h6 class="fw-semibold mt-4 mb-2">English</h6>
                                <div class="mb-3">
                                    <label class="label-custom">Title *</label>
                                    <input type="text" class="form-control-custom" name="title" value="{{ $post->title }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Excerpt</label>
                                    <textarea class="form-control-custom" name="excerpt" rows="2">{{ $post->excerpt }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Description *</label>
                                    <textarea id="editor2" class="form-control-custom" name="description" rows="4">{{ $post->description }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">SEO Title</label>
                                    <input type="text" class="form-control-custom" name="seo_title" value="{{ $post->seo_title }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">SEO Description</label>
                                    <textarea class="form-control-custom" name="seo_meta_description" rows="3">{{ $post->seo_meta_description }}</textarea>
                                </div>

                                <h6 class="fw-semibold mt-4 mb-2">دری (second language)</h6>
                                <div class="mb-3">
                                    <label class="label-custom">Title</label>
                                    <input type="text" class="form-control-custom" name="title_sv" value="{{ $post->title_sv }}" dir="rtl">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Excerpt</label>
                                    <textarea class="form-control-custom" name="excerpt_sv" rows="2" dir="rtl">{{ $post->excerpt_sv }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Description</label>
                                    <textarea id="editor2_{{ $post->id }}_sv" class="form-control-custom tinymce-editor" name="description_sv" rows="4" dir="rtl">{{ $post->description_sv }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">SEO Title</label>
                                    <input type="text" class="form-control-custom" name="seo_title_sv" value="{{ $post->seo_title_sv }}" dir="rtl">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">SEO Description</label>
                                    <textarea class="form-control-custom" name="seo_meta_description_sv" rows="3" dir="rtl">{{ $post->seo_meta_description_sv }}</textarea>
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
                            <form action="{{ route('admin_post_delete', $post->id) }}" method="post">
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