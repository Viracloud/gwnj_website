@extends('admin.layouts.master')
@section('content')
@php $v = fn($k, $d = null) => old($k, $poll ? $poll->{$k} : $d); @endphp
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="page-heading mb-0">
        <h1>{{ $poll ? 'Poll settings' : 'New poll' }}</h1>
    </div>
    <a href="{{ route('admin_poll_index') }}" class="btn btn-light"><i class="fa-solid fa-arrow-left me-1"></i>All polls</a>
</div>

<form action="{{ $poll ? route('admin_poll_update', $poll->id) : route('admin_poll_store') }}" method="post">
    @csrf
    <div class="row g-4">
        <div class="col-12 col-xl-8">
            <div class="card-custom-p">
                <h6 class="fw-semibold mb-3">Where do people answer?</h6>
                @php $src = $v('source', 'site'); $locked = $poll && $poll->responses()->exists(); @endphp
                <div class="d-flex flex-column gap-2 mb-4">
                    <label class="d-flex gap-2 align-items-start p-3 border rounded-3" style="cursor:pointer">
                        <input type="radio" name="source" value="site" {{ $src === 'site' ? 'checked' : '' }} {{ $locked ? 'disabled' : '' }} class="mt-1">
                        <span><b>On this website</b><br><small class="text-muted">You write the questions here; visitors vote on the site and results update instantly. Light and works well on slow connections.</small></span>
                    </label>
                    <label class="d-flex gap-2 align-items-start p-3 border rounded-3" style="cursor:pointer">
                        <input type="radio" name="source" value="google" {{ $src === 'google' ? 'checked' : '' }} {{ $locked ? 'disabled' : '' }} class="mt-1">
                        <span><b>Google Form</b><br><small class="text-muted">People answer your Google Form; the site reads the results from Google every {{ \App\Models\Poll::GOOGLE_REFRESH_MINUTES }} minutes and shows them as charts.</small></span>
                    </label>
                    @if($locked)<input type="hidden" name="source" value="{{ $poll->source }}"><small class="text-muted">The source can't be changed after votes were collected.</small>@endif
                </div>

                <h6 class="fw-semibold mb-2">English</h6>
                <div class="mb-3">
                    <label class="label-custom">Title *</label>
                    <input type="text" name="title" value="{{ $v('title') }}" class="form-control-custom" required>
                </div>
                <div class="mb-3">
                    <label class="label-custom">Description</label>
                    <textarea name="description" rows="3" class="form-control-custom">{{ $v('description') }}</textarea>
                </div>

                <h6 class="fw-semibold mt-4 mb-2">دری</h6>
                <div class="mb-3">
                    <label class="label-custom">Title</label>
                    <input type="text" name="title_fa" value="{{ $v('title_fa') }}" class="form-control-custom" dir="rtl">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Description</label>
                    <textarea name="description_fa" rows="3" class="form-control-custom" dir="rtl">{{ $v('description_fa') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="label-custom">Google Form link <small>- only for Google Form polls: the link people open to answer</small></label>
                    <input type="url" name="google_form_url" value="{{ $v('google_form_url') }}" class="form-control-custom" placeholder="https://docs.google.com/forms/d/e/.../viewform">
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card-custom-p">
                <div class="mb-3">
                    <label class="label-custom">Status</label>
                    <select name="status" class="form-control-custom">
                        @foreach(\App\Models\Poll::STATUSES as $k => $l)
                        <option value="{{ $k }}" {{ $v('status', 'draft') === $k ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Draft polls are not visible on the website.</small>
                </div>
                <div class="mb-3">
                    <label class="label-custom">Show results</label>
                    <select name="results_visibility" class="form-control-custom">
                        @foreach(\App\Models\Poll::VISIBILITY as $k => $l)
                        <option value="{{ $k }}" {{ $v('results_visibility', 'after_vote') === $k ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="label-custom">Closes automatically on <small>(optional)</small></label>
                    <input type="datetime-local" name="ends_at" value="{{ $poll && $poll->ends_at ? $poll->ends_at->format('Y-m-d\TH:i') : old('ends_at') }}" class="form-control-custom">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Order</label>
                    <input type="number" name="sort_order" min="0" value="{{ $v('sort_order', 0) }}" class="form-control-custom">
                </div>
                <label class="d-flex gap-2 align-items-center mb-4">
                    <input type="checkbox" name="show_on_home" value="1" {{ $v('show_on_home') ? 'checked' : '' }}> Show on the home page
                </label>
                @include('admin.partials.extra-locales', ['table' => 'polls', 'item' => $poll, 'fields' => ['title' => 'input', 'description' => 'textarea']])
                <button type="submit" class="btn-admin-primary w-100">{{ $poll ? 'Save settings' : 'Create poll' }}</button>
            </div>
        </div>
    </div>
</form>

@if($poll && Auth::guard('admin')->user()->isSuperAdmin())
<div class="row"><div class="col-12 col-xl-4 offset-xl-8">
    <form action="{{ route('admin_poll_delete', $poll->id) }}" method="post" class="mt-3" onsubmit="return confirm('Delete this poll and all its votes? This cannot be undone.')">
        @csrf
        <button class="btn btn-outline-danger w-100"><i class="fa-solid fa-trash me-1"></i>Delete poll</button>
    </form>
</div></div>
@endif
@endsection
