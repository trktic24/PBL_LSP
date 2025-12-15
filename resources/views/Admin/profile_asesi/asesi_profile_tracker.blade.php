@use('App\Models\DataSertifikasiAsesi')
@use('Carbon\Carbon')

@php
    // Ambil data sertifikasi yang paling utama/terbaru (sesuai logic Controller Admin)
    $sertifikasi = $asesi->dataSertifikasi->first();

    // --- DEFINISI LEVEL (SAMA DENGAN ASESI) ---
    $LVL_DAFTAR_SELESAI = 10;
    $LVL_TUNGGU_BAYAR = 20;
    $LVL_LUNAS = 30;
    $LVL_PRA_ASESMEN = 40;
    $LVL_SETUJU = 50;
    $LVL_ASESMEN = 70;
    $LVL_UMPAN_BALIK = 80;
    $LVL_BANDING = 90;
    $LVL_REKOMENDASI = 100;
    
    $level = $sertifikasi ? $sertifikasi->progres_level : 0;
    
    // --- MAPPING STATUS DARI DATA SERTIFIKASI ---
    if ($sertifikasi) {
        $isApl02Selesai = $sertifikasi->rekomendasi_apl02 == 'diterima' || $sertifikasi->rekomendasi_apl02 == 'ditolak';
        $unlockAPL02 = $sertifikasi->rekomendasi_apl01 == 'diterima';

        $unlockAK01 = $isApl02Selesai;
        $isSetujuSelesai = !is_null($sertifikasi->tgl_ttd_ak01); 

        $jadwal = $sertifikasi->jadwal;
        $unlockAsesmen = false; 
        
        // Logika Waktu Asesmen (untuk menentukan apakah sudah lewat atau belum)
        if ($jadwal && $jadwal->tanggal_pelaksanaan && $jadwal->waktu_mulai) {
            try {
                 // Gunakan accessor Carbon::parse dari model Jadwal
                 $tglPelaksanaan = $jadwal->tanggal_pelaksanaan->format('Y-m-d');
                 $jamMulai = $jadwal->waktu_mulai->format('H:i:s');
                 $waktuMulai = Carbon::parse($tglPelaksanaan . ' ' . $jamMulai);
                 
                 // Asesmen terbuka jika sudah lewat waktu mulai dan sudah diverifikasi APL-02
                 if (Carbon::now()->greaterThanOrEqualTo($waktuMulai) && $isApl02Selesai) {
                     $unlockAsesmen = true;
                 }
            } catch (\Exception $e) {
                // Biarkan unlockAsesmen false jika parsing gagal
            }
        }
        
        $unlockAK03 = !is_null($sertifikasi->rekomendasi_hasil_asesmen_AK02); 
        $unlockAK04 = $unlockAK03; 

    } else {
        $jadwal = null;
    }


    // --- Helper Render Checkmark (Untuk Desktop) ---
    if (!function_exists('renderCheckmark')) {
        function renderCheckmark()
        {
            return '<div class="absolute -top-1 -left-1.5 z-10 bg-green-500 rounded-full p-0.5 border-2 border-white">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="white" class="w-3 h-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>';
        }
    }
    
    // --- CSS VARIABLES ---
    // Di Admin, link yang sudah selesai tetap terlihat sebagai judul (tanpa hover link)
    $titleClassEnabled = 'text-lg font-semibold text-gray-900';
    $titleClassDisabled = 'text-lg font-semibold text-gray-400';

    // CONTAINER KARTU
    $responsiveCardClass = 'flex-1 ml-3 md:ml-0 bg-white p-5 rounded-2xl shadow-[0_4px_20px_-5px_rgba(0,0,0,0.1)] border border-gray-100 md:bg-transparent md:p-0 md:rounded-none md:shadow-none md:border-0 relative z-10';

    // STATUS COLORS
    $statusClassSelesai = 'text-xs text-green-600 font-medium';
    $statusClassProses = 'text-xs text-blue-600 font-medium';
    $statusClassTunggu = 'text-xs text-yellow-600 font-medium';
    $statusClassTerkunci = 'text-xs text-gray-400 font-medium';

    // BUTTON STYLES (Di Admin, tombol ini jadi tombol aksi admin)
    $btnBase = 'mt-2 px-4 py-1.5 text-xs font-semibold rounded-md inline-flex items-center transition-all';
    $btnBlue = "$btnBase bg-blue-500 text-white hover:bg-blue-600 shadow-blue-100 hover:shadow-lg";
    $btnGreen = "$btnBase bg-green-500 text-white hover:bg-green-600 shadow-green-100 hover:shadow-lg";
    $btnYellow = "$btnBase bg-yellow-500 text-white hover:bg-yellow-600 shadow-yellow-100 hover:shadow-lg";
    $btnGray = "$btnBase bg-gray-300 text-gray-500 cursor-not-allowed";
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tracker Sertifikasi Asesi | LSP Polines</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <style>
    body { font-family: 'Poppins', sans-serif; }
    ::-webkit-scrollbar { width: 0; }
  </style>
