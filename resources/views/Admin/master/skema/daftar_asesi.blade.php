@php
    $isMasterView = $isMasterView ?? false;
    $backUrl = $isMasterView 
        ? route('admin.skema.detail', $jadwal->skema->id_skema ?? 0) 
        : route('admin.asesor_profile_tinjauan', $asesor->id_asesor ?? 0);
    $headerTitle = $isMasterView ? 'Daftar Seluruh Asesi' : 'Daftar Asesi';
    $headerSubTitle = $isMasterView 
        ? 'Daftar asesi untuk seluruh skema ini.' 
        : 'Kelola penilaian dan verifikasi untuk setiap asesi pada jadwal ini.';
    
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Asesi | LSP Polines</title> 

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('sidebar', {
            open: window.innerWidth > 1024,
            setOpen(val) { this.open = val },
            toggle() { this.open = !this.open }
        })
    })
  </script>

  <style>
    body { font-family: 'Poppins', sans-serif; background-color: #f9fafb; }
    ::-webkit-scrollbar { width: 0; }
    [x-cloak] { display: none !important; }
  </style>
</head>

<body class="text-gray-800" x-data x-cloak>

  {{-- Navbar Admin --}}
  <x-navbar.navbar-admin />
  
  <div class="flex min-h-[calc(100vh-80px)]">
    
    {{-- Overlay Mobile --}}
    <div 
        x-show="$store.sidebar.open" 
        @click="$store.sidebar.setOpen(false)"
        class="lg:hidden fixed inset-0 bg-black/50 z-40 transition-opacity duration-300"
        x-transition:enter="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="opacity-100"
        x-transition:leave-end="opacity-0">
    </div>

    @if($isMasterView)
        {{-- Sidebar Skema / Generic untuk Master View --}}
        <x-sidebar.sidebar-skema :jadwal="$jadwal" :backUrl="$backUrl" />
    @else
        {{-- Sidebar Asesor (Context: Asesor Profile) --}}
        <x-sidebar.sidebar_profile_asesor :asesor="$asesor" />
    @endif

    {{-- Tombol Buka Sidebar (Desktop & Mobile) --}}
    <button
        x-show="!$store.sidebar.open"
        class="fixed top-[100px] left-6 z-40 bg-white text-blue-600 border border-blue-100 p-2 rounded-lg shadow-md hover:bg-blue-50 transition flex items-center gap-2"
        @click="$store.sidebar.setOpen(true)"
        x-transition.opacity>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <span class="text-sm font-semibold hidden lg:block">Menu</span>
    </button>

    <main 
        class="h-[calc(100vh-80px)] overflow-y-auto p-8 bg-gray-50 flex-1 transition-all duration-300"
        :class="$store.sidebar.open ? 'lg:ml-80' : 'ml-0'"
    >
      
      <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] p-10 border border-gray-100 min-h-full">
        
        {{-- Header Page --}}
        <div class="relative flex items-center justify-center mb-8">
            <a href="{{ $backUrl }}" class="absolute left-0 top-1 text-gray-500 hover:text-blue-600 transition text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-800">{{ $headerTitle }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $headerSubTitle }}</p>
                @if($isMasterView && isset($buttonLabel))
                    <div class="mt-3 flex flex-col items-center gap-2">
                        @if(isset($formName))
                            <span class="text-lg font-semibold text-blue-700 uppercase tracking-wide">
                                {{ $formName }}
                            </span>
                        @endif
                        <span class="px-4 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-full shadow-sm">
                            KODE: {{ $buttonLabel }}
                        </span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Card Informasi Jadwal --}}
        <div class="bg-blue-50/50 rounded-2xl border border-blue-100 p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8 text-sm">
                <div>
                    <span class="block text-xs font-semibold text-blue-600 uppercase tracking-wide mb-1">Skema Sertifikasi</span>
                    <span class="text-gray-800 font-bold text-lg">{{ $jadwal->skema->nama_skema ?? '-' }}</span>
                    <span class="block text-gray-500 mt-1">No: {{ $jadwal->skema->nomor_skema ?? '-' }}</span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    @if(!$isMasterView)
                        <div>
                            <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Tanggal</span>
                            <div class="flex items-center text-gray-700 font-medium">
                                <i class="far fa-calendar-alt mr-2 text-blue-400"></i>
                                {{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->isoFormat('D MMMM Y') }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Waktu</span>
                            <div class="flex items-center text-gray-700 font-medium">
                                <i class="far fa-clock mr-2 text-blue-400"></i>
                                {{ \Carbon\Carbon::parse($jadwal->waktu_mulai ?? '00:00')->format('H:i') }} WIB
                            </div>
                        </div>
                        <div class="col-span-2">
                            <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Lokasi TUK</span>
                            <div class="flex items-center text-gray-700 font-medium">
                                <i class="fas fa-map-marker-alt mr-2 text-blue-400"></i>
                                {{ $jadwal->masterTuk->nama_lokasi ?? '-' }}
                            </div>
                        </div>
                    @else
                        <div class="col-span-2 flex items-center justify-end">
                            <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm border border-blue-200">
                                <i class="fas fa-layer-group mr-2"></i> Master View Mode
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- TOOLBAR: SEARCH & PER PAGE --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            
            {{-- Show Entries --}}
            <div x-data="{ perPage: '{{ request('per_page', $perPage ?? 10) }}', changePerPage() { let url = new URL(window.location.href); url.searchParams.set('per_page', this.perPage); url.searchParams.set('page', this.perPage); window.location.href = url.href; } }" class="flex items-center space-x-2">
                <label for="per_page" class="text-sm text-gray-600 font-medium">Tampilkan:</label>
                <select id="per_page" x-model="perPage" @change="changePerPage()" class="bg-white text-sm border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 py-2 px-3">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                <span class="text-sm text-gray-600">data</span>
            </div>

            {{-- Search Form --}}
            <form action="{{ url()->current() }}" method="GET" class="w-full md:w-auto" x-data="{ search: '{{ request('search', '') }}' }">
                <!-- Keep existing params -->
                @foreach(request()->except(['search', 'page']) as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                
                <div class="relative w-full md:w-72">
                    <input type="text" name="search" x-model="search" placeholder="Cari nama asesi..." class="w-full pl-10 pr-10 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white shadow-sm transition" />
                    <button type="submit" class="absolute left-3 top-0 h-full text-gray-400 hover:text-blue-600"><i class="fas fa-search"></i></button>
                    <button type="button" class="absolute right-3 top-0 h-full text-gray-400 hover:text-red-500" x-show="search.length > 0" @click="search = ''; $nextTick(() => $el.form.submit())" x-cloak><i class="fas fa-times"></i></button>
                </div>
            </form>
        </div>

        {{-- Tabel Daftar Asesi --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 overflow-x-auto">
            <table class="min-w-full text-xs text-left border border-gray-200">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr class="divide-x divide-gray-200 border-b border-gray-200">
                        @php
                            $baseParams = request()->except(['sort', 'direction', 'page']);
                            $sortColumn = $sortColumn ?? 'nama_lengkap';
                            $sortDirection = $sortDirection ?? 'asc';
                        @endphp

                        <th class="px-4 py-3 font-semibold w-16 whitespace-nowrap text-center">No</th>
                        
                        {{-- Kolom Nama dengan Sorting --}}
                        <th class="px-6 py-3 font-semibold whitespace-nowrap">
                            @php $isCurrent = $sortColumn == 'nama_lengkap'; @endphp
                            <a href="{{ request()->fullUrlWithQuery(array_merge($baseParams, ['sort' => 'nama_lengkap', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex items-center group cursor-pointer hover:text-blue-600 transition">
                                <span>Nama Asesi</span>
                                <div class="flex flex-col ml-2 -space-y-1 text-[10px]">
                                    <i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-blue-600' : 'text-gray-300 group-hover:text-blue-400' }}"></i>
                                    <i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-blue-600' : 'text-gray-300 group-hover:text-blue-400' }}"></i>
                                </div>
                            </a>
                        </th>
                        
                        <th class="px-6 py-3 font-semibold text-center whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($pendaftar as $index => $item)
                        <tr class="hover:bg-gray-50 transition divide-x divide-gray-200">
                            {{-- No --}}
                            <td class="px-4 py-4 text-center align-middle">
                                {{ ($pendaftar->currentPage() - 1) * $pendaftar->perPage() + $loop->iteration }}
                            </td>
                            
                            <td class="px-6 py-4 text-left align-middle">
                                <a href="{{ url('/admin/asesi/' . ($item->asesi->id_asesi ?? 0) . '/settings') }}" class="group block">
                                    <div class="font-bold text-gray-800 group-hover:text-blue-600 transition">{{ $item->asesi->nama_lengkap ?? 'Nama Tidak Ditemukan' }}</div>
                                    @if($item->asesi->dataPekerjaan)
                                        <div class="text-xs text-gray-400 font-normal mt-0.5 group-hover:text-blue-400 transition">{{ $item->asesi->dataPekerjaan->nama_institusi_pekerjaan }}</div>
                                    @endif
                                </a>
                            </td>
                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-center align-middle">
                                <a href="{{ route($targetRoute ?? 'admin.asesor.assessment.detail', $item->id_data_sertifikasi_asesi) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-[10px] font-bold transition flex items-center justify-center uppercase tracking-widest">
                                    <i class="fas fa-file-alt mr-1"></i> {{ $buttonLabel ?? 'Detail' }} 
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-search text-4xl mb-3 text-gray-300"></i>
                                    <p>Data asesi tidak ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- PAGINATION FOOTER --}}
            <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
                <div class="text-sm text-gray-500 font-bold">
                    @if ($pendaftar->total() > 0)
                        Menampilkan {{ $pendaftar->firstItem() }} sampai {{ $pendaftar->lastItem() }} dari {{ $pendaftar->total() }} data
                    @else
                        Menampilkan 0 data
                    @endif
                </div>
                <div>
                    {{ $pendaftar->appends(request()->query())->links('components.pagination') }}
                </div>
            </div>
        </div>

        {{-- Action Buttons Bottom --}}
        @if(!$isMasterView)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-t border-gray-100 pt-8">
                {{-- Tombol Daftar Hadir --}}
                <div class="relative group">
                    <button type="button" onclick="toggleDropdown('daftar-hadir-dropdown')"
                        class="w-full bg-white border border-blue-200 text-blue-700 px-4 py-3 rounded-xl shadow-sm hover:bg-blue-50 hover:border-blue-300 transition flex items-center justify-center font-semibold text-sm">
                        <i class="fas fa-clipboard-list mr-2"></i> Daftar Hadir
                        <i class="fas fa-chevron-down ml-auto text-xs opacity-50"></i>
                    </button>
                    <div id="daftar-hadir-dropdown" class="hidden absolute left-0 bottom-full mb-2 w-full bg-white border border-gray-100 rounded-xl shadow-lg z-10 overflow-hidden">
                        <a href="{{ route('admin.schedule.attendance', $jadwal->id_jadwal) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 border-b border-gray-50 transition">Verifikasi Kehadiran</a>
                        <a href="{{ route('admin.attendance.pdf', $jadwal->id_jadwal) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">Unduh PDF</a>
                    </div>
                </div>

                {{-- Tombol Laporan & Tinjauan --}}
                <div class="flex gap-2">
                    <a href="{{ route('asesor.ak05', $jadwal->id_jadwal) }}" class="flex-1 bg-white border border-gray-200 text-gray-700 px-4 py-3 rounded-xl shadow-sm hover:bg-gray-50 transition flex items-center justify-center font-semibold text-sm text-center">
                        Laporan Asesmen
                    </a>
                    <a href="{{ route('asesor.ak06', $jadwal->id_jadwal) }}" class="flex-1 bg-white border border-gray-200 text-gray-700 px-4 py-3 rounded-xl shadow-sm hover:bg-gray-50 transition flex items-center justify-center font-semibold text-sm text-center">
                        Tinjauan Asesmen
                    </a>
                </div>

                {{-- Tombol Berita Acara --}}
                <div class="relative group">
                    <button type="button" 
                        onclick="{{ ($semuaSudahAdaKomentar ?? false) ? "toggleDropdown('berita-acara-dropdown')" : "showWarning()" }}"
                        class="w-full px-4 py-3 rounded-xl shadow-sm flex items-center justify-center font-semibold text-sm transition
                        {{ ($semuaSudahAdaKomentar ?? false) ? 'bg-blue-600 text-white hover:bg-blue-700 shadow-blue-200' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}">
                        <i class="fas fa-file-contract mr-2"></i> Berita Acara
                        @if($semuaSudahAdaKomentar ?? false)
                            <i class="fas fa-chevron-up ml-auto text-xs opacity-50"></i>
                        @endif
                    </button>

                    @if($semuaSudahAdaKomentar ?? false)
                        <div id="berita-acara-dropdown" class="hidden absolute right-0 bottom-full mb-2 w-full bg-white border border-gray-100 rounded-xl shadow-lg z-10 overflow-hidden">
                            <a href="{{ route('asesor.berita_acara', $jadwal->id_jadwal) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 border-b border-gray-50 transition">Verifikasi Berita Acara</a>
                            <a href="{{ route('asesor.berita_acara.pdf', $jadwal->id_jadwal) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">Unduh PDF</a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

      </div>
    </main>
  </div>

  <script>
    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        if(!dropdown) return;
        dropdown.classList.toggle('hidden');
        
        const allDropdowns = ['daftar-hadir-dropdown', 'berita-acara-dropdown'];
        allDropdowns.forEach(d => {
            if (d !== id) {
                const el = document.getElementById(d);
                if(el) el.classList.add('hidden');
            }
        });
    }

    function showWarning() {
        Swal.fire({
            icon: 'warning',
            title: 'Belum Tersedia',
            text: 'Berita acara dapat diakses setelah semua penilaian selesai.',
            confirmButtonColor: '#3b82f6'
        });
    }
  </script>
</body>
</html>