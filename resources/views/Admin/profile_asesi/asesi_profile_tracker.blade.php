@use('App\Models\DataSertifikasiAsesi')
@use('Carbon\Carbon')

@php
// --- 1. AMBIL DATA (Logic Admin) ---
$sertifikasi = $sertifikasiAcuan ?? $asesi->dataSertifikasi->sortByDesc('created_at')->first();

// --- 2. DEFINISI LEVEL ---
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

// --- 3. LOGIC STATUS & FLAG ---
$jadwal = null;
$isApl02Selesai = false;
$unlockAPL02 = false;
$unlockAK01 = false;
$unlockAsesmen = false;
$unlockAK03 = false;
$unlockAK04 = false;
$unlockSertifikat = false;
$statusGagal = false;
$statusLolos = false;
$isAPL01Ditolak = false;
$isAPL02Ditolak = false;

// Flag visual soal
$showIA02 = false; $showIA05 = false; $showIA06 = false; $showIA07 = false; $showIA09 = false;

// Messages
$pesanStatus = null;
$pesanWaktu = null;
$isWaktuHabis = false;
$isIA05Started = false;
$isIA06Started = false;
$isTidakKompeten = false; // Initialize to prevent undefined variable error

if ($sertifikasi) {
$jadwal = $sertifikasi->jadwal;

$isApl02Selesai = in_array($sertifikasi->rekomendasi_apl02, ['diterima', 'ditolak']);
$unlockAPL02 = $sertifikasi->rekomendasi_apl01 == 'diterima';
$isAPL01Ditolak = $sertifikasi->rekomendasi_apl01 == 'ditolak';
$isAPL02Ditolak = $sertifikasi->rekomendasi_apl02 == 'ditolak';

$unlockAK01 = $isApl02Selesai && !$isAPL02Ditolak;

if ($jadwal && $jadwal->tanggal_pelaksanaan && $jadwal->waktu_mulai) {
try {
$tgl = $jadwal->tanggal_pelaksanaan->format('Y-m-d');
$jam = $jadwal->waktu_mulai->format('H:i:s');
$waktuMulai = Carbon::parse($tgl . ' ' . $jam);
if (Carbon::now()->greaterThanOrEqualTo($waktuMulai) && $unlockAK01) {
$unlockAsesmen = true;
}
} catch (\Exception $e) {}
}

$unlockAK03 = !is_null($sertifikasi->rekomendasi_hasil_asesmen_AK02);
$unlockAK04 = $unlockAK03;
$unlockSertifikat = $sertifikasi->status_rekomendasi_komite == 'kompeten';

$statusGagal = $sertifikasi->rekomendasi_hasil_asesmen_AK02 == 'belum_kompeten';
$statusLolos = $sertifikasi->rekomendasi_hasil_asesmen_AK02 == 'kompeten';
$isTidakKompeten = $statusGagal; // Map to variable expected by view logic

if($jadwal && $jadwal->skema && $jadwal->skema->listForm) {
$f = $jadwal->skema->listForm;
$showIA02 = $f->fr_ia_02 == 1;
$showIA05 = $f->fr_ia_05 == 1;
$showIA06 = $f->fr_ia_06 == 1;
$showIA07 = $f->fr_ia_07 == 1;
$showIA09 = $f->fr_ia_09 == 1;
}
}

// --- 4. HELPER VIEW ---
if (!function_exists('renderCheckmark')) {
function renderCheckmark() {
return '<div class="absolute -top-1 -left-1.5 z-10 bg-green-500 rounded-full p-0.5 border-2 border-white">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="white" class="w-3 h-3">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
    </svg>
</div>';
}
}

// --- [PERBAIKAN] DEFINISI CSS VARIABLES YANG HILANG ---
$titleClassEnabled = 'text-lg font-semibold text-gray-900';
$titleClassDisabled = 'text-lg font-semibold text-gray-400';

// PENTING: Class untuk Link/Text Link (Enabled vs Disabled)
$linkClassEnabled = 'text-lg font-semibold text-gray-900 hover:text-blue-600 cursor-pointer transition-colors';
$linkClassDisabled = 'text-lg font-semibold text-gray-400 cursor-not-allowed';

$responsiveCardClass = 'flex-1 ml-3 md:ml-0 bg-white p-5 rounded-2xl shadow-[0_4px_20px_-5px_rgba(0,0,0,0.1)] border border-gray-100 md:bg-transparent md:p-0 md:rounded-none md:shadow-none md:border-0 relative z-10';

$statusClassSelesai = 'text-xs text-green-600 font-medium';
$statusClassProses = 'text-xs text-blue-600 font-medium';
$statusClassTunggu = 'text-xs text-yellow-600 font-medium';
$statusClassTerkunci = 'text-xs text-gray-400 font-medium';

