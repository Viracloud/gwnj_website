<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Mail\Websitemail;
use App\Models\User;
use App\Models\Admin;
use App\Models\Slider;
use App\Models\Photo;
use App\Models\Faq;
use App\Models\AboutItem;
use App\Models\Event;
use App\Models\EventFaq;
use App\Models\EventCategory;
use App\Models\CampaignCategory;
use App\Models\Member;
use App\Models\MemberAward;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Comment;
use App\Models\Reply;
use App\Models\PageHomeItem;
use App\Models\PageAboutItem;
use App\Models\PageEventItem;
use App\Models\PageBlogItem;
use App\Models\PageTeamItem;
use App\Models\PageGalleryItem;
use App\Models\PageFaqItem;
use App\Models\PageLoginItem;
use App\Models\PageTermsItem;
use App\Models\PagePrivacyItem;
use App\Models\PageContactItem;
use App\Models\PageDonateItem;
use App\Models\Donation;
use App\Models\Setting;
use App\Models\Subscriber;
use App\Models\CustomPage;
use App\Models\Publication;
use App\Models\PublicationCategory;
use App\Models\Campaign;
use App\Models\MenuItem;
use App\Models\Partner;
use Illuminate\Support\Facades\Validator;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class FrontController extends Controller
{
    /** Old-style language switch (/lang/xx): go to the same page in that language. */
    public function switch_lang($locale)
    {
        if (!in_array($locale, enabled_locales(), true)) {
            return redirect('/');
        }
        session()->put('site_lang', $locale);
        $back = url()->previous();
        $base = rtrim(url('/'), '/');
        return redirect(str_starts_with($back, $base) && !str_contains($back, '/admin') ? localized_url($locale, $back) : url($locale));
    }

    public function home()
    {
        $sliders = Slider::orderBy('id','asc')->get();
        $about_item = AboutItem::where('id',1)->first();
        $events = Event::where('show_on_home','Yes')->orderBy('event_date','asc')->get();
        $members = Member::with('category')->where('show_on_home','Yes')->get()
            ->sortBy(fn($m) => [optional($m->category)->sort_order ?? 9999, $m->sort_order, $m->id])->values();
        $posts = Post::where('show_on_home','Yes')->orderBy('id','desc')->take(3)->get();
        $photos = Photo::orderBy('id','desc')->take(4)->get();
        $publications = Publication::where('status','Show')->orderBy('published_date','desc')->take(3)->get();
        $page_data = PageHomeItem::where('id',1)->first();
        return view('front.home', compact('sliders', 'about_item', 'events', 'members', 'posts', 'photos', 'publications', 'page_data'));
    }

    public function partners()
    {
        $partners = Partner::where('status', 'Show')->orderBy('sort_order')->orderBy('id')->get();
        return view('front.partners', compact('partners'));
    }

    public function publications(Request $request)
    {
        $publication_categories = PublicationCategory::orderBy('name','asc')->get();

        $query = Publication::where('status','Show')->orderBy('published_date','desc');
        if ($request->category) {
            $category = PublicationCategory::where('slug', $request->category)->first();
            if ($category) {
                $query->where('publication_category_id', $category->id);
            }
        }
        $publications = $query->paginate(8)->withQueryString();

        return view('front.publications', compact('publications', 'publication_categories'));
    }

    public function about()
    {
        $about_item = AboutItem::where('id',1)->first();
        $page_data = PageAboutItem::where('id',1)->first();
        $members = Member::with('category')->where('show_on_home','Yes')->get()
            ->sortBy(fn($m) => [optional($m->category)->sort_order ?? 9999, $m->sort_order, $m->id])->values();
        $about_group = MenuItem::where('label', 'About')->whereNull('parent_id')->first();
        $about_links = $about_group ? $about_group->visibleChildren()->get() : collect();
        return view('front.about', compact('about_item', 'page_data', 'members', 'about_links'));
    }

    public function login()
    {
        if(Auth::guard('web')->check()) {
            return redirect()->route('dashboard');
        }
        $page_data = PageLoginItem::where('id',1)->first();
        return view('front.login', compact('page_data'));
    }

    public function login_submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if email and password are correct
        $user = User::where('email',$request->email)->where('email_verified_at','!=',null)->first();
        if(!$user) {
            return redirect()->route('login')->with('error','The information you entered is incorrect! Please try again!');
        }

        $check = $request->all();
        $data = [
            'email' => $check['email'],
            'password' => $check['password'],
        ];

        if(Auth::guard('web')->attempt($data)) {
            return redirect()->route('dashboard')->with('success','You are logged in successfully!');
        } else {
            return redirect()->route('login')->with('error','The information you entered is incorrect! Please try again!');
        }
    }

    public function register()
    {
        if(Auth::guard('web')->check()) {
            return redirect()->route('dashboard');
        }
        $page_data = PageLoginItem::where('id',1)->first();
        return view('front.register', compact('page_data'));
    }

    public function register_submit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
            'membership_type' => 'required|in:regular,founding,honorary,affiliate,organizational',
            'agree_bylaws' => 'required',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->membership_type = $request->membership_type;
        $token = secure_token();
        $user->remember_token = $token;
        $user->save();

        $verification_link = url('register_verify/'.$token.'/'.$request->email);
        $subject = "Registration Verification";
        $message = "To complete registration, please click on the link below:<br>";
        $message .= "<a href='".$verification_link."'>".$verification_link."</a>";

        $mail_sent = \safe_mail($request->email, $subject, $message);

        if (!$mail_sent) {
            // Mail delivery is down - don't leave the applicant permanently stuck
            // unable to log in just because the confirmation email couldn't go out.
            $user->email_verified_at = now();
            $user->remember_token = '';
            $user->membership_status = 'pending';
            $user->membership_source = 'self';
            $user->membership_applied_at = now();
            $user->save();

            return redirect()->route('login')->with('success', __('Your membership application has been submitted. You can log in now.'));
        }

        return redirect()->back()->with('success','Your registration is completed. Please check your email for verification. If you do not find the email in your inbox, please check your spam folder.');
    }

    public function register_verify($token,$email)
    {
        $user = User::where('remember_token',$token)->where('email',$email)->first();
        if(!$user) {
            return redirect()->route('login');
        }
        $user->email_verified_at = now();
        $user->remember_token = '';
        if ($user->membership_status === 'none') {
            $user->membership_status = 'pending';
            $user->membership_source = 'self';
            $user->membership_applied_at = now();
        }
        $user->save();

        return redirect()->route('login')->with('success', 'Your email is verified. You can login now.');
    }

    public function forget_password()
    {
        if(Auth::guard('web')->check()) {
            return redirect()->route('dashboard');
        }
        $page_data = PageLoginItem::where('id',1)->first();
        return view('front.forget-password', compact('page_data'));
    }

    public function forget_password_submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $sent_message = 'If an account exists for this email, we have sent a password reset link to it. Please check your inbox and your spam folder.';

        // Same answer whether or not the account exists, so the form can't be used to discover members' emails.
        $user = User::where('email',$request->email)->first();
        if(!$user) {
            return redirect()->back()->with('success', $sent_message);
        }

        $token = secure_token(60);
        $user->remember_token = $token;
        $user->update();

        $reset_link = url('reset-password/'.$token.'/'.$request->email);
        $subject = "Password Reset";
        $message = "To reset password, please click on the link below:<br>";
        $message .= "<a href='".$reset_link."'>".$reset_link."</a>";

        \safe_mail($request->email, $subject, $message);

        return redirect()->back()->with('success', $sent_message);
    }

    public function reset_password($token,$email)
    {
        $user = User::where('email',$email)->where('remember_token',$token)->first();
        if(!$user || token_expired($token)) {
            return redirect()->route('login')->with('error','This password reset link is invalid or has expired. Please request a new one.');
        }
        $page_data = PageLoginItem::where('id',1)->first();
        return view('front.reset-password', compact('token','email','page_data'));
    }

    public function reset_password_submit(Request $request, $token, $email)
    {
        $request->validate([
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ]);

        $user = User::where('email',$email)->where('remember_token',$token)->first();
        if(!$user || token_expired($token)) {
            return redirect()->route('login')->with('error','This password reset link is invalid or has expired. Please request a new one.');
        }
        $user->password = Hash::make($request->password);
        $user->remember_token = "";
        $user->update();

        return redirect()->route('login')->with('success','Password reset is successful. You can login now.');
    }

    public function gallery()
    {
        $photos = Photo::orderBy('id','asc')->paginate(8);
        $page_data = PageGalleryItem::where('id',1)->first();
        return view('front.gallery', compact('photos', 'page_data'));
    }

    public function faq()
    {
        $faqs = Faq::orderBy('id','asc')->paginate(8);
        $page_data = PageFaqItem::where('id',1)->first();
        return view('front.faq', compact('faqs', 'page_data'));
    }

    public function events(Request $request)
    {
        // Only categories that actually have events appear as filters.
        $event_categories = EventCategory::whereHas('events')->orderBy('name')->get();

        // Upcoming events first (soonest first), then past events (most recent first).
        $today = now()->toDateString();
        $query = Event::with('category')
            ->orderByRaw('CASE WHEN event_date >= ? THEN 0 ELSE 1 END', [$today])
            ->orderByRaw('CASE WHEN event_date >= ? THEN event_date END ASC', [$today])
            ->orderBy('event_date', 'desc');
        if ($request->category) {
            $category = EventCategory::where('slug', $request->category)->first();
            if ($category) {
                $query->where('event_category_id', $category->id);
            }
        }
        $events = $query->paginate(6)->withQueryString();
        $page_data = PageEventItem::where('id',1)->first();
        return view('front.events', compact('events', 'page_data', 'event_categories'));
    }

    public function event($slug)
    {
        $event = where_slug(Event::query(), $slug)->first();
        if(!$event) {
            return redirect()->route('events');
        }
        if (slug_for($event) !== $slug) {
            return redirect()->route('event', slug_for($event), 301);
        }
        set_model_alternates('event', $event);
        $all_faqs = EventFaq::where('event_id',$event->id)->orderBy('id','asc')->get();
        $page_data = PageEventItem::where('id',1)->first();
        return view('front.event', compact('event', 'all_faqs', 'page_data'));
    }

    public function campaigns(Request $request)
    {
        $query = Campaign::with('category')->orderBy('sort_order')->orderBy('id','desc');
        if ($request->status && in_array($request->status, ['active','won','closed'])) {
            $query->where('status', $request->status);
        }
        if ($request->category) {
            $category = CampaignCategory::where('slug', $request->category)->first();
            if ($category) {
                $query->where('campaign_category_id', $category->id);
            }
        }
        $campaigns = $query->paginate(9)->withQueryString();
        // Only categories that actually have campaigns appear as filters.
        $campaign_categories = CampaignCategory::whereHas('campaigns')->orderBy('name')->get();

        return view('front.campaigns', compact('campaigns', 'campaign_categories'));
    }

    public function campaign($slug)
    {
        $campaign = where_slug(Campaign::query(), $slug)->first();
        if (!$campaign) {
            return redirect()->route('campaigns');
        }
        if (slug_for($campaign) !== $slug) {
            return redirect()->route('campaign', slug_for($campaign), 301);
        }
        set_model_alternates('campaign', $campaign);
        $other_campaigns = Campaign::where('id','!=',$campaign->id)->orderBy('sort_order')->take(3)->get();
        return view('front.campaign', compact('campaign', 'other_campaigns'));
    }

    public function members()
    {
        $categories = \App\Models\MemberCategory::with('members')->orderBy('sort_order')->orderBy('id')->get()
            ->filter(fn($c) => $c->members->count())->values();
        $uncategorized = Member::whereNull('member_category_id')->orderBy('sort_order')->orderBy('id')->get();
        $page_data = PageTeamItem::where('id',1)->first();
        return view('front.members', compact('categories', 'uncategorized', 'page_data'));
    }

    public function member($slug)
    {
        $member = where_slug(Member::query(), $slug)->first();
        if(!$member) {
            return redirect()->route('members');
        }
        if (slug_for($member) !== $slug) {
            return redirect()->route('member', slug_for($member), 301);
        }
        set_model_alternates('member', $member);
        $all_awards = MemberAward::where('member_id',$member->id)->orderBy('id','asc')->get();
        $page_data = PageTeamItem::where('id',1)->first();
        return view('front.member', compact('member', 'all_awards', 'page_data'));
    }

    public function blog()
    {
        $posts = Post::orderBy('id', 'desc')->paginate(6);
        $page_data = PageBlogItem::where('id',1)->first();
        return view('front.blog', compact('posts', 'page_data'));
    }

    public function post($slug)
    {
        $post = where_slug(Post::query(), $slug)->first();
        if(!$post) {
            return redirect()->route('blog');
        }
        if (slug_for($post) !== $slug) {
            return redirect()->route('post', slug_for($post), 301);
        }
        set_model_alternates('post', $post);
        $recent_posts = Post::orderBy('id', 'desc')->take(3)->get();
        $post_categopries = PostCategory::withCount('posts')->orderBy('name', 'asc')->get();
        $total_approved_comments = Comment::where('post_id', $post->id)->where('status', 'Approved')->count();
        $comments = Comment::where('post_id', $post->id)->where('status', 'Approved')->orderBy('id', 'desc')->get();
        $page_data = PageBlogItem::where('id',1)->first();
        return view('front.post', compact('post', 'recent_posts', 'post_categopries', 'total_approved_comments', 'comments', 'page_data'));
    }

    public function category($slug)
    {
        $category = where_slug(PostCategory::query(), $slug)->first();
        if(!$category) {
            return redirect()->route('blog');
        }
        if (slug_for($category) !== $slug) {
            return redirect()->route('category', slug_for($category), 301);
        }
        set_model_alternates('category', $category);
        $posts = Post::where('post_category_id', $category->id)->orderBy('id', 'desc')->paginate(6);
        return view('front.category', compact('category', 'posts'));
    }

    public function archive($month)
    {
        $posts = Post::whereMonth('created_at', date('m', strtotime($month)))->orderBy('id', 'desc')->paginate(6);
        return view('front.archive', compact('month', 'posts'));
    }

    public function tag($tag)
    {
        $posts = Post::where('tags', 'LIKE', '%'.$tag.'%')->orderBy('id', 'desc')->paginate(6);
        return view('front.tag', compact('tag', 'posts'));
    }

    public function search(Request $request)
    {
        $search_text = $request->search_text;
        $posts = Post::where('title', 'LIKE', '%' . $search_text . '%')
                        ->orWhere('description', 'LIKE', '%' . $search_text . '%')
                        ->orderBy('id','desc')
                        ->paginate(6);
        return view('front.search', compact('posts', 'search_text'));
    }

    public function comment_store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'comment' => 'required',
        ]);

        $obj = new Comment();
        $obj->post_id = $request->post_id;
        $obj->name = $request->name;
        $obj->email = $request->email;
        $obj->comment = $request->comment;
        $obj->status = 'Pending';
        $obj->save();

        // Send email to admin as notification
        $admin_data = Admin::where('id',1)->first();
        $admin_email = $admin_data->email;

        $post_title = Post::where('id', $request->post_id)->first()->title;

        $subject = "New Comment Submitted";
        $message = "<b>Post Title:</b><br>".e($post_title)."<br><br>";
        $message .= "<b>Name:</b><br>".e($request->name)."<br><br>";
        $message .= "<b>Email:</b><br>".e($request->email)."<br><br>";
        $message .= "<b>Comment:</b><br>".nl2br(e($request->comment))."<br><br>";

        \safe_mail($admin_email, $subject, $message);

        return redirect()->back()->with('success', 'Comment submitted successfully and is pending for the admin approval.');
    }

    public function reply_store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'reply' => 'required',
        ]);

        $obj = new Reply();
        $obj->post_id = $request->post_id;
        $obj->comment_id = $request->comment_id;
        $obj->name = $request->name;
        $obj->email = $request->email;
        $obj->reply = $request->reply;
        $obj->reply_by = 'User';
        $obj->status = 'Pending';
        $obj->save();

        // Send email to admin as notification
        $admin_data = Admin::where('id',1)->first();
        $admin_email = $admin_data->email;

        $post_title = Post::where('id', $request->post_id)->first()->title;

        $subject = "New Reply Submitted";
        $message = "<b>Post Title:</b><br>".e($post_title)."<br><br>";
        $message .= "<b>Name:</b><br>".e($request->name)."<br><br>";
        $message .= "<b>Email:</b><br>".e($request->email)."<br><br>";
        $message .= "<b>Reply:</b><br>".nl2br(e($request->reply))."<br><br>";

        \safe_mail($admin_email, $subject, $message);

        return redirect()->back()->with('success', 'Reply submitted successfully and is pending for the admin approval.');
    }

    public function terms()
    {
        $page_data = PageTermsItem::where('id',1)->first();
        return view('front.terms', compact('page_data'));
    }
    public function privacy()
    {
        $page_data = PagePrivacyItem::where('id',1)->first();
        return view('front.privacy', compact('page_data'));
    }
    public function contact()
    {
        $page_data = PageContactItem::where('id',1)->first();
        return view('front.contact', compact('page_data'));
    }

    public function contact_submit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        $subject = "New Contact Message";
        $message = "<b>Name:</b><br>".e($request->name)."<br><br>";
        $message .= "<b>Email:</b><br>".e($request->email)."<br><br>";
        $message .= "<b>Message:</b><br>".nl2br(e($request->message))."<br><br>";

        $admin_data = Admin::where('id',1)->first();
        $admin_email = $admin_data->email;

        \safe_mail($admin_email, $subject, $message);

        return redirect()->back()->with('success','Your message is sent successfully. We will contact you soon.');
    }

    public function donate()
    {
        $page_data = PageDonateItem::where('id',1)->first();
        $raised = Donation::where('status','Completed')->sum('amount');
        return view('front.donate', compact('page_data','raised'));
    }

    public function donate_submit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'amount' => 'required|numeric|min:1',
        ]);

        $setting = Setting::where('id',1)->first();
        $currency = $setting->paypal_currency ?: 'USD';

        if(!$setting->paypal_client_id || !$setting->paypal_client_secret) {
            return redirect()->back()->with('error','Online donation is not available right now. Please try again later.');
        }

        $this->configure_paypal($setting);

        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "brand_name" => config('app.name'),
                    "return_url" => route('donate_success'),
                    "cancel_url" => route('donate_cancel'),
                ],
                "purchase_units" => [
                    [
                        "description" => "Donation for social activities",
                        "amount" => [
                            "currency_code" => $currency,
                            "value" => number_format((float) $request->amount, 2, '.', ''),
                        ],
                    ],
                ],
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error','We could not reach PayPal. Please try again later.');
        }

        if(isset($response['id']) && $response['id'] != null) {
            foreach($response['links'] as $link) {
                if($link['rel'] == 'approve') {
                    session()->put('donate_name', $request->name);
                    session()->put('donate_email', $request->email);
                    session()->put('donate_message', $request->message);
                    session()->put('donate_order_id', $response['id']);
                    return redirect()->away($link['href']);
                }
            }
        }

        return redirect()->route('donate_cancel');
    }

    public function donate_success(Request $request)
    {
        // Only complete the PayPal order this visitor started on the donate page.
        $expected_order = session()->pull('donate_order_id');
        if(!$request->has('token') || !$expected_order || !hash_equals((string) $expected_order, (string) $request->token)) {
            return redirect()->route('donate_cancel');
        }

        $setting = Setting::where('id',1)->first();
        $this->configure_paypal($setting);

        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request->token);
        } catch (\Throwable $e) {
            return redirect()->route('donate_cancel');
        }

        if(isset($response['status']) && $response['status'] == 'COMPLETED') {

            $capture = $response['purchase_units'][0]['payments']['captures'][0] ?? [];

            $donation = new Donation();
            $donation->donor_name = session()->get('donate_name');
            $donation->donor_email = session()->get('donate_email');
            $donation->message = session()->get('donate_message');
            $donation->amount = $capture['amount']['value'] ?? 0;
            $donation->currency = $capture['amount']['currency_code'] ?? ($setting->paypal_currency ?: 'USD');
            $donation->payment_method = 'PayPal';
            $donation->transaction_id = $capture['id'] ?? ($response['id'] ?? null);
            $donation->status = 'Completed';
            $donation->save();

            // Notify the admin
            $admin = Admin::where('id',1)->first();
            if($admin) {
                $subject = "New Donation Received";
                $body = "<b>Donor:</b><br>".e($donation->donor_name)."<br><br>";
                $body .= "<b>Email:</b><br>".e($donation->donor_email)."<br><br>";
                $body .= "<b>Amount:</b><br>".$donation->currency." ".$donation->amount."<br><br>";
                $body .= "<b>Message:</b><br>".nl2br(e($donation->message))."<br><br>";
                \safe_mail($admin->email, $subject, $body);
            }

            session()->forget(['donate_name','donate_email','donate_message']);

            return redirect()->route('donate')->with('success','Thank you! Your donation was received successfully.');
        }

        return redirect()->route('donate_cancel');
    }

    public function donate_cancel()
    {
        session()->forget(['donate_name','donate_email','donate_message']);
        return redirect()->route('donate')->with('error','Your donation was not completed.');
    }

    private function configure_paypal($setting)
    {
        $mode = in_array($setting->paypal_mode, ['sandbox','live']) ? $setting->paypal_mode : 'sandbox';

        config([
            'paypal.mode' => $mode,
            'paypal.'.$mode.'.client_id' => $setting->paypal_client_id,
            'paypal.'.$mode.'.client_secret' => $setting->paypal_client_secret,
            'paypal.currency' => $setting->paypal_currency ?: 'USD',
        ]);
    }

    public function subscriber_send_email(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|unique:subscribers,email'
        ]);

        if(!$validator->passes()) {
            return response()->json(['code'=>0,'error_message'=>$validator->errors()->toArray()]);
        } else {

            $token = secure_token();

            $obj = new Subscriber();
            $obj->email = $request->email;
            $obj->token = $token;
            $obj->status = 0;
            $obj->save();

            $verification_link = url('subscriber/verify/'.$request->email.'/'.$token);

            // Send email
            $subject = 'Subscriber Verification';
            $message = 'Please click on the link below to confirm subscription:<br>';
            $message .= '<a href="'.$verification_link.'">';
            $message .= $verification_link;
            $message .= '</a>';

            \safe_mail($request->email, $subject, $message);

            return response()->json(['code'=>1,'success_message'=>'Please check your email to confirm subscription']);
        }
    }

    public function subscriber_verify($email,$token)
    {
        $subscriber_data = Subscriber::where('email',$email)->where('token',$token)->first();

        if($subscriber_data) {

            $subscriber_data->token = '';
            $subscriber_data->status = 1;
            $subscriber_data->update();

            return redirect()->route('home')->with('success', __('Your subscription is verified successfully!'));

        } else {
            return redirect()->route('home');
        }
    }

    public function custom_page($slug)
    {
        $page = where_slug(CustomPage::query(), $slug)->first();
        if(!$page) {
            return redirect()->route('home');
        }
        if(!$page->isVisible()) {
            abort(404);
        }
        if (slug_for($page) !== $slug) {
            return redirect()->route('custom_page', slug_for($page), 301);
        }
        set_model_alternates('custom_page', $page);

        $menu_item = \App\Models\MenuItem::where('link_type', 'custom_page')
            ->where('custom_page_id', $page->id)
            ->first();
        $menu_parent = $menu_item && $menu_item->parent_id ? $menu_item->parent : null;
        $sibling_pages = $menu_parent ? $menu_parent->visibleChildren()->get() : collect();

        return view('front.custom-page', compact('page', 'menu_parent', 'sibling_pages'));
    }

    public function robots()
    {
        $setting = Setting::first();
        $lines = $setting && $setting->seo_robots_indexable
            ? "User-agent: *\nDisallow:\n\nSitemap: " . url('/sitemap.xml')
            : "User-agent: *\nDisallow: /";

        return response($lines, 200)->header('Content-Type', 'text/plain');
    }

    public function sitemap()
    {
        $setting = Setting::first();
        $urls = sitemap_entries($setting);

        return response()->view('front.sitemap', compact('urls'))->header('Content-Type', 'text/xml');
    }

}
