<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PageEventItem;

class AdminPageEventItemController extends Controller
{
    public function index()
    {
        $page_data = PageEventItem::where('id',1)->first();
        return view('admin.page_item.event',compact('page_data'));
    }

    public function update(Request $request)
    {
        $obj = PageEventItem::where('id', 1)->first();

        $obj->page_title = $request->page_title;
        $obj->page_title_sv = $request->page_title_sv;
        $obj->seo_title = $request->seo_title;
        $obj->seo_meta_description = $request->seo_meta_description;

        $obj->save();

        return redirect()->back()->with('success', 'Item updated successfully.');
    }
}
