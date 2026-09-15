<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PageAboutItem;

class AdminPageAboutItemController extends Controller
{
    public function index()
    {
        $page_data = PageAboutItem::where('id',1)->first();
        return view('admin.page_item.about',compact('page_data'));
    }

    public function update(Request $request)
    {
        $obj = PageAboutItem::where('id', 1)->first();
        
        $obj->about_status = $request->about_status;

        $obj->seo_title = $request->seo_title;
        $obj->seo_meta_description = $request->seo_meta_description;

        $obj->save();

        return redirect()->back()->with('success', 'Item updated successfully.');
    }
}
