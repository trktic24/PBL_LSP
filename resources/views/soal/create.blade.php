@extends('layouts.soal')

@section('title', 'Tambah Soal')

@section('content')
    <form action="{{ route('soal.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow">
        @csrf
        <div class="mb-4">
            <label class="block font-medium mb-1">Isi Soal</label>
            <textarea name="soal_IA06" class="border rounded w-full px-3 py-2" rows="4" required>{{ old('soal_IA06') }}</textarea>
            @error('soal_IA06')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex justify-end space-x-2">
            <a href="{{ route('soal.index') }}" class="bg-gray-300 px-4 py-2 rounded">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
        </div>
    </form>
@endsection
