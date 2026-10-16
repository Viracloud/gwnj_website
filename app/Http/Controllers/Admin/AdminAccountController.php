<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminAccountController extends Controller
{
    public function index()
    {
        $admins = Admin::orderBy('id', 'asc')->get();
        return view('admin.admin_account.index', compact('admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in([Admin::ROLE_SUPER_ADMIN, Admin::ROLE_AUTHOR, Admin::ROLE_VIEWER])],
        ]);

        $admin = new Admin();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->role = $request->role;
        $admin->save();

        return redirect()->back()->with('success', 'Admin account created successfully.');
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('admins', 'email')->ignore($admin->id)],
            'password' => 'nullable|string|min:8',
            'role' => ['required', Rule::in([Admin::ROLE_SUPER_ADMIN, Admin::ROLE_AUTHOR, Admin::ROLE_VIEWER])],
        ]);

        if ($admin->id === Auth::guard('admin')->id() && $request->role !== Admin::ROLE_SUPER_ADMIN) {
            return redirect()->back()->with('error', 'You cannot remove your own super admin role.');
        }

        if ($admin->role === Admin::ROLE_SUPER_ADMIN && $request->role !== Admin::ROLE_SUPER_ADMIN
            && Admin::where('role', Admin::ROLE_SUPER_ADMIN)->count() <= 1) {
            return redirect()->back()->with('error', 'At least one super admin must remain.');
        }

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->role = $request->role;
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }
        $admin->save();

        return redirect()->back()->with('success', 'Admin account updated successfully.');
    }

    public function delete($id)
    {
        $admin = Admin::findOrFail($id);

        if ($admin->id === Auth::guard('admin')->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        if ($admin->role === Admin::ROLE_SUPER_ADMIN && Admin::where('role', Admin::ROLE_SUPER_ADMIN)->count() <= 1) {
            return redirect()->back()->with('error', 'At least one super admin must remain.');
        }

        $admin->delete();

        return redirect()->back()->with('success', 'Admin account deleted successfully.');
    }
}
