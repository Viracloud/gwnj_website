@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Settings - Sitemap</h1>
</div>
<p class="text-muted mb-4">
    <code>/sitemap.xml</code> is generated automatically every time it's requested, straight from the current database - any event, post, campaign, team member or page you add, edit or remove in the admin shows up (or disappears) the next time it's read. There is nothing to rebuild or regenerate by hand. The settings below only control which sections are included and how each is prioritised for search engines.
    <a href="{{ route('sitemap') }}" target="_blank" class="fw-semibold">View live sitemap.xml &rarr;</a>
</p>

<form action="{{ route('admin_setting_sitemap_update') }}" method="post">
    @csrf
    <div class="row g-4">
        <div class="col-12 col-xl-12">

            <h6 class="cus-heading">Sections included in the sitemap</h6>
            <div class="card-custom-p mb-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Include</th>
                                <th>Section</th>
                                <th style="width:140px">Priority (0.0&ndash;1.0)</th>
                                <th>Count now</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counts = [
                                    'sitemap_include_static' => 12,
                                    'sitemap_include_pages' => \App\Models\CustomPage::count(),
                                    'sitemap_include_events' => \App\Models\Event::count(),
                                    'sitemap_include_posts' => \App\Models\Post::count(),
                                    'sitemap_include_campaigns' => \App\Models\Campaign::count(),
                                    'sitemap_include_members' => \App\Models\Member::count(),
                                ];
                                $rows = [
                                    'sitemap_include_static' => ['label' => 'Static pages', 'hint' => 'About, Gallery, FAQ, Publications, Events, Campaigns, Team, Blog, Terms, Privacy, Contact, Donate', 'priority' => 'sitemap_priority_static'],
                                    'sitemap_include_pages' => ['label' => 'Custom pages', 'hint' => 'Pages created under Custom Pages', 'priority' => 'sitemap_priority_pages'],
                                    'sitemap_include_events' => ['label' => 'Events', 'hint' => null, 'priority' => 'sitemap_priority_events'],
                                    'sitemap_include_posts' => ['label' => 'Blog posts', 'hint' => null, 'priority' => 'sitemap_priority_posts'],
                                    'sitemap_include_campaigns' => ['label' => 'Campaigns', 'hint' => null, 'priority' => 'sitemap_priority_campaigns'],
                                    'sitemap_include_members' => ['label' => 'Team members', 'hint' => null, 'priority' => 'sitemap_priority_members'],
                                ];
                            @endphp
                            @foreach($rows as $field => $row)
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="{{ $field }}" id="{{ $field }}" value="1" {{ $setting->$field ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td>
                                    <label for="{{ $field }}" class="mb-0 fw-semibold">{{ $row['label'] }}</label>
                                    @if($row['hint'])<div class="text-muted small">{{ $row['hint'] }}</div>@endif
                                </td>
                                <td>
                                    <input type="number" step="0.1" min="0" max="1" name="{{ $row['priority'] }}" value="{{ $setting->{$row['priority']} }}" class="form-control-custom">
                                </td>
                                <td>{{ $counts[$field] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="text-muted small mb-0 mt-2">Unchecking a section removes all its pages from <code>/sitemap.xml</code> immediately - useful if you want search engines to focus on certain content only. Priority is a hint to search engines about relative importance (1.0 = most important); it does not affect ranking directly.</p>
            </div>

            <div class="card-custom-p mb-4">
                <div>
                    <button type="submit" class="btn-admin-primary">Update</button>
                </div>
            </div>

            <h6 class="cus-heading">Live preview - what's in the sitemap right now</h6>
            <div class="card-custom-p mb-4">
                <div class="table-responsive" style="max-height:420px;overflow-y:auto">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>URL</th>
                                <th>Priority</th>
                                <th>Last modified</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($urls as $url)
                            <tr>
                                <td>{{ $url['type'] }}</td>
                                <td class="text-break"><a href="{{ $url['loc'] }}" target="_blank">{{ $url['loc'] }}</a></td>
                                <td>{{ $url['priority'] }}</td>
                                <td>{{ !empty($url['lastmod']) ? \Illuminate\Support\Carbon::parse($url['lastmod'])->format('M j, Y') : '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="text-muted small mb-0 mt-2">{{ $urls->count() }} URLs currently included. This list reflects the database right now - add a new event, post, campaign, team member or page and it will appear here (and in <code>/sitemap.xml</code>) the next time this screen loads, with no extra step.</p>
            </div>

        </div>
    </div>
</form>
@endsection
