<?php

namespace App\Http\Controllers;

use App\Models\ProprietarySoftware;
use App\Models\Suggestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuggestionController extends Controller
{
    /**
     * Menampilkan formulir usulan alternatif open source dari pengunjung.
     */
    public function create(): View
    {
        $softwareList = ProprietarySoftware::orderBy('name')->get();

        return view('suggestions.create', [
            'softwareList' => $softwareList,
        ]);
    }

    /**
     * Menyimpan usulan alternatif baru ke database dengan status pending.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'proprietary_name' => ['required', 'string', 'exists:proprietary_software,name'],
            'website' => ['nullable', 'url', 'max:255'],
            'note' => ['nullable', 'string', 'max:2000'],
        ], [
            'name.required' => 'Nama alternatif open source wajib diisi.',
            'name.max' => 'Nama alternatif maksimal 255 karakter.',
            'proprietary_name.required' => 'Perangkat lunak berbayar yang digantikan wajib dipilih.',
            'proprietary_name.exists' => 'Perangkat lunak berbayar harus dipilih dari daftar katalog.',
            'website.url' => 'Situs web harus berupa URL yang valid (mis. https://example.org).',
            'website.max' => 'URL situs web maksimal 255 karakter.',
            'note.max' => 'Catatan maksimal 2000 karakter.',
        ]);

        Suggestion::create([
            ...$validated,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('suggestions.create')
            ->with('status', 'Terima kasih! Usulan alternatif Anda telah diterima dan menunggu peninjauan admin.');
    }

    /**
     * Menampilkan daftar usulan untuk dimoderasi oleh admin.
     */
    public function adminIndex(): View
    {
        $suggestions = Suggestion::where('status', 'pending')
            ->latest()
            ->paginate(15);

        return view('admin.suggestions.index', [
            'suggestions' => $suggestions,
        ]);
    }

    /**
     * Memperbarui status moderasi usulan (approved atau rejected).
     */
    public function updateStatus(Request $request, Suggestion $suggestion): RedirectResponse
    {
        if ($suggestion->status !== 'pending') {
            return redirect()
                ->route('admin.suggestions.index')
                ->with('error', "Status usulan \"{$suggestion->name}\" sudah ditetapkan dan tidak dapat diubah lagi.");
        }

        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
        ], [
            'status.required' => 'Status moderasi wajib diisi.',
            'status.in' => 'Status moderasi harus approved atau rejected.',
        ]);

        $suggestion->update(['status' => $validated['status']]);

        $label = $validated['status'] === 'approved' ? 'disetujui' : 'ditolak';

        return redirect()
            ->route('admin.suggestions.index')
            ->with('status', "Usulan \"{$suggestion->name}\" berhasil {$label}.");
    }
}
