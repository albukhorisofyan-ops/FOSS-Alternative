@extends('layouts.app')

@section('title', 'Tambah Perangkat Lunak')

@section('content')
    <h1 class="h3 mb-3">Tambah Perangkat Lunak</h1>

    @include('admin._nav')

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.software.store') }}">
                @csrf
                @include('admin.software._form')

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.software.index') }}" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection
