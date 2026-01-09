<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Asesor Bukti Kelengkapan | LSP Polines</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    body { font-family: 'Poppins', sans-serif; background-color: #f9fafb; }
    ::-webkit-scrollbar { width: 0; }
  </style>
  <script>
    document.addEventListener("alpine:init", () => {
        Alpine.store("sidebar", {
            open: true,
            toggle() {
                this.open = !this.open
            },
            setOpen(val) {
                this.open = val
            }
        })
    })
  </script>
  <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="text-gray-800">

  <x-navbar.navbar-admin />

  <div class="flex min-h-[calc(100vh-80px)]">

    <x-sidebar.sidebar_profile_asesor :asesor="$asesor" />

    <main class="ml-[22%] h-[calc(100vh-80px)] overflow-y-auto p-8 bg-gray-50 flex-1">

      <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] p-10 border border-gray-100 min-h-full">

        <h2 class="text-3xl font-bold text-gray-800 mb-10 text-center">Bukti Kelengkapan</h2>

        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                class="mb-6 p-4 bg-green-100 text-green-700 border border-green-200 rounded-lg flex justify-between items-center">
                <span class="font-medium"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</span>
                <button @click="show = false" class="text-green-900 hover:text-green-700"><i class="fas fa-times"></i></button>
            </div>
        @endif

        <div class="space-y-6">
            @foreach ($documents as $index => $doc)
                @php
                    $isUploaded = !empty($doc['file_path']);
                    $fileUrl = $isUploaded ? route('secure.file', ['path' => $doc['file_path']]) : '';
                    $fileExt = $isUploaded ? pathinfo($doc['file_path'], PATHINFO_EXTENSION) : '';
                    $isImage = in_array(strtolower($fileExt), ['jpg', 'jpeg', 'png', 'webp']);
                    $itemId = 'item-' . $index;
                @endphp

                <div class="upload-section border border-gray-200 rounded-lg shadow-sm overflow-hidden transition-all duration-200 hover:shadow-md"
                     data-id="{{ $itemId }}"
                     x-data="{ 
                        open: false,
                        init() {
                            if(localStorage.getItem('open_asesor_' + '{{ $itemId }}') === 'true') {
                                this.open = true;
                            }
                        },
                        toggle() {
                            this.open = !this.open;
                            if(this.open) {
                                localStorage.setItem('open_asesor_' + '{{ $itemId }}', 'true');
                            } else {
                                localStorage.removeItem('open_asesor_' + '{{ $itemId }}');
                            }
                        }
                     }">
                    {{-- HEADER ACCORDION --}}
                    <div @click="toggle()" class="flex items-center justify-between px-4 py-3 bg-gray-50 cursor-pointer hover:bg-gray-100 transition">
                        <div>
                            <h3 class="font-semibold text-gray-700 text-sm" data-jenis="{{ $doc['key'] }}">{{ $doc['title'] }}</h3>
                            <p class="text-[11px] text-gray-500 mt-0.5 leading-tight">{{ $doc['subtitle'] }}</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            @if($isUploaded)
                                <span class="text-[11px] text-green-600 font-bold bg-green-100 px-2.5 py-0.5 rounded-full flex items-center">
                                    <i class="fas fa-check mr-1 text-[10px]"></i> Terunggah
                                </span>
                            @else
                                <span class="text-[11px] text-red-500 font-semibold">Belum diunggah</span>
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
                                    @if($isImage)
                                        <img src="{{ $fileUrl }}" class="w-full h-full object-cover cursor-pointer hover:opacity-90 transition" onclick="window.open('{{ $fileUrl }}', '_blank')">
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

                            {{-- ACTIONS --}}
                            <div class="flex-1 w-full space-y-2">
                                <div>
                                    <p class="text-xs text-gray-500 mb-2">
                                        @if($isUploaded)
                                            File saat ini: <span class="font-medium text-gray-700">{{ basename($doc['file_path']) }}</span>
                                        @else
                                            Belum ada file yang diunggah.
                                        @endif
                                    </p>
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
                                            <i class="fas fa-edit mr-1.5"></i> Ganti File
                                        </button>
                                        <button type="button" class="btn-delete px-3 py-1.5 bg-red-500 text-white text-xs font-bold rounded-md hover:bg-red-600 transition-colors shadow-sm flex items-center"
                                                data-key="{{ $doc['key'] }}">
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

        {{-- CARD TANDA TANGAN & IDENTITAS --}}
        <div class="mt-10 p-10 border border-gray-200 rounded-2xl shadow-md bg-white">
            <h3 class="text-2xl font-bold text-gray-800 text-center mb-8">Tanda Tangan Asesor</h3>

            <div class="bg-gray-50 rounded-xl p-6 max-w-3xl mx-auto border border-gray-200 mb-8">
                <p class="text-sm font-semibold text-gray-800 mb-4">Saya yang bertanda tangan di bawah ini:</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Nama Lengkap</label>
                        <p class="text-gray-900 font-medium text-sm">{{ $asesor->nama_lengkap }}</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Pekerjaan</label>
                        <p class="text-gray-900 font-medium text-sm">{{ $asesor->pekerjaan }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs text-gray-500 mb-1">Alamat Korespondensi</label>
                        <p class="text-gray-900 font-medium text-sm">{{ $asesor->alamat_rumah ?? '-' }}</p>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-gray-200 text-gray-600 text-[11px] leading-relaxed">
                    Dengan ini saya menyatakan bahwa data yang saya isikan adalah benar dan dapat dipertanggungjawabkan. Dokumen ini digunakan sebagai bukti pemenuhan syarat Sertifikasi Kompetensi.
                </div>
            </div>

            <div class="flex flex-col items-center justify-center" x-data="{ hasTtd: {{ $asesor->tanda_tangan ? 'true' : 'false' }} }">
                 <div class="w-full max-w-3xl h-64 border-2 border-dashed border-gray-300 rounded-xl bg-white flex items-center justify-center overflow-hidden relative group">
                    <img id="img-ttd-preview" 
                         src="{{ $asesor->tanda_tangan ? route('secure.file', ['path' => $asesor->tanda_tangan]) : '' }}" 
                         class="max-h-full max-w-full object-contain p-6" 
                         :class="hasTtd ? '' : 'hidden'">
                    
                    <div class="text-center text-gray-400" :class="hasTtd ? 'hidden' : ''">
                        <i class="fas fa-signature text-5xl mb-4 opacity-50"></i>
                        <p class="text-base font-medium">Belum ada tanda tangan</p>
                    </div>
                 </div>
                 
                 <p class="text-xs text-gray-400 mt-4 text-center">
                    Format: PNG/JPG (Disarankan latar belakang transparan). Maks 2MB.
                </p>

                 <div class="flex justify-center gap-4 mt-6">
                    <input type="file" id="input-ttd" class="hidden" accept="image/png, image/jpeg, image/jpg">
                    
                    <button type="button" id="btn-upload-ttd" 
                            class="px-6 py-2.5 font-bold rounded-full transition shadow-md text-sm flex items-center text-white"
                            :class="hasTtd ? 'bg-yellow-400 hover:bg-yellow-500' : 'bg-blue-600 hover:bg-blue-700'">
                        <i class="fas mr-2" :class="hasTtd ? 'fa-edit' : 'fa-upload'"></i> 
                        <span x-text="hasTtd ? 'Ganti Tanda Tangan' : 'Upload Tanda Tangan'"></span>
                    </button>

                    <button type="button" id="btn-delete-ttd" 
                            class="px-6 py-2.5 bg-red-500 text-white font-bold rounded-full hover:bg-red-600 transition shadow-sm text-sm flex items-center"
                            :class="hasTtd ? '' : 'hidden'">
                        <i class="fas fa-trash mr-2"></i> Hapus
                    </button>
                 </div>
            </div>
        </div>


        {{-- TOMBOL VERIFIKASI (TETAP SAMA) --}}
        <div class="mt-8 flex flex-col sm:flex-row justify-end items-center gap-4">
            <form action="{{ route('admin.asesor.update_status', $asesor->id_asesor) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak verifikasi asesor ini?');">
                @csrf
                <input type="hidden" name="status_verifikasi" value="rejected">
                <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 flex items-center">
                    <i class="fas fa-times-circle mr-2"></i> Rejected Asesor
                </button>
            </form>

            <form action="{{ route('admin.asesor.update_status', $asesor->id_asesor) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui verifikasi asesor ini?');">
                @csrf
                <input type="hidden" name="status_verifikasi" value="approved">
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 flex items-center">
                    <i class="fas fa-check-circle mr-2"></i> Accepted Asesor
                </button>
            </form>
        </div>

      </div>
    </main>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
        const idAsesor = "{{ $asesor->id_asesor }}"; 
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // =========================================================================
        // BAGIAN 1: LOGIKA BUKTI KELENGKAPAN (DOKUMEN)
        // =========================================================================

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

        document.querySelectorAll('.upload-section').forEach(section => {
            const fileInput = section.querySelector('.file-input');
            const btnSelect = section.querySelector('.btn-select-file');
            const btnEdit = section.querySelector('.btn-edit');
            const btnSave = section.querySelector('.btn-save');
            const btnCancel = section.querySelector('.btn-cancel');
            const btnDelete = section.querySelector('.btn-delete');
            const previewBox = section.querySelector('.preview-box');
            const groupReady = section.querySelector('.button-group-ready');
            const groupUploaded = section.querySelector('.button-group-uploaded');
            const jenisDokumen = section.querySelector('h3').getAttribute('data-jenis');
            const sectionId = section.getAttribute('data-id'); 

            let selectedFile = null;

            const triggerFile = () => fileInput.click();
            if(btnSelect) btnSelect.addEventListener('click', triggerFile);
            if(btnEdit) btnEdit.addEventListener('click', triggerFile);

            fileInput.addEventListener('change', () => {
                if (fileInput.files.length > 0) {
                    selectedFile = fileInput.files[0];
                    renderPreview(previewBox, selectedFile);
                    
                    if(btnSelect) btnSelect.classList.add('hidden');
                    if(groupUploaded) groupUploaded.classList.add('hidden');
                    groupReady.classList.remove('hidden');
                }
            });

            if(btnCancel) {
                btnCancel.addEventListener('click', () => {
                    fileInput.value = '';
                    selectedFile = null;
                    groupReady.classList.add('hidden');
                    location.reload(); 
                });
            }

            if(btnSave) {
                btnSave.addEventListener('click', async () => {
                    if (!selectedFile) return;
                    
                    localStorage.setItem('open_asesor_' + sectionId, 'true');

                    const formData = new FormData();
                    formData.append('file', selectedFile);
                    formData.append('jenis_dokumen', jenisDokumen);
                    
                    const originalText = btnSave.innerHTML;
                    btnSave.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                    btnSave.disabled = true;

                    try {
                        const response = await fetch(`/asesor/${idAsesor}/bukti/store`, {
                            method: 'POST',
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
                        localStorage.removeItem('open_asesor_' + sectionId);
                    }
                });
            }

            if(btnDelete) {
                btnDelete.addEventListener('click', async () => {
                    if(!confirm('Yakin ingin menghapus dokumen ini?')) return;
                    
                    localStorage.setItem('open_asesor_' + sectionId, 'true');

                    // jenisDokumen is passed as parameter to DELETE route
                    try {
                        const response = await fetch(`/asesor/${idAsesor}/bukti/delete/${jenisDokumen}`, {
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

        if(btnUploadTtd) {
            btnUploadTtd.addEventListener('click', () => inputTtd.click());
        }

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
                        const response = await fetch(`/asesor/${idAsesor}/ttd/store`, {
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

        if(btnDeleteTtd) {
            btnDeleteTtd.addEventListener('click', async () => {
                if(!confirm('Yakin ingin menghapus tanda tangan ini?')) return;

                const originalHtml = btnDeleteTtd.innerHTML;
                btnDeleteTtd.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menghapus...';
                btnDeleteTtd.disabled = true;
                if(btnUploadTtd) btnUploadTtd.disabled = true;

                try {
                    const response = await fetch(`/asesor/${idAsesor}/ttd/delete`, {
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