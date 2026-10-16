@extends('admin.layouts.master')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="page-heading mb-0">
        <h1>Partners</h1>
    </div>
    <a href="" class="btn-admin-primary" data-bs-toggle="modal" data-bs-target="#modal_add">
        <i class="fa-solid fa-plus me-1"></i>Add Partner
    </a>
</div>
<p class="text-muted mb-4">Logos shown in the "Our Partners" section on the homepage and on the public <a href="{{ route('partners') }}" target="_blank">/partners</a> page. Only partners set to "Show" appear on the site.</p>

<!-- Add Modal -->
<div class="modal fade" id="modal_add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fw-semibold">Add Partner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-custom">
                <form action="{{ route('admin_partner_store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="label-custom">Logo * <small>- transparent PNG/SVG looks best</small></label>
                        <input type="file" class="form-control-custom" name="logo">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Name (English) *</label>
                        <input type="text" class="form-control-custom" name="name">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Name (Dari)</label>
                        <input type="text" class="form-control-custom" name="name_sv" dir="rtl">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Website <small>- the logo links here, e.g. https://example.org</small></label>
                        <input type="text" class="form-control-custom" name="website" placeholder="https://">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Display order <small>- smaller number shows first</small></label>
                        <input type="number" min="0" class="form-control-custom" name="sort_order" value="0">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Status</label>
                        <select name="status" class="form-control-custom">
                            <option value="Show">Show</option>
                            <option value="Hide">Hide</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn-admin-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- // Add Modal -->

<div class="card-custom-p5">
    <table id="dataTable" class="table-dt stripe hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Logo</th>
                <th>Name</th>
                <th>Website</th>
                <th>Order</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($partners as $partner)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><img src="{{ asset('uploads/'.$partner->logo) }}" alt="" style="height:36px;max-width:120px;object-fit:contain"></td>
                <td>{{ $partner->name }}</td>
                <td>{{ $partner->website ?: '—' }}</td>
                <td>{{ $partner->sort_order }}</td>
                <td>
                    @if($partner->status == 'Show')
                    <span class="badge bg-success">Show</span>
                    @else
                    <span class="badge bg-secondary">Hide</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex align-items-center gap-1">
                        <a class="btn-icon btn-icon-edit btn-edit-row" data-bs-toggle="modal" data-bs-target="#modal_edit_{{ $loop->iteration }}"><i class="fa-solid fa-pen-to-square text-xs"></i></a>
                        <a class="btn-icon btn-icon-delete" data-bs-toggle="modal" data-bs-target="#modal_delete_{{ $loop->iteration }}"><i class="fa-solid fa-trash text-xs"></i></a>
                    </div>
                </td>
            </tr>
            <!-- Edit Modal -->
            <div class="modal fade" id="modal_edit_{{ $loop->iteration }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content modal-content-custom">
                        <div class="modal-header modal-header-custom">
                            <h5 class="modal-title fw-semibold">Edit Partner</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body modal-body-custom">
                            <form action="{{ route('admin_partner_update', $partner->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="label-custom">Existing Logo</label>
                                    <div><img src="{{ asset('uploads/'.$partner->logo) }}" alt="" style="height:50px;max-width:160px;object-fit:contain"></div>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Change Logo</label>
                                    <input type="file" class="form-control-custom" name="logo">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Name (English) *</label>
                                    <input type="text" class="form-control-custom" name="name" value="{{ $partner->name }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Name (Dari)</label>
                                    <input type="text" class="form-control-custom" name="name_sv" value="{{ $partner->name_sv }}" dir="rtl">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Website</label>
                                    <input type="text" class="form-control-custom" name="website" value="{{ $partner->website }}" placeholder="https://">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Display order</label>
                                    <input type="number" min="0" class="form-control-custom" name="sort_order" value="{{ $partner->sort_order }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Status</label>
                                    <select name="status" class="form-control-custom">
                                        <option value="Show" {{ $partner->status=='Show'?'selected':'' }}>Show</option>
                                        <option value="Hide" {{ $partner->status=='Hide'?'selected':'' }}>Hide</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn-admin-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // Edit Modal -->

            <!-- Delete Modal -->
            <div class="modal fade" id="modal_delete_{{ $loop->iteration }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content modal-content-custom">
                        <div class="modal-header modal-header-custom">
                            <h5 class="modal-title fw-semibold">Delete Partner</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body modal-body-custom">
                            <form action="{{ route('admin_partner_delete', $partner->id) }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <p>Are you sure you want to delete <b>{{ $partner->name }}</b>?</p>
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
