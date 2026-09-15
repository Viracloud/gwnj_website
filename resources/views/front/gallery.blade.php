@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead"><div class="shell">
    <span class="kicker">{{ is_rtl() ? 'گالری' : 'Gallery' }}</span>
    <h1>{{ tf($page_data,'page_title') }}</h1>
</div></div>

<section class="shell">
    <div class="gallery">
        @foreach($photos as $photo)
        <a class="photo glightbox" href="{{ asset('uploads/'.$photo->image) }}" data-glightbox data-gallery="gallery1">
            <img src="{{ asset('uploads/'.$photo->image) }}" alt="{{ tf($photo,'caption') }}">
            @if(tf($photo,'caption'))<span class="cap">{{ tf($photo,'caption') }}</span>@endif
        </a>
        @endforeach
    </div>

    {{ $photos->links() }}
</section>
@endsection
