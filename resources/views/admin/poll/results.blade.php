@extends('admin.layouts.master')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
    <div class="page-heading mb-0">
        <h1>Results — {{ $poll->title }}</h1>
        <p class="mb-0">{{ $results['total'] }} {{ $results['total'] == 1 ? 'response' : 'responses' }}
            @if($poll->isGoogle()) · read from Google {{ $poll->google_synced_at ? $poll->google_synced_at->diffForHumans() : 'never' }}@endif
        </p>
    </div>
    <div class="d-flex gap-2">
        @if(Auth::guard('admin')->user()->canEditContent())
            @if($poll->isGoogle())
            <form action="{{ route('admin_poll_google_refresh', $poll->id) }}" method="post">@csrf<button class="btn btn-light"><i class="fa-solid fa-rotate me-1"></i>Refresh from Google</button></form>
            @else
            <a href="{{ route('admin_poll_export', $poll->id) }}" class="btn btn-light"><i class="fa-solid fa-file-csv me-1"></i>Download all answers (CSV)</a>
            @endif
        @endif
        <a href="{{ route('admin_poll_index') }}" class="btn btn-light"><i class="fa-solid fa-arrow-left me-1"></i>All polls</a>
    </div>
</div>

@forelse($results['questions'] as $q)
<div class="card-custom-p mb-3">
    <div class="d-flex justify-content-between flex-wrap gap-2 mb-3">
        <div>
            <h6 class="fw-semibold mb-0">{{ $q['label'] }}</h6>
            @if(!empty($q['label_fa']))<div class="text-muted" dir="rtl" style="font-size:.875rem">{{ $q['label_fa'] }}</div>@endif
        </div>
        <small class="text-muted">{{ $q['answered'] }} answered @if($q['average'] !== null) · average <b>{{ $q['average'] }}</b>@endif</small>
    </div>
    @foreach($q['options'] as $o)
    <div class="mb-2">
        <div class="d-flex justify-content-between" style="font-size:.875rem">
            <span>{{ $o['label'] }}@if(!empty($o['label_fa']) && $o['label_fa'] !== $o['label']) <span class="text-muted" dir="rtl">/ {{ $o['label_fa'] }}</span>@endif</span>
            <span><b>{{ $o['pct'] }}%</b> <span class="text-muted">({{ $o['count'] }})</span></span>
        </div>
        <div style="height:10px;background:var(--gray-100);border-radius:6px;overflow:hidden">
            <div style="height:100%;width:{{ $o['pct'] }}%;background:var(--primary-500);border-radius:6px"></div>
        </div>
    </div>
    @endforeach
</div>
@empty
<div class="card-custom-p text-muted">
    @if($poll->isGoogle())
    No questions selected yet — <a href="{{ route('admin_poll_google', $poll->id) }}">connect the Google Sheet</a>.
    @else
    No questions yet — <a href="{{ route('admin_poll_questions', $poll->id) }}">add questions</a>.
    @endif
</div>
@endforelse

@foreach($text_answers as $tq)
<div class="card-custom-p mb-3">
    <h6 class="fw-semibold mb-1">{{ $tq->question }} <span class="badge bg-light text-dark ms-1">free text — admin only</span></h6>
    @if($tq->answers->count())
    <ul class="mb-0 mt-2" style="max-height:420px;overflow:auto">
        @foreach($tq->answers as $a)
        <li class="mb-2" style="white-space:pre-line">{{ $a->text }}</li>
        @endforeach
    </ul>
    @else
    <p class="text-muted mb-0">No answers yet.</p>
    @endif
</div>
@endforeach
@endsection
