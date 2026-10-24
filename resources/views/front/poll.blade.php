@extends('front.layouts.master')

@section('banner')
@endsection

@php
    $rtl = is_rtl();
    $title = tf($poll, 'title');
    $desc = tf($poll, 'description');
    $open = $poll->isOpen();
    $participants = $poll->totalResponses();
    $question_count = $poll->questions->count();
@endphp

@section('content')
<div class="pagehead"><div class="shell">
    <span class="kicker">{{ __('Poll') }}</span>
    <h1>{{ $title }}</h1>
    @if($desc)<p class="lede">{{ $desc }}</p>@endif
</div></div>

<section class="shell poll-wrap">
    <div class="poll-summary">
        @if($open)
            <span class="chip done poll-live">{{ __('Open') }}</span>
        @else
            <span class="chip plain">{{ __('Closed') }}</span>
        @endif
        <ul class="poll-meta">
            <li><i class="fa-solid fa-users"></i>{{ trans_choice(':count participant|:count participants', $participants, ['count' => local_digits($participants)]) }}</li>
            @if($question_count)
            <li><i class="fa-regular fa-circle-question"></i>{{ trans_choice(':count question|:count questions', $question_count, ['count' => local_digits($question_count)]) }}</li>
            @endif
            @if($open && $poll->ends_at)
            <li><i class="fa-regular fa-calendar"></i>{{ __('Open until :date', ['date' => fdate($poll->ends_at, 'j F Y')]) }}</li>
            @endif
            @if(!$poll->isGoogle())
            <li><i class="fa-solid fa-user-shield"></i>{{ __('Anonymous') }}</li>
            @endif
        </ul>
    </div>

    {{-- Website poll: voting form --}}
    @if(!$poll->isGoogle() && $open && !$voted)
    <form action="{{ route('poll_vote', $poll->slug) }}" method="post" class="poll-form" data-digits="{{ site_locale() === 'fa' ? 'fa' : '' }}">
        @csrf
        <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="poll-hp" aria-hidden="true">
        @foreach($poll->questions as $q)
        @php $qt = tf($q, 'question'); @endphp
        <fieldset class="poll-q {{ $q->type === 'multiple' ? 'is-multi' : '' }}">
            <legend>
                <span class="poll-num" aria-hidden="true">{{ local_digits($loop->iteration) }}</span>
                <span class="poll-qtext">{{ $qt }} @if($q->required)<span class="req">*</span>@endif</span>
            </legend>
            @if($q->type === 'single' || $q->type === 'multiple')
                @if($q->type === 'multiple')<p class="poll-hint">{{ __('You can choose more than one.') }}</p>@endif
                <div class="poll-opts">
                @foreach($q->options as $o)
                <label class="poll-opt">
                    <input type="{{ $q->type === 'single' ? 'radio' : 'checkbox' }}" name="q[{{ $q->id }}]{{ $q->type === 'multiple' ? '[]' : '' }}" value="{{ $o->id }}"
                        {{ in_array($o->id, (array) old('q.'.$q->id, [])) || old('q.'.$q->id) == $o->id ? 'checked' : '' }}>
                    <span class="poll-mark" aria-hidden="true"></span>
                    <span>{{ tf($o, 'label') }}</span>
                </label>
                @endforeach
                </div>
            @elseif($q->type === 'rating')
                <div class="poll-rating">
                    @for($i = 1; $i <= 5; $i++)
                    <label><input type="radio" name="q[{{ $q->id }}]" value="{{ $i }}" {{ old('q.'.$q->id) == $i ? 'checked' : '' }}><span>{{ local_digits($i) }}</span></label>
                    @endfor
                </div>
                <div class="poll-rating-scale"><span>{{ __('Low') }}</span><span>{{ __('High') }}</span></div>
            @else
                <textarea name="q[{{ $q->id }}]" rows="3" maxlength="2000" class="form-control" placeholder="{{ __('Your answer (only seen by the network)') }}">{{ old('q.'.$q->id) }}</textarea>
            @endif
        </fieldset>
        @endforeach
        <p class="poll-privacy"><i class="fa-solid fa-lock"></i> {{ __('Your vote is anonymous — no name or personal details are recorded.') }}</p>
        <div class="poll-submit">
            <div class="poll-progress">
                <span class="poll-progress-text">{{ __('Answered') }} <b data-poll-done>{{ local_digits(0) }}</b> / {{ local_digits($question_count) }}</span>
                <span class="poll-progress-track"><span data-poll-bar></span></span>
            </div>
            <button type="submit" class="btn btn-brand">{{ __('Submit my vote') }} <i class="fa-solid fa-paper-plane ms-1"></i></button>
        </div>
    </form>
    @endif

    {{-- Google Form poll: link to take part --}}
    @if($poll->isGoogle() && $open && $poll->google_form_url)
    <div class="poll-google">
        <span class="poll-icon"><i class="fa-brands fa-google"></i></span>
        <div>
            <p>{{ __('This survey runs on Google Forms. The overall results on this page update automatically.') }}</p>
            <a class="btn btn-brand" href="{{ $poll->google_form_url }}" target="_blank" rel="noopener">{{ __('Take part in the survey') }} <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i></a>
        </div>
    </div>
    @endif

    @if($voted && !$poll->isGoogle())
    <div class="poll-thanks"><i class="fa-solid fa-circle-check"></i> <span>{{ __('You have taken part in this poll. Thank you!') }}</span></div>
    @endif

    {{-- Results --}}
    @if($show_results && $results)
        @include('front.partials.poll-results', ['results' => $results])
        @if($poll->isGoogle() && $poll->google_synced_at)
        <p class="poll-updated"><i class="fa-solid fa-rotate"></i> {{ __('Last updated:') }} {{ fdate($poll->google_synced_at, 'j F Y') }} {{ local_digits($poll->google_synced_at->format('H:i')) }}</p>
        @endif
    @elseif(!$open || $voted)
        <p class="poll-note"><i class="fa-regular fa-clock"></i> {{ __('Results will be published when the poll closes.') }}</p>
    @endif

    <p style="margin-top:var(--s6)"><a class="more" href="{{ route('polls') }}">{{ __('All polls') }}</a></p>
</section>
@endsection
