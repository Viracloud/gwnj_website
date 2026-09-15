<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Publication;
use App\Models\PublicationCategory;

class AdminPublicationController extends Controller
{
    public function index()
    {
        $publications = Publication::orderBy('published_date', 'desc')->get();
        $publication_categories = PublicationCategory::orderBy('name', 'asc')->get();
        return view('admin.publication.index', compact('publications', 'publication_categories'));
    }

    private function validated(Request $request, $ignoreId = null): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'title_sv' => 'nullable|string|max:255',
            'slug' => 'required|string|max:255|unique:publications,slug,' . $ignoreId,
            'publication_category_id' => 'required|exists:publication_categories,id',
            'description' => 'nullable|string',
            'description_sv' => 'nullable|string',
            'published_date' => 'nullable|date',
            'status' => 'required|in:Show,Hide',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $publication = new Publication($data);

        if ($request->hasFile('cover_image')) {
            $request->validate(['cover_image' => 'mimes:jpeg,png,jpg,gif|max:2048']);
            $name = 'publication_cover_' . time() . '.' . $request->cover_image->extension();
            $request->cover_image->move(public_path('uploads'), $name);
            $publication->cover_image = $name;
        }

        if ($request->hasFile('file')) {
            $request->validate(['file' => 'mimes:pdf|max:10240']);
            $name = 'publication_file_' . time() . '.' . $request->file->extension();
            $request->file->move(public_path('uploads'), $name);
            $publication->file = $name;
        }

        $publication->save();

        return redirect()->back()->with('success', 'Publication created successfully.');
    }

    public function update(Request $request, $id)
    {
        $publication = Publication::findOrFail($id);
        $data = $this->validated($request, $id);
        $publication->fill($data);

        if ($request->hasFile('cover_image')) {
            $request->validate(['cover_image' => 'mimes:jpeg,png,jpg,gif|max:2048']);
            if ($publication->cover_image && file_exists(public_path('uploads/' . $publication->cover_image))) {
                unlink(public_path('uploads/' . $publication->cover_image));
            }
            $name = 'publication_cover_' . time() . '.' . $request->cover_image->extension();
            $request->cover_image->move(public_path('uploads'), $name);
            $publication->cover_image = $name;
        }

        if ($request->hasFile('file')) {
            $request->validate(['file' => 'mimes:pdf|max:10240']);
            if ($publication->file && file_exists(public_path('uploads/' . $publication->file))) {
                unlink(public_path('uploads/' . $publication->file));
            }
            $name = 'publication_file_' . time() . '.' . $request->file->extension();
            $request->file->move(public_path('uploads'), $name);
            $publication->file = $name;
        }

        $publication->save();

        return redirect()->back()->with('success', 'Publication updated successfully.');
    }

    public function delete($id)
    {
        $publication = Publication::findOrFail($id);

        if ($publication->cover_image && file_exists(public_path('uploads/' . $publication->cover_image))) {
            unlink(public_path('uploads/' . $publication->cover_image));
        }
        if ($publication->file && file_exists(public_path('uploads/' . $publication->file))) {
            unlink(public_path('uploads/' . $publication->file));
        }

        $publication->delete();

        return redirect()->back()->with('success', 'Publication deleted successfully.');
    }
}
