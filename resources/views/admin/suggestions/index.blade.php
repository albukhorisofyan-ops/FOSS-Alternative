@extends('layouts.app')

@section('title', 'Moderasi Usulan - Admin AlterFOSS')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1" style="letter-spacing: -0.02em;">
            <i class="bi bi-inbox text-primary me-2"></i>Moderasi Usulan Alternatif
        </h1>
        <p class="text-secondary small mb-0">Tinjau usulan alternatif open source yang dikirimkan oleh pengunjung.</p>
    </div>
    <a href="{{ route('admin.software.index') }}" class="af-btn af-btn-outline text-decoration-none">
        <i class="bi bi-gear me-1"></i>Kelola Software
    </a>
</div>

<div class="af-card-static overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Alternatif Diusulkan</th>
                    <th>Menggantikan</th>
                    <th>Catatan</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Moderasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($suggestions as $item)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-semibold">{{ $item->name }}</div>
                            @if ($item->website)
                                <a href="{{ $item->website }}" target="_blank" rel="noopener" class="small text-decoration-none">
                                    {{ $item->website }} <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            @endif
                        </td>
                        <td class="text-secondary">{{ $item->proprietary_name }}</td>
                        <td class="small text-secondary" style="max-width: 280px;">{{ $item->note ?: '—' }}</td>
                        <td>
                            @if ($item->status === 'approved')
                                <span class="af-badge af-badge-green">Disetujui</span>
                            @elseif ($item->status === 'rejected')
                                <span class="af-badge" style="background: #fee2e2; color: #991b1b;">Ditolak</span>
                            @else
                                <span class="af-badge" style="background: #fef3c7; color: #92400e;">Pending</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-2">
                                <form method="POST" action="{{ route('admin.suggestions.update', $item) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-check-lg me-1"></i>Setujui
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.suggestions.update', $item) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-x-lg me-1"></i>Tolak
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            Belum ada usulan alternatif yang masuk.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($suggestions->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $suggestions->links() }}
    </div>
@endif
@endsection
