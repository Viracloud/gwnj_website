@extends('admin.layouts.master')
@section('content')
<div class="page-heading mb-4">
    <h1>Card Requests</h1>
</div>
<p class="text-muted mb-4">Members who have requested a digital membership card. Issuing generates their verification QR code and emails them a link to view/print it. A card cannot be obtained without this approval.</p>

<div class="card-custom-p5">
    <table id="dataTable" class="table-dt stripe hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Membership ID</th>
                <th>Requested</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $r)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $r->name }}</td>
                <td>{{ $r->membership_id }}</td>
                <td>{{ optional($r->card_requested_at)->format('d M Y') }}</td>
                <td>
                    <div class="d-flex align-items-center gap-1">
                        <form action="{{ route('admin_membership_card_issue', $r->id) }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Issue card</button>
                        </form>
                        <form action="{{ route('admin_membership_card_decline', $r->id) }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Decline</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
