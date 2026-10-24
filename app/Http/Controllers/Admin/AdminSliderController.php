<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;

class AdminSliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('id','asc')->get();
        return view('admin.slider.index',compact('sliders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'heading' => 'required|string|max:255',
            'text' => 'required|string',
        ]);

        $slider = new Slider();
        if($request->hasFile('image')) {
            $final_name = 'slider_'.time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads'), $final_name);
            $slider->image = $final_name;
        }
        $slider->heading = $request->heading;
        $slider->heading_fa = $request->heading_fa;
        $slider->text = $request->text;
        $slider->text_fa = $request->text_fa;
        $slider->button_text = $request->button_text;
        $slider->button_text_fa = $request->button_text_fa;
        $slider->button_link = $request->button_link;
        $slider->save();

        return redirect()->back()->with('success', 'Item created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'heading' => 'required|string|max:255',
            'text' => 'required|string',
        ]);

        $slider = Slider::findOrFail($id);
        if($request->hasFile('image')) {
            $request->validate([
                'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $final_name = 'slider_'.time().'.'.$request->image->extension();
            if($slider->image && file_exists(public_path('uploads/'.$slider->image))) {
                unlink(public_path('uploads/'.$slider->image));
            }
            $request->image->move(public_path('uploads'), $final_name);
            $slider->image = $final_name;
        }
        $slider->heading = $request->heading;
        $slider->heading_fa = $request->heading_fa;
        $slider->text = $request->text;
        $slider->text_fa = $request->text_fa;
        $slider->button_text = $request->button_text;
        $slider->button_text_fa = $request->button_text_fa;
        $slider->button_link = $request->button_link;
        $slider->save();

        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    public function delete($id)
    {
        $slider = Slider::findOrFail($id);
        if($slider->image && file_exists(public_path('uploads/'.$slider->image))) {
            unlink(public_path('uploads/'.$slider->image));
        }
        $slider->delete();

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }
}
