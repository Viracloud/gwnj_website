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

        if($request->boolean('remove_banner') && $setting->banner) {
            if(file_exists(public_path('uploads/'.$setting->banner))) {
                unlink(public_path('uploads/'.$setting->banner));
            }
            $setting->banner = null;
        }

        if($request->hasFile('banner')) {
            $request->validate(['banner' => 'mimes:jpeg,png,jpg,gif,webp|max:4096']);
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
        $setting->footer_website = $request->footer_website;
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
        $setting->membership_dues_amount = $request->membership_dues_amount !== '' ? $request->membership_dues_amount : null;

        $setting->save();
        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    public function seo()
    {
        $setting = Setting::where('id',1)->first();
        return view('admin.setting.seo',compact('setting'));
    }

    public function seo_update(Request $request)
    {
        $request->validate([
            'seo_og_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $setting = Setting::where('id',1)->first();

        $setting->seo_title_template = $request->seo_title_template;
        $setting->seo_default_description = $request->seo_default_description;
        $setting->seo_default_description_sv = $request->seo_default_description_sv;
        $setting->seo_google_verification = $request->seo_google_verification;
        $setting->seo_analytics_id = $request->seo_analytics_id;
        $setting->seo_robots_indexable = $request->has('seo_robots_indexable');

        if ($request->hasFile('seo_og_image')) {
            $final_name = 'seo_og_'.time().'.'.$request->seo_og_image->extension();
            if ($setting->seo_og_image && file_exists(public_path('uploads/'.$setting->seo_og_image))) {
                unlink(public_path('uploads/'.$setting->seo_og_image));
            }
            $request->seo_og_image->move(public_path('uploads'), $final_name);
            $setting->seo_og_image = $final_name;
        }

        $setting->save();
        return redirect()->back()->with('success', 'SEO settings updated successfully.');
    }

    public function sitemap()
    {
        $setting = Setting::where('id',1)->first();
        $urls = sitemap_entries($setting);
        return view('admin.setting.sitemap', compact('setting', 'urls'));
    }

    public function sitemap_update(Request $request)
    {
        $request->validate([
            'sitemap_priority_static' => 'required|numeric|min:0|max:1',
            'sitemap_priority_pages' => 'required|numeric|min:0|max:1',
            'sitemap_priority_events' => 'required|numeric|min:0|max:1',
            'sitemap_priority_posts' => 'required|numeric|min:0|max:1',
            'sitemap_priority_campaigns' => 'required|numeric|min:0|max:1',
            'sitemap_priority_members' => 'required|numeric|min:0|max:1',
        ]);

        $setting = Setting::where('id',1)->first();

        $setting->sitemap_include_static = $request->has('sitemap_include_static');
        $setting->sitemap_include_pages = $request->has('sitemap_include_pages');
        $setting->sitemap_include_events = $request->has('sitemap_include_events');
        $setting->sitemap_include_posts = $request->has('sitemap_include_posts');
        $setting->sitemap_include_campaigns = $request->has('sitemap_include_campaigns');
        $setting->sitemap_include_members = $request->has('sitemap_include_members');

        $setting->sitemap_priority_static = $request->sitemap_priority_static;
        $setting->sitemap_priority_pages = $request->sitemap_priority_pages;
        $setting->sitemap_priority_events = $request->sitemap_priority_events;
        $setting->sitemap_priority_posts = $request->sitemap_priority_posts;
        $setting->sitemap_priority_campaigns = $request->sitemap_priority_campaigns;
        $setting->sitemap_priority_members = $request->sitemap_priority_members;

        $setting->save();
        return redirect()->back()->with('success', 'Sitemap settings updated successfully.');
    }
}
