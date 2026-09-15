@extends('admin.layouts.master')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="page-heading mb-0">
        <h1>Menu</h1>
    </div>
    <a href="" class="btn-admin-primary" data-bs-toggle="modal" data-bs-target="#modal_add">
        <i class="fa-solid fa-plus me-1"></i>Add Menu Item
    </a>
</div>

@php
    $menu_item_type_label = function ($item) {
        if ($item->link_type === 'route') return 'Page: '.($item->route_name ?: '-');
        if ($item->link_type === 'custom_page') return 'Custom page: '.optional($item->customPage)->title;
        return 'URL: '.$item->url;
    };
@endphp

<!-- Add Modal -->
<div class="modal fade" id="modal_add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fw-semibold">Add Menu Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-custom">
                <form action="{{ route('admin_menu_store') }}" method="post">
                    @csrf
                    @include('admin.menu.form', ['item' => null, 'prefix' => 'add'])
                    <div class="mb-3">
                        <button type="submit" class="btn-admin-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- // Add Modal -->

<div class="card-custom-p mb-4">
    <h5 class="fw-semibold mb-3">Login / Dashboard button</h5>
    <p class="text-muted small mb-3">This button switches automatically between "Login" and "Dashboard" depending on whether the visitor is signed in, so it is kept separate from the items below.</p>
    <form action="{{ route('admin_menu_update_login') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-lg-4 mb-3">
                <label class="label-custom">Label (English)</label>
                <input type="text" name="menu_login_name" value="{{ $menu->menu_login_name }}" class="form-control-custom">
            </div>
            <div class="col-lg-4 mb-3">
                <label class="label-custom">Label (second language)</label>
                <input type="text" name="menu_login_name_sv" value="{{ $menu->menu_login_name_sv }}" class="form-control-custom">
            </div>
            <div class="col-lg-4 mb-3">
                <label class="label-custom">Status</label>
                <select name="menu_login_status" class="form-control-custom">
                    <option value="Show" {{ $menu->menu_login_status == 'Show' ? 'selected' : '' }}>Show</option>
                    <option value="Hide" {{ $menu->menu_login_status == 'Hide' ? 'selected' : '' }}>Hide</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn-admin-primary">Update</button>
    </form>
</div>

<div class="card-custom-p5">
    <table class="table-dt stripe hover" id="menuTable">
        <thead>
            <tr>
                <th>Order</th>
                <th>Label</th>
                <th>Links to</th>
                <th>Footer</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($top_items as $item)
            <tr>
                <td>{{ $item->order }}</td>
                <td><strong>{{ $item->label }}</strong>@if($item->is_button) <span class="badge bg-primary">button</span>@endif</td>
                <td>{{ $menu_item_type_label($item) }}</td>
                <td>{{ $item->show_in_footer ? ucfirst($item->footer_column) : '—' }}</td>
                <td>{{ $item->status }}</td>
                <td>
                    <div class="d-flex align-items-center gap-1">
                        <a class="btn-icon btn-icon-edit" data-bs-toggle="modal" data-bs-target="#modal_edit_{{ $item->id }}"><i class="fa-solid fa-pen-to-square text-xs"></i></a>
                        <a class="btn-icon btn-icon-delete" data-bs-toggle="modal" data-bs-target="#modal_delete_{{ $item->id }}"><i class="fa-solid fa-trash text-xs"></i></a>
                    </div>
                </td>
            </tr>
            @foreach($item->children as $child)
            <tr>
                <td>&mdash; {{ $child->order }}</td>
                <td class="ps-4">↳ {{ $child->label }}</td>
                <td>{{ $menu_item_type_label($child) }}</td>
                <td>{{ $child->show_in_footer ? ucfirst($child->footer_column) : '—' }}</td>
                <td>{{ $child->status }}</td>
                <td>
                    <div class="d-flex align-items-center gap-1">
                        <a class="btn-icon btn-icon-edit" data-bs-toggle="modal" data-bs-target="#modal_edit_{{ $child->id }}"><i class="fa-solid fa-pen-to-square text-xs"></i></a>
                        <a class="btn-icon btn-icon-delete" data-bs-toggle="modal" data-bs-target="#modal_delete_{{ $child->id }}"><i class="fa-solid fa-trash text-xs"></i></a>
                    </div>
                </td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>
</div>

@foreach($top_items as $item)
    @include('admin.menu.edit-modal', ['item' => $item])
    @foreach($item->children as $child)
        @include('admin.menu.edit-modal', ['item' => $child])
    @endforeach
@endforeach

@push('scripts')
<script>
function toggleLinkFields(prefix) {
    var type = document.getElementById(prefix + '_link_type').value;
    document.getElementById(prefix + '_route_wrap').style.display = (type === 'route') ? '' : 'none';
    document.getElementById(prefix + '_page_wrap').style.display = (type === 'custom_page') ? '' : 'none';
    document.getElementById(prefix + '_url_wrap').style.display = (type === 'url') ? '' : 'none';
}
document.querySelectorAll('.menu-link-type').forEach(function (el) {
    toggleLinkFields(el.dataset.prefix);
    el.addEventListener('change', function () { toggleLinkFields(el.dataset.prefix); });
});
</script>
@endpush
@endsection
