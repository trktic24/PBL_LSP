@extends('layouts.soal')

@section('title', 'Daftar Soal')

@section('content')
    <div class="mb-4 flex justify-end">
        <a href="{{ route('soal.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Tambah Soal</a>
    </div>


    <div>
        <table class="border border-black mb-5">

            <th>Soal</th>
            <th>Jawaban</th>
            @foreach($soals as $soal)
            <tr>
                <td >{{ $soal->soal_IA06 }}</td>

                @forelse($soal->kuncis as $kunci)
                <td colspan="2">{{ $kunci->kunci_IA06}}</td>
                @endforeach
            </tr>
            @endforeach
        </table>
    </div>
    @foreach($soals as $soal)
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-lg">{{ $soal->soal_IA06 }}</h2>
                <div class="space-x-2">
                    <a href="{{ route('soal.edit', $soal->id_soal_IA06) }}" class="text-blue-600">Edit</a>
                    <form action="{{ route('soal.destroy', $soal->id_soal_IA06) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600" onclick="return confirm('Hapus soal ini?')">Hapus</button>
                    </form>
                </div>
            </div>

            <div class="mt-3 border-t pt-3">
                <p class="font-medium mb-2">Opsi Jawaban:</p>
                <ul class="list-disc ml-6 space-y-1">
                    @forelse($soal->kuncis as $kunci)
                        <li class="flex justify-between items-center">
                            <span>{{ $kunci->kunci_IA06}}</span>
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
                                    <button type="submit" class="text-red-500 text-sm ml-2" onclick="return confirm('Hapus kunci ini?')">Hapus</button>
                                </form>
                            </div>
                        </li>
                    @empty
                        <li class="text-gray-500 italic">Belum ada kunci jawaban.</li>
                    @endforelse
                </ul>

                <form action="{{ route('kunci.store', $soal->id_soal_IA06) }}" method="POST" class="mt-3 flex space-x-2">
                    @csrf
                    <input type="text" name="kunci_IA06" placeholder="Tambah kunci jawaban..." class="border rounded px-3 py-1 w-full">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded">Tambah</button>
                </form>
            </div>
        </div>
    @endforeach
@endsection
