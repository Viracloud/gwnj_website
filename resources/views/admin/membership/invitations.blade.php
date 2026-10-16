@extends('admin.layouts.master')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="page-heading mb-0">
        <h1>Invitations</h1>
    </div>
    <a href="" class="btn-admin-primary" data-bs-toggle="modal" data-bs-target="#modal_add">
        <i class="fa-solid fa-plus me-1"></i>Invite someone
    </a>
</div>
<p class="text-muted mb-4">Use this when the organisation wants to invite a specific person to join, rather than waiting for them to apply. They receive an email with a link to accept and set a password; once accepted, they appear in Membership Applications like any other applicant.</p>

<!-- Add Modal -->
<div class="modal fade" id="modal_add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fw-semibold">Invite someone to join</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-custom">
                <form action="{{ route('admin_membership_invite_store') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label class="label-custom">Name *</label>
                        <input type="text" class="form-control-custom" name="name">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Email *</label>
                        <input type="email" class="form-control-custom" name="email">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Note (internal, optional)</label>
                        <textarea class="form-control-custom" name="note" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn-admin-primary">Send invitation</button>
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
                <th>Sent</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invitations as $inv)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $inv->name }}</td>
                <td>{{ $inv->email }}</td>
                <td>
                    @if($inv->status === 'pending')
                    <span class="badge bg-warning">Pending</span>
                    @elseif($inv->status === 'used')
                    <span class="badge bg-success">Accepted</span>
                    @else
                    <span class="badge bg-secondary">Cancelled</span>
                    @endif
                </td>
                <td>{{ $inv->created_at->format('d M Y') }}</td>
                <td>
                    @if($inv->status === 'pending')
                    <form action="{{ route('admin_membership_invite_cancel', $inv->id) }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
