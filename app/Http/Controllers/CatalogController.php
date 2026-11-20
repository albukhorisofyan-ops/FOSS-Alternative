<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ProprietarySoftware;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    /**
     * Menampilkan katalog perangkat lunak berbayar dengan pencarian dan filter.
     */
    public function index(Request $request): View
    {
        $query = ProprietarySoftware::with(['category', 'fossAlternatives']);

        if ($request->filled('q')) {
            $search = trim((string) $request->query('q'));
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('kategori')) {
            $query->where('category_id', $request->query('kategori'));
        }

        $softwareList = $query->orderBy('name')->paginate(9)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('catalog.index', [
            'softwareList' => $softwareList,
            'categories' => $categories,
            'q' => $request->query('q'),
            'selectedCategory' => $request->query('kategori'),
        ]);
    }

    /**
     * Menampilkan detail perangkat lunak berbayar dan alternatif FOSS-nya.
     */
    public function show(ProprietarySoftware $software): View
    {
        $software->load(['category', 'fossAlternatives']);

        return view('catalog.show', [
            'software' => $software,
        ]);
    }
}
