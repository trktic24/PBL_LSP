@use('App\Models\DataSertifikasiAsesi')

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Kelengkapan Pemohon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="bg-gray-50 md:bg-white font-sans">

    {{-- WRAPPER UTAMA --}}
    <div class="flex min-h-screen flex-col md:flex-row md:h-screen md:overflow-hidden">

        {{-- ====================================================================== --}}
        {{-- 1. SIDEBAR (Desktop Only) --}}
        {{-- ====================================================================== --}}
        <div class="hidden md:block md:w-80 flex-shrink-0">
            <x-sidebar :idAsesi="$asesi->id_asesi" :sertifikasi="$sertifikasi"></x-sidebar>
        </div>

        {{-- ====================================================================== --}}
        {{-- 2. HEADER MOBILE (Component Baru) --}}
        {{-- ====================================================================== --}}
        @php
            $gambarSkema = ($sertifikasi->jadwal && $sertifikasi->jadwal->skema && $sertifikasi->jadwal->skema->gambar) 
                ? asset('images/skema/foto_skema/' . $sertifikasi->jadwal->skema->gambar) 
                : null;
        @endphp

        <x-mobile_header
            :title="$sertifikasi->jadwal->skema->nama_skema ?? 'Skema Sertifikasi'"
            :code="$sertifikasi->jadwal->skema->kode_unit ?? $sertifikasi->jadwal->skema->nomor_skema ?? '-'"
            :name="$sertifikasi->asesi->nama_lengkap ?? 'Nama Peserta'"
            :image="$gambarSkema"
            :sertifikasi="$sertifikasi"
            backUrl="{{ route('asesi.data.sertifikasi', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
        />

        {{-- ====================================================================== --}}
        {{-- 3. MAIN CONTENT --}}
        {{-- ====================================================================== --}}
        <main class="flex-1 w-full relative md:p-12 md:overflow-y-auto bg-gray-50 md:bg-white z-0" 
              data-sertifikasi-id="{{ $sertifikasi->id_data_sertifikasi_asesi }}">
            
            {{-- CONTAINER RESPONSIF --}}
            <div class="max-w-4xl mx-auto mt-16 md:mt-0 p-6 md:p-0 transition-all">

                {{-- A. STEPPER --}}
                {{-- 1. Stepper Desktop (Original Style) --}}
                <div class="hidden md:flex items-center justify-center mb-12">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">1</div>
                    </div>
                    <div class="w-24 h-0.5 bg-yellow-300 mx-4"></div>
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-yellow-300 rounded-full flex items-center justify-center text-white font-bold text-lg">2</div>
                    </div>
                    <div class="w-24 h-0.5 bg-gray-300 mx-4"></div>
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold text-lg">3</div>
                    </div>
                </div>

                {{-- 2. Stepper Mobile (Kuning sesuai gambar mobile) --}}
                <div class="flex items-center justify-center mb-8 md:hidden">
                    <div class="flex flex-col items-center"><div class="w-12 h-12 bg-[#FFD700] rounded-full flex items-center justify-center text-black font-bold text-xl shadow-md">1</div></div>
                    <div class="w-16 h-1.5 bg-[#FFD700] mx-1"></div>
                    <div class="flex flex-col items-center"><div class="w-12 h-12 bg-[#FFD700] rounded-full flex items-center justify-center text-black font-bold text-xl shadow-md">2</div></div>
                    <div class="w-16 h-1.5 bg-[#FFF4C3] mx-1"></div>
                    <div class="flex flex-col items-center"><div class="w-12 h-12 bg-[#FFF4C3] rounded-full flex items-center justify-center text-gray-600 font-bold text-xl">3</div></div>
                </div>

                {{-- HEADING --}}
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2 md:mb-4 text-center md:text-left">Bukti Kelengkapan Pemohon</h1>
                <p class="text-gray-600 mb-8 text-sm md:text-base text-center md:text-left">
                    Unggah bukti kelengkapan persyaratan dasar sesuai dengan skema sertifikasi.
                </p>

                <div class="space-y-4" id="dokumen-container">

                    {{-- LOOPING DOKUMEN --}}
                    
                    {{-- 1. FOTO --}}
                    <div class="bg-white border border-gray-100 rounded-xl p-6 upload-section hover:shadow-sm transition-all duration-200">
                        <div class="flex justify-between items-center cursor-pointer toggle-trigger w-full">
                            <div class="flex-1">
                                <h3 class="text-base md:text-lg font-bold text-gray-900" data-jenis="Foto Background Merah">Pas Foto (Background Merah)</h3>
                                <p class="text-xs md:text-sm text-gray-400 mt-1">Format: JPG/PNG. Maks 2MB.</p>
                            </div>
                            <div class="flex items-center">
                                <span class="file-status text-xs md:text-sm font-semibold mr-4 text-red-500">Belum diunggah</span>
                                {{-- PERBAIKAN: Menambahkan class 'toggle-button' di sini --}}
                                <button type="button" class="toggle-button text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="toggle-icon w-5 h-5 transform transition-transform duration-200">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        {{-- Content Accordion --}}
                        <div class="toggle-content hidden mt-6 border-t border-gray-100 pt-6">
                            <div class="flex flex-col md:flex-row items-start md:space-x-6 space-y-4 md:space-y-0">
                                <div class="preview-box w-full md:w-40 h-40 bg-gray-50 rounded-lg flex items-center justify-center overflow-hidden border-2 border-dashed border-gray-300 relative">
                                    <div class="text-center p-4">
                                        <span class="text-xs text-gray-400 block mb-1">Preview</span>
                                    </div>
                                </div>
                                <div class="flex-1 w-full space-y-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                                        <input type="text" class="description-input w-full border-gray-200 rounded-lg shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5 bg-gray-50" placeholder="Keterangan file...">
                                    </div>
                                    <input type="file" class="file-input hidden" accept=".jpg,.jpeg,.png,.pdf">
                                    
                                    <div class="button-group-initial">
                                        <button type="button" class="btn-select-file px-6 py-2.5 bg-blue-50 text-blue-600 text-sm font-bold rounded-lg hover:bg-blue-100 transition-colors w-full md:w-auto">
                                            Pilih File
                                        </button>
                                    </div>
                                    <div class="button-group-ready hidden flex space-x-3">
                                        <button type="button" class="btn-save px-6 py-2.5 bg-green-500 text-white text-sm font-bold rounded-lg hover:bg-green-600 transition-colors shadow-sm flex-1 md:flex-none">Simpan</button>
                                        <button type="button" class="btn-edit px-6 py-2.5 bg-blue-500 text-white text-sm font-bold rounded-lg hover:bg-blue-600 transition-colors shadow-sm hidden flex-1 md:flex-none">Ubah</button>
                                        <button type="button" class="btn-cancel px-6 py-2.5 bg-gray-100 text-gray-600 text-sm font-bold rounded-lg hover:bg-gray-200 transition-colors flex-1 md:flex-none">Batal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. KTP --}}
                    <div class="bg-white border border-gray-100 rounded-xl p-6 upload-section hover:shadow-sm transition-all duration-200">
                        <div class="flex justify-between items-center cursor-pointer toggle-trigger w-full">
                            <div class="flex-1">
                                <h3 class="text-base md:text-lg font-bold text-gray-900" data-jenis="KTP">Kartu Tanda Penduduk (KTP)</h3>
                                <p class="text-xs md:text-sm text-gray-400 mt-1">Format: JPG/PDF. Pastikan NIK terlihat jelas.</p>
                            </div>
                            <div class="flex items-center">
                                <span class="file-status text-xs md:text-sm font-semibold mr-4 text-red-500">Belum diunggah</span>
                                <button type="button" class="toggle-button text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="toggle-icon w-5 h-5 transform transition-transform duration-200">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="toggle-content hidden mt-6 border-t border-gray-100 pt-6">
                            <div class="flex flex-col md:flex-row items-start md:space-x-6 space-y-4 md:space-y-0">
                                <div class="preview-box w-full md:w-40 h-40 bg-gray-50 rounded-lg flex items-center justify-center overflow-hidden border-2 border-dashed border-gray-300 relative">
                                    <span class="text-xs text-gray-400 block mb-1">Preview</span>
                                </div>
                                <div class="flex-1 w-full space-y-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                                        <input type="text" class="description-input w-full border-gray-200 rounded-lg shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5 bg-gray-50">
                                    </div>
                                    <input type="file" class="file-input hidden" accept=".jpg,.jpeg,.png,.pdf">
                                    <div class="button-group-initial">
                                        <button type="button" class="btn-select-file px-6 py-2.5 bg-blue-50 text-blue-600 text-sm font-bold rounded-lg hover:bg-blue-100 transition-colors w-full md:w-auto">Pilih File</button>
                                    </div>
                                    <div class="button-group-ready hidden flex space-x-3">
                                        <button type="button" class="btn-save px-6 py-2.5 bg-green-500 text-white text-sm font-bold rounded-lg hover:bg-green-600 transition-colors shadow-sm flex-1 md:flex-none">Simpan</button>
                                        <button type="button" class="btn-edit px-6 py-2.5 bg-blue-500 text-white text-sm font-bold rounded-lg hover:bg-blue-600 transition-colors shadow-sm hidden flex-1 md:flex-none">Ubah</button>
                                        <button type="button" class="btn-cancel px-6 py-2.5 bg-gray-100 text-gray-600 text-sm font-bold rounded-lg hover:bg-gray-200 transition-colors flex-1 md:flex-none">Batal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. IJAZAH --}}
                    <div class="bg-white border border-gray-100 rounded-xl p-6 upload-section hover:shadow-sm transition-all duration-200">
                        <div class="flex justify-between items-center cursor-pointer toggle-trigger w-full">
                            <div class="flex-1">
                                <h3 class="text-base md:text-lg font-bold text-gray-900" data-jenis="Ijazah">Ijazah Terakhir</h3>
                                <p class="text-xs md:text-sm text-gray-400 mt-1">Scan Ijazah Legalisir.</p>
                            </div>
                            <div class="flex items-center">
                                <span class="file-status text-xs md:text-sm font-semibold mr-4 text-red-500">Belum diunggah</span>
                                <button type="button" class="toggle-button text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="toggle-icon w-5 h-5 transform transition-transform duration-200">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="toggle-content hidden mt-6 border-t border-gray-100 pt-6">
                            <div class="flex flex-col md:flex-row items-start md:space-x-6 space-y-4 md:space-y-0">
                                <div class="preview-box w-full md:w-40 h-40 bg-gray-50 rounded-lg flex items-center justify-center overflow-hidden border-2 border-dashed border-gray-300 relative">
                                    <span class="text-xs text-gray-400 block mb-1">Preview</span>
                                </div>
                                <div class="flex-1 w-full space-y-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                                        <input type="text" class="description-input w-full border-gray-200 rounded-lg shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5 bg-gray-50">
                                    </div>
                                    <input type="file" class="file-input hidden" accept=".jpg,.jpeg,.png,.pdf">
                                    <div class="button-group-initial">
                                        <button type="button" class="btn-select-file px-6 py-2.5 bg-blue-50 text-blue-600 text-sm font-bold rounded-lg hover:bg-blue-100 transition-colors w-full md:w-auto">Pilih File</button>
                                    </div>
                                    <div class="button-group-ready hidden flex space-x-3">
                                        <button type="button" class="btn-save px-6 py-2.5 bg-green-500 text-white text-sm font-bold rounded-lg hover:bg-green-600 transition-colors shadow-sm flex-1 md:flex-none">Simpan</button>
                                        <button type="button" class="btn-edit px-6 py-2.5 bg-blue-500 text-white text-sm font-bold rounded-lg hover:bg-blue-600 transition-colors shadow-sm hidden flex-1 md:flex-none">Ubah</button>
                                        <button type="button" class="btn-cancel px-6 py-2.5 bg-gray-100 text-gray-600 text-sm font-bold rounded-lg hover:bg-gray-200 transition-colors flex-1 md:flex-none">Batal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 4. SERTIFIKASI --}}
                    <div class="bg-white border border-gray-100 rounded-xl p-6 upload-section hover:shadow-sm transition-all duration-200">
                        <div class="flex justify-between items-center cursor-pointer toggle-trigger w-full">
                            <div class="flex-1">
                                <h3 class="text-base md:text-lg font-bold text-gray-900" data-jenis="Sertifikasi Pelatihan">Sertifikasi Pelatihan / Kompetensi</h3>
                                <p class="text-xs md:text-sm text-gray-400 mt-1">Sertifikat pendukung yang relevan.</p>
                            </div>
                            <div class="flex items-center">
                                <span class="file-status text-xs md:text-sm font-semibold mr-4 text-red-500">Belum diunggah</span>
                                <button type="button" class="toggle-button text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="toggle-icon w-5 h-5 transform transition-transform duration-200">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="toggle-content hidden mt-6 border-t border-gray-100 pt-6">
                            <div class="flex flex-col md:flex-row items-start md:space-x-6 space-y-4 md:space-y-0">
                                <div class="preview-box w-full md:w-40 h-40 bg-gray-50 rounded-lg flex items-center justify-center overflow-hidden border-2 border-dashed border-gray-300 relative">
                                    <span class="text-xs text-gray-400 block mb-1">Preview</span>
                                </div>
                                <div class="flex-1 w-full space-y-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                                        <input type="text" class="description-input w-full border-gray-200 rounded-lg shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5 bg-gray-50">
                                    </div>
                                    <input type="file" class="file-input hidden" accept=".jpg,.jpeg,.png,.pdf">
                                    <div class="button-group-initial">
                                        <button type="button" class="btn-select-file px-6 py-2.5 bg-blue-50 text-blue-600 text-sm font-bold rounded-lg hover:bg-blue-100 transition-colors w-full md:w-auto">Pilih File</button>
                                    </div>
                                    <div class="button-group-ready hidden flex space-x-3">
                                        <button type="button" class="btn-save px-6 py-2.5 bg-green-500 text-white text-sm font-bold rounded-lg hover:bg-green-600 transition-colors shadow-sm flex-1 md:flex-none">Simpan</button>
                                        <button type="button" class="btn-edit px-6 py-2.5 bg-blue-500 text-white text-sm font-bold rounded-lg hover:bg-blue-600 transition-colors shadow-sm hidden flex-1 md:flex-none">Ubah</button>
                                        <button type="button" class="btn-cancel px-6 py-2.5 bg-gray-100 text-gray-600 text-sm font-bold rounded-lg hover:bg-gray-200 transition-colors flex-1 md:flex-none">Batal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 5. SURAT KERJA --}}
                    <div class="bg-white border border-gray-100 rounded-xl p-6 upload-section hover:shadow-sm transition-all duration-200">
                        <div class="flex justify-between items-center cursor-pointer toggle-trigger w-full">
                            <div class="flex-1">
                                <h3 class="text-base md:text-lg font-bold text-gray-900" data-jenis="Surat Keterangan Kerja">Surat Keterangan Kerja / Portofolio</h3>
                                <p class="text-xs md:text-sm text-gray-400 mt-1">Dokumen Hasil Pekerjaan (Min. 2 tahun).</p>
                            </div>
                            <div class="flex items-center">
                                <span class="file-status text-xs md:text-sm font-semibold mr-4 text-red-500">Belum diunggah</span>
                                <button type="button" class="toggle-button text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="toggle-icon w-5 h-5 transform transition-transform duration-200">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="toggle-content hidden mt-6 border-t border-gray-100 pt-6">
                            <div class="flex flex-col md:flex-row items-start md:space-x-6 space-y-4 md:space-y-0">
                                <div class="preview-box w-full md:w-40 h-40 bg-gray-50 rounded-lg flex items-center justify-center overflow-hidden border-2 border-dashed border-gray-300 relative">
                                    <span class="text-xs text-gray-400 block mb-1">Preview</span>
                                </div>
                                <div class="flex-1 w-full space-y-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                                        <input type="text" class="description-input w-full border-gray-200 rounded-lg shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5 bg-gray-50">
                                    </div>
                                    <input type="file" class="file-input hidden" accept=".jpg,.jpeg,.png,.pdf">
                                    <div class="button-group-initial">
                                        <button type="button" class="btn-select-file px-6 py-2.5 bg-blue-50 text-blue-600 text-sm font-bold rounded-lg hover:bg-blue-100 transition-colors w-full md:w-auto">Pilih File</button>
                                    </div>
                                    <div class="button-group-ready hidden flex space-x-3">
                                        <button type="button" class="btn-save px-6 py-2.5 bg-green-500 text-white text-sm font-bold rounded-lg hover:bg-green-600 transition-colors shadow-sm flex-1 md:flex-none">Simpan</button>
                                        <button type="button" class="btn-edit px-6 py-2.5 bg-blue-500 text-white text-sm font-bold rounded-lg hover:bg-blue-600 transition-colors shadow-sm hidden flex-1 md:flex-none">Ubah</button>
                                        <button type="button" class="btn-cancel px-6 py-2.5 bg-gray-100 text-gray-600 text-sm font-bold rounded-lg hover:bg-gray-200 transition-colors flex-1 md:flex-none">Batal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> {{-- End Container Upload --}}

                {{-- Navigation Buttons (Sesuai Palet Warna) --}}
                <div class="flex justify-between items-center mt-12 pb-10 md:pb-0">
                    <a href="{{ route('asesi.data.sertifikasi', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                        class="w-32 md:w-48 text-center px-4 md:px-8 py-3 bg-gray-200 text-gray-700 font-bold rounded-full hover:bg-gray-300 transition-all shadow-sm text-sm md:text-base">
                        Kembali
                    </a>

                    <button type="button" id="btn-next-page"
                        class="w-32 md:w-48 text-center px-4 md:px-8 py-3 bg-blue-600 text-white font-bold rounded-full hover:bg-blue-700 shadow-md transition-all text-sm md:text-base shadow-blue-200">
                        Selanjutnya
                    </button>
                </div>

            </div>
        </main>
    </div>

    {{-- JAVASCRIPT LOGIC --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            
            const mainEl = document.querySelector('main[data-sertifikasi-id]');
            const idDataSertifikasi = mainEl ? mainEl.dataset.sertifikasiId : null;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            const nextUrl = "{{ route('asesi.show.tandatangan', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}";

            if (!idDataSertifikasi) {
                console.error("ID Sertifikasi tidak ditemukan.");
                return;
            }

            // --- VALIDASI KELENGKAPAN ---
            const btnNext = document.getElementById('btn-next-page');

            btnNext.addEventListener('click', function() {
                const allStatus = document.querySelectorAll('.file-status');
                let isComplete = true;

                allStatus.forEach(status => {
                    const text = status.textContent.trim();
                    if (text === 'Belum diunggah') {
                        isComplete = false;
                    }
                });

                if (isComplete) {
                    window.location.href = nextUrl;
                } else {
                    alert('Mohon lengkapi semua dokumen persyaratan terlebih dahulu!');
                }
            });


            // --- FUNGSI HELPER UI & LOGIC UPLOAD ---
            function renderPreview(previewBox, source, isUrl = false) {
                previewBox.innerHTML = '';
                let isImage = false;
                let name = '';

                if (isUrl) {
                    const ext = source.split('.').pop().toLowerCase();
                    isImage = ['jpg', 'jpeg', 'png', 'webp'].includes(ext);
                    name = source.split('/').pop();
                } else {
                    isImage = source.type.startsWith('image/');
                    name = source.name;
                }

                if (isImage) {
                    const img = document.createElement('img');
                    img.className = 'w-full h-full object-cover rounded-lg';
                    img.src = isUrl ? `/${source}` : URL.createObjectURL(source);
                    previewBox.appendChild(img);
                } else {
                    previewBox.innerHTML = `
                        <div class="flex flex-col items-center justify-center h-full p-2">
                            <svg class="w-8 h-8 text-red-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            <span class="text-[10px] text-gray-600 break-all text-center leading-tight">${name}</span>
                        </div>`;
                }
            }

            function setUIState(section, state) {
                const groupInitial = section.querySelector('.button-group-initial');
                const groupReady = section.querySelector('.button-group-ready');
                const btnSave = section.querySelector('.btn-save');
                const btnEdit = section.querySelector('.btn-edit');
                const statusText = section.querySelector('.file-status');
                const toggleButton = section.querySelector('.toggle-button');

                if (state === 'initial') {
                    groupInitial.classList.remove('hidden');
                    groupReady.classList.add('hidden');
                    statusText.textContent = 'Belum diunggah';
                    statusText.className = 'file-status text-xs md:text-sm font-semibold mr-4 text-red-500';
                    toggleButton.classList.add('text-gray-400');
                    toggleButton.classList.remove('text-green-600');

                } else if (state === 'ready') {
                    groupInitial.classList.add('hidden');
                    groupReady.classList.remove('hidden');
                    btnEdit.classList.add('hidden');
                    btnSave.classList.remove('hidden');
                    btnSave.innerText = 'Simpan';
                    btnSave.disabled = false;
                    btnSave.classList.remove('bg-gray-400', 'cursor-not-allowed');
                    btnSave.classList.add('bg-green-500', 'hover:bg-green-600');
                    statusText.textContent = 'Siap Unggah';
                    statusText.className = 'file-status text-xs md:text-sm font-semibold mr-4 text-blue-500';

                } else if (state === 'saved') {
                    groupInitial.classList.add('hidden');
                    groupReady.classList.remove('hidden'); 
                    btnSave.innerText = 'Tersimpan';
                    btnSave.disabled = true;
                    btnSave.classList.remove('bg-green-500', 'hover:bg-green-600');
                    btnSave.classList.add('bg-gray-400', 'cursor-not-allowed');
                    btnEdit.classList.remove('hidden');
                    statusText.textContent = 'Sudah diunggah';
                    statusText.className = 'file-status text-xs md:text-sm font-semibold mr-4 text-green-600';
                    toggleButton.classList.remove('text-gray-400');
                    toggleButton.classList.add('text-green-600');
                }
            }

            // --- AUTO LOAD DATA ---
            if (idDataSertifikasi) {
                fetch(`/api/v1/bukti-kelengkapan/list/${idDataSertifikasi}`)
                    .then(res => res.json())
                    .then(response => {
                        if (response.success && response.data.length > 0) {
                            const existingData = response.data;
                            document.querySelectorAll('.upload-section').forEach(section => {
                                const judulElement = section.querySelector('h3');
                                const jenis = judulElement.getAttribute('data-jenis');
                                const dataItem = existingData.find(item => item.keterangan && item.keterangan.startsWith(jenis));

                                if (dataItem && dataItem.bukti_dasar) {
                                    const previewBox = section.querySelector('.preview-box');
                                    const descInput = section.querySelector('.description-input');
                                    renderPreview(previewBox, dataItem.bukti_dasar, true);
                                    const parts = dataItem.keterangan.split(' - ');
                                    if (parts.length > 1) descInput.value = parts[1];
                                    setUIState(section, 'saved');
                                }
                            });
                        }
                    })
                    .catch(err => console.error("Gagal load bukti:", err));
            }

            // --- EVENT LISTENER PER SECTION ---
            document.querySelectorAll('.upload-section').forEach(section => {
                const header = section.querySelector('.toggle-trigger');
                const content = section.querySelector('.toggle-content');
                const icon = section.querySelector('.toggle-icon');
                const fileInput = section.querySelector('.file-input');
                const btnSelect = section.querySelector('.btn-select-file');
                const btnSave = section.querySelector('.btn-save');
                const btnCancel = section.querySelector('.btn-cancel');
                const btnEdit = section.querySelector('.btn-edit');
                const previewBox = section.querySelector('.preview-box');
                const descInput = section.querySelector('.description-input');
                const jenisDokumen = section.querySelector('h3').getAttribute('data-jenis');
                let selectedFile = null;

                const iconChevronDown = '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />';
                const iconChevronUp = '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />';

                // Accordion
                header.addEventListener('click', (e) => {
                    e.preventDefault();
                    content.classList.toggle('hidden');
                    icon.innerHTML = content.classList.contains('hidden') ? iconChevronDown : iconChevronUp;
                });

                // Pilih File
                btnSelect.addEventListener('click', () => fileInput.click());
                if(btnEdit) btnEdit.addEventListener('click', () => fileInput.click());

                // File Berubah
                fileInput.addEventListener('change', (e) => {
                    if (fileInput.files.length > 0) {
                        selectedFile = fileInput.files[0];
                        renderPreview(previewBox, selectedFile);
                        setUIState(section, 'ready');
                        content.classList.remove('hidden');
                        icon.innerHTML = iconChevronUp;
                    }
                });

                // Batal
                btnCancel.addEventListener('click', () => {
                    fileInput.value = '';
                    selectedFile = null;
                    descInput.value = '';
                    previewBox.innerHTML = '<span class="text-xs text-gray-500 text-center px-2">Preview</span>';
                    setUIState(section, 'initial');
                });

                // Simpan
                btnSave.addEventListener('click', async () => {
                    if (!selectedFile || btnSave.disabled) return;

                    const originalText = btnSave.innerText;
                    btnSave.innerText = '...';
                    btnSave.disabled = true;
                    btnCancel.disabled = true;

                    const formData = new FormData();
                    formData.append('id_data_sertifikasi_asesi', idDataSertifikasi);
                    formData.append('jenis_dokumen', jenisDokumen);
                    formData.append('file', selectedFile);
                    if (descInput.value) formData.append('keterangan', descInput.value);

                    try {
                        const response = await fetch('/api/v1/bukti-kelengkapan/store', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                            body: formData
                        });
                        const result = await response.json();
                        if (response.ok && result.success) {
                            alert('Berhasil diunggah!');
                            setUIState(section, 'saved');
                            setTimeout(() => {
                                content.classList.add('hidden');
                                icon.innerHTML = iconChevronDown;
                            }, 1500);
                        } else {
                            throw new Error(result.message || 'Gagal upload');
                        }
                    } catch (error) {
                        console.error(error);
                        alert('Gagal: ' + error.message);
                        btnSave.innerText = originalText;
                        btnSave.disabled = false;
                    } finally {
                        btnCancel.disabled = false;
                    }
                });
            });
        });
    </script>

</body>
</html>