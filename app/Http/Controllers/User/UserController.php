<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PageUserItem;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function dashboard()
    {
        $page_data = PageUserItem::where('id',1)->first();
        return view('user.dashboard', compact('page_data'));
    }

    public function logout()
    {
        Auth::guard('web')->logout();

        // End the session completely (new id, new CSRF token), so nothing is left on a shared device.
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login')->with('success', __('Logout is successful!'));
    }

    public function profile()
    {
        $page_data = PageUserItem::where('id',1)->first();
        return view('user.profile', compact('page_data'));
    }

    public function profile_update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'surname' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,'.Auth::guard('web')->user()->id,
            'phone' => 'required|string|max:40',
            'address' => 'required|string|max:255',
            'country' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'zip' => 'required|string|max:20',
        ]);

        $user = User::where('id', Auth::guard('web')->user()->id)->first();

        // Changing the e-mail or password needs the current password, so a session left open
        // on a shared or seized device can't be used to take the account over.
        $email_changed = strcasecmp((string) $request->email, (string) $user->email) !== 0;
        if ($email_changed || $request->password != null) {
            $request->validate(['current_password' => ['required', 'current_password:web']]);
        }

        if($request->password != null) {
            $request->validate([
                'password' => ['required','min:8','max:200'],
                'confirm_password' => ['required','same:password'],
            ]);
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('photo')) {
            $request->validate(['photo' => 'mimes:jpeg,png,jpg,webp|max:2048']);
            if ($user->photo && file_exists(public_path('uploads/'.$user->photo))) {
                unlink(public_path('uploads/'.$user->photo));
            }
            // A random name: a time-based one could be guessed to collect members' photos.
            $final_name = 'member_photo_'.bin2hex(random_bytes(16)).'.'.$request->photo->extension();
            $request->photo->move(public_path('uploads'), $final_name);
            $user->photo = $final_name;
        }

        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->country = $request->country;
        $user->state = $request->state;
        $user->city = $request->city;
        $user->zip = $request->zip;
        $user->update();

        if ($request->password != null) {
            Auth::guard('web')->logoutOtherDevices($request->password);
        }

        return redirect()->back()->with('success', __('Profile update is successful!'));
    }

    /** The member deletes their own account, after confirming with their password. */
    public function account_delete(Request $request)
    {
        $request->validate([
            'delete_password' => ['required', 'current_password:web'],
            'confirm_delete' => ['accepted'],
        ]);

        $user = Auth::guard('web')->user();

        if ($user->photo && file_exists(public_path('uploads/'.$user->photo))) {
            @unlink(public_path('uploads/'.$user->photo));
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($user) {
            \Illuminate\Support\Facades\DB::table('password_reset_tokens')->where('email', $user->email)->delete();
            if (config('session.driver') === 'database') {
                \Illuminate\Support\Facades\DB::table(config('session.table', 'sessions'))->where('user_id', $user->id)->delete();
            }
            // Committee links are removed by the foreign key (cascade). Donations are kept as
            // financial records; they hold only the name and e-mail given at payment time.
            $user->delete();
        });

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', __('Your account has been deleted.'));
    }
}
