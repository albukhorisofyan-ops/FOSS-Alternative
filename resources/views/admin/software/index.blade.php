@extends('layouts.app')

@section('title', 'Kelola Perangkat Lunak - Admin AlterFOSS')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1" style="letter-spacing: -0.02em;">
            <i class="bi bi-gear text-primary me-2"></i>Kelola Perangkat Lunak Berbayar
        </h1>
        <p class="text-secondary small mb-0">Tambah, ubah, atau hapus daftar perangkat lunak berbayar di katalog.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.suggestions.index') }}" class="af-btn af-btn-outline text-decoration-none">
            <i class="bi bi-inbox me-1"></i>Usulan Masuk
        </a>
        <a href="{{ route('admin.software.create') }}" class="af-btn af-btn-primary text-decoration-none">
            <i class="bi bi-plus-lg me-1"></i>Tambah Software
        </a>
    </div>
</div>

<div class="af-card-static overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Nama Perangkat Lunak</th>
                    <th>Kategori</th>
                    <th>Vendor</th>
                    <th class="text-end">Harga / Tahun</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($softwareList as $sw)
                    <tr>
                        <td class="ps-4 fw-semibold">{{ $sw->name }}</td>
                        <td><span class="af-badge af-badge-gray">{{ $sw->category->name }}</span></td>
                        <td class="text-secondary small">{{ $sw->vendor ?? '—' }}</td>
                        <td class="text-end fw-semibold">Rp {{ number_format($sw->price_per_year, 0, ',', '.') }}</td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-2">
                                <a href="{{ route('admin.software.edit', $sw) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil me-1"></i>Ubah
                                </a>
                                <form method="POST" action="{{ route('admin.software.destroy', $sw) }}" onsubmit="return confirm('Yakin ingin menghapus {{ addslashes($sw->name) }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash me-1"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            Belum ada data perangkat lunak berbayar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($softwareList->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $softwareList->links() }}
    </div>
@endif
@endsection
