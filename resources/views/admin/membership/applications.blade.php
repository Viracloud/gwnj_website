@extends('admin.layouts.master')
@section('content')
<div class="page-heading mb-4">
    <h1>Membership Applications</h1>
</div>
<p class="text-muted mb-4">People who applied themselves (via the Register form) or accepted an invitation appear here, awaiting your decision. Approving generates their unique membership ID.</p>

@php
    $type_labels = ['regular'=>'Regular','founding'=>'Founding','honorary'=>'Honorary','affiliate'=>'Affiliate','organizational'=>'Organizational'];
@endphp

<div class="card-custom-p5">
    <table id="dataTable" class="table-dt stripe hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Requested type</th>
                <th>Source</th>
                <th>Applied</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($applications as $app)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $app->name }}</td>
                <td>{{ $app->email }}</td>
                <td>{{ $type_labels[$app->membership_type] ?? $app->membership_type }}</td>
                <td>{{ $app->membership_source === 'invited' ? 'Invited' : 'Self-applied' }}</td>
                <td>{{ optional($app->membership_applied_at)->format('d M Y') }}</td>
                <td>
                    <div class="d-flex align-items-center gap-1">
                        <a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal_approve_{{ $loop->iteration }}">Approve</a>
                        <a class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal_reject_{{ $loop->iteration }}">Reject</a>
                    </div>
                </td>
            </tr>
            <!-- Approve Modal -->
            <div class="modal fade" id="modal_approve_{{ $loop->iteration }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content modal-content-custom">
                        <div class="modal-header modal-header-custom">
                            <h5 class="modal-title fw-semibold">Approve {{ $app->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body modal-body-custom">
                            <form action="{{ route('admin_membership_approve', $app->id) }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label class="label-custom">Membership type</label>
                                    <select name="membership_type" class="form-control-custom">
                                        @foreach($type_labels as $val => $lbl)
                                        <option value="{{ $val }}" {{ $app->membership_type === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Confirm or correct the type before generating their membership ID.</small>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn-admin-primary">Approve & generate ID</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Reject Modal -->
            <div class="modal fade" id="modal_reject_{{ $loop->iteration }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content modal-content-custom">
                        <div class="modal-header modal-header-custom">
                            <h5 class="modal-title fw-semibold">Reject {{ $app->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body modal-body-custom">
                            <form action="{{ route('admin_membership_reject', $app->id) }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label class="label-custom">Reason (optional, included in the email to the applicant)</label>
                                    <textarea name="reason" class="form-control-custom" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn-admin-delete">Reject application</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
