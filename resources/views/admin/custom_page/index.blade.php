@extends('admin.layouts.master')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="page-heading mb-0">
        <h1>{{ $heading ?? 'Custom Pages' }}</h1>
    </div>
    <a href="" class="btn-admin-primary" data-bs-toggle="modal" data-bs-target="#modal_add">
        <i class="fa-solid fa-plus me-1"></i>Add Item
    </a>
</div>
@if(!empty($scope_note))
<p class="text-muted mb-4">{{ $scope_note }}</p>
@endif

<!-- Add Modal -->
<div class="modal fade" id="modal_add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fw-semibold">Add Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-custom">
                <form action="{{ route('admin_custom_page_store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="label-custom">Slug *</label>
                        <input type="text" class="form-control-custom" name="slug">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Slug (Dari) <small>- optional, auto-generated from the Dari title</small></label>
                        <input type="text" class="form-control-custom" name="slug_fa" dir="rtl">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Featured image</label>
                        <input type="file" class="form-control-custom" name="image">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Homepage banner background <small>- optional, used when this page is the homepage campaign banner</small></label>
                        <input type="file" class="form-control-custom" name="home_bg_image" accept="image/*">
                    </div>

                    <div class="mb-3 mt-3">
                        <label class="label-custom">Visibility</label>
                        <select name="status" class="form-control-custom">
                            <option value="Show">Visible on the website</option>
                            <option value="Hide">Hidden (kept, but not published)</option>
                        </select>
                    </div>

                    <h6 class="fw-semibold mt-4 mb-2">English</h6>
                    <div class="mb-3">
                        <label class="label-custom">Title *</label>
                        <input type="text" class="form-control-custom" name="title">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Summary <small>- one or two sentences shown highlighted under the title, above the full text</small></label>
                        <textarea class="form-control-custom" name="excerpt" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Content *</label>
                        <textarea id="editor1" class="form-control-custom" name="content" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">SEO Title</label>
                        <input type="text" class="form-control-custom" name="seo_title">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">SEO Description</label>
                        <textarea class="form-control-custom" name="seo_meta_description" rows="3"></textarea>
                    </div>

                    <h6 class="fw-semibold mt-4 mb-2">دری (second language)</h6>
                    <div class="mb-3">
                        <label class="label-custom">Title</label>
                        <input type="text" class="form-control-custom" name="title_fa" dir="rtl">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Summary</label>
                        <textarea class="form-control-custom" name="excerpt_fa" rows="2" dir="rtl"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">Content</label>
                        <textarea id="editor1_fa" class="form-control-custom tinymce-editor" name="content_fa" rows="4" dir="rtl"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">SEO Title</label>
                        <input type="text" class="form-control-custom" name="seo_title_fa" dir="rtl">
                    </div>
                    <div class="mb-3">
                        <label class="label-custom">SEO Description</label>
                        <textarea class="form-control-custom" name="seo_meta_description_fa" rows="3" dir="rtl"></textarea>
                    </div>

                    <div class="mb-3">
                        @include('admin.partials.extra-locales', ['table' => 'custom_pages', 'item' => null, 'fields' => ['slug' => 'input', 'title' => 'input', 'excerpt' => 'textarea', 'content' => 'editor', 'seo_title' => 'input', 'seo_meta_description' => 'textarea']])
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
                <th>Title</th>
                <th>Slug</th>
                <th>Gallery</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($custom_pages as $custom_page)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $custom_page->title }} @unless($custom_page->isVisible())<span class="badge bg-secondary ms-1">Hidden</span>@endunless</td>
                <td>{{ $custom_page->slug }}</td>
                <td><a href="{{ route('admin_custom_page_gallery', $custom_page->id) }}" class="btn btn-info btn-sm">Manage Gallery ({{ $custom_page->images_count }})</a></td>
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
                            <form action="{{ route('admin_custom_page_update', $custom_page->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="label-custom">Slug *</label>
                                    <input type="text" class="form-control-custom" name="slug" value="{{ $custom_page->slug }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Slug (Dari) <small>- optional, auto-generated from the Dari title</small></label>
                                    <input type="text" class="form-control-custom" name="slug_fa" dir="rtl" value="{{ $custom_page->slug_fa }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Featured image</label>
                                    <input type="file" class="form-control-custom" name="image">
                                    @if($custom_page->image)
                                    <small class="text-muted d-block mt-1">Current: {{ $custom_page->image }}</small>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Homepage banner background <small>- optional, used when this page is the homepage campaign banner</small></label>
                                    <input type="file" class="form-control-custom" name="home_bg_image" accept="image/*">
                                    @if($custom_page->home_bg_image)
                                    <img src="{{ asset('uploads/'.$custom_page->home_bg_image) }}" alt="" style="height:70px;border-radius:8px;margin-top:.5rem;display:block">
                                    <label class="d-block mt-2"><input type="checkbox" name="remove_home_bg_image" value="1"> Remove current background</label>
                                    @endif
                                </div>

                                <div class="mb-3 mt-3">
                                    <label class="label-custom">Visibility</label>
                                    <select name="status" class="form-control-custom">
                                        <option value="Show" {{ $custom_page->isVisible() ? 'selected' : '' }}>Visible on the website</option>
                                        <option value="Hide" {{ !$custom_page->isVisible() ? 'selected' : '' }}>Hidden (kept, but not published)</option>
                                    </select>
                                    <small class="text-muted">A hidden page is removed from the website, the menus and the sitemap, but nothing is deleted.</small>
                                </div>

                                <h6 class="fw-semibold mt-4 mb-2">English</h6>
                                <div class="mb-3">
                                    <label class="label-custom">Title *</label>
                                    <input type="text" class="form-control-custom" name="title" value="{{ $custom_page->title }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Summary <small>- one or two sentences shown highlighted under the title, above the full text</small></label>
                                    <textarea class="form-control-custom" name="excerpt" rows="2">{{ $custom_page->excerpt }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Content *</label>
                                    <textarea id="editor2_{{ $custom_page->id }}" class="form-control-custom tinymce-editor" name="content" rows="4">{{ $custom_page->content }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">SEO Title</label>
                                    <input type="text" class="form-control-custom" name="seo_title" value="{{ $custom_page->seo_title }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">SEO Description</label>
                                    <textarea class="form-control-custom" name="seo_meta_description" rows="3">{{ $custom_page->seo_meta_description }}</textarea>
                                </div>

                                <h6 class="fw-semibold mt-4 mb-2">دری (second language)</h6>
                                <div class="mb-3">
                                    <label class="label-custom">Title</label>
                                    <input type="text" class="form-control-custom" name="title_fa" value="{{ $custom_page->title_fa }}" dir="rtl">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Summary</label>
                                    <textarea class="form-control-custom" name="excerpt_fa" rows="2" dir="rtl">{{ $custom_page->excerpt_fa }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Content</label>
                                    <textarea id="editor2_{{ $custom_page->id }}_fa" class="form-control-custom tinymce-editor" name="content_fa" rows="4" dir="rtl">{{ $custom_page->content_fa }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">SEO Title</label>
                                    <input type="text" class="form-control-custom" name="seo_title_fa" value="{{ $custom_page->seo_title_fa }}" dir="rtl">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">SEO Description</label>
                                    <textarea class="form-control-custom" name="seo_meta_description_fa" rows="3" dir="rtl">{{ $custom_page->seo_meta_description_fa }}</textarea>
                                </div>

                                <div class="mb-3">
                                    @include('admin.partials.extra-locales', ['table' => 'custom_pages', 'item' => ($custom_page ?? null), 'fields' => ['slug' => 'input', 'title' => 'input', 'excerpt' => 'textarea', 'seo_title' => 'input', 'seo_meta_description' => 'textarea']])
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
                            <form action="{{ route('admin_custom_page_delete', $custom_page->id) }}" method="post">
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
