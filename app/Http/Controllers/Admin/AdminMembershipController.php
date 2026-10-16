<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\Websitemail;
use App\Models\MemberCategory;
use App\Models\MembershipInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminMembershipController extends Controller
{
    public function applications()
    {
        $applications = User::where('membership_status', 'pending')->orderBy('membership_applied_at', 'asc')->get();
        return view('admin.membership.applications', compact('applications'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'membership_type' => 'required|in:regular,founding,honorary,affiliate,organizational',
        ]);

        $user = User::findOrFail($id);
        $year = now()->format('Y');
        $sequence = User::whereNotNull('membership_id')->count() + 1;
        $membership_id = 'GWJN-' . $year . '-' . str_pad($sequence, 6, '0', STR_PAD_LEFT);
        while (User::where('membership_id', $membership_id)->exists()) {
            $sequence++;
            $membership_id = 'GWJN-' . $year . '-' . str_pad($sequence, 6, '0', STR_PAD_LEFT);
        }

        $user->membership_status = 'approved';
        $user->membership_type = $request->membership_type;
        $user->membership_id = $membership_id;
        $user->membership_approved_at = now();
        $user->save();

        $subject = 'Your GWJN membership application has been approved';
        $message = "Congratulations, {$user->name}!<br>Your GWJN membership application has been approved. Your membership ID is <b>{$membership_id}</b>.<br>You can now log in and request your digital membership card from your dashboard.";
        \safe_mail($user->email, $subject, $message);

        return redirect()->back()->with('success', 'Application approved.');
    }

    public function reject(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->membership_status = 'rejected';
        $user->membership_rejected_reason = $request->reason;
        $user->save();

        $subject = 'Your GWJN membership application';
        $message = "Dear {$user->name},<br>Thank you for your interest in GWJN. After review, we are unable to approve your membership application at this time.";
        if ($request->reason) {
            $message .= "<br><br>Reason: " . e($request->reason);
        }
        \safe_mail($user->email, $subject, $message);

        return redirect()->back()->with('success', 'Application rejected.');
    }

    public function members()
    {
        $members = User::with('committees')->where('membership_status', 'approved')->orderBy('membership_approved_at', 'desc')->get();
        $committees = MemberCategory::where('name', 'like', '%Committee%')->orderBy('sort_order')->get();
        return view('admin.membership.members', compact('members', 'committees'));
    }

    public function toggle_dues($id)
    {
        $user = User::findOrFail($id);
        $user->dues_paid = !$user->dues_paid;
        $user->save();

        return redirect()->back()->with('success', 'Updated.');
    }

    public function update_position(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->position = $request->position;
        $user->position_sv = $request->position_sv;
        $user->occupation = $request->occupation;
        $user->occupation_sv = $request->occupation_sv;
        $user->dues_exempt_override = $request->dues_exempt_override === '' || $request->dues_exempt_override === null
            ? null
            : (bool) $request->dues_exempt_override;
        $user->save();

        $user->committees()->sync($request->input('committees', []));

        return redirect()->back()->with('success', 'Card details updated.');
    }

    public function card_requests()
    {
        $requests = User::where('card_status', 'requested')->orderBy('card_requested_at', 'asc')->get();
        return view('admin.membership.card_requests', compact('requests'));
    }

    public function card_issue($id)
    {
        $user = User::where('id', $id)->where('card_status', 'requested')->firstOrFail();

        if (!$user->canReceiveCard()) {
            return redirect()->back()->with('error', 'This member has not paid their annual dues yet (not exempt) - the card cannot be issued.');
        }

        $user->card_status = 'issued';
        $user->card_issued_at = now();
        $user->card_expires_at = now()->addYear();
        $user->verification_token = Str::random(40);
        $user->save();

        $subject = 'Your GWJN digital membership card is ready';
        $message = "Dear {$user->name},<br>Your digital membership card has been issued. You can view and print it from your dashboard: <a href=\"" . route('membership_card') . "\">" . route('membership_card') . "</a>";
        \safe_mail($user->email, $subject, $message);

        return redirect()->back()->with('success', 'Card issued.');
    }

    public function card_decline($id)
    {
        $user = User::where('id', $id)->where('card_status', 'requested')->firstOrFail();
        $user->card_status = 'not_requested';
        $user->card_requested_at = null;
        $user->save();

        return redirect()->back()->with('success', 'Card request declined.');
    }

    public function card_issue_direct($id)
    {
        $user = User::where('id', $id)->where('membership_status', 'approved')->where('card_status', '!=', 'issued')->firstOrFail();

        if (!$user->canReceiveCard()) {
            return redirect()->back()->with('error', 'This member has not paid their annual dues yet (not exempt) - the card cannot be issued.');
        }

        $user->card_status = 'issued';
        $user->card_requested_at = $user->card_requested_at ?? now();
        $user->card_issued_at = now();
        $user->card_expires_at = now()->addYear();
        $user->verification_token = Str::random(40);
        $user->save();

        $subject = 'Your GWJN digital membership card is ready';
        $message = "Dear {$user->name},<br>Your digital membership card has been issued. You can view and print it from your dashboard: <a href=\"" . route('membership_card') . "\">" . route('membership_card') . "</a>";
        \safe_mail($user->email, $subject, $message);

        return redirect()->back()->with('success', 'Card issued.');
    }

    public function card_view($id)
    {
        $user = User::where('id', $id)->where('card_status', 'issued')->firstOrFail();
        return view('admin.membership.card_view', compact('user'));
    }

    public function invitations()
    {
        $invitations = MembershipInvitation::orderBy('id', 'desc')->get();
        return view('admin.membership.invitations', compact('invitations'));
    }

    public function invite_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'note' => 'nullable|string',
        ]);

        $invitation = new MembershipInvitation();
        $invitation->name = $request->name;
        $invitation->email = $request->email;
        $invitation->note = $request->note;
        $invitation->token = Str::random(40);
        $invitation->status = 'pending';
        $invitation->invited_by = Auth::guard('admin')->id();
        $invitation->save();

        $link = url('/invite/' . $invitation->token);
        $subject = 'You are invited to join GWJN';
        $message = "Dear {$invitation->name},<br>The Global Women's Justice Network invites you to become a member.<br>Please follow this link to accept and complete your registration: <a href=\"{$link}\">{$link}</a>";
        \safe_mail($invitation->email, $subject, $message);

        return redirect()->back()->with('success', 'Invitation sent.');
    }

    public function invite_cancel($id)
    {
        $invitation = MembershipInvitation::where('id', $id)->where('status', 'pending')->first();
        if ($invitation) {
            $invitation->status = 'cancelled';
            $invitation->save();
        }

        return redirect()->back()->with('success', 'Invitation cancelled.');
    }
}
