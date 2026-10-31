@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead"><div class="shell">
    <h1>{{ __('Our Partners') }}</h1>
</div></div>

<section class="shell">
    @if($partners->count())
    <div class="partners">
        @foreach($partners as $p)
        <a class="plogo" href="{{ $p->website ?: '#' }}" @if($p->website) target="_blank" rel="noopener" @endif title="{{ tf($p,'name') }}">
            <img src="{{ asset('uploads/'.$p->logo) }}" alt="{{ tf($p,'name') }}">
        </a>
        @endforeach
    </div>
    @else
    <p style="color:var(--muted)">{{ __('No partner organisations have been added yet.') }}</p>
    @endif
</section>
@endsection
