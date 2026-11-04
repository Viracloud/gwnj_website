{{-- "Three ways to take part": three coloured link cards on a deep band. --}}
@php
    $path_icons = [
        '<circle cx="9" cy="8" r="3"/><circle cx="17" cy="9" r="2.4"/><path d="M3 20c0-3.3 2.7-5 6-5s6 1.7 6 5M15.5 20c0-2.3 1.4-3.6 3.5-3.6s2.5 1 2.5 3.6"/>',
        '<path d="M12 20s-7-4.4-9-9a4.8 4.8 0 019-2.6A4.8 4.8 0 0121 11c-2 4.6-9 9-9 9z"/>',
        '<path d="M3 15h3l4 2.5c.8.5 1.8.4 2.4-.3L19 10"/><path d="M14 11l2.2-2.2a2 2 0 012.8 0l.5.5"/><path d="M7 9.5a3 3 0 015-2.2 3 3 0 015 2.2c0 2.3-5 5-5 5s-5-2.7-5-5z"/>',
    ];
@endphp
<section class="paths-band">
    <div class="shell">
        <div class="paths-intro">
            @if(hc('paths_kicker'))<span class="kicker">{{ hc('paths_kicker') }}</span>@endif
            <h2>{{ hc('paths_heading') }}</h2>
            <p>{{ hc('paths_text') }}</p>
        </div>
        <div class="paths">
            @foreach([1, 2, 3] as $n)
            <a class="path" href="{{ hc_link('paths_'.$n.'_link') }}">
                <span class="pi" aria-hidden="true"><svg viewBox="0 0 24 24">{!! $path_icons[$n - 1] !!}</svg></span>
                <span class="pt">
                    <h3>{{ hc('paths_'.$n.'_title') }}</h3>
                    <span>{{ hc('paths_'.$n.'_text') }}</span>
                </span>
            </a>
            @endforeach
        </div>
    </div>
</section>
