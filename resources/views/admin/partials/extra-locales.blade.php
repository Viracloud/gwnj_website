{{--
    Translation fields for the extra languages (French, German ...).
    Shown only for languages listed in SITE_LOCALES (published) or SITE_LOCALES_PREPARE (being translated).
    Saved automatically by the SavesExtraLocales model trait — no controller changes needed.

    @param string      $table   the model's table
    @param Model|null  $item    the record being edited (null on "add" forms)
    @param array       $fields  [field => 'input' | 'textarea' | 'editor']
--}}
@php $xl_locales = extra_locales(); @endphp
@if(count($xl_locales) && !empty($fields))
@foreach($xl_locales as $xl_code)
@php
    $xl_meta = locale_meta($xl_code);
    $xl_live = in_array($xl_code, enabled_locales(), true);
    $xl_uid = 'xl_'.$table.'_'.$xl_code.'_'.($item->id ?? 'new').'_'.\Illuminate\Support\Str::random(4);
@endphp
<details class="xl-box mt-4 mb-3" data-locale="{{ $xl_code }}" data-locale-label="{{ $xl_meta['native'] }}" data-locale-live="{{ $xl_live ? 1 : 0 }}" style="border:1px solid var(--gray-200);border-radius:10px;padding:.75rem 1rem;background:var(--gray-50)">
    <summary class="fw-semibold" style="cursor:pointer">
        {{ $xl_meta['native'] }} ({{ $xl_meta['name'] }}) translation
        @if($xl_live)
            <span class="badge bg-success ms-1">published</span>
        @else
            <span class="badge bg-secondary ms-1">not published yet</span>
        @endif
        <small class="text-muted d-block fw-normal">Leave empty to show the English text for this language.</small>
    </summary>
    <div class="mt-3">
        @foreach($fields as $xl_field => $xl_kind)
        @php
            $xl_name = 'xl['.$table.']['.$xl_code.']['.$xl_field.']';
            $xl_value = old('xl.'.$table.'.'.$xl_code.'.'.$xl_field, $item ? ($item->{$xl_field.'_'.$xl_code} ?? '') : '');
            $xl_label = \Illuminate\Support\Str::headline($xl_field === 'slug' ? 'URL slug' : $xl_field);
        @endphp
        <div class="mb-3">
            <label class="label-custom">{{ $xl_label }} <small>({{ $xl_meta['native'] }})</small></label>
            @if($xl_kind === 'editor')
            <textarea id="{{ $xl_uid }}_{{ $xl_field }}" class="form-control-custom tinymce-editor" name="{{ $xl_name }}" rows="4" lang="{{ $xl_code }}">{{ $xl_value }}</textarea>
            @elseif($xl_kind === 'textarea')
            <textarea class="form-control-custom" name="{{ $xl_name }}" rows="3" lang="{{ $xl_code }}">{{ $xl_value }}</textarea>
            @else
            <input type="text" class="form-control-custom" name="{{ $xl_name }}" value="{{ $xl_value }}" lang="{{ $xl_code }}">
            @endif
        </div>
        @endforeach
    </div>
</details>
@endforeach
@endif
