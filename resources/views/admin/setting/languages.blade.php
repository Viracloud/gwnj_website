@extends('admin.layouts.master')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="page-heading mb-0">
        <h1>Languages</h1>
    </div>
    <a href="" class="btn-admin-primary" data-bs-toggle="modal" data-bs-target="#modal_add">
        <i class="fa-solid fa-plus me-1"></i>Add Language
    </a>
</div>
<p class="text-muted mb-4">Which languages the website is published in, which one opens by default, and how much of the content is translated.</p>

<!-- Add Modal -->
<div class="modal fade" id="modal_add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fw-semibold">Add Language</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-custom">
                <form action="{{ route('admin_language_store') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label class="label-custom">Language code * <small>- 2 or 3 letters, e.g. es, ps, ar. Used in the address: /es/</small></label>
                        <input type="text" class="form-control-custom" name="code" maxlength="3" value="{{ old('code') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Name in English * <small>- e.g. Spanish</small></label>
                        <input type="text" class="form-control-custom" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Name in the language itself * <small>- shown in the language menu, e.g. Español</small></label>
                        <input type="text" class="form-control-custom" name="native" value="{{ old('native') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Writing direction</label>
                        <select name="dir" class="form-control-custom">
                            <option value="ltr">Left to right</option>
                            <option value="rtl">Right to left</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Status</label>
                        <select name="status" class="form-control-custom">
                            <option value="prepare">Being prepared - translation fields in the admin only</option>
                            <option value="published">Published on the website</option>
                            <option value="off">Off</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Display order <small>- smaller number shows first in the language menu</small></label>
                        <input type="number" min="0" max="999" class="form-control-custom" name="sort_order" value="{{ $languages->count() }}">
                    </div>
                    <p class="text-muted" style="font-size:.85rem">Adding a language creates its translation fields in every form. Until a text is translated, the English text is shown.</p>
                    <button type="submit" class="btn-admin-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- // Add Modal -->

<div class="card-custom-p5 mb-4">
    <table class="table align-middle mb-0">
        <thead>
            <tr><th>Language</th><th>Address</th><th>Status</th><th>Default</th><th>Content translated</th><th>Order</th><th>Action</th></tr>
        </thead>
        <tbody>
        @foreach($languages as $lang)
            @php $is_base = $lang->code === base_locale(); @endphp
            <tr>
                <td><b>{{ $lang->native }}</b> <span class="text-muted">({{ $lang->name }})</span> @if($lang->dir === 'rtl')<span class="badge bg-light text-dark border">RTL</span>@endif</td>
                <td><code>/{{ $lang->code }}/</code></td>
                <td>
                    @if($lang->status === 'published')
                        <span class="badge bg-success">Published</span>
                    @elseif($lang->status === 'prepare')
                        <span class="badge bg-warning text-dark">Being prepared</span>
                    @else
                        <span class="badge bg-secondary">Off</span>
                    @endif
                    @if($is_base) <span class="badge bg-primary">Main language</span>@endif
                </td>
                <td>
                    @if($lang->is_default)
                        <span class="badge bg-primary"><i class="fa-solid fa-star me-1"></i>Default</span>
                    @elseif($lang->status === 'published')
                        <form action="{{ route('admin_language_default', $lang->id) }}" method="post" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-secondary">Make default</button>
                        </form>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td style="min-width:200px">
                    @if($is_base)
                        <span class="text-muted">—</span>
                    @else
                        <div class="d-flex align-items-center gap-2">
                            <div style="flex:1;height:8px;background:var(--gray-100);border-radius:6px;overflow:hidden">
                                <div style="height:100%;width:{{ $lang->progress['percent'] }}%;background:var(--primary-500)"></div>
                            </div>
                            <small><b>{{ $lang->progress['percent'] }}%</b></small>
                        </div>
                    @endif
                </td>
                <td>{{ $lang->sort_order }}</td>
                <td>
                    <div class="d-flex align-items-center gap-1">
                        <a class="btn-icon btn-icon-edit btn-edit-row" data-bs-toggle="modal" data-bs-target="#modal_edit_{{ $lang->id }}"><i class="fa-solid fa-pen-to-square text-xs"></i></a>
                        @if(!$is_base && !$lang->is_default)
                        <a class="btn-icon btn-icon-delete" data-bs-toggle="modal" data-bs-target="#modal_delete_{{ $lang->id }}"><i class="fa-solid fa-trash text-xs"></i></a>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@foreach($languages as $lang)
