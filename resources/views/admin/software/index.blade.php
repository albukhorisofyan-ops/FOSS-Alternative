@extends('layouts.app')

@section('title', 'Kelola Perangkat Lunak')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h1 class="h3 mb-0">Perangkat Lunak Berbayar</h1>
        <a href="{{ route('admin.software.create') }}" class="btn btn-primary">Tambah Software</a>
    </div>

    @include('admin._nav')

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th scope="col">Nama</th>
                    <th scope="col">Kategori</th>
                    <th scope="col">Harga / Tahun</th>
                    <th scope="col" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($software as $item)
                    <tr>
                        <td>
                            <span class="fw-semibold">{{ $item->name }}</span>
                            @if ($item->vendor)
                                <span class="text-muted small d-block">{{ $item->vendor }}</span>
                            @endif
                        </td>
                        <td>{{ $item->category->name }}</td>
                        <td>Rp {{ number_format((float) $item->price_per_year, 0, ',', '.') }}</td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin.software.edit', $item) }}" class="btn btn-sm btn-outline-primary">
                                Ubah
                            </a>
                            <form
                                method="POST"
                                action="{{ route('admin.software.destroy', $item) }}"
                                class="d-inline"
                                onsubmit="return confirm('Hapus data ini? Alternatif open source terkait juga akan terhapus.');"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Belum ada data perangkat lunak.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $software->links('pagination::bootstrap-5') }}
@endsection
