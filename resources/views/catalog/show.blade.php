@extends('layouts.app')

@section('title', $software->name . ' - Detail & Alternatif Open Source')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}">Katalog</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $software->name }}</li>
    </ol>
</nav>

{{-- Kartu Info Software Berbayar --}}
<div class="card shadow-sm mb-5 border-0 border-top border-primary border-4">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
            <div>
                <span class="badge bg-secondary mb-2">{{ $software->category->name }}</span>
                <h1 class="h2 fw-bold mb-1">{{ $software->name }}</h1>
                @if ($software->vendor)
                    <p class="text-muted mb-0">Dikembangkan oleh <strong>{{ $software->vendor }}</strong></p>
                @endif
            </div>
            <div class="text-md-end bg-light p-3 rounded border">
                <div class="small text-muted">Estimasi Biaya Lisensi:</div>
                <div class="h4 text-danger fw-bold mb-0">
                    Rp {{ number_format($software->price_per_year, 0, ',', '.') }}
                    <span class="small text-muted fw-normal">/ tahun</span>
                </div>
            </div>
        </div>

        @if ($software->description)
            <div class="mt-3">
                <h2 class="h6 fw-semibold text-muted text-uppercase mb-2">Deskripsi</h2>
                <p class="mb-0 text-secondary leading-relaxed">{{ $software->description }}</p>
            </div>
        @endif

        <div class="mt-4 pt-3 border-top d-flex flex-wrap gap-2 align-items-center justify-content-between">
            <span class="text-muted small">Ingin menghitung berapa biaya yang bisa dihemat organisasi Anda?</span>
            <a href="/kalkulator?software={{ $software->id }}" class="btn btn-success fw-semibold">
                &plus; Hitung penghematan
            </a>
        </div>
    </div>
</div>

{{-- Bagian Alternatif Open Source --}}
<div class="mb-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="h4 fw-bold mb-0">Alternatif Open Source yang Setara</h2>
        <span class="badge bg-primary rounded-pill">{{ $software->fossAlternatives->count() }} Alternatif</span>
    </div>

    @if ($software->fossAlternatives->isNotEmpty())
        <div class="row g-4">
            @foreach ($software->fossAlternatives as $alt)
                <div class="col-12">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body p-4">
                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                                <div>
                                    <h3 class="h4 card-title fw-bold text-primary mb-1">{{ $alt->name }}</h3>
                                    {{-- Lencana Lisensi --}}
                                    <span class="badge bg-info text-dark fw-medium">
                                        Lisensi: {{ $alt->license }}
                                    </span>
                                </div>
                                @if ($alt->website)
                                    <div>
                                        <a
                                            href="{{ $alt->website }}"
                                            target="_blank"
                                            rel="noopener"
                                            class="btn btn-outline-primary btn-sm"
                                        >
                                            Kunjungi Situs Resmi &nearr;
                                        </a>
                                    </div>
                                @endif
                            </div>

                            @if ($alt->description)
                                <div class="mb-3">
                                    <h4 class="h6 fw-semibold text-muted text-uppercase mb-1">Deskripsi Alternatif</h4>
                                    <p class="text-secondary mb-0">{{ $alt->description }}</p>
                                </div>
                            @endif

                            @if ($alt->migration_guide)
                                <div class="p-3 bg-light rounded border mt-3">
                                    <h4 class="h6 fw-semibold text-dark mb-2">
                                        <span class="badge bg-dark me-1">Panduan</span> Panduan Migrasi Singkat:
                                    </h4>
                                    <div class="small text-secondary" style="white-space: pre-line;">{{ $alt->migration_guide }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-secondary text-center py-4">
            Belum ada data alternatif open source untuk perangkat lunak ini.
        </div>
    @endif
</div>

<div class="mt-4">
    <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary">
        &larr; Kembali ke Katalog
    </a>
</div>
@endsection
