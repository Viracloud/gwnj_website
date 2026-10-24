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
        $obj->team_subheading_fa = $request->team_subheading_fa;
        $obj->team_heading = $request->team_heading;
        $obj->team_heading_fa = $request->team_heading_fa;
        $obj->team_status = $request->team_status;

        $obj->event_subheading = $request->event_subheading;
        $obj->event_subheading_fa = $request->event_subheading_fa;
        $obj->event_heading = $request->event_heading;
        $obj->event_heading_fa = $request->event_heading_fa;
        $obj->event_status = $request->event_status;

        $obj->blog_subheading = $request->blog_subheading;
        $obj->blog_subheading_fa = $request->blog_subheading_fa;
        $obj->blog_heading = $request->blog_heading;
        $obj->blog_heading_fa = $request->blog_heading_fa;
        $obj->blog_status = $request->blog_status;

        $obj->seo_title = $request->seo_title;
        $obj->seo_meta_description = $request->seo_meta_description;

        $obj->save();

        return redirect()->back()->with('success', 'Item updated successfully.');
    }
}
