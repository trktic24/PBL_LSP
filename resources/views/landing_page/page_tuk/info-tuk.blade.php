@extends('layouts.app-profil')

@section('title', 'Tempat Uji Kompetensi')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /* CSS DARI DAFTAR ASESOR: Memastikan font, collapse, vertical align */
        body, table, th, td, input, button {
            font-family: 'Poppins', sans-serif !important;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
        }
        th, td {
            vertical-align: middle;
        }
        th {
            background-color: #FEF9C3; /* yellow-50 */
        }
        .table-container {
            border-radius: 1.5rem; /* rounded-3xl */
            overflow: hidden;
        }
    </style>

    {{-- HEADER BLOCK --}}
    <div class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Tempat Uji Kompetensi</h1>
            
            <p class="text-gray-600">Daftar lokasi pelaksanaan uji kompetensi<br>yang terdaftar secara resmi</p>
        </div>
    </div>

    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- KONTROL SEARCHING (HANYA SEARCH BOX DI KANAN) --}}
        <div class="flex justify-end mb-4">
            
            {{-- SEARCH BOX --}}
            <form method="GET" action="{{ url('/info-tuk') }}" class="relative">
                {{-- Membawa parameter sort yang sudah ada --}}
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                <input type="hidden" name="direction" value="{{ request('direction') }}">

                <input
                    type="text"
                    name="search"
                    placeholder="Search Tempat atau Alamat"
                    value="{{ request('search') }}"
                    class="w-64 pl-10 pr-4 py-2 border border-gray-600 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent"
                >
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </form>
        </div>

        {{-- Kartu Tabel --}}

        <div class="shadow-md border border-gray-200 table-container bg-white">
            <table class="min-w-full text-left text-gray-700">
                
                <thead class="border-b-2 border-gray-900">
                    <tr>
                        {{-- KOLOM TEMPAT (UNTUK SORTING) --}}
                        <th class="px-6 py-4 text-sm font-bold text-gray-900 text-center w-[30%] rounded-tl-3xl">
                            @php
                                $currentSort = request('sort', 'nama_lokasi');
                                $currentDirection = request('direction', 'asc');
                                $newDirection = ($currentSort == 'nama_lokasi' && $currentDirection == 'asc') ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ url('/info-tuk') }}?sort=nama_lokasi&direction={{ $newDirection }}&search={{ request('search') }}"
                               class="flex items-center justify-center">
                                Tempat
                                @if ($currentSort == 'nama_lokasi')
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $currentDirection == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path>
                                    </svg>
                                @endif
                            </a>
                        </th>
                        {{-- KOLOM ALAMAT (UNTUK SORTING) --}}
                         <th class="px-6 py-4 text-sm font-bold text-gray-900 text-center w-[40%]">
                            @php
                                $newDirection = ($currentSort == 'alamat_tuk' && $currentDirection == 'asc') ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ url('/info-tuk') }}?sort=alamat_tuk&direction={{ $newDirection }}&search={{ request('search') }}"
                               class="flex items-center justify-center">
                                Alamat
                                @if ($currentSort == 'alamat_tuk')
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $currentDirection == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path>
                                    </svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-900 text-center w-[20%]">Kontak</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-900 text-center w-[10%] rounded-tr-3xl">Detail</th>
                    </tr>
                </thead>

                <tbody class="bg-yellow-50 divide-y divide-gray-200">
                    @forelse ($tuks as $tuk)
                    <tr class="hover:bg-yellow-100 transition duration-150 ease-in-out">
                        <td class="px-6 py-4 text-gray-900 text-sm">
                            {{ $tuk->nama_lokasi }} <br>
                            <span class="text-xs text-gray-600">Terdaftar Resmi</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $tuk->alamat_tuk }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $tuk->kontak_tuk }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('info.tuk.detail', ['id' => $tuk->id_tuk]) }}"
                               class="bg-yellow-400 hover:bg-yellow-500 text-black text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm">
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
        
        {{-- PAGINATION LINKS --}}
        @if (isset($tuks) && method_exists($tuks, 'links'))
            <div class="mt-4">
                {{ $tuks->appends(request()->except('page'))->links() }}
            </div>
        @endif
    </div>
@endsection
