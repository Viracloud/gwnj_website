@extends('admin.layouts.master')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="page-heading mb-0">
        <h1>Donations</h1>
        <p class="mb-0 text-muted">Total received (completed): <strong>{{ number_format($total_completed, 2) }}</strong></p>
    </div>
    <a href="{{ route('admin_donation_export') }}" class="btn-admin-primary">
        <i class="fa-solid fa-download me-1"></i> Export as CSV
    </a>
</div>

<div class="card-custom-p5">
    <table id="dataTable" class="table-dt stripe hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Donor</th>
                <th>Email</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Transaction ID</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($donations as $donation)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $donation->donor_name }}</td>
                <td>{{ $donation->donor_email }}</td>
                <td>{{ $donation->currency }} {{ number_format($donation->amount, 2) }}</td>
                <td>{{ $donation->payment_method }}</td>
                <td>{{ $donation->transaction_id }}</td>
                <td>
                    @if($donation->status == 'Completed')
                        <span class="badge bg-success">Completed</span>
                    @else
                        <span class="badge bg-warning text-dark">{{ $donation->status }}</span>
                    @endif
                </td>
                <td>{{ $donation->created_at }}</td>
                <td>
                    <div class="d-flex align-items-center gap-1">
                        <a class="btn-icon btn-icon-delete" data-bs-toggle="modal" data-bs-target="#modal_delete_{{ $loop->iteration }}"><i class="fa-solid fa-trash text-xs"></i></a>
                    </div>
                </td>
            </tr>
            <!-- Delete Modal -->
            <div class="modal fade" id="modal_delete_{{ $loop->iteration }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content modal-content-custom">
                        <div class="modal-header modal-header-custom">
                            <h5 class="modal-title fw-semibold">Delete Donation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body modal-body-custom">
                            <form action="{{ route('admin_donation_delete', $donation->id) }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <p>Are you sure you want to delete this donation record?</p>
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
