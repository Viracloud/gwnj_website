<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AboutItem;
use App\Models\AboutItemImage;

class AdminAboutItemController extends Controller
{
    public function index()
    {
        $about_item = AboutItem::where('id',1)->first();
        $slides = AboutItemImage::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.about_item.index',compact('about_item', 'slides'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'heading' => 'required|string|max:255',
            'text' => 'required|string',
        ]);

        $obj = AboutItem::where('id', 1)->first();
        if($request->hasFile('image')) {
            $request->validate([
                'image' => 'mimes:jpeg,png,jpg,webp|max:4096',
            ]);
            $final_name = 'about_item_'.time().'.'.$request->image->extension();
            if($obj->image && file_exists(public_path('uploads/'.$obj->image))) {
                unlink(public_path('uploads/'.$obj->image));
            }
            $request->image->move(public_path('uploads'), $final_name);
            $obj->image = $final_name;
        }
        $obj->heading = $request->heading;
        $obj->heading_fa = $request->heading_fa;
        $obj->text = $request->text;
        $obj->text_fa = $request->text_fa;
        $obj->subheading = $request->subheading;
        $obj->subheading_fa = $request->subheading_fa;
        $obj->experience_year = $request->experience_year;
        $obj->experience_text = $request->experience_text;
        $obj->experience_text_fa = $request->experience_text_fa;
        $obj->youtube_id = $request->youtube_id;
        $obj->phone_number = $request->phone_number;
        $obj->phone_text = $request->phone_text;
        $obj->phone_text_fa = $request->phone_text_fa;
        $obj->button_text = $request->button_text;
        $obj->button_text_fa = $request->button_text_fa;
        $obj->button_link = $request->button_link;
        $obj->save();

        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    public function image_store(Request $request)
    {
        $request->validate([
            'images' => 'required|array|max:20',
            'images.*' => 'mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $next = (int) AboutItemImage::max('sort_order');
        foreach ($request->file('images') as $file) {
            $name = 'about_slide_'.time().'_'.uniqid().'.'.$file->extension();
            $file->move(public_path('uploads'), $name);
            AboutItemImage::create(['image' => $name, 'sort_order' => ++$next]);
        }

        return redirect()->back()->with('success', 'Slideshow photos added successfully.');
    }

    public function image_update(Request $request, $id)
    {
        $item = AboutItemImage::findOrFail($id);
        $request->validate(['sort_order' => 'required|integer|min:0']);
        $item->sort_order = (int) $request->sort_order;
        $item->save();

        return redirect()->back()->with('success', 'Photo order updated.');
    }

    public function image_delete($id)
    {
        $item = AboutItemImage::findOrFail($id);
        if ($item->image && file_exists(public_path('uploads/'.$item->image))) {
            unlink(public_path('uploads/'.$item->image));
        }
        $item->delete();

        return redirect()->back()->with('success', 'Photo removed from the slideshow.');
    }
}
