@extends('layouts.app')

@section('title', 'Kalkulator Penghematan - AlterFOSS')

@push('styles')
<style>
    .af-result-number {
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        font-variant-numeric: tabular-nums;
    }
    .af-result-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.25rem;
        border-radius: .6rem;
        margin-bottom: .5rem;
    }
    .af-meter {
        height: 8px;
        border-radius: 99px;
        background: #e2e8f0;
        overflow: hidden;
        margin-top: .75rem;
    }
    .af-meter-fill {
        height: 100%;
        border-radius: 99px;
        transition: width 0.8s cubic-bezier(.4,0,.2,1);
    }
    .af-step {
        display: flex;
        align-items: flex-start;
        gap: .75rem;
        padding: .75rem 0;
    }
    .af-step-num {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--af-primary-light);
        color: var(--af-primary);
        font-weight: 700;
        font-size: .78rem;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">

        {{-- Page Header --}}
        <div class="af-page-header">
            <h1><i class="bi bi-calculator text-primary me-2"></i>Kalkulator Penghematan</h1>
            <p>Hitung estimasi penghematan biaya dengan beralih dari perangkat lunak berbayar ke alternatif open source.</p>
        </div>

        <div class="row g-4">
            {{-- Left: Form --}}
            <div class="col-lg-{{ isset($result) ? '5' : '7 mx-auto' }}">
                <div class="af-card-static p-4">
                    <h2 class="h6 fw-bold mb-3 d-flex align-items-center gap-2">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-2" style="width:30px; height:30px; background: var(--af-primary-light); color: var(--af-primary);">
                            <i class="bi bi-sliders" style="font-size:.85rem;"></i>
                        </span>
                        Parameter Perhitungan
                    </h2>

                    <form method="POST" action="{{ route('tco.hitung') }}" class="af-form">
                        @csrf

                        {{-- Pilihan Perangkat Lunak --}}
                        <div class="mb-3">
                            <label for="software_id" class="form-label">
                                Perangkat Lunak Berbayar <span class="text-danger">*</span>
                            </label>
                            <select
                                name="software_id"
                                id="software_id"
                                class="form-select @error('software_id') is-invalid @enderror"
                            >
                                <option value="">— Pilih perangkat lunak —</option>
                                @foreach ($softwareList as $sw)
                                    <option
                                        value="{{ $sw->id }}"
                                        @selected(old('software_id', $selectedSoftwareId ?? '') == $sw->id)
                                    >
                                        {{ $sw->name }}
                                        @if ($sw->vendor) ({{ $sw->vendor }}) @endif
                                        — Rp {{ number_format($sw->price_per_year, 0, ',', '.') }}/thn
                                    </option>
                                @endforeach
                            </select>
                            @error('software_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Jumlah PC & Tahun (side by side) --}}
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label for="jumlah_pc" class="form-label">
                                    Jumlah PC <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text" style="border-radius:.6rem 0 0 .6rem; background:var(--af-surface-alt); border-color: var(--af-border);">
                                        <i class="bi bi-pc-display text-secondary"></i>
                                    </span>
                                    <input
                                        type="number"
                                        name="jumlah_pc"
                                        id="jumlah_pc"
                                        class="form-control @error('jumlah_pc') is-invalid @enderror"
                                        value="{{ old('jumlah_pc', $input['jumlah_pc'] ?? '') }}"
                                        min="1"
                                        max="100000"
                                        placeholder="50"
                                        style="border-radius:0 .6rem .6rem 0;"
                                    >
                                    @error('jumlah_pc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <label for="jumlah_tahun" class="form-label">
                                    Jumlah Tahun <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text" style="border-radius:.6rem 0 0 .6rem; background:var(--af-surface-alt); border-color: var(--af-border);">
                                        <i class="bi bi-calendar3 text-secondary"></i>
                                    </span>
                                    <input
                                        type="number"
                                        name="jumlah_tahun"
                                        id="jumlah_tahun"
                                        class="form-control @error('jumlah_tahun') is-invalid @enderror"
                                        value="{{ old('jumlah_tahun', $input['jumlah_tahun'] ?? '') }}"
                                        min="1"
                                        max="10"
                                        placeholder="3"
                                        style="border-radius:0 .6rem .6rem 0;"
                                    >
                                    @error('jumlah_tahun')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Biaya Migrasi --}}
                        <div class="mb-4">
                            <label for="biaya_migrasi" class="form-label">Biaya Migrasi / Pelatihan</label>
                            <div class="input-group">
                                <span class="input-group-text" style="border-radius:.6rem 0 0 .6rem; background:var(--af-surface-alt); border-color: var(--af-border); font-size:.85rem; font-weight:600;">
                                    Rp
                                </span>
                                <input
                                    type="number"
                                    name="biaya_migrasi"
                                    id="biaya_migrasi"
                                    class="form-control @error('biaya_migrasi') is-invalid @enderror"
                                    value="{{ old('biaya_migrasi', $input['biaya_migrasi'] ?? '') }}"
                                    min="0"
                                    step="1"
                                    placeholder="0"
                                    style="border-radius:0 .6rem .6rem 0;"
                                >
                                @error('biaya_migrasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text small" style="font-size:.78rem;">Opsional. Biaya sekali bayar untuk pelatihan atau migrasi data.</div>
                        </div>

                        <button type="submit" class="af-btn af-btn-primary w-100">
                            <i class="bi bi-calculator me-2"></i>Hitung Penghematan
                        </button>
                    </form>
                </div>

                {{-- Cara Kerja (hanya tampil jika belum ada result) --}}
                @if (!isset($result))
                    <div class="af-card-static p-4 mt-4">
                        <h3 class="h6 fw-bold mb-3">
                            <i class="bi bi-question-circle text-primary me-2"></i>Bagaimana cara kerjanya?
                        </h3>
                        <div class="af-step">
                            <span class="af-step-num">1</span>
                            <div>
                                <strong class="d-block" style="font-size:.85rem;">Pilih perangkat lunak</strong>
                                <span class="text-secondary" style="font-size:.82rem;">Pilih perangkat lunak berbayar yang ingin Anda ganti.</span>
                            </div>
                        </div>
                        <div class="af-step">
                            <span class="af-step-num">2</span>
                            <div>
                                <strong class="d-block" style="font-size:.85rem;">Masukkan skenario</strong>
                                <span class="text-secondary" style="font-size:.82rem;">Tentukan jumlah PC dan periode penggunaan.</span>
                            </div>
                        </div>
                        <div class="af-step">
                            <span class="af-step-num">3</span>
                            <div>
                                <strong class="d-block" style="font-size:.85rem;">Lihat estimasi hemat</strong>
                                <span class="text-secondary" style="font-size:.82rem;">Dapatkan perbandingan biaya lisensi vs. migrasi FOSS.</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Right: Result --}}
            @isset($result)
                <div class="col-lg-7">
                    @php
                        $isPositive = $result['penghematan'] >= 0;
                        $totalCost = max($result['biaya_lisensi'], $result['biaya_foss']);
                        $pctLisensi = $totalCost > 0 ? ($result['biaya_lisensi'] / $totalCost) * 100 : 0;
                        $pctFoss = $totalCost > 0 ? ($result['biaya_foss'] / $totalCost) * 100 : 0;
                    @endphp

                    {{-- Savings hero --}}
                    <div class="af-card-static overflow-hidden">
                        <div style="height:5px; background: linear-gradient(90deg, {{ $isPositive ? 'var(--af-accent), #06b6d4' : 'var(--af-warning), var(--af-danger)' }});"></div>
                        <div class="p-4 p-md-5 text-center">
                            <div class="mb-2">
                                @if ($isPositive)
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width:56px; height:56px; background: #d1fae5;">
                                        <i class="bi bi-piggy-bank" style="font-size:1.6rem; color: var(--af-accent-dark);"></i>
                                    </span>
                                @else
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width:56px; height:56px; background: #fef3c7;">
                                        <i class="bi bi-exclamation-triangle" style="font-size:1.6rem; color: #b45309;"></i>
                                    </span>
                                @endif
                            </div>
                            <div class="small text-secondary fw-semibold text-uppercase mb-1" style="letter-spacing:0.06em; font-size:.72rem;">
                                Estimasi Penghematan
                            </div>
                            <div class="af-result-number {{ $isPositive ? '' : 'text-danger' }}">
                                @if ($isPositive)
                                    <span style="color: var(--af-accent-dark);">Rp {{ number_format($result['penghematan'], 0, ',', '.') }}</span>
                                @else
                                    <span>−Rp {{ number_format(abs($result['penghematan']), 0, ',', '.') }}</span>
                                @endif
                            </div>
                            <p class="text-secondary small mb-0 mt-1">
                                untuk <strong>{{ $result['software_name'] }}</strong>
                            </p>
                        </div>
                    </div>

                    {{-- Breakdown --}}
                    <div class="af-card-static p-4 mt-3">
                        <h3 class="h6 fw-bold mb-3">
                            <i class="bi bi-bar-chart text-primary me-2"></i>Rincian Perbandingan
                        </h3>

                        {{-- Lisensi bar --}}
                        <div class="af-result-row" style="background: #fef2f2;">
                            <div>
                                <div class="small fw-bold text-secondary" style="font-size:.75rem; text-transform:uppercase; letter-spacing:.04em;">Biaya Lisensi Berbayar</div>
                                <div class="fw-bold" style="font-size:1.15rem; color: var(--af-danger);">
                                    Rp {{ number_format($result['biaya_lisensi'], 0, ',', '.') }}
                                </div>
                            </div>
                            <i class="bi bi-credit-card-2-back text-danger" style="font-size:1.3rem; opacity:.5;"></i>
                        </div>
                        <div class="af-meter">
                            <div class="af-meter-fill" style="width:{{ $pctLisensi }}%; background: linear-gradient(90deg, #ef4444, #f87171);"></div>
                        </div>

                        <div class="my-3"></div>

                        {{-- FOSS bar --}}
                        <div class="af-result-row" style="background: #f0fdf4;">
                            <div>
                                <div class="small fw-bold text-secondary" style="font-size:.75rem; text-transform:uppercase; letter-spacing:.04em;">Biaya Alternatif FOSS</div>
                                <div class="fw-bold" style="font-size:1.15rem; color: var(--af-accent-dark);">
                                    Rp {{ number_format($result['biaya_foss'], 0, ',', '.') }}
                                </div>
                            </div>
                            <i class="bi bi-box-seam text-success" style="font-size:1.3rem; opacity:.5;"></i>
                        </div>
                        <div class="af-meter">
                            <div class="af-meter-fill" style="width:{{ $pctFoss }}%; background: linear-gradient(90deg, #10b981, #34d399);"></div>
                        </div>
                    </div>

                    {{-- Message --}}
                    <div class="af-card-static p-4 mt-3">
                        @if (!$isPositive)
                            <div class="d-flex align-items-start gap-3">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-2 flex-shrink-0" style="width:36px; height:36px; background: #fef3c7;">
                                    <i class="bi bi-lightbulb text-warning"></i>
                                </span>
                                <div>
                                    <strong class="d-block mb-1">Pada skenario ini belum ada penghematan.</strong>
                                    <p class="text-secondary small mb-0">Biaya migrasi melebihi biaya lisensi. Pertimbangkan untuk menekan biaya migrasi, menambah jumlah PC, atau memperpanjang periode agar penghematan tercapai.</p>
                                </div>
                            </div>
                        @else
                            <div class="d-flex align-items-start gap-3">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-2 flex-shrink-0" style="width:36px; height:36px; background: #d1fae5;">
                                    <i class="bi bi-check-circle text-success"></i>
                                </span>
                                <div>
                                    <strong class="d-block mb-1" style="color: var(--af-accent-dark);">Anda berpotensi menghemat Rp {{ number_format($result['penghematan'], 0, ',', '.') }}!</strong>
                                    <p class="text-secondary small mb-0">Beralih ke alternatif open source bisa menghemat biaya lisensi secara signifikan dalam jangka panjang.</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Disclaimer --}}
                    <p class="text-center text-muted mt-3" style="font-size:.78rem;">
                        <i class="bi bi-info-circle me-1"></i>Ini adalah estimasi sederhana. Biaya aktual dapat bervariasi tergantung kebutuhan organisasi.
                    </p>
                </div>
            @endisset
        </div>

        <div class="mt-5">
            <a href="{{ route('catalog.index') }}" class="af-btn af-btn-outline text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Katalog
            </a>
        </div>
    </div>
</div>
@endsection
