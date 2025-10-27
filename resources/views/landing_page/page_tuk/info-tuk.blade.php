@extends('layouts.app-profil')

@section('title', 'Tempat Uji Kompetensi')

@section('content')
{{-- Sembunyikan navbar hanya di halaman ini --}}
<style>
    body.info-tuk-page nav {
        display: none !important;
    }
</style>

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

            {{-- Body --}}
            <tbody class="bg-[#fffbea] divide-y divide-gray-200">
                <tr class="hover:bg-[#fff4d6] transition">
                    <td class="px-6 py-4 text-gray-900">
                        Politeknik Negeri Semarang <br>
                        <span class="text-sm text-gray-600">Gedung Kuliah Terpadu</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        Jl. Prof. Soedarto, SH.<br>Tembalang, Semarang, Jawa Tengah
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">(024) 7473417 ext.256</td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ url('/info-tuk/detail/polines-gkt') }}"
                           class="bg-yellow-400 hover:bg-yellow-500 text-black text-sm font-semibold px-4 py-2 rounded-full shadow-sm">
                            Detail
                        </a>
                    </td>
                </tr>

                <tr class="hover:bg-[#fff4d6] transition">
                    <td class="px-6 py-4 text-gray-900">
                        Politeknik Negeri Semarang <br>
                        <span class="text-sm text-gray-600">MST LT3</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        Jl. Prof. Soedarto, SH.<br>Tembalang, Semarang, Jawa Tengah
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">25 Oktober 2025</td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ url('/info-tuk/detail/polines-mst-lt3') }}"
                           class="bg-yellow-400 hover:bg-yellow-500 text-black text-sm font-semibold px-4 py-2 rounded-full shadow-sm">
                            Detail
                        </a>
                    </td>
                </tr>

                <tr class="hover:bg-[#fff4d6] transition">
                    <td class="px-6 py-4 text-gray-900">
                        Politeknik Negeri Semarang <br>
                        <span class="text-sm text-gray-600">Gedung Sekolah Satu</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        Jl. Prof. Soedarto, SH.<br>Tembalang, Semarang, Jawa Tengah
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">(024) 7473417 ext.256</td>
                    <td class="px-6 py-4 text-center rounded-b-3xl">
                        <a href="{{ url('/info-tuk/detail/polines-gedung-satu') }}"
                           class="bg-yellow-400 hover:bg-yellow-500 text-black text-sm font-semibold px-4 py-2 rounded-full shadow-sm">
                            Detail
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
