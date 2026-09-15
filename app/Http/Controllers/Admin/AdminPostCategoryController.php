<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostCategory;
use App\Models\Post;

class AdminPostCategoryController extends Controller
{
    public function index()
    {
        $post_categories = PostCategory::orderBy('id','asc')->get();
        return view('admin.post_category.index',compact('post_categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:post_categories,name',
            'slug' => 'required|alpha_dash|unique:post_categories,slug',
        ]);

        $obj = new PostCategory();
        $obj->name = $request->name;
        $obj->name_sv = $request->name_sv;
        $obj->slug = $request->slug;
        $obj->menu_label = $request->menu_label;
        $obj->menu_label_sv = $request->menu_label_sv;
        $obj->save();

        return redirect()->back()->with('success', 'Item created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:post_categories,name,'.$id,
            'slug' => 'required|alpha_dash|unique:post_categories,slug,'.$id,
        ]);

        $obj = PostCategory::where('id', $id)->first();
        $obj->name = $request->name;
        $obj->name_sv = $request->name_sv;
        $obj->slug = $request->slug;
        $obj->menu_label = $request->menu_label;
        $obj->menu_label_sv = $request->menu_label_sv;
        $obj->save();

        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    public function delete($id)
    {
        $total = Post::where('post_category_id', $id)->count();

        if($total > 0) {
            return redirect()->back()->with('error', 'Cannot delete category with associated posts.');
        }

        $obj = PostCategory::where('id', $id)->first();
        $obj->delete();

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }
}
