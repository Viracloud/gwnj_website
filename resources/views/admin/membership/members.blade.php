@extends('admin.layouts.master')
@section('content')
<div class="page-heading mb-4">
    <h1>Members</h1>
</div>
<p class="text-muted mb-4">All approved members. Members based outside Iran, Afghanistan and Pakistan must pay their annual dues online via PayPal from their dashboard (set the amount under Settings &rarr; PayPal &rarr; Membership Dues) before a card can be issued - members based in those three countries are exempt automatically. This list updates automatically when dues are paid online; use the toggle below for cash/bank payments, corrections, or to override a member's exemption. Use "Edit card details" to set each member's position, occupation and committee - all admin-controlled so members can't self-declare a title. You can also issue a member's digital card directly from here (no member-side request needed) and view/print any issued card.</p>

@php
    $type_labels = ['regular'=>'Regular','founding'=>'Founding','honorary'=>'Honorary','affiliate'=>'Affiliate','organizational'=>'Organizational'];
    $card_labels = ['not_requested'=>'Not requested','requested'=>'Requested','issued'=>'Issued'];
    $card_badge = ['not_requested'=>'bg-secondary','requested'=>'bg-warning','issued'=>'bg-success'];
@endphp

<div class="card-custom-p5">
    <table id="dataTable" class="table-dt stripe hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Photo</th>
                <th>Name</th>
                <th>Email</th>
                <th>Membership ID</th>
                <th>Type</th>
                <th>Country</th>
                <th>Position</th>
                <th>Committee</th>
                <th>Approved</th>
                <th>Dues</th>
                <th>Card</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $m)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    @if($m->photo)
                    <img src="{{ asset('uploads/'.$m->photo) }}" alt="" style="width:36px;height:36px;border-radius:50%;object-fit:cover">
                    @endif
                </td>
                <td>{{ trim($m->name.' '.$m->surname) }}</td>
                <td>{{ $m->email }}</td>
                <td>{{ $m->membership_id }}</td>
                <td>{{ $type_labels[$m->membership_type] ?? $m->membership_type }}</td>
                <td>{{ $m->country ?: '—' }}</td>
                <td>{{ $m->position ?: '—' }}</td>
                <td>{{ $m->committees->pluck('name')->implode(', ') ?: '—' }}</td>
                <td>{{ optional($m->membership_approved_at)->format('d M Y') }}</td>
                <td>
                    @if($m->dues_paid)
                    <span class="badge bg-success">Paid</span>
                    @elseif($m->isDuesExempt())
                    <span class="badge bg-info text-dark">Exempt</span>
                    @else
                    <span class="badge bg-danger">Unpaid</span>
                    @endif
                </td>
                <td><span class="badge {{ $card_badge[$m->card_status] ?? 'bg-secondary' }}">{{ $card_labels[$m->card_status] ?? $m->card_status }}</span></td>
                <td>
                    <div class="d-flex align-items-center gap-1">
                        <a class="btn-icon btn-icon-edit btn-edit-row" data-bs-toggle="modal" data-bs-target="#modal_position_{{ $loop->iteration }}" title="Edit card details"><i class="fa-solid fa-id-badge text-xs"></i></a>
                        <form action="{{ route('admin_membership_toggle_dues', $m->id) }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-sm">{{ $m->dues_paid ? 'Mark unpaid' : 'Mark paid' }}</button>
                        </form>
                        @if($m->cardIsExpired())
                        <span class="badge bg-warning text-dark" title="Expired {{ $m->card_expires_at->format('j M Y') }}">Card expired</span>
                        <form action="{{ route('admin_membership_card_issue_direct', $m->id) }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Renew card (1 year)</button>
                        </form>
                        @elseif($m->card_status == 'issued')
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('admin_membership_card_view', $m->id) }}" target="_blank" title="Valid until {{ optional($m->card_expires_at)->format('j M Y') }}">View / print card</a>
                        @else
                        <form action="{{ route('admin_membership_card_issue_direct', $m->id) }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Issue card</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            <!-- Position Modal -->
            <div class="modal fade" id="modal_position_{{ $loop->iteration }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content modal-content-custom">
                        <div class="modal-header modal-header-custom">
                            <h5 class="modal-title fw-semibold">Card details - {{ trim($m->name.' '.$m->surname) }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body modal-body-custom">
                            <form action="{{ route('admin_membership_update_position', $m->id) }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label class="label-custom">Position (English) <small>- their role at GWJN, e.g. "Country Coordinator - Afghanistan". Shown on the card; if empty, occupation is shown instead.</small></label>
                                    <input type="text" class="form-control-custom" name="position" value="{{ $m->position }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Position (Dari)</label>
                                    <input type="text" class="form-control-custom" name="position_fa" dir="rtl" value="{{ $m->position_fa }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Occupation (English) <small>- their real-world profession, e.g. "Student" or "Human Rights Activist". Only shown on the card when Position above is empty.</small></label>
                                    <input type="text" class="form-control-custom" name="occupation" value="{{ $m->occupation }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Occupation (Dari)</label>
                                    <input type="text" class="form-control-custom" name="occupation_fa" dir="rtl" value="{{ $m->occupation_fa }}">
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Committees <small>- a member can belong to more than one; all are shown on the back of the card</small></label>
                                    @php $m_committee_ids = $m->committees->pluck('id')->all(); @endphp
                                    @foreach($committees as $c)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="committees[]" value="{{ $c->id }}" id="committee_{{ $loop->parent->iteration }}_{{ $c->id }}" {{ in_array($c->id, $m_committee_ids) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="committee_{{ $loop->parent->iteration }}_{{ $c->id }}">{{ $c->name }}</label>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="mb-3">
                                    <label class="label-custom">Dues exemption <small>- country is "{{ $m->country ?: 'not set' }}", auto-detected as {{ $m->isDuesExempt() ? 'exempt' : 'required to pay' }}. Override only for a specific correction.</small></label>
                                    <select class="form-control-custom" name="dues_exempt_override">
                                        <option value="" {{ is_null($m->dues_exempt_override) ? 'selected' : '' }}>Auto-detect from country</option>
                                        <option value="1" {{ $m->dues_exempt_override === true ? 'selected' : '' }}>Force exempt (no dues required)</option>
                                        <option value="0" {{ $m->dues_exempt_override === false ? 'selected' : '' }}>Force required (dues always required)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    @include('admin.partials.extra-locales', ['table' => 'users', 'item' => ($m ?? null), 'fields' => ['position' => 'input', 'occupation' => 'input']])
                                    <button type="submit" class="btn-admin-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // Position Modal -->
            @endforeach
        </tbody>
    </table>
</div>
@endsection
