@extends('admin.layouts.master')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="page-heading mb-0">
        <h1>Users</h1>
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
                <form action="{{ route('admin_user_store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label class="label-custom">Name *</label>
                            <input type="text" class="form-control-custom" name="name">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="label-custom">Email *</label>
                            <input type="email" class="form-control-custom" name="email">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="label-custom">Phone</label>
                            <input type="text" class="form-control-custom" name="phone">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="label-custom">Address</label>
                            <input type="text" class="form-control-custom" name="address">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="label-custom">Country</label>
                            <input type="text" class="form-control-custom" name="country">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="label-custom">State</label>
                            <input type="text" class="form-control-custom" name="state">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="label-custom">City</label>
                            <input type="text" class="form-control-custom" name="city">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="label-custom">Zip</label>
                            <input type="text" class="form-control-custom" name="zip">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="label-custom">Password *</label>
                            <input type="password" class="form-control-custom" name="password">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="label-custom">Confirm Password *</label>
                            <input type="password" class="form-control-custom" name="confirm_password">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="label-custom">Status</label>
                            <select class="form-control-custom" name="status">
                                <option value="1">Verified</option>
                                <option value="0">Not Verified</option>
                            </select>
                        </div>
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
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->email_verified_at != null)
                    <span class="badge bg-success">Verified</span>
                    @else
                    <span class="badge bg-danger">Not Verified</span>
                    @endif
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
                            <form action="{{ route('admin_user_update', $user->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <label class="label-custom">Name *</label>
                                        <input type="text" class="form-control-custom" name="name" value="{{ $user->name }}">
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="label-custom">Email *</label>
                                        <input type="email" class="form-control-custom" name="email" value="{{ $user->email }}">
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="label-custom">Phone</label>
                                        <input type="text" class="form-control-custom" name="phone" value="{{ $user->phone }}">
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="label-custom">Address</label>
                                        <input type="text" class="form-control-custom" name="address" value="{{ $user->address }}">
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="label-custom">Country</label>
                                        <input type="text" class="form-control-custom" name="country" value="{{ $user->country }}">
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="label-custom">State</label>
                                        <input type="text" class="form-control-custom" name="state" value="{{ $user->state }}">
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="label-custom">City</label>
                                        <input type="text" class="form-control-custom" name="city" value="{{ $user->city }}">
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="label-custom">Zip</label>
                                        <input type="text" class="form-control-custom" name="zip" value="{{ $user->zip }}">
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="label-custom">Password</label>
                                        <input type="password" class="form-control-custom" name="password">
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="label-custom">Confirm Password</label>
                                        <input type="password" class="form-control-custom" name="confirm_password">
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="label-custom">Status</label>
                                        <select class="form-control-custom" name="status">
                                            <option value="1" @if($user->email_verified_at != null) selected @endif>Verified</option>
                                            <option value="0" @if($user->email_verified_at == null) selected @endif>Not Verified</option>
                                        </select>
                                    </div>
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
                            <form action="{{ route('admin_user_delete', $user->id) }}" method="post">
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