@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Settings - SEO</h1>
</div>
<p class="text-muted mb-4">Titles and descriptions are generated automatically for every page - events, posts, campaigns, team members and custom pages each use their own content (falling back to their own SEO fields on the edit form if you set them), so no two pages share the same title. The settings below only control the sitewide defaults and social-sharing behaviour.</p>

<form action="{{ route('admin_setting_seo_update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-12 col-xl-12">

            <h6 class="cus-heading">Search engine visibility</h6>
            <div class="card-custom-p mb-4">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="seo_robots_indexable" id="seo_robots_indexable" value="1" {{ $setting->seo_robots_indexable ? 'checked' : '' }}>
                    <label class="form-check-label" for="seo_robots_indexable">Allow search engines to index this site</label>
                </div>
                <p class="text-muted mb-0 small">
                    Leave this <b>unchecked</b> while the site is still being built or is not ready for the public - it tells Google and other search engines not to list any page yet. When this is off, <code>/robots.txt</code> blocks the whole site. Turn it on only when you're ready to go live; <code>/sitemap.xml</code> is generated automatically and is only advertised to search engines once this is on.
                </p>
            </div>

            <h6 class="cus-heading">Default title &amp; description</h6>
            <div class="card-custom-p mb-4">
                <div class="mb-3">
                    <label class="label-custom">Title template</label>
                    <input type="text" name="seo_title_template" value="{{ $setting->seo_title_template }}" class="form-control-custom" placeholder="%s — Global Women's Justice Network">
                    <small class="text-muted">Use <code>%s</code> where the page's own title should appear. Leave empty to use the default shown above.</small>
                </div>
                <div class="mb-3">
                    <label class="label-custom">Default description (English)</label>
                    <textarea name="seo_default_description" class="form-control-custom" rows="3">{{ $setting->seo_default_description }}</textarea>
                    <small class="text-muted">Used only on pages that don't already have their own description (e.g. search results, tag pages).</small>
                </div>
                <div class="mb-3">
                    <label class="label-custom">Default description (دری)</label>
                    <textarea dir="rtl" name="seo_default_description_fa" class="form-control-custom" rows="3">{{ $setting->seo_default_description_fa }}</textarea>
                </div>
            </div>

            <h6 class="cus-heading">Social sharing (Open Graph / Twitter Card)</h6>
            <div class="card-custom-p mb-4">
                <div class="mb-3">
                    <label class="label-custom">Default share image</label>
                    @if($setting->seo_og_image)
                    <div class="mb-2"><img src="{{ asset('uploads/'.$setting->seo_og_image) }}" alt="" style="max-width:280px;border-radius:8px"></div>
                    @endif
                    <input type="file" name="seo_og_image" class="form-control-custom" accept="image/*">
                    <small class="text-muted">Shown when a page is shared on Facebook, X/Twitter, WhatsApp, etc. Recommended size 1200×630px. Individual events, posts and campaigns use their own cover photo instead when they have one.</small>
                </div>
            </div>

            <h6 class="cus-heading">Verification &amp; analytics</h6>
            <div class="card-custom-p mb-4">
                <div class="mb-3">
                    <label class="label-custom">Google Search Console verification code</label>
                    <input type="text" name="seo_google_verification" value="{{ $setting->seo_google_verification }}" class="form-control-custom" placeholder="content value only, from the meta tag Google gives you">
                </div>
                <div class="mb-3">
                    <label class="label-custom">Google Analytics / GA4 measurement ID</label>
                    <input type="text" name="seo_analytics_id" value="{{ $setting->seo_analytics_id }}" class="form-control-custom" placeholder="G-XXXXXXXXXX">
                    <small class="text-muted">Optional. Leave empty if you don't want visitor analytics tracking.</small>
                </div>
            </div>

            <div class="card-custom-p mb-4">
                <div>
                    @include('admin.partials.extra-locales', ['table' => 'settings', 'item' => ($setting ?? null), 'fields' => ['seo_default_description' => 'textarea']])
                    <button type="submit" class="btn-admin-primary">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
