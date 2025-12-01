@extends('layouts.app-profil')

@section('title', 'Tempat Uji Kompetensi')
@section('description', 'Daftar Tempat Uji Kompetensi (TUK) yang terdaftar di LSP Polines.')

@section('content')
    <style>
        /* STYLE TABEL (SAMA DENGAN JADWAL) */
        .custom-table-header {
            background: #FFF8C6;
            color: #333;
            font-weight: 600;
            border-bottom: 2px solid #E5DFA3;
        }
        .custom-table-row {
            background: #FFFFFF;
            border-bottom: 1px solid #F1EEC7;
        }
        .custom-table-row:nth-child(even) {
            background: #FFFDF2;
        }
        .custom-table-row:hover {
            background: #F7F7F7;
        }
    </style>

    <div class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2 font-poppins">Tempat Uji Kompetensi</h1>
            <p class="text-gray-600">Daftar lokasi pelaksanaan uji kompetensi<br>yang terdaftar secara resmi</p>
        </div>
    </div>

    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- SEARCH BOX --}}
        <div class="flex justify-end mb-4">
            <form method="GET" action="{{ url('/info-tuk') }}" class="relative">
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                <input type="hidden" name="direction" value="{{ request('direction') }}">

                <input type="text" name="search" placeholder="Cari Tempat atau Alamat..." value="{{ request('search') }}"
                       class="w-64 pl-10 pr-4 py-2 border border-gray-600 rounded-full text-sm focus:outline-none">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </form>
        </div>

        <div class="shadow-sm border border-[#E5DFA3] rounded-xl overflow-hidden">

            {{-- Grid kolom disesuaikan: 30% | 40% | 20% | 10% --}}
            <div class="grid grid-cols-10 px-6 py-4 custom-table-header text-sm font-poppins">
                
                {{-- Tempat (3 Kolom) --}}
                <div class="col-span-3 font-bold flex items-center">
                    @php
                        $currentSort = request('sort', 'nama_lokasi');
                        $currentDirection = request('direction', 'asc');
                        $newDirection = ($currentSort == 'nama_lokasi' && $currentDirection == 'asc') ? 'desc' : 'asc';
                    @endphp
                    <a href="{{ url('/info-tuk') }}?sort=nama_lokasi&direction={{ $newDirection }}&search={{ request('search') }}" class="flex items-center hover:text-gray-600">
                        Tempat
                        @if ($currentSort == 'nama_lokasi')
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $currentDirection == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path>
                            </svg>
                        @endif
                    </a>
                </div>

                {{-- Alamat (4 Kolom) --}}
                <div class="col-span-4 font-bold flex items-center">
                    @php
                        $newDirection = ($currentSort == 'alamat_tuk' && $currentDirection == 'asc') ? 'desc' : 'asc';
                    @endphp
                    <a href="{{ url('/info-tuk') }}?sort=alamat_tuk&direction={{ $newDirection }}&search={{ request('search') }}" class="flex items-center hover:text-gray-600">
                        Alamat
                        @if ($currentSort == 'alamat_tuk')
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $currentDirection == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path>
                            </svg>
                        @endif
                    </a>
                </div>

                {{-- Kontak (2 Kolom) --}}
                <div class="col-span-2 font-bold text-center">Kontak</div>

                {{-- Detail (1 Kolom) --}}
                <div class="col-span-1 font-bold text-center">Detail</div>
            </div>

            @forelse ($tuks as $tuk)
                <div class="grid grid-cols-10 px-6 py-4 custom-table-row text-sm items-center">
                    
                    {{-- Tempat --}}
                    <div class="col-span-3">
                        <div class="font-medium text-gray-900">{{ $tuk->nama_lokasi }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">Terdaftar Resmi</div>
                    </div>

                    {{-- Alamat --}}
                    <div class="col-span-4 text-gray-700 pr-4">
                        {{ $tuk->alamat_tuk }}
                    </div>

                    {{-- Kontak --}}
                    <div class="col-span-2 text-center text-gray-700">
                        {{ $tuk->kontak_tuk }}
                    </div>

                    {{-- Detail --}}
                    <div class="col-span-1 text-center">
                        <a href="{{ route('info.tuk.detail', ['id' => $tuk->id_tuk]) }}"
                           class="bg-yellow-400 hover:bg-yellow-500 text-black text-xs font-semibold px-4 py-1.5 rounded-full shadow-sm transition">
                            Detail
                        </a>
                    </div>

                </div>
            @empty
                <div class="py-8 text-center text-gray-500">Belum ada Tempat Uji Kompetensi yang terdaftar.</div>
            @endforelse

        </div>
        
        {{-- PAGINATION LINKS --}}
        @if (isset($tuks) && method_exists($tuks, 'links'))
            <div class="mt-4">
                {{ $tuks->appends(request()->except('page'))->links() }}
            </div>
        @endif
    </div>
@endsection