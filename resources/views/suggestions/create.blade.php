@extends('layouts.app')

@section('title', 'Usulkan Alternatif')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="h3 mb-1">Usulkan Alternatif Open Source</h1>
            <p class="text-muted mb-4">
                Tidak menemukan alternatif yang cocok? Kirim usulan Anda, admin akan meninjaunya.
            </p>

            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('suggestions.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Alternatif Open Source</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror"
                                maxlength="255"
                                placeholder="Contoh: LibreOffice"
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="proprietary_name" class="form-label">Menggantikan Perangkat Lunak Berbayar</label>
                            <input
                                type="text"
                                id="proprietary_name"
                                name="proprietary_name"
                                value="{{ old('proprietary_name') }}"
                                class="form-control @error('proprietary_name') is-invalid @enderror"
                                maxlength="255"
                                placeholder="Contoh: Microsoft 365"
                                required
                            >
                            @error('proprietary_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="website" class="form-label">
                                Situs Resmi <span class="text-muted">(opsional)</span>
                            </label>
                            <input
                                type="url"
                                id="website"
                                name="website"
                                value="{{ old('website') }}"
                                class="form-control @error('website') is-invalid @enderror"
                                maxlength="255"
                                placeholder="https://contoh.org"
                            >
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="note" class="form-label">
                                Catatan <span class="text-muted">(opsional)</span>
                            </label>
                            <textarea
                                id="note"
                                name="note"
                                rows="4"
                                class="form-control @error('note') is-invalid @enderror"
                                maxlength="2000"
                                placeholder="Alasan atau keterangan tambahan."
                            >{{ old('note') }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Kirim Usulan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
