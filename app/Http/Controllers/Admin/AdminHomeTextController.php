<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeText;
use Illuminate\Http\Request;

class AdminHomeTextController extends Controller
{
    public function index()
    {
        $sections = config('home_content.sections');
        $rows = HomeText::all()->keyBy('key');
        return view('admin.home_text.index', compact('sections', 'rows'));
    }

    public function update(Request $request)
    {
        $save = function (string $key, ?string $value, ?string $value_fa = null) {
            $value = ($value !== null && trim($value) !== '') ? trim($value) : null;
            $value_fa = ($value_fa !== null && trim($value_fa) !== '') ? trim($value_fa) : null;
            if ($value === null && $value_fa === null) {
                HomeText::where('key', $key)->delete();
                return;
            }
            HomeText::updateOrCreate(['key' => $key], ['value' => $value, 'value_fa' => $value_fa]);
        };

        foreach (config('home_content.sections') as $section) {
            if (!empty($section['toggle'])) {
                $save($section['toggle'], $request->input('toggle.' . $section['toggle']) === '0' ? '0' : null);
            }
            foreach ($section['fields'] as $key => $def) {
                if (in_array($def['type'], ['shared', 'link'])) {
                    $save($key, $request->input("val.$key"));
                    continue;
                }
                $en = $request->input("en.$key");
                $fa = $request->input("fa.$key");
                $save(
                    $key,
                    ($en !== null && trim($en) === trim($def['en'])) ? null : $en,
                    ($fa !== null && trim($fa) === trim($def['fa'])) ? null : $fa
                );
            }
        }

        // Extra languages (French, German ...): empty means "use the English text".
        foreach (extra_locales() as $code) {
            $column = 'value_'.$code;
            if (!\Illuminate\Support\Facades\Schema::hasColumn('home_texts', $column)) {
                continue;
            }
            foreach ((array) $request->input("xl_text.$code", []) as $key => $text) {
                if (!is_string($key) || !array_key_exists($key, home_content_fields())) {
                    continue;
                }
                $text = is_string($text) && trim($text) !== '' ? trim($text) : null;
                $row = HomeText::firstOrNew(['key' => $key]);
                if (!$row->exists && $text === null) {
                    continue;
                }
                $row->{$column} = $text;
                $row->save();
            }
        }

        return redirect()->back()->with('success', 'Home page texts saved.');
    }
}
