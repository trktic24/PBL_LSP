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

  <style>
    body { font-family: 'Poppins', sans-serif; background-color: #f9fafb; }
    ::-webkit-scrollbar { width: 0; }
    [x-cloak] { display: none !important; }
  </style>
</head>

<body class="text-gray-800">

  {{-- Navbar Admin --}}
  <x-navbar.navbar-admin />
  
  <div class="flex min-h-[calc(100vh-80px)]">
    
    {{-- Sidebar Asesor --}}
    <x-sidebar.sidebar_profile_asesor :asesor="$asesor" />

    <main class="ml-[22%] h-[calc(100vh-80px)] overflow-y-auto p-8 bg-gray-50 flex-1">
      
      <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] p-10 border border-gray-100 min-h-full">
        
        {{-- Header Page --}}
        <div class="relative flex items-center justify-center mb-8">
            <a href="{{ route('admin.asesor_profile_tinjauan', $asesor->id_asesor) }}" class="absolute left-0 top-1 text-gray-500 hover:text-blue-600 transition text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-800">Daftar Asesi</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola penilaian dan verifikasi untuk setiap asesi pada jadwal ini.</p>
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
                            {{-- PERBAIKAN: Menggunakan masterTuk --}}
                            {{ $jadwal->masterTuk->nama_lokasi ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TOOLBAR: SEARCH & PER PAGE --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            
            {{-- Show Entries --}}
            <div x-data="{ perPage: '{{ request('per_page', 10) }}', changePerPage() { let url = new URL(window.location.href); url.searchParams.set('per_page', this.perPage); url.searchParams.set('page', 1); window.location.href = url.href; } }" class="flex items-center space-x-2">
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
                        {{-- Logic Sorting --}}
                        @php
                            $baseParams = request()->except(['sort', 'direction', 'page']);
                            $sortColumn = $sortColumn ?? 'nama_lengkap';
                            $sortDirection = $sortDirection ?? 'asc';
                        @endphp

                        <th class="px-4 py-3 font-semibold w-16 whitespace-nowrap">No</th>
                        
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
                        
                        <th class="px-6 py-3 font-semibold text-center whitespace-nowrap">Progres</th>
                        <th class="px-6 py-3 font-semibold text-center whitespace-nowrap">Pra Asesmen</th>
                        <th class="px-6 py-3 font-semibold text-center whitespace-nowrap">Asesmen</th>
                        <th class="px-6 py-3 font-semibold text-center whitespace-nowrap">Asesmen Mandiri</th>
                        <th class="px-6 py-3 font-semibold text-center whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($pendaftar as $index => $item)
                        <tr class="hover:bg-gray-50 transition divide-x divide-gray-200">
                            {{-- Perhitungan Index Pagination --}}
                            <td class="px-4 py-4 align-top">
                                {{ ($pendaftar->currentPage() - 1) * $pendaftar->perPage() + $loop->iteration }}
                            </td>
                            
                            <td class="px-6 py-4 text-left align-top">
                                <a href="{{ url('/admin/asesi/' . $item->asesi->id_asesi . '/settings') }}" class="group block">
                                    <div class="font-bold text-gray-800 group-hover:text-blue-600 transition">{{ $item->asesi->nama_lengkap ?? 'Nama Tidak Ditemukan' }}</div>
                                    @if($item->asesi->dataPekerjaan)
                                        <div class="text-xs text-gray-400 font-normal mt-0.5 group-hover:text-blue-400 transition">{{ $item->asesi->dataPekerjaan->nama_institusi_pekerjaan }}</div>
                                    @endif
                                </a>
                            </td>

                            <td class="px-6 py-4 text-center align-top">
                                @php
                                    if (is_null($item->rekomendasi_apl02) && is_null($item->rekomendasi_hasil_asesmen_AK02)){
                                        $statusText = 'Belum Direview';
                                        $statusColor = 'bg-red-50 text-red-700 border-red-100';
                                    } elseif (!is_null($item->rekomendasi_apl02) && is_null($item->rekomendasi_hasil_asesmen_AK02)) {
                                        $statusText = 'Dalam Proses';
                                        $statusColor = 'bg-yellow-50 text-yellow-700 border-yellow-100';
                                    } else {
                                        $statusText = 'Sudah Direview';
                                        $statusColor = 'bg-green-50 text-green-700 border-green-100';
                                    }
                                @endphp
                                <a href="{{ route('admin.asesor.tracker.view', ['id_asesor' => $asesor->id_asesor, 'id_data_sertifikasi_asesi' => $item->id_data_sertifikasi_asesi]) }}" 
                                   class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $statusColor }} transition hover:opacity-80">
                                    {{ $statusText }}
                                </a>
                            </td>

                             {{-- Status Pra Asesmen --}}
                            @php
                                if ($item->responApl02Ia01->isEmpty() && $item->responBuktiAk01->isEmpty()) {
                                    $statusPra = 'Belum Direview';
                                    $classPra = 'bg-gray-100 text-gray-500';
                                } elseif ($item->responApl02Ia01->isNotEmpty() && $item->responBuktiAk01->isEmpty()) {
                                    $statusPra = 'Dalam Proses';
                                    $classPra = 'bg-yellow-100 text-yellow-700 border border-yellow-200';
                                } else {
                                    $statusPra = 'Selesai';
                                    $classPra = 'bg-green-100 text-green-700 border border-green-200';
                                }
                            @endphp
                            <td class="px-6 py-4 text-center align-top">
                                {{-- UPDATE: Tambahkan #pra-asesmen --}}
                                <a href="{{ route('admin.asesor.assessment.detail', ['id_asesor' => $asesor->id_asesor, 'id_data_sertifikasi_asesi' => $item->id_data_sertifikasi_asesi]) }}#pra-asesmen" 
                                   class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $classPra }} hover:opacity-80 transition">
                                    {{ $statusPra }}
                                </a>
                            </td>

                            {{-- Status Asesmen --}}
                            @php
                                if (!$item->lembarJawabIa05 && !$item->komentarAk05) {
                                    $statusAsesmen = 'Belum Dimulai';
                                    $classAsesmen = 'bg-gray-100 text-gray-500';
                                } elseif ($item->lembarJawabIa05 && !$item->komentarAk05) {
                                    $statusAsesmen = 'Berlangsung';
                                    $classAsesmen = 'bg-blue-100 text-blue-700 border border-blue-200';
                                } else {
                                    $statusAsesmen = 'Selesai';
                                    $classAsesmen = 'bg-green-100 text-green-700 border border-green-200';
                                }
                            @endphp
                            <td class="px-6 py-4 text-center align-top">
                                {{-- UPDATE: Pastikan ada #asesmen --}}
                                <a href="{{ route('admin.asesor.assessment.detail', ['id_asesor' => $asesor->id_asesor, 'id_data_sertifikasi_asesi' => $item->id_data_sertifikasi_asesi]) }}#asesmen" 
                                   class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $classAsesmen }} hover:opacity-80 transition">
                                    {{ $statusAsesmen }}
                                </a>
                            </td>

                            {{-- Status Mandiri (APL.02) --}}
                            <td class="px-6 py-4 text-center align-top">
                                @if ($item->rekomendasi_apl02 == 'diterima')
                                    <span class="text-green-600 font-bold text-xs"><i class="fas fa-check mr-1"></i> Diterima</span>
                                @elseif ($item->rekomendasi_apl02 == 'tidak diterima')
                                    <span class="text-red-600 font-bold text-xs"><i class="fas fa-times mr-1"></i> Ditolak</span>
                                @else
                                    <a href="{{ route('admin.asesor.assessment.detail', ['id_asesor' => $asesor->id_asesor, 'id_data_sertifikasi_asesi' => $item->id_data_sertifikasi_asesi]) }}" class="text-yellow-600 font-bold text-xs hover:underline"><i class="fas fa-exclamation-circle mr-1"></i> Perlu Verifikasi</a>
                                @endif
                            </td>

                            {{-- Tombol Penyesuaian --}}
                            <td class="px-6 py-4 text-center align-top">
                                <a href="{{ route('admin.asesor.assessment.detail', ['id_asesor' => $asesor->id_asesor, 'id_data_sertifikasi_asesi' => $item->id_data_sertifikasi_asesi]) }}" class="text-blue-600 hover:text-blue-800 text-xs font-bold transition flex items-center justify-center">
                                    <i class="fas fa-cog mr-1"></i> Penyesuaian
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
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
                    onclick="{{ $semuaSudahAdaKomentar ? "toggleDropdown('berita-acara-dropdown')" : "showWarning()" }}"
                    class="w-full px-4 py-3 rounded-xl shadow-sm flex items-center justify-center font-semibold text-sm transition
                    {{ $semuaSudahAdaKomentar ? 'bg-blue-600 text-white hover:bg-blue-700 shadow-blue-200' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}">
                    <i class="fas fa-file-contract mr-2"></i> Berita Acara
                    @if($semuaSudahAdaKomentar)
                        <i class="fas fa-chevron-up ml-auto text-xs opacity-50"></i>
                    @endif
                </button>

                @if($semuaSudahAdaKomentar)
                    <div id="berita-acara-dropdown" class="hidden absolute right-0 bottom-full mb-2 w-full bg-white border border-gray-100 rounded-xl shadow-lg z-10 overflow-hidden">
                        <a href="{{ route('asesor.berita_acara', $jadwal->id_jadwal) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 border-b border-gray-50 transition">Verifikasi Berita Acara</a>
                        <a href="{{ route('asesor.berita_acara.pdf', $jadwal->id_jadwal) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">Unduh PDF</a>
                    </div>
                @endif
            </div>
        </div>

      </div>
    </main>
  </div>

  <script>
    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
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