@extends('admin.layouts.master')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="page-heading mb-0">
        <h1>Replies</h1>
    </div>
</div>

<div class="card-custom-p5">
    <table id="dataTable" class="table-dt stripe hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Post</th>
                <th>Comment</th>
                <th>Name & Email</th>
                <th>Reply</th>
                <th>Reply By</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($replies as $reply)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                     {{ $reply->post->title }}
                    <br>
                    <a href="{{ route('post', $reply->post->slug) }}" target="_blank">Visit Link</a>
                </td>
                <td>
                    {{ $reply->comment->comment }}
                    @if($reply->reply_by == 'User')
                    <br>
                    <a href="" data-bs-toggle="modal" data-bs-target="#modal_admin_reply_{{ $reply->id }}">Admin Reply</a>
                    @endif

                    <!-- Admin Reply Modal -->
                    <div class="modal fade" id="modal_admin_reply_{{ $reply->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Give Reply</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('admin_post_reply_admin_reply') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="post_id" value="{{ $reply->post->id }}">
                                        <input type="hidden" name="comment_id" value="{{ $reply->comment->id }}">
                                        <div class="row">
                                            <div class="col-lg-12 mb-3">
                                                <label for="">Reply</label>
                                                <textarea name="reply" class="form-control h_100" rows="5" required></textarea>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- // Admin Reply Modal -->
                </td>
                <td>{{ $reply->name }}<br>{{ $reply->email }}</td>
                <td>{!! nl2br($reply->reply) !!}</td>
                <td>
                    {{ $reply->reply_by }}
                </td>
                <td>
                    @if($reply->status == 'Pending')
                        <span class="badge bg-warning">Pending</span>
                    @elseif($reply->status == 'Approved')
                        <span class="badge bg-success">Approved</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex align-items-center gap-1">
                        <a class="btn-icon btn-icon-edit btn-edit-row" data-bs-toggle="modal" data-bs-target="#modal_change_status_{{ $loop->iteration }}"><i class="fa-solid fa-pen-to-square text-xs"></i></a>
                        <a class="btn-icon btn-icon-delete" data-bs-toggle="modal" data-bs-target="#modal_delete_{{ $loop->iteration }}"><i class="fa-solid fa-trash text-xs"></i></a>
                    </div>
                </td>
            </tr>
            <!-- Change Status Modal -->
            <div class="modal fade" id="modal_change_status_{{ $loop->iteration }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content modal-content-custom">
                        <div class="modal-header modal-header-custom">
                            <h5 class="modal-title fw-semibold">Change Status</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body modal-body-custom">
                            <form action="{{ route('admin_post_reply_change', $reply->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="label-custom">Change Status *</label>
                                    <select name="status" class="form-control-custom">
                                        <option value="Pending" {{ $reply->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Approved" {{ $reply->status == 'Approved' ? 'selected' : '' }}>Approved</option>
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
            <!-- // Change Status Modal -->

            <!-- Delete Modal -->
            <div class="modal fade" id="modal_delete_{{ $loop->iteration }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content modal-content-custom">
                        <div class="modal-header modal-header-custom">
                            <h5 class="modal-title fw-semibold">Delete Item</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body modal-body-custom">
                            <form action="{{ route('admin_post_reply_delete', $reply->id) }}" method="post">
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