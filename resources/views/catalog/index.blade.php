@extends('layouts.app')

@section('title', 'Katalog Alternatif Open Source - AlterFOSS')

@section('content')
<div class="row mb-4">
    <div class="col-lg-8 mx-auto text-center">
        <h1 class="display-6 fw-bold mb-2">Katalog Alternatif Open Source</h1>
        <p class="text-muted">
            Temukan perangkat lunak open source yang setara untuk menggantikan perangkat lunak berbayar Anda.
        </p>
    </div>
</div>

{{-- Formulir Pencarian dan Filter Kategori --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('catalog.index') }}" class="row g-3 align-items-end">
            <div class="col-md-6 col-lg-7">
                <label for="q" class="form-label fw-semibold small">Cari Nama Perangkat Lunak</label>
                <input
                    type="search"
                    class="form-control"
                    id="q"
                    name="q"
                    value="{{ $q }}"
                    placeholder="Contoh: Photoshop, Microsoft Office..."
                >
            </div>
            <div class="col-md-4 col-lg-3">
                <label for="kategori" class="form-label fw-semibold small">Kategori</label>
                <select class="form-select" id="kategori" name="kategori">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected((string) $selectedCategory === (string) $cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    Cari
                </button>
                @if ($q || $selectedCategory)
                    <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary" title="Reset filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Grid Daftar Perangkat Lunak --}}
<div class="row g-4">
    @forelse ($softwareList as $software)
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 border-top border-primary border-3">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h2 class="h5 card-title mb-0 fw-bold">{{ $software->name }}</h2>
                        <span class="badge bg-secondary">{{ $software->category->name }}</span>
                    </div>

                    @if ($software->vendor)
                        <div class="text-muted small mb-3">oleh {{ $software->vendor }}</div>
                    @else
                        <div class="mb-3"></div>
                    @endif

                    <div class="mb-3 flex-grow-1">
                        <div class="small fw-semibold text-muted mb-2">Alternatif Open Source:</div>
                        @if ($software->fossAlternatives->isNotEmpty())
                            <ul class="list-unstyled mb-0">
                                @foreach ($software->fossAlternatives as $alt)
                                    <li class="small py-1 border-bottom text-truncate">
                                        <span class="text-primary fw-medium">&bull;</span> {{ $alt->name }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="small text-muted fst-italic mb-0">Belum ada alternatif yang tercatat.</p>
                        @endif
                    </div>

                    <div class="mt-auto pt-2">
                        <a href="{{ route('catalog.show', $software) }}" class="btn btn-outline-primary btn-sm w-100">
                            Lihat Detail Alternatif &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center py-5 my-3 shadow-sm" role="alert">
                <h3 class="h5 alert-heading mb-2">Tidak ada hasil.</h3>
                <p class="mb-0 text-muted">
                    Tidak ditemukan perangkat lunak yang cocok dengan kriteria pencarian Anda.
                    @if ($q || $selectedCategory)
                        <br>
                        <a href="{{ route('catalog.index') }}" class="alert-link">Tampilkan semua perangkat lunak</a>.
                    @endif
                </p>
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
