@extends('layouts.app')

@section('title', 'Usulkan Alternatif Open Source - AlterFOSS')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">

        {{-- Page Header --}}
        <div class="af-page-header">
            <h1><i class="bi bi-lightbulb text-primary me-2"></i>Usulkan Alternatif Open Source</h1>
            <p>Kenal perangkat lunak open source bagus yang belum ada di direktori? Kirimkan usulan Anda untuk membantu pengguna lain.</p>
        </div>

        <div class="row g-4">
            {{-- Left: Form --}}
            <div class="col-lg-8">
                <div class="af-card-static overflow-hidden">
                    <div style="height: 5px; background: linear-gradient(90deg, var(--af-primary), var(--af-accent));"></div>
                    <div class="p-4 p-md-5">
                        <h2 class="h6 fw-bold mb-4 d-flex align-items-center gap-2">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-2" style="width: 30px; height: 30px; background: var(--af-primary-light); color: var(--af-primary);">
                                <i class="bi bi-send" style="font-size: .85rem;"></i>
                            </span>
                            Detail Usulan Alternatif
                        </h2>

                        <form method="POST" action="{{ route('suggestions.store') }}" class="af-form" novalidate>
                            @csrf

                            {{-- Nama Alternatif --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    Nama Alternatif Open Source <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text" style="border-radius: .6rem 0 0 .6rem; background: var(--af-surface-alt); border-color: var(--af-border);">
                                        <i class="bi bi-box-seam text-secondary"></i>
                                    </span>
                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}"
                                        placeholder="Contoh: Inkscape, GIMP, Nextcloud"
                                        maxlength="255"
                                        required
                                        style="border-radius: 0 .6rem .6rem 0;"
                                    >
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Perangkat Lunak Berbayar --}}
                            <div class="mb-3">
                                <label for="proprietary_name" class="form-label">
                                    Menggantikan Perangkat Lunak Berbayar <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text" style="border-radius: .6rem 0 0 .6rem; background: var(--af-surface-alt); border-color: var(--af-border);">
                                        <i class="bi bi-arrow-left-right text-secondary"></i>
                                    </span>
                                    <select
                                        name="proprietary_name"
                                        id="proprietary_name"
                                        class="form-select @error('proprietary_name') is-invalid @enderror"
                                        required
                                        style="border-radius: 0 .6rem .6rem 0;"
                                    >
                                        <option value="">— Pilih perangkat lunak berbayar —</option>
                                        @foreach ($softwareList as $sw)
                                            <option value="{{ $sw->name }}" @selected(old('proprietary_name') === $sw->name)>
                                                {{ $sw->name }}@if ($sw->vendor) ({{ $sw->vendor }})@endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('proprietary_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Situs Web Resmi --}}
                            <div class="mb-3">
                                <label for="website" class="form-label">Situs Web Resmi</label>
                                <div class="input-group">
                                    <span class="input-group-text" style="border-radius: .6rem 0 0 .6rem; background: var(--af-surface-alt); border-color: var(--af-border);">
                                        <i class="bi bi-globe text-secondary"></i>
                                    </span>
                                    <input
                                        type="url"
                                        name="website"
                                        id="website"
                                        class="form-control @error('website') is-invalid @enderror"
                                        value="{{ old('website') }}"
                                        placeholder="https://inkscape.org"
                                        maxlength="255"
                                        style="border-radius: 0 .6rem .6rem 0;"
                                    >
                                    @error('website')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text small" style="font-size: .78rem;">Opsional. Sertakan awalan <code>https://</code> atau <code>http://</code>.</div>
                            </div>

                            {{-- Catatan --}}
                            <div class="mb-4">
                                <label for="note" class="form-label">Catatan / Alasan</label>
                                <textarea
                                    name="note"
                                    id="note"
                                    rows="4"
                                    class="form-control @error('note') is-invalid @enderror"
                                    placeholder="Jelaskan keunggulan singkat, jenis lisensi (jika tahu), atau kecocokan fiturnya..."
                                    maxlength="2000"
                                >{{ old('note') }}</textarea>
                                @error('note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text small" style="font-size: .78rem;">Opsional, maksimal 2.000 karakter.</div>
                            </div>

                            <button type="submit" class="af-btn af-btn-primary w-100">
                                <i class="bi bi-send-fill me-2"></i>Kirim Usulan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Right: Info & Guidelines --}}
            <div class="col-lg-4">
                <div class="af-card-static p-4">
                    <h3 class="h6 fw-bold mb-3">
                        <i class="bi bi-info-circle text-primary me-2"></i>Panduan Usulan
                    </h3>
                    <ul class="list-unstyled mb-0 small text-secondary d-flex flex-column gap-3">
                        <li class="d-flex align-items-start gap-2">
                            <i class="bi bi-check-circle-fill text-success mt-1 flex-shrink-0"></i>
                            <span>Pastikan perangkat lunak yang diusulkan benar-benar berlisensi <strong>Open Source</strong> (OSI-approved).</span>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <i class="bi bi-check-circle-fill text-success mt-1 flex-shrink-0"></i>
                            <span>Sebutkan perangkat lunak berbayar yang paling relevan sebagai pembanding.</span>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <i class="bi bi-check-circle-fill text-success mt-1 flex-shrink-0"></i>
                            <span>Usulan akan masuk dengan status <strong>pending</strong> dan ditinjau oleh admin.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <a href="{{ route('catalog.index') }}" class="af-btn af-btn-outline text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Katalog
            </a>
        </div>
    </div>
</div>
@endsection
