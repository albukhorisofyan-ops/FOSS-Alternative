<div class="mb-3">
    <label for="category_id" class="form-label">
        Kategori <span class="text-danger">*</span>
    </label>
    <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
        <option value="">— Pilih kategori —</option>
        @foreach ($categories as $cat)
            <option value="{{ $cat->id }}" @selected(old('category_id', $software->category_id ?? '') == $cat->id)>
                {{ $cat->name }}
            </option>
        @endforeach
    </select>
    @error('category_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="name" class="form-label">
        Nama Perangkat Lunak <span class="text-danger">*</span>
    </label>
    <input
        type="text"
        name="name"
        id="name"
        class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $software->name ?? '') }}"
        maxlength="255"
        placeholder="Contoh: Adobe Photoshop"
        required
    >
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label for="vendor" class="form-label">Vendor</label>
        <input
            type="text"
            name="vendor"
            id="vendor"
            class="form-control @error('vendor') is-invalid @enderror"
            value="{{ old('vendor', $software->vendor ?? '') }}"
            maxlength="255"
            placeholder="Contoh: Adobe Inc."
        >
        @error('vendor')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="price_per_year" class="form-label">
            Harga per Tahun (Rp) <span class="text-danger">*</span>
        </label>
        <input
            type="number"
            name="price_per_year"
            id="price_per_year"
            class="form-control @error('price_per_year') is-invalid @enderror"
            value="{{ old('price_per_year', $software->price_per_year ?? '') }}"
            min="0"
            step="1"
            placeholder="Contoh: 3500000"
            required
        >
        @error('price_per_year')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-4">
    <label for="description" class="form-label">Deskripsi</label>
    <textarea
        name="description"
        id="description"
        rows="4"
        class="form-control @error('description') is-invalid @enderror"
        maxlength="2000"
        placeholder="Deskripsi singkat perangkat lunak..."
    >{{ old('description', $software->description ?? '') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
