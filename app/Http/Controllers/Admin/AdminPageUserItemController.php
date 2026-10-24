<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PageUserItem;

class AdminPageUserItemController extends Controller
{
    public function index()
    {
        $page_data = PageUserItem::where('id',1)->first();
        return view('admin.page_item.user',compact('page_data'));
    }

    public function update(Request $request)
    {
        $obj = PageUserItem::where('id', 1)->first();

        $obj->dashboard_page_title = $request->dashboard_page_title;
        $obj->dashboard_page_title_fa = $request->dashboard_page_title_fa;
        $obj->profile_page_title = $request->profile_page_title;
        $obj->profile_page_title_fa = $request->profile_page_title_fa;
        $obj->seo_title = $request->seo_title;
        $obj->seo_meta_description = $request->seo_meta_description;

        $obj->save();

        return redirect()->back()->with('success', 'Item updated successfully.');
    }
}
