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
  </style>
</head>

<body class="text-gray-800">

  {{-- PERBAIKAN: Menggunakan Navbar Admin --}}
  <x-navbar.navbar_admin />
  
  <div class="flex min-h-[calc(100vh-80px)]">
    
    {{-- 1. Panggil Component Sidebar Asesor --}}
    <x-sidebar.sidebar_profile_asesor :asesor="$asesor" />

    <main class="ml-[22%] h-[calc(100vh-80px)] overflow-y-auto p-8 bg-gray-50 flex-1">
      
      <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] p-10 border border-gray-100 min-h-full">
        
        {{-- Header Page --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Daftar Asesi</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola penilaian dan verifikasi untuk setiap asesi pada jadwal ini.</p>
            </div>
            
            {{-- PERBAIKAN ROUTE: Menambahkan prefix admin. --}}
            <a href="{{ route('admin.asesor_profile_tinjauan', $asesor->id_asesor) }}" class="flex items-center text-gray-500 hover:text-blue-600 transition text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Tinjauan
            </a>
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
                            {{ $jadwal->tuk->nama_lokasi ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Daftar Asesi --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm mb-8">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-700 text-xs uppercase tracking-wider font-semibold">
                        <th class="py-4 px-6 text-left w-16">No</th>
                        <th class="py-4 px-6 text-left">Nama Asesi</th>
                        <th class="py-4 px-6 text-center">Pra Asesmen</th>
                        <th class="py-4 px-6 text-center">Asesmen</th>
                        <th class="py-4 px-6 text-center">Asesmen Mandiri</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-medium">
                    @forelse($jadwal->dataSertifikasiAsesi as $index => $item)
                        <tr class="border-b border-gray-100 hover:bg-blue-50/30 transition duration-150">
                            <td class="py-4 px-6 text-left">{{ $index + 1 }}</td>
                            <td class="py-4 px-6 text-left">
                                <div class="font-bold text-gray-800">{{ $item->asesi->nama_lengkap ?? 'Nama Tidak Ditemukan' }}</div>
                            </td>

                            {{-- Status Pra Asesmen --}}
                            @php
                                if (!$item->responApl2Ia01 && !$item->responBuktiAk01) {
                                    $statusPra = 'Belum Direview';
                                    $classPra = 'bg-gray-100 text-gray-500';
                                } elseif ($item->responApl2Ia01 && !$item->responBuktiAk01) {
                                    $statusPra = 'Dalam Proses';
                                    $classPra = 'bg-yellow-100 text-yellow-700 border border-yellow-200';
                                } else {
                                    $statusPra = 'Selesai';
                                    $classPra = 'bg-green-100 text-green-700 border border-green-200';
                                }
                            @endphp
                            <td class="py-4 px-6 text-center">
                                {{-- PERBAIKAN ROUTE: Menambahkan prefix admin. --}}
                                <a href="{{ route('admin.asesor.assessment.detail', ['id_asesor' => $asesor->id_asesor, 'id_data_sertifikasi_asesi' => $item->id_data_sertifikasi_asesi]) }}" 
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
                            <td class="py-4 px-6 text-center">
                                {{-- PERBAIKAN ROUTE: Menambahkan prefix admin. --}}
                                <a href="{{ route('admin.asesor.assessment.detail', ['id_asesor' => $asesor->id_asesor, 'id_data_sertifikasi_asesi' => $item->id_data_sertifikasi_asesi]) }}#asesmen" 
                                   class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $classAsesmen }} hover:opacity-80 transition">
                                    {{ $statusAsesmen }}
                                </a>
                            </td>

                            {{-- Status Mandiri (APL.02) --}}
                            <td class="py-4 px-6 text-center">
                                @if ($item->rekomendasi_apl02 == 'diterima')
                                    <span class="text-green-600 font-bold text-xs"><i class="fas fa-check mr-1"></i> Diterima</span>
                                @elseif ($item->rekomendasi_apl02 == 'tidak diterima')
                                    <span class="text-red-600 font-bold text-xs"><i class="fas fa-times mr-1"></i> Ditolak</span>
                                @else
                                    {{-- PERBAIKAN ROUTE: Menambahkan prefix admin. --}}
                                    <a href="{{ route('admin.asesor.assessment.detail', ['id_asesor' => $asesor->id_asesor, 'id_data_sertifikasi_asesi' => $item->id_data_sertifikasi_asesi]) }}" class="text-yellow-600 font-bold text-xs hover:underline"><i class="fas fa-exclamation-circle mr-1"></i> Perlu Verifikasi</a>
                                @endif
                            </td>

                            {{-- Tombol Penyesuaian --}}
                            <td class="py-4 px-6 text-center">
                                <a href="#" class="text-blue-600 hover:text-blue-800 text-xs font-bold transition flex items-center justify-center">
                                    <i class="fas fa-cog mr-1"></i> Penyesuaian
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-users-slash text-4xl mb-3 text-gray-300"></i>
                                    <p>Belum ada asesi terdaftar pada jadwal ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Action Buttons Bottom --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Tombol Daftar Hadir --}}
            <div class="relative group">
                <button type="button" onclick="toggleDropdown('daftar-hadir-dropdown')"
                    class="w-full bg-white border border-blue-200 text-blue-700 px-4 py-3 rounded-xl shadow-sm hover:bg-blue-50 hover:border-blue-300 transition flex items-center justify-center font-semibold text-sm">
                    <i class="fas fa-clipboard-list mr-2"></i> Daftar Hadir
                    <i class="fas fa-chevron-down ml-auto text-xs opacity-50"></i>
                </button>
                <div id="daftar-hadir-dropdown" class="hidden absolute left-0 bottom-full mb-2 w-full bg-white border border-gray-100 rounded-xl shadow-lg z-10 overflow-hidden">
                    <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 border-b border-gray-50 transition">Verifikasi Kehadiran</a>
                    <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">Unduh PDF</a>
                </div>
            </div>

            {{-- Tombol Laporan & Tinjauan --}}
            <div class="flex gap-2">
                <a href="#" class="flex-1 bg-white border border-gray-200 text-gray-700 px-4 py-3 rounded-xl shadow-sm hover:bg-gray-50 transition flex items-center justify-center font-semibold text-sm text-center">
                    Laporan Asesmen
                </a>
                <a href="#" class="flex-1 bg-white border border-gray-200 text-gray-700 px-4 py-3 rounded-xl shadow-sm hover:bg-gray-50 transition flex items-center justify-center font-semibold text-sm text-center">
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
                        <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 border-b border-gray-50 transition">Verifikasi Berita Acara</a>
                        <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">Unduh PDF</a>
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