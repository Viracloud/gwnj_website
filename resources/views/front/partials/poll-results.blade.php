{{-- Aggregated poll results as light CSS bars (works for website and Google Form polls). --}}
@php
    $rtl = is_rtl();
    $pick = fn($row) => !empty($row['label_'.site_locale()]) ? $row['label_'.site_locale()] : $row['label'];
    $num = fn($n) => site_locale() === 'fa' ? strtr((string) $n, ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹','.'=>'٫']) : $n;
@endphp
<div class="poll-results">
    <div class="poll-results-head">
        <h2 class="poll-results-title">{{ __('Results') }}</h2>
        <p class="poll-total">
            <i class="fa-solid fa-users"></i>
            {{ trans_choice(':count person has taken part|:count people have taken part', $results['total'], ['count' => $num($results['total'])]) }}
        </p>
    </div>
    @foreach($results['questions'] as $q)
    <div class="poll-q">
        <h3><span class="poll-num" aria-hidden="true">{{ $num($loop->iteration) }}</span><span>{{ $pick($q) }}</span></h3>
        @if($q['average'] !== null)
        <div class="poll-avg">
            <small>{{ __('Average rating:') }}</small>
            <b>{{ $num($q['average']) }}</b><span>/ {{ $num(5) }}</span>
            <span class="poll-stars" aria-hidden="true">
                @for($i = 1; $i <= 5; $i++)<i class="fa-{{ $i <= round($q['average']) ? 'solid' : 'regular' }} fa-star"></i>@endfor
            </span>
        </div>
        @endif
        @php $top = collect($q['options'])->max('count'); @endphp
        @foreach($q['options'] as $o)
        @php $is_top = $top && $o['count'] === $top; @endphp
        <div class="poll-bar {{ $is_top ? 'is-top' : '' }}">
            <div class="poll-bar-head">
                <span>{{ $pick($o) }} @if($is_top)<em class="poll-badge">{{ __('Most chosen') }}</em>@endif</span>
                <b>{{ $num($o['pct']) }}{{ __('%') }} <small>({{ trans_choice(':count vote|:count votes', $o['count'], ['count' => $num($o['count'])]) }})</small></b>
            </div>
            <div class="poll-bar-track"><span style="width:{{ $o['pct'] }}%;animation-delay:{{ $loop->index * 80 }}ms"></span></div>
        </div>
        @endforeach
    </div>
    @endforeach
</div>
