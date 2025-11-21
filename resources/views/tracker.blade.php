@use('App\Models\DataSertifikasiAsesi')

@php
    // --- Variabel Level Real ---
    $level = $sertifikasi->progres_level;

    // --- DEFINISI LEVEL (ANGKA) BIAR KONSISTEN ---
    // Kita pakai angka hardcode sesuai logic Model biar gampang dibandingin
    $LVL_DAFTAR_SELESAI = 10;
    $LVL_TUNGGU_BAYAR = 20;
    $LVL_LUNAS = 30;
    $LVL_PRA_ASESMEN = 40;
    $LVL_SETUJU = 50;
    $LVL_ASESMEN = 70;
    $LVL_UMPAN_BALIK = 80;
    $LVL_BANDING = 90;
    $LVL_REKOMENDASI = 100;

    // --- Variabel CSS ---
    $linkClassEnabled = 'text-lg font-semibold text-gray-900 hover:text-blue-600 cursor-pointer';
    $linkClassDisabled = 'text-lg font-semibold text-gray-400 cursor-not-allowed';
    $statusClassSelesai = 'text-xs text-green-600 font-medium';
    $statusClassProses = 'text-xs text-blue-600 font-medium';
    $statusClassTunggu = 'text-xs text-yellow-600 font-medium';
    $statusClassTerkunci = 'text-xs text-gray-400 font-medium';
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skema Sertifikat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="flex min-h-screen">

        @if ($sertifikasi)
            {{-- ====================================================================== --}}
            {{-- 2. Sidebar (Tidak berubah, sudah dinamis) --}}
            {{-- ====================================================================== --}}
            <aside
                class="w-80 bg-gradient-to-b from-yellow-100 via-blue-100 to-blue-300 p-6 relative z-10 shadow-[8px_0_20px_-5px_rgba(0,0,0,0.15)]">
                <div class="mb-6">
                    <a href="/" class="flex items-center text-gray-700 hover:text-gray-900">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span class="font-medium">Kembali</span>
                    </a>
                </div>

                <h1 class="text-3xl font-bold mb-2">Skema Sertifikat</h1>

                <div class="flex justify-center my-6">
                    <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-800 shadow-lg">
                        <img src="https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=400&h=400&fit=crop"
                            alt="Coding" class="w-full h-full object-cover">
                    </div>
                </div>

                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold">{{ $sertifikasi->jadwal->skema->nama_skema ?? 'Judul Skema' }}</h2>
                    <p class="text-gray-600 text-sm mt-2">
                        {{ $sertifikasi->jadwal->skema->nomor_skema ?? 'Nomor Skema' }}
                    </p>
                    <p class="text-xl font-medium text-gray-800 mt-4">
                        {{ $sertifikasi->asesi->nama_lengkap ?? 'Nama Asesi' }}</p>
                </div>

                <p class="text-center text-sm text-gray-700 mb-8 px-4">
                    Ini adalah halaman tracker progres sertifikasi Anda. Selesaikan setiap langkah untuk melanjutkan.
                </p>

                <div>
                    <h3 class="font-bold text-lg mb-4">Persyaratan Utama</h3>
                    <ul class="space-y-2">
                        {{-- Ini bisa kamu buat dinamis juga nanti --}}
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm">Data Sertifikasi (APL-01)</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm">Bukti Kelengkapan (APL-02)</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm">Bukti Pembayaran</span>
                        </li>
                    </ul>
                </div>
            </aside>


            {{-- ====================================================================== --}}
            {{-- 3. MAIN CONTENT (YANG KITA UBAH JADI DINAMIS) --}}
            {{-- ====================================================================== --}}
            <main class="flex-1 p-10 overflow-y-auto">
                <div class="max-w-3xl mx-auto bg-white p-8 sm:p-12 rounded-2xl shadow-xl">

                    <ol class="relative">

                        @php
                            // --- Fungsi Ceklis (biarin aja) ---
                            function renderCheckmark()
                            {
                                return '<div class="absolute -top-1 -left-1.5 z-10 bg-green-500 rounded-full p-0.5 border-2 border-white">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="white" class="w-3 h-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </div>';
                            }

                            // --- Variabel CSS Biar Rapi ---
                            $linkClassEnabled =
                                'text-lg font-semibold text-gray-900 hover:text-blue-600 cursor-pointer';
                            $linkClassDisabled = 'text-lg font-semibold text-gray-400 cursor-not-allowed';

                            $statusClassSelesai = 'text-xs text-green-600 font-medium';
                            $statusClassProses = 'text-xs text-blue-600 font-medium';
                            $statusClassTunggu = 'text-xs text-yellow-600 font-medium';
                            $statusClassTerkunci = 'text-xs text-gray-400 font-medium';
                        @endphp

                        {{-- ============================================= --}}
                        {{-- LANGKAH 1: Formulir Pendaftaran (Level 10) --}}
                        {{-- ============================================= --}}
                        <li class="relative flex items-start pb-10">
                            <div class="absolute left-6 top-6 -bottom-10 w-0.5 bg-gray-200"></div>
                            <div class="relative flex-shrink-0 mr-6">
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                {{-- Ceklis: Muncul jika level 10 (Selesai Daftar) atau lebih --}}
                                @if ($level >= $LVL_DAFTAR_SELESAI)
                                    {!! renderCheckmark() !!}
                                @endif
                            </div>
                            <div class="flex-1">
                                {{-- Link: Selalu bisa diklik untuk edit data --}}
                                <a href="{{ route('data.sertifikasi', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                                    class="{{ $linkClassEnabled }}">
                                    Formulir Pendaftaran Sertifikasi
                                </a>
                                <p class="text-sm text-gray-500">{{ $sertifikasi->tanggal_daftar->format('l, d F Y') }}
                                </p>
                                <p class="{{ $statusClassSelesai }}">Selesai</p>

                                {{-- Tombol Download APL-01 --}}
                                @if ($level >= $LVL_DAFTAR_SELESAI)
                                    {{-- Kalau level >= 10, BISA DOWNLOAD --}}
                                    <a href="{{ route('apl01.download', ['id_asesi' => $sertifikasi->id_asesi]) }}"
                                        target="_blank"
                                        class="mt-2 px-4 py-1.5 bg-blue-500 text-white text-xs font-semibold rounded-md hover:bg-blue-600 inline-block">
                                        Unduh APL-01
                                    </a>
                                @else
                                    {{-- Kalau level masih 5 (sedang_mendaftar), DISABLE --}}
                                    <button disabled
                                        class="mt-2 px-4 py-1.5 bg-gray-300 text-gray-500 text-xs font-semibold rounded-md cursor-not-allowed inline-block">
                                        Unduh APL-01 (Selesaikan Tanda Tangan)
                                    </button>
                                @endif

                                {{-- Teks status dinamis --}}
                                @if ($sertifikasi->progres_level >= $LVL_DAFTAR_SELESAI)
                                    <p class="{{ $statusClassSelesai }}">Selesai</p>
                                @else
                                    {{-- Ini status untuk Level 5 --}}
                                    <p class="{{ $statusClassProses }}">Sedang Mengisi...</p>
                                @endif
                            </div>
                        </li>

                        {{-- ============================================= --}}
                        {{-- LANGKAH 2: Pembayaran (Level 30) --}}
                        {{-- ============================================= --}}
                        <li class="relative flex items-start pb-10">
                            <div class="absolute left-6 top-6 -bottom-10 w-0.5 bg-gray-200"></div>
                            <div class="relative flex-shrink-0 mr-6">
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h6m3-3.75l-3 3m0 0l-3-3m3 3V15m6-1.5h.008v.008H18V13.5z" />
                                    </svg>
                                </div>
                                {{-- Ceklis: Muncul jika level 30 (Lunas) atau lebih --}}
                                @if ($level >= $LVL_LUNAS)
                                    {!! renderCheckmark() !!}
                                @endif
                            </div>
                            <div class="flex-1">
                                {{-- Link: Bisa diklik jika sudah selesai daftar (level 10) --}}
                                @if ($level >= $LVL_DAFTAR_SELESAI)
                                    <a href="{{ route('payment.create', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}" class="{{ $linkClassEnabled }}">Pembayaran</a>
                                @else
                                    <span class="{{ $linkClassDisabled }}">Pembayaran</span>
                                @endif

                                {{-- Status Dinamis --}}
                                @if ($level >= $LVL_LUNAS)
                                    <p class="{{ $statusClassSelesai }}">Lunas</p>
                                    <button
                                        class="mt-2 px-4 py-1.5 bg-blue-500 text-white text-xs font-semibold rounded-md hover:bg-blue-600">
                                        Unduh Invoice
                                    </button>
                                @elseif ($level == $LVL_TUNGGU_BAYAR)
                                    <p class="{{ $statusClassTunggu }}">Menunggu Verifikasi</p>
                                @else
                                    <p class="{{ $statusClassTerkunci }}">Terkunci</p>
                                @endif
                            </div>
                        </li>

                        {{-- ============================================= --}}
                        {{-- LANGKAH 3: Pra-Asesmen (Level 40) --}}
                        {{-- ============================================= --}}
                        <li class="relative flex items-start pb-10">
                            <div class="absolute left-6 top-6 -bottom-10 w-0.5 bg-gray-200"></div>
                            <div class="relative flex-shrink-0 mr-6">
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.122 2.122l7.81-7.81" />
                                    </svg>
                                </div>
                                {{-- Ceklis: Muncul jika level 40 (Selesai Pra-Asesmen) atau lebih --}}
                                @if ($level >= $LVL_PRA_ASESMEN)
                                    {!! renderCheckmark() !!}
                                @endif
                            </div>
                            <div class="flex-1">
                                {{-- Link: Bisa diklik jika sudah lunas (level 30) --}}
                                @if ($level >= $LVL_LUNAS)
                                    <a href="/praasesmen1" class="{{ $linkClassEnabled }}">Pra-Asesmen</a>
                                @else
                                    <span class="{{ $linkClassDisabled }}">Pra-Asesmen</span>
                                @endif

                                {{-- Status Dinamis --}}
                                @if ($level >= $LVL_PRA_ASESMEN)
                                    <p class="{{ $statusClassSelesai }}">Selesai</p>
                                    <button
                                        class="mt-2 px-4 py-1.5 bg-blue-500 text-white text-xs font-semibold rounded-md hover:bg-blue-600">
                                        Unduh Dokumen
                                    </button>
                                @elseif ($level == $LVL_LUNAS)
                                    <p class="{{ $statusClassProses }}">Siap Diisi</p>
                                @else
                                    <p class="{{ $statusClassTerkunci }}">Terkunci</p>
                                @endif
                            </div>
                        </li>

                        {{-- ============================================= --}}
                        {{-- LANGKAH 4: Verifikasi TUK (Admin) --}}
                        {{-- ============================================= --}}
                        <li class="relative flex items-start pb-10">
                            <div class="absolute left-6 top-6 -bottom-10 w-0.5 bg-gray-200"></div>
                            <div class="relative flex-shrink-0 mr-6">
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h6.75M9 11.25h6.75M9 15.75h6.75M9 20.25h6.75" />
                                    </svg>
                                </div>
                                {{-- Ceklis: Muncul jika level 40 (Pra-Asesmen) atau lebih. Ini adalah langkah Admin/Asesor --}}
                                @if ($level >= $LVL_PRA_ASESMEN)
                                    {!! renderCheckmark() !!}
                                @endif
                            </div>
                            <div class="flex-1">
                                @if ($level >= $LVL_PRA_ASESMEN)
                                    <a href="#" class="{{ $linkClassEnabled }}">Verifikasi TUK</a>
                                @else
                                    <span class="{{ $linkClassDisabled }}">Verifikasi TUK</span>
                                @endif
                                <p class="text-sm text-gray-500">Dilakukan oleh Asesor</p>
                                @if ($level >= $LVL_PRA_ASESMEN)
                                    <p class="{{ $statusClassSelesai }}">Terverifikasi</p>
                                @else
                                    <p class="{{ $statusClassTerkunci }}">Menunggu Pra-Asesmen</p>
                                @endif
                            </div>
                        </li>

                        {{-- ============================================= --}}
                        {{-- LANGKAH 5: Persetujuan Asesmen (Level 50) --}}
                        {{-- ============================================= --}}
                        <li class="relative flex items-start pb-10">
                            <div class="absolute left-6 top-6 -bottom-10 w-0.5 bg-gray-200"></div>
                            <div class="relative flex-shrink-0 mr-6">
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                                    </svg>
                                </div>
                                {{-- Ceklis: Muncul jika level 50 (Setuju) atau lebih --}}
                                @if ($level >= $LVL_SETUJU)
                                    {!! renderCheckmark() !!}
                                @endif
                            </div>
                            <div class="flex-1">
                                {{-- Link: Bisa diklik jika sudah selesai Pra-Asesmen (level 40) --}}
                                @if ($level >= $LVL_PRA_ASESMEN)
                                    <a href="{{ route('kerahasiaan.fr_ak01', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                                        class="{{ $linkClassEnabled }}">
                                        Persetujuan Asesmen dan Kerahasiaan
                                    </a>
                                @else
                                    <span class="{{ $linkClassDisabled }}">Persetujuan Asesmen dan Kerahasiaan</span>
                                @endif

                                {{-- Status Dinamis --}}
                                @if ($level >= $LVL_SETUJU)
                                    <p class="{{ $statusClassSelesai }}">Telah Disetujui</p>
                                    <button
                                        class="mt-2 px-4 py-1.5 bg-blue-500 text-white text-xs font-semibold rounded-md hover:bg-blue-600">
                                        Unduh Dokumen
                                    </button>
                                @elseif ($level == $LVL_PRA_ASESMEN)
                                    <p class="{{ $statusClassProses }}">Siap Diisi</p>
                                @else
                                    <p class="{{ $statusClassTerkunci }}">Terkunci</p>
                                @endif
                            </div>
                        </li>

                        {{-- ============================================= --}}
                        {{-- LANGKAH 6: Asesmen (Level 70) --}}
                        {{-- ============================================= --}}
                        <li class="relative flex items-start pb-10">
                            <div class="absolute left-6 top-6 -bottom-10 w-0.5 bg-gray-200"></div>
                            <div class="relative flex-shrink-0 mr-6">
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 019 9v.375M10.125 2.25A3.375 3.375 0 006.75 5.625v1.5c0 .621.504 1.125 1.125 1.125h.375m-3.75 0h16.5v1.5c0 .621-.504 1.125-1.125 1.125h-14.25c-.621 0-1.125-.504-1.125-1.125v-1.5z" />
                                    </svg>
                                </div>
                                {{-- Ceklis: Muncul jika level 70 (Asesmen Selesai) atau lebih --}}
                                @if ($level >= $LVL_ASESMEN)
                                    {!! renderCheckmark() !!}
                                @endif
                            </div>
                            <div class="flex-1">
                                {{-- Link: Terkunci (ini hanya judul) jika belum level 50 --}}
                                @if ($level >= $LVL_SETUJU)
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Asesmen</h3>
                                @else
                                    <h3 class="{{ $linkClassDisabled }} mb-2">Asesmen</h3>
                                @endif

                                <div class="space-y-4">
                                    {{-- Sub-item: Praktek --}}
                                    <div class="flex justify-between items-start space-x-4">
                                        <div>
                                            <h4 class="font-medium text-gray-800">Cek Observasi - Demonstrasi/Praktek
                                            </h4>
                                            @if ($level >= $LVL_ASESMEN)
                                                <p class="{{ $statusClassSelesai }}">Rekomendasi Kompeten</p>
                                            @elseif ($level == $LVL_SETUJU)
                                                <p class="{{ $statusClassProses }}">Menunggu Jadwal Asesor</p>
                                            @else
                                                <p class="{{ $statusClassTerkunci }}">Terkunci</p>
                                            @endif
                                        </div>
                                    </div>
                                    {{-- Sub-item: Lisan --}}
                                    <div class="flex justify-between items-start space-x-4">
                                        <div>
                                            <h4 class="font-medium text-gray-800">Pertanyaan Lisan</h4>
                                            @if ($level >= $LVL_ASESMEN)
                                                <p class="{{ $statusClassSelesai }}">Rekomendasi Kompeten</p>
                                            @elseif ($level >= $LVL_SETUJU)
                                                <p class="{{ $statusClassProses }}">Menunggu Jadwal Asesor</p>
                                            @else
                                                <p class="{{ $statusClassTerkunci }}">Terkunci</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        {{-- ==================================================== --}}
                        {{-- LANGKAH 7: Keputusan & Umpan Balik Asesor (Level 80) --}}
                        {{-- ==================================================== --}}
                        <li class="relative flex items-start pb-10">
                            <div class="absolute left-6 top-6 -bottom-10 w-0.5 bg-gray-200"></div>
                            <div class="relative flex-shrink-0 mr-6">
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                {{-- Ceklis: Muncul jika level 80 (Selesai Umpan Balik) atau lebih --}}
                                @if ($level >= $LVL_UMPAN_BALIK)
                                    {!! renderCheckmark() !!}
                                @endif
                            </div>
                            <div class="flex-1">
                                {{-- Link: Bisa diklik jika sudah selesai Asesmen (level 70) --}}
                                @if ($level >= $LVL_ASESMEN)
                                    <a href="/umpan_balik" class="{{ $linkClassEnabled }}">Keputusan dan Umpan Balik
                                        Asesor</a>
                                @else
                                    <span class="{{ $linkClassDisabled }}">Keputusan dan Umpan Balik Asesor</span>
                                @endif

                                {{-- Status Dinamis --}}
                                @if ($level >= $LVL_UMPAN_BALIK)
                                    <p class="{{ $statusClassSelesai }}">Selesai</p>
                                @elseif ($level == $LVL_ASESMEN)
                                    <p class="{{ $statusClassProses }}">Siap Diisi</p>
                                @else
                                    <p class="{{ $statusClassTerkunci }}">Terkunci</p>
                                @endif
                            </div>
                        </li>

                        {{-- ============================================= --}}
                        {{-- LANGKAH 8: Banding (Opsional, Level 90) --}}
                        {{-- ============================================= --}}
                        <li class="relative flex items-start pb-10">
                            <div class="absolute left-6 top-6 -bottom-10 w-0.5 bg-gray-200"></div>
                            <div class="relative flex-shrink-0 mr-6">
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z" />
                                    </svg>
                                </div>
                                {{-- Ceklis: Muncul jika level 90 (Banding Selesai) --}}
                                @if ($level >= $LVL_BANDING)
                                    {!! renderCheckmark() !!}
                                @endif
                            </div>
                            <div class="flex-1">
                                {{-- Link: Bisa diklik jika sudah Umpan Balik (level 80) --}}
                                @if ($level >= $LVL_UMPAN_BALIK)
                                    <a href="/banding" class="{{ $linkClassEnabled }}">Umpan Balik Peserta /
                                        Banding</a>
                                @else
                                    <span class="{{ $linkClassDisabled }}">Umpan Balik Peserta / Banding</span>
                                @endif

                                {{-- Status Dinamis --}}
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
                        {{-- LANGKAH 9: Keputusan Komite (Level 100) --}}
                        {{-- ============================================= --}}
                        <li class="relative flex items-start">
                            <div class="relative flex-shrink-0 mr-6">
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.462 48.462 0 0 0 12 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c.317.053.626.111.928.174m-15.356 0c.317.053.626.111.928.174m13.5 0L12 12m0 0L6.25 4.97M12 12v8.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M12 12h8.25m-8.25 0H3.75" />
                                    </svg>
                                </div>
                                {{-- Ceklis: Muncul jika level 100 (Direkomendasikan) --}}
                                @if ($level >= $LVL_REKOMENDASI)
                                    {!! renderCheckmark() !!}
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="{{ $linkClassDisabled }}">Keputusan Komite</h3>

                                {{-- Status Dinamis --}}
                                @if ($level == $LVL_REKOMENDASI)
                                    <p class="{{ $statusClassSelesai }}">Kompeten - Direkomendasikan Menerima
                                        Sertifikat</p>
                                    <button
                                        class="mt-2 px-4 py-1.5 bg-green-500 text-white text-xs font-semibold rounded-md hover:bg-green-600">
                                        Unduh Sertifikat
                                    </button>
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
            {{-- ====================================================================== --}}
            {{-- 4. Tampilan Jika $sertifikasi null --}}
            {{-- ====================================================================== --}}
            <main class="flex-1 p-10 overflow-y-auto">
                <div class="max-w-3xl mx-auto">
                    <div class="alert alert-info text-center shadow-sm bg-white p-10 rounded-2xl"
                        style="border-radius: 15px; padding: 30px;">
                        <h2 class="text-3xl font-bold mb-4">Belum Ada Pendaftaran</h2>
                        <p class="text-lg text-gray-600">Anda belum terdaftar pada skema sertifikasi apapun.</p>
                        <a href="/"
                            class="mt-6 inline-block px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600">
                            Lihat Daftar Skema
                        </a>
                    </div>
                </div>
            </main>
        @endif

    </div>
</body>

</html>
