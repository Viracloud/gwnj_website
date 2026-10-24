<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PublicationCategory;
use App\Models\Publication;

class AdminPublicationCategoryController extends Controller
{
    public function index()
    {
        $publication_categories = PublicationCategory::orderBy('id','asc')->get();
        return view('admin.publication_category.index', compact('publication_categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:publication_categories,name',
            'slug' => 'required|alpha_dash|unique:publication_categories,slug',
        ]);

        $obj = new PublicationCategory();
        $obj->name = $request->name;
        $obj->name_fa = $request->name_fa;
        $obj->slug = $request->slug;
        $obj->save();

        return redirect()->back()->with('success', 'Item created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:publication_categories,name,'.$id,
            'slug' => 'required|alpha_dash|unique:publication_categories,slug,'.$id,
        ]);

        $obj = PublicationCategory::findOrFail($id);
        $obj->name = $request->name;
        $obj->name_fa = $request->name_fa;
        $obj->slug = $request->slug;
        $obj->save();

        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    public function delete($id)
    {
        $total = Publication::where('publication_category_id', $id)->count();

        if($total > 0) {
            return redirect()->back()->with('error', 'Cannot delete category with associated publications.');
        }

        $obj = PublicationCategory::findOrFail($id);
        $obj->delete();

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }
}
