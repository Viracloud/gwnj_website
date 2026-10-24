<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventFaq;
use App\Models\EventCategory;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('id','asc')->get();
        $event_categories = EventCategory::orderBy('name','asc')->get();
        return view('admin.event.index',compact('events','event_categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|alpha_dash|max:255',
            'description' => 'required|string',
            'event_location' => 'required|string',
            'event_location_detail' => 'required|string',
            'event_date' => 'required|string',
            'event_time' => 'required|string',
        ]);

        $obj = new Event();
        if($request->hasFile('image')) {
            $final_name = 'event_image_'.time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads'), $final_name);
            $obj->image = $final_name;
        }
        $obj->event_category_id = $request->event_category_id;
        $obj->title = $request->title;
        $obj->title_fa = $request->title_fa;
        $obj->slug = resolve_slug('events', $request->slug, $request->title, $obj->id);
        $obj->slug_fa = resolve_slug_fa('events', $request->slug_fa, $request->title_fa, $obj->id);
        $obj->description = $request->description;
        $obj->description_fa = $request->description_fa;
        $obj->show_on_home = $request->show_on_home;
        $obj->event_location = $request->event_location;
        $obj->event_location_fa = $request->event_location_fa;
        $obj->event_location_detail = $request->event_location_detail;
        $obj->event_location_detail_fa = $request->event_location_detail_fa;
        $obj->event_date = $request->event_date;
        $obj->event_time = $request->event_time;
        $obj->ticket_price = $request->ticket_price;
        $obj->youtube_id = $request->youtube_id;
        $obj->button_text = $request->button_text;
        $obj->button_text_fa = $request->button_text_fa;
        $obj->button_link = $request->button_link;
        $obj->save();

        return redirect()->back()->with('success', 'Item created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|alpha_dash|max:255',
            'description' => 'required|string',
            'event_location' => 'required|string',
            'event_location_detail' => 'required|string',
            'event_date' => 'required|string',
            'event_time' => 'required|string',
        ]);

        $obj = Event::findOrFail($id);
        if($request->hasFile('image')) {
            $request->validate([
                'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $final_name = 'event_image_'.time().'.'.$request->image->extension();
            if($obj->image && file_exists(public_path('uploads/'.$obj->image))) {
                unlink(public_path('uploads/'.$obj->image));
            }
            $request->image->move(public_path('uploads'), $final_name);
            $obj->image = $final_name;
        }
        $obj->event_category_id = $request->event_category_id;
        $obj->title = $request->title;
        $obj->title_fa = $request->title_fa;
        $obj->slug = resolve_slug('events', $request->slug, $request->title, $obj->id);
        $obj->slug_fa = resolve_slug_fa('events', $request->slug_fa, $request->title_fa, $obj->id);
        $obj->description = $request->description;
        $obj->description_fa = $request->description_fa;
        $obj->show_on_home = $request->show_on_home;
        $obj->event_location = $request->event_location;
        $obj->event_location_fa = $request->event_location_fa;
        $obj->event_location_detail = $request->event_location_detail;
        $obj->event_location_detail_fa = $request->event_location_detail_fa;
        $obj->event_date = $request->event_date;
        $obj->event_time = $request->event_time;
        $obj->ticket_price = $request->ticket_price;
        $obj->youtube_id = $request->youtube_id;
        $obj->button_text = $request->button_text;
        $obj->button_text_fa = $request->button_text_fa;
        $obj->button_link = $request->button_link;
        $obj->save();

        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    public function delete($id)
    {

        EventFaq::where('event_id', $id)->delete();

        $obj = Event::findOrFail($id);
        if($obj->image && file_exists(public_path('uploads/'.$obj->image))) {
            unlink(public_path('uploads/'.$obj->image));
        }
        $obj->delete();

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }

    public function faqs($event_id)
    {
        $event = Event::findOrFail($event_id);
        $event_faqs = EventFaq::where('event_id', $event_id)->get();
        return view('admin.event.faq', compact('event_faqs', 'event'));
    }

    public function faq_store(Request $request, $event_id)
    {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        $obj = new EventFaq();
        $obj->event_id = $event_id;
        $obj->question = $request->question;
        $obj->question_fa = $request->question_fa;
        $obj->answer = $request->answer;
        $obj->answer_fa = $request->answer_fa;
        $obj->save();

        return redirect()->back()->with('success', 'Item created successfully.');
    }

    public function faq_update(Request $request, $faq_id)
    {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        $obj = EventFaq::findOrFail($faq_id);
        $obj->question = $request->question;
        $obj->question_fa = $request->question_fa;
        $obj->answer = $request->answer;
        $obj->answer_fa = $request->answer_fa;
        $obj->save();

        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    public function faq_delete(Request $request, $faq_id)
    {
        $obj = EventFaq::findOrFail($faq_id);
        $obj->delete();

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }
}
