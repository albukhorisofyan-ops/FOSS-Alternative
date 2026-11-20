<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProprietarySoftware;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SoftwareController extends Controller
{
    /**
     * Tampilkan daftar perangkat lunak berbayar.
     */
    public function index(): View
    {
        $software = ProprietarySoftware::with('category')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.software.index', ['software' => $software]);
    }

    /**
     * Tampilkan formulir tambah perangkat lunak.
     */
    public function create(): View
    {
        return view('admin.software.create', ['categories' => $this->categories()]);
    }

    /**
     * Simpan perangkat lunak baru.
     */
    public function store(Request $request): RedirectResponse
    {
        ProprietarySoftware::create($this->validated($request));

        return redirect()
            ->route('admin.software.index')
            ->with('status', 'Data perangkat lunak berhasil ditambahkan.');
    }

    /**
     * Tampilkan formulir ubah perangkat lunak.
     */
    public function edit(ProprietarySoftware $software): View
    {
        return view('admin.software.edit', [
            'software' => $software,
            'categories' => $this->categories(),
        ]);
    }

    /**
     * Perbarui perangkat lunak.
     */
    public function update(Request $request, ProprietarySoftware $software): RedirectResponse
    {
        $software->update($this->validated($request));

        return redirect()
            ->route('admin.software.index')
            ->with('status', 'Data perangkat lunak berhasil diperbarui.');
    }

    /**
     * Hapus perangkat lunak beserta alternatifnya.
     */
    public function destroy(ProprietarySoftware $software): RedirectResponse
    {
        $software->delete();

        return redirect()
            ->route('admin.software.index')
            ->with('status', 'Data perangkat lunak berhasil dihapus.');
    }

    /**
     * Validasi isian formulir perangkat lunak.
     *
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'vendor' => ['nullable', 'string', 'max:255'],
            'price_per_year' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
        ], [
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.integer' => 'Kategori tidak valid.',
            'category_id.exists' => 'Kategori yang dipilih tidak ditemukan.',
            'name.required' => 'Nama perangkat lunak wajib diisi.',
            'name.max' => 'Nama perangkat lunak maksimal 255 karakter.',
            'vendor.max' => 'Nama vendor maksimal 255 karakter.',
            'price_per_year.required' => 'Harga per tahun wajib diisi.',
            'price_per_year.numeric' => 'Harga per tahun harus berupa angka.',
            'price_per_year.min' => 'Harga per tahun tidak boleh negatif.',
            'description.max' => 'Deskripsi maksimal 2000 karakter.',
        ]);
    }

    /**
     * Daftar kategori untuk pilihan formulir.
     */
    private function categories(): Collection
    {
        return Category::orderBy('name')->get();
    }
}
