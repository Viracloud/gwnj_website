<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PageLoginItem;

class AdminPageLoginItemController extends Controller
{
    public function index()
    {
        $page_data = PageLoginItem::where('id',1)->first();
        return view('admin.page_item.login',compact('page_data'));
    }

    public function update(Request $request)
    {
        $obj = PageLoginItem::where('id', 1)->first();

        $obj->login_page_title = $request->login_page_title;
        $obj->login_page_title_sv = $request->login_page_title_sv;
        $obj->register_page_title = $request->register_page_title;
        $obj->register_page_title_sv = $request->register_page_title_sv;
        $obj->forget_password_page_title = $request->forget_password_page_title;
        $obj->forget_password_page_title_sv = $request->forget_password_page_title_sv;
        $obj->reset_password_page_title = $request->reset_password_page_title;
        $obj->reset_password_page_title_sv = $request->reset_password_page_title_sv;
        $obj->seo_title = $request->seo_title;
        $obj->seo_meta_description = $request->seo_meta_description;

        $obj->save();

        return redirect()->back()->with('success', 'Item updated successfully.');
    }
}
