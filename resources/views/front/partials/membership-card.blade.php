{{-- The membership card itself is always English, regardless of the site's
     current language - a printed ID card needs one fixed, consistent language. --}}
@php
    $type_labels = [
        'founding' => 'Founding Member',
        'regular' => 'Regular Member',
        'honorary' => 'Honorary Member',
        'affiliate' => 'Affiliate Member',
        'organizational' => 'Organizational Member',
    ];
    $position = $user->position ?: $user->occupation;
    $committees = $user->committees;
    $verify_url = url('/verify/'.$user->verification_token);
    $site_host = $global_setting->footer_website ?: parse_url(url('/'), PHP_URL_HOST);
@endphp
<div class="mc-pair" dir="ltr">
    <div class="mc-side">
        <span class="mc-side-label no-print">Front</span>
        <div class="membership-card mc-front">
            <img class="mc-watermark" src="{{ asset('uploads/'.$global_setting->logo_1) }}" alt="">
            <div class="mc-head">
                <img class="mc-logo" src="{{ asset('uploads/'.$global_setting->logo_1) }}" alt="GWJN">
                <div class="mc-org">
                    <b>Global Women&rsquo;s Justice Network (GWJN)</b>
                    <span>Membership Card</span>
                </div>
            </div>
            <div class="mc-body">
                <div class="mc-photo">
                    @if($user->photo)
                    <img src="{{ asset('uploads/'.$user->photo) }}" alt="{{ $user->fullName() }}">
                    @else
                    <i class="fa-solid fa-user"></i>
                    @endif
                </div>
                <div class="mc-info">
                    <span class="mc-name">{{ $user->fullName() }}</span>
                    <span class="mc-position">{{ $position ?: ($type_labels[$user->membership_type] ?? $type_labels['regular']) }}</span>
                    @if($position)
                    <span class="mc-type-tag">{{ $type_labels[$user->membership_type] ?? $type_labels['regular'] }}</span>
                    @endif
                    <span class="mc-id"><i class="fa-solid fa-id-badge"></i> {{ $user->membership_id }}</span>
                </div>
            </div>
            <div class="mc-foot">
                <span>ISSUED {{ optional($user->card_issued_at)->format('j M Y') }} &nbsp;&middot;&nbsp; EXPIRES {{ optional($user->card_expires_at)->format('j M Y') }}</span>
            </div>
            <div class="mc-foot mc-foot-contact">
                <span><i class="fa-solid fa-phone"></i> {{ $global_setting->footer_phone }}</span>
                <span><i class="fa-solid fa-envelope"></i> {{ $global_setting->footer_email }}</span>
                <span><i class="fa-solid fa-globe"></i> {{ $site_host }}</span>
            </div>
        </div>
    </div>

    <div class="mc-side">
        <span class="mc-side-label no-print">Back</span>
        <div class="membership-card mc-back">
            <img class="mc-watermark" src="{{ asset('uploads/'.$global_setting->logo_1) }}" alt="">
            <div class="mc-head mc-head-sm">
                <img class="mc-logo" src="{{ asset('uploads/'.$global_setting->logo_1) }}" alt="GWJN">
                <div class="mc-org">
                    <b>Global Women&rsquo;s Justice Network</b>
                    <span>GWJN</span>
                </div>
            </div>
            <div class="mc-back-body">
                @if($committees->count())
                <div class="mc-committees">
                    @foreach($committees as $c)
                    <span class="mc-committee">{{ $c->name }}</span>
                    @endforeach
                </div>
                @endif
                <div class="mc-qr">{!! membership_qr_svg($verify_url, 140) !!}</div>
                <span class="mc-scan">Scan to verify membership</span>
                <p class="mc-legal">This card remains the property of the Global Women&rsquo;s Justice Network and must be returned if found.</p>
            </div>
            <div class="mc-foot mc-foot-contact">
                <span><i class="fa-solid fa-phone"></i> {{ $global_setting->footer_phone }}</span>
                <span><i class="fa-solid fa-envelope"></i> {{ $global_setting->footer_email }}</span>
                <span><i class="fa-solid fa-globe"></i> {{ $site_host }}</span>
            </div>
        </div>
    </div>
</div>
