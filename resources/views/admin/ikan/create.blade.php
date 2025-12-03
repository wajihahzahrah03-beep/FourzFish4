@extends('layouts.app')

@section('title', 'Tambah Ikan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Tambah Ikan</h2>
    <a href="{{ route('admin.ikan.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<form action="{{ route('admin.ikan.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- Nama --}}
    <div class="mb-3">
        <label for="nama" class="form-label">Nama Ikan</label>
        <input type="text" name="nama" id="nama"
               class="form-control @error('nama') is-invalid @enderror"
               value="{{ old('nama') }}" required>
        @error('nama')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Kategori --}}
    <div class="mb-3">
        <label for="kategori" class="form-label">Kategori</label>
        <input type="text" name="kategori" id="kategori"
               class="form-control @error('kategori') is-invalid @enderror"
               value="{{ old('kategori') }}">
        @error('kategori')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Harga --}}
    <div class="mb-3">
        <label for="harga" class="form-label">Harga</label>
        <input type="number" name="harga" id="harga"
               class="form-control @error('harga') is-invalid @enderror"
               value="{{ old('harga') }}" min="0" required>
        @error('harga')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Stok --}}
    <div class="mb-3">
        <label for="stok" class="form-label">Stok</label>
        <input type="number" name="stok" id="stok"
               class="form-control @error('stok') is-invalid @enderror"
               value="{{ old('stok') }}" min="0" required>
        @error('stok')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Deskripsi --}}
    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" rows="4"
                  class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi') }}</textarea>
        @error('deskripsi')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Gambar (Cloudinary) --}}
    <div class="mb-3">
        <label for="gambar" class="form-label">Gambar Ikan</label>
        <input type="file" name="gambar" id="gambar"
               class="form-control @error('gambar') is-invalid @enderror"
               accept="image/*">
        <div class="form-text">Format: jpg, jpeg, png, gif, svg. Maks 5 MB.</div>
        @error('gambar')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
@endsection
