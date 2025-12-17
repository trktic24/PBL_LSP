@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Tambah Struktur Organisasi</h2>
    <form action="{{ route('admin.struktur.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Jabatan</label>
            <input type="text" name="jabatan" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Foto</label>
            <input type="file" name="gambar" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.struktur.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection