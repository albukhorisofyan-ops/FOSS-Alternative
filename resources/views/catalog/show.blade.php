@extends('layouts.app')

@section('title', $software->name . ' - Detail & Alternatif Open Source')

@section('content')
{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb" style="font-size:.85rem;">
        <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}" class="text-decoration-none text-primary"><i class="bi bi-grid-3x3-gap me-1"></i>Katalog</a></li>
        <li class="breadcrumb-item active text-secondary" aria-current="page">{{ $software->name }}</li>
    </ol>
</nav>

{{-- Kartu Info Software Berbayar --}}
<div class="af-card-static overflow-hidden mb-5">
    <div style="height:5px; background: linear-gradient(90deg, var(--af-primary), #8b5cf6, var(--af-danger));"></div>
    <div class="p-4 p-md-5">
        <div class="row align-items-start">
            <div class="col-lg-8">
                <span class="af-badge af-badge-gray mb-3 d-inline-block">
                    <i class="bi bi-tag me-1"></i>{{ $software->category->name }}
                </span>
                <h1 class="fw-bold mb-2" style="font-size:1.85rem; letter-spacing:-0.025em;">{{ $software->name }}</h1>
                @if ($software->vendor)
                    <p class="text-secondary mb-0">
                        <i class="bi bi-building me-1"></i>Dikembangkan oleh <strong>{{ $software->vendor }}</strong>
                    </p>
                @endif

                @if ($software->description)
                    <hr class="af-divider">
                    <h2 class="text-uppercase small fw-bold text-secondary mb-2" style="letter-spacing:0.06em; font-size:.72rem;">Deskripsi</h2>
                    <p class="text-secondary mb-0" style="line-height:1.7;">{{ $software->description }}</p>
                @endif
            </div>
            <div class="col-lg-4 mt-4 mt-lg-0">
                {{-- Price card --}}
                <div class="p-4 rounded-3 text-center" style="background: linear-gradient(135deg, #fef2f2, #fff1f2); border: 1px solid #fecdd3;">
                    <div class="small text-secondary fw-semibold mb-1">Estimasi Biaya Lisensi</div>
                    <div class="fw-bold mb-1" style="font-size:1.6rem; color: var(--af-danger);">
                        Rp {{ number_format($software->price_per_year, 0, ',', '.') }}
                    </div>
                    <div class="af-badge af-badge-gray">per lisensi / tahun</div>
                </div>

                <a href="/kalkulator?software={{ $software->id }}" class="af-btn af-btn-success w-100 d-block text-center text-decoration-none mt-3">
                    <i class="bi bi-calculator me-2"></i>Hitung Penghematan
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Bagian Alternatif Open Source --}}
<div class="mb-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h4 fw-bold mb-1" style="letter-spacing:-0.015em;">Alternatif Open Source yang Setara</h2>
            <p class="text-secondary small mb-0">Perangkat lunak gratis & open source sebagai pengganti.</p>
        </div>
        <span class="af-badge af-badge-green" style="font-size:.8rem; padding:.4em .9em;">
            {{ $software->fossAlternatives->count() }} Alternatif
        </span>
    </div>

    @if ($software->fossAlternatives->isNotEmpty())
        <div class="row g-4">
            @foreach ($software->fossAlternatives as $index => $alt)
                <div class="col-12">
                    <div class="af-card-static overflow-hidden">
                        <div class="d-flex">
                            {{-- Left accent --}}
                            <div class="flex-shrink-0" style="width:4px; background: linear-gradient(180deg, var(--af-accent), var(--af-primary));"></div>
                            <div class="p-4 flex-grow-1">
                                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle fw-bold" style="width:32px; height:32px; font-size:.8rem; background: var(--af-primary-light); color: var(--af-primary);">
                                                {{ $index + 1 }}
                                            </span>
                                            <h3 class="h5 fw-bold mb-0" style="color: var(--af-primary);">{{ $alt->name }}</h3>
                                        </div>
                                        <span class="af-badge af-badge-cyan">
                                            <i class="bi bi-shield-check me-1"></i>Lisensi: {{ $alt->license }}
                                        </span>
                                    </div>
                                    @if ($alt->website)
                                        <a
                                            href="{{ $alt->website }}"
                                            target="_blank"
                                            rel="noopener"
                                            class="af-btn af-btn-outline text-decoration-none"
                                            style="font-size:.82rem;"
                                        >
                                            <i class="bi bi-box-arrow-up-right me-1"></i>Situs Resmi
                                        </a>
                                    @endif
                                </div>

                                @if ($alt->description)
                                    <p class="text-secondary mb-0" style="line-height:1.65;">{{ $alt->description }}</p>
                                @endif

                                @if ($alt->migration_guide)
                                    <div class="mt-3 p-3 rounded-3" style="background: var(--af-surface-alt); border: 1px solid var(--af-border);">
                                        <h4 class="d-flex align-items-center gap-2 mb-2" style="font-size:.82rem;">
                                            <span class="d-inline-flex align-items-center justify-content-center rounded-2" style="width:28px; height:28px; background: #0f172a; color:#fff;">
                                                <i class="bi bi-signpost-2" style="font-size:.8rem;"></i>
                                            </span>
                                            <span class="fw-bold text-dark">Panduan Migrasi Singkat</span>
                                        </h4>
                                        <div class="small text-secondary" style="white-space: pre-line; line-height:1.6;">{{ $alt->migration_guide }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-inbox text-secondary" style="font-size:2.5rem; opacity:.35;"></i>
            <p class="text-muted mt-2 mb-0">Belum ada data alternatif open source untuk perangkat lunak ini.</p>
        </div>
    @endif
</div>

<div class="mt-5">
    <a href="{{ route('catalog.index') }}" class="af-btn af-btn-outline text-decoration-none">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Katalog
    </a>
</div>
@endsection
