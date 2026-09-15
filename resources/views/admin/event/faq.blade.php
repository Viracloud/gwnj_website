@extends('admin.layouts.master')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="page-heading mb-0">
        <h1>FAQs of {{ $event->title }}</h1>
    </div>
    <a href="" class="btn-admin-primary" data-bs-toggle="modal" data-bs-target="#modal_add">
        <i class="fa-solid fa-plus me-1"></i>Add Item
    </a>
</div>

<!-- Add Modal -->
<div class="modal fade" id="modal_add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fw-semibold">Add Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-custom">
                <form action="{{ route('admin_event_faq_store', $event->id) }}" method="post">
                    @csrf
                    <h6 class="fw-semibold mt-4 mb-2">English</h6>
                    <div class="mb-3">
                        <label class="label-custom">Question *</label>
                        <input type="text" class="form-control-custom" name="question">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Answer *</label>
                        <textarea class="form-control-custom" name="answer" rows="4"></textarea>
                    </div>

                    <h6 class="fw-semibold mt-4 mb-2">دری (second language)</h6>
                    <div class="mb-3">
                        <label class="label-custom">Question</label>
                        <input type="text" class="form-control-custom" name="question_sv" dir="rtl">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Answer</label>
                        <textarea class="form-control-custom" name="answer_sv" rows="4" dir="rtl"></textarea>
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
                <th>Question</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($event_faqs as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->question }}</td>
                <td>
                    <div class="d-flex align-items-center gap-1">
                        <a class="btn-icon btn-icon-edit btn-edit-row" data-bs-toggle="modal" data-bs-target="#modal_edit_{{ $loop->iteration }}"><i class="fa-solid fa-pen-to-square text-xs"></i></a>
                        <a class="btn-icon btn-icon-delete" data-bs-toggle="modal" data-bs-target="#modal_delete_{{ $loop->iteration }}"><i class="fa-solid fa-trash text-xs"></i></a>
                    </div>
                </td>
            </tr>
            <!-- Edit Modal -->
            <div class="modal fade" id="modal_edit_{{ $loop->iteration }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content modal-content-custom">
                        <div class="modal-header modal-header-custom">
                            <h5 class="modal-title fw-semibold">Edit Item</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body modal-body-custom">
                            <form action="{{ route('admin_event_faq_update', $item->id) }}" method="post">
                                @csrf
                                <h6 class="fw-semibold mt-4 mb-2">English</h6>
                                <div class="mb-3">
                                    <label class="label-custom">Question *</label>
                                    <input type="text" class="form-control-custom" name="question" value="{{ $item->question }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Answer *</label>
                                    <textarea class="form-control-custom" name="answer" rows="4">{{ $item->answer }}</textarea>
                                </div>

                                <h6 class="fw-semibold mt-4 mb-2">دری (second language)</h6>
                                <div class="mb-3">
                                    <label class="label-custom">Question</label>
                                    <input type="text" class="form-control-custom" name="question_sv" value="{{ $item->question_sv }}" dir="rtl">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Answer</label>
                                    <textarea class="form-control-custom" name="answer_sv" rows="4" dir="rtl">{{ $item->answer_sv }}</textarea>
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
                            <h5 class="modal-title fw-semibold">Delete Item</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body modal-body-custom">
                            <form action="{{ route('admin_event_faq_delete', $item->id) }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <p>
                                        Are you sure you want to delete this item?
                                    </p>
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