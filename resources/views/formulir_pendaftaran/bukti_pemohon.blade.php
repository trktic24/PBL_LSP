<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Kelengkapan Pemohon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-100">

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        <x-sidebar :idAsesi="$asesi->id_asesi" :sertifikasi="$sertifikasi"></x-sidebar>

        {{-- Main Content --}}
        {{-- [PERBAIKAN] Pakai $sertifikasi->id_... langsung, jangan $idSertifikasi --}}
        <main class="flex-1 p-12 bg-white overflow-y-auto" 
              data-sertifikasi-id="{{ $sertifikasi->id_data_sertifikasi_asesi }}">
            
            <div class="max-w-4xl mx-auto">

                {{-- Progress Bar --}}
                <div class="flex items-center justify-center mb-12">
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

                <h1 class="text-4xl font-bold text-gray-900 mb-4">Bukti Kelengkapan Pemohon</h1>
                <p class="text-gray-600 mb-8">
                    Silakan unggah semua bukti kelengkapan di bawah ini. Anda tidak dapat melanjutkan jika dokumen belum lengkap.
                </p>

                <div class="space-y-4" id="dokumen-container">

                    {{-- DOKUMEN 1: FOTO --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 upload-section transition-all duration-300 hover:shadow-md">
                        <div class="flex justify-between items-center cursor-pointer toggle-trigger">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900" data-jenis="Foto Background Merah">Foto Background Merah</h3>
                                <p class="text-sm text-gray-500">Format: JPG/PNG. Maks 2MB.</p>
                            </div>
                            <button type="button" class="toggle-button flex items-center text-sm font-medium text-red-600 hover:text-red-800 focus:outline-none">
                                <span class="file-status mr-2">Belum diunggah</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="toggle-icon w-5 h-5 transform transition-transform duration-200">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                        </div>
                        <div class="toggle-content hidden mt-4 border-t border-gray-200 pt-4">
                            <div class="flex items-start space-x-5">
                                <div class="preview-box w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center overflow-hidden border border-gray-300 relative">
                                    <span class="text-xs text-gray-500 text-center px-2">Preview</span>
                                </div>
                                <div class="flex-1">
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                                        <input type="text" class="description-input w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <input type="file" class="file-input hidden" accept=".jpg,.jpeg,.png,.pdf">
                                    
                                    <div class="button-group-initial flex space-x-3">
                                        <button type="button" class="btn-select-file px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors shadow-sm">
                                            Pilih File
                                        </button>
                                    </div>
                                    <div class="button-group-ready hidden flex space-x-3 mt-2">
                                        <button type="button" class="btn-save px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors shadow-sm">
                                            Simpan / Upload
                                        </button>
                                        <button type="button" class="btn-edit px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors shadow-sm hidden">
                                            Ubah File
                                        </button>
                                        <button type="button" class="btn-cancel px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300 transition-colors">
                                            Batal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DOKUMEN 2: KTP --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 upload-section transition-all duration-300 hover:shadow-md">
                        <div class="flex justify-between items-center cursor-pointer toggle-trigger">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900" data-jenis="KTP">KTP / Identitas Diri</h3>
                                <p class="text-sm text-gray-500">Scan KTP Asli.</p>
                            </div>
                            <button type="button" class="toggle-button flex items-center text-sm font-medium text-red-600 hover:text-red-800 focus:outline-none">
                                <span class="file-status mr-2">Belum diunggah</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="toggle-icon w-5 h-5 transform transition-transform duration-200">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                        </div>
                        <div class="toggle-content hidden mt-4 border-t border-gray-200 pt-4">
                            <div class="flex items-start space-x-5">
                                <div class="preview-box w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center overflow-hidden border border-gray-300 relative">
                                    <span class="text-xs text-gray-500 text-center px-2">Preview</span>
                                </div>
                                <div class="flex-1">
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan (Opsional)</label>
                                        <input type="text" class="description-input w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <input type="file" class="file-input hidden" accept=".jpg,.jpeg,.png,.pdf">
                                    <div class="button-group-initial flex space-x-3">
                                        <button type="button" class="btn-select-file px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors shadow-sm">Pilih File</button>
                                    </div>
                                    <div class="button-group-ready hidden flex space-x-3 mt-2">
                                        <button type="button" class="btn-save px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors shadow-sm">Simpan / Upload</button>
                                        <button type="button" class="btn-edit px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors shadow-sm hidden">Ubah File</button>
                                        <button type="button" class="btn-cancel px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300 transition-colors">Batal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DOKUMEN 3: IJAZAH --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 upload-section transition-all duration-300 hover:shadow-md">
                        <div class="flex justify-between items-center cursor-pointer toggle-trigger">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900" data-jenis="Ijazah">Ijazah Terakhir</h3>
                                <p class="text-sm text-gray-500">Scan Ijazah Legalisir.</p>
                            </div>
                            <button type="button" class="toggle-button flex items-center text-sm font-medium text-red-600 hover:text-red-800 focus:outline-none">
                                <span class="file-status mr-2">Belum diunggah</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="toggle-icon w-5 h-5 transform transition-transform duration-200">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                        </div>
                        <div class="toggle-content hidden mt-4 border-t border-gray-200 pt-4">
                            <div class="flex items-start space-x-5">
                                <div class="preview-box w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center overflow-hidden border border-gray-300 relative">
                                    <span class="text-xs text-gray-500 text-center px-2">Preview</span>
                                </div>
                                <div class="flex-1">
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan (Opsional)</label>
                                        <input type="text" class="description-input w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <input type="file" class="file-input hidden" accept=".jpg,.jpeg,.png,.pdf">
                                    <div class="button-group-initial flex space-x-3">
                                        <button type="button" class="btn-select-file px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors shadow-sm">Pilih File</button>
                                    </div>
                                    <div class="button-group-ready hidden flex space-x-3 mt-2">
                                        <button type="button" class="btn-save px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors shadow-sm">Simpan / Upload</button>
                                        <button type="button" class="btn-edit px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors shadow-sm hidden">Ubah File</button>
                                        <button type="button" class="btn-cancel px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300 transition-colors">Batal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DOKUMEN 4: SERTIFIKASI --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 upload-section transition-all duration-300 hover:shadow-md">
                        <div class="flex justify-between items-center cursor-pointer toggle-trigger">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900" data-jenis="Sertifikasi Pelatihan">Sertifikasi Pelatihan / Kompetensi</h3>
                                <p class="text-sm text-gray-500">Sertifikat pendukung yang relevan.</p>
                            </div>
                            <button type="button" class="toggle-button flex items-center text-sm font-medium text-red-600 hover:text-red-800 focus:outline-none">
                                <span class="file-status mr-2">Belum diunggah</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="toggle-icon w-5 h-5 transform transition-transform duration-200">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                        </div>
                        <div class="toggle-content hidden mt-4 border-t border-gray-200 pt-4">
                            <div class="flex items-start space-x-5">
                                <div class="preview-box w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center overflow-hidden border border-gray-300 relative">
                                    <span class="text-xs text-gray-500 text-center px-2">Preview</span>
                                </div>
                                <div class="flex-1">
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan (Opsional)</label>
                                        <input type="text" class="description-input w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <input type="file" class="file-input hidden" accept=".jpg,.jpeg,.png,.pdf">
                                    <div class="button-group-initial flex space-x-3">
                                        <button type="button" class="btn-select-file px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors shadow-sm">Pilih File</button>
                                    </div>
                                    <div class="button-group-ready hidden flex space-x-3 mt-2">
                                        <button type="button" class="btn-save px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors shadow-sm">Simpan / Upload</button>
                                        <button type="button" class="btn-edit px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors shadow-sm hidden">Ubah File</button>
                                        <button type="button" class="btn-cancel px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300 transition-colors">Batal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DOKUMEN 5: SURAT KERJA --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 upload-section transition-all duration-300 hover:shadow-md">
                        <div class="flex justify-between items-center cursor-pointer toggle-trigger">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900" data-jenis="Surat Keterangan Kerja">Surat Keterangan Kerja / Portofolio / Presentasi / Dokumen Hasil Pekerjaan</h3>
                                <p class="text-sm text-gray-500">Pengalaman kerja min. 2 tahun.</p>
                            </div>
                            <button type="button" class="toggle-button flex items-center text-sm font-medium text-red-600 hover:text-red-800 focus:outline-none">
                                <span class="file-status mr-2">Belum diunggah</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="toggle-icon w-5 h-5 transform transition-transform duration-200">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                        </div>
                        <div class="toggle-content hidden mt-4 border-t border-gray-200 pt-4">
                            <div class="flex items-start space-x-5">
                                <div class="preview-box w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center overflow-hidden border border-gray-300 relative">
                                    <span class="text-xs text-gray-500 text-center px-2">Preview</span>
                                </div>
                                <div class="flex-1">
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                                        <input type="text" class="description-input w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <input type="file" class="file-input hidden" accept=".jpg,.jpeg,.png,.pdf">
                                    <div class="button-group-initial flex space-x-3">
                                        <button type="button" class="btn-select-file px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors shadow-sm">Pilih File</button>
                                    </div>
                                    <div class="button-group-ready hidden flex space-x-3 mt-2">
                                        <button type="button" class="btn-save px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors shadow-sm">Simpan / Upload</button>
                                        <button type="button" class="btn-edit px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors shadow-sm hidden">Ubah File</button>
                                        <button type="button" class="btn-cancel px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300 transition-colors">Batal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DOKUMEN 6: Pengalaman Kerja --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 upload-section transition-all duration-300 hover:shadow-md">
                        <div class="flex justify-between items-center cursor-pointer toggle-trigger">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900" data-jenis="Pengalaman Kerja">Pengalaman Kerja / Curiculum Vitae (CV)</h3>
                                <p class="text-sm text-gray-500">CV terbaru dengan pengalaman kerja yang relevan dengan skema sertifikasi.</p>
                            </div>
                            <button type="button" class="toggle-button flex items-center text-sm font-medium text-red-600 hover:text-red-800 focus:outline-none">
                                <span class="file-status mr-2">Belum diunggah</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="toggle-icon w-5 h-5 transform transition-transform duration-200">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                        </div>
                        <div class="toggle-content hidden mt-4 border-t border-gray-200 pt-4">
                            <div class="flex items-start space-x-5">
                                <div class="preview-box w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center overflow-hidden border border-gray-300 relative">
                                    <span class="text-xs text-gray-500 text-center px-2">Preview</span>
                                </div>
                                <div class="flex-1">
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                                        <input type="text" class="description-input w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <input type="file" class="file-input hidden" accept=".jpg,.jpeg,.png,.pdf">
                                    <div class="button-group-initial flex space-x-3">
                                        <button type="button" class="btn-select-file px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors shadow-sm">Pilih File</button>
                                    </div>
                                    <div class="button-group-ready hidden flex space-x-3 mt-2">
                                        <button type="button" class="btn-save px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors shadow-sm">Simpan / Upload</button>
                                        <button type="button" class="btn-edit px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors shadow-sm hidden">Ubah File</button>
                                        <button type="button" class="btn-cancel px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300 transition-colors">Batal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> {{-- End Container Upload --}}

                {{-- Navigation --}}
                <div class="flex justify-between items-center mt-10">
                    {{-- [PERBAIKAN] Pakai $sertifikasi->id_... langsung --}}
                    <a href="{{ route('data.sertifikasi', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                        class="w-48 text-center px-8 py-3 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300 transition-all shadow-sm">
                        Kembali
                    </a>

                    <button type="button" id="btn-next-page"
                        class="w-48 text-center px-8 py-3 bg-blue-600 text-white font-semibold rounded-full hover:bg-blue-700 shadow-md transition-all">
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

            // [PERBAIKAN] Ambil ID langsung dari Controller (blade variable)
            const nextUrl = "{{ route('show.tandatangan', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}";

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
                    if (status.textContent.trim() !== 'Sudah diunggah') {
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
            // (Kode di bawah ini SAMA PERSIS seperti sebelumnya, tidak ada perubahan logika utama)
            
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
                    statusText.className = 'file-status mr-2';
                    toggleButton.classList.add('text-red-600');
                    toggleButton.classList.remove('text-green-600');

                } else if (state === 'ready') {
                    groupInitial.classList.add('hidden');
                    groupReady.classList.remove('hidden');
                    btnEdit.classList.add('hidden');
                    btnSave.classList.remove('hidden');
                    btnSave.innerText = 'Simpan / Upload';
                    btnSave.disabled = false;
                    btnSave.classList.remove('bg-gray-400', 'cursor-not-allowed');
                    btnSave.classList.add('bg-green-600', 'hover:bg-green-700');
                    statusText.textContent = 'Siap diunggah';
                    statusText.className = 'file-status mr-2 text-blue-600';

                } else if (state === 'saved') {
                    groupInitial.classList.add('hidden');
                    groupReady.classList.remove('hidden'); 
                    btnSave.innerText = 'Tersimpan ✔';
                    btnSave.disabled = true;
                    btnSave.classList.remove('bg-green-600', 'hover:bg-green-700');
                    btnSave.classList.add('bg-gray-400', 'cursor-not-allowed');
                    btnEdit.classList.remove('hidden');
                    statusText.textContent = 'Sudah diunggah';
                    statusText.className = 'file-status mr-2 text-green-600 font-bold';
                    toggleButton.classList.remove('text-red-600');
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
                    btnSave.innerText = 'Mengunggah...';
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