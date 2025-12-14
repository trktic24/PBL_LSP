@extends('layouts.app-sidebar')

@section('custom_styles')
<style>
    /* Style kustom untuk Portofolio */
    .upload-card {
        transition: all 0.3s ease;
    }
    .upload-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
</style>
@endsection

@section('content')
<div class="bg-white min-h-screen overflow-y-auto p-6 lg:p-12">
    <div class="max-w-5xl mx-auto pb-20"> {{-- Tambah padding bawah agar footer tidak mepet --}}

        {{-- Header --}}
        <div class="mb-8 border-b border-gray-200 pb-6">
            <h1 class="text-2xl lg:text-4xl font-bold text-gray-900 mb-2">Portofolio Asesi</h1>
            <p class="text-gray-600">
                Silakan unggah dokumen portofolio Anda sesuai dengan kategori di bawah ini.
            </p>
        </div>

        {{-- Info Box --}}
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-8 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500">Nama Asesi</p>
                <p class="text-lg font-bold text-gray-900">
                    {{ $asesi->nama_lengkap ?? 'Nama Asesi Tidak Ada' }}
                </p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Skema</p>
                <p class="font-medium text-gray-900">Junior Web Developer</p>
            </div>
        </div>

        {{-- AREA KONTEN UTAMA --}}
        <div class="space-y-8">

            {{-- BLOK 1: PERSYARATAN DASAR --}}
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden upload-card">
                <div class="bg-blue-50 p-4 border-b border-blue-100 flex items-center gap-3">
                    <div class="bg-blue-600 text-white w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">1</div>
                    <h3 class="text-lg font-bold text-gray-800">PERSYARATAN DASAR</h3>
                </div>
                
                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-4">
                        Unggah dokumen persyaratan dasar kompetensi (contoh: KTP, Sertifikat Pelatihan, Raport/Transkrip Nilai, dll).
                    </p>

                    <div class="space-y-4">
                        {{-- Item 1: KTP (SUDAH DIUBAH AGAR KE KANAN) --}}
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="text-blue-500"> 
                                    {{-- Ikon ID Card/KTP yang lebih umum --}}
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Kartu Tanda Penduduk (KTP)</p>
                                    <p class="text-xs text-gray-400">Wajib</p>
                                </div>
                            </div>
                            
                            {{-- AREA KONTROL FILE YANG DIGESER KE KANAN --}}
                            {{-- Tambahkan div pembungkus di sini dan hilangkan 'w-full' dari input agar tidak memakan ruang kiri --}}
                            <div class="flex items-center flex-shrink-0">
                                <input 
                                    type="file" 
                                    class="
                                        block text-sm text-gray-500 
                                        file:mr-4 file:py-2 file:px-4 
                                        file:rounded-lg file:border-none
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-600 
                                        hover:file:bg-blue-100 cursor-pointer
                                    "
                                />
                            </div>
                        </div>

                        {{-- Item 2: Ijazah --}}
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="text-blue-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Ijazah Terakhir / Transkrip</p>
                                    <p class="text-xs text-gray-400">Wajib</p>
                                </div>
                            </div>
                            <div class="flex items-center flex-shrink-0">
                                <input 
                                    type="file" 
                                    class="
                                        block text-sm text-gray-500 
                                        file:mr-4 file:py-2 file:px-4 
                                        file:rounded-lg file:border-none
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-600 
                                        hover:file:bg-blue-100 cursor-pointer
                                    "
                                />
                            </div>
                        </div>
                        
                        {{-- Item 3: Dokument Pendukung Lainnya --}}
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="text-blue-500">
                                    {{-- Menggunakan ikon Folder yang lebih cocok untuk 'Pendukung Lainnya' --}}
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Dokument Pendukung Lainnya</p>
                                </div>
                            </div>
                            <div class="flex items-center flex-shrink-0">
                                <input 
                                    type="file" 
                                    class="
                                        block text-sm text-gray-500 
                                        file:mr-4 file:py-2 file:px-4 
                                        file:rounded-lg file:border-none
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-600 
                                        hover:file:bg-blue-100 cursor-pointer
                                    "
                                />
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
                    <p class="text-sm text-gray-600 mb-4">
                        Unggah kelengkapan administrasi (contoh: Bukti Pembayaran, Formulir Pendaftaran, CV).
                    </p>

                    <div class="space-y-4">
                        
                        {{-- ITEM: Bukti Pembayaran --}}
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="text-blue-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Bukti Pembayaran</p>
                                    <p class="text-xs text-gray-400">Wajib</p>
                                </div>
                            </div>
                            <div class="flex items-center flex-shrink-0">
                                <input 
                                    type="file" 
                                    class="
                                        block text-sm text-gray-500 
                                        file:mr-4 file:py-2 file:px-4 
                                        file:rounded-lg file:border-none
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-600 
                                        hover:file:bg-blue-100 cursor-pointer
                                    "
                                />
                            </div>
                        </div>
                        
                        {{-- ITEM: Formulir Pendaftaran (IKON BARU) --}}
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="text-blue-500">
                                    {{-- Ikon Form dengan Tanda Centang (Pendaftaran Selesai/Formulir) --}}
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Formulir Pendaftaran</p>
                                    <p class="text-xs text-gray-400">Wajib</p>
                                </div>
                            </div>
                            <div class="flex items-center flex-shrink-0">
                                <input 
                                    type="file" 
                                    class="
                                        block text-sm text-gray-500 
                                        file:mr-4 file:py-2 file:px-4 
                                        file:rounded-lg file:border-none
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-600 
                                        hover:file:bg-blue-100 cursor-pointer
                                    "
                                />
                            </div>
                        </div>
                        
                        {{-- Item: CV --}}
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="text-blue-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Curriculum Vitae (CV)</p>
                                    <p class="text-xs text-gray-400">Terbaru</p>
                                </div>
                            </div>
                            <div class="flex items-center flex-shrink-0">
                                <input 
                                    type="file" 
                                    class="
                                        block text-sm text-gray-500 
                                        file:mr-4 file:py-2 file:px-4 
                                        file:rounded-lg file:border-none
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-600 
                                        hover:file:bg-blue-100 cursor-pointer
                                    "
                                />
                            </div>
                        </div>

                        {{-- Item: File Pendukung Lainnya --}}
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="text-blue-500">
                                    {{-- Mengganti ikon dengan Paper Clip --}}
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">File Pendukung Lainnya</p>
                                </div>
                            </div>
                            <div class="flex items-center flex-shrink-0">
                                <input 
                                    type="file" 
                                    class="
                                        block text-sm text-gray-500 
                                        file:mr-4 file:py-2 file:px-4 
                                        file:rounded-lg file:border-none
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-600 
                                        hover:file:bg-blue-100 cursor-pointer
                                    "
                                />
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        {{-- Tombol Footer --}}
        <div class="flex justify-between items-center mt-12 border-t border-gray-200 pt-6">
            <a href="#" class="px-8 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition shadow-sm">
                Kembali
            </a>
            <button class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5">
                Simpan Portofolio
            </button>
        </div>

    </div>
</div>
@endsection