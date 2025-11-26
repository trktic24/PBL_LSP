@extends('layouts.app-profil')
@section('content')

<style>

    /* FILTER STYLE */
    .filter-dropdown { position: relative; display: inline-block; }
    .filter-btn {
        padding: 7px 20px; font-size: 14px; border: 1px solid #444;
        border-radius: 999px; background: white; display: flex;
        align-items: center; gap: 8px; cursor: pointer; transition: 0.2s;
    }
    .filter-btn:hover { background: #f3f3f3; }
    .filter-panel {
        position: absolute; top: 48px; left: 0; background: white;
        width: 280px; border-radius: 18px; padding: 12px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.12); border: 1px solid #ddd;
        display: none; z-index: 30;
    }
    .filter-panel.show { display: block; }
    .dropdown-btn {
        width: 100%; padding: 10px 14px; border-radius: 12px;
        border: 1px solid #e5e7eb; background: #fafafa; font-size: 14px;
        cursor: pointer; margin-bottom: 6px; display: flex;
        justify-content: space-between; align-items: center;
    }
    .submenu {
        display: none; background: #fff; border: 1px solid #e5e7eb;
        border-radius: 12px; padding: 10px; margin-top: 6px;
        max-height: 220px; overflow-y: auto;
    }
    .submenu.show { display: block; }
</style>

