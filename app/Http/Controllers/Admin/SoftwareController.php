<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProprietarySoftware;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SoftwareController extends Controller
{
    /**
     * Menampilkan daftar perangkat lunak berbayar untuk admin.
     */
    public function index(): View
    {
        $softwareList = ProprietarySoftware::with('category')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.software.index', [
            'softwareList' => $softwareList,
        ]);
    }

    /**
     * Menampilkan formulir tambah perangkat lunak berbayar.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.software.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Menyimpan perangkat lunak berbayar baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateSoftware($request);

        ProprietarySoftware::create($validated);

        return redirect()
            ->route('admin.software.index')
            ->with('status', 'Perangkat lunak berbayar berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir ubah perangkat lunak berbayar.
     */
    public function edit(ProprietarySoftware $software): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.software.edit', [
            'software' => $software,
            'categories' => $categories,
        ]);
    }

    /**
     * Memperbarui data perangkat lunak berbayar.
     */
    public function update(Request $request, ProprietarySoftware $software): RedirectResponse
    {
        $validated = $this->validateSoftware($request);

        $software->update($validated);

        return redirect()
            ->route('admin.software.index')
            ->with('status', 'Data perangkat lunak berbayar berhasil diperbarui.');
    }

    /**
     * Menghapus perangkat lunak berbayar dari database.
     */
    public function destroy(ProprietarySoftware $software): RedirectResponse
    {
        $software->delete();

        return redirect()
            ->route('admin.software.index')
            ->with('status', 'Perangkat lunak berbayar berhasil dihapus.');
    }

    /**
     * Validasi input perangkat lunak berbayar.
     *
     * @return array<string, mixed>
     */
    private function validateSoftware(Request $request): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'vendor' => ['nullable', 'string', 'max:255'],
            'price_per_year' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
        ], [
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori tidak valid.',
            'name.required' => 'Nama perangkat lunak wajib diisi.',
            'name.max' => 'Nama perangkat lunak maksimal 255 karakter.',
            'vendor.max' => 'Nama vendor maksimal 255 karakter.',
            'price_per_year.required' => 'Harga per tahun wajib diisi.',
            'price_per_year.numeric' => 'Harga per tahun harus berupa angka.',
            'price_per_year.min' => 'Harga per tahun tidak boleh negatif.',
            'description.max' => 'Deskripsi maksimal 2000 karakter.',
        ]);
    }
}
