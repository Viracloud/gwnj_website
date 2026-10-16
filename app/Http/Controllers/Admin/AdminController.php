<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\User;
use App\Models\Subscriber;
use App\Models\Donation;
use App\Models\Comment;
use App\Models\Reply;
use App\Models\Post;
use App\Models\Event;
use App\Models\Campaign;
use App\Models\Publication;
use App\Models\Photo;
use App\Models\CustomPage;
use App\Mail\Websitemail;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = now()->toDateString();

        // Things waiting for someone to act on them
        $pending = [
            'comments' => Comment::where('status', 'Pending')->count(),
            'replies' => Reply::where('status', 'Pending')->count(),
            'applications' => User::where('membership_status', 'pending')->count(),
            'cards' => User::where('card_status', 'requested')->count(),
        ];

        // Content overview
        $counts = [
            'posts' => Post::count(),
            'upcoming_events' => Event::where('event_date', '>=', $today)->count(),
            'campaigns' => Campaign::count(),
            'publications' => Publication::count(),
            'photos' => Photo::count(),
            'pages' => CustomPage::count(),
        ];

        // People & support (shown to super admins only)
        $total_members = User::where('membership_status', 'approved')->count();
        $total_users = User::count();
        $total_subscribers = Subscriber::where('status',1)->count();
        $total_donations = Donation::where('status','Completed')->count();
        $total_raised = Donation::where('status','Completed')->sum('amount');

        $upcoming_events = Event::where('event_date', '>=', $today)->orderBy('event_date')->take(5)->get();
        $latest_posts = Post::orderBy('id', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'pending',
            'counts',
            'total_members',
            'total_users',
            'total_subscribers',
            'total_donations',
            'total_raised',
            'upcoming_events',
            'latest_posts'
        ));
    }

    public function login()
    {
        if(Auth::guard('admin')->check()) {
            return redirect()->route('admin_dashboard');
        }
        return view('admin.login');
    }

    public function login_submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $check = $request->all();
        $data = [
            'email' => $check['email'],
            'password' => $check['password']
        ];

        if(Auth::guard('admin')->attempt($data)) {
            return redirect()->route('admin_dashboard')->with('success','Login is successful!');
        } else {
            return redirect()->route('admin_login')->with('error','The information you entered is incorrect! Please try again!');
        }
    }
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin_login')->with('success','Logout is successful!');
    }

    public function forget_password()
    {
        return view('admin.forget-password');
    }

    public function forget_password_submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $admin = Admin::where('email',$request->email)->first();
        if(!$admin) {
            return redirect()->back()->with('error','Email is not found');
        }

        $token = hash('sha256',time());
        $admin->token = $token;
        $admin->update();

        $reset_link = url('admin/reset-password/'.$token.'/'.$request->email);
        $subject = "Password Reset";
        $message = "To reset password, please click on the link below:<br>";
        $message .= "<a href='".$reset_link."'>".$reset_link."</a>";

        \safe_mail($request->email, $subject, $message);

        return redirect()->back()->with('success','We have sent a password reset link to your email. Please check your email. If you do not find the email in your inbox, please check your spam folder.');
    }

    public function reset_password($token,$email)
    {
        $admin = Admin::where('email',$email)->where('token',$token)->first();
        if(!$admin) {
            return redirect()->route('admin_login')->with('error','Token or email is not correct');
        }
        return view('admin.reset-password', compact('token','email'));
    }

    public function reset_password_submit(Request $request, $token, $email)
    {
        $request->validate([
            'password' => ['required'],
            'confirm_password' => ['required','same:password'],
        ]);

        $admin = Admin::where('email',$request->email)->where('token',$request->token)->first();
        $admin->password = Hash::make($request->password);
        $admin->token = "";
        $admin->update();

        return redirect()->route('admin_login')->with('success','Password reset is successful. You can login now.');
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function profile_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $admin = Admin::where('id', Auth::guard('admin')->user()->id)->first();

        if($request->hasFile('photo')) {
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $final_name = 'admin_'.time().'.'.$request->photo->getClientOriginalExtension();

            if($admin->photo != null && file_exists(public_path('uploads/'.$admin->photo))) {
                unlink(public_path('uploads/'.$admin->photo));
            }

            $request->photo->move(public_path('uploads'), $final_name);
            $admin->photo = $final_name;
        }

        if($request->password != null) {
            $request->validate([
                'password' => ['required'],
                'confirm_password' => ['required','same:password'],
            ]);
            $admin->password = Hash::make($request->password);
        }

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->update();

        return redirect()->back()->with('success','Profile update is successful!');
    }
}
