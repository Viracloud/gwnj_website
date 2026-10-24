<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\CustomPage;

class AdminMenuController extends Controller
{
    public function index()
    {
        $menu = Menu::where('id', 1)->first();
        $top_items = MenuItem::whereNull('parent_id')->orderBy('order')->with(['children' => function ($q) {
            $q->orderBy('order');
        }])->get();
        $parent_options = MenuItem::whereNull('parent_id')->orderBy('order')->get();
        $custom_pages = CustomPage::orderBy('title')->get();
        $available_routes = MenuItem::availableRoutes();

        return view('admin.menu.index', compact('menu', 'top_items', 'parent_options', 'custom_pages', 'available_routes'));
    }

    public function update_login(Request $request)
    {
        $obj = Menu::where('id', 1)->first();
        $obj->menu_login_name = $request->menu_login_name;
        $obj->menu_login_name_fa = $request->menu_login_name_fa;
        $obj->menu_login_status = $request->menu_login_status;
        $obj->save();

        return redirect()->back()->with('success', 'Login button updated successfully.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'label' => 'required|string|max:255',
            'label_fa' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id',
            'link_type' => 'required|in:route,custom_page,url',
            'route_name' => 'nullable|string',
            'custom_page_id' => 'nullable|exists:custom_pages,id',
            'url' => 'nullable|string|max:2048',
            'open_new_tab' => 'nullable',
            'is_button' => 'nullable',
            'show_in_footer' => 'nullable',
            'footer_column' => 'required|in:about,work',
            'order' => 'nullable|integer',
            'status' => 'required|in:Show,Hide',
        ]);

        $data['open_new_tab'] = $request->has('open_new_tab');
        $data['is_button'] = $request->has('is_button');
        $data['show_in_footer'] = $request->has('show_in_footer');
        $data['order'] = $data['order'] ?? 0;

        // empty-string selects ("— Top level —", "— Select —") must become
        // real NULLs, not '' — the DB columns are nullable foreign keys/ints
        foreach (['parent_id', 'custom_page_id', 'route_name', 'url'] as $field) {
            if (($data[$field] ?? null) === '') {
                $data[$field] = null;
            }
        }

        // only keep the field relevant to the chosen link_type, so a value
        // left over from switching the dropdown doesn't get saved by mistake
        if ($data['link_type'] !== 'route') {
            $data['route_name'] = null;
        }
        if ($data['link_type'] !== 'custom_page') {
            $data['custom_page_id'] = null;
        }
        if ($data['link_type'] !== 'url') {
            $data['url'] = null;
        }

        return $data;
    }

    public function store(Request $request)
    {
        MenuItem::create($this->validated($request));

        return redirect()->back()->with('success', 'Menu item created successfully.');
    }

    public function update(Request $request, $id)
    {
        $item = MenuItem::findOrFail($id);

        // a menu item cannot become its own parent (and, to keep the UI to
        // two levels, only top-level items are offered as parents anyway)
        $data = $this->validated($request);
        if ((int) ($data['parent_id'] ?? 0) === (int) $id) {
            $data['parent_id'] = null;
        }

        $item->update($data);

        return redirect()->back()->with('success', 'Menu item updated successfully.');
    }

    public function delete($id)
    {
        MenuItem::where('id', $id)->delete();

        return redirect()->back()->with('success', 'Menu item deleted successfully.');
    }
}
