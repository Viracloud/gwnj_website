<!-- Edit Modal -->
<div class="modal fade" id="modal_edit_{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fw-semibold">Edit Menu Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-custom">
                <form action="{{ route('admin_menu_update', $item->id) }}" method="post">
                    @csrf
                    @include('admin.menu.form', ['item' => $item, 'prefix' => 'edit'.$item->id])
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
<div class="modal fade" id="modal_delete_{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fw-semibold">Delete Menu Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-custom">
                <form action="{{ route('admin_menu_delete', $item->id) }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <p>Are you sure you want to delete "{{ $item->label }}"?
                        @if($item->children->count())
                        <br><strong>This will also delete its {{ $item->children->count() }} submenu item(s).</strong>
                        @endif
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
