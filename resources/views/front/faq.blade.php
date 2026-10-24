@extends('front.layouts.master')

@section('banner')
@endsection

@section('content')
<div class="pagehead"><div class="shell">
    <span class="kicker">{{ __('FAQ') }}</span>
    <h1>{{ tf($page_data,'page_title') }}</h1>
</div></div>

<section class="shell">
    @if($faqs->count())
    <div class="accordion" id="faqAccordion" style="max-width:840px;margin-inline:auto">
        @foreach($faqs as $faq)
        <div class="accordion-item">
            <h2 class="accordion-header" id="faqHead{{ $faq->id }}">
                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                    data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $faq->id }}"
                    aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="faqCollapse{{ $faq->id }}">
                    {{ tf($faq,'question') }}
                </button>
            </h2>
            <div id="faqCollapse{{ $faq->id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                aria-labelledby="faqHead{{ $faq->id }}" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    {!! nl2br(e(tf($faq,'answer'))) !!}
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div style="max-width:840px;margin-inline:auto">
        {{ $faqs->links() }}
    </div>
    @else
    <p style="color:var(--muted)">{{ __('No questions have been added yet.') }}</p>
    @endif
</section>
@endsection
