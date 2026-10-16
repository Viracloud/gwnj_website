@extends('admin.layouts.master')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="page-heading mb-0">
        <h1>Team Members</h1>
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
                <form action="{{ route('admin_member_store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="label-custom">Select Image *</label>
                        <input type="file" class="form-control-custom" name="image">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Group * <small>- e.g. President, Board of Directors, Executive Team (create groups first under Team → Groups)</small></label>
                        <select class="form-control-custom" name="member_category_id">
                            <option value="">Select a group</option>
                            @foreach($member_categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Slug *</label>
                        <input type="text" class="form-control-custom" name="slug">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Slug (Dari) <small>- optional, auto-generated from the Dari name</small></label>
                        <input type="text" class="form-control-custom" name="slug_sv" dir="rtl">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Display order inside the group <small>- smaller number shows first</small></label>
                        <input type="number" min="0" class="form-control-custom" name="sort_order" value="0">
                    </div>

                    <h6 class="fw-semibold mt-4 mb-2">English</h6>
                    <div class="mb-3">
                        <label class="label-custom">Name *</label>
                        <input type="text" class="form-control-custom" name="name">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Designation *</label>
                        <input type="text" class="form-control-custom" name="designation">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Short Description *</label>
                        <textarea class="form-control-custom" name="short_description" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Full biography <small>- optional; the short description is enough for the leadership page</small></label>
                        <textarea id="editor1" class="form-control-custom tinymce-editor" name="description" rows="4"></textarea>
                    </div>

                    <h6 class="fw-semibold mt-4 mb-2">دری (second language)</h6>
                    <div class="mb-3">
                        <label class="label-custom">Name</label>
                        <input type="text" class="form-control-custom" name="name_sv" dir="rtl">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Designation</label>
                        <input type="text" class="form-control-custom" name="designation_sv" dir="rtl">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Short Description</label>
                        <textarea class="form-control-custom" name="short_description_sv" rows="4" dir="rtl"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Description</label>
                        <textarea id="editor1_sv" class="form-control-custom tinymce-editor" name="description_sv" rows="4" dir="rtl"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="label-custom">Show on Home?</label>
                        <select class="form-control-custom" name="show_on_home">
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Show phone and email publicly?</label>
                        <select class="form-control-custom" name="show_contact">
                            <option value="0">No (recommended)</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Phone</label>
                        <input type="text" class="form-control-custom" name="phone">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Email</label>
                        <input type="text" class="form-control-custom" name="email">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Experience</label>
                        <input type="text" class="form-control-custom" name="experience">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Facebook</label>
                        <input type="text" class="form-control-custom" name="facebook">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Twitter</label>
                        <input type="text" class="form-control-custom" name="twitter">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">LinkedIn</label>
                        <input type="text" class="form-control-custom" name="linkedin">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Instagram</label>
                        <input type="text" class="form-control-custom" name="instagram">
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
                <th>Name</th>
                <th>Group</th>
                <th>Order</th>
                <th>Show on Home?</th>
                <th>Award</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $member)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <img src="{{ asset('uploads/'.$member->image) }}" alt="" class="w_100">
                </td>
                <td>{{ $member->name }}</td>
                <td>{{ optional($member->category)->name }}</td>
                <td>{{ $member->sort_order }}</td>
                <td>
                    @if($member->show_on_home == 'Yes')
                    <span class="badge bg-success">Yes</span>
                    @else
                    <span class="badge bg-danger">No</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin_member_awards', $member->id) }}" class="btn btn-info btn-sm">Manage Award</a>
                </td>
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
                            <form action="{{ route('admin_member_update', $member->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="label-custom">Existing Image</label>
                                    <div>
                                        <img src="{{ asset('uploads/'.$member->image) }}" alt="" class="w_150">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Change Image</label>
                                    <input type="file" class="form-control-custom" name="image">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Slug *</label>
                                    <input type="text" class="form-control-custom" name="slug" value="{{ $member->slug }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Group *</label>
                                    <select class="form-control-custom" name="member_category_id">
                                        <option value="">Select a group</option>
                                        @foreach($member_categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $member->member_category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Slug (Dari) <small>- optional</small></label>
                                    <input type="text" class="form-control-custom" name="slug_sv" dir="rtl" value="{{ $member->slug_sv }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Display order inside the group <small>- smaller number shows first</small></label>
                                    <input type="number" min="0" class="form-control-custom" name="sort_order" value="{{ $member->sort_order }}">
                                </div>

                                <h6 class="fw-semibold mt-4 mb-2">English</h6>
                                <div class="mb-3">
                                    <label class="label-custom">Name *</label>
                                    <input type="text" class="form-control-custom" name="name" value="{{ $member->name }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Designation *</label>
                                    <input type="text" class="form-control-custom" name="designation" value="{{ $member->designation }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Short Description *</label>
                                    <textarea class="form-control-custom" name="short_description" rows="4">{{ $member->short_description }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Full biography <small>- optional; the short description is enough for the leadership page</small></label>
                                    <textarea id="editor1_{{ $member->id }}" class="form-control-custom tinymce-editor" name="description" rows="4">{{ $member->description }}</textarea>
                                </div>

                                <h6 class="fw-semibold mt-4 mb-2">دری (second language)</h6>
                                <div class="mb-3">
                                    <label class="label-custom">Name</label>
                                    <input type="text" class="form-control-custom" name="name_sv" value="{{ $member->name_sv }}" dir="rtl">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Designation</label>
                                    <input type="text" class="form-control-custom" name="designation_sv" value="{{ $member->designation_sv }}" dir="rtl">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Short Description</label>
                                    <textarea class="form-control-custom" name="short_description_sv" rows="4" dir="rtl">{{ $member->short_description_sv }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Description</label>
                                    <textarea id="editor1_{{ $member->id }}_sv" class="form-control-custom tinymce-editor" name="description_sv" rows="4" dir="rtl">{{ $member->description_sv }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="label-custom">Show on Home?</label>
                                    <select class="form-control-custom" name="show_on_home">
                                        <option value="Yes" {{ $member->show_on_home == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ $member->show_on_home == 'No' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Show phone and email publicly?</label>
                                    <select class="form-control-custom" name="show_contact">
                                        <option value="0" {{ !$member->show_contact ? 'selected' : '' }}>No (recommended)</option>
                                        <option value="1" {{ $member->show_contact ? 'selected' : '' }}>Yes</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Phone</label>
                                    <input type="text" class="form-control-custom" name="phone" value="{{ $member->phone }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Email</label>
                                    <input type="text" class="form-control-custom" name="email" value="{{ $member->email }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Experience</label>
                                    <input type="text" class="form-control-custom" name="experience" value="{{ $member->experience }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Facebook</label>
                                    <input type="text" class="form-control-custom" name="facebook" value="{{ $member->facebook }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Twitter</label>
                                    <input type="text" class="form-control-custom" name="twitter" value="{{ $member->twitter }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">LinkedIn</label>
                                    <input type="text" class="form-control-custom" name="linkedin" value="{{ $member->linkedin }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Instagram</label>
                                    <input type="text" class="form-control-custom" name="instagram" value="{{ $member->instagram }}">
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
                            <form action="{{ route('admin_member_delete', $member->id) }}" method="post">
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