$btnBase = 'mt-2 px-4 py-1.5 text-xs font-semibold rounded-md inline-flex items-center transition-all';
$btnBlue = "$btnBase bg-blue-500 text-white hover:bg-blue-600 shadow-blue-100 hover:shadow-lg";
$btnGreen = "$btnBase bg-green-500 text-white hover:bg-green-600 shadow-green-100 hover:shadow-lg";
$btnYellow = "$btnBase bg-yellow-500 text-white hover:bg-yellow-600 shadow-yellow-100 hover:shadow-lg";
$btnGray = "$btnBase bg-gray-300 text-gray-500 cursor-not-allowed border border-gray-200";
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tracker Sertifikasi Asesi| LSP Polines</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        ::-webkit-scrollbar {
            width: 0;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 text-sm">

    <x-navbar.navbar-admin />

    <main class="flex min-h-[calc(100vh-80px)]">

        @php
            // Default fallback (hanya jika data jadwal benar-benar hilang/corrupt)
            $urlKembali = route('admin.master_schedule');

            // [LOGIKA UTAMA]
            // Cek variabel $sertifikasi (yang dipakai untuk menampilkan data di halaman ini)
            if (isset($sertifikasi) && $sertifikasi->id_jadwal) {
                // Paksa redirect ke Daftar Hadir (Attendance) berdasarkan ID Jadwal sertifikasi ini
                $urlKembali = route('admin.schedule.attendance', $sertifikasi->id_jadwal);
            } 
            // Opsi tambahan: Cek jika ada id_jadwal di query string url (misal ?id_jadwal=50)
            elseif (request()->has('id_jadwal')) {
                $urlKembali = route('admin.schedule.attendance', request('id_jadwal'));
            }
        @endphp

        <x-sidebar.sidebar_profile_asesi
            :asesi="$asesi"
            :backUrl="$urlKembali"
            :activeSertifikasi="$sertifikasiAcuan ?? null" />

        <section class="ml-[22%] flex-1 p-8 h-[calc(100vh-80px)] overflow-y-auto bg-gray-50">

            <div class="bg-white p-10 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] border border-gray-100 min-h-[500px]">

                <h1 class="text-3xl font-bold text-gray-800 mb-10 text-center">Tracker Status Sertifikasi</h1>

                <div class="max-w-3xl mx-auto">

                    @if (!$sertifikasi)
                    <div class="p-6 text-center text-gray-500 italic border-2 border-dashed border-gray-300 rounded-xl mt-10">
                        Asesi ini belum terdaftar dalam jadwal sertifikasi manapun.
                    </div>
                    @else

                    @if($statusGagal)
                    <div class="p-4 mb-8 bg-red-100 text-red-700 rounded-xl border border-red-200 text-center font-medium">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Asesi dinyatakan **BELUM KOMPETEN**.
                    </div>
                    @endif

                    <ol class="relative z-10 space-y-6 md:space-y-0">

                        {{-- ============================================= --}}
                        {{-- ITEM 1: Formulir APL-01 --}}
                        {{-- ============================================= --}}
                        <li class="relative flex items-center md:items-start md:pb-10">
                            @php
                            $isFormSelesai = $level >= $LVL_DAFTAR_SELESAI;
                            @endphp

                            {{-- Garis Timeline --}}
                            <div class="absolute left-5 top-0 -bottom-8 w-1 md:left-6 md:top-6 md:-bottom-10 md:w-0.5 {{ $isFormSelesai ? 'bg-green-500' : 'bg-gray-200' }}"></div>

                            <div class="relative flex-shrink-0 ml-1 mr-4 md:mr-6 z-10">
                                {{-- ICON SVG: Dokumen --}}
                                <div class="hidden md:flex w-12 h-12 rounded-lg bg-gray-100 items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>

                                {{-- Bulatan Mobile --}}
                                <div class="md:hidden flex items-center justify-center w-9 h-9 bg-white rounded-full border-4 border-gray-100 shadow-sm z-20 relative">
                                    <div class="w-4 h-4 rounded-full {{ $isFormSelesai ? 'bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.6)]' : 'bg-gray-300' }}"></div>
                                </div>

                                {{-- Centang Hijau --}}
                                @if ($isFormSelesai)
                                <div class="hidden md:block">{!! renderCheckmark() !!}</div>
                                @elseif ($isAPL01Ditolak)
                                <div class="hidden md:block absolute -top-2 -right-2 bg-red-100 rounded-full p-1 border-2 border-white shadow-sm">
                                    <svg class="w-4 h-4 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                @endif
                            </div>

                            <div class="{{ $responsiveCardClass }}">
                                {{-- JUDUL: Teks Biasa (H3), tidak bisa diklik --}}
                                <h3 class="{{ $titleClassEnabled }}">
                                    Formulir Pendaftaran Sertifikasi
                                </h3>

                                @if ($isFormSelesai)
                                @if ($isAPL01Ditolak)
                                <p class="text-xs text-red-600 font-bold">Dokumen Ditolak</p>
                                @else
                                <p class="{{ $statusClassSelesai }}">Selesai Diisi Asesi</p>
                                @endif

                                <div class="flex flex-wrap gap-2 mt-2">

                                    {{-- Tombol Unduh Dokumen (BIRU) --}}
                                    <a href="{{ route('admin.cetak.apl01', ['id_data_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}" target="_blank" class="{{ $btnBlue }}">
                                        <i class="fas fa-download mr-1"></i> Unduh Dokumen
                                    </a>

                                    {{-- Tombol Lihat Data (HIJAU) --}}
                                    <a href="{{ route('admin.cetak.apl01', ['id_data_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi, 'mode' => 'preview', 't' => time()]) }}" class="{{ $btnGreen }}">
                                        <i class="fas fa-eye mr-1"></i> Lihat Data
                                    </a>
                                </div>
                                @else
                                <p class="{{ $statusClassTunggu }}">Menunggu Asesi Mengisi</p>

                                <div class="flex flex-wrap gap-2 mt-2">
                                    {{-- Tombol Disabled --}}
                                    <button disabled class="{{ $btnGray }}">
                                        <i class="fas fa-eye mr-1"></i> Lihat Data
                                    </button>

                                    <button disabled class="{{ $btnGray }}">
                                        <i class="fas fa-download mr-1"></i> Unduh Dokumen
                                    </button>
                                </div>
                                @endif
                            </div>
                        </li>


                        {{-- ============================================= --}}
                        {{-- ITEM 2: Pembayaran --}}
                        {{-- ============================================= --}}
                        <li class="relative flex items-center md:items-start md:pb-10">
                            {{-- Logika Penentu Status --}}
                            @php
                            // Cek status database
                            $isVerified = $sertifikasi->rekomendasi_apl01 == 'diterima';
                            $isRejected = $sertifikasi->rekomendasi_apl01 === 'tidak diterima';

                            // Logika Warna Garis (Tali)
                            if ($isVerified) {
                            $lineColor = 'bg-green-500'; // Hijau jika Lunas
                            } elseif ($isRejected) {
                            $lineColor = 'bg-red-500'; // Merah jika Ditolak
                            } else {
                            $lineColor = 'bg-gray-200'; // Abu-abu default
                            }
                            @endphp

                            {{-- Garis Timeline (Tali) --}}
                            <div class="absolute left-5 top-0 -bottom-8 w-1 md:left-6 md:top-6 md:-bottom-10 md:w-0.5 {{ $lineColor }}"></div>

                            <div class="relative flex-shrink-0 ml-1 mr-4 md:mr-6 z-10">
                                {{-- KOTAK ICON (Selalu Abu-abu) --}}
                                <div class="hidden md:flex w-12 h-12 rounded-lg items-center justify-center bg-gray-100 relative">
                                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h6m3-3.75l-3 3m0 0l-3-3m3 3V15m6-1.5h.008v.008H18V13.5z" />
                                    </svg>

                                    {{-- INDIKATOR STATUS (DESKTOP) --}}
                                    @if ($isVerified)
                                    <div class="hidden md:block">{!! renderCheckmark() !!}</div>
                                    @elseif ($isRejected)
                                    <div class="hidden md:block absolute -top-1.5 -left-1.5 z-20 bg-white rounded-full">
                                        <div class="w-5 h-5 bg-red-600 rounded-full border-2 border-white shadow-sm flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                {{-- Bulatan Kecil (Mobile) --}}
                                <div class="md:hidden flex items-center justify-center w-9 h-9 bg-white rounded-full border-4 border-gray-100 shadow-sm z-20 relative">
                                    <div class="w-4 h-4 rounded-full {{ $isVerified ? 'bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.6)]' : ($isRejected ? 'bg-red-500' : 'bg-gray-300') }}"></div>
                                </div>
                            </div>

                            <div class="{{ $responsiveCardClass }}">
                                <h3 class="{{ ($level >= $LVL_DAFTAR_SELESAI) ? $titleClassEnabled : $titleClassDisabled }}">Pembayaran</h3>

                                {{-- KONDISI 1: SUDAH ACC (LUNAS) --}}
                                @if ($isVerified)
                                <p class="{{ $statusClassSelesai }}">Lunas & Terverifikasi</p>
                                <div class="flex gap-2 mt-2">
                                    <a href="{{ route('admin.payment.invoice', $sertifikasi->id_data_sertifikasi_asesi) }}" target="_blank" class="{{ $btnBlue }} inline-flex items-center justify-center text-center">
                                        <i class="fas fa-file-invoice mr-1"></i> Unduh Invoice
                                    </a>
                                    <a href="{{ route('admin.payment.invoice', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi, 'mode' => 'preview', 't' => time()]) }}" 
                                    target="_blank" 
                                    class="{{ $btnGreen ?? 'bg-green-600 hover:bg-green-700 text-white' }} px-4 py-1.5 text-xs inline-flex items-center rounded-md transition shadow-sm">
                                        <i class="fas fa-eye mr-1"></i> Lihat Invoice
                                    </a>
                                    <form action="{{ route('admin.verifikasi.pembayaran', ['id_asesi' => $asesi->id_asesi, 'id' => $sertifikasi->id_data_sertifikasi_asesi]) }}" method="POST" onsubmit="return confirm('Batalkan verifikasi? Status akan kembali menunggu.');">
                                        @csrf @method('POST')
                                        <input type="hidden" name="status" value="menunggu">
                                        <button type="submit" class="mt-2 px-3 py-1.5 text-xs font-semibold rounded-md inline-flex items-center transition-all bg-gray-200 text-gray-600 hover:bg-gray-300">
                                            <i class="fas fa-undo mr-1"></i> Batal
                                        </button>
                                    </form>
                                </div>

                                {{-- KONDISI 2: DITOLAK --}}
                                @elseif ($isRejected)
                                <p class="text-xs font-semibold text-red-600">Pembayaran Ditolak</p>
                                <p class="text-xs text-gray-500 mb-2">Bukti pembayaran tidak valid atau tidak sesuai.</p>

                                <div class="flex flex-wrap items-center gap-2 mt-2">
                                    <a href="{{ route('admin.payment.invoice', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}" target="_blank" class="{{ $btnBlue }} px-4 py-1.5 text-xs inline-flex items-center">
                                        <i class="fas fa-file-invoice mr-1"></i> Unduh Invoice
                                    </a>
                                    <a href="{{ route('admin.payment.invoice', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi, 'mode' => 'preview', 't' => time()]) }}" 
                                    target="_blank" 
                                    class="{{ $btnGreen ?? 'bg-green-600 hover:bg-green-700 text-white' }} px-4 py-1.5 text-xs inline-flex items-center rounded-md transition shadow-sm">
                                        <i class="fas fa-eye mr-1"></i> Lihat Invoice
                                    </a>
                                    <form action="{{ route('admin.verifikasi.pembayaran', ['id_asesi' => $asesi->id_asesi, 'id' => $sertifikasi->id_data_sertifikasi_asesi]) }}" method="POST" onsubmit="return confirm('Kembalikan status ke menunggu verifikasi?');">
                                        @csrf
                                        <input type="hidden" name="status" value="menunggu">
                                        <button type="submit" class="mt-2 px-4 py-1.5 text-xs font-semibold rounded-md inline-flex items-center bg-gray-200 text-gray-700 hover:bg-gray-300 transition">
                                            <i class="fas fa-undo mr-1"></i> Batal
                                        </button>
                                    </form>
                                </div>

                                {{-- KONDISI 3 [BARU]: MENUNGGU VERIFIKASI MIDTRANS (Teks Biru, Tombol Mati) --}}
                                @elseif ($isMidtransProcess)
                                <p class="{{ $statusClassProses }}">
                                    <span class="text-blue-600">Menunggu Verifikasi Bayar</span>
                                </p>
                                <p class="text-xs text-gray-500 mb-2">Sistem sedang memverifikasi pembayaran (Midtrans).</p>

                                <div class="flex flex-wrap items-center gap-2 mt-2">
                                    <button disabled class="{{ $btnGray }} cursor-not-allowed opacity-75">
                                        <i class="fas fa-file-invoice mr-1"></i> Unduh Invoice
                                    </button>
                                    <button disabled class="{{ $btnGray }} cursor-not-allowed opacity-75">
                                        <i class="fas fa-eye mr-1"></i> Lihat Invoice
                                    </button>
                                    <button disabled class="{{ $btnGray }} cursor-not-allowed opacity-75">
                                        <i class="fas fa-check-circle mr-1"></i> Verifikasi
                                    </button>
                                    <button disabled class="{{ $btnGray }} cursor-not-allowed opacity-75">
                                        <i class="fas fa-times-circle mr-1"></i> Tolak Verifikasi
                                    </button>
                                </div>

                                {{-- KONDISI 4: MENUNGGU VERIFIKASI ADMIN (MANUAL) --}}
                                @elseif ($level >= $LVL_TUNGGU_BAYAR)
                                <p class="{{ $statusClassTunggu }}">
                                    <span class="text-green-600">Lunas</span><span class="mx-1">•</span>Menunggu Verifikasi Admin
                                </p>
                                <p class="text-xs text-gray-500 mb-2">Asesi telah mengunggah bukti pembayaran.</p>

                                <div class="flex flex-wrap items-center gap-2 mt-2">
                                    <a href="{{ route('asesi.payment.invoice', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}" target="_blank" class="{{ $btnBlue }} px-4 py-1.5 text-xs">
                                        <i class="fas fa-file-invoice mr-1"></i> Unduh Invoice
                                    </a>

                                    {{-- Tombol Verifikasi Manual --}}
                                    <form action="{{ route('admin.verifikasi.pembayaran', ['id_asesi' => $asesi->id_asesi, 'id' => $sertifikasi->id_data_sertifikasi_asesi]) }}" method="POST" class="m-0 p-0">
                                        @csrf
                                        <input type="hidden" name="status" value="diterima">
                                        <button type="submit" class="{{ $btnGreen }} px-4 py-1.5 text-xs inline-flex items-center" onclick="return confirm('Verifikasi pembayaran ini?');">
                                            <i class="fas fa-check-circle mr-1"></i> Verifikasi
                                        </button>
                                    </form>

                                    {{-- Tombol Tolak Manual --}}
                                    <form action="{{ route('admin.verifikasi.pembayaran', ['id_asesi' => $asesi->id_asesi, 'id' => $sertifikasi->id_data_sertifikasi_asesi]) }}" method="POST" class="m-0 p-0">
                                        @csrf @method('POST')
                                        <input type="hidden" name="status" value="ditolak">
                                        <button type="submit" class="mt-2 px-4 py-1.5 text-xs font-semibold rounded-md inline-flex items-center transition-all bg-red-600 text-white hover:bg-red-700 shadow-sm m-0" onclick="return confirm('Tolak pembayaran ini?');">
                                            <i class="fas fa-times-circle mr-1"></i> Tolak Verifikasi
                                        </button>
                                    </form>
                                </div>

                                {{-- KONDISI 5: MENUNGGU ASESI BAYAR --}}
                                @elseif ($level == $LVL_DAFTAR_SELESAI)
                                <p class="{{ $statusClassTunggu }}">Menunggu Pembayaran Asesi</p>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    <button disabled class="{{ $btnGray }}"><i class="fas fa-download mr-1"></i> Unduh Invoice</button>
                                    <button disabled class="{{ $btnGray }}"><i class="fas fa-check-circle mr-1"></i> Verifikasi</button>
                                    <button disabled class="{{ $btnGray }}"><i class="fas fa-times-circle mr-1"></i> Tolak Verifikasi</button>
                                </div>

                                @else
                                <p class="{{ $statusClassTerkunci }}">Terkunci</p>
                                @endif
                            </div>
                        </li>

                        {{-- ============================================= --}}
                        {{-- ITEM 3: Pra-Asesmen (APL-02) --}}
                        {{-- ============================================= --}}
                        <li class="relative flex items-center md:items-start md:pb-10">
                            @php
                            // Ambil variable dari Controller
                            // $statusStep3 values: 'locked', 'menunggu_asesi', 'menunggu_asesor', 'selesai', 'ditolak'

                            // LOGIC WARNA TALI (LINE)
                            // Hijau jika selesai, Merah jika ditolak (APL02 atau Pembayaran), sisanya Abu
                            if ($statusStep3 == 'selesai') {
                            $lineColor = 'bg-green-500';
                            } elseif ($statusStep3 == 'ditolak' || $paymentRejected) {
                            $lineColor = 'bg-red-500';
                            } else {
                            $lineColor = 'bg-gray-200';
                            }

                            // LOGIC WARNA ICON & TEKS JUDUL
                            // [PERUBAHAN DI SINI]: Icon dikunci jadi statis (Abu-abu)
                            $iconBg = 'bg-gray-100';
                            $iconColor = 'text-gray-500';

                            $titleClass = $titleClassDisabled; // Default abu

                            // Kita hanya mengubah Title Class (Judul menyala), tapi Icon tetap abu-abu
                            if ($statusStep3 == 'selesai') {
                            $titleClass = $titleClassEnabled;
                            } elseif ($statusStep3 == 'menunggu_asesor') {
                            $titleClass = $titleClassEnabled;
                            } elseif ($statusStep3 == 'menunggu_asesi') {
                            $titleClass = $titleClassEnabled;
                            } elseif ($statusStep3 == 'ditolak') {
                            $titleClass = $titleClassEnabled;
                            }
                            @endphp

                            {{-- Garis Timeline (Tali) --}}
                            <div class="absolute left-5 top-0 -bottom-8 w-1 md:left-6 md:top-6 md:-bottom-10 md:w-0.5 {{ $lineColor }}"></div>

                            <div class="relative flex-shrink-0 ml-1 mr-4 md:mr-6 z-10">
                                {{-- KOTAK ICON (STATIS ABU-ABU) --}}
                                <div class="hidden md:flex w-12 h-12 rounded-lg items-center justify-center {{ $iconBg }} relative">
                                    <svg class="w-6 h-6 {{ $iconColor }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.122 2.122l7.81-7.81" />
                                    </svg>

                                    {{-- INDIKATOR STATUS --}}
                                    @if ($statusStep3 == 'ditolak' || $paymentRejected)
                                    {{-- SILANG MERAH (Pojok Kiri Atas) --}}
                                    <div class="hidden md:block absolute -top-1.5 -left-1.5 z-20 bg-white rounded-full">
                                        <div class="w-5 h-5 bg-red-600 rounded-full border-2 border-white shadow-sm flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                    </div>
                                    @elseif ($statusStep3 == 'selesai')
                                    {{-- CENTANG HIJAU (Pojok Kanan Atas) --}}
                                    <div class="hidden md:block">{!! renderCheckmark() !!}</div>
                                    @endif
                                </div>

                                {{-- Bulatan Mobile --}}
                                <div class="md:hidden flex items-center justify-center w-9 h-9 bg-white rounded-full border-4 border-gray-100 shadow-sm z-20 relative">
                                    <div class="w-4 h-4 rounded-full {{ $statusStep3 == 'selesai' ? 'bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.6)]' : (($statusStep3 == 'ditolak' || $paymentRejected) ? 'bg-red-500' : 'bg-gray-300') }}"></div>
                                </div>
                            </div>

                            <div class="{{ $responsiveCardClass }}">
                                {{-- JUDUL --}}
                                <h3 class="{{ $titleClass }}">Pra-Asesmen</h3>

                                {{-- LOGIC KONTEN --}}

                                {{-- KONDISI 0: PEMBAYARAN DITOLAK ATAU BELUM LUNAS --}}
                                @if ($paymentRejected || !$paymentVerified)
                                <p class="{{ $statusClassTerkunci }}">Terkunci</p>

                                {{-- KONDISI 1: DITOLAK OLEH ASESOR --}}
                                @elseif ($statusStep3 == 'ditolak')
                                <p class="text-xs text-red-600 font-semibold">Tidak Terverifikasi</p>
                                <p class="text-xs text-gray-500 mb-2">Asesmen mandiri ditolak oleh Asesor.</p>

                                <div class="flex gap-2 mt-2">
                                    {{-- Tombol Unduh (Sekarang Aktif/Biru) --}}
                                    <a href="{{ route('admin.cetak.apl02', $sertifikasi->id_data_sertifikasi_asesi) }}"
                                        target="_blank"
                                        class="{{ $btnBlue }}">
                                        <i class="fas fa-download mr-1"></i> Unduh Dokumen
                                    </a>

                                    {{-- Tombol Lihat Hasil (Sama dengan kondisi Lulus) --}}
                                    <a href="{{ route('admin.cetak.apl02', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi, 'mode' => 'preview', 't' => time()]) }}"
                                        class="{{ $btnGreen }}">
                                        <i class="fas fa-eye mr-1"></i> Lihat Hasil
                                    </a>
                                </div>

                                {{-- KONDISI 2: SELESAI / DITERIMA (HIJAU) --}}
                                @elseif ($statusStep3 == 'selesai')
                                <p class="{{ $statusClassSelesai }}">Lulus</p>
                                <div class="flex gap-2 mt-2">
                                    {{-- Tombol Unduh AKTIF (Biru) --}}
                                    <a href="{{ route('admin.cetak.apl02', $sertifikasi->id_data_sertifikasi_asesi) }}" class="{{ $btnBlue }}" target="_blank">
                                        <i class="fas fa-download mr-1"></i> Unduh Dokumen
                                    </a>
                                    {{-- Tombol Lihat Hasil --}}
                                    <a href="{{ route('admin.cetak.apl02', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi, 'mode' => 'preview', 't' => time()]) }}" class="{{ $btnGreen }}">
                                        <i class="fas fa-eye mr-1"></i> Lihat Hasil
                                    </a>
                                </div>

                                {{-- KONDISI 3: MENUNGGU VERIFIKASI ASESOR (BIRU) --}}
                                @elseif ($statusStep3 == 'menunggu_asesor')
                                <p class="{{ $statusClassProses }}">
                                    <span class="text-blue-600">Menunggu Verifikasi Asesor</span>

                                </p>
                                <p class="text-xs text-gray-500 mt-1">Asesi sudah mengisi form. Menunggu penilaian Asesor.</p>

                                {{-- Tombol Unduh (Mati) --}}
                                <button disabled class="{{ $btnGray }} mt-2 cursor-not-allowed opacity-75">
                                    <i class="fas fa-download mr-1"></i> Unduh Dokumen
                                </button>

                                {{-- KONDISI 4: MENUNGGU ASESI MENGISI (KUNING) --}}
                                @elseif ($statusStep3 == 'menunggu_asesi')
                                <p class="{{ $statusClassTunggu }}">
                                    Menunggu Asesi Mengisi
                                </p>
                                <p class="text-xs text-gray-500 mt-1">Asesi belum melengkapi formulir APL-02.</p>

                                {{-- Tombol Unduh (Mati) --}}
                                <button disabled class="{{ $btnGray }} mt-2 cursor-not-allowed opacity-75">
                                    <i class="fas fa-download mr-1"></i> Unduh Dokumen
                                </button>

                                @endif
                            </div>
                        </li>

                        {{-- ============================================= --}}
                        {{-- ITEM 4: Jadwal TUK --}}
                        {{-- ============================================= --}}
                        <li class="relative flex items-center md:items-start md:pb-10">
                            @php
                            // Logic Status
                            // Step ini terbuka HANYA jika Step 3 (Pra-Asesmen) SELESAI
                            $isStep4Open = ($statusStep3 == 'selesai');

                            // Cek Penolakan di Step-step sebelumnya
                            $isRejectedPrev = $paymentRejected || ($statusStep3 == 'ditolak');

                            // Logic Warna Tali
                            if ($isStep4Open) {
                            $lineColor = 'bg-green-500';
                            } elseif ($isRejectedPrev) {
                            $lineColor = 'bg-red-500';
                            } else {
                            $lineColor = 'bg-gray-200';
                            }

                            // Logic Judul
                            $titleClass = $isStep4Open ? $titleClassEnabled : $titleClassDisabled;

                            // Logic Warna Icon Detail (Kecil-kecil)
                            // Jika terbuka (verified) -> Warna-warni
                            // Jika terkunci/ditolak -> Abu-abu semua
                            $iconClassDate = $isStep4Open ? 'text-blue-500' : 'text-gray-400';
                            $iconClassTime = $isStep4Open ? 'text-yellow-500' : 'text-gray-400';
                            $iconClassLoc = $isStep4Open ? 'text-red-500' : 'text-gray-400';

                            // Text detail juga jadi abu jika belum verified
                            $textDetailClass = $isStep4Open ? 'text-gray-600' : 'text-gray-400';

                            $jadwalData = $sertifikasi->jadwal;
                            @endphp

                            {{-- Garis Timeline --}}
                            <div class="absolute left-5 top-0 -bottom-8 w-1 md:left-6 md:top-6 md:-bottom-10 md:w-0.5 {{ $lineColor }}"></div>

                            <div class="relative flex-shrink-0 ml-1 mr-4 md:mr-6 z-10">
                                {{-- KOTAK ICON (Statis Abu-abu) --}}
                                <div class="hidden md:flex w-12 h-12 rounded-lg bg-gray-100 items-center justify-center relative">
                                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h6.75M9 11.25h6.75M9 15.75h6.75M9 20.25h6.75" />
                                    </svg>

                                    {{-- INDIKATOR STATUS --}}
                                    @if ($isRejectedPrev)
                                    {{-- SILANG MERAH --}}
                                    <div class="hidden md:block absolute -top-1.5 -left-1.5 z-20 bg-white rounded-full">
                                        <div class="w-5 h-5 bg-red-600 rounded-full border-2 border-white shadow-sm flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                    </div>
                                    @elseif ($isStep4Open)
                                    {{-- CENTANG HIJAU --}}
                                    <div class="hidden md:block">{!! renderCheckmark() !!}</div>
                                    @endif
                                </div>

                                {{-- Bulatan Mobile --}}
                                <div class="md:hidden flex items-center justify-center w-9 h-9 bg-white rounded-full border-4 border-gray-100 shadow-sm z-20 relative">
                                    <div class="w-4 h-4 rounded-full {{ $isStep4Open ? 'bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.6)]' : ($isRejectedPrev ? 'bg-red-500' : 'bg-gray-300') }}"></div>
                                </div>
                            </div>

                            <div class="{{ $responsiveCardClass }}">
                                <h3 class="{{ $titleClass }}">Jadwal dan TUK</h3>

                                @if ($jadwalData)
                                <div class="text-xs {{ $textDetailClass }} mt-1 mb-2 flex flex-wrap items-center">

                                    {{-- Tanggal --}}
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-day w-4 text-center mr-1 {{ $iconClassDate }}"></i>
                                        <span class="font-medium">
                                            {{ \Carbon\Carbon::parse($jadwalData->tanggal_pelaksanaan)->isoFormat('D MMMM Y') }}
                                        </span>
                                    </div>

                                    {{-- Pembatas --}}
                                    <span class="mx-2 text-gray-300">|</span>

                                    {{-- Jam --}}
                                    <div class="flex items-center">
                                        <i class="fas fa-clock w-4 text-center mr-1 {{ $iconClassTime }}"></i>
                                        <span>
                                            {{ \Carbon\Carbon::parse($jadwalData->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwalData->waktu_selesai)->format('H:i') }} WIB
                                        </span>
                                    </div>

                                    {{-- Pembatas --}}
                                    <span class="mx-2 text-gray-300">|</span>

                                    {{-- Lokasi --}}
                                    <div class="flex items-center">
                                        <i class="fas fa-map-marker-alt w-4 text-center mr-1 {{ $iconClassLoc }}"></i>
                                        <span>
                                            {{ $jadwalData->masterTuk->nama_lokasi ?? 'Lokasi TUK belum diset' }}
                                        </span>
                                    </div>

                                </div>
                                @else
                                <p class="text-sm text-gray-400 mb-2">Belum dijadwalkan.</p>
                                @endif

                                {{-- KONDISI 1: DITOLAK SEBELUMNYA --}}
                                @if ($isRejectedPrev)
                                <p class="{{ $statusClassTerkunci }}">Menunggu Verifikasi Pra-Asesmen</p>

                                {{-- KONDISI 2: LOLOS PRA-ASESMEN (AKTIF) --}}
                                @elseif ($isStep4Open)
                                <p class="{{ $statusClassSelesai }}">Terverifikasi</p>
                                <div class="mt-2 flex flex-wrap gap-2">
    
                                    {{-- [TOMBOL 1] UNDUH KARTU (TETAP SAMA) --}}
                                    <a href="{{ route('admin.pdf.kartu_peserta', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}" 
                                    class="{{ $btnBlue }} inline-flex items-center" 
                                    target="_blank">
                                        <i class="fas fa-id-card mr-1"></i> Unduh Kartu Peserta
                                    </a>

                                    {{-- [TOMBOL 2] LIHAT KARTU (BARU) --}}
                                    <a href="{{ route('admin.pdf.kartu_peserta', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi, 'mode' => 'preview', 't' => time()]) }}" 
                                    class="{{ $btnGreen }} inline-flex items-center" 
                                    target="_blank">
                                        <i class="fas fa-eye mr-1"></i> Lihat Kartu
                                    </a>

                                </div>

                                {{-- KONDISI 3: MENUNGGU --}}
                                @else
                                <p class="{{ $statusClassTerkunci }}">Menunggu Verifikasi Pra-Asesmen</p>
                                @endif
                            </div>
                        </li>

                        {{-- ============================================= --}}
                        {{-- ITEM 5: Persetujuan (FR.AK.01) --}}
                        {{-- ============================================= --}}
                        <li class="relative flex items-center md:items-start md:pb-10">
                            @php
                            // 1. Cek Penolakan (Merah)
                            $isRejectedPrev = $paymentRejected || ($statusStep3 == 'ditolak');

                            // 2. Cek Syarat Rantai (Chain)
                            // Step 5 hanya boleh AKTIF jika: Pembayaran Lunas DAN APL-02 Selesai
                            // Jika admin membatalkan pembayaran, $paymentVerified jadi false, maka $chainValid jadi false
                            $chainValid = $paymentVerified && ($statusStep3 == 'selesai');

                            // 3. Cek Status Tahap Ini
                            // Terbuka jika rantai valid
                            $isStep5Open = $chainValid;

                            // Selesai HANYA JIKA level cukup DAN rantai masih valid
                            // Jadi kalau level tinggi tapi pembayaran dibatalin, ini jadi false
                            $isStep5Finished = ($level >= $LVL_SETUJU) && $chainValid;

                            // 4. Logic Warna Tali
                            if ($isRejectedPrev) {
                            $lineColor = 'bg-red-500'; // Prioritas 1: Merah jika ada yang ditolak
                            } elseif ($isStep5Finished) {
                            $lineColor = 'bg-green-500'; // Prioritas 2: Hijau jika benar-benar selesai & valid
                            } else {
                            $lineColor = 'bg-gray-200'; // Prioritas 3: Abu-abu (Terkunci/Reset)
                            }

                            // 5. Logic Judul
                            // Judul aktif hanya jika step terbuka dan tidak ada penolakan
                            $titleClass = ($isStep5Open && !$isRejectedPrev) ? $titleClassEnabled : $titleClassDisabled;
                            @endphp

                            {{-- Garis Timeline --}}
                            <div class="absolute left-5 top-0 -bottom-8 w-1 md:left-6 md:top-6 md:-bottom-10 md:w-0.5 {{ $lineColor }}"></div>

                            <div class="relative flex-shrink-0 ml-1 mr-4 md:mr-6 z-10">
                                {{-- KOTAK ICON (Statis Abu-abu) --}}
                                <div class="hidden md:flex w-12 h-12 rounded-lg bg-gray-100 items-center justify-center relative">
                                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                                    </svg>

                                    {{-- INDIKATOR STATUS --}}
                                    @if ($isRejectedPrev)
                                    {{-- SILANG MERAH --}}
                                    <div class="hidden md:block absolute -top-1.5 -left-1.5 z-20 bg-white rounded-full">
                                        <div class="w-5 h-5 bg-red-600 rounded-full border-2 border-white shadow-sm flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                    </div>
                                    @elseif ($isStep5Finished)
                                    {{-- CENTANG HIJAU --}}
                                    <div class="hidden md:block">{!! renderCheckmark() !!}</div>
                                    @endif
                                </div>

                                {{-- Bulatan Mobile --}}
                                <div class="md:hidden flex items-center justify-center w-9 h-9 bg-white rounded-full border-4 border-gray-100 shadow-sm z-20 relative">
                                    <div class="w-4 h-4 rounded-full {{ $isStep5Finished ? 'bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.6)]' : ($isRejectedPrev ? 'bg-red-500' : 'bg-gray-300') }}"></div>
                                </div>
                            </div>

                            <div class="{{ $responsiveCardClass }}">
                                <h3 class="{{ $titleClass }}">Persetujuan Asesmen dan Kerahasiaan</h3>

                                {{-- KONDISI 1: DITOLAK SEBELUMNYA --}}
                                @if ($isRejectedPrev)
                                <p class="{{ $statusClassTerkunci }}">Terkunci</p>

                                {{-- KONDISI 2: SELESAI / DISETUJUI & VALID --}}
                                @elseif ($isStep5Finished)
                                <p class="{{ $statusClassSelesai }}">Telah Disetujui Asesi</p>

                                <div class="mt-2 flex flex-wrap gap-2">
    
                                    {{-- [TOMBOL 1] UNDUH DOKUMEN --}}
                                    <a href="{{ route('admin.cetak.ak01', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}" 
                                    class="{{ $btnBlue }} inline-flex items-center" 
                                    target="_blank">
                                        <i class="fas fa-download mr-1"></i> Unduh Dokumen
                                    </a>

                                    {{-- [TOMBOL 2] LIHAT DOKUMEN (BARU) --}}
                                    <a href="{{ route('admin.cetak.ak01', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi, 'mode' => 'preview', 't' => time()]) }}" 
                                    class="{{ $btnGreen }} inline-flex items-center" 
                                    target="_blank">
                                        <i class="fas fa-eye mr-1"></i> Lihat Dokumen
                                    </a>

                                </div>

                                {{-- KONDISI 3: SIAP DIISI (STEP OPEN & VALID) --}}
                                @elseif ($isStep5Open)
                                <p class="{{ $statusClassProses }}">Siap Diisi Asesi</p>
                                <p class="text-xs text-gray-500 mb-2">Menunggu Asesi menyetujui FR.AK.01</p>

                                <div class="mt-2">
                                    <button disabled class="{{ $btnGray }} cursor-not-allowed opacity-75 inline-flex items-center">
                                        <i class="fas fa-downloadmr-1"></i> Unduh Dokumen
                                    </button>
                                </div>

                                {{-- KONDISI 4: TERKUNCI (Jika Pembayaran Batal atau Belum Sampai) --}}
                                @else
                                <p class="{{ $statusClassTerkunci }}">Terkunci</p>
                                @endif
                            </div>
                        </li>

                        {{-- ============================================= --}}
                        {{-- ITEM 6: Asesmen (Pelaksanaan) --}}
                        {{-- ============================================= --}}
                        <li class="relative flex items-start mt-1 md:mt-0 md:items-start md:pb-10">
                            @php
                            // 1. Cek Penolakan Sebelumnya
                            $isRejectedPrev = $paymentRejected || ($statusStep3 == 'ditolak');

                            // 2. Ambil Status & Hasil dari Database
                            $currentStatus = $sertifikasi->status_sertifikasi;
                            $hasilDB = $hasilAK02;

                            // 3. Definisi "SUDAH ADA HASIL"
                            // Gunakan !empty agar string kosong "" tidak dianggap sebagai hasil
                            $hasResult = !empty($hasilDB);
                            $isStatusSelesai = ($currentStatus == 'asesmen_praktek_selesai');

                            // 4. LOGIKA WAKTU
                            $jadwal = $sertifikasi->jadwal;
                            $now = \Carbon\Carbon::now();

                            $dateOnly = \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->format('Y-m-d');
                            $timeStartOnly = \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i:s');
                            $timeEndOnly = \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i:s');

                            $startDateTime = \Carbon\Carbon::parse($dateOnly . ' ' . $timeStartOnly);
                            $endDateTime = \Carbon\Carbon::parse($dateOnly . ' ' . $timeEndOnly);

                            $hasStarted = $now->greaterThanOrEqualTo($startDateTime);
                            // Variable kunci untuk permintaan Anda:
                            $hasEnded = $now->greaterThan($endDateTime);

                            // 5. Validasi Chain
                            $chainValid = ($level >= $LVL_SETUJU) && !$isRejectedPrev;

                            // 6. Status Menunggu Jadwal
                            $isWaitingSchedule = $chainValid && !$hasStarted && !$isStatusSelesai && !$hasResult;

                            // 7. Auto Fail (Waktu habis tapi belum selesai)
                            $isAutoFail = $chainValid && $hasEnded && !$isStatusSelesai && !$hasResult;

                            // 8. Tentukan Status Kompetensi
                            $isKompeten = ($hasilDB == 'kompeten');
                            $isBelumKompeten = ($hasilDB == 'belum kompeten' || $hasilDB == 'tidak_kompeten') || $isAutoFail;

                            // 9. LOGIKA ONGOING (Sedang Dikerjakan)
                            $isOngoing = $chainValid && $hasStarted && !$isStatusSelesai && !$hasResult && !$isAutoFail;

                            // 10. KUNCI UTAMA ADMIN (Variable Penentu Tombol Terbuka)
                            if ($isWaitingSchedule) {
                            $adminCanView = false;
                            } else {
                            // Jika tidak menunggu jadwal, cek apakah selesai/ada hasil/auto fail
                            $adminCanView = $isStatusSelesai || $hasResult || $isAutoFail;
                            }

                            // 11. Variable Show Modules (Modul tampil di UI secara umum)
                            $showModules = $chainValid && ($hasStarted || $isStatusSelesai || $hasResult);

                            // --- PERBAIKAN LOGIKA WARNA TALI ---
                            if ($isRejectedPrev) {
                            $lineColor = 'bg-red-500'; // Merah jika ditolak
                            } elseif ($hasEnded) {
                            // HANYA JIKA WAKTU JADWAL SUDAH SELESAI -> HIJAU
                            $lineColor = 'bg-green-500';
                            } else {
                            // Sisanya (Menunggu, Ongoing) -> Abu-abu (Biru dihapus)
                            $lineColor = 'bg-gray-200';
                            }
                            @endphp

                            {{-- Garis Timeline --}}
                            <div class="absolute left-5 top-0 -bottom-8 w-1 md:left-6 md:top-6 md:-bottom-10 md:w-0.5 {{ $lineColor }}"></div>

                            {{-- Icon Wrapper --}}
                            <div class="relative flex-shrink-0 ml-1 mr-4 mt-6 md:mt-0 md:mr-6 z-10">
                                {{-- Icon Utama --}}
                                <div class="hidden md:flex w-12 h-12 rounded-lg bg-gray-100 items-center justify-center relative">
                                    <svg class="w-6 h-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                    </svg>

                                    {{-- INDICATOR BADGE --}}
                                    @if ($isRejectedPrev)
                                    <div class="hidden md:block absolute -top-1.5 -left-1.5 z-20 bg-white rounded-full">
                                        <div class="w-5 h-5 bg-red-600 rounded-full border-2 border-white shadow-sm flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                    </div>
                                    @elseif ($hasEnded && !$isRejectedPrev)
                                    {{-- --- PERBAIKAN LOGIKA IKON --- --}}
                                    {{-- HANYA JIKA WAKTU JADWAL SUDAH SELESAI -> Centang Hijau --}}
                                    <div class="hidden md:block">{!! renderCheckmark() !!}</div>
                                    @endif
                                </div>

                                {{-- Bulat Status (Mobile) --}}
                                <div class="md:hidden flex items-center justify-center w-9 h-9 bg-white rounded-full border-4 border-gray-100 shadow-sm z-20 relative">
                                    {{-- Mobile logic: Hijau jika waktu habis, Merah jika ditolak, sisanya Abu --}}
                                    <div class="w-4 h-4 rounded-full {{ ($hasEnded && !$isRejectedPrev) ? 'bg-green-500' : ( $isRejectedPrev ? 'bg-red-500' : 'bg-gray-300' ) }}"></div>
                                </div>
                            </div>

                            {{-- CONTENT CARD --}}
                            <div class="flex-1 ml-3 md:ml-0 w-full bg-white p-6 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-300">

                                {{-- JUDUL SECTION --}}
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="text-lg font-bold {{ $isOngoing || $adminCanView ? 'text-gray-900' : 'text-gray-400' }}">
                                        Asesmen
                                    </h3>

                                    @if ($isRejectedPrev)
                                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold uppercase tracking-wide">Terhenti</span>
                                    @elseif ($isBelumKompeten)
                                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold uppercase tracking-wide">Belum Kompeten</span>
                                    @elseif ($isKompeten)
                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold uppercase tracking-wide">Kompeten</span>
                                    @elseif ($isStatusSelesai)
                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold uppercase tracking-wide">Selesai (Menunggu Hasil)</span>
                                    @elseif ($isOngoing)
                                    <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold uppercase tracking-wide">Sedang Berlangsung</span>
                                    @elseif ($isWaitingSchedule)
                                    <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-xs font-bold uppercase tracking-wide">Menunggu Jadwal</span>
                                    @else
                                    <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-500 text-xs font-bold uppercase tracking-wide">Terkunci</span>
                                    @endif
                                </div>

                                {{-- ALERT BOXES --}}
                                @if ($isRejectedPrev)
                                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-r-lg text-sm flex items-start gap-3">
                                    <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div><span class="font-bold">Proses Terhenti.</span> Terdapat penolakan pada tahap sebelumnya.</div>
                                </div>
                                @elseif ($isWaitingSchedule)
                                <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 rounded-r-lg text-sm flex items-start gap-3">
                                    <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <span class="font-bold">Jadwal Belum Dimulai.</span><br>
                                        Asesmen akan dimulai pada: <span class="font-mono font-bold">{{ $startDateTime->format('d M Y, H:i') }} WIB</span>
                                    </div>
                                </div>
                                @elseif ($isAutoFail)
                                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-r-lg text-sm flex items-start gap-3">
                                    <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div><span class="font-bold">Waktu Habis.</span> Waktu pengerjaan telah berakhir.</div>
                                </div>
                                @elseif ($isBelumKompeten)
                                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-r-lg text-sm flex items-start gap-3">
                                    <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div><span class="font-bold">Hasil Akhir: Belum Kompeten (BK).</span> Asesi dinyatakan belum kompeten.</div>
                                </div>
                                @elseif ($isKompeten)
                                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-r-lg text-sm flex items-start gap-3">
                                    <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div><span class="font-bold">Selamat! Kompeten (K).</span> Asesi telah menyelesaikan seluruh rangkaian.</div>
                                </div>
                                @endif

                                {{-- LIST TOMBOL UJIAN --}}
                                <div class="space-y-4">

                                    {{-- 1. IA.02 (Praktik) --}}
                                    @if ($showIA02)
                                    <div class="group flex flex-col sm:flex-row justify-between items-center p-4 rounded-xl border transition-all duration-200 {{ $showModules ? 'bg-white border-gray-200 hover:border-blue-300 hover:shadow-sm' : 'bg-gray-50 border-gray-100 opacity-70' }}">
                                        <div class="flex items-center gap-4 mb-3 sm:mb-0 w-full sm:w-auto flex-1">
                                            <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center {{ $showModules ? 'bg-blue-50 text-blue-600' : 'bg-gray-200 text-gray-400' }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-800 {{ !$showModules ? 'text-gray-500' : '' }}">FR.IA.02 Demonstrasi</h4>
                                                <p class="text-xs text-gray-500">Tugas Praktik & Observasi</p>
                                            </div>
                                        </div>
                                        <div class="w-full sm:w-auto flex-shrink-0">
                                            @if ($adminCanView)
                                            <a href="{{ route('admin.ia02.index', $sertifikasi->id_data_sertifikasi_asesi) }}" class="flex items-center justify-center w-full sm:w-48 h-10 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-md transition-all">Lihat Detail</a>
                                            @elseif ($isOngoing)
                                            <div class="flex items-center justify-center w-full sm:w-48 h-10 bg-blue-50 text-blue-600 text-xs font-bold rounded-lg border border-blue-200 cursor-wait">
                                                <i class="fas fa-spinner fa-spin mr-2"></i> Sedang Dikerjakan
                                            </div>
                                            @else
                                            <div class="flex items-center justify-center w-full sm:w-48 h-10 bg-gray-100 text-gray-400 text-xs font-semibold rounded-lg border border-gray-200 cursor-not-allowed">Terkunci</div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif

                                    {{-- 2. IA.05 (Pilihan Ganda) --}}
                                    @if ($showIA05)
                                    <div class="group flex flex-col sm:flex-row justify-between items-center p-4 rounded-xl border transition-all duration-200 {{ $showModules ? 'bg-white border-gray-200 hover:border-indigo-300 hover:shadow-sm' : 'bg-gray-50 border-gray-100 opacity-70' }}">
                                        <div class="flex items-center gap-4 mb-3 sm:mb-0 w-full sm:w-auto flex-1">
                                            <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center {{ $showModules ? 'bg-indigo-50 text-indigo-600' : 'bg-gray-200 text-gray-400' }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-800 {{ !$showModules ? 'text-gray-500' : '' }}">FR.IA.05 Pilihan Ganda</h4>
                                                <p class="text-xs text-gray-500">Tes Tertulis</p>
                                            </div>
                                        </div>
                                        <div class="w-full sm:w-auto flex-shrink-0">
                                            @if ($adminCanView)
                                            <a href="{{ route('ia05.asesor', ['id' => $sertifikasi->id_data_sertifikasi_asesi]) }}" class="flex items-center justify-center w-full sm:w-48 h-10 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-md transition-all">Lihat Jawaban</a>
                                            @elseif ($isOngoing)
                                            <div class="flex items-center justify-center w-full sm:w-48 h-10 bg-indigo-50 text-indigo-600 text-xs font-bold rounded-lg border border-indigo-200 cursor-wait">
                                                <i class="fas fa-spinner fa-spin mr-2"></i> Sedang Dikerjakan
                                            </div>
                                            @else
                                            <div class="flex items-center justify-center w-full sm:w-48 h-10 bg-gray-100 text-gray-400 text-xs font-semibold rounded-lg border border-gray-200 cursor-not-allowed">Terkunci</div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif

                                    {{-- 3. IA.06 (Esai) --}}
                                    @if ($showIA06)
                                    <div class="group flex flex-col sm:flex-row justify-between items-center p-4 rounded-xl border transition-all duration-200 {{ $showModules ? 'bg-white border-gray-200 hover:border-purple-300 hover:shadow-sm' : 'bg-gray-50 border-gray-100 opacity-70' }}">
                                        <div class="flex items-center gap-4 mb-3 sm:mb-0 w-full sm:w-auto flex-1">
                                            <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center {{ $showModules ? 'bg-purple-50 text-purple-600' : 'bg-gray-200 text-gray-400' }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-800 {{ !$showModules ? 'text-gray-500' : '' }}">FR.IA.06 Esai</h4>
                                                <p class="text-xs text-gray-500">Tes Tertulis Uraian</p>
                                            </div>
                                        </div>
                                        <div class="w-full sm:w-auto flex-shrink-0">
                                            @if ($adminCanView)
                                            <a href="{{ route('asesor.ia06.edit', ['id' => $sertifikasi->id_data_sertifikasi_asesi]) }}" class="flex items-center justify-center w-full sm:w-48 h-10 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-lg shadow-md transition-all">Lihat Jawaban</a>
                                            @elseif ($isOngoing)
                                            <div class="flex items-center justify-center w-full sm:w-48 h-10 bg-purple-50 text-purple-600 text-xs font-bold rounded-lg border border-purple-200 cursor-wait">
                                                <i class="fas fa-spinner fa-spin mr-2"></i> Sedang Dikerjakan
                                            </div>
                                            @else
                                            <div class="flex items-center justify-center w-full sm:w-48 h-10 bg-gray-100 text-gray-400 text-xs font-semibold rounded-lg border border-gray-200 cursor-not-allowed">Terkunci</div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif

                                    {{-- 4. IA.07 (Lisan) --}}
                                    @if ($showIA07)
                                    <div class="group flex flex-col sm:flex-row justify-between items-center p-4 rounded-xl border transition-all duration-200 {{ $showModules ? 'bg-white border-gray-200 hover:border-orange-300 hover:shadow-sm' : 'bg-gray-50 border-gray-100 opacity-70' }}">
                                        <div class="flex items-center gap-4 mb-3 sm:mb-0 w-full sm:w-auto flex-1">
                                            <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center {{ $showModules ? 'bg-orange-50 text-orange-600' : 'bg-gray-200 text-gray-400' }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-800 {{ !$showModules ? 'text-gray-500' : '' }}">FR.IA.07 Pertanyaan Lisan</h4>
                                                <p class="text-xs text-gray-500">Interview Asesor</p>
                                            </div>
                                        </div>
                                        <div class="w-full sm:w-auto flex-shrink-0">
                                            @if ($adminCanView)
                                            <a href="{{ route('admin.ia07.index', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}" class="flex items-center justify-center w-full sm:w-48 h-10 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-lg shadow-md transition-all">Lihat Hasil</a>
                                            @else
                                            {{-- SAAT ONGOING, LISAN TETAP TERKUNCI --}}
                                            <div class="flex items-center justify-center w-full sm:w-48 h-10 bg-gray-100 text-gray-400 text-xs font-semibold rounded-lg border border-gray-200 cursor-not-allowed">Terkunci</div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif

                                    {{-- 5. IA.09 (Wawancara) --}}
                                    @if ($showIA09)
                                    <div class="group flex flex-col sm:flex-row justify-between items-center p-4 rounded-xl border transition-all duration-200 {{ $showModules ? 'bg-white border-gray-200 hover:border-red-300 hover:shadow-sm' : 'bg-gray-50 border-gray-100 opacity-70' }}">
                                        <div class="flex items-center gap-4 mb-3 sm:mb-0 w-full sm:w-auto flex-1">
                                            <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center {{ $showModules ? 'bg-red-50 text-red-600' : 'bg-gray-200 text-gray-400' }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-800 {{ !$showModules ? 'text-gray-500' : '' }}">FR.IA.09 Wawancara</h4>
                                                <p class="text-xs text-gray-500">Verifikasi Portofolio</p>
                                            </div>
                                        </div>
                                        <div class="w-full sm:w-auto flex-shrink-0">
                                            @if ($adminCanView)
                                            <a href="{{ route('admin.asesmen.fr_ia_09.index', $sertifikasi->id_data_sertifikasi_asesi) }}" 
                                                class="flex items-center justify-center w-full sm:w-48 h-10 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Lihat Hasil
                                            </a>
                                            @else
                                            {{-- SAAT ONGOING, WAWANCARA TETAP TERKUNCI --}}
                                            <div class="flex items-center justify-center w-full sm:w-48 h-10 bg-gray-100 text-gray-400 text-xs font-semibold rounded-lg border border-gray-200 cursor-not-allowed">Terkunci</div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif

                                </div>

                            </div>
                        </li>

                        {{-- ============================================= --}}
                        {{-- ITEM 7: Umpan Balik Asesor --}}
                        {{-- ============================================= --}}
                        <li class="relative flex items-center md:items-start md:pb-10">
                            @php
                            // 1. Cek Penolakan
                            $isRejectedPrev = $paymentRejected || ($statusStep3 == 'ditolak');

                            // 2. Chain Valid
                            $chainValid = $paymentVerified && ($statusStep3 == 'selesai');

                            // 3. Status DB
                            $statusDB = $sertifikasi->status_sertifikasi;

                            // 4. Logika Waktu (Sama seperti Item 6)
                            $jadwal = $sertifikasi->jadwal;
                            $now = \Carbon\Carbon::now();
                            $dateOnly = \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->format('Y-m-d');
                            $timeEndOnly = \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i:s');
                            $endDateTime = \Carbon\Carbon::parse($dateOnly . ' ' . $timeEndOnly);
                            $hasEnded = $now->greaterThan($endDateTime);

                            // --- PERBAIKAN DI SINI ---
                            // Selesai jika: Status DB spesifik 'umpan_balik_selesai' ATAU sudah masuk tahap rekomendasi/terbit sertifikat
                            $isFinished = ($statusDB == 'umpan_balik_selesai' || $level >= $LVL_REKOMENDASI) && $chainValid;

                            // Menunggu: Status 'asesmen_praktek_selesai' DAN Waktu Habis
                            $isWaitingAsesi = ($statusDB == 'asesmen_praktek_selesai') && !$isRejectedPrev && $chainValid && $hasEnded;

                            // Terbuka
                            $isOpen = $isWaitingAsesi || $isFinished;

                            // Warna Tali
                            if ($isRejectedPrev) {
                            $lineColor = 'bg-red-500';
                            } elseif ($isFinished) {
                            $lineColor = 'bg-green-500';
                            } else {
                            $lineColor = 'bg-gray-200';
                            }

                            // Visual
                            $iconColorClass = 'text-gray-500';
                            $titleClass = $isOpen ? $titleClassEnabled : $titleClassDisabled;
                            @endphp

                            {{-- Garis Timeline --}}
                            <div class="absolute left-5 top-0 -bottom-8 w-1 md:left-6 md:top-6 md:-bottom-10 md:w-0.5 {{ $lineColor }}"></div>

                            <div class="relative flex-shrink-0 ml-1 mr-4 md:mr-6 z-10">
                                {{-- Icon Wrapper --}}
                                <div class="hidden md:flex w-12 h-12 rounded-lg items-center justify-center relative bg-gray-100">
                                    <svg class="w-6 h-6 {{ $iconColorClass }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>

                                    @if ($isRejectedPrev)
                                    <div class="hidden md:block absolute -top-1.5 -left-1.5 z-20 bg-white rounded-full">
                                        <div class="w-5 h-5 bg-red-600 rounded-full border-2 border-white shadow-sm flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                    </div>
                                    @elseif ($isFinished)
                                    <div class="hidden md:block">{!! renderCheckmark() !!}</div>
                                    @endif
                                </div>

                                {{-- Bulatan Mobile --}}
                                <div class="md:hidden flex items-center justify-center w-9 h-9 bg-white rounded-full border-4 border-gray-100 shadow-sm z-20 relative">
                                    <div class="w-4 h-4 rounded-full {{ $isFinished ? 'bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.6)]' : ($isRejectedPrev ? 'bg-red-500' : ($isWaitingAsesi ? 'bg-blue-500' : 'bg-gray-300')) }}"></div>
                                </div>
                            </div>

                            <div class="{{ $responsiveCardClass }}">
                                <h3 class="{{ $titleClass }}">Umpan Balik Asesor</h3>

                                @if ($isRejectedPrev)
                                <p class="{{ $statusClassTerkunci }}">Terkunci</p>
                                @elseif ($isFinished)
                                <p class="{{ $statusClassSelesai }}">Selesai diisi Asesi</p>
                                @elseif ($isWaitingAsesi)
                                <p class="{{ $statusClassTunggu }}">Menunggu Asesi Mengisi</p>
                                @else
                                <p class="{{ $statusClassTerkunci }}">Terkunci</p>
                                @endif
                            </div>
                        </li>

                        {{-- ============================================= --}}
                        {{-- ITEM 8: Pengajuan Banding Asesmen --}}
                        {{-- ============================================= --}}
                        <li class="relative flex items-center md:items-start md:pb-10">
                            @php
                            // 1. Cek Penolakan
                            $isRejectedPrev = $paymentRejected || ($statusStep3 == 'ditolak');

                            // 2. Chain Validation
                            $chainValid = $paymentVerified && ($statusStep3 == 'selesai');

                            // 3. Ambil Status DB
                            $statusDB = $sertifikasi->status_sertifikasi;

                            // --- PERBAIKAN DI SINI ---
                            // Selesai jika: Status 'banding_selesai' ATAU sudah masuk tahap rekomendasi (level >= 6)
                            $isFinished = ($statusDB == 'banding_selesai' || $level >= $LVL_REKOMENDASI) && $chainValid;

                            // Opsional terbuka saat umpan balik selesai, tapi belum finish/reject
                            // Note: Jika level sudah rekomendasi, $isFinished true, jadi $isOptional tidak akan tereksekusi (aman)
                            $isOptional = ($statusDB == 'umpan_balik_selesai') && !$isFinished && !$isRejectedPrev && $chainValid;

                            // Terbuka
                            $isOpen = $isOptional || $isFinished;

                            // Warna Tali
                            if ($isRejectedPrev) {
                            $lineColor = 'bg-red-500';
                            } elseif ($isOpen) {
                            // Jika terbuka (baik selesai atau sedang masa banding), tali hijau
                            // Atau Anda bisa ubah logika ini jika ingin tali abu-abu saat opsional
                            $lineColor = 'bg-green-500';
                            } else {
                            $lineColor = 'bg-gray-200';
                            }

                            // Visual
                            $titleClass = $isOpen ? $titleClassEnabled : $titleClassDisabled;
                            @endphp

                            {{-- Garis Timeline --}}
                            <div class="absolute left-5 top-0 -bottom-8 w-1 md:left-6 md:top-6 md:-bottom-10 md:w-0.5 {{ $lineColor }}"></div>

                            <div class="relative flex-shrink-0 ml-1 mr-4 md:mr-6 z-10">
                                {{-- KOTAK ICON --}}
                                <div class="hidden md:flex w-12 h-12 rounded-lg items-center justify-center relative bg-gray-100">
                                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z" />
                                    </svg>

                                    @if ($isRejectedPrev)
                                    <div class="hidden md:block absolute -top-1.5 -left-1.5 z-20 bg-white rounded-full">
                                        <div class="w-5 h-5 bg-red-600 rounded-full border-2 border-white shadow-sm flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                    </div>
                                    @elseif ($isFinished)
                                    <div class="hidden md:block">{!! renderCheckmark() !!}</div>
                                    @endif
                                </div>

                                {{-- Bulatan Mobile --}}
                                <div class="md:hidden flex items-center justify-center w-9 h-9 bg-white rounded-full border-4 border-gray-100 shadow-sm z-20 relative">
                                    <div class="w-4 h-4 rounded-full {{ $isFinished ? 'bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.6)]' : ($isRejectedPrev ? 'bg-red-500' : ($isOptional ? 'bg-blue-500' : 'bg-gray-300')) }}"></div>
                                </div>
                            </div>

                            <div class="{{ $responsiveCardClass }}">
                                <h3 class="{{ $titleClass }}">Pengajuan Banding Asesmen</h3>

                                @if ($isRejectedPrev)
                                <p class="{{ $statusClassTerkunci }}">Terkunci</p>

                                @elseif ($isFinished)
                                <p class="{{ $statusClassSelesai }}">Banding Selesai</p>

                                @elseif ($isOptional)
                                <p class="{{ $statusClassProses }}">Opsional Diisi Asesi</p>

                                @else
                                <p class="{{ $statusClassTerkunci }}">Terkunci</p>
                                @endif
                            </div>
                        </li>

                        {{-- ============================================= --}}
                        {{-- ITEM 9: Keputusan Komite --}}
                        {{-- ============================================= --}}
                        <li class="relative flex items-center md:items-start" x-data>
                            @php
                            // 1. Ambil Status & File
                            $statusFinal = $sertifikasi->status_sertifikasi;
                            $fileSertifikat = $sertifikasi->sertifikat;

                            // 2. Boolean Logic
                            $isLolos = ($statusFinal == 'direkomendasikan');
                            $isGagal = ($statusFinal == 'tidak_direkomendasikan');
                            $hasFile = !empty($fileSertifikat);

                            // 3. Menunggu Keputusan
                            $isWaiting = !$isLolos && !$isGagal;
                            @endphp

                            {{-- BAGIAN KIRI: ICON & TIMELINE --}}
                            <div class="relative flex-shrink-0 ml-1 mr-4 md:mr-6 z-10">

                                {{-- ICON SVG (Statis Abu-abu) --}}
                                <div class="hidden md:flex w-12 h-12 rounded-lg items-center justify-center bg-gray-100">
                                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.462 48.462 0 0 0 12 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c.317.053.626.111.928.174m-15.356 0c.317.053.626.111.928.174m13.5 0L12 12m0 0L6.25 4.97M12 12v8.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M12 12h8.25m-8.25 0H3.75" />
                                    </svg>
                                </div>

                                {{-- BULATAN MOBILE --}}
                                <div class="md:hidden flex items-center justify-center w-9 h-9 bg-white rounded-full border-4 border-gray-100 shadow-sm z-20 relative">
                                    <div class="w-4 h-4 rounded-full {{ $isLolos ? 'bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.6)]' : ($isGagal ? 'bg-red-500' : 'bg-gray-300') }}"></div>
                                </div>

                                {{-- BADGE INDICATOR (Desktop) --}}
                                @if ($isGagal)
                                <div class="hidden md:block absolute -top-1.5 -left-1.5 z-20 bg-white rounded-full">
                                    <div class="w-5 h-5 bg-red-600 rounded-full border-2 border-white shadow-sm flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                </div>
                                @elseif ($isLolos)
                                <div class="hidden md:block">{!! renderCheckmark() !!}</div>
                                @endif
                            </div>

                            {{-- BAGIAN KANAN: KONTEN KARTU --}}
                            <div class="{{ $responsiveCardClass }}">
                                <h3 class="{{ ($isLolos || $isGagal) ? $titleClassEnabled : $titleClassDisabled }}">Keputusan Komite</h3>

                                @if ($isLolos)
                                {{-- 1. SUKSES --}}
                                <span class="{{ $statusClassSelesai }}">Kompeten - Direkomendasikan Menerima</span>

                                {{-- Tambahkan state 'isUploading' di x-data --}}
                                <div class="flex flex-wrap gap-2 items-center" x-data="{ isUploading: false }">

                                    {{-- [TOMBOL 1] LIHAT SERTIFIKAT (KUNING) --}}
                                    @if($hasFile)
                                        <a href="{{ route('admin.sertifikasi.download', ['id_asesi' => $asesi->id_asesi, 'id' => $sertifikasi->id_data_sertifikasi_asesi, 'mode' => 'preview', 't' => time()]) }}"
                                        target="_blank"
                                        class="{{ $btnYellow }} inline-flex items-center justify-center">
                                            <i class="fas fa-certificate mr-2"></i> Sertifikat
                                        </a>
                                    @endif

                                    {{-- [TOMBOL 2] FORM UPLOAD --}}
                                    {{-- Tambahkan ID unik pada form: id="formUploadSertifikat" --}}
                                    <form id="formUploadSertifikat"
                                        action="{{ route('admin.sertifikasi.upload', ['id_asesi' => $asesi->id_asesi, 'id' => $sertifikasi->id_data_sertifikasi_asesi]) }}" 
                                        method="POST" 
                                        enctype="multipart/form-data">
                                        @csrf
                                        
                                        {{-- INPUT FILE --}}
                                        {{-- LOGIC UBAH: --}}
                                        {{-- 1. Set isUploading = true --}}
                                        {{-- 2. Paksa submit via document.getElementById --}}
                                        <input type="file" 
                                            name="sertifikat" 
                                            x-ref="fileInput" 
                                            style="display:none" 
                                            accept="application/pdf"
                                            @change="isUploading = true; document.getElementById('formUploadSertifikat').submit();">

                                        {{-- TOMBOL TRIGGER --}}
                                        <button type="button"
                                                @click="$refs.fileInput.click()"
                                                :disabled="isUploading" 
                                                class="{{ $btnBlue }} inline-flex items-center justify-center transition disabled:opacity-70 disabled:cursor-wait">
                                            
                                            {{-- TAMPILAN NORMAL --}}
                                            <span x-show="!isUploading" class="inline-flex items-center">
                                                <i class="fas {{ $hasFile ? 'fa-sync-alt' : 'fa-upload' }} mr-2"></i>
                                                {{ $hasFile ? 'Ganti Sertifikat' : 'Upload Sertifikat' }}
                                            </span>

                                            {{-- TAMPILAN LOADING --}}
                                            <span x-show="isUploading" class="inline-flex items-center" style="display: none;">
                                                <i class="fas fa-circle-notch fa-spin mr-2"></i>
                                                Mengunggah...
                                            </span>
                                        </button>
                                    </form>

                                    {{-- [TOMBOL 3] UNDUH (HIJAU) --}}
                                    @if($hasFile)
                                        <a href="{{ route('admin.sertifikasi.download', ['id_asesi' => $asesi->id_asesi, 'id' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                                        target="_blank"
                                        class="{{ $btnGreen }} inline-flex items-center justify-center">
                                            <i class="fas fa-download mr-2"></i> Unduh
                                        </a>
                                    @endif

                                </div>

                                {{-- NOTIFIKASI TOAST (FLOATING) --}}
                                {{-- Wadah Fixed di pojok kanan bawah --}}
                                <div class="fixed bottom-5 right-5 z-50 flex flex-col gap-3 w-full max-w-sm pointer-events-none">

                                    {{-- 1. VALIDATION ERRORS (List) --}}
                                    @if($errors->any())
                                        <div x-data="{ show: true }" 
                                            x-init="setTimeout(() => show = false, 5000)" 
                                            x-show="show"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 translate-y-2"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            x-transition:leave="transition ease-in duration-300"
                                            x-transition:leave-start="opacity-100 translate-y-0"
                                            x-transition:leave-end="opacity-0 translate-y-2"
                                            class="pointer-events-auto bg-white border-l-4 border-red-500 shadow-lg rounded-r-lg p-4 flex items-start gap-3">
                                            
                                            {{-- Icon --}}
                                            <div class="text-red-500 mt-0.5">
                                                <i class="fas fa-exclamation-circle text-xl"></i>
                                            </div>
                                            
                                            {{-- Content --}}
                                            <div class="flex-1">
                                                <h3 class="font-bold text-red-600 text-sm">Terjadi Kesalahan</h3>
                                                <ul class="mt-1 text-sm text-gray-600 list-disc list-inside">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>

                                            {{-- Close Button --}}
                                            <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @endif

                                    {{-- 2. SESSION ERROR (Single) --}}
                                    @if(session('error'))
                                        <div x-data="{ show: true }" 
                                            x-init="setTimeout(() => show = false, 5000)" 
                                            x-show="show"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 translate-y-2"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            x-transition:leave="transition ease-in duration-300"
                                            x-transition:leave-start="opacity-100 translate-y-0"
                                            x-transition:leave-end="opacity-0 translate-y-2"
                                            class="pointer-events-auto bg-white border-l-4 border-red-500 shadow-lg rounded-r-lg p-4 flex items-center gap-3">
                                            
                                            <div class="text-red-500">
                                                <i class="fas fa-times-circle text-xl"></i>
                                            </div>
                                            
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-800">{{ session('error') }}</p>
                                            </div>

                                            <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @endif

                                    {{-- 3. SESSION SUCCESS (Single) --}}
                                    @if(session('success'))
                                        <div x-data="{ show: true }" 
                                            x-init="setTimeout(() => show = false, 5000)" 
                                            x-show="show"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 translate-y-2"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            x-transition:leave="transition ease-in duration-300"
                                            x-transition:leave-start="opacity-100 translate-y-0"
                                            x-transition:leave-end="opacity-0 translate-y-2"
                                            class="pointer-events-auto bg-white border-l-4 border-green-500 shadow-lg rounded-r-lg p-4 flex items-center gap-3">
                                            
                                            <div class="text-green-500">
                                                <i class="fas fa-check-circle text-xl"></i>
                                            </div>
                                            
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-800">{{ session('success') }}</p>
                                            </div>

                                            <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @endif

                                </div>

                                @elseif($isGagal)
                                {{-- 2. GAGAL --}}
                                <div class="mt-2">
                                    <p class="text-xs text-red-600 font-semibold">Belum Kompeten - Tidak Direkomendasikan</p>
                                </div>

                                @else
                                {{-- 3. MENUNGGU --}}
                                <p class="{{ $statusClassTunggu }}">Menunggu Keputusan</p>
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