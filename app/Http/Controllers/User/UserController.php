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

        // Destroy all session data
        session()->flush();

        return redirect()->route('login')->with('success','Logout is successful!');
    }

    public function profile()
    {
        $page_data = PageUserItem::where('id',1)->first();
        return view('user.profile', compact('page_data'));
    }

    public function profile_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'zip' => 'required',
        ]);

        $user = User::where('id', Auth::guard('web')->user()->id)->first();

        if($request->password != null) {
            $request->validate([
                'password' => ['required'],
                'confirm_password' => ['required','same:password'],
            ]);
            $user->password = Hash::make($request->password);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->country = $request->country;
        $user->state = $request->state;
        $user->city = $request->city;
        $user->zip = $request->zip;
        $user->update();

        return redirect()->back()->with('success','Profile update is successful!');
    }
}
