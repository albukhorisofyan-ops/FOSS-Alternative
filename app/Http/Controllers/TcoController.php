<?php

namespace App\Http\Controllers;

use App\Models\ProprietarySoftware;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class TcoController extends Controller
{
    /**
     * Menampilkan formulir kalkulator penghematan.
     */
    public function index(Request $request): View
    {
        $softwareList = ProprietarySoftware::orderBy('name')->get();

        $selectedSoftware = $request->integer('software');

        if (! $softwareList->contains('id', $selectedSoftware)) {
            $selectedSoftware = null;
        }

        return view('tco.index', [
            'softwareList' => $softwareList,
            'form' => [
                'software' => $selectedSoftware,
                'pc' => null,
                'years' => null,
                'migration_cost' => 0,
            ],
            'result' => null,
        ]);
    }

    /**
     * Menghitung estimasi penghematan biaya lisensi.
     */
    public function hitung(Request $request): View|RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'software' => ['required', 'integer', 'exists:proprietary_software,id'],
            'pc' => ['required', 'integer', 'between:1,100000'],
            'years' => ['required', 'integer', 'between:1,10'],
            'migration_cost' => ['nullable', 'numeric', 'min:0'],
        ], [
            'software.required' => 'Pilih perangkat lunak berbayar terlebih dahulu.',
            'software.integer' => 'Perangkat lunak berbayar tidak valid.',
            'software.exists' => 'Perangkat lunak berbayar yang dipilih tidak ditemukan.',
            'pc.required' => 'Jumlah PC wajib diisi.',
            'pc.integer' => 'Jumlah PC harus berupa bilangan bulat.',
            'pc.between' => 'Jumlah PC harus antara 1 sampai 100.000.',
            'years.required' => 'Jumlah tahun wajib diisi.',
            'years.integer' => 'Jumlah tahun harus berupa bilangan bulat.',
            'years.between' => 'Jumlah tahun harus antara 1 sampai 10.',
            'migration_cost.numeric' => 'Biaya migrasi/pelatihan harus berupa angka.',
            'migration_cost.min' => 'Biaya migrasi/pelatihan tidak boleh negatif.',
        ], [
            'software' => 'perangkat lunak berbayar',
            'pc' => 'jumlah PC',
            'years' => 'jumlah tahun',
            'migration_cost' => 'biaya migrasi/pelatihan',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('tco.index')
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $software = ProprietarySoftware::findOrFail($validated['software']);

        $pc = (int) $validated['pc'];
        $years = (int) $validated['years'];
        $migrationCost = (float) ($validated['migration_cost'] ?? 0);

        $licenseCost = (float) $software->price_per_year * $pc * $years;
        $alternativeCost = $migrationCost;
        $savings = $licenseCost - $alternativeCost;

        return view('tco.index', [
            'softwareList' => ProprietarySoftware::orderBy('name')->get(),
            'form' => [
                'software' => $software->id,
                'pc' => $pc,
                'years' => $years,
                'migration_cost' => $migrationCost,
            ],
            'result' => [
                'software' => $software,
                'pc' => $pc,
                'years' => $years,
                'license_cost' => $licenseCost,
                'alternative_cost' => $alternativeCost,
                'savings' => $savings,
            ],
        ]);
    }
}
