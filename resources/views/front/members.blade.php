@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
@php
    $intro = trim((string) tf($page_data, 'intro'));
    $intro_paragraphs = $intro !== '' ? preg_split('/\R{2,}/u', $intro) : [];
@endphp
<div class="pagehead"><div class="shell">
    <span class="kicker">{{ __('About us') }}</span>
    <h1>{{ tf($page_data,'page_title') }}</h1>
</div></div>

@if(count($intro_paragraphs))
<section class="shell structure-intro">
    <span class="kicker">{{ __('Organizational structure') }}</span>
    @foreach($intro_paragraphs as $para)
    <p class="lead">{!! nl2br(e(trim($para))) !!}</p>
    @endforeach
</section>
@endif

@foreach($categories as $category)
<section class="shell people-group">
    <div class="sec-head">
        <div class="txt">
            <h2>{{ tf($category,'name') }}</h2>
        </div>
    </div>
    <div class="cards people">
        @foreach($category->members as $member)
        @include('front.partials.person-card')
        @endforeach
    </div>
</section>
@endforeach

@if($uncategorized->count())
<section class="shell people-group">
    <div class="cards people">
        @foreach($uncategorized as $member)
        @include('front.partials.person-card')
        @endforeach
    </div>
</section>
@endif

@if(!$categories->count() && !$uncategorized->count())
<section class="shell">
    <p style="color:var(--muted)">{{ __('No one has been introduced here yet.') }}</p>
</section>
@endif
@endsection
