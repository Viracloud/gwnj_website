@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead"><div class="shell">
    <span class="kicker">{{ is_rtl() ? 'شراکت‌ها' : 'Partnerships' }}</span>
    <h1>{{ is_rtl() ? 'سازمان‌های شریک ما' : 'Our Partners' }}</h1>
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
    <p style="color:var(--muted)">{{ is_rtl() ? 'هنوز سازمان شریکی ثبت نشده است.' : 'No partner organisations have been added yet.' }}</p>
    @endif
</section>
@endsection
