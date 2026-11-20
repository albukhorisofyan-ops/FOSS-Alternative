@extends('layouts.app')

@section('title', 'Kalkulator Penghematan Biaya - AlterFOSS')

@section('content')
@php
    $selectedSoftware = old('software', $form['software']);
    $pcValue = old('pc', $form['pc']);
    $yearsValue = old('years', $form['years']);
    $migrationCostValue = old('migration_cost', $form['migration_cost']);
@endphp

<div class="row mb-4">
    <div class="col-lg-8 mx-auto text-center">
        <h1 class="display-6 fw-bold mb-2">Kalkulator Penghematan Biaya</h1>
        <p class="text-muted mb-0">
            Bandingkan biaya lisensi perangkat lunak berbayar dengan biaya migrasi ke alternatif open source.
        </p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body p-4">
                <h2 class="h5 fw-bold mb-3">Skenario Perhitungan</h2>

                <form method="POST" action="{{ route('tco.hitung') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="software" class="form-label fw-semibold">Perangkat lunak berbayar</label>
                        <select class="form-select @error('software') is-invalid @enderror" id="software" name="software" required>
                            <option value="">-- Pilih perangkat lunak --</option>
                            @foreach ($softwareList as $item)
                                <option value="{{ $item->id }}" @selected((string) $selectedSoftware === (string) $item->id)>
                                    {{ $item->name }} &middot; Rp {{ number_format($item->price_per_year, 0, ',', '.') }}/tahun
                                </option>
                            @endforeach
                        </select>
                        @error('software')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="pc" class="form-label fw-semibold">Jumlah PC</label>
                            <input
                                type="number"
                                class="form-control @error('pc') is-invalid @enderror"
                                id="pc"
                                name="pc"
                                value="{{ $pcValue }}"
                                min="1"
                                max="100000"
                                step="1"
                                inputmode="numeric"
                                placeholder="Contoh: 25"
                                required
                            >
                            @error('pc')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="years" class="form-label fw-semibold">Jumlah tahun</label>
                            <input
                                type="number"
                                class="form-control @error('years') is-invalid @enderror"
                                id="years"
                                name="years"
                                value="{{ $yearsValue }}"
                                min="1"
                                max="10"
                                step="1"
                                inputmode="numeric"
                                placeholder="Contoh: 3"
                                required
                            >
                            @error('years')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="migration_cost" class="form-label fw-semibold">
                            Biaya migrasi/pelatihan FOSS (Rp)
                            <span class="text-muted fw-normal">(opsional)</span>
                        </label>
                        <input
                            type="number"
                            class="form-control @error('migration_cost') is-invalid @enderror"
                            id="migration_cost"
                            name="migration_cost"
                            value="{{ $migrationCostValue }}"
                            min="0"
                            step="any"
                            inputmode="decimal"
                            placeholder="0"
                        >
                        <div class="form-text">Kosongkan bila tidak ada biaya migrasi atau pelatihan.</div>
                        @error('migration_cost')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success w-100 fw-semibold mt-4">
                        Hitung Penghematan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        @if ($result)
            <div class="card shadow-sm h-100 border-0 border-top {{ $result['savings'] > 0 ? 'border-success' : 'border-warning' }} border-4">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-1">Hasil Estimasi</h2>
                    <p class="text-muted small mb-3">
                        {{ $result['software']->name }} &middot;
                        {{ number_format($result['pc'], 0, ',', '.') }} PC &middot;
                        {{ $result['years'] }} tahun
                    </p>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-3">
                            <tbody>
                                <tr>
                                    <th scope="row" class="fw-normal text-muted">Biaya lisensi berbayar</th>
                                    <td class="text-end fw-semibold">Rp {{ number_format($result['license_cost'], 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="fw-normal text-muted">Biaya alternatif open source</th>
                                    <td class="text-end fw-semibold">Rp {{ number_format($result['alternative_cost'], 0, ',', '.') }}</td>
                                </tr>
                                <tr class="{{ $result['savings'] > 0 ? 'table-success' : 'table-warning' }}">
                                    <th scope="row">Estimasi penghematan</th>
                                    <td class="text-end fw-bold">Rp {{ number_format($result['savings'], 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    @if ($result['savings'] > 0)
                        <div class="alert alert-success mb-3">
                            Dengan skenario ini, organisasi Anda berpotensi menghemat
                            <strong>Rp {{ number_format($result['savings'], 0, ',', '.') }}</strong>
                            dibandingkan menggunakan lisensi berbayar.
                        </div>
                    @else
                        <div class="alert alert-warning mb-3" role="alert">
                            <strong>Pada skenario ini belum ada penghematan.</strong>
                            Biaya migrasi/pelatihan sama dengan atau lebih besar dari biaya lisensi berbayar.
                        </div>
                    @endif

                    <p class="small text-muted mb-0">
                        Catatan: ini estimasi sederhana. Lisensi alternatif open source dianggap Rp0
                        dan biaya operasional lain belum diperhitungkan.
                    </p>
                </div>
            </div>
        @else
            <div class="card shadow-sm h-100">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-3">Cara Kerja</h2>
                    <ol class="small text-secondary ps-3 mb-4">
                        <li class="mb-2">Pilih perangkat lunak berbayar yang ingin diganti.</li>
                        <li class="mb-2">Masukkan jumlah PC dan lama penggunaan dalam tahun.</li>
                        <li class="mb-2">Isi biaya migrasi atau pelatihan bila ada (opsional).</li>
                        <li>Tekan <strong>Hitung Penghematan</strong> untuk melihat hasilnya.</li>
                    </ol>

                    <div class="p-3 bg-light rounded border small">
                        <div class="fw-semibold mb-1">Rumus yang dipakai</div>
                        <div class="text-secondary">Biaya lisensi = harga per tahun &times; jumlah PC &times; jumlah tahun</div>
                        <div class="text-secondary">Penghematan = biaya lisensi &minus; biaya migrasi</div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
