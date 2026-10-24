@extends('admin.layouts.master')
@section('content')
@php $can_edit = Auth::guard('admin')->user()->canEditContent(); @endphp
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="page-heading mb-0">
        <h1>Polls &amp; Surveys</h1>
    </div>
    @if($can_edit)
    <a href="{{ route('admin_poll_create') }}" class="btn-admin-primary"><i class="fa-solid fa-plus me-1"></i>New poll</a>
    @endif
</div>
<p class="text-muted mb-4">
    A poll either collects votes <b>on this website</b>, or shows the results of a <b>Google Form</b>.
    Only overall results (counts and percentages) are shown publicly — never who answered.
</p>

<div class="card-custom-p5">
    <table id="dataTable" class="table-dt stripe hover">
        <thead>
            <tr>
                <th>Title</th>
                <th>Source</th>
                <th>Status</th>
                <th>Responses</th>
                <th>Home page</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($polls as $poll)
            <tr>
                <td>
                    <b>{{ $poll->title }}</b>
                    @if($poll->title_fa)<div class="text-muted" dir="rtl" style="font-size:.85rem">{{ $poll->title_fa }}</div>@endif
                </td>
                <td>
                    @if($poll->isGoogle())
                    <span class="badge bg-info text-dark"><i class="fa-brands fa-google me-1"></i>Google Form</span>
                    @else
                    <span class="badge bg-primary">Website</span>
                    @endif
                </td>
                <td>
                    @php $b = ['draft' => 'bg-secondary', 'open' => 'bg-success', 'closed' => 'bg-dark'][$poll->status] ?? 'bg-secondary'; @endphp
                    <span class="badge {{ $b }}">{{ $poll->isClosed() && $poll->status === 'open' ? 'Ended' : ucfirst($poll->status) }}</span>
                </td>
                <td>{{ $poll->isGoogle() ? ($poll->google_cache['total'] ?? '—') : $poll->responses_count }}</td>
                <td>{!! $poll->show_on_home ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-light text-dark">No</span>' !!}</td>
                <td>
                    <div class="d-flex flex-wrap gap-1">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin_poll_results', $poll->id) }}"><i class="fa-solid fa-chart-simple me-1"></i>Results</a>
                        @if($can_edit)
                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin_poll_edit', $poll->id) }}"><i class="fa-solid fa-pen me-1"></i>Settings</a>
                        @if($poll->isGoogle())
                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin_poll_google', $poll->id) }}"><i class="fa-brands fa-google me-1"></i>Google Sheet</a>
                        @else
                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin_poll_questions', $poll->id) }}"><i class="fa-solid fa-list-check me-1"></i>Questions</a>
                        @endif
                        @endif
                        @if($poll->status !== 'draft')
                        <a class="btn btn-sm btn-light" href="{{ route('poll', $poll->slug) }}" target="_blank" title="View on website"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
