<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PageDonateItem;

class AdminPageDonateItemController extends Controller
{
    public function index()
    {
        $page_data = PageDonateItem::where('id',1)->first();
        return view('admin.page_item.donate',compact('page_data'));
    }

    public function update(Request $request)
    {
        $obj = PageDonateItem::where('id', 1)->first();

        $obj->page_title = $request->page_title;
        $obj->page_title_sv = $request->page_title_sv;
        $obj->heading = $request->heading;
        $obj->heading_sv = $request->heading_sv;
        $obj->description = $request->description;
        $obj->description_sv = $request->description_sv;
        $obj->preset_amounts = $request->preset_amounts;
        $obj->goal_amount = $request->goal_amount;
        $obj->seo_title = $request->seo_title;
        $obj->seo_meta_description = $request->seo_meta_description;

        $obj->save();

        return redirect()->back()->with('success', 'Item updated successfully.');
    }
}
