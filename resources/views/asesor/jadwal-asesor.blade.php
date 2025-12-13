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

<main class="container mx-auto px-6 mt-20 mb-12">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
        Jadwal Asesmen
    </h1>

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

                    <button type="button" class="dropdown-btn" onclick="toggleSubmenu('sesiBox')">
                        Sesi
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                        </svg>
                    </button>
                    <div id="sesiBox" class="submenu">
                        <input type="text" class="w-full mb-2 p-2 border rounded-lg" placeholder="Cari..." onkeyup="filterCheckbox('sesiBox', this.value)">
                        @foreach($listSesi as $sesi)
                            <label class="flex items-center gap-2 text-sm mb-1">
                                <input type="checkbox" name="sesi[]" value="{{ $sesi }}" {{ is_array(request('sesi')) && in_array($sesi, request('sesi')) ? 'checked' : '' }}>
                                {{ $sesi }}
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

                    <button type="button" class="dropdown-btn" onclick="toggleSubmenu('tukBox')">
                        TUK
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                        </svg>
                    </button>
                    <div id="tukBox" class="submenu">
                        <input type="text" class="w-full mb-2 p-2 border rounded-lg" placeholder="Cari..." onkeyup="filterCheckbox('tukBox', this.value)">
                        @foreach($listTuk as $tuk)
                            <label class="flex items-center gap-2 text-sm mb-1">
                                <input type="checkbox" name="tuk[]" value="{{ $tuk }}" {{ is_array(request('tuk')) && in_array($tuk, request('tuk')) ? 'checked' : '' }}>
                                {{ $tuk }}
                            </label>
                        @endforeach
                    </div>    
                    
                    <button type="button" class="dropdown-btn" onclick="toggleSubmenu('jenistukBox')">
                        Jenis TUK
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                        </svg>
                    </button>
                    <div id="jenistukBox" class="submenu">
                        <input type="text" class="w-full mb-2 p-2 border rounded-lg" placeholder="Cari..." onkeyup="filterCheckbox('jenistukBox', this.value)">
                        @foreach($listjenisTuk as $jenistuk)
                            <label class="flex items-center gap-2 text-sm mb-1">
                                <input type="checkbox" name="jenistuk[]" value="{{ $jenistuk }}" {{ is_array(request('jenistuk')) && in_array($jenistuk, request('jenistuk')) ? 'checked' : '' }}>
                                {{ $jenistuk }}
                            </label>
                        @endforeach
                    </div>                    

                    <div class="flex justify-between mt-4 pt-3 border-t">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Terapkan</button>
                        <a href="{{ route('asesor.jadwal.index') }}" class="px-4 py-2 bg-gray-300 rounded-lg text-sm">Reset</a>
                    </div>
                </div>
            </div>
        </form>

        <form method="GET" action="{{ route('asesor.jadwal.index') }}" class="relative">
            <input type="text" name="search" placeholder="Cari jadwal..." value="{{ request('search') }}" class="w-64 pl-10 pr-4 py-2 border border-gray-600 rounded-full text-sm">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </form>
    </div>

    <div class="bg-yellow-100 shadow-md rounded-lg overflow-x-auto">
            <table class="w-full border-collapse">
                <thead class="bg-yellow-100 text-gray-800 rounded-t-lg border-b-2 border-gray-800">
                    <tr>
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Nama Skema</th>
                        <th class="py-3 px-4 text-center">Sesi</th>
                        <th class="py-3 px-4 text-center">Waktu Mulai</th>
                        <th class="py-3 px-4 text-center">Tanggal</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">TUK</th>
                        <th class="py-3 px-4 text-center">Jenis TUK</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                {{-- Loop data dari controller --}}
                    @forelse($jadwals as $index => $jadwal)
                        <tr class="border-b bg-yellow-50 hover:bg-yellow-100">
                            {{-- Penomoran yang benar untuk pagination --}}
                            <td class="px-4 py-2">{{ $index + 1 }}</td>
                            <td class="py-3 px-4">{{ $jadwal->skema->nama_skema ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-center">{{ $jadwal->sesi ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-center">{{ $jadwal->waktu_mulai ? $jadwal->waktu_mulai->format('H:i') : 'N/A' }}</td>
                            <td class="py-3 px-4 text-center">{{ $jadwal->tanggal_pelaksanaan ? \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->translatedFormat('d F Y') : 'N/A' }}</td>
                            <td class="px-4 py-2">
                                @if($jadwal->Status_jadwal == 'Dibatalkan')
                                    <span class="px-2 py-1 rounded-full text-xs-center text-red-500 bg-red-100 ">
                                        Dibatalkan
                                    </span>
                                @elseif($jadwal->Status_jadwal == 'Selesai')
                                    <span class="px-2 py-1 rounded-full text-xs-center text-green-600 bg-green-100">
                                        Selesai
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-xs-center text-blue-500 bg-blue-100">
                                        Terjadwal
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">{{ $jadwal->masterTuk->nama_lokasi ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-center">{{ $jadwal->jenisTuk->jenis_tuk ?? 'N/A' }}</td>
                            <td class="px-4 py-2 text-center space-x-2">
                                {{-- 1. Cek apakah ID Jadwal tersedia (Safety Check) --}}
                                @if ($jadwal->id_jadwal)
                                    
                                    {{-- 2. Logika Status: Beda Tampilan, Rute Sama --}}
                                    @if($jadwal->Status_jadwal == 'Dibatalkan')
                                        {{-- Tampilan Tombol Detail (Abu-abu) --}}
                                        <a href="{{ route('asesor.daftar_asesi', $jadwal->id_jadwal) }}" 
                                        class="text-sm bg-gray-400 text-white px-3 py-1 rounded hover:bg-gray-500 transition duration-150">
                                            Detail
                                        </a>
                                    @else
                                        {{-- Tampilan Tombol Lihat (Kuning Emas) --}}
                                        <a href="{{ route('asesor.daftar_asesi', $jadwal->id_jadwal) }}" 
                                        class="text-sm bg-yellow-600 text-white px-3 py-1 rounded hover:bg-yellow-700 transition duration-150 font-medium">
                                            Lihat
                                        </a>
                                    @endif

                                @else
                                    {{-- Jika ID Jadwal Null/Error --}}
                                    <span class="text-gray-400 text-sm italic">N/A</span>
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
    <div class="p-4 bg-yellow-50 border-t hover:bg-yellow-100">
        {{-- Ini akan merender link pagination (1, 2, 3, Next, Prev) --}}
        {{ $jadwals->links() }}
    </div>
</main>

{{-- GABUNGKAN SEMUA SCRIPT DI SINI --}}
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