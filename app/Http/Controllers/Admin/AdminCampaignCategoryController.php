<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CampaignCategory;
use App\Models\Campaign;

class AdminCampaignCategoryController extends Controller
{
    public function index()
    {
        $campaign_categories = CampaignCategory::orderBy('id','asc')->get();
        return view('admin.campaign_category.index', compact('campaign_categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:campaign_categories,name',
            'slug' => 'required|alpha_dash|unique:campaign_categories,slug',
        ]);

        $obj = new CampaignCategory();
        $obj->name = $request->name;
        $obj->name_fa = $request->name_fa;
        $obj->slug = $request->slug;
        $obj->save();

        return redirect()->back()->with('success', 'Item created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:campaign_categories,name,'.$id,
            'slug' => 'required|alpha_dash|unique:campaign_categories,slug,'.$id,
        ]);

        $obj = CampaignCategory::findOrFail($id);
        $obj->name = $request->name;
        $obj->name_fa = $request->name_fa;
        $obj->slug = $request->slug;
        $obj->save();

        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    public function delete($id)
    {
        $total = Campaign::where('campaign_category_id', $id)->count();

        if($total > 0) {
            return redirect()->back()->with('error', 'Cannot delete category with associated campaigns.');
        }

        $obj = CampaignCategory::findOrFail($id);
        $obj->delete();

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }
}