</head>

<body class="bg-gray-50 text-gray-800 text-sm">

    <x-navbar.navbar_admin/>
    
    <main class="flex min-h-[calc(100vh-80px)]">
        
        @php
            $firstSertifikasi = $asesi->dataSertifikasi->first();
            $defaultBackUrl = route('admin.master_asesi'); 
            $backUrl = $firstSertifikasi ? route('admin.schedule.attendance', $firstSertifikasi->id_jadwal) : $defaultBackUrl;
        @endphp

        <x-sidebar.sidebar_profile_asesi 
            :asesi="$asesi" 
            :backUrl="$backUrl" 
        />

        {{-- 3. KONTEN UTAMA --}}
        <section class="ml-[22%] flex-1 p-8 h-[calc(100vh-80px)] overflow-y-auto bg-gray-50">
            
            {{-- 4. CARD PUTIH UTAMA --}}
            <div class="bg-white p-10 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] border border-gray-100 min-h-[500px]">
                
                {{-- JUDUL --}}
                <h1 class="text-3xl font-bold text-gray-800 mb-10 text-center">Tracker Status Sertifikasi</h1>

                <div class="max-w-3xl mx-auto">
                    
                    @if (!$sertifikasi)
                        <div class="p-6 text-center text-gray-500 italic border-2 border-dashed border-gray-300 rounded-xl mt-10">
                            Asesi ini belum terdaftar dalam jadwal sertifikasi manapun.
                        </div>
                    @else

                        @if($sertifikasi->rekomendasi_hasil_asesmen_AK02 == 'belum_kompeten')
                        <div class="p-4 mb-8 bg-red-100 text-red-700 rounded-xl border border-red-200 text-center font-medium">
                            <i class="fas fa-exclamation-triangle mr-2"></i> PERHATIAN: Asesi ini dinyatakan **BELUM KOMPETEN**. Proses sertifikasi terhenti.
                        </div>
                        @endif

                        <ol class="relative z-10 space-y-6 md:space-y-0">
                            
                            {{-- ============================================= --}}
                            {{-- ITEM 1: Formulir APL-01 --}}
                            {{-- ============================================= --}}
                            <li class="relative flex items-center md:items-start md:pb-10">
                                <div class="absolute left-5 top-0 -bottom-8 w-1 md:left-6 md:top-6 md:-bottom-10 md:w-0.5 
                                    {{ $level >= $LVL_DAFTAR_SELESAI ? 'bg-green-500' : 'bg-gray-200' }}">
                                </div>

                                <div class="relative flex-shrink-0 ml-1 mr-4 md:mr-6 z-10">
                                    <div class="hidden md:flex w-12 h-12 rounded-lg bg-gray-100 items-center justify-center">
                                        <i class="fas fa-file-alt w-6 h-6 text-gray-500"></i>
                                    </div>
                                    <div class="md:hidden flex items-center justify-center w-9 h-9 bg-white rounded-full border-4 border-gray-100 shadow-sm z-20 relative">
                                        @if ($level >= $LVL_DAFTAR_SELESAI)
                                            <div class="w-4 h-4 left-1 bg-green-500 rounded-full shadow-[0_0_10px_rgba(34,197,94,0.6)]"></div>
                                        @else
                                            <div class="w-4 h-4 bg-gray-300 rounded-full"></div>
                                        @endif
                                    </div>
                                    @if ($level >= $LVL_DAFTAR_SELESAI)
                                        <div class="hidden md:block">{!! renderCheckmark() !!}</div>
                                    @endif
                                </div>

                                <div class="{{ $responsiveCardClass }}">
                                    
                                    <h3 class="{{ $titleClassEnabled }}">
                                        Formulir Pendaftaran Sertifikasi (APL-01)
                                    </h3>

                                    <p class="text-sm text-gray-500">
                                        {{ $sertifikasi->tanggal_daftar ? $sertifikasi->tanggal_daftar->format('l, d F Y') : 'Belum Terdaftar' }}
                                    </p>

                                    @if ($level >= $LVL_DAFTAR_SELESAI)
                                        <p class="{{ $statusClassSelesai }}">Selesai Diisi Asesi</p>
                                        <a href="{{ route('admin.asesi.form', ['id_asesi' => $asesi->id_asesi]) }}" 
                                            class="{{ $btnBlue }} mr-2">
                                            <i class="fas fa-eye mr-1"></i> Lihat Data Asesi
                                        </a>
                                        <a href="{{ route('asesi.cetak.apl01', ['id_data_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                                            target="_blank" class="{{ $btnGreen }}">
                                            <i class="fas fa-download mr-1"></i> Unduh APL-01 Asesi
                                        </a>
                                    @else
                                        <p class="{{ $statusClassTunggu }}">Menunggu Asesi Selesai Mengisi</p>
                                        <button disabled class="{{ $btnGray }}">Unduh APL-01</button>
                                    @endif
                                </div>
                            </li>

                            {{-- ============================================= --}}
                            {{-- ITEM 2: Pembayaran --}}
                            {{-- ============================================= --}}
                            <li class="relative flex items-center md:items-start md:pb-10">
                                <div class="absolute left-5 top-0 -bottom-8 w-1 md:left-6 md:top-6 md:-bottom-10 md:w-0.5 
                                    {{ $level >= $LVL_LUNAS ? 'bg-green-500' : 'bg-gray-200' }}">
                                </div>

                                <div class="relative flex-shrink-0 ml-1 mr-4 md:mr-6 z-10">
                                    <div class="hidden md:flex w-12 h-12 rounded-lg bg-gray-100 items-center justify-center">
                                        <i class="fas fa-credit-card w-6 h-6 text-gray-500"></i>
                                    </div>
                                    <div class="md:hidden flex items-center justify-center w-9 h-9 bg-white rounded-full border-4 border-gray-100 shadow-sm z-20 relative">
                                        @if ($level >= $LVL_LUNAS)
                                            <div class="w-4 h-4 bg-green-500 rounded-full shadow-[0_0_10px_rgba(34,197,94,0.6)]"></div>
                                        @else
                                            <div class="w-4 h-4 bg-gray-300 rounded-full"></div>
                                        @endif
                                    </div>
                                    @if ($level >= $LVL_LUNAS)
                                        <div class="hidden md:block">{!! renderCheckmark() !!}</div>
                                    @endif
                                </div>

                                <div class="{{ $responsiveCardClass }}">
                                    <h3 class="{{ $titleClassEnabled }}">Pembayaran</h3>

                                    @if ($level >= $LVL_LUNAS)
                                        <p class="{{ $statusClassSelesai }}">Lunas</p>
                                        <a href="#" class="{{ $btnGreen }} inline-flex items-center justify-center text-center">
                                            <i class="fas fa-file-invoice mr-1"></i> Lihat Bukti Bayar
                                        </a>
                                    @elseif ($level == $LVL_TUNGGU_BAYAR)
                                        <p class="{{ $statusClassTunggu }}">Menunggu Verifikasi Admin</p>
                                        <a href="#" class="{{ $btnYellow }} inline-flex items-center justify-center text-center">
                                            <i class="fas fa-search-plus mr-1"></i> Verifikasi
                                        </a>
                                    @elseif ($level == $LVL_DAFTAR_SELESAI)
                                        <p class="{{ $statusClassTunggu }}">Menunggu Pembayaran Asesi</p>
                                        <button disabled class="{{ $btnGray }}">Menunggu Pembayaran</button>
                                    @else
                                        <p class="{{ $statusClassTerkunci }}">Terkunci</p>
                                    @endif
                                </div>
                            </li>

                            {{-- ============================================= --}}
                            {{-- ITEM 3: Pra-Asesmen APL-02 --}}
                            {{-- ============================================= --}}
                            <li class="relative flex items-center md:items-start md:pb-10">
                                <div class="absolute left-5 top-0 -bottom-8 w-1 md:left-6 md:top-6 md:-bottom-10 md:w-0.5 
                                    {{ $level >= $LVL_PRA_ASESMEN ? 'bg-green-500' : 'bg-gray-200' }}">
                                </div>

                                <div class="relative flex-shrink-0 ml-1 mr-4 md:mr-6 z-10">
                                    <div class="hidden md:flex w-12 h-12 rounded-lg bg-gray-100 items-center justify-center">
                                        <i class="fas fa-clipboard-check w-6 h-6 text-gray-500"></i>
                                    </div>
                                    <div class="md:hidden flex items-center justify-center w-9 h-9 bg-white rounded-full border-4 border-gray-100 shadow-sm z-20 relative">
                                        @if ($level >= $LVL_PRA_ASESMEN)
                                            <div class="w-4 h-4 bg-green-500 rounded-full shadow-[0_0_10px_rgba(34,197,94,0.6)]"></div>
                                        @else
                                            <div class="w-4 h-4 bg-gray-300 rounded-full"></div>
                                        @endif
                                    </div>
                                    @if ($level >= $LVL_PRA_ASESMEN)
                                        <div class="hidden md:block">{!! renderCheckmark() !!}</div>
                                    @endif
                                </div>

                                <div class="{{ $responsiveCardClass }}">

                                    <h3 class="{{ $titleClassEnabled }}">Pra-Asesmen (APL-02)</h3>

                                    @if ($level >= $LVL_PRA_ASESMEN)
                                        <p class="{{ $statusClassSelesai }}">Selesai Diverifikasi</p>
                                        <a href="{{ route('admin.verifikasi.apl02', ['id_sertifikasi_asesi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                                            class="{{ $btnBlue }} mr-2">
                                            <i class="fas fa-eye mr-1"></i> Lihat Verifikasi
                                        </a>
                                        <a href="{{ route('asesi.cetak.apl02', $sertifikasi->id_data_sertifikasi_asesi) }}"
                                            class="{{ $btnGreen }}" target="_blank">
                                            <i class="fas fa-download mr-1"></i> Unduh APL-02
                                        </a>
                                    @elseif ($level == $LVL_LUNAS)
                                        @if ($sertifikasi->rekomendasi_apl02 == 'menunggu')
                                            <p class="{{ $statusClassTunggu }}">Menunggu Asesi Mengisi</p>
                                            <button disabled class="{{ $btnGray }}">Menunggu Asesi</button>
                                        @else
                                            <p class="{{ $statusClassProses }}">Tunggu Anda Verifikasi</p>
                                            <a href="{{ route('admin.verifikasi.apl02', ['id_sertifikasi_asesi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                                                class="{{ $btnBlue }} inline-flex items-center">
                                                <i class="fas fa-tasks mr-1"></i> Mulai Verifikasi
                                            </a>
                                        @endif
                                    @else
                                        <p class="{{ $statusClassTerkunci }}">Terkunci (Bayar belum lunas)</p>
                                    @endif
                                </div>
                            </li>

                            {{-- ============================================= --}}
                            {{-- ITEM 4: Jadwal TUK --}}
                            {{-- ============================================= --}}
                            <li class="relative flex items-center md:items-start md:pb-10">
                                <div class="absolute left-5 top-0 -bottom-8 w-1 md:left-6 md:top-6 md:-bottom-10 md:w-0.5 
                                    {{ $level >= $LVL_SETUJU ? 'bg-green-500' : 'bg-gray-200' }}">
                                </div>

                                <div class="relative flex-shrink-0 ml-1 mr-4 md:mr-6 z-10">
                                    <div class="hidden md:flex w-12 h-12 rounded-lg bg-gray-100 items-center justify-center">
                                        <i class="fas fa-calendar-alt w-6 h-6 text-gray-500"></i>
                                    </div>
                                    <div class="md:hidden flex items-center justify-center w-9 h-9 bg-white rounded-full border-4 border-gray-100 shadow-sm z-20 relative">
                                        @if ($level >= $LVL_SETUJU)
                                            <div class="w-4 h-4 bg-green-500 rounded-full shadow-[0_0_10px_rgba(34,197,94,0.6)]"></div>
                                        @else
                                            <div class="w-4 h-4 bg-gray-300 rounded-full"></div>
                                        @endif
                                    </div>
                                    @if ($level >= $LVL_SETUJU)
                                        <div class="hidden md:block">{!! renderCheckmark() !!}</div>
                                    @endif
                                </div>

                                <div class="{{ $responsiveCardClass }}">
                                    @if ($jadwal)
                                        <h3 class="{{ $titleClassEnabled }}">
                                            Jadwal: {{ $jadwal->skema->nama_skema ?? '-' }}
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            TUK: {{ $jadwal->masterTuk->nama_lokasi ?? '-' }} | Tgl: {{ $jadwal->tanggal_pelaksanaan->format('d M Y') }}
                                        </p>
                                    @else
                                        <h3 class="{{ $titleClassDisabled }}">Jadwal & TUK Belum Ditetapkan</h3>
                                    @endif
                                    
                                    @if ($jadwal)
                                        <p class="{{ $statusClassSelesai }}">Jadwal Sudah Ada</p>
                                        <a href="{{ route('admin.edit_schedule', $sertifikasi->id_jadwal) }}" class="{{ $btnBlue }} inline-flex items-center">
                                            <i class="fas fa-edit mr-1"></i> Lihat/Edit Jadwal
                                        </a>
                                    @elseif ($level >= $LVL_PRA_ASESMEN)
                                        <p class="{{ $statusClassProses }}">Menunggu Penentuan Jadwal</p>
                                        <a href="{{ route('admin.add_schedule') }}" class="{{ $btnYellow }} inline-flex items-center">
                                            <i class="fas fa-plus mr-1"></i> Buat Jadwal Baru
                                        </a>
                                    @else
                                        <p class="{{ $statusClassTerkunci }}">Menunggu Pra-Asesmen Selesai</p>
                                    @endif
                                </div>
                            </li>

                            {{-- ============================================= --}}
                            {{-- ITEM 5: Persetujuan (FR.AK.01) --}}
                            {{-- ============================================= --}}
                            <li class="relative flex items-center md:items-start md:pb-10">
                                <div class="absolute left-5 top-0 -bottom-8 w-1 md:left-6 md:top-6 md:-bottom-10 md:w-0.5 
                                    {{ $level >= $LVL_SETUJU ? 'bg-green-500' : 'bg-gray-200' }}">
                                </div>

                                <div class="relative flex-shrink-0 ml-1 mr-4 md:mr-6 z-10">
                                    <div class="hidden md:flex w-12 h-12 rounded-lg bg-gray-100 items-center justify-center">
                                        <i class="fas fa-handshake w-6 h-6 text-gray-500"></i>
                                    </div>
                                    <div class="md:hidden flex items-center justify-center w-9 h-9 bg-white rounded-full border-4 border-gray-100 shadow-sm z-20 relative">
                                        @if ($level >= $LVL_SETUJU)
                                            <div class="w-4 h-4 bg-green-500 rounded-full shadow-[0_0_10px_rgba(34,197,94,0.6)]"></div>
                                        @else
                                            <div class="w-4 h-4 bg-gray-300 rounded-full"></div>
                                        @endif
                                    </div>
                                    @if ($level >= $LVL_SETUJU)
                                        <div class="hidden md:block">{!! renderCheckmark() !!}</div>
                                    @endif
                                </div>

                                <div class="{{ $responsiveCardClass }}">
                                    <h3 class="{{ $titleClassEnabled }}">
                                        Persetujuan Asesmen (FR.AK.01)
                                    </h3>

                                    @if ($level >= $LVL_SETUJU)
                                        <p class="{{ $statusClassSelesai }}">Telah Disetujui Asesi</p>
                                        <a href="{{ route('asesi.cetak.ak01', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                                            class="{{ $btnGreen }}" target="_blank">
                                            <i class="fas fa-download mr-1"></i> Unduh Dokumen
                                        </a>
                                    @elseif ($level >= $LVL_PRA_ASESMEN)
                                        <p class="{{ $statusClassProses }}">Menunggu Persetujuan Asesi</p>
                                    @else
                                        <p class="{{ $statusClassTerkunci }}">Terkunci</p>
                                    @endif
                                </div>
                            </li>

                            {{-- ============================================= --}}
                            {{-- ITEM 6: Asesmen (Pelaksanaan Uji) --}}
                            {{-- ============================================= --}}
                            <li class="relative flex items-center md:items-start md:pb-10">
                                <div class="absolute left-5 top-0 -bottom-8 w-1 md:left-6 md:top-6 md:-bottom-10 md:w-0.5 
                                    {{ $level >= $LVL_ASESMEN ? 'bg-green-500' : 'bg-gray-200' }}">
                                </div>

                                <div class="relative flex-shrink-0 ml-1 mr-4 md:mr-6 z-10">
                                    <div class="hidden md:flex w-12 h-12 rounded-lg bg-gray-100 items-center justify-center">
                                        <i class="fas fa-pen-square w-6 h-6 text-gray-500"></i>
                                    </div>
                                    <div class="md:hidden flex items-center justify-center w-9 h-9 bg-white rounded-full border-4 border-gray-100 shadow-sm z-20 relative">
                                        @if ($level >= $LVL_ASESMEN)
                                            <div class="w-4 h-4 bg-green-500 rounded-full shadow-[0_0_10px_rgba(34,197,94,0.6)]"></div>
                                        @else
                                            <div class="w-4 h-4 bg-gray-300 rounded-full"></div>
                                        @endif
                                    </div>
                                    @if ($level >= $LVL_ASESMEN)
                                        <div class="hidden md:block">{!! renderCheckmark() !!}</div>
                                    @endif
                                </div>

                                <div class="{{ $responsiveCardClass }}">
                                    <h3 class="{{ $titleClassEnabled }}">
                                        Pelaksanaan Uji Kompetensi (FR.AK.02)
                                    </h3>
                                    
                                    @if ($level >= $LVL_ASESMEN)
                                        <p class="{{ $statusClassSelesai }}">Asesmen Selesai</p>
                                        <a href="{{ route('admin.rekomendasi.ak02', ['id_sertifikasi_asesi' => $sertifikasi->id_data_sertifikasi_asesi]) }}" class="{{ $btnBlue }} inline-flex items-center bg-purple-600 hover:bg-purple-700">
                                            <i class="fas fa-star mr-1"></i> Lihat Rekaman Nilai
                                        </a>
                                    @elseif ($unlockAsesmen)
                                        <p class="{{ $statusClassProses }}">Sedang Berlangsung / Menunggu Input Asesor</p>
                                    @else
                                        <p class="{{ $statusClassTerkunci }}">Menunggu Jadwal / Persetujuan</p>
                                    @endif
                                </div>
                            </li>

                            {{-- ============================================= --}}
                            {{-- ITEM 7: Keputusan dan Umpan Balik Asesor --}}
                            {{-- ============================================= --}}
                            <li class="relative flex items-center md:items-start md:pb-10">
                                <div class="absolute left-5 top-0 -bottom-8 w-1 md:left-6 md:top-6 md:-bottom-10 md:w-0.5 
                                    {{ $level >= $LVL_UMPAN_BALIK ? 'bg-green-500' : 'bg-gray-200' }}">
                                </div>

                                <div class="relative flex-shrink-0 ml-1 mr-4 md:mr-6 z-10">
                                    <div class="hidden md:flex w-12 h-12 rounded-lg bg-gray-100 items-center justify-center">
                                        <i class="fas fa-comment-dots w-6 h-6 text-gray-500"></i>
                                    </div>
                                    <div class="md:hidden flex items-center justify-center w-9 h-9 bg-white rounded-full border-4 border-gray-100 shadow-sm z-20 relative">
                                        @if ($level >= $LVL_UMPAN_BALIK)
                                            <div class="w-4 h-4 bg-green-500 rounded-full shadow-[0_0_10px_rgba(34,197,94,0.6)]"></div>
                                        @else
                                            <div class="w-4 h-4 bg-gray-300 rounded-full"></div>
                                        @endif
                                    </div>
                                    @if ($level >= $LVL_UMPAN_BALIK)
                                        <div class="hidden md:block">{!! renderCheckmark() !!}</div>
                                    @endif
                                </div>

                                <div class="{{ $responsiveCardClass }}">
                                    <h3 class="{{ $titleClassEnabled }}">
                                        Keputusan dan Umpan Balik Asesor (AK.03)
                                    </h3>

                                    @if ($level >= $LVL_UMPAN_BALIK)
                                        <p class="{{ $statusClassSelesai }}">Selesai Diisi Asesor & Asesi</p>
                                        <a href="#" class="{{ $btnBlue }} inline-flex items-center bg-purple-600 hover:bg-purple-700">
                                            <i class="fas fa-download mr-1"></i> Unduh Dokumen AK.03
                                        </a>
                                    @elseif ($unlockAK03)
                                        <p class="{{ $statusClassProses }}">Menunggu Umpan Balik dari Asesi</p>
                                    @else
                                        <p class="{{ $statusClassTerkunci }}">Menunggu Keputusan Asesor (AK.02)</p>
                                    @endif
                                </div>
                            </li>

                            {{-- ============================================= --}}
                            {{-- ITEM 8: Banding (Opsional) --}}
                            {{-- ============================================= --}}
                            <li class="relative flex items-center md:items-start md:pb-10">
                                <div class="absolute left-5 top-0 -bottom-8 w-1 md:left-6 md:top-6 md:-bottom-10 md:w-0.5 
                                    {{ $level >= $LVL_BANDING ? 'bg-green-500' : 'bg-gray-200' }}">
                                </div>

                                <div class="relative flex-shrink-0 ml-1 mr-4 md:mr-6 z-10">
                                    <div class="hidden md:flex w-12 h-12 rounded-lg bg-gray-100 items-center justify-center">
                                        <i class="fas fa-balance-scale w-6 h-6 text-gray-500"></i>
                                    </div>
                                    <div class="md:hidden flex items-center justify-center w-9 h-9 bg-white rounded-full border-4 border-gray-100 shadow-sm z-20 relative">
                                        @if ($level >= $LVL_BANDING)
                                            <div class="w-4 h-4 bg-green-500 rounded-full shadow-[0_0_10px_rgba(34,197,94,0.6)]"></div>
                                        @else
                                            <div class="w-4 h-4 bg-gray-300 rounded-full"></div>
                                        @endif
                                    </div>
                                    @if ($level >= $LVL_BANDING)
                                        <div class="hidden md:block">{!! renderCheckmark() !!}</div>
                                    @endif
                                </div>

                                <div class="{{ $responsiveCardClass }}">
                                    <h3 class="{{ $titleClassEnabled }}">
                                        Banding Asesmen (FR.AK.04)
                                    </h3>

                                    @if ($level >= $LVL_BANDING)
                                        <p class="{{ $statusClassSelesai }}">Banding Selesai</p>
                                        <a href="#" class="{{ $btnGreen }} inline-flex items-center">
                                            <i class="fas fa-download mr-1"></i> Unduh Banding
                                        </a>
                                    @elseif ($unlockAK04)
                                        <p class="{{ $statusClassProses }}">Menunggu Asesi Mengajukan Banding</p>
                                    @else
                                        <p class="{{ $statusClassTerkunci }}">Terkunci</p>
                                    @endif
                                </div>
                            </li>

                            {{-- ============================================= --}}
                            {{-- ITEM 9: Keputusan Komite --}}
                            {{-- ============================================= --}}
                            <li class="relative flex items-center md:items-start">
                                <div class="relative flex-shrink-0 ml-1 mr-4 md:mr-6 z-10">
                                    <div class="hidden md:flex w-12 h-12 rounded-lg bg-gray-100 items-center justify-center">
                                        <i class="fas fa-trophy w-6 h-6 text-gray-500"></i>
                                    </div>
                                    <div class="md:hidden flex items-center justify-center w-9 h-9 bg-white rounded-full border-4 border-gray-100 shadow-sm z-20 relative">
                                        @if ($level >= $LVL_REKOMENDASI)
                                            <div class="w-4 h-4 bg-green-500 rounded-full shadow-[0_0_10px_rgba(34,197,94,0.6)]"></div>
                                        @else
                                            <div class="w-4 h-4 bg-gray-300 rounded-full"></div>
                                        @endif
                                    </div>
                                    @if ($level >= $LVL_REKOMENDASI)
                                        <div class="hidden md:block">{!! renderCheckmark() !!}</div>
                                    @endif
                                </div>

                                <div class="{{ $responsiveCardClass }}">
                                    <h3 class="{{ $titleClassEnabled }}">Keputusan Komite (FR.AK.06)</h3>

                                    @if ($level == $LVL_REKOMENDASI)
                                        <p class="{{ $statusClassSelesai }}">Kompeten - Direkomendasikan Menerima Sertifikat</p>
                                        <button class="{{ $btnGreen }}">Cetak Sertifikat</button>
                                    @elseif ($sertifikasi->rekomendasi_hasil_asesmen_AK02 == 'belum_kompeten')
                                        <p class="text-xs text-red-600 font-medium">Belum Kompeten - Tidak Direkomendasikan</p>
                                    @else
                                        <p class="{{ $statusClassTunggu }}">Menunggu Keputusan Komite</p>
                                    @endif
                                </div>
                            </li>

                        </ol>
                    @endif

                </div>

            </div>

        </section>
    </main>

</body>
</html>