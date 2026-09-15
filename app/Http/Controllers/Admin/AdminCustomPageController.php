<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomPage;

class AdminCustomPageController extends Controller
{
    public function index()
    {
        $custom_pages = CustomPage::orderBy('id','asc')->get();
        return view('admin.custom_page.index',compact('custom_pages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:custom_pages,slug',
            'content' => 'required|string',
        ]);

        $custom_page = new CustomPage();
        $custom_page->title = $request->title;
        $custom_page->title_sv = $request->title_sv;
        $custom_page->slug = $request->slug;
        $custom_page->content = $request->content;
        $custom_page->content_sv = $request->content_sv;
        $custom_page->seo_title = $request->seo_title;
        $custom_page->seo_title_sv = $request->seo_title_sv;
        $custom_page->seo_meta_description = $request->seo_meta_description;
        $custom_page->seo_meta_description_sv = $request->seo_meta_description_sv;

        if ($request->hasFile('image')) {
            $request->validate(['image' => 'mimes:jpeg,png,jpg,gif|max:2048']);
            $name = 'custom_page_' . time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads'), $name);
            $custom_page->image = $name;
        }

        $custom_page->save();

        return redirect()->back()->with('success', 'Item created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:custom_pages,slug,' . $id,
            'content' => 'required|string',
        ]);

        $custom_page = CustomPage::where('id', $id)->first();
        $custom_page->title = $request->title;
        $custom_page->title_sv = $request->title_sv;
        $custom_page->slug = $request->slug;
        $custom_page->content = $request->content;
        $custom_page->content_sv = $request->content_sv;
        $custom_page->seo_title = $request->seo_title;
        $custom_page->seo_title_sv = $request->seo_title_sv;
        $custom_page->seo_meta_description = $request->seo_meta_description;
        $custom_page->seo_meta_description_sv = $request->seo_meta_description_sv;

        if ($request->hasFile('image')) {
            $request->validate(['image' => 'mimes:jpeg,png,jpg,gif|max:2048']);
            if ($custom_page->image && file_exists(public_path('uploads/' . $custom_page->image))) {
                unlink(public_path('uploads/' . $custom_page->image));
            }
            $name = 'custom_page_' . time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads'), $name);
            $custom_page->image = $name;
        }

        $custom_page->save();

        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    public function delete($id)
    {
        $custom_page = CustomPage::where('id', $id)->first();
        if ($custom_page->image && file_exists(public_path('uploads/' . $custom_page->image))) {
            unlink(public_path('uploads/' . $custom_page->image));
        }
        $custom_page->delete();

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }
}
