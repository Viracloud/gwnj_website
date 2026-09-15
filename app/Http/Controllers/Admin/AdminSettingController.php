<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class AdminSettingController extends Controller
{
    public function logo()
    {
        $setting = Setting::where('id',1)->first();
        return view('admin.setting.logo',compact('setting'));
    }

    public function logo_update(Request $request)
    {
        $setting = Setting::where('id',1)->first();

        if($request->hasFile('logo_1')) {
            $request->validate(['logo_1' => 'mimes:jpeg,png,jpg,gif,svg|max:2048']);
            $final_name = 'logo_1_'.time().'.'.$request->logo_1->extension();
            if($setting->logo_1 && file_exists(public_path('uploads/'.$setting->logo_1))) {
                unlink(public_path('uploads/'.$setting->logo_1));
            }
            $request->logo_1->move(public_path('uploads'), $final_name);
            $setting->logo_1 = $final_name;
        }

        if($request->hasFile('logo_2')) {
            $request->validate(['logo_2' => 'mimes:jpeg,png,jpg,gif,svg|max:2048']);
            $final_name = 'logo_2_'.time().'.'.$request->logo_2->extension();
            if($setting->logo_2 && file_exists(public_path('uploads/'.$setting->logo_2))) {
                unlink(public_path('uploads/'.$setting->logo_2));
            }
            $request->logo_2->move(public_path('uploads'), $final_name);
            $setting->logo_2 = $final_name;
        }

        $setting->save();
        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    public function favicon()
    {
        $setting = Setting::where('id',1)->first();
        return view('admin.setting.favicon',compact('setting'));
    }

    public function favicon_update(Request $request)
    {
        $setting = Setting::where('id',1)->first();

        if($request->hasFile('favicon')) {
            $request->validate(['favicon' => 'mimes:jpeg,png,jpg,gif,svg,ico|max:2048']);
            $final_name = 'favicon_'.time().'.'.$request->favicon->extension();
            if($setting->favicon && file_exists(public_path('uploads/'.$setting->favicon))) {
                unlink(public_path('uploads/'.$setting->favicon));
            }
            $request->favicon->move(public_path('uploads'), $final_name);
            $setting->favicon = $final_name;
        }

        $setting->save();
        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    public function banner()
    {
        $setting = Setting::where('id',1)->first();
        return view('admin.setting.banner',compact('setting'));
    }

    public function banner_update(Request $request)
    {
        $setting = Setting::where('id',1)->first();

        if($request->hasFile('banner')) {
            $request->validate(['banner' => 'mimes:jpeg,png,jpg,gif,svg|max:2048']);
            $final_name = 'banner_'.time().'.'.$request->banner->extension();
            if($setting->banner && file_exists(public_path('uploads/'.$setting->banner))) {
                unlink(public_path('uploads/'.$setting->banner));
            }
            $request->banner->move(public_path('uploads'), $final_name);
            $setting->banner = $final_name;
        }

        $setting->save();
        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    public function top_bar()
    {
        $setting = Setting::where('id',1)->first();
        return view('admin.setting.top_bar',compact('setting'));
    }

    public function top_bar_update(Request $request)
    {
        $setting = Setting::where('id',1)->first();

        $setting->top_bar_email = $request->top_bar_email;
        $setting->top_bar_phone = $request->top_bar_phone;
        $setting->top_bar_address = $request->top_bar_address;
        $setting->top_bar_address_sv = $request->top_bar_address_sv;
        $setting->top_bar_facebook = $request->top_bar_facebook;
        $setting->top_bar_twitter = $request->top_bar_twitter;
        $setting->top_bar_linkedin = $request->top_bar_linkedin;
        $setting->top_bar_instagram = $request->top_bar_instagram;

        $setting->save();
        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    public function footer()
    {
        $setting = Setting::where('id',1)->first();
        return view('admin.setting.footer',compact('setting'));
    }

    public function footer_update(Request $request)
    {
        $setting = Setting::where('id',1)->first();

        $setting->footer_facebook = $request->footer_facebook;
        $setting->footer_twitter = $request->footer_twitter;
        $setting->footer_linkedin = $request->footer_linkedin;
        $setting->footer_instagram = $request->footer_instagram;
        $setting->footer_phone = $request->footer_phone;
        $setting->footer_email = $request->footer_email;
        $setting->footer_copyright = $request->footer_copyright;
        $setting->footer_copyright_sv = $request->footer_copyright_sv;
        $setting->footer_about_text = $request->footer_about_text;
        $setting->footer_about_text_sv = $request->footer_about_text_sv;

        $setting->save();
        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    public function theme_color()
    {
        $setting = Setting::where('id',1)->first();
        return view('admin.setting.theme_color',compact('setting'));
    }

    public function theme_color_update(Request $request)
    {
        $setting = Setting::where('id',1)->first();

        $setting->theme_color = $request->theme_color;
        $setting->theme_ink = $request->theme_ink;
        $setting->theme_signal = $request->theme_signal;
        $setting->theme_hope = $request->theme_hope;

        $setting->save();
        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    public function layout_direction()
    {
        $setting = Setting::where('id',1)->first();
        return view('admin.setting.layout_direction',compact('setting'));
    }

    public function layout_direction_update(Request $request)
    {
        $setting = Setting::where('id',1)->first();

        $setting->layout_direction = $request->layout_direction;

        $setting->save();
        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    public function paypal()
    {
        $setting = Setting::where('id',1)->first();
        return view('admin.setting.paypal',compact('setting'));
    }

    public function paypal_update(Request $request)
    {
        $request->validate([
            'paypal_mode' => 'required|in:sandbox,live',
            'paypal_currency' => 'required|string|max:10',
        ]);

        $setting = Setting::where('id',1)->first();

        $setting->paypal_mode = $request->paypal_mode;
        $setting->paypal_client_id = $request->paypal_client_id;
        $setting->paypal_client_secret = $request->paypal_client_secret;
        $setting->paypal_currency = strtoupper($request->paypal_currency);

        $setting->save();
        return redirect()->back()->with('success', 'Item updated successfully.');
    }
}
