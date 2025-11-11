@extends('layouts.soal')

@section('title', 'Edit Soal')

@section('content')
    <form action="{{ route('soal.update', $soal->id_soal_IA06) }}" method="POST" class="bg-white p-6 rounded-lg shadow">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block font-medium mb-1">Isi Soal</label>
            <textarea name="soal_IA06" class="border rounded w-full px-3 py-2" rows="4" required>{{ old('soal_IA06', $soal->soal_IA06) }}</textarea>
        </div>

        <div class="flex justify-end space-x-2">
            <a href="{{ route('soal.index') }}" class="bg-gray-300 px-4 py-2 rounded">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan Perubahan</button>
        </div>
    </form>

    {{-- Daftar Kunci Jawaban --}}
    <div class="mt-8 bg-white p-6 rounded-lg shadow">
        <h3 class="font-semibold text-lg mb-3">Kunci Jawaban</h3>

        <ul class="list-disc ml-6 space-y-2">
            @forelse($soal->kuncis as $kunci)
                <li class="flex justify-between items-center">
                    <span>{{ $kunci->kunci_IA06 }}</span>
                    <div>
                        <form action="{{ route('kunci.update', $kunci->id_kunci_IA06) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <input type="text" name="kunci_IA06" value="{{ $kunci->kunci_IA06 }}" class="border rounded px-2 py-1 text-sm">
                            <button type="submit" class="text-green-600 text-sm">Update</button>
                        </form>

                        <form action="{{ route('kunci.destroy', $kunci->id_kunci_IA06) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 text-sm ml-2" onclick="return confirm('Hapus kunci ini?')">Hapus</button>
                        </form>
                    </div>
                </li>
            @empty
                <li class="text-gray-500 italic">Belum ada kunci jawaban.</li>
            @endforelse
        </ul>

        <form action="{{ route('kunci.store', $soal->id_soal_IA06) }}" method="POST" class="mt-4 flex space-x-2">
            @csrf
            <input type="text" name="kunci_IA06" placeholder="Tambah kunci baru..." class="border rounded px-3 py-1 w-full">
            <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded">Tambah</button>
        </form>
    </div>
@endsection
