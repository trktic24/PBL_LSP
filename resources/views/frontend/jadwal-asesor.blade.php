@extends('layouts.app-asesor')
@section('content')

<main class="container mx-auto px-6 mt-20 mb-12">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
        Jadwal Asesmen
    </h1>

    <div class="mb-4">
        <form action="{{ url()->current() }}" method="GET" id="filterForm">
            <div class="flex justify-end items-center mb-4 space-x-2">

                {{-- TOMBOL TOGGLE FILTER --}}
                <button type="button" id="toggleFilterBtn"
                        class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-4 rounded-lg text-sm flex items-center shadow-md transition duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414A1 1 0 0012 15.586V21h-2v-5.414a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <span>Filter</span>
                </button>

                {{-- Kotak Pencarian (Search Box) --}}
                <div class="relative w-64">
                    <input type="text" name="search"
                        class="w-full pl-4 pr-12 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500"
                        placeholder="Cari..."
                        value="{{ $currentSearch ?? '' }}">
                    <button type="submit"
                        class="absolute right-0 top-0 h-full w-10 bg-amber-600 text-white rounded-r-lg flex items-center justify-center hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                        <svg class="w-5 h-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div id="filterContainer" class="bg-gray-100 p-4 rounded-lg shadow-inner mb-4 hidden">
                <h3 class="font-bold text-gray-700 mb-3 border-b pb-2">Filter Data Jadwal</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">

                    {{-- Filter Sesi --}}
                    <div>
                        <label for="filter_sesi" class="block text-sm font-medium text-gray-700">Sesi</label>
                        <select name="filter_sesi" id="filter_sesi" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm rounded-md">
                            <option value="">Semua Sesi</option>
                            @foreach (['1', '2', '3'] as $sesi)
                                <option value="{{ $sesi }}" {{ (request('filter_sesi') == $sesi) ? 'selected' : '' }}>{{ $sesi }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Status --}}
                    <div>
                        <label for="filter_status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="filter_status" id="filter_status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm rounded-md">
                            <option value="">Semua Status</option>
                            @foreach (['Terjadwal', 'Selesai', 'Dibatalkan'] as $status)
                                <option value="{{ $status }}" {{ (request('filter_status') == $status) ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Jenis TUK --}}
                    <div>
                        <label for="filter_jenis_tuk" class="block text-sm font-medium text-gray-700">Jenis TUK</label>
                        <select name="filter_jenis_tuk" id="filter_jenis_tuk" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm rounded-md">
                            <option value="">Semua Jenis TUK</option>
                            @foreach (['Sewaktu', 'Tempat Kerja'] as $jenis)
                                <option value="{{ $jenis }}" {{ (request('filter_jenis_tuk') == $jenis) ? 'selected' : '' }}>{{ $jenis }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Tanggal --}}
                    <div>
                        <label for="filter_tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                        <input type="date" name="filter_tanggal" id="filter_tanggal"
                               class="mt-1 block w-full pl-3 pr-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm rounded-md"
                               value="{{ request('filter_tanggal') }}">
                    </div>

                </div>

                <div class="mt-4 flex justify-end space-x-2">
                    <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-4 rounded-md text-sm transition duration-150">
                        Terapkan Filter
                    </button>
                    <a href="{{ url()->current() }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-md text-sm transition duration-150">
                        Reset Filter
                    </a>
                </div>
            </div>
            @if(isset($currentSortBy) && isset($currentSortDir))
                <input type="hidden" name="sort_by" value="{{ $currentSortBy }}">
                <input type="hidden" name="sort_dir" value="{{ $currentSortDir }}">
            @endif
        </form>
    </div>

    <div class="bg-amber-50 shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-separate border-spacing-0">
                <thead class="bg-amber-200 text-gray-800 rounded-t-lg">
                    <tr>
                        <th class="py-3 px-4 text-left">No</th>
                        {{-- Helper untuk membuat link sorting --}}
                        @php
                        $makeSortLink = function($column, $label) use ($currentSortBy, $currentSortDir) {
                            $dir = ($currentSortBy == $column && $currentSortDir == 'asc') ? 'desc' : 'asc';
                            $icon = ($currentSortBy == $column) ? ($currentSortDir == 'asc' ? '▲' : '▼') : '';
                            $url = url()->current() . '?' . http_build_query(array_merge(request()->query(), ['sort_by' => $column, 'sort_dir' => $dir]));
                            return '<a href="' . $url . '" class="hover:underline">' . $label . ' ' . $icon . '</a>';
                        };
                        @endphp                        
                        <th class="py-3 px-4 text-left">{!! $makeSortLink('nama_skema', 'Nama Skema') !!}</th>
                        <th class="py-3 px-4 text-center">{!! $makeSortLink('sesi', 'Sesi') !!}</th>
                        <th class="py-3 px-4 text-center">{!! $makeSortLink('waktu_mulai', 'Waktu Mulai') !!}</th>
                        <th class="py-3 px-4 text-center">{!! $makeSortLink('tanggal_pelaksanaan', 'Tanggal') !!}</th>
                        <th class="py-3 px-4 text-center">{!! $makeSortLink('Status_jadwal', 'Status') !!}</th>
                        <th class="py-3 px-4 text-center">{!! $makeSortLink('nama_lokasi', 'TUK') !!}</th>
                        <th class="py-3 px-4 text-center">Jenis TUK</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                {{-- Loop data dari controller --}}
                    @forelse ($jadwals as $jadwal)
                        <tr class="border-b hover:bg-amber-100">
                            {{-- Penomoran yang benar untuk pagination --}}
                            <td class="py-3 px-4">{{ ($jadwals->currentPage() - 1) * $jadwals->perPage() + $loop->iteration }}</td>
                            <td class="py-3 px-4">{{ $jadwal->skema->nama_skema ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-center">{{ $jadwal->sesi ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-center">{{ $jadwal->waktu_mulai ? $jadwal->waktu_mulai->format('H:i') : 'N/A' }}</td>
                            <td class="py-3 px-4 text-center">{{ $jadwal->tanggal_pelaksanaan ? \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->translatedFormat('d F Y') : 'N/A' }}</td>
                            <td class="py-3 px-4 text-center">{{ $jadwal->Status_jadwal ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-center">{{ $jadwal->tuk->nama_lokasi ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-center">{{ $jadwal->jenisTuk->jenis_tuk ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-center space-x-2">
                                @if ($jadwal->id_jadwal)
                                    <a href="{{ route('daftar_asesi', $jadwal->id_jadwal) }}"
                                       class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded-md text-sm font-medium">
                                        Lihat
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        {{-- Tampil jika $jadwals kosong --}}
                        <tr>
                            <td colspan="9" class="py-4 px-4 text-center text-gray-500">
                                @if(isset($currentSearch) && $currentSearch)
                                    Data tidak ditemukan untuk pencarian "{{ $currentSearch }}".
                                @else
                                    Belum ada jadwal asesmen yang tersedia.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 bg-amber-100 border-t border-amber-200">
            {{-- Ini akan merender link pagination (1, 2, 3, Next, Prev) --}}
            {{ $jadwals->links() }}
        </div>
    </div>
</main>

{{-- GABUNGKAN SEMUA SCRIPT DI SINI --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. SCRIPT UNTUK FILTER TOGGLE
        const toggleBtn = document.getElementById('toggleFilterBtn');
        const filterContainer = document.getElementById('filterContainer');

        if (toggleBtn && filterContainer) {
            // Fungsi untuk mengecek apakah ada filter yang aktif di URL
            const checkActiveFilters = () => {
                const params = new URLSearchParams(window.location.search);
                // Cek apakah ada nilai di parameter filter yang spesifik
                const filterParams = ['filter_sesi', 'filter_status', 'filter_jenis_tuk', 'filter_tanggal_mulai', 'filter_tanggal_akhir'];

                for (const param of filterParams) {
                    if (params.get(param)) {
                        return true;
                    }
                }
                return false;
            };

            // Jika ada filter yang aktif di URL, pastikan kontainer filter ditampilkan saat dimuat
            if (checkActiveFilters()) {
                filterContainer.classList.remove('hidden');
            }

            // Event listener untuk tombol filter
            toggleBtn.addEventListener('click', () => {
                filterContainer.classList.toggle('hidden');
            });
        }

        // 2. SCRIPT DROPDOWN (JIKA MASIH DIGUNAKAN DI TEMPAT LAIN)
        const dropdownToggle = document.getElementById('dropdownToggle');
        const dropdownMenu = document.getElementById('dropdownMenu');

        if (dropdownToggle && dropdownMenu) {
            dropdownToggle.addEventListener('click', (e) => {
                e.stopPropagation(); // Penting untuk mencegah event klik menyebar ke window
                dropdownMenu.classList.toggle('hidden');
            });

            window.addEventListener('click', function(e) {
                if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.add('hidden');
                }
            });
        }
    });
</script>

@endsection