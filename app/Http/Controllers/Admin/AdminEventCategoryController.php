<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventCategory;
use App\Models\Event;

class AdminEventCategoryController extends Controller
{
    public function index()
    {
        $event_categories = EventCategory::orderBy('id','asc')->get();
        return view('admin.event_category.index', compact('event_categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:event_categories,name',
            'slug' => 'required|alpha_dash|unique:event_categories,slug',
        ]);

        $obj = new EventCategory();
        $obj->name = $request->name;
        $obj->name_sv = $request->name_sv;
        $obj->slug = $request->slug;
        $obj->save();

        return redirect()->back()->with('success', 'Item created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:event_categories,name,'.$id,
            'slug' => 'required|alpha_dash|unique:event_categories,slug,'.$id,
        ]);

        $obj = EventCategory::where('id', $id)->first();
        $obj->name = $request->name;
        $obj->name_sv = $request->name_sv;
        $obj->slug = $request->slug;
        $obj->save();

        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    public function delete($id)
    {
        $total = Event::where('event_category_id', $id)->count();

        if($total > 0) {
            return redirect()->back()->with('error', 'Cannot delete category with associated events.');
        }

        $obj = EventCategory::where('id', $id)->first();
        $obj->delete();

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }
}
