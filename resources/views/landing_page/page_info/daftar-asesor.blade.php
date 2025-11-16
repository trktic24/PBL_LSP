@extends('layouts.app-profil')
@section('content')

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Poppins', sans-serif !important; }

    /* ----------------------------- */
    /* FILTER BUTTON */
    /* ----------------------------- */
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

    /* ikon tidak gepeng */
    .filter-btn svg {
        width: 18px;
        height: 18px;
        stroke-width: 2.2;
        flex-shrink: 0;
    }

    /* ----------------------------- */
    /* FILTER PANEL */
    /* ----------------------------- */
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
        animation: fadeIn .18s ease-in-out;
    }
    .filter-panel.show { display: block; }

    @keyframes fadeIn {
        from { opacity:0; transform: translateY(-5px); }
        to { opacity:1; transform: translateY(0); }
    }

    /* ----------------------------- */
    /* SUBMENU DROPDOWN */
    /* ----------------------------- */
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

    /* icon submenu tidak gepeng */
    .dropdown-btn svg {
        width: 16px;
        height: 16px;
        stroke-width: 2;
        flex-shrink: 0;
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
        animation: fadeIn .15s ease-in-out;
    }
    .submenu.show { display: block; }

    .filter-title {
        font-weight: 600;
        margin-bottom: 6px;
        font-size: 13px;
    }
</style>


<div class="bg-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Daftar Asesor</h1>
        <p class="text-gray-600">Berikut daftar asesor<br>beserta bidang pekerjannya</p>
    </div>
</div>


<div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- FILTER -->
    <div class="flex flex-col sm:flex-row justify-between mb-4 gap-4">
        
        <form method="GET" id="filterForm">

            <div class="filter-dropdown">
                
                <button type="button" onclick="toggleFilterPanel()" class="filter-btn">
                    <span>Filter</span>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                    </svg>
                </button>

                <!-- Panel Filter -->
                <div id="filterPanel" class="filter-panel">

                    <!-- PROVINSI -->
                    <button type="button" class="dropdown-btn" onclick="toggleSubmenu('provinsiBox')">
                        Provinsi
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                        </svg>
                    </button>

                    <div id="provinsiBox" class="submenu">
                        <div class="filter-title">Pilih Provinsi</div>

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

                    <!-- BIDANG -->
                    <button type="button" class="dropdown-btn" onclick="toggleSubmenu('bidangBox')">
                        Keahlian
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                        </svg>
                    </button>

                    <div id="bidangBox" class="submenu">
                        <div class="filter-title">Pilih Keahlian</div>

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

                    <!-- BUTTONS -->
                    <div class="flex justify-between mt-4 pt-3 border-t">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Terapkan</button>

                        <a href="{{ url('/daftar-asesor') }}"
                           class="px-4 py-2 bg-gray-300 rounded-lg text-sm">Reset</a>
                    </div>

                </div>
            </div>

        </form>

        <!-- SEARCH UTAMA -->
        <form method="GET" action="{{ url('/daftar-asesor') }}" class="relative">
            <input type="text"
                   name="search"
                   placeholder="Cari asesor..."
                   value="{{ request('search') }}"
                   class="w-64 pl-10 pr-4 py-2 border border-gray-600 rounded-full text-sm">

            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-600"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </form>
    </div>


    <!-- TABEL -->
    <div class="table-container bg-yellow-50 shadow-md border border-gray-200">
        <table class="w-full">
            <thead class="border-b-2 border-gray-900">
            <tr>
                <th class="px-6 py-4 font-bold">Nama Asesor</th>
                <th class="px-6 py-4 font-bold">No. Registrasi</th>
                <th class="px-6 py-4 font-bold">Bidang</th>
                <th class="px-6 py-4 font-bold">Provinsi</th>
                <th class="px-6 py-4 font-bold">Asesmen</th>
            </tr>
            </thead>

            <tbody>
            @forelse($asesors as $asesor)
                <tr class="border-b bg-yellow-50 hover:bg-yellow-100">
                    <td class="px-6 py-4">{{ $asesor->nama_lengkap }}</td>
                    <td class="px-6 py-4 text-center">{{ $asesor->nomor_regis }}</td>
                    <td class="px-6 py-4 text-center">{{ $asesor->pekerjaan }}</td>
                    <td class="px-6 py-4 text-center">{{ $asesor->provinsi }}</td>
                    <td class="px-6 py-4 text-center">{{ rand(70,130) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">Tidak ada data asesor.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
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
            const text = label.textContent.toLowerCase();
            label.style.display = text.includes(keyword) ? "flex" : "none";
        });
    }
</script>

@endsection
