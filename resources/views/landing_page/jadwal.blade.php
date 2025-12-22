@extends('layouts.app-profil')

@section('title', 'Jadwal Asesmen')
@section('content')

<style>
    /* TABLE STYLE LIKE IMAGE */
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

    /* FILTER STYLE */
    .filter-dropdown { position: relative; display: inline-block; }

    .filter-btn {
        padding: 7px 20px;
        font-size: 14px;
        border: 1px solid #444;
        border-radius: 999px;
        background: white;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: 0.2s;
    }
    .filter-btn:hover { background: #f3f3f3; }

    .filter-panel {
        position: absolute;
        top: 48px;
        left: 0;
        background: white;
        width: 280px;
        border-radius: 18px;
        padding: 12px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.12);
        border: 1px solid #ddd;
        display: none;
        z-index: 30;
    }
    .filter-panel.show { display: block; }

    .dropdown-btn {
        width: 100%;
        padding: 10px 14px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        background: #fafafa;
        font-size: 14px;
        cursor: pointer;
        margin-bottom: 6px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .submenu {
        display: none;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 10px;
        margin-top: 6px;
        max-height: 220px;
        overflow-y: auto;
    }
    .submenu.show { display: block; }
</style>


<!-- TITLE -->
<div class="bg-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-3xl font-bold text-gray-900 mb-2 font-poppins">Jadwal Asesmen</h1>
        <p class="text-gray-600">Berikut jadwal asesmen terbaru</p>
    </div>
</div>


<div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- FILTER -->
    <div class="flex flex-col sm:flex-row justify-between mb-4 gap-4">

        <!-- FILTER FORM -->
        <form method="GET" id="filterForm">
            <div class="filter-dropdown">

                <button type="button" onclick="toggleFilterPanel()" class="filter-btn">
                    <span>Filter</span>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                    </svg>
                </button>

                <!-- PANEL -->
                <div id="filterPanel" class="filter-panel">

                    <!-- SKEMA -->
                    <button type="button" class="dropdown-btn" onclick="toggleSubmenu('skemaBox')">
                        Skema Sertifikasi
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                        </svg>
                    </button>

                    <div id="skemaBox" class="submenu">
                        <input class="w-full p-2 border rounded-lg mb-2" placeholder="Cari..."
                               onkeyup="filterCheckbox('skemaBox', this.value)">

                        @foreach($listSkema as $skema)
                            <label class="flex items-center gap-2 text-sm mb-1">
                                <input type="checkbox" name="skema[]" value="{{ $skema->nama_skema }}"
                                    {{ is_array(request('skema')) && in_array($skema->nama_skema, request('skema')) ? 'checked' : '' }}>
                                {{ $skema->nama_skema }}
                            </label>
                        @endforeach
                    </div>

                    <!-- TUK -->
                    <button type="button" class="dropdown-btn" onclick="toggleSubmenu('tukBox')">
                        TUK
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                        </svg>
                    </button>

                    <div id="tukBox" class="submenu">
                        <input class="w-full p-2 border rounded-lg mb-2" placeholder="Cari..."
                               onkeyup="filterCheckbox('tukBox', this.value)">

                        @foreach($listTuk as $tuk)
                            <label class="flex items-center gap-2 text-sm mb-1">
                                <input type="checkbox" name="tuk[]" value="{{ $tuk->nama_lokasi }}"
                                    {{ is_array(request('tuk')) && in_array($tuk->nama_lokasi, request('tuk')) ? 'checked' : '' }}>
                                {{ $tuk->nama_lokasi }}
                            </label>
                        @endforeach
                    </div>

                    <!-- STATUS -->
                    <button type="button" class="dropdown-btn" onclick="toggleSubmenu('statusBox')">
                        Status
                        <svg fill="none" stroke="currentColor" class="w-4 h-4" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                        </svg>
                    </button>

                    <div id="statusBox" class="submenu">
                        @foreach(['Terjadwal','Full','Selesai','Dibatalkan'] as $status)
                            <label class="flex items-center gap-2 text-sm mb-1">
                                <input type="checkbox" name="status[]" value="{{ $status }}"
                                    {{ is_array(request('status')) && in_array($status, request('status')) ? 'checked' : '' }}>
                                {{ $status }}
                            </label>
                        @endforeach
                    </div>

                    <!-- TANGGAL ASESMENT (PERBAIKAN DI SINI) -->
                    <button type="button" class="dropdown-btn" onclick="toggleSubmenu('tanggalBox')">
                        Tanggal Asesmen
                        <svg fill="none" stroke="currentColor" class="w-4 h-4" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                        </svg>
                    </button>

                    <div id="tanggalBox" class="submenu">
                        <label class="text-sm mb-1">Dari:</label>
                        <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}"
                               class="w-full p-2 border rounded-lg mb-2">

                        <label class="text-sm mb-1">Sampai:</label>
                        <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai') }}"
                               class="w-full p-2 border rounded-lg">
                    </div>

                    <!-- APPLY / RESET -->
                    <div class="flex justify-between mt-4 pt-3 border-t">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">
                            Terapkan
                        </button>
                        <a href="{{ url('/jadwal') }}" class="px-4 py-2 bg-gray-300 rounded-lg text-sm">
                            Reset
                        </a>
                    </div>

                </div>
            </div>
        </form>

        <!-- SEARCH -->
        <form method="GET" action="{{ url('/jadwal') }}" class="relative" id="searchForm">
            <input type="text" id="searchInput" name="search" placeholder="Cari jadwal..."
                   value="{{ request('search') }}"
                   class="w-64 pl-10 pr-4 py-2 border border-gray-600 rounded-full text-sm">

            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-600"
                 fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </form>

    </div>



    <!-- ========== TABEL JADWAL (WARNA SUDAH MENYERUPAI GAMBAR) ========== -->
