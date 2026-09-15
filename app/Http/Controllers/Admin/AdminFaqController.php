<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;

class AdminFaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('id','asc')->get();
        return view('admin.faq.index',compact('faqs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        $faq = new Faq();
        $faq->question = $request->question;
        $faq->question_sv = $request->question_sv;
        $faq->answer = $request->answer;
        $faq->answer_sv = $request->answer_sv;
        $faq->save();

        return redirect()->back()->with('success', 'Item created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        $faq = Faq::where('id', $id)->first();
        $faq->question = $request->question;
        $faq->question_sv = $request->question_sv;
        $faq->answer = $request->answer;
        $faq->answer_sv = $request->answer_sv;
        $faq->save();

        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    public function delete($id)
    {
        $faq = Faq::where('id', $id)->first();
        $faq->delete();

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }
}
