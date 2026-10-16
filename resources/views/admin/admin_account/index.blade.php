@extends('admin.layouts.master')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="page-heading mb-0">
        <h1>Admin Accounts</h1>
    </div>
    <a href="" class="btn-admin-primary" data-bs-toggle="modal" data-bs-target="#modal_add">
        <i class="fa-solid fa-plus me-1"></i>Add Admin
    </a>
</div>
<p class="text-muted mb-4">Control who can log into the admin panel and what they're allowed to do. <b>Super Admin</b> has full access. <b>Author</b> can manage content (posts, events, campaigns, pages, gallery, publications, team) but not settings, other admin accounts, membership approvals or payments. <b>Viewer</b> can only view - no create/edit/delete.</p>

@php $role_badges = ['super_admin'=>'bg-danger','author'=>'bg-primary','viewer'=>'bg-secondary']; @endphp

<!-- Add Modal -->
<div class="modal fade" id="modal_add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fw-semibold">Add Admin Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-custom">
                <form action="{{ route('admin_account_store') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label class="label-custom">Name *</label>
                        <input type="text" class="form-control-custom" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Email *</label>
                        <input type="email" class="form-control-custom" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Password *</label>
                        <input type="password" class="form-control-custom" name="password" minlength="8" required>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Role *</label>
                        <select name="role" class="form-control-custom" required>
                            <option value="author">Author</option>
                            <option value="viewer">Viewer</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
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
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($admins as $admin)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $admin->name }}@if($admin->id === auth('admin')->id()) <span class="badge bg-info">You</span>@endif</td>
                <td>{{ $admin->email }}</td>
                <td><span class="badge {{ $role_badges[$admin->role] ?? 'bg-secondary' }}">{{ $admin->roleLabel() }}</span></td>
                <td>
                    <div class="d-flex align-items-center gap-1">
                        <a class="btn-icon btn-icon-edit btn-edit-row" data-bs-toggle="modal" data-bs-target="#modal_edit_{{ $loop->iteration }}"><i class="fa-solid fa-pen-to-square text-xs"></i></a>
                        @if($admin->id !== auth('admin')->id())
                        <a class="btn-icon btn-icon-delete" data-bs-toggle="modal" data-bs-target="#modal_delete_{{ $loop->iteration }}"><i class="fa-solid fa-trash text-xs"></i></a>
                        @endif
                    </div>
                </td>
            </tr>
            <!-- Edit Modal -->
            <div class="modal fade" id="modal_edit_{{ $loop->iteration }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content modal-content-custom">
                        <div class="modal-header modal-header-custom">
                            <h5 class="modal-title fw-semibold">Edit Admin Account</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body modal-body-custom">
                            <form action="{{ route('admin_account_update', $admin->id) }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label class="label-custom">Name *</label>
                                    <input type="text" class="form-control-custom" name="name" value="{{ $admin->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Email *</label>
                                    <input type="email" class="form-control-custom" name="email" value="{{ $admin->email }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Password <small>- leave blank to keep the current password</small></label>
                                    <input type="password" class="form-control-custom" name="password" minlength="8">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Role *</label>
                                    <select name="role" class="form-control-custom" required {{ $admin->id === auth('admin')->id() ? 'disabled' : '' }}>
                                        <option value="author" {{ $admin->role=='author'?'selected':'' }}>Author</option>
                                        <option value="viewer" {{ $admin->role=='viewer'?'selected':'' }}>Viewer</option>
                                        <option value="super_admin" {{ $admin->role=='super_admin'?'selected':'' }}>Super Admin</option>
                                    </select>
                                    @if($admin->id === auth('admin')->id())
                                    <input type="hidden" name="role" value="{{ $admin->role }}">
                                    <small class="text-muted">You can't change your own role.</small>
                                    @endif
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

            @if($admin->id !== auth('admin')->id())
            <!-- Delete Modal -->
            <div class="modal fade" id="modal_delete_{{ $loop->iteration }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content modal-content-custom">
                        <div class="modal-header modal-header-custom">
                            <h5 class="modal-title fw-semibold">Delete Admin Account</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body modal-body-custom">
                            <form action="{{ route('admin_account_delete', $admin->id) }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <p>Are you sure you want to delete <b>{{ $admin->name }}</b>'s admin account?</p>
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
            @endif
            @endforeach
        </tbody>
    </table>
</div>
@endsection
