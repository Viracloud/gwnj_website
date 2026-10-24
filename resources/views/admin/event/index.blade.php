@extends('admin.layouts.master')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="page-heading mb-0">
        <h1>Events</h1>
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
                <form action="{{ route('admin_event_store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="label-custom">Select Image *</label>
                        <input type="file" class="form-control-custom" name="image">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Category *</label>
                        <select name="event_category_id" class="form-control-custom" required>
                            <option value="">Select Category</option>
                            @foreach($event_categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @if($event_categories->isEmpty())
                        <small class="text-danger d-block mt-1">No categories yet — create one first under Events &gt; Categories.</small>
                        @endif
                    </div>

                    <h6 class="fw-semibold mt-4 mb-2">English</h6>
                    <div class="mb-3">
                        <label class="label-custom">Title *</label>
                        <input type="text" class="form-control-custom" name="title">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Slug *</label>
                        <input type="text" class="form-control-custom" name="slug">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Slug (Dari) <small>- optional, auto-generated from the Dari title</small></label>
                        <input type="text" class="form-control-custom" name="slug_fa" dir="rtl">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Description *</label>
                        <textarea id="editor1" class="form-control-custom tinymce-editor" name="description" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Event Location *</label>
                        <input type="text" class="form-control-custom" name="event_location">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Event Location Detail *</label>
                        <textarea class="form-control-custom" name="event_location_detail" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Button Text</label>
                        <input type="text" class="form-control-custom" name="button_text">
                    </div>

                    <h6 class="fw-semibold mt-4 mb-2">دری (second language)</h6>
                    <div class="mb-3">
                        <label class="label-custom">Title</label>
                        <input type="text" class="form-control-custom" name="title_fa" dir="rtl">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Description</label>
                        <textarea id="editor1_fa" class="form-control-custom tinymce-editor" name="description_fa" rows="4" dir="rtl"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Event Location</label>
                        <input type="text" class="form-control-custom" name="event_location_fa" dir="rtl">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Event Location Detail</label>
                        <textarea class="form-control-custom" name="event_location_detail_fa" rows="4" dir="rtl"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Button Text</label>
                        <input type="text" class="form-control-custom" name="button_text_fa" dir="rtl">
                    </div>

                    <div class="mb-3">
                        <label class="label-custom">Show on Home?</label>
                        <select class="form-control-custom" name="show_on_home">
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label class="label-custom">Event Date *</label>
                            <input type="text" class="form-control-custom" name="event_date">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="label-custom">Event Time *</label>
                            <input type="text" class="form-control-custom" name="event_time">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="label-custom">Ticket Price</label>
                            <input type="text" class="form-control-custom" name="ticket_price">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="label-custom">Video link <small>- YouTube, Instagram, Facebook or Vimeo link (or a YouTube id)</small></label>
                            <input type="text" class="form-control-custom" name="youtube_id">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="label-custom">Button Link</label>
                            <input type="text" class="form-control-custom" name="button_link">
                        </div>
                    </div>
                    <div class="mb-3">
                        @include('admin.partials.extra-locales', ['table' => 'events', 'item' => null, 'fields' => ['slug' => 'input', 'title' => 'input', 'description' => 'editor', 'event_location' => 'input', 'event_location_detail' => 'textarea', 'button_text' => 'input']])
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
                <th>Image</th>
                <th>Title</th>
                <th>Category</th>
                <th>Show on Home?</th>
                <th>FAQs</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <img src="{{ asset('uploads/'.$event->image) }}" alt="" class="w_200">
                </td>
                <td>{{ $event->title }}</td>
                <td>{{ optional($event->category)->name }}</td>
                <td>
                    @if($event->show_on_home == 'Yes')
                    <span class="badge bg-success">Yes</span>
                    @else
                    <span class="badge bg-danger">No</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin_event_faqs', $event->id) }}" class="btn btn-warning btn-sm">Manage FAQs</a>
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
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content modal-content-custom">
                        <div class="modal-header modal-header-custom">
                            <h5 class="modal-title fw-semibold">Edit Item</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body modal-body-custom">
                            <form action="{{ route('admin_event_update', $event->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="label-custom">Existing Image</label>
                                    <div>
                                        <img src="{{ asset('uploads/'.$event->image) }}" alt="" class="w_150">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Change Image</label>
                                    <input type="file" class="form-control-custom" name="image">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Category *</label>
                                    <select name="event_category_id" class="form-control-custom" required>
                                        <option value="">Select Category</option>
                                        @foreach($event_categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $event->event_category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <h6 class="fw-semibold mt-4 mb-2">English</h6>
                                <div class="mb-3">
                                    <label class="label-custom">Title *</label>
                                    <input type="text" class="form-control-custom" name="title" value="{{ $event->title }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Slug *</label>
                                    <input type="text" class="form-control-custom" name="slug" value="{{ $event->slug }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Slug (Dari) <small>- optional, auto-generated from the Dari title</small></label>
                                    <input type="text" class="form-control-custom" name="slug_fa" dir="rtl" value="{{ $event->slug_fa }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Description *</label>
                                    <textarea id="editor1_{{ $event->id }}" class="form-control-custom tinymce-editor" name="description" rows="4">{{ $event->description }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Event Location *</label>
                                    <input type="text" class="form-control-custom" name="event_location" value="{{ $event->event_location }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Event Location Detail *</label>
                                    <textarea class="form-control-custom" name="event_location_detail" rows="4">{{ $event->event_location_detail }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Button Text</label>
                                    <input type="text" class="form-control-custom" name="button_text" value="{{ $event->button_text }}">
                                </div>

                                <h6 class="fw-semibold mt-4 mb-2">دری (second language)</h6>
                                <div class="mb-3">
                                    <label class="label-custom">Title</label>
                                    <input type="text" class="form-control-custom" name="title_fa" value="{{ $event->title_fa }}" dir="rtl">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Description</label>
                                    <textarea id="editor1_{{ $event->id }}_fa" class="form-control-custom tinymce-editor" name="description_fa" rows="4" dir="rtl">{{ $event->description_fa }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Event Location</label>
                                    <input type="text" class="form-control-custom" name="event_location_fa" value="{{ $event->event_location_fa }}" dir="rtl">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Event Location Detail</label>
                                    <textarea class="form-control-custom" name="event_location_detail_fa" rows="4" dir="rtl">{{ $event->event_location_detail_fa }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Button Text</label>
                                    <input type="text" class="form-control-custom" name="button_text_fa" value="{{ $event->button_text_fa }}" dir="rtl">
                                </div>

                                <div class="mb-3">
                                    <label class="label-custom">Show on Home?</label>
                                    <select class="form-control-custom" name="show_on_home">
                                        <option value="Yes" {{ $event->show_on_home == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ $event->show_on_home == 'No' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <label class="label-custom">Event Date *</label>
                                        <input type="text" class="form-control-custom" name="event_date" value="{{ $event->event_date }}">
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="label-custom">Event Time *</label>
                                        <input type="text" class="form-control-custom" name="event_time" value="{{ $event->event_time }}">
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="label-custom">Ticket Price</label>
                                        <input type="text" class="form-control-custom" name="ticket_price" value="{{ $event->ticket_price }}">
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="label-custom">Video link <small>- YouTube, Instagram, Facebook or Vimeo link (or a YouTube id)</small></label>
                                        <input type="text" class="form-control-custom" name="youtube_id" value="{{ $event->youtube_id }}">
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="label-custom">Button Link</label>
                                        <input type="text" class="form-control-custom" name="button_link" value="{{ $event->button_link }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    @include('admin.partials.extra-locales', ['table' => 'events', 'item' => ($event ?? null), 'fields' => ['slug' => 'input', 'title' => 'input', 'event_location' => 'input', 'event_location_detail' => 'textarea', 'button_text' => 'input']])
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
                            <form action="{{ route('admin_event_delete', $event->id) }}" method="post">
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