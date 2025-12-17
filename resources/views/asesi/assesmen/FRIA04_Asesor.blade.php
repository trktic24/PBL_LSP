<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FR.IA.04A - Daftar Instruksi Terstruktur</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
    /* 1. Kustomisasi Font Global & Scrollbar */
        body { 
            font-family: 'Poppins', sans-serif; 
             /* Mengontrol scrolling di level body */
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        
        /* 2. Style Sidebar (Fixed Position) */
        .sidebar {
            position: fixed; /* Sidebar tetap diam */
            top: 0;
            left: 0;
            bottom: 0;
            width: 16rem; /* w-64 */
            background-color: #ffffff; 
            border-right: 1px solid #e5e7eb; 
            padding: 1.5rem; 
            color: #1f2937; 
            overflow-y: auto; /* Memungkinkan sidebar discroll jika isinya panjang */
            z-index: 50; 
        }
        .sidebar-item {
            padding: 0.75rem 0.5rem; 
            margin-bottom: 0.5rem;
            border-radius: 0.5rem; 
            transition: background-color 0.15s;
            font-size: 0.875rem; 
            font-weight: 500; 
        }
        .sidebar-item:hover {
            background-color: #f3f4f6; 
        }
        .sidebar-item.active {
            background-color: #e5e7eb; 
            font-weight: 600; 
            color: #000;
        }

        /* 3. Style Konten Utama (Form IA.04A) */
        .content-font-base {
            font-size: 0.875rem; 
        }
        
        /* Override untuk field Read-Only */
        .form-table textarea[readonly], .form-table input[type="text"][readonly] {
            background-color: #f0f4f8 !important; 
            border: 1px solid #e5e7eb !important; 
            cursor: default;
        }
        
        /* Style Tabel Formal */
        .table-formal {
            border: 1px solid #000; 
            border-collapse: collapse;
            width: 100%; 
        }
        .table-formal th, .table-formal td {
            border: 1px solid #000; 
            padding: 8px 12px;
        }
        .table-formal thead th {
            background-color: #1e40af; 
            color: #fff;
            font-weight: 700;
            border-color: #000; 
        }
        .table-formal td textarea {
            border: none;
            background: transparent;
            padding: 0;
            margin: 0;
            resize: none;
            outline: none;
            box-shadow: none;
            width: 100%;
        }

        /* 4. MEDIA QUERY untuk Margin Kiri Main Content (Menjamin kerapatan di Desktop) */
        @media (min-width: 1024px) { 
            .main-content-shifted {
                margin-left: 16rem !important; /* Menempel persis ke sidebar w-64 */
            }
        }
        .signature-img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
            padding: 0.5rem;
        }

    </style>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('sidebar', {
                open: false,
                toggle() { this.open = ! this.open }
            })
        })
    </script>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="flex min-h-screen" x-data>

        {{-- SIDEBAR - Menggunakan Komponen x-sidebar2 --}}
        <x-sidebar2 :idAsesi="$asesi->id_asesi ?? null" :sertifikasi="$sertifikasi ?? null" />

        <main class="flex-1 bg-white p-8 lg:p-12 h-full overflow-y-auto">
            
            {{-- TOMBOL MENU MOBILE --}}
            <div class="p-4 bg-blue-600 text-white flex justify-between items-center shadow-md sticky top-0 z-30 flex-shrink-0 mb-8 lg:hidden">
                <span class="font-bold">Form Assessment</span>
                <button @click="$store.sidebar.open = true" class="p-2 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <div class="max-w-6xl mx-auto content-font-base">
                
                {{-- SIMULASI DATA YANG HARUSNYA DARI CONTROLLER --}}
                @php
                    //Data Controller (dengan fallback untuk tampilan)
                    $mockUnits = $mockUnits ?? []; // Data dinamis Unit Kompetensi
                    $kelompok_pekerjaan = $kelompok_pekerjaan ?? 'Pengembangan Aplikasi Junior'; // Data dinamis Kelompok Pekerjaan
                    
                    $judul_kegiatan_db = $judul_kegiatan_db ?? 'Proyek Pembuatan Sistem Informasi Pendaftaran Mahasiswa Baru'; 

                    $skema = $skema ?? (object)['nama_skema' => 'Junior Web Developer', 'nomor_skema' => 'J.620100.024.48'];
                    $asesor = $asesor ?? (object)['nama_lengkap' => 'Dr. Zora Bednar', 'nomor_regis' => '6478469'];
                    $asesi = $asesi ?? (object)['nama_lengkap' => 'Dr. Bennie Stokes'];
                    $tanggal_pelaksanaan = $tanggal_pelaksanaan ?? '01/12/2025';
                    
                    // Data Poin IA04A dari DB
                    $hal_yang_disiapkan_db = $hal_yang_disiapkan_db ?? null;
                    $hal_yang_didemonstrasikan_db = $hal_yang_didemonstrasikan_db ?? null;

                    // Data Tanda Tangan dan Rekomendasi
                    $tanda_tangan_asesor_path = $tanda_tangan_asesor_path ?? null;
                    $tanda_tangan_asesi_path = $tanda_tangan_asesi_path ?? null;
                    $rekomendasi_db = $rekomendasi_db ?? null;
                @endphp
                
                {{-- FORM START --}}
                <form action="{{ route('fria04a.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id_sertifikasi" value="{{ $sertifikasi->id_data_sertifikasi_asesi ?? 0 }}">
                <input type="hidden" name="poin_id" value="{{ $poinIA04A->id_poin_ia04A ?? 0 }}">
                
                {{-- Judul & Kop --}}
                <div class="mb-8 text-center"> 
                    <div class="mb-4 text-gray-400 text-sm font-bold italic text-left">Logo BNSP</div> 
                    
                    <h1 class="text-2xl font-bold text-black uppercase tracking-wide border-b-2 border-gray-100 pb-4 mb-6 inline-block">
                        FR.IA.04A. DIT – DAFTAR INSTRUKSI TERSTRUKTUR
                    </h1>
                </div>

                {{-- Informasi Data Peserta --}}
                <div class="info-data-box mb-10 text-gray-700">
                    <div class="grid grid-cols-2 gap-y-3 gap-x-8">
                        
                        {{-- Kolom Kiri: Skema & TUK & Judul Kegiatan --}}
                        <div>
                            <div class="font-semibold text-gray-500 text-sm">Skema Sertifikasi</div>
                            <div class="font-bold text-base text-gray-900 mb-4">{{ $skema->nama_skema ?? 'Junior Web Developer' }}</div>
                            
                            <div class="font-semibold text-gray-500 text-sm">Nomor Skema</div>
                            <div class="font-bold text-base text-gray-900 mb-4">{{ $skema->nomor_skema ?? 'J.620100.024.48' }}</div>
                            
                            <div class="font-semibold text-gray-500 text-sm">TUK</div>
                            <div class="flex flex-wrap gap-x-6 mt-1 custom-radio text-sm mb-4">
                                {{-- Radio Button Pertama: Sewaktu (READ ONLY DARI DB) --}}
                                <label class="inline-flex items-center">
                                    <input type="radio" value="sewaktu" class="h-4 w-4 text-blue-600 border-gray-300" 
                                        @if(isset($jenis_tuk_db) && $jenis_tuk_db == 'sewaktu') checked @endif
                                        disabled>
                                    {{-- DIUBAH: Hilangkan text-blue-700. Gunakan text-gray-900 jika terpilih --}}
                                    <span class="ml-2 @if(isset($jenis_tuk_db) && $jenis_tuk_db == 'sewaktu') font-bold text-gray-900 @else text-gray-700 @endif">Sewaktu</span>
                                </label>
                                
                                {{-- Radio Button Kedua: Tempat Kerja (READ ONLY DARI DB) --}}
                                <label class="inline-flex items-center">
                                    <input type="radio" value="tempat_kerja" class="h-4 w-4 text-blue-600 border-gray-300"
                                        @if(isset($jenis_tuk_db) && $jenis_tuk_db == 'tempat kerja') checked @endif
                                        disabled>
                                    {{-- DIUBAH: Hilangkan text-blue-700. Gunakan text-gray-900 jika terpilih --}}
                                    <span class="ml-2 @if(isset($jenis_tuk_db) && $jenis_tuk_db == 'tempat kerja') font-bold text-gray-900 @else text-gray-700 @endif">Tempat Kerja</span>
                                </label>
                            </div>
                            
                            <div class="font-semibold text-gray-500 text-sm">Judul Kegiatan Terstruktur</div>
                            <div class="mt-1">
                                <p class="text-base font-bold text-gray-900 break-words">{{ $judul_kegiatan_db }}</p>
                            </div>
                        </div>

                        {{-- Kolom Kanan: Asesor & Asesi --}}
                        <div>
                            <div class="font-semibold text-gray-500 text-sm">Nama Asesor</div>
                            <div class="font-bold text-base text-gray-900 mb-4">{{ $asesor->nama_lengkap ?? 'Dr. Zora Bednar' }}</div>

                            <div class="font-semibold text-gray-500 text-sm">Nama Asesi</div>
                            <div class="font-bold text-base text-gray-900 mb-4">{{ $asesi->nama_lengkap ?? 'Dr. Bennie Stokes' }}</p>

                            <div class="font-semibold text-gray-500 text-sm">Tanggal Pelaksanaan</div>
                            <div class="mt-1">
                                <input type="text" value="{{ $tanggal_pelaksanaan ?? '01/12/2025' }}" class="border-gray-300 rounded-lg shadow-sm text-base text-gray-900 font-bold p-2 w-40 text-center" readonly>
                            </div>
                        </div>

                    </div>
                </div>
                
                <hr class="border-t border-gray-200 mb-8">

                {{-- Panduan Asesor --}}
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-8 text-sm text-gray-700 shadow-sm">
                    <h3 class="font-bold text-blue-900 mb-2">PANDUAN BAGI ASESOR:</h3>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Tentukan proyek singkat atau kegiatan terstruktur lainnya yang harus dipersiapkan dan dipresentasikan oleh asesi.</li>
                        <li>Proyek singkat dibuat untuk keseluruhan unit kompetensi atau masing-masing kelompok pekerjaan.</li>
                        <li>Kumpulkan hasil proyek sesuai keluaran yang ditetapkan.</li>
                    </ul>
                </div>
                
                {{-- 1. TABEL UNIT KOMPETENSI (Dynamic) --}}
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Unit Kompetensi</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto table-formal text-sm">
                            <thead class="bg-gray-900 text-white text-center font-semibold">
                                <tr>
                                    <th rowspan="2" class="p-3 w-40 border-gray-300 align-middle">Kelompok Pekerjaan</th>
                                    <th colspan="3" class="p-3 border-gray-300">Unit Kompetensi</th>
                                </tr>
                                <tr>
                                    <th class="p-3 w-10 border-gray-300">No.</th>
                                    <th class="p-3 w-1/4 border-gray-300">Kode Unit</th>
                                    <th class="p-3 w-auto border-gray-300">Judul Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($mockUnits ?? [] as $index => $unit)
                                <tr class="bg-white hover:bg-gray-50">
                                    @if ($index == 0)
                                    <td rowspan="{{ count($mockUnits) }}" class="p-3 align-top font-semibold text-gray-800 border-gray-300">
                                        {{ $kelompok_pekerjaan }}
                                    </td>
                                    @endif
                                    <td class="p-2 text-center align-top border-gray-300">{{ $index + 1 }}.</td>
                                    <td class="p-2 align-top border-gray-300 font-medium text-blue-800">{{ $unit['code'] }}</td>
                                    <td class="p-2 align-top border-gray-300">{{ $unit['title'] }}</td>
                                </tr>
                                @empty
                                <tr class="bg-white hover:bg-gray-50">
                                    <td class="p-3 align-top font-semibold text-gray-800 border-gray-300">
                                        Kelompok Pekerjaan Tidak Ditemukan
                                    </td>
                                    <td colspan="4" class="p-4 text-center text-gray-500">
                                        Data unit kompetensi tidak ditemukan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr class="border-t border-gray-200 mb-8">

                {{-- 2. TABEL SKENARIO & HASIL (FR.IA.04A) --}}
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Instruksi & Skenario (FR.IA.04A)</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto table-formal text-sm min-w-[700px]">
                            <tbody>
                                <tr>
                                    {{-- Judul Baris 1: Statis --}}
                                    <td class="p-4 align-top font-semibold bg-gray-50 text-gray-800 border-gray-300 w-1/3">
                                        Hal yang harus disiapkan atau dilakukan atau dihasilkan untuk suatu proyek singkat/kegiatan terstruktur
                                    </td>
                                    {{-- Isi Baris 1: Skenario (Editable) & Tanggal (Editable) --}}
                                    <td class="p-4 align-top border-gray-300 w-2/3">
                                        <label class="block text-xs font-bold text-gray-600 mb-2 uppercase">Skenario studi kasus/kegiatan terstruktur yang berisikan data informasi, lingkup bahasan dan instruksi untuk asesi:</label>
                                        {{-- TERHUBUNG KE DB: poin_ia04a.hal_yang_disiapkan --}}
                                        <textarea name="skenario_umum" rows="5" class="bg-gray-100 text-gray-700" placeholder="Masukkan skenario, lingkup, dan instruksi di sini...">{{ $hal_yang_disiapkan_db ?? '' }}</textarea>
                                        
                                        <div class="mt-3 flex items-center justify-end gap-2 text-sm">
                                            <span class="font-medium text-gray-700">Waktu:</span>
                                            <input type="text" name="tanggal_skenario" class="w-32 text-center border-gray-300 rounded-md p-1" placeholder="DD/MM/YYYY" value="{{ $tanggal_pelaksanaan ?? '01/12/2025' }}"> 
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    {{-- Judul Baris 2: Statis --}}
                                    <td class="p-4 align-top font-semibold bg-gray-50 text-gray-800 border-gray-300">
                                        Hal yang perlu didemonstrasikan
                                    </td>
                                    {{-- Isi Baris 2: Hasil Demonstrasi (Editable) & Tanggal (Editable) --}}
                                    <td class="p-4 align-top border-gray-300">
                                        <label class="block text-xs font-bold text-gray-600 mb-2 uppercase">Hasil studi kasus atau kegiatan terstruktur (Asesor Input):</label>
                                        {{-- TERHUBUNG KE DB: poin_ia04a.hal_yang_didemonstrasikan --}}
                                        <textarea name="hasil_umum" rows="5" placeholder="Sebutkan output yang diharapkan dari demonstrasi/presentasi asesi di sini...">{{ $hal_yang_didemonstrasikan_db ?? '' }}</textarea>
                                        
                                        <div class="mt-3 flex items-center justify-end gap-2 text-sm">
                                            <span class="font-medium text-gray-700">Waktu:</span>
                                            <input type="text" name="tanggal_demonstrasi_umum" class="w-32 text-center border-gray-300 rounded-md p-1" placeholder="DD/MM/YYYY" value="{{ $tanggal_pelaksanaan ?? '01/12/2025' }}"> 
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr class="border-t border-gray-200 mb-8">

                {{-- 3. TABEL ASPEK PENILAIAN (FR.IA.04B) --}}
                <div class="mb-10">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Aspek Penilaian Proyek (FR.IA.04B)</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto table-formal text-sm min-w-[900px]">
                            <thead class="bg-gray-900 text-white text-center font-semibold">
                                <tr>
                                    <th rowspan="2" class="p-3 w-10 border-gray-300">No</th>
                                    <th colspan="3" class="p-3 border-gray-300">Aspek Penilaian</th>
                                    <th colspan="2" class="p-3 w-24 border-gray-300">Pencapaian</th>
                                </tr>
                                <tr>
                                    <th class="p-3 w-1/4 border-gray-300">Lingkup Penyajian proyek atau kegiatan terstruktur lainnya</th>
                                    <th class="p-3 w-1/3 border-gray-300">Daftar Pertanyaan</th>
                                    <th class="p-3 w-1/4 border-gray-300">Kesesuaian dengan standar kompetensi kerja (unit/elemen/KUK)</th>
                                    <th class="p-3 w-10 border-gray-300">Ya</th>
                                    <th class="p-3 w-10 border-gray-300">Tdk</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($q = 1; $q <= 2; $q++)
                                <tr class="bg-white hover:bg-gray-50">
                                    <td class="p-2 text-center align-top border-gray-300 font-bold">{{ $q }}.</td>
                                    <td class="p-2 align-top border-gray-300">
                                        <textarea rows="5" name="lingkup_q{{ $q }}" placeholder="Tulis lingkup penyajian proyek..."></textarea>
                                    </td>
                                    <td class="p-2 align-top border-gray-300">
                                        <div class="mb-2">
                                            <span class="font-bold block text-xs mb-1">Pertanyaan :</span>
                                            <textarea rows="3" name="pertanyaan_q{{ $q }}"></textarea>
                                        </div>
                                        <div>
                                            <span class="font-bold block text-xs mb-1">Tanggapan :</span>
                                            <textarea rows="3" name="tanggapan_q{{ $q }}"></textarea>
                                        </div>
                                    </td>
                                    <td class="p-2 align-top border-gray-300">
                                        <textarea rows="8" name="kesesuaian_q{{ $q }}" placeholder="Kesesuaian dengan Unit/Elemen/KUK..."></textarea>
                                    </td>
                                    <td class="p-2 text-center align-middle border-gray-300">
                                        <input type="checkbox" name="pencapaian_q{{ $q }}_ya" class="h-4 w-4">
                                    </td>
                                    <td class="p-2 text-center align-middle border-gray-300">
                                        <input type="checkbox" name="pencapaian_q{{ $q }}_tdk" class="h-4 w-4">
                                    </td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr class="border-t border-gray-200 mb-8">

                {{-- 4. REKOMENDASI HASIL ASESMEN (INPUT ASESOR) --}}
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Rekomendasi Hasil Asesmen</h3>
                    
                    <table class="w-full table-auto table-formal text-sm bg-white">
                        <tbody>
                            <tr>
                                <td class="p-4 align-top font-semibold bg-gray-50 text-gray-800 w-1/3 border-gray-300">
                                    Rekomendasi Asesor:
                                </td>
                                <td class="p-4 align-top border-gray-300 w-2/3">
                                    <p class="mb-3 font-medium text-gray-700">Asesi telah memenuhi/belum memenuhi pencapaian seluruh kriteria unjuk kerja, direkomendasikan:</p>
                                    
                                    {{-- RADIO BUTTON KOMPETEN --}}
                                    <label class="flex items-center mb-2">
                                        <input type="radio" name="rekomendasi_ia04b" value="kompeten" class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                                            @if($rekomendasi_db == 'kompeten') checked @endif>
                                        <span class="ml-2 text-gray-900">Kompeten</span>
                                    </label>
                                    
                                    {{-- RADIO BUTTON BELUM KOMPETEN --}}
                                    <label class="flex items-center">
                                        <input type="radio" name="rekomendasi_ia04b" value="belum kompeten" class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                                            @if($rekomendasi_db == 'belum kompeten') checked @endif>
                                        <span class="ml-2 text-gray-900">Belum Kompeten</span>
                                    </label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <hr class="border-t border-gray-200 mb-8">

                {{-- 5. BAGIAN TANDA TANGAN --}}
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Umpan Balik & Tanda Tangan</h3>
                    
                    {{-- Umpan Balik --}}
                    <label class="block text-sm font-medium text-gray-700 mb-2">Umpan Balik untuk Asesi (Kompeten / Belum Kompeten)</label>
                    <textarea name="umpan_balik_asesi" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 mb-8 p-3 text-sm bg-gray-50" rows="3" placeholder="Tuliskan kesimpulan dan saran di sini..."></textarea>

                    {{-- Kotak Tanda Tangan --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        
                        {{-- 1. Asesor (Tanda Tangan) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Asesor (Tanda Tangan)</label>
                            <div class="w-full h-40 bg-gray-100 border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center">
                                @if($tanda_tangan_asesor_path)
                                    <img src="{{ route('secure.file', ['path' => $tanda_tangan_asesor_path]) }}" alt="Tanda Tangan Asesor" class="signature-img">
                                @else
                                    <div class="signature-placeholder text-xs">Belum TTD</div>
                                @endif
                            </div>
                            <p class="text-xs mt-1 text-gray-500 font-medium">Asesor</p>
                            <p class="mt-3 text-sm font-bold text-gray-900">{{ $asesor->nama_lengkap ?? 'Dr. Zora Bednar' }}</p>
                            <p class="text-xs text-gray-500 font-mono">No. Reg. MET.{{ $asesor->nomor_regis ?? '6478469' }}</p>
                        </div>

                        {{-- 2. Asesi (Tanda Tangan) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Asesi (Tanda Tangan)</label>
                            <div class="w-full h-40 bg-white border-2 border-dashed border-blue-300 rounded-xl flex items-center justify-center cursor-pointer hover:bg-blue-50 hover:border-blue-500 transition-all">
                                @if($tanda_tangan_asesi_path)
                                    <img src="{{ route('secure.file', ['path' => $tanda_tangan_asesi_path]) }}" alt="Tanda Tangan Asesi" class="signature-img">
                                @else
                                    <div class="text-center">
                                        <svg class="mx-auto h-8 w-8 text-blue-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        <p class="text-sm font-medium text-blue-600">Klik untuk Tanda Tangan</p>
                                        <p class="text-xs text-gray-400">Konfirmasi instruksi</p>
                                    </div>
                                @endif
                            </div>
                            <p class="mt-3 text-sm font-bold text-gray-900">{{ $asesi->nama_lengkap ?? 'Dr. Bennie Stokes' }}</p>
                        </div>
                    </div>
                </div>

                <hr class="border-t border-gray-200 mb-8">
                
                {{-- Tombol Aksi --}}
                <div class="mt-10 flex justify-end gap-4">
                    <button class="px-8 py-3 bg-gray-100 text-gray-700 font-semibold rounded-full hover:bg-gray-200 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300">Simpan Draft</button>
                    <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-full hover:bg-blue-700 shadow-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Submit Form</button>
                </div>

                </form>

            </div>
        </main>

        {{-- Overlay untuk Mobile --}}
        <div 
            x-show="$store.sidebar.open" 
            @click="$store.sidebar.open = false"
            x-transition.opacity
            class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
        ></div>

    </div>

</body>
</html>