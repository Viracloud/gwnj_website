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
        return view('admin.campaign.index', compact('campaigns'));
    }

    private function fill(Campaign $obj, Request $request): void
    {
        $obj->title = $request->title;
        $obj->title_sv = $request->title_sv;
        $obj->slug = resolve_slug('campaigns', $request->slug, $request->title, $obj->id);
        $obj->slug_sv = resolve_slug_sv('campaigns', $request->slug_sv, $request->title_sv, $obj->id);
        $obj->excerpt = $request->excerpt;
        $obj->excerpt_sv = $request->excerpt_sv;
        $obj->content = $request->content;
        $obj->content_sv = $request->content_sv;
        $obj->status = $request->status;
        $obj->goal = $request->goal;
        $obj->goal_sv = $request->goal_sv;
        $obj->cta_text = $request->cta_text;
        $obj->cta_text_sv = $request->cta_text_sv;
        $obj->cta_link = $request->cta_link;
        $obj->stat_number = $request->stat_number;
        $obj->stat_label = $request->stat_label;
        $obj->stat_label_sv = $request->stat_label_sv;
        $obj->countries = $request->countries;
        $obj->countries_sv = $request->countries_sv;
        $obj->published_date = $request->published_date ?: null;
        $obj->sort_order = (int) $request->sort_order;
        $obj->show_on_home = $request->boolean('show_on_home');
        $obj->seo_title = $request->seo_title;
        $obj->seo_title_sv = $request->seo_title_sv;
        $obj->seo_meta_description = $request->seo_meta_description;
        $obj->seo_meta_description_sv = $request->seo_meta_description_sv;
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|alpha_dash|max:255',
            'status' => 'required|in:active,won,closed',
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
