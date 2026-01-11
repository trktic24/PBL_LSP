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
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; }
    ::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
    [x-cloak] { display: none !important; }
  </style>
</head>

<body class="text-gray-800" x-data="{ 
    showMap: false, 
    mapSrc: '',
    openMap(location, mapLink) {
        this.showMap = true;
        if (mapLink && mapLink.includes('embed')) {
            this.mapSrc = mapLink;
        } else if (mapLink) {
            // Try to extract coordinates or use link directly if frame-able (unlikely for direct Google Maps links)
            // Ideally, use an embed URL. If link_gmap is a direct link, use regex to convert or fallback to search.
            this.mapSrc = 'https://maps.google.com/maps?q=' + encodeURIComponent(location) + '&t=&z=15&ie=UTF8&iwloc=&output=embed';
        } else {
            this.mapSrc = 'https://maps.google.com/maps?q=' + encodeURIComponent(location) + '&t=&z=15&ie=UTF8&iwloc=&output=embed';
        }
    }
}">

  {{-- Navbar Admin --}}
  <x-navbar.navbar-admin />
  
  <div class="flex min-h-[calc(100vh-80px)]">
    
    {{-- Sidebar Asesor --}}
    <x-sidebar.sidebar_profile_asesor :asesor="$asesor" />

    <main class="ml-[22%] h-[calc(100vh-80px)] overflow-y-auto p-8 bg-gray-50 flex-1">
      
      <div class="max-w-7xl mx-auto">
        
        {{-- Header Page --}}
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.asesor_profile_tinjauan', $asesor->id_asesor) }}" 
                   class="flex items-center justify-center w-10 h-10 rounded-full bg-white border border-gray-200 text-gray-500 hover:text-blue-600 hover:border-blue-200 shadow-sm transition-all duration-300">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Daftar Asesi</h1>
                    <p class="text-sm text-gray-500 mt-1">Kelola penilaian & verifikasi jadwal asesmen.</p>
                </div>
            </div>
        </div>

        {{-- Card Informasi Jadwal --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8 hover:shadow-md transition-shadow duration-300 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-full -mr-8 -mt-8 opacity-50 pointer-events-none"></div>
            
            <div class="flex flex-col md:flex-row gap-6 relative z-10">
                {{-- Skema Info --}}
                <div class="flex-1 border-r border-gray-100 pr-6">
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-blue-50 rounded-lg text-blue-600 shrink-0">
                            <i class="fas fa-certificate text-xl"></i>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Skema Sertifikasi</span>
                            <a href="{{ route('admin.skema.detail', $jadwal->skema->id_skema) }}" class="text-lg font-bold text-blue-600 hover:text-blue-800 hover:underline leading-tight mb-1 transition-colors">
                                {{ $jadwal->skema->nama_skema ?? '-' }}
                            </a>
                            <span class="inline-block px-2 py-0.5 mt-1 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                {{ $jadwal->skema->nomor_skema ?? '-' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Jadwal Details --}}
                <div class="flex-1 grid grid-cols-2 gap-4">
                    {{-- Tanggal --}}
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-purple-50 rounded-lg text-purple-600 shrink-0">
                            <i class="far fa-calendar-alt"></i>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Tanggal</span>
                            <a href="{{ route('admin.schedule.attendance', $jadwal->id_jadwal) }}" class="font-medium text-blue-600 hover:text-blue-800 hover:underline transition-colors">
                                {{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->isoFormat('D MMMM Y') }}
                            </a>
                            <div class="text-xs text-gray-500 mt-0.5 flex items-center">
                                <i class="far fa-clock mr-1"></i>
                                {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }} WIB
                            </div>
                        </div>
                    </div>

                    {{-- Waktu --}}
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-orange-50 rounded-lg text-orange-600 shrink-0">
                            <i class="far fa-clock"></i>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Waktu</span>
                            <p class="font-medium text-gray-700">
                                {{ \Carbon\Carbon::parse($jadwal->waktu_mulai ?? '00:00')->format('H:i') }} WIB
                            </p>
                        </div>
                    </div>

                    {{-- Lokasi TUK --}}
                    <div class="col-span-2 flex items-start gap-3 mt-2">
                        <div class="p-2 bg-red-50 rounded-lg text-red-600 shrink-0">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Lokasi TUK</span>
                            {{-- TRIGGER MAP MODAL --}}
                            <button 
                                @click="openMap('{{ $jadwal->masterTuk->nama_lokasi ?? $jadwal->masterTuk->alamat_tuk ?? '' }}', '{{ $jadwal->masterTuk->link_gmap ?? '' }}')"
                                class="font-medium text-blue-600 hover:text-blue-800 hover:underline transition text-left cursor-pointer flex items-center gap-2 group">
                                {{ $jadwal->masterTuk->nama_lokasi ?? '-' }}
                                <i class="fas fa-external-link-alt text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </button>
                            @if($jadwal->masterTuk->alamat_tuk)
                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-1 truncate max-w-md">{{ $jadwal->masterTuk->alamat_tuk }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TOOLBAR: SEARCH & PER PAGE --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
            
            {{-- Show Entries --}}
            <div x-data="{ perPage: '{{ request('per_page', 10) }}', changePerPage() { let url = new URL(window.location.href); url.searchParams.set('per_page', this.perPage); url.searchParams.set('page', 1); window.location.href = url.href; } }" class="flex items-center space-x-3">
                <span class="text-sm text-gray-500 font-medium">Tampilkan</span>
                <select id="per_page" x-model="perPage" @change="changePerPage()" class="bg-gray-50 text-sm border-0 rounded-lg focus:ring-2 focus:ring-blue-500 py-2 pl-3 pr-8 font-semibold text-gray-700 cursor-pointer hover:bg-gray-100 transition">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                <span class="text-sm text-gray-500 font-medium">baris</span>
            </div>

            {{-- Search Form --}}
            <form action="{{ url()->current() }}" method="GET" class="w-full md:w-auto" x-data="{ search: '{{ request('search', '') }}' }">
                <!-- Keep existing params -->
                @foreach(request()->except(['search', 'page']) as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                
                <div class="relative w-full md:w-80 group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                    </div>
                    <input type="text" name="search" x-model="search" placeholder="Cari nama asesi..." 
                           class="w-full pl-10 pr-10 py-2.5 text-sm bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white text-gray-700 transition-all placeholder-gray-400 shadow-inner" />
                    <button type="button" class="absolute right-3 top-0 h-full text-gray-400 hover:text-red-500 transition" x-show="search.length > 0" @click="search = ''; $nextTick(() => $el.form.submit())" x-cloak>
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>
            </form>
        </div>

        {{-- Tabel Daftar Asesi --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs font-semibold tracking-wider">
                        <tr class="border-b border-gray-200">
                            {{-- Logic Sorting --}}
                            @php
                                $baseParams = request()->except(['sort', 'direction', 'page']);
                                $sortColumn = $sortColumn ?? 'nama_lengkap';
                                $sortDirection = $sortDirection ?? 'asc';
                            @endphp

                            <th class="px-6 py-4 w-16 text-center">No</th>
                            
                            {{-- Kolom Nama dengan Sorting --}}
                            <th class="px-6 py-4 text-left">
                                @php $isCurrent = $sortColumn == 'nama_lengkap'; @endphp
                                <a href="{{ request()->fullUrlWithQuery(array_merge($baseParams, ['sort' => 'nama_lengkap', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" 
                                   class="inline-flex items-center group cursor-pointer hover:text-blue-600 transition gap-2">
                                    <span>Nama Asesi</span>
                                    <div class="flex flex-col -space-y-1 text-[8px] opacity-70">
                                        <i class="fas fa-chevron-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-blue-600' : 'text-gray-300 group-hover:text-blue-400' }}"></i>
                                        <i class="fas fa-chevron-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-blue-600' : 'text-gray-300 group-hover:text-blue-400' }}"></i>
                                    </div>
                                </a>
                            </th>
                            
                            <th class="px-6 py-4 text-center">Tracker</th>
                            <th class="px-6 py-4 text-center">Asesmen Mandiri</th>
                            <th class="px-6 py-4 text-center">Penyesuaian</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pendaftar as $index => $item)
                            <tr class="hover:bg-blue-50/50 transition duration-150 group">
                                {{-- Perhitungan Index Pagination --}}
                                <td class="px-6 py-4 text-center text-gray-500 font-medium">
                                    {{ ($pendaftar->currentPage() - 1) * $pendaftar->perPage() + $loop->iteration }}
                                </td>
                                
                                <td class="px-6 py-4 align-middle">
                                    <a href="{{ url('/admin/asesi/' . $item->asesi->id_asesi . '/settings') }}" class="flex flex-col">
                                        <span class="font-bold text-gray-800 text-sm group-hover:text-blue-600 transition line-clamp-1">{{ $item->asesi->nama_lengkap ?? 'Nama Tidak Ditemukan' }}</span>
                                        @if($item->asesi->dataPekerjaan)
                                            <span class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                                                <i class="far fa-building text-[10px]"></i>
                                                {{ $item->asesi->dataPekerjaan->nama_institusi_pekerjaan }}
                                            </span>
                                        @endif
                                    </a>
                                </td>

                                {{-- Tracker Logic (Mirrored from Asesor) --}}
                                <td class="px-6 py-4 text-center align-middle">
                                    @php
                                        if (is_null($item->rekomendasi_apl02) && is_null($item->rekomendasi_hasil_asesmen_AK02)){
                                            $statusText = 'Belum Direview';
                                            $statusClass = 'bg-red-100 text-red-700 ring-1 ring-red-600/10';
                                        } elseif (!is_null($item->rekomendasi_apl02) && is_null($item->rekomendasi_hasil_asesmen_AK02)) {
                                            $statusText = 'Dalam Proses';
                                            $statusClass = 'bg-blue-100 text-blue-700 ring-1 ring-blue-600/10'; // Changed to Blue for Admin convention or stick to Yellow
                                            // Asesor uses Yellow, let's stick to Yellow for consistency
                                            $statusClass = 'bg-yellow-100 text-yellow-700 ring-1 ring-yellow-600/10';
                                        } else {
                                            $statusText = 'Sudah Direview';
                                            $statusClass = 'bg-green-100 text-green-700 ring-1 ring-green-600/10';
                                        }
                                    @endphp
                                    <a href="{{ route('admin.asesi.profile.tracker', ['id_asesi' => $item->asesi->id_asesi, 'sertifikasi_id' => $item->id_data_sertifikasi_asesi]) }}" 
                                       class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClass }} transition hover:opacity-80">
                                        {{ $statusText }}
                                    </a>
                                </td>

                                {{-- Asesmen Mandiri (APL.02) Logic --}}
                                <td class="px-6 py-4 text-center align-middle">
                                    @if ($item->rekomendasi_apl02 == 'diterima')
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-50 text-green-700 border border-green-100 shadow-sm">
                                            <i class="fas fa-check-circle text-xs"></i>
                                            <span class="font-semibold text-xs">Diterima</span>
                                        </div>
                                    @elseif ($item->rekomendasi_apl02 == 'tidak diterima')
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-50 text-red-700 border border-red-100 shadow-sm">
                                            <i class="fas fa-times-circle text-xs"></i>
                                            <span class="font-semibold text-xs">Ditolak</span>
                                        </div>
                                    @else
                                        {{-- Use valid admin route for APL.02 (Linked to Asesor Logic) --}}
                                        <a href="{{ route('apl02.view', $item->id_data_sertifikasi_asesi) }}" 
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-yellow-400 text-white hover:bg-yellow-500 shadow-sm hover:shadow transition-all font-medium text-xs">
                                            <i class="fas fa-clipboard-check"></i>
                                            Verifikasi
                                        </a>
                                    @endif
                                </td>

                                {{-- Penyesuaian (AK.07) Logic --}}
                                <td class="px-6 py-4 text-center align-middle">
                                    @if($item->hasilPenyesuaianAK07)
                                        <a href="{{ route('fr-ak-07.create', $item->id_data_sertifikasi_asesi) }}" 
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-200 transition-all font-medium text-xs">
                                            <i class="fas fa-file-alt"></i> Lihat
                                        </a>
                                    @else
                                        <a href="{{ route('fr-ak-07.create', $item->id_data_sertifikasi_asesi) }}" 
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 border border-blue-200 transition-all font-medium text-xs">
                                            <i class="fas fa-plus"></i> Buat
                                        </a>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 text-center align-middle">
                                    <a href="{{ route('admin.asesor.assessment.detail', ['id_asesor' => $asesor->id_asesor, 'id_data_sertifikasi_asesi' => $item->id_data_sertifikasi_asesi]) }}" 
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 border border-transparent hover:border-blue-100 transition-all"
                                       title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-search text-2xl text-gray-300"></i>
                                        </div>
                                        <h3 class="text-gray-800 font-semibold text-lg mb-1">Data tidak ditemukan</h3>
                                        <p class="text-gray-400 text-sm max-w-xs mx-auto">Coba ubah kata kunci pencarian atau filter yang Anda gunakan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION FOOTER --}}
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-500">
                    @if ($pendaftar->total() > 0)
                        Menampilkan <span class="font-semibold text-gray-900">{{ $pendaftar->firstItem() }}</span> sampai <span class="font-semibold text-gray-900">{{ $pendaftar->lastItem() }}</span> dari <span class="font-semibold text-gray-900">{{ $pendaftar->total() }}</span> data
                    @else
                        Menampilkan 0 data
                    @endif
                </div>
                <div class="scale-90 origin-right">
                    {{ $pendaftar->appends(request()->query())->links('components.pagination') }}
                </div>
            </div>
        </div>

        {{-- Action Buttons Bottom --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pb-8">
            {{-- Tombol Daftar Hadir --}}
            <div class="relative group" x-data="{ open: false }">
                <button type="button" @click="open = !open" @click.away="open = false"
                    class="w-full bg-white border border-gray-200 text-gray-700 px-4 py-3.5 rounded-xl shadow-sm hover:border-blue-300 hover:text-blue-600 hover:shadow-md transition-all flex items-center justify-between font-semibold text-sm group-hover:bg-blue-50/30">
                    <span class="flex items-center"><i class="fas fa-clipboard-list mr-2 text-gray-400 group-hover:text-blue-500"></i> Daftar Hadir</span>
                    <i class="fas fa-chevron-down text-xs opacity-50 transition-transform duration-200" :class="{'rotate-180': open}"></i>
                </button>
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-2"
                     class="absolute bottom-full mb-2 w-full bg-white border border-gray-100 rounded-xl shadow-xl z-20 overflow-hidden" x-cloak>
                    <a href="{{ route('admin.schedule.attendance', $jadwal->id_jadwal) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 border-b border-gray-50 transition flex items-center">
                        <i class="fas fa-check-double w-6"></i> Verifikasi Kehadiran
                    </a>
                    <a href="{{ route('admin.attendance.pdf', $jadwal->id_jadwal) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition flex items-center">
                        <i class="fas fa-file-pdf w-6"></i> Unduh PDF
                    </a>
                </div>
            </div>

            {{-- Tombol Laporan & Tinjauan --}}
            <div class="flex gap-3">
                <a href="{{ route('asesor.ak05', $jadwal->id_jadwal) }}" class="flex-1 bg-white border border-gray-200 text-gray-700 px-4 py-3.5 rounded-xl shadow-sm hover:border-blue-300 hover:text-blue-600 hover:shadow-md transition-all flex items-center justify-center font-semibold text-sm group text-center">
                    <i class="fas fa-file-signature text-gray-400 mr-2 group-hover:text-blue-500"></i> Laporan
                </a>
                <a href="{{ route('asesor.ak06', $jadwal->id_jadwal) }}" class="flex-1 bg-white border border-gray-200 text-gray-700 px-4 py-3.5 rounded-xl shadow-sm hover:border-blue-300 hover:text-blue-600 hover:shadow-md transition-all flex items-center justify-center font-semibold text-sm group text-center">
                    <i class="fas fa-search-plus text-gray-400 mr-2 group-hover:text-blue-500"></i> Tinjauan
                </a>
            </div>

            {{-- Tombol Berita Acara --}}
            <div class="relative group" x-data="{ open: false }">
                <button type="button" 
                    @if($sudahVerifikasiValidator) @click="open = !open" @click.away="open = false" @else onclick="showWarning()" @endif
                    class="w-full px-4 py-3.5 rounded-xl shadow-sm flex items-center justify-between font-semibold text-sm transition-all
                    {{ $sudahVerifikasiValidator 
                        ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white hover:shadow-lg hover:shadow-blue-500/30 border border-transparent' 
                        : 'bg-gray-100 text-gray-400 cursor-not-allowed border border-gray-200' }}">
                    <span class="flex items-center"><i class="fas fa-file-contract mr-2"></i> Berita Acara</span>
                    @if($sudahVerifikasiValidator)
                        <i class="fas fa-chevron-up text-xs opacity-70 transition-transform duration-200" :class="{'rotate-180': !open}"></i>
                    @endif
                </button>

                @if($sudahVerifikasiValidator)
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         class="absolute bottom-full mb-2 w-full bg-white border border-gray-100 rounded-xl shadow-xl z-20 overflow-hidden" x-cloak>
                        <a href="{{ route('asesor.berita_acara', $jadwal->id_jadwal) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 border-b border-gray-50 transition flex items-center">
                            <i class="fas fa-check-circle w-6 text-green-500"></i> Verifikasi Berita Acara
                        </a>
                        <a href="{{ route('asesor.berita_acara.pdf', $jadwal->id_jadwal) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition flex items-center">
                            <i class="fas fa-file-pdf w-6 text-red-500"></i> Unduh PDF
                        </a>
                    </div>
                @endif
            </div>
        </div>

      </div>
    </main>

    {{-- Map Modal --}}
    <div x-show="showMap" class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            
            <div x-show="showMap" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                 @click="showMap = false" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showMap" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                
                <div class="bg-white p-6 relative">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Lokasi TUK</h3>
                        <button @click="showMap = false" type="button" class="bg-white rounded-full p-2 hover:bg-gray-100 text-gray-400 hover:text-gray-500 focus:outline-none transition">
                            <span class="sr-only">Close</span>
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                    {{-- Iframe Container --}}
                    <div class="w-full h-[500px] bg-gray-100 rounded-xl overflow-hidden relative">
                         <div class="absolute inset-0 flex items-center justify-center text-gray-400 z-0">
                            <i class="fas fa-spinner fa-spin text-2xl"></i>
                         </div>
                         <iframe 
                            :src="mapSrc" 
                            class="absolute inset-0 w-full h-full z-10" 
                            frameborder="0" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <div class="mt-4 text-sm text-gray-500 text-center">
                        <p class="mb-1">{{ $jadwal->masterTuk->nama_lokasi ?? 'Lokasi' }}</p>
                        <p>{{ $jadwal->masterTuk->alamat_tuk ?? '' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>

  <script>
    function showWarning() {
        Swal.fire({
            icon: 'warning',
            title: 'Belum Tersedia',
            text: 'Berita acara dapat diakses setelah semua penilaian selesai dan diverifikasi oleh validator.',
            confirmButtonColor: '#3b82f6',
            confirmButtonText: 'Mengerti',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl px-6 py-2.5 font-semibold'
            }
        });
    }
  </script>
</body>
</html>