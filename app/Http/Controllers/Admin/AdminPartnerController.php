<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class AdminPartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.partner.index', compact('partners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'website' => 'nullable|url|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:Show,Hide',
        ]);

        $partner = new Partner();
        $partner->name = $request->name;
        $partner->name_fa = $request->name_fa;
        $partner->website = $request->website;
        $partner->sort_order = (int) $request->sort_order;
        $partner->status = $request->status;

        if ($request->hasFile('logo')) {
            $final_name = 'partner_' . time() . '.' . $request->logo->extension();
            $request->logo->move(public_path('uploads'), $final_name);
            $partner->logo = $final_name;
        }
        $partner->save();

        return redirect()->back()->with('success', 'Partner added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'website' => 'nullable|url|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:Show,Hide',
        ]);

        $partner = Partner::findOrFail($id);
        $partner->name = $request->name;
        $partner->name_fa = $request->name_fa;
        $partner->website = $request->website;
        $partner->sort_order = (int) $request->sort_order;
        $partner->status = $request->status;

        if ($request->hasFile('logo')) {
            $request->validate(['logo' => 'mimes:jpeg,png,jpg,gif,svg,webp|max:2048']);
            if ($partner->logo && file_exists(public_path('uploads/' . $partner->logo))) {
                unlink(public_path('uploads/' . $partner->logo));
            }
            $final_name = 'partner_' . time() . '.' . $request->logo->extension();
            $request->logo->move(public_path('uploads'), $final_name);
            $partner->logo = $final_name;
        }
        $partner->save();

        return redirect()->back()->with('success', 'Partner updated successfully.');
    }

    public function delete($id)
    {
        $partner = Partner::findOrFail($id);
        if ($partner->logo && file_exists(public_path('uploads/' . $partner->logo))) {
            unlink(public_path('uploads/' . $partner->logo));
        }
        $partner->delete();

        return redirect()->back()->with('success', 'Partner deleted successfully.');
    }
}
