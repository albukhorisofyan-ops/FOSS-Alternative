@extends('layouts.app')

@section('title', 'Masuk Admin - AlterFOSS')

@section('content')
<div class="row justify-content-center py-4 py-md-5">
    <div class="col-12 col-sm-10 col-md-7 col-lg-5">
        <div class="af-card-static overflow-hidden">
            {{-- Accent top bar --}}
            <div style="height: 5px; background: linear-gradient(90deg, var(--af-primary), var(--af-accent));"></div>

            <div class="p-4 p-md-5">
                <div class="text-center mb-4">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 56px; height: 56px; background: var(--af-primary-light); color: var(--af-primary);">
                        <i class="bi bi-shield-lock" style="font-size: 1.6rem;"></i>
                    </span>
                    <h1 class="h3 fw-bold mb-1" style="letter-spacing: -0.02em;">Masuk Admin</h1>
                    <p class="text-secondary small mb-0">Masuk untuk mengelola katalog perangkat lunak dan moderasi usulan.</p>
                </div>

                <form method="POST" action="{{ route('login.attempt') }}" class="af-form" novalidate>
                    @csrf

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            Email <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-radius: .6rem 0 0 .6rem; background: var(--af-surface-alt); border-color: var(--af-border);">
                                <i class="bi bi-envelope text-secondary"></i>
                            </span>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                placeholder="admin@alterfoss.test"
                                autocomplete="email"
                                autofocus
                                required
                                style="border-radius: 0 .6rem .6rem 0;"
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="mb-4">
                        <label for="password" class="form-label">
                            Kata Sandi <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-radius: .6rem 0 0 .6rem; background: var(--af-surface-alt); border-color: var(--af-border);">
                                <i class="bi bi-key text-secondary"></i>
                            </span>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Masukkan kata sandi"
                                autocomplete="current-password"
                                required
                                style="border-radius: 0 .6rem .6rem 0;"
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="af-btn af-btn-primary w-100">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                    </button>
                </form>

                <hr class="af-divider my-4">

                <div class="text-center">
                    <a href="{{ route('catalog.index') }}" class="text-decoration-none small text-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali ke Katalog
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
