<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Ubah Judul Sesuai Konten --}}
    <title>Portofolio Asesi</title> 
    
    {{-- Link Tailwind & Font Poppins Sama dengan IA04 --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* 1. Kustomisasi Font Global & Scrollbar (SAMA DENGAN IA04) */
        body { 
            font-family: 'Poppins', sans-serif; 
            overflow: hidden; 
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        
        /* 2. Style Sidebar (SAMA DENGAN IA04) */
        .sidebar {
            position: fixed; 
            top: 0;
            left: 0;
            bottom: 0;
            width: 16rem; /* w-64 */
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

        /* 3. Style Konten Utama (SAMA DENGAN IA04) */
        .content-font-base {
            font-size: 0.875rem; 
        }
        
        /* 4. MEDIA QUERY untuk Margin Kiri Main Content (SAMA DENGAN IA04) */
        @media (min-width: 1024px) { 
            .main-content-shifted {
                margin-left: 16rem !important; /* Menempel persis ke sidebar w-64 */
            }
        }
        
        /* === KUSTOMISASI DARI PORTOFOLIO (DIATUR WARNANYA) === */
        .upload-card {
            transition: all 0.3s ease;
        }
        .upload-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .file-input-container {
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 300px;
        }
        
        /* Gaya untuk tombol Cancel (sebelum submit) */
        .client-clear-file-button {
            font-size: 0.875rem; 
            font-weight: 600; 
            padding: 0.5rem 1rem; 
            border-radius: 0.5rem; 
            line-height: 1.25;
            
            /* WARNA KUNING (TIDAK BERUBAH) */
            color: #b45309; 
            background-color: #fef3c7; 
            border: 1px solid #fde68a; 
            
            cursor: pointer;
            transition: all 0.2s ease;
            display: none; 
        }
        .client-clear-file-button:hover {
            background-color: #fcd34d; 
            color: #92400e;
            border-color: #f59e0b;
        }
        
        /* Mengatur Input File (SAMA DENGAN PORTOFOLIO LAMA) */
        input[type="file"]::file-selector-button {
            content: 'Choose File';
        }
        input[type="file"] {
            color: transparent; 
            width: 100%;
        }
        input[type="file"]:focus,
        input[type="file"]:focus-visible {
            outline: none !important; 
            box-shadow: none !important; 
            border-color: transparent !important; 
        }
        input[type="file"]:not(:hover)::after {
            content: attr(data-file-name);
            color: #4a5568; 
            position: absolute;
            left: 100px; 
            pointer-events: none;
            top: 8px; 
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px; 
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
{{-- Tambahkan class bg-gray-100 dan body-font --}}
<body class="bg-gray-100 font-sans antialiased">

    <div class="flex h-screen" x-data>

        {{-- SIDEBAR - PENTING: Anda harus memastikan komponen x-sidebar2 tersedia di lingkungan Laravel/Blade Anda --}}
        {{-- ASUMSI: Komponen x-sidebar2 yang sama digunakan untuk IA04 --}}
        {{-- Ganti dengan data yang sesuai untuk Portofolio jika diperlukan --}}
        <x-sidebar2 :idAsesi="$asesi->id_asesi ?? null" :sertifikasi="$sertifikasi ?? null" />

        {{-- MAIN CONTENT - Geser ke kanan sebesar sidebar (16rem) dan bisa di-scroll --}}
        <main class="flex-1 bg-white p-8 lg:p-12 overflow-y-auto ml-0 main-content-shifted">
            
            {{-- TOMBOL MENU MOBILE --}}
            <div class="p-4 bg-blue-600 text-white flex justify-between items-center shadow-md sticky top-0 z-30 flex-shrink-0 mb-8 lg:hidden">
                <span class="font-bold">Portofolio Asesi</span>
                <button @click="$store.sidebar.open = true" class="p-2 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <div class="max-w-6xl mx-auto content-font-base pb-20">
                {{-- Konten Portofolio dimulai di sini --}}
                
                {{-- Pesan Sukses/Error/Info (Dibiarkan) --}}
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Berhasil!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Gagal!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                @if (session('info'))
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Info:</strong>
                        <span class="block sm:inline">{{ session('info') }}</span>
                    </div>
                @endif
                
                {{-- Header --}}
                <div class="mb-8 border-b border-gray-200 pb-6">
                    {{-- Ganti text-4xl agar sesuai dengan font-size IA04 yang lebih kecil di konten --}}
                    <h1 class="text-xl lg:text-2xl font-bold text-gray-900 mb-2">Portofolio Asesi</h1>
                    <p class="text-gray-600 content-font-base">
                        Silakan unggah dokumen portofolio Anda sesuai dengan kategori di bawah ini.
                    </p>
                </div>

                {{-- Info Box (Dibiarkan) --}}
                <div class="info-data-box bg-gray-50 border border-gray-200 rounded-xl p-6 mb-8 shadow-sm flex justify-between items-center text-gray-700">
                    <div>
                        <p class="font-semibold text-gray-500 text-sm">Nama Asesi</p>
                        <p class="font-bold text-base text-gray-900">
                            {{ $asesi->nama_lengkap ?? 'Data Asesi Tidak Ditemukan' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-500 text-sm">Skema</p>
                        <p class="font-bold text-base text-gray-900">Junior Web Developer</p>
                    </div>
                </div>

                {{-- AREA KONTEN UTAMA DIBUNGKUS OLEH FORM --}}
                <form id="portofolio-form" action="{{ route('portofolio.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf 
                    <div class="space-y-8">
                        
                        {{-- BLOK 1: PERSYARATAN DASAR --}}
                        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden upload-card">
                            {{-- Ubah warna latar belakang header block dari blue-50 ke blue-50 (tetap) dan blue-100 (tetap) --}}
                            {{-- Ubah warna icon & text-blue-500 ke blue-600 agar konsisten dengan IA04's primary color --}}
                            <div class="bg-blue-50 p-4 border-b border-blue-100 flex items-center gap-3">
                                <div class="bg-blue-600 text-white w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">1</div>
                                <h3 class="text-lg font-bold text-gray-800">PERSYARATAN DASAR</h3>
                            </div>
                            
                            <div class="p-6">
                                <div class="space-y-4">
                                    
                                    {{-- Item 1: KTP --}}
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                                        <div class="flex items-center gap-3">
                                            {{-- Ubah icon ke blue-600 --}}
                                            <div class="text-blue-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg></div>
                                            <div><p class="text-sm font-medium text-gray-900">Kartu Tanda Penduduk (KTP)</p><p class="text-xs text-gray-400">Wajib</p></div>
                                        </div>
                                        <div class="flex items-center flex-shrink-0 file-input-container">
                                            {{-- Ubah warna tombol file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 (sudah bagus, sesuaikan dengan IA04) --}}
                                            <input onchange="handleFileChange(this, 'ktp')" type="file" name="ktp" id="file-ktp" class="block text-sm text-gray-500 file:mr-4 file:py-2 file:px-10 file:rounded-lg file:border-none file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 cursor-pointer"/>
                                            <button type="button" class="client-clear-file-button" id="clear-ktp" onclick="clearClientFile('file-ktp', 'clear-ktp')">Cancel</button>
                                        </div>
                                    </div>

                                    {{-- Item 2: Ijazah --}}
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                                        <div class="flex items-center gap-3">
                                            {{-- Ubah icon ke blue-600 --}}
                                            <div class="text-blue-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg></div>
                                            <div><p class="text-sm font-medium text-gray-900">Ijazah Terakhir / Transkrip</p><p class="text-xs text-gray-400">Wajib</p></div>
                                        </div>
                                        <div class="flex items-center flex-shrink-0 file-input-container">
                                            <input onchange="handleFileChange(this, 'ijazah')" type="file" name="ijazah" id="file-ijazah" class="block text-sm text-gray-500 file:mr-4 file:py-2 file:px-10 file:rounded-lg file:border-none file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 cursor-pointer"/>
                                            <button type="button" class="client-clear-file-button" id="clear-ijazah" onclick="clearClientFile('file-ijazah', 'clear-ijazah')">Cancel</button>
                                        </div>
                                    </div>
                                    
                                    {{-- Item 3: Dokument Pendukung Lainnya --}}
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                                        <div class="flex items-center gap-3">
                                            {{-- Ubah icon ke blue-600 --}}
                                            <div class="text-blue-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg></div>
                                            <div><p class="text-sm font-medium text-gray-900">Dokument Pendukung Lainnya</p></div>
                                        </div>
                                        <div class="flex items-center flex-shrink-0 file-input-container">
                                            <input onchange="handleFileChange(this, 'pendukung-dasar')" type="file" name="pendukung_dasar" id="file-pendukung-dasar" class="block text-sm text-gray-500 file:mr-4 file:py-2 file:px-10 file:rounded-lg file:border-none file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 cursor-pointer"/>
                                            <button type="button" class="client-clear-file-button" id="clear-pendukung-dasar" onclick="clearClientFile('file-pendukung-dasar', 'clear-pendukung-dasar')">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- BLOK 2: PERSYARATAN ADMINISTRATIF --}}
                        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden upload-card">
                            <div class="bg-blue-50 p-4 border-b border-blue-100 flex items-center gap-3">
                                <div class="bg-blue-600 text-white w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">2</div>
                                <h3 class="text-lg font-bold text-gray-800">PERSYARATAN ADMINISTRATIF</h3>
                            </div>
                            
                            <div class="p-6">
                                <div class="space-y-4">
                                    
                                    {{-- ITEM: Bukti Pembayaran --}}
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                                        <div class="flex items-center gap-3">
                                            {{-- Ubah icon ke blue-600 --}}
                                            <div class="text-blue-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></div>
                                            <div><p class="text-sm font-medium text-gray-900">Bukti Pembayaran</p><p class="text-xs text-gray-400">Wajib</p></div>
                                        </div>
                                        <div class="flex items-center flex-shrink-0 file-input-container">
                                            <input onchange="handleFileChange(this, 'bukti-pembayaran')" type="file" name="bukti_pembayaran" id="file-bukti-pembayaran" class="block text-sm text-gray-500 file:mr-4 file:py-2 file:px-10 file:rounded-lg file:border-none file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 cursor-pointer"/>
                                            <button type="button" class="client-clear-file-button" id="clear-bukti-pembayaran" onclick="clearClientFile('file-bukti-pembayaran', 'clear-bukti-pembayaran')">Cancel</button>
                                        </div>
                                    </div>
                                    
                                    {{-- ITEM: Formulir Pendaftaran --}}
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                                        <div class="flex items-center gap-3">
                                            {{-- Ubah icon ke blue-600 --}}
                                            <div class="text-blue-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg></div>
                                            <div><p class="text-sm font-medium text-gray-900">Formulir Pendaftaran</p><p class="text-xs text-gray-400">Wajib</p></div>
                                        </div>
                                        <div class="flex items-center flex-shrink-0 file-input-container">
                                            <input onchange="handleFileChange(this, 'formulir-pendaftaran')" type="file" name="formulir_pendaftaran" id="file-formulir-pendaftaran" class="block text-sm text-gray-500 file:mr-4 file:py-2 file:px-10 file:rounded-lg file:border-none file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 cursor-pointer"/>
                                            <button type="button" class="client-clear-file-button" id="clear-formulir-pendaftaran" onclick="clearClientFile('file-formulir-pendaftaran', 'clear-formulir-pendaftaran')">Cancel</button>
                                        </div>
                                    </div>
                                    
                                    {{-- ITEM: CV --}}
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                                        <div class="flex items-center gap-3">
                                            {{-- Ubah icon ke blue-600 --}}
                                            <div class="text-blue-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></div>
                                            <div><p class="text-sm font-medium text-gray-900">Curriculum Vitae (CV)</p><p class="text-xs text-gray-400">Terbaru</p></div>
                                        </div>
                                        <div class="flex items-center flex-shrink-0 file-input-container">
                                            <input onchange="handleFileChange(this, 'cv')" type="file" name="cv" id="file-cv" class="block text-sm text-gray-500 file:mr-4 file:py-2 file:px-10 file:rounded-lg file:border-none file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 cursor-pointer"/>
                                            <button type="button" class="client-clear-file-button" id="clear-cv" onclick="clearClientFile('file-cv', 'clear-cv')">Cancel</button>
                                        </div>
                                    </div>
                                    
                                    {{-- ITEM: File Pendukung Lainnya --}}
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                                        <div class="flex items-center gap-3">
                                            {{-- Ubah icon ke blue-600 --}}
                                            <div class="text-blue-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg></div>
                                            <div><p class="text-sm font-medium text-gray-900">File Pendukung Lainnya (Admin)</p></div>
                                        </div>
                                        <div class="flex items-center flex-shrink-0 file-input-container">
                                            <input onchange="handleFileChange(this, 'pendukung-admin')" type="file" name="pendukung_admin" id="file-pendukung-admin" class="block text-sm text-gray-500 file:mr-4 file:py-2 file:px-10 file:rounded-lg file:border-none file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 cursor-pointer"/>
                                            <button type="button" class="client-clear-file-button" id="clear-pendukung-admin" onclick="clearClientFile('file-pendukung-admin', 'clear-pendukung-admin')">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
                {{-- AKHIR FORM UPLOAD --}}

                {{-- Tombol Footer --}}
                <div class="flex justify-between items-center mt-12 border-t border-gray-200 pt-6">
                    {{-- Ubah styling tombol kembali --}}
                    <a href="#" class="px-8 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-full hover:bg-gray-50 shadow-md transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2">
                        Kembali
                    </a>
                    {{-- Ubah styling tombol submit agar sama dengan tombol simpan IA04 (rounded-full & green) --}}
                    <button type="submit" form="portofolio-form" class="px-8 py-3 bg-green-600 text-white font-semibold rounded-full hover:bg-green-700 shadow-md transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        Simpan Portofolio
                    </button>
                </div>

            </div>
        </main>

        {{-- Overlay untuk Mobile (SAMA DENGAN IA04) --}}
        <div 
            x-show="$store.sidebar.open" 
            @click="$store.sidebar.open = false"
            x-transition.opacity
            class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
        ></div>

    </div>

{{-- Skrip JavaScript Anda tetap dipertahankan --}}
<script>
    // Fungsi untuk menyembunyikan/menampilkan tombol Cancel saat halaman dimuat dan saat file dipilih
    document.addEventListener('DOMContentLoaded', (event) => {
        // Sembunyikan semua tombol Cancel saat DOM selesai dimuat
        const buttons = document.querySelectorAll('.client-clear-file-button');
        buttons.forEach(button => {
            button.style.display = 'none';
        });

        // Cek status input file saat dimuat dan perbarui data-file-name
        const inputs = document.querySelectorAll('input[type="file"]');
        inputs.forEach(input => {
            if (input.files.length > 0) {
                input.setAttribute('data-file-name', input.files[0].name);
            } else {
                input.removeAttribute('data-file-name');
            }
        });
    });


    /**
     * Fungsi yang dipanggil saat status input file berubah.
     */
    function handleFileChange(input, type) {
        const clearButtonId = 'clear-' + type;
        const clearButton = document.getElementById(clearButtonId);
        
        if (input.files.length > 0) {
            // Update atribut data-file-name
            input.setAttribute('data-file-name', input.files[0].name);
            clearButton.style.display = 'inline-block';
        } else {
            // Hapus atribut data-file-name saat dibatalkan
            input.removeAttribute('data-file-name');
            clearButton.style.display = 'none';
        }
    }

    /**
     * Fungsi untuk membatalkan pilihan file dari input sebelum form di-submit.
     */
    function clearClientFile(fileInputId, clearButtonId) {
        const fileInput = document.getElementById(fileInputId);
        const clearButton = document.getElementById(clearButtonId);

        // 1. Reset input file
        fileInput.value = null;

        // 2. Hapus atribut data-file-name
        fileInput.removeAttribute('data-file-name');

        // 3. Sembunyikan tombol Cancel
        clearButton.style.display = 'none';
    }
</script>
</body>
</html>