<div id="jadwalContainer">
    <div class="shadow-sm border border-[#E5DFA3] rounded-xl overflow-hidden overflow-x-auto">

        <div class="min-w-[800px]">
            <!-- HEADER -->
            <div class="grid grid-cols-5 px-6 py-4 custom-table-header text-sm font-poppins">
                <div>Skema Sertifikasi</div>
                <div class="text-center">Pendaftaran</div>
                <div class="text-center">Tanggal Asesmen</div>
                <div class="text-center">TUK</div>
                <div class="text-center">Status</div>
            </div>

            @forelse($jadwal as $item)

            @php
                $statusClass = [
                    'Terjadwal' => 'bg-green-100 text-green-800',
                    'Full'      => 'bg-yellow-100 text-yellow-800',
                    'Selesai'   => 'bg-gray-200 text-gray-700',
                    'Dibatalkan'=> 'bg-red-100 text-red-700',
                ][$item->Status_jadwal] ?? 'bg-gray-200 text-gray-700';
            @endphp

            <!-- ROW -->
            <div class="jadwal-row grid grid-cols-5 px-6 py-4 custom-table-row text-sm">

                <div class="flex items-center">
                    {{ $item->skema->nama_skema ?? 'N/A' }}
                </div>

                <div class="text-center">
                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M') }} -
                    {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}
                </div>

                <div class="text-center">
                    {{ \Carbon\Carbon::parse($item->tanggal_pelaksanaan)->format('d M Y') }}
                </div>

                <div class="text-center">
                    {{ $item->masterTuk->nama_lokasi ?? 'N/A' }}
                </div>

                <div class="text-center">
                    @if($item->Status_jadwal === 'Terjadwal')
                        <a href="{{ route('jadwal.detail', $item->id_jadwal) }}"
                            class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                            Lihat Detail
                        </a>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                            {{ $item->Status_jadwal }}
                        </span>
                    @endif
                </div>

            </div>

            @empty
            <div class="py-6 text-center text-gray-500">Tidak ada jadwal ditemukan.</div>
            @endforelse
        </div>

    </div>


    {{-- PAGINATION --}}
    @if (isset($jadwal) && method_exists($jadwal, 'links'))
        <div class="mt-4 overflow-x-auto">
            {{ $jadwal->appends(request()->except('page'))->onEachSide(1)->links() }}
        </div>
    @endif

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
    if (!e.target.closest(".filter-dropdown")) panel.classList.remove("show");
});
function filterCheckbox(boxId, keyword) {
    keyword = keyword.toLowerCase();
    const labels = document.querySelectorAll(`#${boxId} label`);
    labels.forEach(label => {
        label.style.display = label.textContent.toLowerCase().includes(keyword) ? "flex" : "none";
    });
}
</script>

<script>
document.getElementById('searchInput').addEventListener('input', function () {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('.jadwal-row');

    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
});
</script>

@endsection
