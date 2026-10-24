@php
    $item = $item ?? null;
    $val = fn($field, $default = '') => old($field, $item->{$field} ?? $default);
@endphp
<div class="row">
    <div class="col-lg-6 mb-3">
        <label class="label-custom">Label (English) *</label>
        <input type="text" name="label" value="{{ $val('label') }}" class="form-control-custom" required>
    </div>
    <div class="col-lg-6 mb-3">
        <label class="label-custom">Label (second language)</label>
        <input type="text" name="label_fa" value="{{ $val('label_fa') }}" class="form-control-custom">
    </div>

    <div class="col-lg-6 mb-3">
        <label class="label-custom">Parent item</label>
        <select name="parent_id" class="form-control-custom">
            <option value="">— Top level —</option>
            @foreach($parent_options as $p)
                @if(!$item || $p->id != $item->id)
                <option value="{{ $p->id }}" {{ (string) $val('parent_id') === (string) $p->id ? 'selected' : '' }}>{{ $p->label }}</option>
                @endif
            @endforeach
        </select>
        <small class="text-muted">Leave as "Top level" for a main menu item, or choose a parent to make this a submenu link.</small>
    </div>
    <div class="col-lg-6 mb-3">
        <label class="label-custom">Order</label>
        <input type="number" name="order" value="{{ $val('order', 0) }}" class="form-control-custom">
    </div>

    <div class="col-lg-12 mb-3">
        <label class="label-custom">Links to *</label>
        <select name="link_type" class="form-control-custom menu-link-type" id="{{ $prefix }}_link_type" data-prefix="{{ $prefix }}">
            <option value="route" {{ $val('link_type','route') == 'route' ? 'selected' : '' }}>An existing page on this site</option>
            <option value="custom_page" {{ $val('link_type') == 'custom_page' ? 'selected' : '' }}>A custom page</option>
            <option value="url" {{ $val('link_type') == 'url' ? 'selected' : '' }}>A web address (URL)</option>
        </select>
    </div>

    <div class="col-lg-12 mb-3" id="{{ $prefix }}_route_wrap">
        <label class="label-custom">Page</label>
        <select name="route_name" class="form-control-custom">
            @foreach($available_routes as $key => $label)
            <option value="{{ $key }}" {{ $val('route_name') == $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-12 mb-3" id="{{ $prefix }}_page_wrap">
        <label class="label-custom">Custom page</label>
        <select name="custom_page_id" class="form-control-custom">
            <option value="">— Select —</option>
            @foreach($custom_pages as $cp)
            <option value="{{ $cp->id }}" {{ (string) $val('custom_page_id') === (string) $cp->id ? 'selected' : '' }}>{{ $cp->title }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-12 mb-3" id="{{ $prefix }}_url_wrap">
        <label class="label-custom">URL</label>
        <input type="text" name="url" value="{{ $val('url') }}" class="form-control-custom" placeholder="https://... or /some-path">
        <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" name="open_new_tab" id="{{ $prefix }}_new_tab" {{ $val('open_new_tab') ? 'checked' : '' }}>
            <label class="form-check-label" for="{{ $prefix }}_new_tab">Open in a new tab</label>
        </div>
    </div>

    <div class="col-lg-6 mb-3">
        <label class="label-custom">Status</label>
        <select name="status" class="form-control-custom">
            <option value="Show" {{ $val('status','Show') == 'Show' ? 'selected' : '' }}>Show</option>
            <option value="Hide" {{ $val('status') == 'Hide' ? 'selected' : '' }}>Hide</option>
        </select>
    </div>
    <div class="col-lg-6 mb-3 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_button" id="{{ $prefix }}_is_button" {{ $val('is_button') ? 'checked' : '' }}>
            <label class="form-check-label" for="{{ $prefix }}_is_button">Show as a highlighted button (e.g. "Donate")</label>
        </div>
    </div>

    <div class="col-lg-6 mb-3 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="show_in_footer" id="{{ $prefix }}_show_in_footer" {{ $val('show_in_footer', true) ? 'checked' : '' }}>
            <label class="form-check-label" for="{{ $prefix }}_show_in_footer">Also show this link in the footer</label>
        </div>
    </div>
    <div class="col-lg-6 mb-3">
        <label class="label-custom">Footer column</label>
        <select name="footer_column" class="form-control-custom">
            <option value="about" {{ $val('footer_column','about') == 'about' ? 'selected' : '' }}>About</option>
            <option value="work" {{ $val('footer_column') == 'work' ? 'selected' : '' }}>Our work</option>
        </select>
    </div>
</div>
@include('admin.partials.extra-locales', ['table' => 'menu_items', 'item' => $item, 'fields' => ['label' => 'input']])
