@extends('admin.layouts.master')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="page-heading mb-0">
        <h1>Gallery of {{ $custom_page->title }}</h1>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin_custom_page_index') }}" class="btn btn-secondary btn-sm">Back to Custom Pages</a>
        <a href="" class="btn-admin-primary" data-bs-toggle="modal" data-bs-target="#modal_add">
            <i class="fa-solid fa-plus me-1"></i>Add Image
        </a>
    </div>
</div>
<p class="text-muted mb-4">Extra supporting photos for this topic page, shown below the main content on the front end. Order controls where each photo appears (smaller number first).</p>

<!-- Add Modal -->
<div class="modal fade" id="modal_add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fw-semibold">Add Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-custom">
                <form action="{{ route('admin_custom_page_gallery_store', $custom_page->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="label-custom">Image *</label>
                        <div><input type="file" name="image" class="form-control-custom" accept="image/*"></div>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Display order <small>- smaller number shows first</small></label>
                        <input type="number" min="0" class="form-control-custom" name="sort_order" value="0">
                    </div>

                    <h6 class="fw-semibold mt-4 mb-2">English</h6>
                    <div class="mb-3">
                        <label class="label-custom">Caption</label>
                        <input type="text" class="form-control-custom" name="caption">
                    </div>

                    <h6 class="fw-semibold mt-4 mb-2">دری (second language)</h6>
                    <div class="mb-3">
                        <label class="label-custom">Caption</label>
                        <input type="text" class="form-control-custom" name="caption_sv" dir="rtl">
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
                <th>Photo</th>
                <th>Caption</th>
                <th>Order</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($images as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <img src="{{ asset('uploads/'.$item->image) }}" alt="{{ $item->caption }}" class="w_100">
                </td>
                <td>{{ $item->caption }}</td>
                <td>{{ $item->sort_order }}</td>
                <td>
                    <div class="d-flex align-items-center gap-1">
                        <a class="btn-icon btn-icon-edit btn-edit-row" data-bs-toggle="modal" data-bs-target="#modal_edit_{{ $loop->iteration }}"><i class="fa-solid fa-pen-to-square text-xs"></i></a>
                        <a class="btn-icon btn-icon-delete" data-bs-toggle="modal" data-bs-target="#modal_delete_{{ $loop->iteration }}"><i class="fa-solid fa-trash text-xs"></i></a>
                    </div>
                </td>
            </tr>
            <!-- Edit Modal -->
            <div class="modal fade" id="modal_edit_{{ $loop->iteration }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content modal-content-custom">
                        <div class="modal-header modal-header-custom">
                            <h5 class="modal-title fw-semibold">Edit Image</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body modal-body-custom">
                            <form action="{{ route('admin_custom_page_gallery_update', $item->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="label-custom">Existing Photo</label>
                                    <div><img src="{{ asset('uploads/'.$item->image) }}" alt="{{ $item->caption }}" class="w_100"></div>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Change Photo</label>
                                    <div><input type="file" name="image" class="form-control-custom" accept="image/*"></div>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Display order</label>
                                    <input type="number" min="0" class="form-control-custom" name="sort_order" value="{{ $item->sort_order }}">
                                </div>

                                <h6 class="fw-semibold mt-4 mb-2">English</h6>
                                <div class="mb-3">
                                    <label class="label-custom">Caption</label>
                                    <input type="text" class="form-control-custom" name="caption" value="{{ $item->caption }}">
                                </div>

                                <h6 class="fw-semibold mt-4 mb-2">دری (second language)</h6>
                                <div class="mb-3">
                                    <label class="label-custom">Caption</label>
                                    <input type="text" class="form-control-custom" name="caption_sv" value="{{ $item->caption_sv }}" dir="rtl">
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
                            <h5 class="modal-title fw-semibold">Delete Image</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body modal-body-custom">
                            <form action="{{ route('admin_custom_page_gallery_delete', $item->id) }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <p>
                                        Are you sure you want to delete this image?
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
