<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Bukti Kelengkapan Asesi | LSP Polines</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <style>
    body { font-family: 'Poppins', sans-serif; }
    ::-webkit-scrollbar { width: 0; }
  </style>
</head>

<body class="bg-gray-50 text-gray-800 text-sm">

  <x-navbar.navbar_admin/>
  
  <main class="flex min-h-[calc(100vh-80px)]">
    
    @php
        // [PERBAIKAN] Logic URL Kembali & Context Sidebar
        $urlKembali = route('admin.master_asesi'); 

        // Gunakan $sertifikasiAcuan dari Controller agar link kembali sesuai jadwal
        if (isset($sertifikasiAcuan) && $sertifikasiAcuan) {
            $urlKembali = route('admin.schedule.attendance', $sertifikasiAcuan->id_jadwal);
        }
    @endphp

    <x-sidebar.sidebar_profile_asesi 
        :asesi="$asesi" 
        :backUrl="$urlKembali"
        :activeSertifikasi="$sertifikasiAcuan" 
    />

    <section class="ml-[22%] flex-1 p-8 h-[calc(100vh-80px)] overflow-y-auto bg-gray-50">
      
      <div class="bg-white p-10 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] border border-gray-100 mb-8">
        
        <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Bukti Kelengkapan</h1>

        <div class="space-y-6" id="dokumen-container">
            
            @foreach($persyaratan as $index => $syarat)
                @php
                    $bukti = $asesi->buktiDasar->first(function($item) use ($syarat) {
                        return strpos($item->keterangan, $syarat['jenis']) !== false;
                    });
                    
                    $isUploaded = !is_null($bukti);
                    $fileUrl = $isUploaded ? asset($bukti->bukti_dasar) : '';
                    $fileExt = $isUploaded ? pathinfo($bukti->bukti_dasar, PATHINFO_EXTENSION) : '';
                    $userKeterangan = $isUploaded ? (explode(' - ', $bukti->keterangan)[1] ?? '') : '';
                    
                    // ID unik untuk setiap item (digunakan untuk state open/close)
                    $itemId = 'item-' . $index; 
                @endphp

                <div class="upload-section border border-gray-200 rounded-lg shadow-sm overflow-hidden transition-all duration-200 hover:shadow-md" 
                     data-id="{{ $itemId }}"
                     x-data="{ 
                        // Default closed, nanti di-override oleh JS jika ada di localStorage
                        open: false, 
                        init() {
                            // Cek localStorage apakah item ini harus terbuka
                            if(localStorage.getItem('open_' + '{{ $itemId }}') === 'true') {
                                this.open = true;
                            }
                        },
                        toggle() {
                            this.open = !this.open;
                            // Simpan state ke localStorage
                            if(this.open) {
                                localStorage.setItem('open_' + '{{ $itemId }}', 'true');
                            } else {
                                localStorage.removeItem('open_' + '{{ $itemId }}');
                            }
                        }
                     }">

                    {{-- HEADER ACCORDION --}}
                    <div @click="toggle()" class="flex items-center justify-between px-4 py-3 bg-gray-50 cursor-pointer hover:bg-gray-100 transition">
                        <div>
                            <h3 class="font-semibold text-gray-700 text-sm" data-jenis="{{ $syarat['jenis'] }}">{{ $syarat['jenis'] }}</h3>
                            <p class="text-[11px] text-gray-500 mt-0.5 leading-tight">{{ $syarat['desc'] }}</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            @if($isUploaded)
                                <span class="text-[11px] text-green-600 font-bold bg-green-100 px-2.5 py-0.5 rounded-full flex items-center file-status">
                                    <i class="fas fa-check mr-1 text-[10px]"></i> Terunggah
                                </span>
                            @else
                                <span class="text-[11px] text-red-500 font-semibold file-status">Belum diunggah</span>
                            @endif
                            <i class="fas text-gray-400 text-xs transition-transform duration-300" :class="open ? 'fa-chevron-up rotate-180' : 'fa-chevron-down'"></i>
                        </div>
                    </div>

                    {{-- CONTENT --}}
                    <div x-show="open" x-transition class="p-4 border-t border-gray-200 bg-white toggle-content">
                        <div class="flex flex-col md:flex-row items-start gap-4">
                            
                            {{-- PREVIEW BOX --}}
                            <div class="preview-box w-full md:w-40 md:h-40 bg-gray-50 rounded-lg flex items-center justify-center overflow-hidden border-2 border-dashed border-gray-300 relative group shrink-0">
                                @if($isUploaded)
                                    @if(in_array(strtolower($fileExt), ['jpg','jpeg','png','webp']))
                                        <img src="{{ $fileUrl }}" class="w-full h-full object-cover cursor-pointer hover:opacity-90 transition" onclick="window.open(this.src, '_blank')">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition pointer-events-none"></div>
                                    @else
                                        <div class="text-center p-2">
                                            <i class="fas fa-file-pdf text-red-500 text-2xl mb-1"></i>
                                            <span class="text-[10px] font-bold text-gray-600 block uppercase truncate w-16">{{ $fileExt }}</span>
                                            <a href="{{ $fileUrl }}" target="_blank" class="text-blue-600 text-[10px] hover:underline mt-0.5 inline-block">Buka</a>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center text-gray-400">
                                        <i class="fas fa-image text-xl mb-1 opacity-50"></i>
                                        <span class="text-[10px] block">Preview</span>
                                    </div>
                                @endif
                            </div>

                            {{-- FORM ACTION --}}
                            <div class="flex-1 w-full space-y-2">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Keterangan Tambahan</label>
                                    <input type="text" class="description-input w-full border-gray-200 rounded-md shadow-sm text-xs focus:border-blue-500 focus:ring-blue-500 px-3 py-1.5 bg-gray-50" 
                                           placeholder="Contoh: Ijazah S1 Tahun 2024..." value="{{ $userKeterangan }}">
                                </div>
                                
                                <input type="file" class="file-input hidden" accept=".jpg,.jpeg,.png,.pdf">

                                <div class="flex flex-wrap gap-2 mt-2">
                                    {{-- Tombol Pilih File --}}
                                    <button type="button" class="btn-select-file px-3 py-1.5 bg-blue-50 text-blue-600 text-xs font-bold rounded-md hover:bg-blue-100 transition-colors shadow-sm {{ $isUploaded ? 'hidden' : '' }}">
                                        <i class="fas fa-upload mr-1.5"></i> Pilih File
                                    </button>

                                    {{-- Tombol Simpan/Batal --}}
                                    <div class="button-group-ready hidden flex gap-2 w-full md:w-auto">
                                        <button type="button" class="btn-save px-3 py-1.5 bg-green-500 text-white text-xs font-bold rounded-md hover:bg-green-600 transition-colors shadow-sm flex items-center">
                                            <i class="fas fa-save mr-1.5"></i> Simpan
                                        </button>
                                        <button type="button" class="btn-cancel px-3 py-1.5 bg-gray-100 text-gray-600 text-xs font-bold rounded-md hover:bg-gray-200 transition-colors">
                                            Batal
                                        </button>
                                    </div>

                                    {{-- Tombol Edit/Hapus --}}
                                    <div class="button-group-uploaded flex gap-2 {{ $isUploaded ? '' : 'hidden' }}">
                                        <button type="button" class="btn-edit px-3 py-1.5 bg-yellow-400 text-white text-xs font-bold rounded-md hover:bg-yellow-500 transition-colors shadow-sm flex items-center">
                                            <i class="fas fa-edit mr-1.5"></i> Edit
                                        </button>
                                        <button type="button" class="btn-delete px-3 py-1.5 bg-red-500 text-white text-xs font-bold rounded-md hover:bg-red-600 transition-colors shadow-sm flex items-center"
                                                data-id="{{ $bukti->id_bukti_dasar ?? '' }}">
                                            <i class="fas fa-trash mr-1.5"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
      </div>

      {{-- CARD TANDA TANGAN & IDENTITAS --}}
      <div class="bg-white p-10 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] border border-gray-100 mt-8">
        
        <h3 class="text-2xl font-bold text-gray-800 text-center mb-8">Tanda Tangan Pemohon</h3>
        
        {{-- BAGIAN IDENTITAS --}}
        @php
            // Menggunakan relasi hasOne (tanpa first)
            $pekerjaan = $asesi->dataPekerjaan; 
        @endphp

        <div class="bg-gray-50 rounded-xl p-6 max-w-3xl mx-auto border border-gray-200 mb-8">
            <p class="text-sm font-semibold text-gray-800 mb-4">Saya yang bertanda tangan di bawah ini:</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                {{-- Item 1 --}}
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Nama Lengkap</label>
                    <p class="text-gray-900 font-medium text-sm">{{ $asesi->nama_lengkap ?? '-' }}</p>
                </div>
                
                {{-- Item 2 --}}
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Jabatan</label>
                    <p class="text-gray-900 font-medium text-sm">{{ $pekerjaan?->jabatan ?? '-' }}</p>
                </div>
                
                {{-- Item 3 --}}
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Perusahaan</label>
                    <p class="text-gray-900 font-medium text-sm">{{ $pekerjaan?->nama_institusi_pekerjaan ?? '-' }}</p>
                </div>
                
                {{-- Item 4 --}}
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Alamat Perusahaan</label>
                    <p class="text-gray-900 font-medium text-sm">{{ $pekerjaan?->alamat_institusi ?? '-' }}</p>
                </div>
            </div>

            <div class="mt-5 pt-4 border-t border-gray-200 text-gray-600 text-[11px] leading-relaxed">
                Dengan ini saya menyatakan bahwa data yang saya isikan adalah benar dan saya setuju untuk mengikuti proses sertifikasi sesuai dengan prosedur yang berlaku.
            </div>
        </div>
        {{-- BAGIAN UPLOAD TTD --}}
        <div class="flex flex-col items-center" x-data="{ hasTtd: {{ $asesi->tanda_tangan ? 'true' : 'false' }} }">
            <h3 class="text-sm text-gray-700 font-medium mb-2">Tanda Tangan Saat Ini</h3>
            <div class="h-32 w-auto mb-2 border rounded-md p-1 overflow-hidden" x-show="hasTtd">
                <img :src="hasTtd ? '{{ $asesi->tanda_tangan ? route('secure.file', ['path' => $asesi->tanda_tangan]) : '' }}' : ''" 
                     src="{{ $asesi->tanda_tangan ? route('secure.file', ['path' => $asesi->tanda_tangan]) : '' }}" 
                     alt="Tanda Tangan" 
                     class="h-full object-contain">
            </div>
            <p x-show="!hasTtd" class="text-xs text-gray-500 italic mb-2">Belum ada tanda tangan</p>
            {{-- 1. AREA PREVIEW GAMBAR --}}
            <div class="w-full max-w-3xl h-64 border-2 border-dashed border-gray-300 rounded-xl bg-white flex items-center justify-center overflow-hidden relative group">
                
                <img id="img-ttd-preview" 
                     src="{{ $asesi->tanda_tangan ? route('secure.file', ['path' => $asesi->tanda_tangan]) : '' }}" 
                     class="max-h-full max-w-full object-contain p-6" 
                     :class="hasTtd ? '' : 'hidden'">
                
                <div class="text-center text-gray-400" :class="hasTtd ? 'hidden' : ''">
                    <i class="fas fa-signature text-5xl mb-4 opacity-50"></i>
                    <p class="text-base font-medium">Belum ada tanda tangan</p>
                </div>
            </div>

            {{-- 2. TEKS FORMAT --}}
            <p class="text-xs text-gray-400 mt-4 text-center">
                Format: PNG/JPG (Disarankan latar belakang transparan). Maks 2MB.
            </p>

            {{-- 3. TOMBOL AKSI --}}
            <div class="flex flex-wrap justify-center gap-4 mt-6">
                <input type="file" id="input-ttd" class="hidden" accept="image/png, image/jpeg, image/jpg">
                
                {{-- Tombol Edit/Upload (Warna DINAMIS) --}}
                {{-- Perhatikan bagian :class di bawah ini --}}
                <button type="button" id="btn-upload-ttd" 
                        class="px-6 py-2.5 font-bold rounded-full transition shadow-md text-sm flex items-center text-white"
                        :class="hasTtd ? 'bg-yellow-400 hover:bg-yellow-500' : 'bg-blue-600 hover:bg-blue-700'">
                    {{-- Icon dinamis --}}
                    <i class="fas mr-2" :class="hasTtd ? 'fa-edit' : 'fa-upload'"></i> 
                    {{-- Teks dinamis --}}
                    <span x-text="hasTtd ? 'Edit' : 'Upload TTD'"></span>
                </button>

                {{-- Tombol Hapus (Merah Solid) --}}
                <button type="button" id="btn-delete-ttd" 
                        class="px-6 py-2.5 bg-red-500 text-white font-bold rounded-full hover:bg-red-600 transition shadow-sm text-sm flex items-center"
                        :class="hasTtd ? '' : 'hidden'">
                    <i class="fas fa-trash mr-2"></i> Hapus
                </button>
            </div>
            
        </div>
      </div>

    </section>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
        // === VARIABEL GLOBAL ===
        const idAsesi = "{{ $asesi->id_asesi }}"; 
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // =========================================================================
        // BAGIAN 1: LOGIKA BUKTI KELENGKAPAN (DOKUMEN)
        // =========================================================================

        // Helper: Render Preview Gambar/PDF
        function renderPreview(previewBox, file) {
            previewBox.innerHTML = '';
            const isImage = file.type.startsWith('image/');
            if (isImage) {
                const img = document.createElement('img');
                img.className = 'w-full h-full object-cover rounded-lg';
                img.src = URL.createObjectURL(file);
                previewBox.appendChild(img);
            } else {
                previewBox.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full p-2 text-center">
                        <i class="fas fa-file-pdf text-red-500 text-3xl mb-1"></i>
                        <span class="text-[10px] text-gray-600 block break-all leading-tight">${file.name}</span>
                    </div>`;
            }
        }

        // Loop untuk setiap item upload dokumen
        document.querySelectorAll('.upload-section').forEach(section => {
            const fileInput = section.querySelector('.file-input');
            const btnSelect = section.querySelector('.btn-select-file');
            const btnEdit = section.querySelector('.btn-edit');
            const btnSave = section.querySelector('.btn-save');
            const btnCancel = section.querySelector('.btn-cancel');
            const btnDelete = section.querySelector('.btn-delete');
            const previewBox = section.querySelector('.preview-box');
            const descInput = section.querySelector('.description-input');
            const groupReady = section.querySelector('.button-group-ready');
            const groupUploaded = section.querySelector('.button-group-uploaded');
            const jenisDokumen = section.querySelector('h3').getAttribute('data-jenis');
            
            // Ambil ID Section untuk localStorage
            const sectionId = section.getAttribute('data-id'); 
            
            // Cek apakah ini Update atau Create (berdasarkan tombol delete yang punya data-id)
            const existingId = btnDelete ? btnDelete.getAttribute('data-id') : null;

            let selectedFile = null;

            // Trigger File Input
            const triggerFile = () => fileInput.click();
            if(btnSelect) btnSelect.addEventListener('click', triggerFile);
            if(btnEdit) btnEdit.addEventListener('click', triggerFile);

            // Handle File Change
            fileInput.addEventListener('change', () => {
                if (fileInput.files.length > 0) {
                    selectedFile = fileInput.files[0];
                    renderPreview(previewBox, selectedFile);
                    
                    if(btnSelect) btnSelect.classList.add('hidden');
                    if(groupUploaded) groupUploaded.classList.add('hidden');
                    groupReady.classList.remove('hidden');
                }
            });

            // Handle Cancel
            if(btnCancel) {
                btnCancel.addEventListener('click', () => {
                    fileInput.value = '';
                    selectedFile = null;
                    groupReady.classList.add('hidden');
                    location.reload(); 
                });
            }

            // Handle Save (Create / Update)
            if(btnSave) {
                btnSave.addEventListener('click', async () => {
                    if (!selectedFile) return;
                    
                    // Simpan state agar tetap terbuka setelah reload
                    localStorage.setItem('open_' + sectionId, 'true');

                    const formData = new FormData();
                    formData.append('file', selectedFile);
                    formData.append('jenis_dokumen', jenisDokumen);
                    formData.append('keterangan', descInput.value);

                    const originalText = btnSave.innerHTML;
                    btnSave.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                    btnSave.disabled = true;

                    try {
                        let url, method;

                        // LOGIKA PENENTUAN ROUTE (CREATE vs UPDATE)
                        if (existingId) {
                            // UPDATE (Pakai POST karena upload file)
                            url = `/admin/asesi/${idAsesi}/bukti/update/${existingId}`;
                            method = 'POST';
                        } else {
                            // CREATE
                            url = `/admin/asesi/${idAsesi}/bukti/store`;
                            method = 'POST';
                        }

                        const response = await fetch(url, {
                            method: method,
                            headers: { 'X-CSRF-TOKEN': csrfToken },
                            body: formData
                        });
                        
                        const result = await response.json();

                        if (result.success) {
                            alert('Berhasil diunggah!');
                            location.reload(); 
                        } else {
                            throw new Error(result.message);
                        }
                    } catch (error) {
                        alert('Gagal: ' + error.message);
                        btnSave.innerHTML = originalText;
                        btnSave.disabled = false;
                        localStorage.removeItem('open_' + sectionId); // Hapus state jika gagal
                    }
                });
            }

            // Handle Delete Dokumen
            if(btnDelete) {
                btnDelete.addEventListener('click', async () => {
                    if(!confirm('Yakin ingin menghapus dokumen ini?')) return;
                    
                    localStorage.setItem('open_' + sectionId, 'true');

                    const idBukti = btnDelete.getAttribute('data-id');
                    try {
                        const response = await fetch(`/admin/asesi/${idAsesi}/bukti/delete/${idBukti}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json' }
                        });
                        const result = await response.json();
                        if (result.success) {
                            alert('Dokumen dihapus!');
                            location.reload();
                        } else {
                            throw new Error(result.message);
                        }
                    } catch (error) {
                        alert('Gagal menghapus: ' + error.message);
                    }
                });
            }
        });


        // =========================================================================
        // BAGIAN 2: LOGIKA TANDA TANGAN (TTD)
        // =========================================================================
        
        const inputTtd = document.getElementById('input-ttd');
        const btnUploadTtd = document.getElementById('btn-upload-ttd');
        const btnDeleteTtd = document.getElementById('btn-delete-ttd');
        const imgTtd = document.getElementById('img-ttd-preview');

        // Klik tombol upload -> trigger input file
        if(btnUploadTtd) {
            btnUploadTtd.addEventListener('click', () => inputTtd.click());
        }

        // Handle File Selected (Upload Langsung)
        if(inputTtd) {
            inputTtd.addEventListener('change', async () => {
                if (inputTtd.files.length > 0) {
                    const file = inputTtd.files[0];
                    const formData = new FormData();
                    formData.append('file_ttd', file);

                    const originalHtml = btnUploadTtd.innerHTML;
                    const isEditing = btnUploadTtd.innerText.includes('Ganti') || btnUploadTtd.innerText.includes('Edit');
                    
                    btnUploadTtd.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i> ${isEditing ? 'Mengganti...' : 'Mengupload...'}`;
                    btnUploadTtd.disabled = true;
                    if(btnDeleteTtd) btnDeleteTtd.disabled = true;

                    try {
                        const response = await fetch(`/admin/asesi/${idAsesi}/ttd/store`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken },
                            body: formData
                        });
                        const result = await response.json();

                        if (result.success) {
                            alert('Tanda tangan berhasil disimpan!');
                            location.reload(); 
                        } else {
                            throw new Error(result.message);
                        }
                    } catch (error) {
                        alert('Gagal: ' + error.message);
                        btnUploadTtd.innerHTML = originalHtml;
                        btnUploadTtd.disabled = false;
                        if(btnDeleteTtd) btnDeleteTtd.disabled = false;
                        inputTtd.value = ''; 
                    }
                }
            });
        }

        // Handle Hapus TTD
        if(btnDeleteTtd) {
            btnDeleteTtd.addEventListener('click', async () => {
                if(!confirm('Yakin ingin menghapus tanda tangan ini?')) return;

                const originalHtml = btnDeleteTtd.innerHTML;
                btnDeleteTtd.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menghapus...';
                btnDeleteTtd.disabled = true;
                if(btnUploadTtd) btnUploadTtd.disabled = true;

                try {
                    const response = await fetch(`/admin/asesi/${idAsesi}/ttd/delete`, {
                        method: 'DELETE',
                        headers: { 
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        }
                    });
                    const result = await response.json();

                    if (result.success) {
                        alert('Tanda tangan dihapus!');
                        location.reload();
                    } else {
                        throw new Error(result.message);
                    }
                } catch (error) {
                    alert('Gagal menghapus: ' + error.message);
                    btnDeleteTtd.innerHTML = originalHtml;
                    btnDeleteTtd.disabled = false;
                    if(btnUploadTtd) btnUploadTtd.disabled = false;
                }
            });
        }
    });
</script>
</body>
</html>