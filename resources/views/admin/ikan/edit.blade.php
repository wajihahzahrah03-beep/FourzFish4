@extends('layouts.app')

@section('title', 'Edit Ikan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Edit Ikan</h2>
    <a href="{{ route('admin.ikan.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<form action="{{ route('admin.ikan.update', $ikan->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Nama --}}
    <div class="mb-3">
        <label for="nama" class="form-label">Nama Ikan</label>
        <input type="text" name="nama" id="nama"
               class="form-control @error('nama') is-invalid @enderror"
               value="{{ old('nama', $ikan->nama) }}" required>
        @error('nama')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Kategori --}}
    <div class="mb-3">
        <label for="kategori" class="form-label">Kategori</label>
        <input type="text" name="kategori" id="kategori"
               class="form-control @error('kategori') is-invalid @enderror"
               value="{{ old('kategori', $ikan->kategori) }}">
        @error('kategori')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Harga --}}
    <div class="mb-3">
        <label for="harga" class="form-label">Harga</label>
        <input type="number" name="harga" id="harga"
               class="form-control @error('harga') is-invalid @enderror"
               value="{{ old('harga', $ikan->harga) }}" min="0" required>
        @error('harga')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Stok --}}
    <div class="mb-3">
        <label for="stok" class="form-label">Stok</label>
        <input type="number" name="stok" id="stok"
               class="form-control @error('stok') is-invalid @enderror"
               value="{{ old('stok', $ikan->stok) }}" min="0" required>
        @error('stok')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Deskripsi --}}
    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" rows="4"
                  class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $ikan->deskripsi) }}</textarea>
        @error('deskripsi')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Gambar (Cloudinary) --}}
    <div class="mb-3">
        <label class="form-label d-block">Gambar Saat Ini</label>
        @if ($ikan->gambar)
            <img src="{{ $ikan->gambar }}" alt="{{ $ikan->nama }}" class="img-thumbnail mb-2" style="max-height: 150px;">
        @else
            <p class="text-muted">Belum ada gambar.</p>
        @endif
    </div>

    <div class="mb-3">
        <label for="gambar" class="form-label">Ganti Gambar (opsional)</label>
        <input type="file" name="gambar" id="gambar"
               class="form-control @error('gambar') is-invalid @enderror"
               accept="image/*">
        <div class="form-text">Kosongkan jika tidak ingin mengganti gambar.</div>
        @error('gambar')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
</form>
@endsection
