@extends('layouts.app-profil')

@section('title', 'Tempat Uji Kompetensi')

@section('content')

<div class="min-h-screen bg-[#fffdf5] py-16 px-6 lg:px-8">
    {{-- Judul --}}
    <div class="max-w-4xl mx-auto text-center mb-10">
        <h1 class="text-3xl font-semibold text-gray-800">Tempat Uji Kompetensi</h1>
        <div class="mt-2 w-24 h-1 bg-yellow-400 mx-auto rounded-full"></div>
    </div>

    {{-- Kartu Tabel --}}
    <div class="max-w-6xl mx-auto bg-[#fffef7] rounded-3xl shadow-md overflow-hidden border border-gray-200">
        <table class="min-w-full text-left text-gray-700">
            {{-- Header --}}
            <thead class="bg-[#fffbea] border-b-2 border-black">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-800 rounded-tl-3xl">Tempat</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-800">Alamat</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-800">Kontak</th>
                    <th class="px-6 py-4 text-sm font-semibold text-center rounded-tr-3xl">Detail</th>
                </tr>
            </thead>

            {{-- BODY DINAMIS --}}
            <tbody class="bg-[#fffbea] divide-y divide-gray-200">
                @forelse ($tuks as $tuk)
                <tr class="hover:bg-[#fff4d6] transition">
                    <td class="px-6 py-4 text-gray-900">
                        {{-- MENGGUNAKAN SINTAKS OBJEK: $tuk->nama --}}
                        {{ $tuk->nama }} <br>
                        <span class="text-sm text-gray-600">{{ $tuk->sub_nama }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{-- MENGGUNAKAN SINTAKS OBJEK: $tuk->alamat --}}
                        {{ $tuk->alamat }}<br>Tembalang, Semarang, Jawa Tengah
                    </td>
                    {{-- MENGGUNAKAN SINTAKS OBJEK: $tuk->kontak --}}
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $tuk->kontak }}</td> 
                    <td class="px-6 py-4 text-center">
                        {{-- PERBAIKAN RUTE: Menggunakan sintaks objek dan primary key $tuk->id_tuk --}}
                        <a href="{{ route('info-tuk.detail', ['id' => $tuk->id_tuk ]) }}"
                            class="bg-yellow-400 hover:bg-yellow-500 text-black text-sm font-semibold px-4 py-2 rounded-full shadow-sm">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500 text-lg">
                        Belum ada Tempat Uji Kompetensi yang terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
