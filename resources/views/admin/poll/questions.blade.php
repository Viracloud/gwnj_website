@extends('admin.layouts.master')
@section('content')
@php $locked = $poll->responses_count > 0; @endphp
<div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
    <div class="page-heading mb-0">
        <h1>Questions — {{ $poll->title }}</h1>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin_poll_edit', $poll->id) }}" class="btn btn-light"><i class="fa-solid fa-gear me-1"></i>Settings</a>
        <a href="{{ route('admin_poll_results', $poll->id) }}" class="btn btn-light"><i class="fa-solid fa-chart-simple me-1"></i>Results</a>
    </div>
</div>
@if($locked)
<div class="alert alert-warning">This poll already has <b>{{ $poll->responses_count }}</b> votes. You can still fix the wording of questions and options, but not add, remove or change their type — that would make the results meaningless.</div>
@elseif($poll->status === 'draft')
<p class="text-muted">Add the questions, then set the poll to <b>Open</b> in Settings to publish it.</p>
@endif

@foreach($poll->questions as $q)
<div class="card-custom-p mb-3">
    <form action="{{ route('admin_poll_question_update', $q->id) }}" method="post">
        @csrf
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="badge bg-light text-dark">{{ \App\Models\PollQuestion::TYPES[$q->type] ?? $q->type }}</span>
            <small class="text-muted">#{{ $loop->iteration }}</small>
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="label-custom">Question (English) *</label>
                <input type="text" name="question" value="{{ $q->question }}" class="form-control-custom" required>
            </div>
            <div class="col-md-6">
                <label class="label-custom">Question (دری)</label>
                <input type="text" name="question_fa" value="{{ $q->question_fa }}" class="form-control-custom" dir="rtl">
            </div>
            @if($locked)
                <input type="hidden" name="type" value="{{ $q->type }}">
                @if($q->hasOptions())
                <div class="col-12">
                    <label class="label-custom">Options</label>
                    @foreach($q->options as $opt)
                    <div class="row g-2 mb-1">
                        <div class="col-md-6"><input type="text" name="opt_label[{{ $opt->id }}]" value="{{ $opt->label }}" class="form-control-custom"></div>
                        <div class="col-md-6"><input type="text" name="opt_label_fa[{{ $opt->id }}]" value="{{ $opt->label_fa }}" class="form-control-custom" dir="rtl"></div>
                    </div>
                    @endforeach
                    <input type="hidden" name="options" value="{{ $q->options->pluck('label')->implode("\n") }}">
                </div>
                @endif
            @else
                <div class="col-md-4">
                    <label class="label-custom">Type</label>
                    <select name="type" class="form-control-custom">
                        @foreach(\App\Models\PollQuestion::TYPES as $k => $l)
                        <option value="{{ $k }}" {{ $q->type === $k ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="label-custom">Order</label>
                    <input type="number" name="sort_order" value="{{ $q->sort_order }}" min="0" class="form-control-custom">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <label class="d-flex gap-2 align-items-center mb-2"><input type="checkbox" name="required" value="1" {{ $q->required ? 'checked' : '' }}> Answer required</label>
                </div>
                <div class="col-md-6">
                    <label class="label-custom">Options (English) <small>- one per line, for choice questions</small></label>
                    <textarea name="options" rows="4" class="form-control-custom">{{ $q->options->pluck('label')->implode("\n") }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="label-custom">Options (دری) <small>- same order, one per line</small></label>
                    <textarea name="options_fa" rows="4" class="form-control-custom" dir="rtl">{{ $q->options->pluck('label_fa')->implode("\n") }}</textarea>
                </div>
            @endif
            @if($locked)<input type="hidden" name="sort_order" value="{{ $q->sort_order }}"><input type="hidden" name="required" value="{{ $q->required ? 1 : 0 }}">@endif
        </div>
        <div class="d-flex gap-2 mt-3">
            @include('admin.partials.extra-locales', ['table' => 'poll_questions', 'item' => ($q ?? null), 'fields' => ['question' => 'input']])
            <button type="submit" class="btn-admin-primary">Save</button>
        </div>
    </form>
    @unless($locked)
    <form action="{{ route('admin_poll_question_delete', $q->id) }}" method="post" class="mt-2" onsubmit="return confirm('Delete this question?')">
        @csrf
        <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash me-1"></i>Delete question</button>
    </form>
    @endunless
</div>
@endforeach

@unless($locked)
<div class="card-custom-p" style="border-style:dashed">
    <h5 class="fw-semibold mb-3"><i class="fa-solid fa-plus me-1"></i>Add a question</h5>
    <form action="{{ route('admin_poll_question_store', $poll->id) }}" method="post">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="label-custom">Question (English) *</label>
                <input type="text" name="question" value="{{ old('question') }}" class="form-control-custom" required>
            </div>
            <div class="col-md-6">
                <label class="label-custom">Question (دری)</label>
                <input type="text" name="question_fa" value="{{ old('question_fa') }}" class="form-control-custom" dir="rtl">
            </div>
            <div class="col-md-4">
                <label class="label-custom">Type</label>
                <select name="type" class="form-control-custom">
                    @foreach(\App\Models\PollQuestion::TYPES as $k => $l)
                    <option value="{{ $k }}" {{ old('type') === $k ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="label-custom">Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $poll->questions->count() + 1) }}" min="0" class="form-control-custom">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <label class="d-flex gap-2 align-items-center mb-2"><input type="checkbox" name="required" value="1" checked> Answer required</label>
            </div>
            <div class="col-md-6">
                <label class="label-custom">Options (English) <small>- one per line</small></label>
                <textarea name="options" rows="4" class="form-control-custom" placeholder="Yes&#10;No&#10;Not sure">{{ old('options') }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="label-custom">Options (دری) <small>- same order</small></label>
                <textarea name="options_fa" rows="4" class="form-control-custom" dir="rtl" placeholder="بله&#10;نخیر&#10;مطمئن نیستم">{{ old('options_fa') }}</textarea>
            </div>
        </div>
        <p class="text-muted mt-2 mb-3" style="font-size:.85rem">“Rating 1–5” and “Free text” questions don't need options. Free-text answers are only visible here in the admin panel, never on the public site.</p>
        @include('admin.partials.extra-locales', ['table' => 'poll_questions', 'item' => null, 'fields' => ['question' => 'input']])
        <button type="submit" class="btn-admin-primary">Add question</button>
    </form>
</div>
@endunless
@endsection
