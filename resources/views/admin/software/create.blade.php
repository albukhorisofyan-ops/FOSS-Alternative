@extends('layouts.app')

@section('title', 'Tambah Perangkat Lunak - Admin AlterFOSS')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="mb-4">
            <a href="{{ route('admin.software.index') }}" class="text-decoration-none small text-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Software
            </a>
            <h1 class="h3 fw-bold mt-2 mb-0">Tambah Perangkat Lunak Berbayar</h1>
        </div>

        <div class="af-card-static p-4 p-md-5">
            <form method="POST" action="{{ route('admin.software.store') }}" class="af-form" novalidate>
                @csrf
                @include('admin.software._form')
                <div class="d-flex gap-2">
                    <button type="submit" class="af-btn af-btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Simpan
                    </button>
                    <a href="{{ route('admin.software.index') }}" class="af-btn af-btn-outline text-decoration-none">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
