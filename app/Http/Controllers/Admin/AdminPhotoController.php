<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Photo;

class AdminPhotoController extends Controller
{
    public function index()
    {
        $photos = Photo::orderBy('id','asc')->get();
        return view('admin.photo.index',compact('photos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'caption' => 'nullable|string|max:255',
        ]);

        $photo = new Photo();
        if($request->hasFile('image')) {
            $final_name = 'photo_'.time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads'), $final_name);
            $photo->image = $final_name;
        }
        $photo->caption = $request->caption;
        $photo->caption_fa = $request->caption_fa;
        $photo->save();

        return redirect()->back()->with('success', 'Item created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'caption' => 'nullable|string|max:255',
        ]);

        $photo = Photo::findOrFail($id);
        if($request->hasFile('image')) {
            $request->validate([
                'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $final_name = 'photo_'.time().'.'.$request->image->extension();
            if($photo->image && file_exists(public_path('uploads/'.$photo->image))) {
                unlink(public_path('uploads/'.$photo->image));
            }
            $request->image->move(public_path('uploads'), $final_name);
            $photo->image = $final_name;
        }
        $photo->caption = $request->caption;
        $photo->caption_fa = $request->caption_fa;
        $photo->save();

        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    public function delete($id)
    {
        $photo = Photo::findOrFail($id);
        if($photo->image && file_exists(public_path('uploads/'.$photo->image))) {
            unlink(public_path('uploads/'.$photo->image));
        }
        $photo->delete();

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }
}
