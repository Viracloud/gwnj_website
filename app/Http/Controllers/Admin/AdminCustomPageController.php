<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomPage;
use App\Models\CustomPageImage;
use App\Models\MenuItem;

class AdminCustomPageController extends Controller
{
    public function index()
    {
        $custom_pages = CustomPage::withCount('images')->orderBy('id','asc')->get();
        return view('admin.custom_page.index',compact('custom_pages'));
    }

    /**
     * Only the pages linked under the "About" menu (Who We Are, Our Mission,
     * Our Vision, Our Values, Our History, Where We Work, Research & Documentation, ...),
     * in the same order they appear in the About submenu on the site.
     */
    public function about_index()
    {
        $about = MenuItem::whereNull('parent_id')->where('label', 'About')->first();

        $page_ids = $about
            ? $about->children()->where('link_type', 'custom_page')->orderBy('order')->pluck('custom_page_id')
            : collect();

        $custom_pages = CustomPage::withCount('images')
            ->whereIn('id', $page_ids)
            ->get()
            ->sortBy(fn ($p) => $page_ids->search($p->id))
            ->values();

        $heading = 'About - Submenu Pages';
        $scope_note = 'Only the pages shown under the About submenu on the site (Who We Are, Our Mission, Our Vision, Our Values, Our History, Where We Work, Research & Documentation, ...). A new page created here still needs to be attached to the About menu from Navigation -> Menu before it appears on the site.';

        return view('admin.custom_page.index', compact('custom_pages', 'heading', 'scope_note'));
    }

    /**
     * Only the pages linked under the "Our Work" menu (Campaigns, Advocacy, ...),
     * in the same order they appear in the Our Work submenu on the site.
     * "Events & Protests" also lives under this menu but is its own module
     * (with dates, RSVPs, etc.) - manage it from Our Work -> Events instead.
     */
    public function our_work_index()
    {
        $our_work = MenuItem::whereNull('parent_id')->where('label', 'Our Work')->first();

        $page_ids = $our_work
            ? $our_work->children()->where('link_type', 'custom_page')->orderBy('order')->pluck('custom_page_id')
            : collect();

        $custom_pages = CustomPage::withCount('images')
            ->whereIn('id', $page_ids)
            ->get()
            ->sortBy(fn ($p) => $page_ids->search($p->id))
            ->values();

        $heading = 'Our Work - Submenu Pages';
        $scope_note = 'Only the pages shown under the Our Work submenu on the site (currently just Advocacy). "Campaigns" and "Events & Protests" are managed separately below (Our Work -> Campaigns / Events), since each is its own repeating module rather than a single page. A new page created here still needs to be attached to the Our Work menu from Navigation -> Menu before it appears on the site.';

        return view('admin.custom_page.index', compact('custom_pages', 'heading', 'scope_note'));
    }

    /**
     * Only the pages linked under the "News & Media" menu (currently just Media),
     * in the same order they appear in the News & Media submenu on the site.
     * News & Statements, Gallery and Publications are their own modules -
     * manage them from the other links in this sidebar group.
     */
    public function news_media_index()
    {
        $news_media = MenuItem::whereNull('parent_id')->where('label', 'News & Media')->first();

        $page_ids = $news_media
            ? $news_media->children()->where('link_type', 'custom_page')->orderBy('order')->pluck('custom_page_id')
            : collect();

        $custom_pages = CustomPage::withCount('images')
            ->whereIn('id', $page_ids)
            ->get()
            ->sortBy(fn ($p) => $page_ids->search($p->id))
            ->values();

        $heading = 'News & Media - Submenu Pages';
        $scope_note = 'Only the pages shown under the News & Media submenu on the site (currently just Media). "News & Statements" (Posts), "Gallery" and "Publications" are their own modules - manage them from the other links in this sidebar group. A new page created here still needs to be attached to the News & Media menu from Navigation -> Menu before it appears on the site.';

        return view('admin.custom_page.index', compact('custom_pages', 'heading', 'scope_note'));
    }

