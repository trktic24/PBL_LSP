@extends('layouts.app-profil')
@section('content')

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Poppins', sans-serif !important; }

    /* ==============================
       STYLE TABEL SESUAI GAMBAR
    =============================== */
    .custom-table-header {
        background: #FFF8C6;
        color: #333;
        font-weight: 600;
        border-bottom: 2px solid #E5DFA3;
    }
    .custom-table-row {
        background: #FFFFFF !important;
        border-bottom: 1px solid #F1EEC7;
    }
    .custom-table-row:hover {
        background: #F7F7F7;
    }

    /* ==============================
       FILTER STYLE
    =============================== */
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


<div class="bg-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Daftar Asesor</h1>
        <p class="text-gray-600">Berikut daftar asesor beserta bidang pekerjannya</p>
    </div>
</div>


<div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex flex-col sm:flex-row justify-between mb-4 gap-4">
        
        {{-- FILTER --}}
        <form method="GET" id="filterForm">
            <div class="filter-dropdown">
                <button type="button" onclick="toggleFilterPanel()" class="filter-btn">
                    <span>Filter</span>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                    </svg>
                </button>

                <div id="filterPanel" class="filter-panel">
                    
                    {{-- Provinsi --}}
                    <button type="button" class="dropdown-btn" onclick="toggleSubmenu('provinsiBox')">
                        Provinsi
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                        </svg>
                    </button>

                    <div id="provinsiBox" class="submenu">
                        <input type="text" class="w-full mb-2 p-2 border rounded-lg"
                               placeholder="Cari..." onkeyup="filterCheckbox('provinsiBox', this.value)">
                        @foreach($listProvinsi as $prov)
                            <label class="flex items-center gap-2 text-sm mb-1">
                                <input type="checkbox" name="provinsi[]" value="{{ $prov }}"
                                    {{ is_array(request('provinsi')) && in_array($prov, request('provinsi')) ? 'checked' : '' }}>
                                {{ $prov }}
                            </label>
                        @endforeach
                    </div>

                    {{-- Bidang --}}
                    <button type="button" class="dropdown-btn" onclick="toggleSubmenu('bidangBox')">
                        Keahlian
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                        </svg>
                    </button>

                    <div id="bidangBox" class="submenu">
                        <input type="text" class="w-full mb-2 p-2 border rounded-lg"
                               placeholder="Cari..." onkeyup="filterCheckbox('bidangBox', this.value)">
                        @foreach($listBidang as $bid)
                            <label class="flex items-center gap-2 text-sm mb-1">
                                <input type="checkbox" name="bidang[]" value="{{ $bid }}"
                                    {{ is_array(request('bidang')) && in_array($bid, request('bidang')) ? 'checked' : '' }}>
                                {{ $bid }}
                            </label>
                        @endforeach
                    </div>

                    <div class="flex justify-between mt-4 pt-3 border-t">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Terapkan</button>
                        <a href="{{ url('/daftar-asesor') }}" class="px-4 py-2 bg-gray-300 rounded-lg text-sm">Reset</a>
                    </div>
                </div>
            </div>
        </form>

        {{-- SEARCH --}}
        <form method="GET" action="{{ url('/daftar-asesor') }}" class="relative">
            <input 
                type="text" 
                id="searchInput"
                name="search" 
                placeholder="Cari asesor..." 
                value="{{ request('search') }}"
                class="w-64 pl-10 pr-4 py-2 border border-gray-600 rounded-full text-sm">

            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </form>

    </div>

    {{-- TABLE --}}
    <div class="shadow-sm border border-[#E5DFA3] rounded-xl overflow-hidden">

        <div class="grid grid-cols-5 px-6 py-4 custom-table-header text-sm">
            <div class="font-bold">Nama Asesor</div>
            <div class="font-bold text-center">No. Registrasi</div>
            <div class="font-bold text-center">Bidang</div>
            <div class="font-bold text-center">Provinsi</div>
            <div class="font-bold text-center">Asesmen</div>
        </div>

        @forelse($asesors as $asesor)
            <div class="grid grid-cols-5 px-6 py-4 custom-table-row text-sm items-center">
                
                <div class="font-medium text-gray-900">{{ $asesor->nama_lengkap }}</div>
                <div class="text-center text-gray-600">{{ $asesor->nomor_regis }}</div>
                <div class="text-center text-gray-600">{{ $asesor->pekerjaan }}</div>
                <div class="text-center text-gray-600">{{ $asesor->provinsi }}</div>
                <div class="text-center text-gray-600 font-semibold">{{ rand(70,130) }}</div>

            </div>
        @empty
            <div class="py-8 text-center text-gray-500">Tidak ada data asesor ditemukan.</div>
        @endforelse

    </div>

    {{-- PAGINATION --}}
    @if (isset($asesors) && method_exists($asesors, 'links'))
        <div class="mt-4">
            {{ $asesors->appends(request()->except('page'))->links() }}
        </div>
    @endif

</div>


<script>
/* ================================
   FILTER
================================ */
function toggleFilterPanel() {
    document.getElementById("filterPanel").classList.toggle("show");
}
function toggleSubmenu(id) {
    document.getElementById(id).classList.toggle("show");
}
document.addEventListener('click', function(e) {
    if (!e.target.closest(".filter-dropdown")) {
        document.getElementById("filterPanel").classList.remove("show");
    }
});
function filterCheckbox(boxId, keyword) {
    keyword = keyword.toLowerCase();
    const labels = document.querySelectorAll(`#${boxId} label`);
    labels.forEach(label => {
        label.style.display = label.textContent.toLowerCase().includes(keyword)
            ? "flex" : "none";
    });
}

/* =======================================
   SEARCH:
   - Submit hanya saat ENTER
   - Jika dikosongkan → auto submit
======================================= */

const searchInput = document.getElementById("searchInput");
let lastSearchValue = searchInput.value;

searchInput.addEventListener("keyup", function(e) {

    // Jika dikosongkan → submit otomatis
    if (this.value.trim() === "") {
        if (lastSearchValue !== "") {
            lastSearchValue = "";
            this.form.submit();
        }
        return;
    }

    // Submit hanya saat ENTER
    if (e.key === "Enter") {
        lastSearchValue = this.value;
        this.form.submit();
    }
});
</script>

@endsection
