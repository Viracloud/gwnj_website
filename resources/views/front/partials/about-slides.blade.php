@php $about_slides = $about_item->slideshowImages(); @endphp
@if(count($about_slides) > 1)
<div class="about-slides" data-slideshow>
    @foreach($about_slides as $i => $img)
    <img src="{{ asset('uploads/'.$img) }}" alt="{{ $i === 0 ? ($alt ?? '') : '' }}" class="{{ $i === 0 ? 'is-active' : '' }}" @if($i > 0) loading="lazy" @endif>
    @endforeach
    <div class="about-dots" role="tablist">
        @foreach($about_slides as $i => $img)
        <button type="button" class="{{ $i === 0 ? 'is-active' : '' }}" aria-label="{{ __('Photo :number', ['number' => local_digits($i + 1)]) }}"></button>
        @endforeach
    </div>
</div>
@else
<img src="{{ asset('uploads/'.$about_item->image) }}" alt="{{ $alt ?? '' }}">
@endif