    /**
     * The pages linked under "Get Involved": the Get Involved landing page
     * itself (shown when the top-level menu item is clicked directly) plus
     * its submenu pages (Membership ID, Digital Membership Card, QR / Barcode
     * Verification, ...). The membership FORM is its own module - manage it
     * from the other links in this sidebar group (Users & Login).
     */
    public function get_involved_index()
    {
        $get_involved = MenuItem::whereNull('parent_id')->where('label', 'Get Involved')->first();

        $page_ids = collect();
        if ($get_involved) {
            if ($get_involved->link_type === 'custom_page' && $get_involved->custom_page_id) {
                $page_ids->push($get_involved->custom_page_id);
            }
            $page_ids = $page_ids->merge(
                $get_involved->children()->where('link_type', 'custom_page')->orderBy('order')->pluck('custom_page_id')
            );
        }

        $custom_pages = CustomPage::withCount('images')
            ->whereIn('id', $page_ids)
            ->get()
            ->sortBy(fn ($p) => $page_ids->search($p->id))
            ->values();

        $heading = 'Get Involved - Submenu Pages';
        $scope_note = 'The Get Involved landing page itself, plus its submenu pages (Membership ID, Digital Membership Card, QR / Barcode Verification, ...). The membership form and registered members are their own module - manage them from "Registered Users" below. A new page created here still needs to be attached to the Get Involved menu from Navigation -> Menu before it appears on the site.';

        return view('admin.custom_page.index', compact('custom_pages', 'heading', 'scope_note'));
    }

    /**
     * Only the pages linked under the "Partnerships" menu (Human Rights
     * Organisations, International Bodies, NGOs & Networks, ...). The
     * "Partnership Request Form" link goes to the Contact page and is
     * managed from Contact Page Settings instead.
     */
    public function partnerships_index()
    {
        $partnerships = MenuItem::whereNull('parent_id')->where('label', 'Partnerships')->first();

        $page_ids = $partnerships
            ? $partnerships->children()->where('link_type', 'custom_page')->orderBy('order')->pluck('custom_page_id')
            : collect();

        $custom_pages = CustomPage::withCount('images')
            ->whereIn('id', $page_ids)
            ->get()
            ->sortBy(fn ($p) => $page_ids->search($p->id))
            ->values();

        $heading = 'Partnerships - Submenu Pages';
        $scope_note = 'Only the pages shown under the Partnerships submenu on the site (Human Rights Organisations, International Bodies, NGOs & Networks, Research & Projects, Campaigns & Events, Our Partners). "Partnership Request Form" goes to the Contact page - manage it from Contact Page Settings. A new page created here still needs to be attached to the Partnerships menu from Navigation -> Menu before it appears on the site.';

        return view('admin.custom_page.index', compact('custom_pages', 'heading', 'scope_note'));
    }

    /**
     * The pages linked under "Contact": Transparency & Accountability.
     * FAQ is its own module - manage it from the FAQs link in this sidebar group.
     */
    public function contact_index()
    {
        $contact = MenuItem::whereNull('parent_id')->where('label', 'Contact')->first();

        $page_ids = $contact
            ? $contact->children()->where('link_type', 'custom_page')->orderBy('order')->pluck('custom_page_id')
            : collect();

        $custom_pages = CustomPage::withCount('images')
            ->whereIn('id', $page_ids)
            ->get()
            ->sortBy(fn ($p) => $page_ids->search($p->id))
            ->values();

        $heading = 'Contact - Submenu Pages';
        $scope_note = 'Only the pages shown under the Contact submenu on the site (currently Transparency & Accountability). FAQ is its own module - manage it from the FAQs link in this sidebar group. A new page created here still needs to be attached to the Contact menu from Navigation -> Menu before it appears on the site.';

        return view('admin.custom_page.index', compact('custom_pages', 'heading', 'scope_note'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'content' => 'required|string',
        ]);

        $custom_page = new CustomPage();
        $custom_page->title = $request->title;
        $custom_page->title_sv = $request->title_sv;
        $custom_page->slug = resolve_slug('custom_pages', $request->slug, $request->title, $custom_page->id);
        $custom_page->slug_sv = resolve_slug_sv('custom_pages', $request->slug_sv, $request->title_sv, $custom_page->id);
        $custom_page->excerpt = $request->excerpt;
        $custom_page->excerpt_sv = $request->excerpt_sv;
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

        if ($request->hasFile('home_bg_image')) {
            $request->validate(['home_bg_image' => 'mimes:jpeg,png,jpg,gif,webp|max:4096']);
            $name = 'custom_page_bg_' . time() . '.' . $request->home_bg_image->extension();
            $request->home_bg_image->move(public_path('uploads'), $name);
            $custom_page->home_bg_image = $name;
        }

        $custom_page->save();

        return redirect()->back()->with('success', 'Item created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'content' => 'required|string',
        ]);

