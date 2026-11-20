<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuggestionController extends Controller
{
    /**
     * Tampilkan formulir usulan untuk pengunjung.
     */
    public function create(): View
    {
        return view('suggestions.create');
    }

    /**
     * Simpan usulan baru dari pengunjung.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'proprietary_name' => ['required', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'note' => ['nullable', 'string', 'max:2000'],
        ], [
            'name.required' => 'Nama alternatif wajib diisi.',
            'name.max' => 'Nama alternatif maksimal 255 karakter.',
            'proprietary_name.required' => 'Nama perangkat lunak berbayar wajib diisi.',
            'proprietary_name.max' => 'Nama perangkat lunak berbayar maksimal 255 karakter.',
            'website.url' => 'Situs harus berupa URL yang valid, misalnya https://contoh.org.',
            'website.max' => 'Situs maksimal 255 karakter.',
            'note.max' => 'Catatan maksimal 2000 karakter.',
        ]);

        // Status selalu dimulai dari pending; pengunjung tidak boleh menentukannya.
        $validated['status'] = 'pending';

        Suggestion::create($validated);

        return redirect()
            ->route('suggestions.create')
            ->with('status', 'Terima kasih! Usulan Anda sudah kami terima dan akan ditinjau admin.');
    }
}
