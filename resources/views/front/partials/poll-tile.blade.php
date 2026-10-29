{{-- Colourful poll card (home slider and polls page). Needs $poll and $tone (navy|orange|green|sand|brand). --}}
@php
    $open = $poll->isOpen();
    $total = $poll->totalResponses();
    $lead = null;
    if (!$open && $poll->resultsVisible(false)) {
        $first = $poll->results()['questions'][0] ?? null;
        $top = $first ? collect($first['options'])->sortByDesc('count')->first() : null;
        if ($top && $top['count'] > 0) {
            $lead = [
                'label' => !empty($top['label_'.site_locale()]) ? $top['label_'.site_locale()] : $top['label'],
                'pct' => $top['pct'],
            ];
        }
    }
@endphp
<a class="ptile tone-{{ $tone }} {{ $open ? 'is-open' : 'is-closed' }}" href="{{ route('poll', $poll->slug) }}">
    <span class="ptile-top">
        <span class="ptile-tag">{{ $poll->isGoogle() ? __('Google Form') : ($open ? __('Open') : __('Closed')) }}</span>
        <span class="ptile-go" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M8 7h9v9"/></svg>
        </span>
    </span>

    <span class="ptile-art" aria-hidden="true">
        <svg viewBox="0 0 120 84" fill="currentColor">
            <rect x="0" y="44" width="24" height="40" rx="12" opacity=".40"/>
            <rect x="32" y="22" width="24" height="62" rx="12" opacity=".62"/>
            <rect x="64" y="34" width="24" height="50" rx="12" opacity=".30"/>
            <rect x="96" y="4" width="24" height="80" rx="12" opacity=".85"/>
        </svg>
    </span>

    <span class="ptile-body">
        <h3>{{ tf($poll, 'title') }}</h3>
        @if(tf($poll, 'description'))<span class="ptile-desc">{{ \Illuminate\Support\Str::limit(tf($poll, 'description'), 90) }}</span>@endif
        @if($lead)
        <span class="ptile-lead">
            <span class="ptile-lead-head"><span>{{ $lead['label'] }}</span><b>{{ local_digits($lead['pct']) }}{{ __('%') }}</b></span>
            <span class="ptile-lead-bar"><span style="width:{{ $lead['pct'] }}%"></span></span>
        </span>
        @endif
        <span class="ptile-foot">
            <span class="ptile-count">
                @if($total)<i class="fa-solid fa-user-group" aria-hidden="true"></i>{{ trans_choice(':count participant|:count participants', $total, ['count' => local_digits($total)]) }}@endif
            </span>
            <span class="ptile-cta">{{ $open ? __('Take part') : __('See results') }}</span>
        </span>
    </span>
</a>
