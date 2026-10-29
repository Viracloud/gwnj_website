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
<a class="ptile tone-{{ $tone }}" href="{{ route('poll', $poll->slug) }}">
    <span class="ptile-tag">{{ $poll->isGoogle() ? __('Google Form') : ($open ? __('Open') : __('Closed')) }}</span>
    <span class="ptile-go" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M8 7h9v9"/></svg></span>
    <span class="ptile-art" aria-hidden="true"><i></i><i></i><i></i></span>
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
            <span class="ptile-count">@if($total){{ trans_choice(':count participant|:count participants', $total, ['count' => local_digits($total)]) }}@endif</span>
            <span class="ptile-cta">{{ $open ? __('Take part') : __('See results') }}</span>
        </span>
    </span>
</a>
