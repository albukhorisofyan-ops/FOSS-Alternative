@php
    $software = $software ?? null;
@endphp

<div class="mb-3">
    <label for="category_id" class="form-label">Kategori</label>
    <select
        id="category_id"
        name="category_id"
        class="form-select @error('category_id') is-invalid @enderror"
        required
    >
        <option value="">&mdash; Pilih kategori &mdash;</option>
        @foreach ($categories as $category)
            <option
                value="{{ $category->id }}"
                @selected((string) old('category_id', $software?->category_id) === (string) $category->id)
            >
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('category_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="name" class="form-label">Nama Perangkat Lunak</label>
    <input
        type="text"
        id="name"
        name="name"
        value="{{ old('name', $software?->name) }}"
        class="form-control @error('name') is-invalid @enderror"
        maxlength="255"
        required
    >
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="vendor" class="form-label">Vendor <span class="text-muted">(opsional)</span></label>
    <input
        type="text"
        id="vendor"
        name="vendor"
        value="{{ old('vendor', $software?->vendor) }}"
        class="form-control @error('vendor') is-invalid @enderror"
        maxlength="255"
    >
    @error('vendor')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="price_per_year" class="form-label">Harga per Lisensi per Tahun (Rp)</label>
    <input
        type="number"
        id="price_per_year"
        name="price_per_year"
        value="{{ old('price_per_year', $software?->price_per_year) }}"
        class="form-control @error('price_per_year') is-invalid @enderror"
        min="0"
        step="1"
        required
    >
    @error('price_per_year')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label for="description" class="form-label">Deskripsi <span class="text-muted">(opsional)</span></label>
    <textarea
        id="description"
        name="description"
        rows="4"
        class="form-control @error('description') is-invalid @enderror"
        maxlength="2000"
    >{{ old('description', $software?->description) }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
