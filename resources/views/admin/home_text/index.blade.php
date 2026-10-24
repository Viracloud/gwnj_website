@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Home Page Texts</h1>
</div>

<p class="text-muted mb-4">Every word on the public home page can be changed here, in English and in Dari. Leave a field as it is to keep the current text; clear it to go back to the default. Photos, the slider, About text, events, news and reports are managed from their own menus (shown in each section's note).</p>

<form action="{{ route('admin_home_text_update') }}" method="post">
    @csrf
    @foreach($sections as $sid => $section)
    <h6 class="cus-heading">{{ $section['title'] }}</h6>
    <div class="card-custom-p mb-4">
        @if(!empty($section['help']))
        <p class="text-muted small mb-3">{{ $section['help'] }}</p>
        @endif

        @if(!empty($section['toggle']))
        @php $off = optional($rows->get($section['toggle']))->value === '0'; @endphp
        <div class="mb-3">
            <label class="label-custom">Show this section on the home page</label>
            <select name="toggle[{{ $section['toggle'] }}]" class="form-control-custom" style="max-width:220px">
                <option value="1" {{ !$off ? 'selected' : '' }}>Show</option>
                <option value="0" {{ $off ? 'selected' : '' }}>Hide</option>
            </select>
        </div>
        <hr>
        @endif

        @foreach($section['fields'] as $key => $def)
        @php $row = $rows->get($key); @endphp
        <div class="mb-3">
            <label class="label-custom">{{ $def['label'] }}</label>
            @if(in_array($def['type'], ['shared', 'link']))
                <input type="text" name="val[{{ $key }}]" class="form-control-custom"
                       value="{{ $row->value ?? '' }}"
                       placeholder="{{ $def['type'] === 'shared' ? $def['en'] : 'Default: ' . $def['route'] . ' page (or type a full link)' }}">
                @if($def['type'] === 'shared')<small class="text-muted">Same number in both languages.</small>@endif
            @else
            <div class="row g-2">
                <div class="col-md-6">
                    <small class="text-muted d-block">English</small>
                    @if($def['type'] === 'textarea')
                    <textarea name="en[{{ $key }}]" rows="3" class="form-control-custom">{{ $row->value ?? $def['en'] }}</textarea>
                    @else
                    <input type="text" name="en[{{ $key }}]" class="form-control-custom" value="{{ $row->value ?? $def['en'] }}">
                    @endif
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block">دری</small>
                    @if($def['type'] === 'textarea')
                    <textarea dir="rtl" name="fa[{{ $key }}]" rows="3" class="form-control-custom">{{ $row->value_fa ?? $def['fa'] }}</textarea>
                    @else
                    <input type="text" dir="rtl" name="fa[{{ $key }}]" class="form-control-custom" value="{{ $row->value_fa ?? $def['fa'] }}">
                    @endif
                </div>
                {{-- Extra languages (French, German ...) when published or being prepared --}}
                @foreach(extra_locales() as $xl_code)
                <div class="col-md-6">
                    <small class="text-muted d-block">{{ locale_meta($xl_code)['native'] }} <span class="text-muted">(empty = English)</span></small>
                    @if($def['type'] === 'textarea')
                    <textarea name="xl_text[{{ $xl_code }}][{{ $key }}]" rows="3" class="form-control-custom" lang="{{ $xl_code }}">{{ $row->{'value_'.$xl_code} ?? '' }}</textarea>
                    @else
                    <input type="text" name="xl_text[{{ $xl_code }}][{{ $key }}]" class="form-control-custom" value="{{ $row->{'value_'.$xl_code} ?? '' }}" lang="{{ $xl_code }}">
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endforeach

    <div class="card-custom-p mb-4" style="position:sticky;bottom:0;z-index:5">
        <button type="submit" class="btn-admin-primary">Save all texts</button>
    </div>
</form>
@endsection
