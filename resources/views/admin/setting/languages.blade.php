@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Languages</h1>
    <p>Which website languages are published, and how much of the content is translated.</p>
</div>

<div class="card-custom-p5 mb-4">
    <table class="table align-middle mb-0">
        <thead>
            <tr><th>Language</th><th>Address</th><th>Status</th><th>Content translated</th></tr>
        </thead>
        <tbody>
        @foreach($languages as $code => $lang)
            <tr>
                <td><b>{{ $lang['native'] }}</b> <span class="text-muted">({{ $lang['name'] }})</span></td>
                <td><code>/{{ $code }}/</code></td>
                <td>
                    @if($code === base_locale())
                        <span class="badge bg-primary">Main language</span>
                    @elseif($lang['enabled'])
                        <span class="badge bg-success">Published</span>
                    @elseif($lang['prepared'])
                        <span class="badge bg-warning text-dark">Being prepared (admin only)</span>
                    @else
                        <span class="badge bg-secondary">Off</span>
                    @endif
                </td>
                <td style="min-width:220px">
                    @if($code === base_locale())
                        <span class="text-muted">—</span>
                    @else
                        <div class="d-flex align-items-center gap-2">
                            <div style="flex:1;height:8px;background:var(--gray-100);border-radius:6px;overflow:hidden">
                                <div style="height:100%;width:{{ $lang['progress']['percent'] }}%;background:var(--primary-500)"></div>
                            </div>
                            <small><b>{{ $lang['progress']['percent'] }}%</b> <span class="text-muted">({{ $lang['progress']['filled'] }} / {{ $lang['progress']['total'] }})</span></small>
                        </div>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="card-custom-p" style="background:var(--gray-50)">
    <h5 class="fw-semibold mb-2"><i class="fa-solid fa-circle-info me-1"></i>How languages are switched on</h5>
    <p class="mb-2" style="font-size:.9rem">Languages are controlled by the developer in the server's <code>.env</code> file (not from this panel), then <code>php artisan config:clear</code>:</p>
    <ol style="font-size:.9rem;line-height:1.8">
        <li><b>Prepare</b> — <code>SITE_LOCALES_PREPARE=fr,de</code><br>Translation fields for these languages appear in every form here, marked “not published yet”. The public website does not change.</li>
        <li><b>Publish</b> — <code>SITE_LOCALES=en,fa,fr,de</code><br>The languages appear in the language menu, at <code>/fr/</code> and <code>/de/</code>, in the sitemap and for Google.</li>
    </ol>
    <p class="mb-0 text-muted" style="font-size:.85rem">Anything not translated yet is shown in English. Interface texts (buttons, messages) come from <code>lang/fr.json</code> and <code>lang/de.json</code>.</p>
</div>
@endsection
