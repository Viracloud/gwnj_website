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
            'slug' => 'nullable|alpha_dash|max:255',
        ]);

        $obj = new PostCategory();
        $obj->name = $request->name;
        $obj->name_sv = $request->name_sv;
        $obj->slug = resolve_slug('post_categories', $request->slug, $request->name, $obj->id);
        $obj->slug_sv = resolve_slug_sv('post_categories', $request->slug_sv, $request->name_sv, $obj->id);
        $obj->menu_label = $request->menu_label;
        $obj->menu_label_sv = $request->menu_label_sv;
        $obj->save();

        return redirect()->back()->with('success', 'Item created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:post_categories,name,'.$id,
            'slug' => 'nullable|alpha_dash|max:255',
        ]);

        $obj = PostCategory::where('id', $id)->first();
        $obj->name = $request->name;
        $obj->name_sv = $request->name_sv;
        $obj->slug = resolve_slug('post_categories', $request->slug, $request->name, $obj->id);
        $obj->slug_sv = resolve_slug_sv('post_categories', $request->slug_sv, $request->name_sv, $obj->id);
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
