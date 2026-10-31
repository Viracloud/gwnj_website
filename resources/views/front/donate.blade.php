@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
@php
    $currency = $global_setting->paypal_currency ?: 'USD';
    $presets = array_filter(array_map('trim', explode(',', (string) $page_data->preset_amounts)));
    $goal = (float) $page_data->goal_amount;
    $progress = $goal > 0 ? min(100, round(($raised / $goal) * 100)) : 0;
@endphp

<div class="pagehead"><div class="shell">
    <h1>{{ tf($page_data,'page_title') ?: tf($page_data,'heading') }}</h1>
</div></div>

<section class="shell">
    <div class="{{ $goal > 0 ? 'doc' : 'donate-solo' }}">
        <div class="doc-main">
            @if(tf($page_data,'heading'))<h2 style="margin-top:0">{{ tf($page_data,'heading') }}</h2>@endif
            <p>{{ tf($page_data,'description') }}</p>

            <div class="form-card">
                <h2 style="margin-top:0">{{ __('Make a donation') }}</h2>

                <form action="{{ route('donate_submit') }}" method="post" id="donateForm">
                    @csrf

                    <div class="field-row">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Full name') }} *</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="{{ __('Your full name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Email address') }} *</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="{{ __('Your email address') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Donation amount') }} ({{ $currency }}) *</label>

                        @if(count($presets))
                        <div class="amount-pick">
                            @foreach($presets as $preset)
                            <input type="radio" name="amount_pick" id="amt-{{ $preset }}" value="{{ $preset }}" data-amount="{{ $preset }}">
                            <label for="amt-{{ $preset }}">{{ $currency }} {{ $preset }}</label>
                            @endforeach
                        </div>
                        @endif

                        <input type="number" class="form-control" name="amount" id="amountInput" min="1" step="0.01"
                               value="{{ old('amount') }}" placeholder="{{ __('Enter amount') }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">{{ __('Message (optional)') }}</label>
                        <textarea class="form-control" name="message" rows="3" placeholder="{{ __('Leave a note of support') }}">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-signal btn-lg" style="width:100%">
                        {{ __('Donate with PayPal') }}
                    </button>

                    <p style="font-family:var(--font-ui);font-size:var(--t-xs);color:var(--muted);text-align:center;margin:var(--s3) 0 0">
                        {{ __('You will be redirected to PayPal to complete your secure donation.') }}
                    </p>
                </form>
            </div>
        </div>

        @if($goal > 0)
        <aside>
            <div class="aside-box">
                <h3>{{ __('Our goal') }}</h3>
                <div class="in">
                    <div class="goal-row">
                        <span>{{ __('Raised') }}: {{ $currency }} {{ number_format($raised, 2) }}</span>
                        <span>{{ __('Goal') }}: {{ $currency }} {{ number_format($goal, 2) }}</span>
                    </div>
                    <div class="goal-bar"><span style="width:{{ $progress }}%"></span></div>
                    <div style="font-family:var(--font-ui);font-size:var(--t-xs);color:var(--muted);margin-top:var(--s2)">
                        {{ $progress }}% {{ __('of our goal reached') }}
                    </div>
                </div>
            </div>
        </aside>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.amount-pick input[type="radio"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            document.getElementById('amountInput').value = this.getAttribute('data-amount');
        });
    });
</script>
@endpush
