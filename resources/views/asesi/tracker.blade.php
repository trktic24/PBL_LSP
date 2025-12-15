@use('App\Models\DataSertifikasiAsesi')

@php
    // --- Variabel Level Real ---
    $level = $sertifikasi->progres_level;

    // --- DEFINISI LEVEL ---
    $LVL_DAFTAR_SELESAI = 10;
    $LVL_TUNGGU_BAYAR = 20;
    $LVL_LUNAS = 30;
    $LVL_PRA_ASESMEN = 40;
    $LVL_SETUJU = 50;
    $LVL_ASESMEN = 70;
    $LVL_UMPAN_BALIK = 80;
    $LVL_BANDING = 90;
    $LVL_REKOMENDASI = 100;

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
    $linkClassEnabled = 'text-lg font-semibold text-gray-900 hover:text-blue-600 cursor-pointer';
    $linkClassDisabled = 'text-lg font-semibold text-gray-400 cursor-not-allowed';

    // CONTAINER KARTU (Responsive: Box di Mobile, Transparent di Desktop)
    // UPDATE: ml-6 diubah jadi ml-3 biar konten geser ke kiri (lebih dekat ke garis)
    $responsiveCardClass =
        'flex-1 ml-3 md:ml-0 bg-white p-5 rounded-2xl shadow-[0_4px_20px_-5px_rgba(0,0,0,0.1)] border border-gray-100 md:bg-transparent md:p-0 md:rounded-none md:shadow-none md:border-0 relative z-10';

    // STATUS COLORS
    $statusClassSelesai = 'text-xs text-green-600 font-medium';
    $statusClassProses = 'text-xs text-blue-600 font-medium';
    $statusClassTunggu = 'text-xs text-yellow-600 font-medium';
    $statusClassTerkunci = 'text-xs text-gray-400 font-medium';

    // BUTTON STYLES
    $btnBase = 'mt-2 px-4 py-1.5 text-xs font-semibold rounded-md inline-block transition-all';
    $btnBlue = "$btnBase bg-blue-500 text-white hover:bg-blue-600 shadow-blue-100 hover:shadow-lg";
    $btnGray = "$btnBase bg-gray-300 text-gray-500 cursor-not-allowed";
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skema Sertifikat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 font-sans">

    <div class="flex min-h-screen flex-col md:flex-row md:h-screen md:overflow-hidden">

        @if ($sertifikasi)
            {{-- 1. SIDEBAR (Desktop Only) --}}
            <div class="hidden md:block md:w-80 flex-shrink-0">
                <x-sidebar :idAsesi="$sertifikasi->id_asesi" :sertifikasi="$sertifikasi" backUrl="/" />
            </div>

            {{-- 2. HEADER MOBILE (Data Dinamis) --}}
            @php
                $gambarSkema =
                    $sertifikasi->jadwal && $sertifikasi->jadwal->skema && $sertifikasi->jadwal->skema->gambar
                        ? asset('images/' . $sertifikasi->jadwal->skema->gambar)
                        : null;
            @endphp

            <x-mobile_header :title="$sertifikasi->jadwal->skema->nama_skema ?? 'Skema Sertifikasi'" :code="$sertifikasi->jadwal->skema->kode_unit ?? ($sertifikasi->jadwal->skema->nomor_skema ?? '-')" :name="$sertifikasi->asesi->nama_lengkap ?? 'Nama Peserta'" :image="$gambarSkema" />

            {{-- 3. MAIN CONTENT --}}
            <main class="flex-1 w-full relative md:p-10 md:overflow-y-auto">
                <div
                    class="max-w-3xl mx-auto mt-14 md:mt-0 p-6 md:p-12 md:bg-white md:rounded-2xl md:shadow-xl transition-all">

                    {{-- List items --}}
                    <ol class="relative z-10 space-y-6 md:space-y-0">

                        {{-- ============================================= --}}
                        {{-- ITEM 1: Formulir --}}
                        {{-- ============================================= --}}
                        {{-- UPDATE: pl-4 dihapus biar sejajar dengan item bawahnya --}}
                        <li class="relative flex items-center md:items-start md:pb-10">
                            {{-- GARIS: Mobile left-5 (geser kanan dikit) | Desktop -bottom-10 (Nyambung) --}}
                            <div
                                class="absolute left-5 top-0 -bottom-8 w-1 md:left-6 md:top-6 md:-bottom-10 md:w-0.5 
                                {{ $level >= $LVL_DAFTAR_SELESAI ? 'bg-green-500' : 'bg-gray-200' }}">
                            </div>

                            <div class="relative flex-shrink-0 ml-1 mr-4 md:mr-6 z-10">
                                <div
                                    class="hidden md:flex w-12 h-12 rounded-lg bg-gray-100 items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <div
                                    class="md:hidden flex items-center justify-center w-9 h-9 bg-white rounded-full border-4 border-gray-100 shadow-sm z-20 relative">
                                    @if ($level >= $LVL_DAFTAR_SELESAI)
                                        <div
                                            class="w-4 h-4 left-1 bg-green-500 rounded-full shadow-[0_0_10px_rgba(34,197,94,0.6)]">
                                        </div>
                                    @else
                                        <div class="w-4 h-4 bg-gray-300 rounded-full"></div>
                                    @endif
                                </div>
                                @if ($level >= $LVL_DAFTAR_SELESAI && $unlockAPL02)
                                    <div class="hidden md:block">{!! renderCheckmark() !!}</div>
                                @endif
                            </div>

                            <div class="{{ $responsiveCardClass }}">
                                {{-- LOGIC GANTI LINK --}}
                                @php
                                    // Cek apakah user sudah selesai daftar (sudah TTD)
                                    $isSelesai = $sertifikasi->progres_level >= $LVL_DAFTAR_SELESAI;

                                    // Jika selesai, arahkan ke halaman berhasil. Jika belum, ke form input.
                                    $targetUrl = $isSelesai
                                        ? route('asesi.pendaftaran.selesai', [
                                            'id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi,
                                        ])
                                        : route('asesi.data.sertifikasi', [
                                            'id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi,
                                        ]);
                                @endphp

                                <a href="{{ $targetUrl }}" class="{{ $linkClassEnabled }}">
                                    Formulir Pendaftaran Sertifikasi
                                </a>

                                <p class="text-sm text-gray-500">{{ $sertifikasi->tanggal_daftar->format('l, d F Y') }}
                                </p>

                                @if ($isSelesai)
                                    <p class="{{ $statusClassSelesai }}">Selesai</p>
                                    <a href="{{ route('asesi.cetak.apl01', ['id_data_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                                        target="_blank" class="{{ $btnBlue }}">
                                        Unduh Document
                                    </a>
                                @else
                                    <p class="{{ $statusClassProses }}">Silahkan Mengisi...</p>
                                    <button disabled class="{{ $btnGray }}">
                                        Unduh Document (Selesaikan Pendaftaran)
                                    </button>
                                @endif
                            </div>
                        </li>

                        {{-- ============================================= --}}
                        {{-- ITEM 2: Pembayaran --}}
                        {{-- ============================================= --}}
                        <li class="relative flex items-center md:items-start md:pb-10">
                            {{-- UPDATE: left-4 jadi left-5 --}}
                            <div
                                class="absolute left-5 top-0 -bottom-8 w-1 md:left-6 md:top-6 md:-bottom-10 md:w-0.5 
                                {{ $level >= $LVL_LUNAS ? 'bg-green-500' : 'bg-gray-200' }}">
                            </div>

                            <div class="relative flex-shrink-0 ml-1 mr-4 md:mr-6 z-10">
                                <div
                                    class="hidden md:flex w-12 h-12 rounded-lg bg-gray-100 items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h6m3-3.75l-3 3m0 0l-3-3m3 3V15m6-1.5h.008v.008H18V13.5z" />
                                    </svg>
                                </div>
                                <div
                                    class="md:hidden flex items-center justify-center w-9 h-9 bg-white rounded-full border-4 border-gray-100 shadow-sm z-20 relative">
                                    @if ($level >= $LVL_LUNAS)
                                        <div
                                            class="w-4 h-4 bg-green-500 rounded-full shadow-[0_0_10px_rgba(34,197,94,0.6)]">
                                        </div>
                                    @else
                                        <div class="w-4 h-4 bg-gray-300 rounded-full"></div>
                                    @endif
                                </div>
                                @if ($level >= $LVL_LUNAS && $unlockAPL02)
                                    <div class="hidden md:block">{!! renderCheckmark() !!}</div>
                                @endif
                            </div>

                            <div class="{{ $responsiveCardClass }}">
                                @if ($level >= $LVL_DAFTAR_SELESAI)
                                    <a href="{{ route('asesi.payment.create', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                                        class="{{ $linkClassEnabled }}">Pembayaran</a>
                                @else
                                    <span class="{{ $linkClassDisabled }}">Pembayaran</span>
                                @endif

                                @if ($level >= $LVL_LUNAS)
                                    <p class="{{ $statusClassSelesai }}">Lunas</p>
                                    <a href="{{ route('asesi.payment.invoice', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                                        target="_blank"
                                        class="{{ $btnBlue }} inline-flex items-center justify-center text-center">
                                        {{-- ... icon ... --}}
                                        Unduh Invoice
                                    </a>
                                @elseif ($level == $LVL_TUNGGU_BAYAR)
                                    <p class="{{ $statusClassTunggu }}">Menunggu Verifikasi</p>
                                @elseif ($level == $LVL_DAFTAR_SELESAI)
                                    <p class="{{ $statusClassTunggu }}">Silahkan Lakukan Pembayaran</p>
                                @else
                                    <p class="{{ $statusClassTerkunci }}">Terkunci</p>
                                @endif
                            </div>
                        </li>

                        {{-- ============================================= --}}
                        {{-- ITEM 3: Pra-Asesmen --}}
                        {{-- ============================================= --}}
                        <li class="relative flex items-center md:items-start md:pb-10">
                            {{-- UPDATE: left-4 jadi left-5 --}}
                            <div
                                class="absolute left-5 top-0 -bottom-8 w-1 md:left-6 md:top-6 md:-bottom-10 md:w-0.5 
                                {{ $level >= $LVL_PRA_ASESMEN ? 'bg-green-500' : 'bg-gray-200' }}">
                            </div>

                            <div class="relative flex-shrink-0 ml-1 mr-4 md:mr-6 z-10">
                                <div
                                    class="hidden md:flex w-12 h-12 rounded-lg bg-gray-100 items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.122 2.122l7.81-7.81" />
                                    </svg>
                                </div>
                                <div
                                    class="md:hidden flex items-center justify-center w-9 h-9 bg-white rounded-full border-4 border-gray-100 shadow-sm z-20 relative">
                                    @if ($level >= $LVL_PRA_ASESMEN)
                                        <div
                                            class="w-4 h-4 bg-green-500 rounded-full shadow-[0_0_10px_rgba(34,197,94,0.6)]">
                                        </div>
                                    @else
                                        <div class="w-4 h-4 bg-gray-300 rounded-full"></div>
                                    @endif
                                </div>
                                @if ($level >= $LVL_PRA_ASESMEN && $unlockAK01)
                                    <div class="hidden md:block">{!! renderCheckmark() !!}</div>
                                @endif
                            </div>

                            <div class="{{ $responsiveCardClass }}">

                                @php
                                    // Kita buat variabelnya dulu biar gak Undefined Variable
                                    $isPraAsesmenSelesai = $level >= $LVL_PRA_ASESMEN;
                                    $isPraAsesmenOpen = $level >= $LVL_LUNAS && $unlockAPL02;
                                @endphp

                                @if ($isPraAsesmenSelesai)
                                    {{-- PERBAIKAN: Kirim parameter id_sertifikasi --}}
                                    <a href="{{ route('asesi.pra_asesmen.selesai', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                                        class="{{ $linkClassEnabled }}">
                                        Pra-Asesmen
                                    </a>
                                @elseif ($isPraAsesmenOpen)
                                    {{-- Link ke Form Input (tetap) --}}
                                    <a href="{{ route('asesi.apl02.view', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                                        class="{{ $linkClassEnabled }}">
                                        Pra-Asesmen
                                    </a>
                                @else
                                    <span class="{{ $linkClassDisabled }}">Pra-Asesmen</span>
                                @endif

                                @if ($level >= $LVL_PRA_ASESMEN)
                                    <p class="{{ $statusClassSelesai }}">Selesai</p>
                                    <a href="{{ route('asesi.cetak.apl02', $sertifikasi->id_data_sertifikasi_asesi) }}"
                                        class="{{ $btnBlue }}" target="_blank"> Unduh Document
                                    </a>
                                @elseif ($level == $LVL_LUNAS && $unlockAPL02)
                                    <p class="{{ $statusClassProses }}">Siap Diisi</p>
                                @elseif ($level == $LVL_LUNAS)
                                    <p class="{{ $statusClassProses }}">Menunggu Verifikasi Admin</p>
                                @else
                                    <p class="{{ $statusClassTerkunci }}">Terkunci</p>
                                @endif
                            </div>
                        </li>

                        {{-- ============================================= --}}
                        {{-- ITEM 4: Jadwal TUK --}}
                        {{-- ============================================= --}}
                        <li class="relative flex items-center md:items-start md:pb-10">
                            {{-- UPDATE: left-4 jadi left-5 --}}
                            <div
                                class="absolute left-5 top-0 -bottom-8 w-1 md:left-6 md:top-6 md:-bottom-10 md:w-0.5 
                                {{ $level >= $LVL_PRA_ASESMEN ? 'bg-green-500' : 'bg-gray-200' }}">
                            </div>

                            <div class="relative flex-shrink-0 ml-1 mr-4 md:mr-6 z-10">
                                <div
                                    class="hidden md:flex w-12 h-12 rounded-lg bg-gray-100 items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h6.75M9 11.25h6.75M9 15.75h6.75M9 20.25h6.75" />
                                    </svg>
                                </div>
                                <div
                                    class="md:hidden flex items-center justify-center w-9 h-9 bg-white rounded-full border-4 border-gray-100 shadow-sm z-20 relative">
                                    @if ($level >= $LVL_PRA_ASESMEN)
                                        <div
                                            class="w-4 h-4 bg-green-500 rounded-full shadow-[0_0_10px_rgba(34,197,94,0.6)]">
                                        </div>
                                    @else
                                        <div class="w-4 h-4 bg-gray-300 rounded-full"></div>
                                    @endif
                                </div>
                                @if ($level >= $LVL_PRA_ASESMEN && $unlockAK01)
                                    <div class="hidden md:block">{!! renderCheckmark() !!}</div>
                                @endif
                            </div>

                            <div class="{{ $responsiveCardClass }}">
                                @if ($level >= $LVL_PRA_ASESMEN && $unlockAK01)
                                    <a href="{{ route('asesi.show.jadwal_tuk', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                                        class="{{ $linkClassEnabled }}">Jadwal dan TUK</a>
                                @else
                                    <span class="{{ $linkClassDisabled }}">Jadwal dan TUK</span>
                                @endif
                                <p class="text-sm text-gray-500">Dilakukan oleh Admin</p>
                                @if ($level >= $LVL_PRA_ASESMEN && $unlockAK01)
                                    <a href="{{ route('asesi.pdf.kartu_peserta', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                                        class="{{ $btnBlue }}" target="_blank">
                                        Unduh Dokumen
                                    </a>
                                    <p class="{{ $statusClassSelesai }}">Terverifikasi</p>
                                @else
                                    <p class="{{ $statusClassTerkunci }}">Menunggu Verifikasi Pra-Asesmen</p>
                                @endif
                            </div>
                        </li>

                        {{-- ============================================= --}}
                        {{-- ITEM 5: Persetujuan --}}
                        {{-- ============================================= --}}
                        <li class="relative flex items-center md:items-start md:pb-10">
                            {{-- UPDATE: left-4 jadi left-5 --}}
                            <div
                                class="absolute left-5 top-0 -bottom-8 w-1 md:left-6 md:top-6 md:-bottom-10 md:w-0.5 
                                {{ $level >= $LVL_SETUJU ? 'bg-green-500' : 'bg-gray-200' }}">
                            </div>

                            <div class="relative flex-shrink-0 ml-1 mr-4 md:mr-6 z-10">
                                <div
                                    class="hidden md:flex w-12 h-12 rounded-lg bg-gray-100 items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                                    </svg>
                                </div>
                                <div
                                    class="md:hidden flex items-center justify-center w-9 h-9 bg-white rounded-full border-4 border-gray-100 shadow-sm z-20 relative">
                                    @if ($level >= $LVL_SETUJU)
                                        <div
                                            class="w-4 h-4 bg-green-500 rounded-full shadow-[0_0_10px_rgba(34,197,94,0.6)]">
                                        </div>
                                    @else
                                        <div class="w-4 h-4 bg-gray-300 rounded-full"></div>
                                    @endif
                                </div>
                                @if ($level >= $LVL_SETUJU)
                                    <div class="hidden md:block">{!! renderCheckmark() !!}</div>
                                @endif
                            </div>

                            <div class="{{ $responsiveCardClass }}">
                                @if ($level >= $LVL_PRA_ASESMEN && $unlockAK01)
                                    <a href="{{ route('asesi.kerahasiaan.fr_ak01', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                                        class="{{ $linkClassEnabled }}">
                                        Persetujuan Asesmen dan Kerahasiaan
                                    </a>
                                @else
                                    <span class="{{ $linkClassDisabled }}">Persetujuan Asesmen dan Kerahasiaan</span>
                                @endif

                                @if ($level >= $LVL_SETUJU)
                                    <p class="{{ $statusClassSelesai }}">Telah Disetujui</p>
                                    <a href="{{ route('asesi.cetak.ak01', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                                        class="{{ $btnBlue }}" target="_blank">
                                        Unduh Dokumen
                                    </a>
                                @elseif ($level == $LVL_PRA_ASESMEN && $unlockAK01)
                                    <p class="{{ $statusClassProses }}">Siap Diisi</p>
                                @elseif ($level == $LVL_PRA_ASESMEN)
                                    <p class="{{ $statusClassTerkunci }}">Menunggu Verifikasi Pra-Asesmen</p>
                                @else
                                    <p class="{{ $statusClassTerkunci }}">Terkunci</p>
                                @endif
                            </div>
                        </li>

                        {{-- ============================================= --}}
                        {{-- ITEM 6: Asesmen --}}
                        {{-- ============================================= --}}
                        <li class="relative flex items-center md:items-start md:pb-10">
                            {{-- Garis Timeline --}}
                            <div
                                class="absolute left-5 top-0 -bottom-8 w-1 md:left-6 md:top-6 md:-bottom-10 md:w-0.5 {{ $unlockAsesmen ? 'bg-green-500' : 'bg-gray-200' }}">
                            </div>

                            {{-- Icon Timeline --}}
                            <div class="relative flex-shrink-0 ml-1 mr-4 md:mr-6 z-10">
                                <div
                                    class="hidden md:flex w-12 h-12 rounded-lg {{ $unlockAsesmen ? 'bg-green-100 text-green-600 ring-4 ring-green-50' : 'bg-gray-100 text-gray-400' }} items-center justify-center transition-all duration-300">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                    </svg>
                                </div>
                            </div>

                            {{-- KONTEN ASESMEN --}}
                            <div
                                class="w-full bg-white p-6 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-300">

                                {{-- JUDUL SECTION --}}
                                <div class="flex justify-between items-center mb-6">
                                    <h3
                                        class="text-lg font-bold {{ $unlockAsesmen ? 'text-gray-900' : 'text-gray-400' }}">
                                        Daftar Asesmen
                                    </h3>
                                    @if ($unlockAsesmen)
                                        <span
                                            class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold uppercase tracking-wide">
                                            Tersedia
                                        </span>
                                    @endif
                                </div>

                                {{-- INFO STATUS --}}
                                @if (!$unlockAsesmen && isset($pesanStatus))
                                    <div
                                        class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 rounded-r-lg text-sm flex items-start gap-3">
                                        <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <span class="font-bold">Menunggu Jadwal:</span> {{ $pesanStatus }}
                                            @if ($pesanWaktu)
                                                <div
                                                    class="text-yellow-600 mt-1 text-xs font-mono bg-yellow-100 px-2 py-1 rounded inline-block">
                                                    {{ $pesanWaktu }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @elseif($isWaktuHabis)
                                    <div
                                        class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-800 rounded-r-lg text-sm font-medium flex items-center gap-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Waktu Asesmen Telah Habis. Jawaban otomatis tersimpan.
                                    </div>
                                @endif

                                <div class="space-y-4">

                                    {{-- 1. IA.02 (Praktik) --}}
                                    @if ($showIA02)
                                        <div
                                            class="group flex flex-col sm:flex-row justify-between items-center p-4 rounded-xl border transition-all duration-200 
                    {{ $unlockAsesmen ? 'bg-white border-gray-200 hover:border-blue-300 hover:shadow-sm' : 'bg-gray-50 border-gray-100 opacity-70' }}">
                                            <div class="flex items-center gap-4 mb-3 sm:mb-0 w-full sm:w-auto flex-1">
                                                <div
                                                    class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center {{ $unlockAsesmen ? 'bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors' : 'bg-gray-200 text-gray-400' }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h4
                                                        class="font-bold text-gray-800 {{ !$unlockAsesmen ? 'text-gray-500' : '' }}">
                                                        FR.IA.02 Demonstrasi</h4>
                                                    <p class="text-xs text-gray-500">Tugas Praktik & Observasi</p>
                                                </div>
                                            </div>
                                            <div class="w-full sm:w-auto flex-shrink-0">
                                                @if ($unlockAsesmen)
                                                    <a href="{{ route('asesi.ia02.index', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                                                        class="flex items-center justify-center w-full sm:w-36 h-10 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                                        <svg class="w-4 h-4 mr-2" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                            </path>
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                            </path>
                                                        </svg> Lihat Soal
                                                    </a>
                                                @else
                                                    <div
                                                        class="flex items-center justify-center w-full sm:w-36 h-10 bg-gray-100 text-gray-400 text-xs font-semibold rounded-lg border border-gray-200 cursor-not-allowed">
                                                        <svg class="w-3 h-3 mr-1.5" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                            </path>
                                                        </svg> Terkunci</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    {{-- 2. IA.05 (Pilihan Ganda) --}}
                                    @if ($showIA05)
                                        <div
                                            class="group flex flex-col sm:flex-row justify-between items-center p-4 rounded-xl border transition-all duration-200 {{ $unlockAsesmen ? 'bg-white border-gray-200 hover:border-blue-300 hover:shadow-sm' : 'bg-gray-50 border-gray-100 opacity-70' }}">
                                            <div class="flex items-center gap-4 mb-3 sm:mb-0 w-full sm:w-auto flex-1">
                                                <div
                                                    class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center {{ $unlockAsesmen ? 'bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors' : 'bg-gray-200 text-gray-400' }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h4
                                                        class="font-bold text-gray-800 {{ !$unlockAsesmen ? 'text-gray-500' : '' }}">
                                                        FR.IA.05 Pilihan Ganda</h4>
                                                    <p class="text-xs text-gray-500">Tes Tertulis</p>
                                                </div>
                                            </div>
                                            <div class="w-full sm:w-auto flex-shrink-0">
                                                @if ($isWaktuHabis || $unlockHasil)
                                                    <span
                                                        class="inline-flex items-center justify-center w-full sm:w-36 h-10 bg-green-100 text-green-700 text-xs font-bold rounded-lg border border-green-200 cursor-default"><svg
                                                            class="w-4 h-4 mr-1.5" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg> Selesai</span>
                                                @elseif ($unlockAsesmen)
                                                    <a href="{{ route('asesi.asesmen.ia05.view', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                                                        class="flex items-center justify-center w-full sm:w-36 h-10 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                                        <svg class="w-4 h-4 mr-2" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                            </path>
                                                        </svg> Kerjakan
                                                    </a>
                                                @else
                                                    <div
                                                        class="flex items-center justify-center w-full sm:w-36 h-10 bg-gray-100 text-gray-400 text-xs font-semibold rounded-lg border border-gray-200 cursor-not-allowed">
                                                        <svg class="w-3 h-3 mr-1.5" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                            </path>
                                                        </svg> Terkunci</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    {{-- 3. IA.06 (Esai) --}}
                                    @if ($showIA06)
                                        <div
                                            class="group flex flex-col sm:flex-row justify-between items-center p-4 rounded-xl border transition-all duration-200 {{ $unlockAsesmen ? 'bg-white border-gray-200 hover:border-blue-300 hover:shadow-sm' : 'bg-gray-50 border-gray-100 opacity-70' }}">
                                            <div class="flex items-center gap-4 mb-3 sm:mb-0 w-full sm:w-auto flex-1">
                                                <div
                                                    class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center {{ $unlockAsesmen ? 'bg-purple-50 text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors' : 'bg-gray-200 text-gray-400' }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h4
                                                        class="font-bold text-gray-800 {{ !$unlockAsesmen ? 'text-gray-500' : '' }}">
                                                        FR.IA.06 Esai</h4>
                                                    <p class="text-xs text-gray-500">Tes Tertulis Uraian</p>
                                                </div>
                                            </div>
                                            <div class="w-full sm:w-auto flex-shrink-0">
                                                @if ($isWaktuHabis || $unlockHasil)
                                                    <span
                                                        class="inline-flex items-center justify-center w-full sm:w-36 h-10 bg-green-100 text-green-700 text-xs font-bold rounded-lg border border-green-200 cursor-default"><svg
                                                            class="w-4 h-4 mr-1.5" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg> Selesai</span>
                                                @elseif ($unlockAsesmen)
                                                    <a href="{{ route('asesi.asesmen.ia06.view', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                                                        class="flex items-center justify-center w-full sm:w-36 h-10 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                                        <svg class="w-4 h-4 mr-2" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                            </path>
                                                        </svg> Kerjakan
                                                    </a>
                                                @else
                                                    <div
                                                        class="flex items-center justify-center w-full sm:w-36 h-10 bg-gray-100 text-gray-400 text-xs font-semibold rounded-lg border border-gray-200 cursor-not-allowed">
                                                        <svg class="w-3 h-3 mr-1.5" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                            </path>
                                                        </svg> Terkunci</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    {{-- 4. IA.07 (Pertanyaan Lisan) --}}
                                    {{-- DISINI KITA GAK PAKAI LOOP LAGI, TAPI MANUAL BIAR BISA SET ROUTE SENDIRI --}}
                                    @if ($showIA07)
                                        <div
                                            class="group flex flex-col sm:flex-row justify-between items-center p-4 rounded-xl border transition-all duration-200 {{ $unlockAsesmen ? 'bg-white border-gray-200 hover:border-orange-300 hover:shadow-sm' : 'bg-gray-50 border-gray-100 opacity-70' }}">
                                            <div class="flex items-center gap-4 mb-3 sm:mb-0 w-full sm:w-auto flex-1">
                                                <div
                                                    class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center {{ $unlockAsesmen ? 'bg-orange-50 text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-colors' : 'bg-gray-200 text-gray-400' }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h4
                                                        class="font-bold text-gray-800 {{ !$unlockAsesmen ? 'text-gray-500' : '' }}">
                                                        FR.IA.07 Pertanyaan Lisan</h4>
                                                    <p class="text-xs text-gray-500">Interview Asesor</p>
                                                </div>
                                            </div>
                                            <div class="w-full sm:w-auto flex-shrink-0">
                                                @if ($unlockHasil)
                                                    <a href="{{ route('asesi.ia07.index', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                                                        class="flex items-center justify-center w-full sm:w-36 h-10 text-blue-600 text-sm font-semibold hover:underline">Lihat
                                                        Hasil</a>
                                                @elseif ($unlockAsesmen)
                                                    {{-- GANTI ROUTE DI SINI UNTUK IA.07 --}}
                                                    <a href="{{ route('asesi.ia07.index', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                                                        class="flex items-center justify-center w-full sm:w-36 h-10 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                                        <svg class="w-4 h-4 mr-2" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                            </path>
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                            </path>
                                                        </svg> Lihat Soal
                                                    </a>
                                                @else
                                                    <div
                                                        class="flex items-center justify-center w-full sm:w-36 h-10 bg-gray-100 text-gray-400 text-xs font-semibold rounded-lg border border-gray-200 cursor-not-allowed">
                                                        <svg class="w-3 h-3 mr-1.5" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                            </path>
                                                        </svg> Terkunci</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    {{-- 5. IA.09 (Wawancara) --}}
                                    @if ($showIA09)
                                        <div
                                            class="group flex flex-col sm:flex-row justify-between items-center p-4 rounded-xl border transition-all duration-200 {{ $unlockAsesmen ? 'bg-white border-gray-200 hover:border-red-300 hover:shadow-sm' : 'bg-gray-50 border-gray-100 opacity-70' }}">
                                            <div class="flex items-center gap-4 mb-3 sm:mb-0 w-full sm:w-auto flex-1">
                                                <div
                                                    class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center {{ $unlockAsesmen ? 'bg-red-50 text-red-600 group-hover:bg-red-600 group-hover:text-white transition-colors' : 'bg-gray-200 text-gray-400' }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h4
                                                        class="font-bold text-gray-800 {{ !$unlockAsesmen ? 'text-gray-500' : '' }}">
                                                        FR.IA.09 Wawancara</h4>
                                                    <p class="text-xs text-gray-500">Verifikasi Portofolio</p>
                                                </div>
                                            </div>
                                            <div class="w-full sm:w-auto flex-shrink-0">
                                                @if ($unlockHasil)
                                                    <a href="#"
                                                        class="flex items-center justify-center w-full sm:w-36 h-10 text-blue-600 text-sm font-semibold hover:underline">Lihat
                                                        Hasil</a>
                                                @elseif ($unlockAsesmen)
                                                    {{-- GANTI ROUTE DI SINI UNTUK IA.09 --}}
                                                    <a href="{{ route('asesi.ia09.index', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                                                        class="flex items-center justify-center w-full sm:w-36 h-10 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                                        <svg class="w-4 h-4 mr-2" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                            </path>
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                            </path>
                                                        </svg> Lihat Soal
                                                    </a>
                                                @else
                                                    <div
                                                        class="flex items-center justify-center w-full sm:w-36 h-10 bg-gray-100 text-gray-400 text-xs font-semibold rounded-lg border border-gray-200 cursor-not-allowed">
                                                        <svg class="w-3 h-3 mr-1.5" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                            </path>
                                                        </svg> Terkunci</div>
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
                            {{-- UPDATE: left-4 jadi left-5 --}}
                            <div
                                class="absolute left-5 top-0 -bottom-8 w-1 md:left-6 md:top-6 md:-bottom-10 md:w-0.5 
                                {{ $level >= $LVL_UMPAN_BALIK ? 'bg-green-500' : 'bg-gray-200' }}">
                            </div>

                            <div class="relative flex-shrink-0 ml-1 mr-4 md:mr-6 z-10">
                                <div
                                    class="hidden md:flex w-12 h-12 rounded-lg bg-gray-100 items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <div
                                    class="md:hidden flex items-center justify-center w-9 h-9 bg-white rounded-full border-4 border-gray-100 shadow-sm z-20 relative">
                                    @if ($level >= $LVL_UMPAN_BALIK)
                                        <div
                                            class="w-4 h-4 bg-green-500 rounded-full shadow-[0_0_10px_rgba(34,197,94,0.6)]">
                                        </div>
                                    @else
                                        <div class="w-4 h-4 bg-gray-300 rounded-full"></div>
                                    @endif
                                </div>
                                @if ($level >= $LVL_UMPAN_BALIK)
                                    <div class="hidden md:block">{!! renderCheckmark() !!}</div>
                                @endif
                            </div>

                            <div class="{{ $responsiveCardClass }}">
                                @if ($unlockAK03)
                                    <a href="{{ route('asesi.ak03.index', $sertifikasi->id_data_sertifikasi_asesi) }}"
                                        class="{{ $linkClassEnabled }}">Keputusan dan Umpan Balik
                                        Asesor</a>
                                @else
                                    <span class="{{ $linkClassDisabled }}">Keputusan dan Umpan Balik Asesor</span>
                                @endif

                                @if ($level >= $LVL_UMPAN_BALIK)
                                    <p class="{{ $statusClassSelesai }}">Selesai</p>
                                    <button class="{{ $btnBlue }}">Unduh Dokumen</button>
                                @elseif ($unlockAK03)
                                    <p class="{{ $statusClassProses }}">Siap Diisi</p>
                                @else
                                    <p class="{{ $statusClassTerkunci }}">Terkunci</p>
                                @endif
                            </div>
                        </li>

                        {{-- ============================================= --}}
                        {{-- ITEM 8: Banding --}}
                        {{-- ============================================= --}}
                        <li class="relative flex items-center md:items-start md:pb-10">
                            {{-- UPDATE: left-4 jadi left-5 --}}
                            <div
                                class="absolute left-5 top-0 -bottom-8 w-1 md:left-6 md:top-6 md:-bottom-10 md:w-0.5 
                                {{ $level >= $LVL_BANDING ? 'bg-green-500' : 'bg-gray-200' }}">
                            </div>

                            <div class="relative flex-shrink-0 ml-1 mr-4 md:mr-6 z-10">
                                <div
                                    class="hidden md:flex w-12 h-12 rounded-lg bg-gray-100 items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z" />
                                    </svg>
                                </div>
                                <div
                                    class="md:hidden flex items-center justify-center w-9 h-9 bg-white rounded-full border-4 border-gray-100 shadow-sm z-20 relative">
                                    @if ($level >= $LVL_BANDING)
                                        <div
                                            class="w-4 h-4 bg-green-500 rounded-full shadow-[0_0_10px_rgba(34,197,94,0.6)]">
                                        </div>
                                    @else
                                        <div class="w-4 h-4 bg-gray-300 rounded-full"></div>
                                    @endif
                                </div>
                                @if ($level >= $LVL_BANDING)
                                    <div class="hidden md:block">{!! renderCheckmark() !!}</div>
                                @endif
                            </div>

                            <div class="{{ $responsiveCardClass }}">
                                @if ($level >= $LVL_UMPAN_BALIK)
                                    <a href="{{ route('asesi.banding.fr_ak04', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                                        class="{{ $linkClassEnabled }}">Umpan Balik Peserta /
                                        Banding</a>
                                @else
                                    <span class="{{ $linkClassDisabled }}">Umpan Balik Peserta / Banding</span>
                                @endif

                                @if ($level >= $LVL_BANDING)
                                    <p class="{{ $statusClassSelesai }}">Banding Selesai</p>
                                @elseif ($level >= $LVL_UMPAN_BALIK)
                                    <p class="{{ $statusClassProses }}">Opsional</p>
                                @else
                                    <p class="{{ $statusClassTerkunci }}">Terkunci</p>
                                @endif
                            </div>
                        </li>

                        {{-- ============================================= --}}
                        {{-- ITEM 9: Keputusan Komite --}}
                        {{-- ============================================= --}}
                        <li class="relative flex items-center md:items-start">
                            {{-- Garis Dihilangkan di item terakhir (bersih) --}}

                            <div class="relative flex-shrink-0 ml-1 mr-4 md:mr-6 z-10">
                                <div
                                    class="hidden md:flex w-12 h-12 rounded-lg bg-gray-100 items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.462 48.462 0 0 0 12 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c.317.053.626.111.928.174m-15.356 0c.317.053.626.111.928.174m13.5 0L12 12m0 0L6.25 4.97M12 12v8.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M12 12h8.25m-8.25 0H3.75" />
                                    </svg>
                                </div>
                                <div
                                    class="md:hidden flex items-center justify-center w-9 h-9 bg-white rounded-full border-4 border-gray-100 shadow-sm z-20 relative">
                                    @if ($level >= $LVL_REKOMENDASI)
                                        <div
                                            class="w-4 h-4 bg-green-500 rounded-full shadow-[0_0_10px_rgba(34,197,94,0.6)]">
                                        </div>
                                    @else
                                        <div class="w-4 h-4 bg-gray-300 rounded-full"></div>
                                    @endif
                                </div>
                                @if ($level >= $LVL_REKOMENDASI)
                                    <div class="hidden md:block">{!! renderCheckmark() !!}</div>
                                @endif
                            </div>

                            <div class="{{ $responsiveCardClass }}">
                                <h3 class="{{ $linkClassDisabled }}">Keputusan Komite</h3>

                                @if ($level == $LVL_REKOMENDASI)
                                    <p class="{{ $statusClassSelesai }}">Kompeten - Direkomendasikan Menerima
                                        Sertifikat</p>
                                    <button
                                        class="{{ $btnBlue }} bg-green-500 hover:bg-green-600 border-none shadow-green-100">Unduh
                                        Sertifikat</button>
                                @elseif ($level == DataSertifikasiAsesi::STATUS_TIDAK_DIREKOMENDASIKAN)
                                    <p class="text-xs text-red-600 font-medium">Belum Kompeten - Tidak Direkomendasikan
                                    </p>
                                @else
                                    <p class="{{ $statusClassTunggu }}">Menunggu Keputusan</p>
                                @endif
                            </div>
                        </li>

                    </ol>
                </div>
            </main>
        @else
            <main class="flex-1 p-10 overflow-y-auto">
                <div class="max-w-3xl mx-auto">
                    <div class="alert alert-info text-center shadow-sm bg-white p-10 rounded-2xl"
                        style="border-radius: 15px; padding: 30px;">
                        <h2 class="text-3xl font-bold mb-4">Belum Ada Pendaftaran</h2>
                        <p class="text-lg text-gray-600">Anda belum terdaftar pada skema sertifikasi apapun.</p>
                        <a href="/"
                            class="mt-6 inline-block px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600">Lihat
                            Daftar Skema</a>
                    </div>
                </div>
            </main>
        @endif

    </div>
</body>

</html>
