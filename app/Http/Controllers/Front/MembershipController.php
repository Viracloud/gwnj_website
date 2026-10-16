<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Mail\Websitemail;
use App\Models\Donation;
use App\Models\MembershipInvitation;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class MembershipController extends Controller
{
    public function invite_show($token)
    {
        $invitation = MembershipInvitation::where('token', $token)->where('status', 'pending')->first();
        if (!$invitation) {
            abort(404);
        }
        return view('front.invite', compact('invitation'));
    }

    public function invite_submit(Request $request, $token)
    {
        $invitation = MembershipInvitation::where('token', $token)->where('status', 'pending')->first();
        if (!$invitation) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'membership_type' => 'required|in:regular,founding,honorary,affiliate,organizational',
            'agree_bylaws' => 'required',
        ]);

        $user = User::where('email', $invitation->email)->first();
        if (!$user) {
            $user = new User();
            $user->email = $invitation->email;
        }
        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->email_verified_at = $user->email_verified_at ?? now();
        $user->membership_type = $request->membership_type;
        if ($user->membership_status === 'none' || !$user->membership_status) {
            $user->membership_status = 'pending';
            $user->membership_source = 'invited';
            $user->membership_applied_at = now();
        }
        $user->save();

        $invitation->status = 'used';
        $invitation->save();

        return redirect()->route('login')->with('success', is_rtl()
            ? 'حساب شما ساخته شد. اکنون وارد شوید.'
            : 'Your account has been created. You can log in now.');
    }

    public function card_request(Request $request)
    {
        $user = Auth::guard('web')->user();

        if (!$user->isApprovedMember() || !$user->profileIsComplete() || $user->card_status !== 'not_requested') {
            return redirect()->route('dashboard')->with('error', is_rtl()
                ? 'درخواست کارت در حال حاضر ممکن نیست.'
                : 'A card request is not available right now.');
        }

        if (!$user->canReceiveCard()) {
            return redirect()->route('dashboard')->with('error', is_rtl()
                ? 'برای درخواست کارت عضویت، ابتدا حق عضویت سالانه را پرداخت کنید.'
                : 'Please pay your annual membership dues before requesting a card.');
        }

        $user->card_status = 'requested';
        $user->card_requested_at = now();
        $user->save();

        return redirect()->route('dashboard')->with('success', is_rtl()
            ? 'درخواست کارت عضویت شما ثبت شد و در انتظار تأیید مدیر است.'
            : 'Your membership card request has been submitted and is awaiting admin approval.');
    }

    public function card()
    {
        $user = Auth::guard('web')->user();
        if ($user->card_status !== 'issued') {
            return redirect()->route('dashboard');
        }
        return view('front.membership-card-page', compact('user'));
    }

    public function verify($token)
    {
        $user = User::where('verification_token', $token)->where('card_status', 'issued')->first();
        return view('front.verify', compact('user'));
    }

    public function dues_pay()
    {
        $user = Auth::guard('web')->user();
        $setting = Setting::where('id', 1)->first();

        if (!$user->isApprovedMember() || $user->dues_paid || !$setting->membership_dues_amount) {
            return redirect()->route('dashboard');
        }
        if (!$setting->paypal_client_id || !$setting->paypal_client_secret) {
            return redirect()->route('dashboard')->with('error', is_rtl()
                ? 'پرداخت آنلاین در حال حاضر در دسترس نیست.'
                : 'Online payment is not available right now.');
        }

        $this->configure_paypal($setting);
        $currency = $setting->paypal_currency ?: 'USD';

        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $response = $provider->createOrder([
                'intent' => 'CAPTURE',
                'application_context' => [
                    'brand_name' => config('app.name'),
                    'return_url' => route('membership_dues_success'),
                    'cancel_url' => route('membership_dues_cancel'),
                ],
                'purchase_units' => [
                    [
                        'description' => 'GWJN membership dues - ' . $user->membership_id,
                        'amount' => [
                            'currency_code' => $currency,
                            'value' => number_format((float) $setting->membership_dues_amount, 2, '.', ''),
                        ],
                    ],
                ],
            ]);
        } catch (\Throwable $e) {
            return redirect()->route('dashboard')->with('error', is_rtl()
                ? 'اتصال به پی‌پل ممکن نشد. لطفاً بعداً دوباره تلاش کنید.'
                : 'We could not reach PayPal. Please try again later.');
        }

        if (isset($response['id']) && $response['id'] !== null) {
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect()->away($link['href']);
                }
            }
        }

        return redirect()->route('membership_dues_cancel');
    }

    public function dues_success(Request $request)
    {
        $user = Auth::guard('web')->user();
        if (!$request->has('token')) {
            return redirect()->route('membership_dues_cancel');
        }

        $setting = Setting::where('id', 1)->first();
        $this->configure_paypal($setting);

        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request->token);
        } catch (\Throwable $e) {
            return redirect()->route('membership_dues_cancel');
        }

        if (isset($response['status']) && $response['status'] === 'COMPLETED') {
            $capture = $response['purchase_units'][0]['payments']['captures'][0] ?? [];

            $donation = new Donation();
            $donation->donor_name = $user->name;
            $donation->donor_email = $user->email;
            $donation->message = 'Membership dues - ' . $user->membership_id;
            $donation->amount = $capture['amount']['value'] ?? 0;
            $donation->currency = $capture['amount']['currency_code'] ?? ($setting->paypal_currency ?: 'USD');
            $donation->payment_method = 'PayPal';
            $donation->transaction_id = $capture['id'] ?? ($response['id'] ?? null);
            $donation->status = 'Completed';
            $donation->save();

            $user->dues_paid = true;
            $user->save();

            return redirect()->route('dashboard')->with('success', is_rtl()
                ? 'حق عضویت شما با موفقیت پرداخت شد. سپاسگزاریم!'
                : 'Your membership dues were paid successfully. Thank you!');
        }

        return redirect()->route('membership_dues_cancel');
    }

    public function dues_cancel()
    {
        return redirect()->route('dashboard')->with('error', is_rtl()
            ? 'پرداخت حق عضویت تکمیل نشد.'
            : 'Your dues payment was not completed.');
    }

    private function configure_paypal($setting)
    {
        $mode = in_array($setting->paypal_mode, ['sandbox', 'live']) ? $setting->paypal_mode : 'sandbox';

        config([
            'paypal.mode' => $mode,
            'paypal.' . $mode . '.client_id' => $setting->paypal_client_id,
            'paypal.' . $mode . '.client_secret' => $setting->paypal_client_secret,
            'paypal.currency' => $setting->paypal_currency ?: 'USD',
        ]);
    }
}
