@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead"><div class="shell">
    <span class="kicker">{{ __('Accountability') }}</span>
    <h1>{{ tf($page_data,'page_title') }}</h1>
</div></div>

<section class="shell">
    <div class="rich-content">
        {!! clean_html(tf($page_data,'page_content')) !!}
    </div>
</section>
@endsection