        $custom_page = CustomPage::where('id', $id)->first();
        $custom_page->title = $request->title;
        $custom_page->title_sv = $request->title_sv;
        $custom_page->slug = resolve_slug('custom_pages', $request->slug, $request->title, $custom_page->id);
        $custom_page->slug_sv = resolve_slug_sv('custom_pages', $request->slug_sv, $request->title_sv, $custom_page->id);
        $custom_page->excerpt = $request->excerpt;
        $custom_page->excerpt_sv = $request->excerpt_sv;
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

        if ($request->boolean('remove_home_bg_image') && $custom_page->home_bg_image) {
            if (file_exists(public_path('uploads/' . $custom_page->home_bg_image))) {
                unlink(public_path('uploads/' . $custom_page->home_bg_image));
            }
            $custom_page->home_bg_image = null;
        }

        if ($request->hasFile('home_bg_image')) {
            $request->validate(['home_bg_image' => 'mimes:jpeg,png,jpg,gif,webp|max:4096']);
            if ($custom_page->home_bg_image && file_exists(public_path('uploads/' . $custom_page->home_bg_image))) {
                unlink(public_path('uploads/' . $custom_page->home_bg_image));
            }
            $name = 'custom_page_bg_' . time() . '.' . $request->home_bg_image->extension();
            $request->home_bg_image->move(public_path('uploads'), $name);
            $custom_page->home_bg_image = $name;
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
        if ($custom_page->home_bg_image && file_exists(public_path('uploads/' . $custom_page->home_bg_image))) {
            unlink(public_path('uploads/' . $custom_page->home_bg_image));
        }
        foreach ($custom_page->images as $img) {
            if ($img->image && file_exists(public_path('uploads/' . $img->image))) {
                unlink(public_path('uploads/' . $img->image));
            }
        }
        $custom_page->images()->delete();
        $custom_page->delete();

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }

    public function gallery($custom_page_id)
    {
        $custom_page = CustomPage::findOrFail($custom_page_id);
        $images = $custom_page->images;
        return view('admin.custom_page.gallery', compact('custom_page', 'images'));
    }

    public function gallery_store(Request $request, $custom_page_id)
    {
        $custom_page = CustomPage::findOrFail($custom_page_id);

        $request->validate([
            'image' => 'required|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $name = 'custom_page_gallery_' . time() . '_' . uniqid() . '.' . $request->image->extension();
        $request->image->move(public_path('uploads'), $name);

        CustomPageImage::create([
            'custom_page_id' => $custom_page->id,
            'image' => $name,
            'caption' => $request->caption,
            'caption_sv' => $request->caption_sv,
            'sort_order' => (int) $request->sort_order,
        ]);

        return redirect()->back()->with('success', 'Image added successfully.');
    }

    public function gallery_update(Request $request, $image_id)
    {
        $item = CustomPageImage::findOrFail($image_id);

        if ($request->hasFile('image')) {
            $request->validate(['image' => 'mimes:jpeg,png,jpg,gif,webp|max:4096']);
            if ($item->image && file_exists(public_path('uploads/' . $item->image))) {
                unlink(public_path('uploads/' . $item->image));
            }
            $name = 'custom_page_gallery_' . time() . '_' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads'), $name);
            $item->image = $name;
        }

        $item->caption = $request->caption;
        $item->caption_sv = $request->caption_sv;
        $item->sort_order = (int) $request->sort_order;
        $item->save();

        return redirect()->back()->with('success', 'Image updated successfully.');
    }

    public function gallery_delete($image_id)
    {
        $item = CustomPageImage::findOrFail($image_id);
        if ($item->image && file_exists(public_path('uploads/' . $item->image))) {
            unlink(public_path('uploads/' . $item->image));
        }
        $item->delete();

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }
}
