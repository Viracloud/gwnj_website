@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead"><div class="shell">
    <h1>{{ __('Reports and publications') }}</h1>
</div></div>

<section class="shell">
    @if($publication_categories->count())
    <div class="filters">
        <a href="{{ route('publications') }}" class="{{ !request('category') ? 'active' : '' }}">{{ __('All') }}</a>
        @foreach($publication_categories as $cat)
        <a href="{{ route('publications', ['category' => $cat->slug]) }}" class="{{ request('category') == $cat->slug ? 'active' : '' }}">{{ tf($cat,'name') }}</a>
        @endforeach
    </div>
    @endif
    @if($publications->count())
    <ul class="records">
        @foreach($publications as $pub)
        <li class="record">
            <div class="meta">
                <b>{{ $pub->published_date ? fdate($pub->published_date, 'Y') : '' }}</b>
                <span>{{ $pub->category ? tf($pub->category,'name') : '' }}</span>
            </div>
            <div>
                <h3>{{ tf($pub, 'title') }}</h3>
                @if(tf($pub, 'description'))
                <p>{{ plain_excerpt(tf($pub, 'description'), 160) }}</p>
                @endif
            </div>
            @if($pub->file || $pub->hasVideo())
            <div class="record-actions">
                @if($pub->hasVideo())
                @php
                    // Plays on the site (YouTube / Instagram / Facebook / Vimeo / uploaded file) only after a click;
                    // an unrecognised link opens in a new tab.
                    $vsrc = $pub->videoSource();
                    $vemb = $pub->video_file ? null : video_embed($vsrc);
                @endphp
                <a class="go go-video" href="{{ $vsrc }}"
                   @if($pub->video_file) data-video-file="{{ $vsrc }}"
                   @elseif($vemb) data-video-embed="{{ $vemb['embed'] }}" data-video-portrait="{{ $vemb['portrait'] ? 1 : 0 }}"
                   @else target="_blank" rel="noopener" @endif>
                    <i class="fa-solid fa-circle-play"></i> {{ __('Watch video') }}
                </a>
                @endif
                @if($pub->file)
                <a class="go" href="{{ asset('uploads/'.$pub->file) }}" target="_blank" rel="noopener">{{ hc('pubs_download') }}</a>
                @endif
            </div>
            @endif
        </li>
        @endforeach
    </ul>
    {{ $publications->links() }}
    @else
    <p style="color:var(--muted)">{{ __('No publications have been posted yet.') }}</p>
    @endif
</section>
@endsection
