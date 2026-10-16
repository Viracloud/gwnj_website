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
        $save = function (string $key, ?string $value, ?string $value_sv = null) {
            $value = ($value !== null && trim($value) !== '') ? trim($value) : null;
            $value_sv = ($value_sv !== null && trim($value_sv) !== '') ? trim($value_sv) : null;
            if ($value === null && $value_sv === null) {
                HomeText::where('key', $key)->delete();
                return;
            }
            HomeText::updateOrCreate(['key' => $key], ['value' => $value, 'value_sv' => $value_sv]);
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

        return redirect()->back()->with('success', 'Home page texts saved.');
    }
}
