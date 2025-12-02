@extends('layouts.app-sidebar')

@section('custom_styles')
<style>
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
        
        /* Warna Kuning */
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
    
    /* === PERBAIKAN UNTUK MENGHILANGKAN FOKUS & TEXT BAWAAN === */

    /* 1. Menghilangkan "No file chosen" dan nama file yang dipilih */
    /* Ini menargetkan area teks di input file (web-kit adalah untuk Chrome/Safari) */
    input[type="file"]::file-selector-button {
        /* Memastikan tombol Choose File tetap terlihat */
        content: 'Choose File';
    }

    /* Ini menyembunyikan teks 'No file chosen' di Chrome/Edge/Safari */
    input[type="file"] {
        color: transparent; /* Sembunyikan teks di dalam input */
        width: 100%;
    }
    
    /* 2. Menghilangkan Kotak Hitam (Outline Fokus) */
    input[type="file"]:focus,
    input[type="file"]:focus-visible {
        outline: none !important; /* Hapus outline bawaan browser */
        box-shadow: none !important; /* Hapus shadow fokus (ring) dari framework */
        border-color: transparent !important; /* Pastikan border tidak berubah warna */
    }

    /* PENTING: MENGEMBALIKAN WARNA TEKS SAAT FILE DIPILIH */
    /* Jika file sudah dipilih, browser mungkin menampilkan namanya di samping tombol. */
    /* Kita kembalikan warna teks agar nama file yang sudah dipilih tidak ikut hilang */
    input[type="file"]:not(:hover)::after {
        content: attr(data-file-name);
        color: #4a5568; /* Warna abu-abu yang wajar */
        position: absolute;
        left: 100px; /* Sesuaikan posisi teks agar berada setelah tombol choose file */
        pointer-events: none;
        top: 8px; /* Sesuaikan vertikal */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 150px; 
    }
    
</style>
@endsection

@section('content')
<div class="bg-white min-h-screen overflow-y-auto p-6 lg:p-12">
    <div class="max-w-5xl mx-auto pb-20">

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
        
        {{-- Header (Dibiarkan) --}}
        <div class="mb-8 border-b border-gray-200 pb-6">
            <h1 class="text-2xl lg:text-4xl font-bold text-gray-900 mb-2">Portofolio Asesi</h1>
            <p class="text-gray-600">
                Silakan unggah dokumen portofolio Anda sesuai dengan kategori di bawah ini.
            </p>
        </div>

        {{-- Info Box (Dibiarkan) --}}
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-8 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500">Nama Asesi</p>
                <p class="text-lg font-bold text-gray-900">
                    {{ $asesi->nama_lengkap ?? 'Data Asesi Tidak Ditemukan' }}
                </p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Skema</p>
                <p class="font-medium text-gray-900">Junior Web Developer</p>
            </div>
        </div>

        {{-- AREA KONTEN UTAMA DIBUNGKUS OLEH FORM --}}
        <form id="portofolio-form" action="{{ route('portofolio.store') }}" method="POST" enctype="multipart/form-data">
            @csrf 
            <div class="space-y-8">
                
                {{-- BLOK 1: PERSYARATAN DASAR --}}
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden upload-card">
                    <div class="bg-blue-50 p-4 border-b border-blue-100 flex items-center gap-3">
                        <div class="bg-blue-600 text-white w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">1</div>
                        <h3 class="text-lg font-bold text-gray-800">PERSYARATAN DASAR</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            
                            {{-- Item 1: KTP --}}
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="text-blue-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg></div>
                                    <div><p class="text-sm font-medium text-gray-900">Kartu Tanda Penduduk (KTP)</p><p class="text-xs text-gray-400">Wajib</p></div>
                                </div>
                                <div class="flex items-center flex-shrink-0 file-input-container">
                                    <input onchange="handleFileChange(this, 'ktp')" type="file" name="ktp" id="file-ktp" class="block text-sm text-gray-500 file:mr-4 file:py-2 file:px-10 file:rounded-lg file:border-none file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 cursor-pointer"/>
                                    <button type="button" class="client-clear-file-button" id="clear-ktp" onclick="clearClientFile('file-ktp', 'clear-ktp')">Cancel</button>
                                </div>
                            </div>

                            {{-- Item 2: Ijazah --}}
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="text-blue-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg></div>
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
                                    <div class="text-blue-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg></div>
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
                                    <div class="text-blue-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></div>
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
                                    <div class="text-blue-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg></div>
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
                                    <div class="text-blue-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></div>
                                    <div><p class="text-sm font-medium text-gray-900">Curriculum Vitae (CV)</p><p class="text-xs text-gray-400">Terbaru</p></div>
                                </div>
                                <div class="flex items-center flex-shrink-0 file-input-container">
                                    <input onchange="handleFileChange(this, 'cv')" type="file" name="cv" id="file-cv" class="block text-sm text-gray-500 file:mr-4 file:py-2 file:px-10 file:rounded-lg file:border-none file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 cursor-pointer"/>
                                    <button type="button" class="client-clear-file-button" id="clear-cv" onclick="clearClientFile('file-cv', 'clear-cv')">Cancel</button>
                                </div>
                            </div>
                            
                            {{-- Item: File Pendukung Lainnya --}}
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="text-blue-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg></div>
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

        {{-- Tombol Footer (Dibiarkan) --}}
        <div class="flex justify-between items-center mt-12 border-t border-gray-200 pt-6">
            <a href="#" class="px-8 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition shadow-sm">
                Kembali
            </a>
            <button type="submit" form="portofolio-form" class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5">
                Simpan Portofolio
            </button>
        </div>

    </div>
</div>

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
@endsection