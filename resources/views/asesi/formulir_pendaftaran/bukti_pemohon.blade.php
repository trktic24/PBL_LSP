@use('App\Models\DataSertifikasiAsesi')

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Kelengkapan Pemohon</title>
    {{-- Tailwind & Fonts --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        /* Animasi */
        .fade-enter-active { animation: fadeIn 0.4s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
        
        .accordion-content { transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out; max-height: 0; opacity: 0; overflow: hidden; }
        .accordion-content.open { max-height: 1000px; opacity: 1; }
    </style>
</head>

<body class="bg-gray-50 md:bg-white font-sans text-gray-800">

    <div class="flex min-h-screen flex-col md:flex-row md:h-screen md:overflow-hidden">

        {{-- SIDEBAR --}}
        <div class="hidden md:block md:w-80 flex-shrink-0">
            <x-sidebar :idAsesi="$asesi->id_asesi" :sertifikasi="$sertifikasi"></x-sidebar>
        </div>

        {{-- HEADER MOBILE --}}
        @php
            $gambarSkema = null;
            if ($sertifikasi->jadwal && $sertifikasi->jadwal->skema && $sertifikasi->jadwal->skema->gambar) {
                 $gambarSkema = asset('storage/' . $sertifikasi->jadwal->skema->gambar);
            }
        @endphp

        <x-mobile_header
            :title="$sertifikasi->jadwal->skema->nama_skema ?? 'Skema Sertifikasi'"
            :code="$sertifikasi->jadwal->skema->kode_unit ?? $sertifikasi->jadwal->skema->nomor_skema ?? '-'"
            :name="$sertifikasi->asesi->nama_lengkap ?? 'Nama Peserta'"
            :image="$gambarSkema"
            :sertifikasi="$sertifikasi"
            backUrl="{{ route('asesi.data.sertifikasi', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
        />

        {{-- MAIN CONTENT --}}
        <main class="flex-1 w-full relative md:p-12 md:overflow-y-auto bg-gray-50 md:bg-white z-0" 
              data-sertifikasi-id="{{ $sertifikasi->id_data_sertifikasi_asesi }}">
            
            <div class="max-w-4xl mx-auto mt-20 md:mt-0 p-6 md:p-0 transition-all pb-24">

                {{-- STEPPER --}}
                <div class="hidden md:flex items-center justify-center mb-12">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-sm">1</div>
                    </div>
                    <div class="w-24 h-1 bg-yellow-300 mx-4 rounded-full"></div>
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-yellow-300 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-sm">2</div>
                    </div>
                    <div class="w-24 h-1 bg-gray-200 mx-4 rounded-full"></div>
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-bold text-lg">3</div>
                    </div>
                </div>

                {{-- HEADING --}}
                <div class="mb-8">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2 text-center md:text-left">Bukti Kelengkapan Pemohon</h1>
                    <p class="text-gray-500 text-sm md:text-base text-center md:text-left">
                        Unggah dokumen persyaratan. Anda dapat mengedit atau menghapus dokumen yang sudah diunggah.
                    </p>
                </div>

                @php
                    $daftarDokumen = [
                        ['jenis' => 'Foto Background Merah', 'label' => 'Pas Foto (Background Merah)', 'desc' => 'Format: JPG/PNG. Maks 2MB.', 'isMulti' => false, 'icon' => 'foto'],
                        ['jenis' => 'KTP', 'label' => 'Kartu Tanda Penduduk (KTP)', 'desc' => 'Format: JPG/PDF. NIK harus terlihat jelas.', 'isMulti' => false, 'icon' => 'ktp'],
                        ['jenis' => 'Ijazah', 'label' => 'Ijazah Terakhir', 'desc' => 'Scan Ijazah Legalisir (PDF/JPG).', 'isMulti' => false, 'icon' => 'doc'],
                        ['jenis' => 'Sertifikasi Pelatihan', 'label' => 'Sertifikasi Pelatihan / Kompetensi', 'desc' => 'Sertifikat relevan (Bisa upload banyak).', 'isMulti' => true, 'icon' => 'doc'],
                        ['jenis' => 'Surat Keterangan Kerja', 'label' => 'Surat Keterangan Kerja / Portofolio', 'desc' => 'Bukti pengalaman/portofolio (Bisa upload banyak).', 'isMulti' => true, 'icon' => 'doc']
                    ];
                @endphp

                <div class="space-y-5" id="dokumen-container">
                    @foreach($daftarDokumen as $doc)
                    <div class="upload-section bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden" 
                         data-jenis="{{ $doc['jenis'] }}" 
                         data-multi="{{ $doc['isMulti'] ? 'true' : 'false' }}">
                        
                        {{-- HEADER CARD --}}
                        <div class="toggle-trigger p-5 flex justify-between items-center cursor-pointer bg-white hover:bg-gray-50 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                                    @if($doc['icon'] == 'foto')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    @elseif($doc['icon'] == 'ktp')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-base md:text-lg font-bold text-gray-800 leading-tight">{{ $doc['label'] }}</h3>
                                    <p class="text-xs text-gray-500 mt-1">{{ $doc['desc'] }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="status-badge bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap">Wajib Diisi</span>
                                <button type="button" class="text-gray-400 hover:text-blue-600 transition-colors">
                                    <svg class="toggle-icon w-6 h-6 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                            </div>
                        </div>

                        {{-- ACCORDION CONTENT --}}
                        <div class="accordion-content bg-gray-50">
                            <div class="p-5 border-t border-gray-100">
                                
                                {{-- LIST FILE YANG SUDAH DIUPLOAD --}}
                                <div class="uploaded-list mb-4 space-y-3 empty:hidden"></div>

                                {{-- BUTTON TRIGGER: TAMBAH / GANTI --}}
                                <div class="btn-action-wrapper hidden mb-4">
                                    <button type="button" class="btn-toggle-form flex items-center justify-center w-full md:w-auto px-5 py-2.5 border-2 border-dashed border-blue-300 rounded-xl text-blue-600 font-semibold hover:bg-blue-50 hover:border-blue-500 transition-all gap-2 group">
                                        @if($doc['isMulti'])
                                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            Tambah Dokumen Lain
                                        @else
                                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732"></path></svg>
                                            Ganti File
                                        @endif
                                    </button>
                                </div>

                                {{-- FORM UPLOAD --}}
                                <div class="upload-form-wrapper">
                                    <div class="bg-white p-4 rounded-xl border border-dashed border-gray-300 hover:border-blue-400 transition-colors relative">
                                        
                                        <button type="button" class="btn-cancel-upload absolute top-2 right-2 text-gray-400 hover:text-red-500 hidden z-10 p-1 rounded-full hover:bg-red-50 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>

                                        <div class="flex flex-col md:flex-row gap-4 items-start">
                                            {{-- Preview Box --}}
                                            <div class="w-full md:w-32 h-32 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden border border-gray-200 relative shrink-0">
                                                <img src="" class="preview-img w-full h-full object-cover hidden" alt="Preview">
                                                <div class="preview-placeholder text-center p-2">
                                                    <svg class="w-8 h-8 text-gray-300 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    <span class="text-[10px] text-gray-400">Preview</span>
                                                </div>
                                            </div>

                                            {{-- Inputs --}}
                                            <div class="flex-1 w-full space-y-3">
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Keterangan File</label>
                                                    <input type="text" class="description-input w-full border border-gray-200 rounded-lg text-sm px-4 py-2 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all" placeholder="Contoh: Dokumen Pendukung">
                                                </div>
                                                
                                                <input type="file" class="file-input hidden" accept=".jpg,.jpeg,.png,.pdf">
                                                
                                                <div class="flex gap-3 mt-2">
                                                    <button type="button" class="btn-select-file px-4 py-2 bg-blue-50 text-blue-600 text-sm font-bold rounded-lg hover:bg-blue-100 transition-colors">
                                                        Pilih File
                                                    </button>
                                                    <button type="button" class="btn-save hidden px-6 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition-colors shadow-md flex items-center gap-2">
                                                        <span>Simpan</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- NAVIGASI --}}
                <div class="flex justify-between items-center mt-12">
                    <a href="{{ route('asesi.data.sertifikasi', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                        class="w-32 md:w-48 text-center px-4 md:px-8 py-3 bg-gray-200 text-gray-700 font-bold rounded-full hover:bg-gray-300 transition-all shadow-sm text-sm md:text-base">
                        Kembali
                    </a>
                    <button type="button" id="btn-next-page"
                        class="w-32 md:w-48 text-center px-4 md:px-8 py-3 bg-blue-600 text-white font-bold rounded-full hover:bg-blue-700 shadow-md transition-all disabled:opacity-50 disabled:cursor-not-allowed text-sm md:text-base shadow-blue-200">
                        Selanjutnya
                    </button>
                </div>

            </div>
        </main>
    </div>

    {{-- LOGIC JAVASCRIPT FIXED --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const mainEl = document.querySelector('main[data-sertifikasi-id]');
            const idDataSertifikasi = mainEl ? mainEl.dataset.sertifikasiId : null;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const nextUrl = "{{ route('asesi.show.tandatangan', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}";

            // --- 1. ACCORDION ---
            document.querySelectorAll('.toggle-trigger').forEach(trigger => {
                trigger.addEventListener('click', function() {
                    const parent = this.closest('.upload-section');
                    const content = parent.querySelector('.accordion-content');
                    const icon = parent.querySelector('.toggle-icon');

                    document.querySelectorAll('.upload-section').forEach(sec => {
                        if (sec !== parent) {
                            sec.querySelector('.accordion-content').classList.remove('open');
                            sec.querySelector('.toggle-icon').style.transform = 'rotate(0deg)';
                            sec.classList.remove('ring-2', 'ring-blue-100');
                        }
                    });

                    if (content.classList.contains('open')) {
                        content.classList.remove('open');
                        icon.style.transform = 'rotate(0deg)';
                        parent.classList.remove('ring-2', 'ring-blue-100');
                    } else {
                        content.classList.add('open');
                        icon.style.transform = 'rotate(180deg)';
                        parent.classList.add('ring-2', 'ring-blue-100');
                    }
                });
            });

            // --- 2. LOAD DATA ---
            if (idDataSertifikasi) {
                fetch(`/api/v1/bukti-kelengkapan/list/${idDataSertifikasi}`)
                    .then(res => res.json())
                    .then(response => {
                        if (response.success && response.data.length > 0) {
                            const data = response.data;
                            document.querySelectorAll('.upload-section').forEach(section => {
                                const jenis = section.dataset.jenis;
                                const isMulti = section.dataset.multi === 'true';
                                const items = data.filter(item => item.keterangan && item.keterangan.startsWith(jenis));

                                if (items.length > 0) {
                                    updateStatusBadge(section, true);
                                    const listContainer = section.querySelector('.uploaded-list');
                                    listContainer.innerHTML = '';
                                    items.forEach(item => {
                                        // PASTIKAN ID DIAMBIL. Cek struktur tabel kamu (id, id_bukti_dasar, dll)
                                        const realId = item.id || item.id_bukti_dasar || item.id_bukti_kelengkapan;
                                        addFileToListUI(listContainer, item.bukti_dasar, item.keterangan, realId, section);
                                    });
                                    toggleFormVisibility(section, false); 
                                }
                            });
                        }
                    });
            }

            // --- 3. HANDLE UPLOAD ---
            document.querySelectorAll('.upload-section').forEach(section => {
                const fileInput = section.querySelector('.file-input');
                const btnSelect = section.querySelector('.btn-select-file');
                const btnSave = section.querySelector('.btn-save');
                const btnCancelUpload = section.querySelector('.btn-cancel-upload');
                const btnToggleForm = section.querySelector('.btn-toggle-form');
                const descInput = section.querySelector('.description-input');
                const previewImg = section.querySelector('.preview-img');
                const previewPlaceholder = section.querySelector('.preview-placeholder');
                const jenisDokumen = section.dataset.jenis;
                const isMulti = section.dataset.multi === 'true';

                btnToggleForm.addEventListener('click', () => {
                    toggleFormVisibility(section, true);
                    if(!isMulti) resetForm(section);
                });

                btnCancelUpload.addEventListener('click', () => {
                    toggleFormVisibility(section, false);
                    resetForm(section);
                });

                btnSelect.addEventListener('click', () => fileInput.click());

                fileInput.addEventListener('change', () => {
                    const file = fileInput.files[0];
                    if (file) {
                        btnSave.classList.remove('hidden');
                        btnSelect.innerText = "Ganti";
                        
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                previewImg.src = e.target.result;
                                previewImg.classList.remove('hidden');
                                previewPlaceholder.classList.add('hidden');
                            }
                            reader.readAsDataURL(file);
                        } else {
                            previewImg.classList.add('hidden');
                            previewPlaceholder.classList.remove('hidden');
                            previewPlaceholder.innerHTML = `<span class="text-xs font-bold text-gray-600 block px-2 truncate">${file.name}</span>`;
                        }
                    }
                });

                btnSave.addEventListener('click', async () => {
                    const file = fileInput.files[0];
                    if (!file) return;

                    const originalBtnContent = btnSave.innerHTML;
                    btnSave.innerHTML = '<svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                    btnSave.disabled = true;

                    const formData = new FormData();
                    formData.append('id_data_sertifikasi_asesi', idDataSertifikasi);
                    formData.append('jenis_dokumen', jenisDokumen);
                    formData.append('file', file);
                    const ket = descInput.value ? `${jenisDokumen} - ${descInput.value}` : jenisDokumen;
                    formData.append('keterangan', ket);

                    try {
                        const res = await fetch('/api/v1/bukti-kelengkapan/store', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                            body: formData
                        });
                        const result = await res.json();

                        if (res.ok && result.success) {
                            Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Dokumen tersimpan.', timer: 1000, showConfirmButton: false });

                            const listContainer = section.querySelector('.uploaded-list');
                            if (!isMulti) listContainer.innerHTML = '';
                            
                            // AMBIL ID BARU DARI RESPONSE
                            // Sesuaikan 'id' dengan nama kolom PK kamu kalau beda (misal result.data.id_bukti_dasar)
                            const newId = result.data.id || result.data.id_bukti_dasar || null;
                            
                            addFileToListUI(listContainer, file.name, ket, newId, section, true);

                            updateStatusBadge(section, true);
                            resetForm(section);
                            toggleFormVisibility(section, false);

                        } else {
                            throw new Error(result.message || 'Gagal upload');
                        }
                    } catch (err) {
                        Swal.fire('Gagal', err.message, 'error');
                    } finally {
                        btnSave.innerHTML = originalBtnContent;
                        btnSave.disabled = false;
                    }
                });
            });

            // --- HELPER FUNCTIONS ---

            function toggleFormVisibility(section, showForm) {
                const formWrapper = section.querySelector('.upload-form-wrapper');
                const actionWrapper = section.querySelector('.btn-action-wrapper');
                const btnCancel = section.querySelector('.btn-cancel-upload');
                const hasItems = section.querySelector('.uploaded-list').children.length > 0;

                if (showForm) {
                    formWrapper.classList.remove('hidden');
                    formWrapper.classList.add('fade-enter-active');
                    actionWrapper.classList.add('hidden');
                    if(hasItems) btnCancel.classList.remove('hidden');
                } else {
                    formWrapper.classList.add('hidden');
                    actionWrapper.classList.remove('hidden');
                    actionWrapper.classList.add('fade-enter-active');
                    btnCancel.classList.add('hidden');
                }
            }

            function resetForm(section) {
                section.querySelector('.file-input').value = '';
                section.querySelector('.description-input').value = '';
                section.querySelector('.preview-img').classList.add('hidden');
                const placeholder = section.querySelector('.preview-placeholder');
                placeholder.classList.remove('hidden');
                placeholder.innerHTML = '<span class="text-[10px] text-gray-400">Preview</span>';
                section.querySelector('.btn-save').classList.add('hidden');
                section.querySelector('.btn-select-file').innerText = "Pilih File";
            }

            function updateStatusBadge(section, isFilled) {
                const badge = section.querySelector('.status-badge');
                if (isFilled) {
                    badge.className = 'status-badge bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1';
                    badge.innerHTML = `<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Berhasil Upload`;
                } else {
                    badge.className = 'status-badge bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-bold';
                    badge.innerHTML = 'Wajib Diisi';
                }
            }

            // --- LOGIC HAPUS YANG SUDAH DIPERBAIKI (TUNGGU SERVER) ---
            function addFileToListUI(container, source, keterangan, id, section, isNewUpload = false) {
                const div = document.createElement('div');
                div.className = 'flex items-center justify-between bg-gray-50 p-3 rounded-lg border border-gray-200 animate-[fadeIn_0.3s_ease-out] group hover:border-blue-300 transition-colors';
                const url = isNewUpload ? '#' : `/secure-doc/${source}`;
                
                div.innerHTML = `
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center shrink-0 text-blue-600">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <a href="${url}" target="_blank" class="block">
                                <p class="text-sm font-semibold text-gray-800 truncate hover:text-blue-600 transition-colors cursor-pointer" title="Klik untuk lihat file">${keterangan || source}</p>
                            </a>
                            <p class="text-xs text-gray-500">${isNewUpload ? 'Baru saja' : 'Tersimpan'}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1 opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity">
                        <button type="button" class="btn-edit-item p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit / Ganti">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732"></path></svg>
                        </button>
                        <button type="button" class="btn-delete-item p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                `;
                
                // Logic DELETE (FIXED)
                const btnDel = div.querySelector('.btn-delete-item');
                btnDel.addEventListener('click', () => {
                    // Validasi: Kalau ID null (misal upload gagal tapi UI nongol), jangan hit server
                    if (!id) {
                        Swal.fire('Error', 'ID Dokumen tidak valid (Silakan refresh halaman)', 'error');
                        return;
                    }

                    Swal.fire({
                        title: 'Hapus Dokumen?',
                        text: "File akan dihapus permanen.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#EF4444',
                        cancelButtonColor: '#D1D5DB',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // FETCH DULU, BARU HAPUS UI
                            fetch(`/api/v1/bukti-kelengkapan/${id}`, {
                                method: 'DELETE',
                                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    // Server bilang OK, baru hapus UI
                                    div.remove();
                                    
                                    // Cek kalau kosong, update badge & buka form
                                    if(container.children.length === 0) {
                                        updateStatusBadge(section, false);
                                        toggleFormVisibility(section, true);
                                    }
                                    
                                    Swal.fire('Terhapus!', 'File berhasil dihapus.', 'success');
                                } else {
                                    Swal.fire('Gagal', data.message || 'Gagal menghapus.', 'error');
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                Swal.fire('Error', 'Terjadi kesalahan server.', 'error');
                            });
                        }
                    });
                });

                // Logic EDIT (Ganti File)
                const btnEdit = div.querySelector('.btn-edit-item');
                btnEdit.addEventListener('click', () => {
                     Swal.fire({
                        title: 'Ganti File?',
                        text: "File lama akan dihapus dan Anda bisa mengunggah yang baru.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Ganti',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Hapus dulu (optional: bisa langsung ganti via upload tapi lebih bersih hapus dulu)
                            if(id) {
                                fetch(`/api/v1/bukti-kelengkapan/${id}`, {
                                    method: 'DELETE',
                                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                                });
                            }
                            div.remove();
                            // Isi form dengan data lama
                            const cleanKet = keterangan.includes(' - ') ? keterangan.split(' - ')[1] : '';
                            section.querySelector('.description-input').value = cleanKet;
                            toggleFormVisibility(section, true);
                            if(container.children.length === 0) updateStatusBadge(section, false);
                        }
                    });
                });

                container.appendChild(div);
            }

            document.getElementById('btn-next-page').addEventListener('click', () => {
                let complete = true;
                document.querySelectorAll('.upload-section').forEach(sec => {
                    if (sec.querySelector('.uploaded-list').children.length === 0) complete = false;
                });
                if (complete) window.location.href = nextUrl;
                else Swal.fire('Belum Lengkap', 'Mohon lengkapi semua dokumen wajib.', 'warning');
            });
        });
    </script>
</body>
</html>