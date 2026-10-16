<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberCategory;
use Illuminate\Http\Request;

class AdminMemberCategoryController extends Controller
{
    public function index()
    {
        $member_categories = MemberCategory::withCount('members')->orderBy('sort_order')->orderBy('id')->get();
        return view('admin.member_category.index', compact('member_categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:member_categories,name',
            'slug' => 'required|alpha_dash|unique:member_categories,slug',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $obj = new MemberCategory();
        $obj->name = $request->name;
        $obj->name_sv = $request->name_sv;
        $obj->slug = $request->slug;
        $obj->sort_order = (int) $request->sort_order;
        $obj->save();

        return redirect()->back()->with('success', 'Item created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:member_categories,name,'.$id,
            'slug' => 'required|alpha_dash|unique:member_categories,slug,'.$id,
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $obj = MemberCategory::where('id', $id)->first();
        $obj->name = $request->name;
        $obj->name_sv = $request->name_sv;
        $obj->slug = $request->slug;
        $obj->sort_order = (int) $request->sort_order;
        $obj->save();

        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    public function delete($id)
    {
        if (Member::where('member_category_id', $id)->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete a group that still has people. Move or delete them first.');
        }

        MemberCategory::where('id', $id)->first()->delete();

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }
}
