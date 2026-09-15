<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PageHomeItem;

class AdminPageHomeItemController extends Controller
{
    public function index()
    {
        $page_data = PageHomeItem::where('id',1)->first();
        return view('admin.page_item.home',compact('page_data'));
    }

    public function update(Request $request)
    {
        $obj = PageHomeItem::where('id', 1)->first();
        
        $obj->about_status = $request->about_status;

        $obj->team_subheading = $request->team_subheading;
        $obj->team_subheading_sv = $request->team_subheading_sv;
        $obj->team_heading = $request->team_heading;
        $obj->team_heading_sv = $request->team_heading_sv;
        $obj->team_status = $request->team_status;

        $obj->event_subheading = $request->event_subheading;
        $obj->event_subheading_sv = $request->event_subheading_sv;
        $obj->event_heading = $request->event_heading;
        $obj->event_heading_sv = $request->event_heading_sv;
        $obj->event_status = $request->event_status;

        $obj->blog_subheading = $request->blog_subheading;
        $obj->blog_subheading_sv = $request->blog_subheading_sv;
        $obj->blog_heading = $request->blog_heading;
        $obj->blog_heading_sv = $request->blog_heading_sv;
        $obj->blog_status = $request->blog_status;

        $obj->seo_title = $request->seo_title;
        $obj->seo_meta_description = $request->seo_meta_description;

        $obj->save();

        return redirect()->back()->with('success', 'Item updated successfully.');
    }
}
