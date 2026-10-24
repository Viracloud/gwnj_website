@php
    $share_url_enc = urlencode($share_url);
    $share_title_enc = urlencode($share_title ?? '');
@endphp
<div class="share-row">
    <span class="share-label">{{ __('Share:') }}</span>
    <a class="share-btn" target="_blank" rel="noopener" aria-label="Facebook"
       href="https://www.facebook.com/sharer/sharer.php?u={{ $share_url_enc }}"><i class="fab fa-facebook-f"></i></a>
    <a class="share-btn" target="_blank" rel="noopener" aria-label="X / Twitter"
       href="https://twitter.com/intent/tweet?url={{ $share_url_enc }}&text={{ $share_title_enc }}"><i class="fab fa-x-twitter"></i></a>
    <a class="share-btn" target="_blank" rel="noopener" aria-label="WhatsApp"
       href="https://wa.me/?text={{ $share_title_enc }}%20{{ $share_url_enc }}"><i class="fab fa-whatsapp"></i></a>
    <a class="share-btn" target="_blank" rel="noopener" aria-label="LinkedIn"
       href="https://www.linkedin.com/sharing/share-offsite/?url={{ $share_url_enc }}"><i class="fab fa-linkedin-in"></i></a>
    <a class="share-btn" target="_blank" rel="noopener" aria-label="Telegram"
       href="https://t.me/share/url?url={{ $share_url_enc }}&text={{ $share_title_enc }}"><i class="fab fa-telegram"></i></a>
    <a class="share-btn" aria-label="Email"
       href="mailto:?subject={{ $share_title_enc }}&body={{ $share_url_enc }}"><i class="fa-solid fa-envelope"></i></a>
    <button type="button" class="share-btn share-copy" data-copy-url="{{ $share_url }}" aria-label="{{ __('Copy link') }}">
        <i class="fa-solid fa-link"></i>
    </button>
</div>
