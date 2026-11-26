@extends('layouts.app')

@section('title', 'Katalog Alternatif Open Source - AlterFOSS')

@section('content')
{{-- Hero Header --}}
<div class="af-page-header">
    <h1><i class="bi bi-grid-3x3-gap text-primary me-2"></i>Katalog Alternatif Open Source</h1>
    <p>Temukan perangkat lunak open source yang setara untuk menggantikan perangkat lunak berbayar Anda.</p>
</div>

{{-- Formulir Pencarian dan Filter Kategori --}}
<div class="af-card-static p-4 mb-4">
    <form method="GET" action="{{ route('catalog.index') }}" class="row g-3 align-items-end">
        <div class="col-md-6 col-lg-7">
            <label for="q" class="form-label fw-semibold small text-secondary">
                <i class="bi bi-search me-1"></i>Cari Nama Perangkat Lunak
            </label>
            <div class="af-form">
                <input
                    type="search"
                    class="form-control"
                    id="q"
                    name="q"
                    value="{{ $q }}"
                    placeholder="Contoh: Photoshop, Microsoft Office..."
                >
            </div>
        </div>
        <div class="col-md-4 col-lg-3">
            <label for="kategori" class="form-label fw-semibold small text-secondary">
                <i class="bi bi-funnel me-1"></i>Kategori
            </label>
            <div class="af-form">
                <select class="form-select" id="kategori" name="kategori">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected((string) $selectedCategory === (string) $cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-2 col-lg-2 d-flex gap-2">
            <button type="submit" class="af-btn af-btn-primary w-100">
                <i class="bi bi-search me-1"></i>Cari
            </button>
            @if ($q || $selectedCategory)
                <a href="{{ route('catalog.index') }}" class="af-btn af-btn-outline" title="Reset filter">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </div>
    </form>
</div>

{{-- Stats bar --}}
@if ($softwareList->total() > 0)
    <div class="d-flex align-items-center justify-content-between mb-3 px-1">
        <p class="text-secondary small mb-0">
            Menampilkan <strong>{{ $softwareList->firstItem() }}–{{ $softwareList->lastItem() }}</strong>
            dari <strong>{{ $softwareList->total() }}</strong> perangkat lunak
        </p>
        @if ($q)
            <span class="af-badge af-badge-blue">
                <i class="bi bi-search me-1"></i>{{ $q }}
            </span>
        @endif
    </div>
@endif

{{-- Grid Daftar Perangkat Lunak --}}
<div class="row g-4">
    @forelse ($softwareList as $software)
        <div class="col-12 col-md-6 col-lg-4">
            <div class="af-card h-100 overflow-hidden">
                {{-- Accent top bar --}}
                <div style="height:4px; background: linear-gradient(90deg, var(--af-primary), var(--af-accent));"></div>
                <div class="p-4 d-flex flex-column h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h2 class="h5 mb-0 fw-bold" style="letter-spacing:-0.01em;">{{ $software->name }}</h2>
                        <span class="af-badge af-badge-gray ms-2 flex-shrink-0">{{ $software->category->name }}</span>
                    </div>

                    @if ($software->vendor)
                        <p class="text-secondary small mb-3">
                            <i class="bi bi-building me-1"></i>{{ $software->vendor }}
                        </p>
                    @else
                        <div class="mb-3"></div>
                    @endif

                    <div class="mb-3 flex-grow-1">
                        <div class="d-flex align-items-center mb-2">
                            <span class="small fw-bold text-secondary text-uppercase" style="letter-spacing:0.05em; font-size:.7rem;">
                                Alternatif Open Source
                            </span>
                            @if ($software->fossAlternatives->isNotEmpty())
                                <span class="af-badge af-badge-green ms-2">{{ $software->fossAlternatives->count() }}</span>
                            @endif
                        </div>
                        @if ($software->fossAlternatives->isNotEmpty())
                            <div class="d-flex flex-wrap gap-1">
                                @foreach ($software->fossAlternatives as $alt)
                                    <span class="badge rounded-pill" style="background:#f1f5f9; color:#334155; font-weight:500; font-size:.78rem; padding:.35em .7em;">
                                        {{ $alt->name }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="small text-muted fst-italic mb-0">Belum ada alternatif yang tercatat.</p>
                        @endif
                    </div>

                    <div class="mt-auto pt-3" style="border-top:1px solid var(--af-border);">
                        <a href="{{ route('catalog.show', $software) }}" class="af-btn af-btn-primary w-100 text-center d-block text-decoration-none">
                            Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5 my-4">
                <div class="mb-3">
                    <i class="bi bi-search text-secondary" style="font-size:3rem; opacity:.4;"></i>
                </div>
                <h3 class="h5 fw-bold mb-2 text-secondary">Tidak ada hasil.</h3>
                <p class="text-muted mb-3">
                    Tidak ditemukan perangkat lunak yang cocok dengan kriteria pencarian Anda.
                </p>
                @if ($q || $selectedCategory)
                    <a href="{{ route('catalog.index') }}" class="af-btn af-btn-outline">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Tampilkan semua perangkat lunak
                    </a>
                @endif
            </div>
        </div>
    @endforelse
</div>

{{-- Paginasi --}}
@if ($softwareList->hasPages())
    <div class="d-flex justify-content-center mt-5">
        {{ $softwareList->links() }}
    </div>
@endif
@endsection
