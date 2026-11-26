<?php

namespace App\Http\Controllers;

use App\Models\ProprietarySoftware;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TcoController extends Controller
{
    /**
     * Menampilkan formulir kalkulator penghematan.
     */
    public function index(Request $request): View
    {
        $softwareList = ProprietarySoftware::orderBy('name')->get();

        // Pra-pilih perangkat lunak jika parameter ?software= dikirim
        $selectedSoftwareId = $request->query('software');

        return view('tco.index', [
            'softwareList' => $softwareList,
            'selectedSoftwareId' => $selectedSoftwareId,
            'result' => null,
        ]);
    }

    /**
     * Menghitung estimasi penghematan biaya.
     */
    public function hitung(Request $request): View
    {
        $validated = $request->validate([
            'software_id' => ['required', 'exists:proprietary_software,id'],
            'jumlah_pc' => ['required', 'integer', 'min:1', 'max:100000'],
            'jumlah_tahun' => ['required', 'integer', 'min:1', 'max:10'],
            'biaya_migrasi' => ['nullable', 'numeric', 'min:0'],
        ], [
            'software_id.required' => 'Pilih perangkat lunak berbayar.',
            'software_id.exists' => 'Perangkat lunak tidak ditemukan di database.',
            'jumlah_pc.required' => 'Jumlah PC wajib diisi.',
            'jumlah_pc.integer' => 'Jumlah PC harus berupa bilangan bulat.',
            'jumlah_pc.min' => 'Jumlah PC minimal 1.',
            'jumlah_pc.max' => 'Jumlah PC maksimal 100.000.',
            'jumlah_tahun.required' => 'Jumlah tahun wajib diisi.',
            'jumlah_tahun.integer' => 'Jumlah tahun harus berupa bilangan bulat.',
            'jumlah_tahun.min' => 'Jumlah tahun minimal 1.',
            'jumlah_tahun.max' => 'Jumlah tahun maksimal 10.',
            'biaya_migrasi.numeric' => 'Biaya migrasi harus berupa angka.',
            'biaya_migrasi.min' => 'Biaya migrasi tidak boleh negatif.',
        ]);

        $software = ProprietarySoftware::findOrFail($validated['software_id']);
        $jumlahPc = (int) $validated['jumlah_pc'];
        $jumlahTahun = (int) $validated['jumlah_tahun'];
        $biayaMigrasi = (float) ($validated['biaya_migrasi'] ?? 0);

        // Rumus perhitungan
        $biayaLisensi = $software->price_per_year * $jumlahPc * $jumlahTahun;
        $biayaFoss = $biayaMigrasi;
        $penghematan = $biayaLisensi - $biayaFoss;

        $softwareList = ProprietarySoftware::orderBy('name')->get();

        return view('tco.index', [
            'softwareList' => $softwareList,
            'selectedSoftwareId' => $validated['software_id'],
            'result' => [
                'software_name' => $software->name,
                'biaya_lisensi' => $biayaLisensi,
                'biaya_foss' => $biayaFoss,
                'penghematan' => $penghematan,
            ],
            'input' => $validated,
        ]);
    }
}