<div class="container mx-auto px-6 mt-20 mb-12">
        <div class="flex items-center space-x-5 mb-10">
            <img src="{{ Auth::user()->asesor?->url_foto ?? asset('images/profil_asesor.jpeg') }}"
                alt="Foto Profil"
                class="w-20 h-20 rounded-full object-cover border-4 border-blue-500">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Selamat Datang {{ $profile['nama'] }}!</h1>
                <p class="text-xl font-semibold text-gray-800 mt-1">{{ $profile['nama'] }}</p>
                <p class="text-base text-gray-600">{{ $profile['nomor_registrasi'] }}</p>
                <p class="text-base text-gray-600">{{ $profile['kompetensi'] }}</p>
            </div>
        </div>

        <div class="mb-10">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Ringkasan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="bg-blue-600 rounded-xl shadow-lg p-6 text-white flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-5xl font-bold">5</span>
                        <span class="text-base font-medium mt-1">Asesmen<br>Belum Direview</span>
                    </div>
                    <div class="text-6xl opacity-70">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                </div>

                <div class="bg-blue-600 rounded-xl shadow-lg p-6 text-white flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-5xl font-bold">7</span>
                        <span class="text-base font-medium mt-1">Asesmen<br>Dalam Proses</span>
                    </div>
                    <div class="text-6xl opacity-70">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>

                <div class="bg-blue-600 rounded-xl shadow-lg p-6 text-white flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-5xl font-bold">4</span>
                        <span class="text-base font-medium mt-1">Asesmen<br>Telah Direview</span>
                    </div>
                    <div class="text-6xl opacity-70">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7l-3 3-1.5-1.5" />
                        </svg>
                    </div>
                </div>

                <div class="bg-blue-600 rounded-xl shadow-lg p-6 text-white flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-5xl font-bold">18</span>
                        <span class="text-base font-medium mt-1">Jumlah<br>Asesi</span>
                    </div>
                    <div class="text-6xl opacity-70">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-semibold text-gray-800">Jadwal Anda</h2>
            </div>

    <div class="flex flex-col sm:flex-row justify-between mb-4 gap-4">
        
        <form method="GET" id="filterForm">
            <div class="filter-dropdown">
                <button type="button" onclick="toggleFilterPanel()" class="filter-btn">
                    <span>Filter</span>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                    </svg>
                </button>

                <div id="filterPanel" class="filter-panel">
                    <button type="button" class="dropdown-btn" onclick="toggleSubmenu('namaskemaBox')">
                        Nama Skema
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                        </svg>
                    </button>
                    <div id="namaskemaBox" class="submenu">
                        <input type="text" class="w-full mb-2 p-2 border rounded-lg" placeholder="Cari..." onkeyup="filterCheckbox('namaskemaBox', this.value)">
                        @foreach($listSkema as $skema)
                            <label class="flex items-center gap-2 text-sm mb-1">
                                <input type="checkbox" name="namaskema[]" value="{{ $skema }}" {{ is_array(request('skema')) && in_array($skema, request('skema')) ? 'checked' : '' }}>
                                {{ $skema }}
                            </label>
                        @endforeach
                    </div>

                    <button type="button" class="dropdown-btn" onclick="toggleSubmenu('waktuBox')">
                        Waktu Mulai
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                        </svg>
                    </button>
                    <div id="waktuBox" class="submenu">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai</label>
                        <input type="time" name="waktu" 
                            value="{{ request('waktu') }}"
                            class="w-full mb-2 p-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <button type="button" class="dropdown-btn" onclick="toggleSubmenu('tanggalBox')">
                        Tanggal
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                        </svg>
                    </button>
                    <div id="tanggalBox" class="submenu">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" 
                            value="{{ request('tanggal') }}"
                            class="w-full mb-2 p-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>                    

                    <button type="button" class="dropdown-btn" onclick="toggleSubmenu('statusBox')">
                        Status
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                        </svg>
                    </button>
                    <div id="statusBox" class="submenu">
                        <input type="text" class="w-full mb-2 p-2 border rounded-lg" placeholder="Cari..." onkeyup="filterCheckbox('statusBox', this.value)">
                        @foreach($listStatus as $status)
                            <label class="flex items-center gap-2 text-sm mb-1">
                                <input type="checkbox" name="status[]" value="{{ $status }}" {{ is_array(request('status')) && in_array($status, request('status')) ? 'checked' : '' }}>
                                {{ $status }}
                            </label>
                        @endforeach
                    </div>

                    <div class="flex justify-between mt-4 pt-3 border-t">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Terapkan</button>
                        <a href="{{ url('/home') }}" class="px-4 py-2 bg-gray-300 rounded-lg text-sm">Reset</a>
                    </div>
                </div>
            </div>
        </form>

        <form method="GET" action="{{ url('/home') }}" class="relative">
            <input type="text" name="search" placeholder="Cari jadwal..." value="{{ request('search') }}" class="w-64 pl-10 pr-4 py-2 border border-gray-600 rounded-full text-sm">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </form>
    </div>
        

    <div class="bg-yellow-50 shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead class="bg-yellow-100 border-b-2 border-gray-800">
                    <tr>
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Nama Skema</th>
                        <th class="py-3 px-4 text-center">Waktu Mulai</th>
                        <th class="py-3 px-4 text-center">Tanggal</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    {{-- Loop data dari controller --}}
                    @forelse ($jadwals as $jadwal)
                        <tr class="border-b hover:bg-yellow-100">
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">{{ $jadwal->skema->nama_skema ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-center">{{ $jadwal->waktu_mulai ? \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') : 'N/A' }}</td>
                            <td class="py-3 px-4 text-center">{{ $jadwal->tanggal_pelaksanaan ? \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->translatedFormat('d F Y') : 'N/A' }}</td>
                            <td class="py-3 px-4 text-center">{{ $jadwal->Status_jadwal ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-center space-x-2">
                                @if ($jadwal->id_jadwal)
                                    <a href="{{ route('daftar_asesi', $jadwal->id_jadwal) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded-md text-sm font-medium">
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
                                Belum ada jadwal asesmen yang tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>              
            </table>
        </div>
        {{-- PAGINATION --}}
        @if (isset($jadwals) && method_exists($jadwals, 'links'))
            <div class="mt-4">
                {{ $jadwals->appends(request()->except('page'))->links() }}
            </div>
        @endif  
    </div>

            <div class="text-right mt-4">
                <a href="{{ route('jadwal.index') }}" class="text-sm text-blue-600 hover:underline font-medium">Lihat Selengkapnya</a>
            </div>
        </div>
</div>

<script>
    function toggleFilterPanel() {
        document.getElementById("filterPanel").classList.toggle("show");
    }
    function toggleSubmenu(id) {
        document.getElementById(id).classList.toggle("show");
    }
    document.addEventListener('click', function(e) {
        const panel = document.getElementById("filterPanel");
        if (!e.target.closest(".filter-dropdown")) {
            panel.classList.remove("show");
        }
    });
    function filterCheckbox(boxId, keyword) {
        keyword = keyword.toLowerCase();
        const labels = document.querySelectorAll(`#${boxId} label`);
        labels.forEach(label => {
            label.style.display = label.textContent.toLowerCase().includes(keyword) ? "flex" : "none";
        });
    }
</script>

@endsection