@extends('admin.layouts.master')
@section('content')
@php $selected = collect($poll->google_columns ?? [])->keyBy('index'); @endphp
<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
    <div class="page-heading mb-0">
        <h1>Google Sheet — {{ $poll->title }}</h1>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin_poll_edit', $poll->id) }}" class="btn btn-light"><i class="fa-solid fa-gear me-1"></i>Settings</a>
        <a href="{{ route('admin_poll_results', $poll->id) }}" class="btn btn-light"><i class="fa-solid fa-chart-simple me-1"></i>Results</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-xl-7">
        <div class="card-custom-p mb-4">
            <h5 class="fw-semibold mb-3">1. Link to the published results</h5>
            <form action="{{ route('admin_poll_google_url', $poll->id) }}" method="post">
                @csrf
                <label class="label-custom">“Publish to web” link of the <b>Public</b> tab (CSV)</label>
                <input type="text" name="google_csv_url" value="{{ old('google_csv_url', $poll->google_csv_url) }}" class="form-control-custom mb-2" placeholder="https://docs.google.com/spreadsheets/d/e/.../pub?gid=...&single=true&output=csv" required>
                <small class="text-muted d-block mb-3">This link stays in the admin panel; visitors never see it.</small>
                <button type="submit" class="btn-admin-primary">Save link &amp; load questions</button>
                @if($poll->google_csv_url)
                <a href="{{ route('admin_poll_google', ['id' => $poll->id, 'check' => 1]) }}" class="btn btn-light ms-1">Reload questions</a>
                @endif
            </form>
        </div>

        @if($error)
        <div class="alert alert-danger"><b>Google could not be read:</b> {{ $error }}</div>
        @endif

        @if($headers)
        <div class="card-custom-p mb-4">
            <h5 class="fw-semibold mb-1">2. Choose the questions to show</h5>
            <p class="text-muted" style="font-size:.875rem">Tick only survey questions. Leave name, e-mail, phone, address and free-text columns <b>unticked</b> — they are never read.</p>
            <form action="{{ route('admin_poll_google_columns', $poll->id) }}" method="post">
                @csrf
                <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Show</th><th>Column in the sheet</th><th>Chart</th><th>Title on the site (English / دری)</th></tr></thead>
                    <tbody>
                    @foreach($headers as $i => $h)
                    @php $s = $selected->get($i); $looks_personal = preg_match('/timestamp|name|e-?mail|phone|address|نام|ایمیل|تلفن|شماره|آدرس|زمان/iu', $h); @endphp
                    <tr class="{{ $looks_personal ? 'table-light' : '' }}">
                        <td>
                            <input type="hidden" name="cols[{{ $i }}][index]" value="{{ $i }}">
                            <input type="checkbox" name="cols[{{ $i }}][use]" value="1" {{ $s ? 'checked' : '' }}>
                        </td>
                        <td>{{ $h ?: '(empty header)' }} @if($looks_personal)<span class="badge bg-warning text-dark ms-1">looks personal</span>@endif</td>
                        <td>
                            <select name="cols[{{ $i }}][type]" class="form-control-custom" style="min-width:150px">
                                <option value="single" {{ ($s['type'] ?? '') === 'single' ? 'selected' : '' }}>One answer</option>
                                <option value="multiple" {{ ($s['type'] ?? '') === 'multiple' ? 'selected' : '' }}>Several answers (checkboxes)</option>
                                <option value="rating" {{ ($s['type'] ?? '') === 'rating' ? 'selected' : '' }}>Rating / scale</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="cols[{{ $i }}][label]" value="{{ $s['label'] ?? $h }}" class="form-control-custom mb-1">
                            <input type="text" name="cols[{{ $i }}][label_fa]" value="{{ $s['label_fa'] ?? '' }}" class="form-control-custom" dir="rtl" placeholder="عنوان دری">
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                </div>
                <button type="submit" class="btn-admin-primary">Save &amp; read results</button>
            </form>
        </div>
        @endif

        @if($poll->google_columns && !$headers)
        <div class="card-custom-p">
            <h5 class="fw-semibold mb-2">Status</h5>
            <p class="mb-1">Questions shown: <b>{{ count($poll->google_columns) }}</b> · Responses: <b>{{ $poll->google_cache['total'] ?? 0 }}</b></p>
            <p class="text-muted mb-2" style="font-size:.875rem">Last read from Google: {{ $poll->google_synced_at ? $poll->google_synced_at->diffForHumans() : 'never' }} — refreshed automatically every {{ \App\Models\Poll::GOOGLE_REFRESH_MINUTES }} minutes.</p>
            @if($poll->google_sync_error)<div class="alert alert-warning py-2">Last attempt failed: {{ $poll->google_sync_error }} (the previous results are still shown)</div>@endif
            <form action="{{ route('admin_poll_google_refresh', $poll->id) }}" method="post">
                @csrf
                <button class="btn btn-outline-primary"><i class="fa-solid fa-rotate me-1"></i>Refresh now</button>
            </form>
        </div>
        @endif
    </div>

    <div class="col-12 col-xl-5">
        <div class="card-custom-p" style="background:var(--gray-50)">
            <h5 class="fw-semibold mb-3"><i class="fa-solid fa-circle-info me-1"></i>How to prepare the Google Sheet</h5>
            <ol style="font-size:.875rem;line-height:1.7;padding-inline-start:1.1rem">
                <li>In Google Forms open <b>Responses → Link to Sheets</b>. The answers go to a tab called <i>Form Responses 1</i>. <b>This tab stays private.</b></li>
                <li>Add a new tab and name it <b>Public</b>.</li>
                <li>In cell A1 of <b>Public</b> type a formula that copies <b>only the survey questions</b> (not name, e-mail, phone…). Example — copy columns D, E and F:
                    <code class="d-block my-2 p-2 bg-white border rounded" dir="ltr">=QUERY('Form Responses 1'!A:Z, "select D, E, F")</code>
                    Change the letters to your question columns. New answers appear in this tab automatically.</li>
                <li>Open <b>File → Share → Publish to web</b>. Choose the <b>Public</b> tab (not “Entire document”) and <b>Comma-separated values (.csv)</b>, then click <b>Publish</b>.</li>
                <li>Copy the link and paste it in step 1 on this page.</li>
            </ol>
            <p class="mb-0" style="font-size:.8rem;color:var(--gray-500)">Google updates a published tab every few minutes, and the site re-reads it every {{ \App\Models\Poll::GOOGLE_REFRESH_MINUTES }} minutes — new answers appear on the site within about 5–15 minutes.</p>
        </div>
    </div>
</div>
@endsection
