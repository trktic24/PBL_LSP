<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FR.IA.09 - Pertanyaan Wawancara</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
    /* 1. Kustomisasi Font Global & Scrollbar */
        body { 
            font-family: 'Poppins', sans-serif; 
            overflow: hidden; 
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        
        /* 2. Style Sidebar (Fixed Position) */
        .sidebar {
            position: fixed; 
            top: 0;
            left: 0;
            bottom: 0;
            width: 16rem; 
            background-color: #ffffff; 
            border-right: 1px solid #e5e7eb; 
            padding: 1.5rem; 
            color: #1f2937; 
            overflow-y: auto; 
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

        /* 3. Style Konten Utama (Form IA.09) */
        .content-font-base {
            font-size: 0.875rem; 
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
            /* Styling untuk textarea di dalam tabel */
            border: 1px solid #d1d5db;
            background: #f9fafb;
            padding: 0.5rem;
            margin: 0;
            resize: none;
            outline: none;
            box-shadow: none;
            width: 100%;
            height: 100%; 
        }
        .table-formal td.readonly-cell textarea {
            border: none !important;
            background: transparent !important;
            padding: 0;
        }

        /* 4. MEDIA QUERY untuk Margin Kiri Main Content (Menjamin kerapatan di Desktop) */
        @media (min-width: 1024px) { 
            .main-content-shifted {
                margin-left: 16rem !important; 
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

    <div class="flex h-screen" x-data>

        {{-- SIDEBAR - Menggunakan Komponen x-sidebar2 --}}
        <x-sidebar2 :idAsesi="$asesi->id_asesi ?? null" :sertifikasi="$sertifikasi ?? null" />

        {{-- MAIN CONTENT --}}
        <main class="flex-1 bg-white p-8 lg:p-12 overflow-y-auto ml-0 main-content-shifted">
            
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
                
                {{-- DUMMY DATA / FALLBACK UNTUK VIEWS --}}
                @php
                    // Mengambil data dari Controller (dengan fallback)
                    $skema_sertifikasi = $skema->nama_skema ?? 'Junior Web Developer';
                    $nomor_skema = $skema->nomor_skema ?? 'J.620100.024.48';
                    $nama_asesor = $asesor->nama_lengkap ?? 'DR. HENDRI (N/A)';
                    $nama_asesi = $asesi->nama_lengkap ?? 'AGUS (12345)';
                    $tanggal = $tanggal_pelaksanaan ?? date('d/m/Y');
                    $jenis_tuk = $jenis_tuk_db ?? 'Sewaktu'; 
                    $judul_kegiatan_db = 'Proyek Pembuatan Sistem Informasi Pendaftaran Mahasiswa Baru'; 

                    // Variabel Tanda Tangan (FIXED: Pastikan ada fallback untuk menghindari Undefined Variable)
                    $tanda_tangan_asesor_path = $tanda_tangan_asesor_path ?? null; 
                    $tanda_tangan_asesi_path = $tanda_tangan_asesi_path ?? null; 

                    // Data DUMMY Pertanyaan Wawancara & Bukti Portofolio (untuk loop)
                    $pertanyaan_dummy = [
                        ['no' => 1, 'pertanyaan' => 'Sesuai dengan bukti no. 1 yang Anda ajukan, jelaskan cara Anda menganalisis kebutuhan pengguna.', 'bukti' => 'Dokumen Kebutuhan'],
                        ['no' => 2, 'pertanyaan' => 'Bagaimana Anda memastikan kode yang Anda buat mematuhi standar keamanan?', 'bukti' => 'Laporan Uji Keamanan'],
                        ['no' => 3, 'pertanyaan' => 'Jelaskan tahapan *debugging* yang efisien.', 'bukti' => 'Screenshoot Konsol Error'],
                        ['no' => 4, 'pertanyaan' => 'Bagaimana Anda mengelola alur data untuk menghindari redundansi?', 'bukti' => 'Diagram ERD'],
                    ];

                    // Data DUMMY Unit Kompetensi (Digunakan jika $unitsToDisplay dari Controller kosong)
                    $dummyUnits = [
                        ['code' => 'J.620100.001.01', 'title' => 'Menggunakan Struktur Data'],
                        ['code' => 'J.620100.002.02', 'title' => 'Mengimplementasikan User Interface'],
                        ['code' => 'J.620100.003.03', 'title' => 'Melakukan Debugging'],
                    ];
                    $unitsToDisplay = empty($unitsToDisplay) ? $dummyUnits : $unitsToDisplay;
                    $kelompok_pekerjaan = empty($unitsToDisplay) ? 'Pengembangan Aplikasi' : $kelompok_pekerjaan;

                @endphp
                
                {{-- FORM START --}}
                <form action="{{ route('asesmen.ia09.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id_sertifikasi" value="{{ $sertifikasi->id_data_sertifikasi_asesi ?? 0 }}">
                
                {{-- Judul & Kop --}}
                <div class="mb-8 text-center"> 
                    <div class="mb-4 text-gray-400 text-sm font-bold italic text-left">Logo BNSP</div> 
                    
                    <h1 class="text-2xl font-bold text-black uppercase tracking-wide border-b-2 border-gray-100 pb-4 mb-6 inline-block">
                        FR.IA.09. PW – PERTANYAAN WAWANCARA
                    </h1>
                </div>

                {{-- INFORMASI UMUM (GAYA FR.IA.04/SCREENSHOT) --}}
                <div class="info-data-box mb-10 text-gray-700">
                    <div class="grid grid-cols-2 gap-y-3 gap-x-8">
                        
                        {{-- Kolom Kiri: Skema & TUK & Judul Kegiatan --}}
                        <div>
                            <div class="font-semibold text-gray-500 text-sm">Skema Sertifikasi</div>
                            <div class="font-bold text-base text-gray-900 mb-4">{{ $skema_sertifikasi }}</div>
                            
                            <div class="font-semibold text-gray-500 text-sm">Nomor Skema</div>
                            <div class="font-bold text-base text-gray-900 mb-4">{{ $nomor_skema }}</div>
                            
                            <div class="font-semibold text-gray-500 text-sm">TUK</div>
                            <div class="flex flex-wrap gap-x-6 mt-1 custom-radio text-sm mb-4">
                                {{-- Radio Button Pertama: Sewaktu (READ ONLY DARI DB) --}}
                                <label class="inline-flex items-center">
                                    <input type="radio" value="sewaktu" class="h-4 w-4 text-blue-600 border-gray-300" 
                                        @if(isset($jenis_tuk) && $jenis_tuk == 'Sewaktu') checked @endif
                                        disabled>
                                    <span class="ml-2 @if(isset($jenis_tuk) && $jenis_tuk == 'Sewaktu') font-bold text-gray-900 @else text-gray-700 @endif">Sewaktu</span>
                                </label>
                                
                                {{-- Radio Button Kedua: Tempat Kerja (READ ONLY DARI DB) --}}
                                <label class="inline-flex items-center">
                                    <input type="radio" value="tempat_kerja" class="h-4 w-4 text-blue-600 border-gray-300"
                                        @if(isset($jenis_tuk) && $jenis_tuk == 'Tempat Kerja') checked @endif
                                        disabled>
                                    <span class="ml-2 @if(isset($jenis_tuk) && $jenis_tuk == 'Tempat Kerja') font-bold text-gray-900 @else text-gray-700 @endif">Tempat Kerja</span>
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
                            <div class="font-bold text-base text-gray-900 mb-4">{{ $nama_asesor }}</div>

                            <div class="font-semibold text-gray-500 text-sm">Nama Asesi</div>
                            <div class="font-bold text-base text-gray-900 mb-4">{{ $nama_asesi }}</p>

                            <div class="font-semibold text-gray-500 text-sm">Tanggal Pelaksanaan</div>
                            <div class="mt-1">
                                <input type="text" value="{{ $tanggal }}" class="border-gray-300 rounded-lg shadow-sm text-base text-gray-900 font-bold p-2 w-40 text-center" readonly>
                            </div>
                        </div>

                    </div>
                </div>

                <hr class="border-t border-gray-200 mb-8">

                {{-- PANDUAN BAGI ASESOR (READ-ONLY) --}}
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-8 text-sm text-gray-700 shadow-sm">
                    <h3 class="font-bold text-blue-900 mb-2">PANDUAN BAGI ASESOR:</h3>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Pertanyaan wawancara dapat dilakukan untuk keseluruhan unit kompetensi dalam skema sertifikasi atau dilakukan untuk masing-masing kelompok pekerjaan dalam satu skema sertifikasi.</li>
                        <li>Isilah bukti portofolio sesuai dengan bukti yang diminta pada skema sertifikasi sebagaimana yang telah dibuat pada FR.IA.08</li>
                        <li>Ajukan pertanyaan verifikasi portofolio untuk semua unit/elemen kompetensi yang di *checklist* pada FR.IA.08</li>
                        <li>Ajukan pertanyaan kepada asesi sebagai tindak lanjut hasil verifikasi portofolio.</li>
                        <li>Jika hasil verifikasi portofolio telah memenuhi aturan bukti maka pertanyaan wawancara tidak perlu dilakukan terhadap bukti tersebut.</li>
                        <li>Tuliskan pencapaian atas setiap kesimpulan pertanyaan wawancara dengan cara mencentang (√) “Ya” atau “Tidak”.</li>
                    </ul>
                </div>
                
                {{-- 1. TABEL UNIT KOMPETENSI (Lihat) --}}
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Unit Kompetensi (Lihat)</h3>
                    
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
                                @forelse ($unitsToDisplay as $index => $unit)
                                <tr class="bg-white hover:bg-gray-50">
                                    @if ($index === 0)
                                    <td rowspan="{{ count($unitsToDisplay) }}" class="p-3 align-top font-semibold text-gray-800 border-gray-300">
                                        {{ $kelompok_pekerjaan }}
                                    </td>
                                    @endif
                                    <td class="p-2 text-center align-top border-gray-300">{{ $index + 1 }}.</td>
                                    <td class="p-2 align-top border-gray-300 font-medium text-blue-800">{{ $unit['code'] }}</td>
                                    <td class="p-2 align-top border-gray-300">{{ $unit['title'] }}</td>
                                </tr>
                                @empty
                                <tr class="bg-white hover:bg-gray-50">
                                    <td colspan="4" class="p-4 text-center text-gray-500">Data unit kompetensi tidak ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr class="border-t border-gray-200 mb-8">
                
                {{-- 2A. TABEL BUKTI PORTOFOLIO --}}
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Bukti Portofolio</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto table-formal text-sm min-w-[500px]">
                            <thead class="bg-gray-900 text-white text-center font-semibold">
                                <tr>
                                    <th class="p-3 w-10 border-gray-300">No.</th>
                                    <th class="p-3 w-full border-gray-300">Bukti Portofolio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pertanyaan_dummy as $q)
                                <tr class="bg-white hover:bg-gray-50">
                                    <td class="p-2 text-center align-top border-gray-300 font-bold w-10">{{ $q['no'] }}.</td>
                                    <td class="p-2 align-top border-gray-300">
                                        {{-- Input Bukti Portofolio --}}
                                        <textarea rows="2" name="bukti_portofolio_{{ $q['no'] }}" placeholder="Masukkan Judul Bukti Portofolio">{{ $q['bukti'] }}</textarea>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <hr class="border-t border-gray-200 mb-8">


                {{-- 2B. TABEL DAFTAR PERTANYAAN WAWANCARA (Pencapaian Dihapus, Kolom No. Dirampingkan) --}}
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Daftar Pertanyaan Wawancara</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto table-formal text-sm min-w-[900px]">
                            <thead class="bg-gray-900 text-white text-center font-semibold">
                                <tr>
                                    <th class="p-3 border-gray-300" style="width: 5%;" rowspan="2">No.</th>
                                    <th class="p-3 w-1/2 border-gray-300" rowspan="2">Daftar Pertanyaan Wawancara</th>
                                    <th class="p-3 w-1/4 border-gray-300" rowspan="2">Kesimpulan Jawaban Asesi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pertanyaan_dummy as $q)
                                <tr class="bg-white hover:bg-gray-50">
                                    <td class="p-2 text-center align-top border-gray-300 font-bold" style="width: 5%;">{{ $q['no'] }}.</td>
                                    <td class="p-2 align-top border-gray-300">
                                        {{-- DAFTAR PERTANYAAN WAWANCARA --}}
                                        <textarea rows="4" name="pertanyaan_{{ $q['no'] }}" placeholder="Pertanyaan... (Asesor mengisi/memodifikasi)">{{ $q['pertanyaan'] }}</textarea>
                                    </td>
                                    <td class="p-2 align-top border-gray-300">
                                        {{-- KESIMPULAN JAWABAN ASESI --}}
                                        <textarea rows="4" name="kesimpulan_{{ $q['no'] }}" placeholder="Kesimpulan Asesor atas jawaban Asesi"></textarea>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr class="border-t border-gray-200 mb-8">

                {{-- 3. BLOK TANDA TANGAN (Sama seperti FRIA04) --}}
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Konfirmasi Tanda Tangan</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        
                        {{-- 1. Asesi --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Asesi (Tanda Tangan)</label>
                            <div class="w-full h-40 bg-gray-100 border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center">
                                @if($tanda_tangan_asesi_path)
                                    <img src="{{ asset('storage/' . $tanda_tangan_asesi_path) }}" alt="Tanda Tangan Asesi" class="signature-img">
                                @else
                                    <p class="text-gray-400 text-sm">TTD Asesi (Belum Ada)</p>
                                @endif
                            </div>
                            <p class="mt-3 text-sm font-bold text-gray-900">{{ $nama_asesi }}</p>
                            <p class="text-xs text-gray-500">Tanggal: {{ $tanggal }}</p>
                        </div>

                        {{-- 2. Asesor --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Asesor (Tanda Tangan)</label>
                            <div class="w-full h-40 bg-white border-2 border-dashed border-blue-300 rounded-xl flex items-center justify-center cursor-pointer hover:bg-blue-50 hover:border-blue-500 transition-all">
                                @if($tanda_tangan_asesor_path)
                                    <img src="{{ asset('storage/' . $tanda_tangan_asesor_path) }}" alt="Tanda Tangan Asesor" class="signature-img">
                                @else
                                <div class="text-center">
                                    <svg class="mx-auto h-8 w-8 text-blue-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    <p class="text-sm font-medium text-blue-600">Klik untuk Tanda Tangan</p>
                                    <p class="text-xs text-gray-400">Konfirmasi/Tanda Tangan</p>
                                </div>
                                @endif
                            </div>
                            <p class="mt-3 text-sm font-bold text-gray-900">{{ $nama_asesor }}</p>
                            <p class="text-xs text-gray-500">No. Reg. MET. N/A</p>
                        </div>
                    </div>
                </div>

                <hr class="border-t border-gray-200 mb-8">
                
                {{-- Tombol Aksi --}}
                <div class="mt-10 flex justify-end gap-4">
                    <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-full hover:bg-blue-700 shadow-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Simpan Penilaian Wawancara
                    </button>
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