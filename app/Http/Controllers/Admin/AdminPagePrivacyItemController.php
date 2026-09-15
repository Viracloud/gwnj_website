<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PagePrivacyItem;

class AdminPagePrivacyItemController extends Controller
{
    public function index()
    {
        $page_data = PagePrivacyItem::where('id',1)->first();
        return view('admin.page_item.privacy',compact('page_data'));
    }

    public function update(Request $request)
    {
        $obj = PagePrivacyItem::where('id', 1)->first();

        $obj->page_title = $request->page_title;
        $obj->page_title_sv = $request->page_title_sv;
        $obj->page_content = $request->page_content;
        $obj->page_content_sv = $request->page_content_sv;
        $obj->seo_title = $request->seo_title;
        $obj->seo_meta_description = $request->seo_meta_description;

        $obj->save();

        return redirect()->back()->with('success', 'Item updated successfully.');
    }
}
