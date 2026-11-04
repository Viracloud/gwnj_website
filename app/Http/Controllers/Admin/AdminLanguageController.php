<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteLanguage;
use App\Support\LocaleColumns;
use Illuminate\Http\Request;

/**
 * Settings → Languages: add, edit and remove website languages and choose the default one.
 * English is the base language (its text lives in the plain columns), so it can be
 * renamed but never switched off or removed.
 */
class AdminLanguageController extends Controller
{
    public function index()
    {
        $languages = SiteLanguage::orderBy('sort_order')->orderBy('id')->get();
        foreach ($languages as $lang) {
            $lang->progress = $lang->code === base_locale() ? null : LocaleColumns::completeness($lang->code);
        }
        return view('admin.setting.languages', compact('languages'));
    }

    public function store(Request $request)
    {
        $request->merge(['code' => strtolower(trim((string) $request->code))]);
        $request->validate([
            // Codes that are also common column endings (post_category_id, card_expires_at ...) are refused.
            'code' => 'required|regex:/^[a-z]{2,3}$/|not_in:id,at,by,to,on,in,is,no,or,ip,url,key|unique:site_languages,code',
            'name' => 'required|string|max:60',
            'native' => 'required|string|max:60',
            'dir' => 'required|in:ltr,rtl',
            'status' => 'required|in:published,prepare,off',
            'sort_order' => 'nullable|integer|min:0|max:999',
        ], [
            'code.regex' => 'The language code must be 2 or 3 letters, e.g. es, ps, ar.',
            'code.unique' => 'This language already exists.',
            'code.not_in' => 'This code is reserved by the system. Please use another code for this language.',
        ]);

        // Dari's columns come with the site; every other language gets its own translation columns.
        if ($request->code !== 'fa') {
            LocaleColumns::add($request->code);
        }

        SiteLanguage::create([
            'code' => $request->code,
            'name' => $request->name,
            'native' => $request->native,
            'dir' => $request->dir,
            'status' => $request->status,
            'sort_order' => (int) $request->sort_order,
        ]);

        return redirect()->back()->with('success', 'Language added.');
    }

    public function update(Request $request, $id)
    {
        $lang = SiteLanguage::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:60',
            'native' => 'required|string|max:60',
            'dir' => 'required|in:ltr,rtl',
            'status' => 'required|in:published,prepare,off',
            'sort_order' => 'nullable|integer|min:0|max:999',
        ]);

        $status = $lang->code === base_locale() ? 'published' : $request->status;
        if ($lang->is_default && $status !== 'published') {
            return redirect()->back()->with('error', 'This is the default language. Choose another default language before switching it off.');
        }

        $lang->update([
            'name' => $request->name,
            'native' => $request->native,
            'dir' => $request->dir,
            'status' => $status,
            'sort_order' => (int) $request->sort_order,
        ]);

        return redirect()->back()->with('success', 'Language updated.');
    }

    public function make_default($id)
    {
        $lang = SiteLanguage::findOrFail($id);
        if ($lang->status !== 'published') {
            return redirect()->back()->with('error', 'Only a published language can be the default.');
        }
        SiteLanguage::where('id', '!=', $lang->id)->update(['is_default' => false]);
        $lang->update(['is_default' => true]);

        return redirect()->back()->with('success', $lang->name.' is now the default language.');
    }

    public function delete($id)
    {
        $lang = SiteLanguage::findOrFail($id);
        if ($lang->code === base_locale()) {
            return redirect()->back()->with('error', 'English is the main language of the site and cannot be removed.');
        }
        if ($lang->is_default) {
            return redirect()->back()->with('error', 'This is the default language. Choose another default language before removing it.');
        }
        // Only the language is removed; its translations stay in the database,
        // so adding the same code again brings them back.
        $lang->delete();

        return redirect()->back()->with('success', 'Language removed.');
    }
}
