<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;

class AdminCampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::orderBy('sort_order')->orderBy('id', 'desc')->get();
        $campaign_categories = \App\Models\CampaignCategory::orderBy('name')->get();
        return view('admin.campaign.index', compact('campaigns', 'campaign_categories'));
    }

    private function fill(Campaign $obj, Request $request): void
    {
        $obj->title = $request->title;
        $obj->title_fa = $request->title_fa;
        $obj->slug = resolve_slug('campaigns', $request->slug, $request->title, $obj->id);
        $obj->slug_fa = resolve_slug_fa('campaigns', $request->slug_fa, $request->title_fa, $obj->id);
        $obj->excerpt = $request->excerpt;
        $obj->excerpt_fa = $request->excerpt_fa;
        $obj->content = $request->content;
        $obj->content_fa = $request->content_fa;
        $obj->status = $request->status;
        $obj->campaign_category_id = $request->campaign_category_id ?: null;
        $obj->goal = $request->goal;
        $obj->goal_fa = $request->goal_fa;
        $obj->cta_text = $request->cta_text;
        $obj->cta_text_fa = $request->cta_text_fa;
        $obj->cta_link = $request->cta_link;
        $obj->stat_number = $request->stat_number;
        $obj->stat_label = $request->stat_label;
        $obj->stat_label_fa = $request->stat_label_fa;
        $obj->countries = $request->countries;
        $obj->countries_fa = $request->countries_fa;
        $obj->published_date = $request->published_date ?: null;
        $obj->sort_order = (int) $request->sort_order;
        $obj->show_on_home = $request->boolean('show_on_home');
        $obj->seo_title = $request->seo_title;
        $obj->seo_title_fa = $request->seo_title_fa;
        $obj->seo_meta_description = $request->seo_meta_description;
        $obj->seo_meta_description_fa = $request->seo_meta_description_fa;
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|alpha_dash|max:255',
            'status' => 'required|in:active,won,closed',
            'campaign_category_id' => 'nullable|exists:campaign_categories,id',
        ]);

        $obj = new Campaign();
        if ($request->hasFile('image')) {
            $final_name = 'campaign_' . time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads'), $final_name);
            $obj->image = $final_name;
        }
        $this->fill($obj, $request);
        $obj->save();

        return redirect()->back()->with('success', 'Item created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|alpha_dash|max:255',
            'status' => 'required|in:active,won,closed',
            'campaign_category_id' => 'nullable|exists:campaign_categories,id',
        ]);

        $obj = Campaign::where('id', $id)->firstOrFail();
        if ($request->hasFile('image')) {
            $request->validate(['image' => 'mimes:jpeg,png,jpg,gif,webp|max:4096']);
            if ($obj->image && file_exists(public_path('uploads/' . $obj->image))) {
                unlink(public_path('uploads/' . $obj->image));
            }
            $final_name = 'campaign_' . time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads'), $final_name);
            $obj->image = $final_name;
        }
        $this->fill($obj, $request);
        $obj->save();

        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    public function delete($id)
    {
        $obj = Campaign::where('id', $id)->firstOrFail();
        if ($obj->image && file_exists(public_path('uploads/' . $obj->image))) {
            unlink(public_path('uploads/' . $obj->image));
        }
        $obj->delete();

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }
}
