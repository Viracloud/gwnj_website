@extends('admin.layouts.master')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="page-heading mb-0">
        <h1>Sliders</h1>
    </div>
    <a href="" class="btn-admin-primary" data-bs-toggle="modal" data-bs-target="#modal_add">
        <i class="fa-solid fa-plus me-1"></i>Add Item
    </a>
</div>

<!-- Add Modal -->
<div class="modal fade" id="modal_add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fw-semibold">Add Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-custom">
                <form action="{{ route('admin_slider_store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="label-custom">Select Image *</label>
                        <input type="file" class="form-control-custom" name="image">
                    </div>
                    <h6 class="fw-semibold mt-4 mb-2">English</h6>
                    <div class="mb-3">
                        <label class="label-custom">Heading *</label>
                        <input type="text" class="form-control-custom" name="heading">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Text *</label>
                        <textarea class="form-control-custom" name="text" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Button Text</label>
                        <input type="text" class="form-control-custom" name="button_text">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Button Link</label>
                        <input type="text" class="form-control-custom" name="button_link">
                    </div>

                    <h6 class="fw-semibold mt-4 mb-2">دری (second language)</h6>
                    <div class="mb-3">
                        <label class="label-custom">Heading</label>
                        <input type="text" class="form-control-custom" name="heading_sv" dir="rtl">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Text</label>
                        <textarea class="form-control-custom" name="text_sv" rows="4" dir="rtl"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Button Text</label>
                        <input type="text" class="form-control-custom" name="button_text_sv" dir="rtl">
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
                <th>Heading</th>
                <th>Button Text</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sliders as $slider)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <img src="{{ asset('uploads/'.$slider->image) }}" alt="" class="w_200">
                </td>
                <td>{{ $slider->heading }}</td>
                <td>{{ $slider->button_text }}</td>
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
                            <h5 class="modal-title fw-semibold">Edit Item</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body modal-body-custom">
                            <form action="{{ route('admin_slider_update', $slider->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="label-custom">Existing Image</label>
                                    <div>
                                        <img src="{{ asset('uploads/'.$slider->image) }}" alt="" class="w_150">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Change Image</label>
                                    <input type="file" class="form-control-custom" name="image">
                                </div>
                                <h6 class="fw-semibold mt-4 mb-2">English</h6>
                                <div class="mb-3">
                                    <label class="label-custom">Heading *</label>
                                    <input type="text" class="form-control-custom" name="heading" value="{{ $slider->heading }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Text *</label>
                                    <textarea class="form-control-custom" name="text" rows="4">{{ $slider->text }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Button Text</label>
                                    <input type="text" class="form-control-custom" name="button_text" value="{{ $slider->button_text }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Button Link</label>
                                    <input type="text" class="form-control-custom" name="button_link" value="{{ $slider->button_link }}">
                                </div>

                                <h6 class="fw-semibold mt-4 mb-2">دری (second language)</h6>
                                <div class="mb-3">
                                    <label class="label-custom">Heading</label>
                                    <input type="text" class="form-control-custom" name="heading_sv" value="{{ $slider->heading_sv }}" dir="rtl">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Text</label>
                                    <textarea class="form-control-custom" name="text_sv" rows="4" dir="rtl">{{ $slider->text_sv }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Button Text</label>
                                    <input type="text" class="form-control-custom" name="button_text_sv" value="{{ $slider->button_text_sv }}" dir="rtl">
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
                            <form action="{{ route('admin_slider_delete', $slider->id) }}" method="post">
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