@php $is_base = $lang->code === base_locale(); @endphp
<!-- Edit Modal -->
<div class="modal fade" id="modal_edit_{{ $lang->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fw-semibold">Edit Language - {{ $lang->name }} (/{{ $lang->code }}/)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-custom">
                <form action="{{ route('admin_language_update', $lang->id) }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label class="label-custom">Name in English *</label>
                        <input type="text" class="form-control-custom" name="name" value="{{ $lang->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Name in the language itself * <small>- shown in the language menu</small></label>
                        <input type="text" class="form-control-custom" name="native" value="{{ $lang->native }}" dir="{{ $lang->dir }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Writing direction</label>
                        <select name="dir" class="form-control-custom">
                            <option value="ltr" {{ $lang->dir === 'ltr' ? 'selected' : '' }}>Left to right</option>
                            <option value="rtl" {{ $lang->dir === 'rtl' ? 'selected' : '' }}>Right to left</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Status @if($is_base)<small>- the main language is always published</small>@elseif($lang->is_default)<small>- the default language stays published</small>@endif</label>
                        <select name="status" class="form-control-custom" @if($is_base) disabled @endif>
                            <option value="published" {{ $lang->status === 'published' ? 'selected' : '' }}>Published on the website</option>
                            <option value="prepare" {{ $lang->status === 'prepare' ? 'selected' : '' }}>Being prepared - translation fields in the admin only</option>
                            <option value="off" {{ $lang->status === 'off' ? 'selected' : '' }}>Off</option>
                        </select>
                        @if($is_base)<input type="hidden" name="status" value="published">@endif
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Display order <small>- smaller number shows first in the language menu</small></label>
                        <input type="number" min="0" max="999" class="form-control-custom" name="sort_order" value="{{ $lang->sort_order }}">
                    </div>
                    <button type="submit" class="btn-admin-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- // Edit Modal -->

@if(!$is_base && !$lang->is_default)
<!-- Delete Modal -->
<div class="modal fade" id="modal_delete_{{ $lang->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fw-semibold">Remove {{ $lang->name }}?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-custom">
                <p>The language disappears from the website, the language menu and the admin forms. Its address <code>/{{ $lang->code }}/</code> stops working.</p>
                <p class="text-muted" style="font-size:.85rem">Translations already entered are kept in the database: adding the language again with the code <code>{{ $lang->code }}</code> brings them back. To hide a language for a while, set its status to "Off" instead.</p>
                <form action="{{ route('admin_language_delete', $lang->id) }}" method="post" class="d-flex gap-2 justify-content-end">
                    @csrf
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Remove</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- // Delete Modal -->
@endif
@endforeach

<div class="card-custom-p" style="background:var(--gray-50)">
    <h5 class="fw-semibold mb-2"><i class="fa-solid fa-circle-info me-1"></i>How it works</h5>
    <ul class="mb-0" style="font-size:.9rem;line-height:1.8">
        <li><b>Published</b> - the language is in the language menu, at its own address (e.g. <code>/fr/</code>), in the sitemap and for Google.</li>
        <li><b>Being prepared</b> - its translation fields appear in every form here so content can be translated first; the website does not show it yet.</li>
        <li><b>Default</b> - the language a visitor gets at the bare address (<code>/</code>) on a first visit. Visitors who chose a language keep their choice.</li>
        <li>Anything not translated yet is shown in English. Interface texts (buttons, messages) come from <code>lang/&lt;code&gt;.json</code>; without that file they appear in English.</li>
    </ul>
</div>
@endsection
