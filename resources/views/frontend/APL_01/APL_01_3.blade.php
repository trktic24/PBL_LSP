@extends('layouts.app-sidebar')
@php
    $jadwal = $sertifikasi->jadwal;
    $asesi = $sertifikasi->asesi;
    $backUrl = route('asesor.tracker', $sertifikasi->jadwal->id_jadwal);
@endphp

@section('content')
    <style>
        .file-input-wrapper input[type="file"] {
            display: none;
        }

        .drag-drop-area.is-dragover {
            border-color: #2563eb;
            background-color: #eff6ff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }
    </style>

    <div class="p-3 sm:p-6 md:p-8">

        {{-- Progress Bar --}}
        <div class="flex items-center justify-center mb-8 sm:mb-12">
            <div class="flex flex-col items-center">
                <div
                    class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-md">
                    1</div>
            </div>
            <div class="w-32 h-1 bg-yellow-400 mx-3"></div>
            <div class="flex flex-col items-center">
                <div
                    class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-md">
                    2</div>
            </div>
            <div class="w-32 h-1 bg-yellow-400 mx-3"></div>
            <div class="flex flex-col items-center">
                <div
                    class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-md ring-4 ring-yellow-100">
                    3</div>
            </div>
        </div>

        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-6 sm:mb-8 text-center">Tanda Tangan Pemohon
        </h1>


        <div class="bg-white p-6 rounded-md shadow-sm mb-6 border border-gray-200">

            <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Informasi Pemohon</h3>

            <dl class="grid grid-cols-1 md:grid-cols-4 gap-y-6 text-sm">
                {{-- Nama Asesor --}}
                <dt class="col-span-1 font-medium text-gray-500">Nama Asesi</dt>
                <dd class="col-span-3 text-gray-900 font-semibold block">: <span id="nama_asesor">[Memuat...]</span></dd>

                {{-- Nama Asesi --}}
                <dt class="col-span-1 font-medium text-gray-500">Perusahaan</dt>
                <dd class="col-span-3 text-gray-900 font-semibold block">: <span id="nama_asesi">[Memuat...]</span></dd>

                {{-- Nama Asesi --}}
                <dt class="col-span-1 font-medium text-gray-500">Nama Perusahaan</dt>
                <dd class="col-span-3 text-gray-900 font-semibold block">: <span id="nama_asesi">[Memuat...]</span></dd>

                {{-- Nama Asesi --}}
                <dt class="col-span-1 font-medium text-gray-500">Jabatan</dt>
                <dd class="col-span-3 text-gray-900 font-semibold block">: <span id="nama_asesi">[Memuat...]</span></dd>
            </dl>
        </div>

        {{-- Pernyataan --}}
        <div class="mt-4 sm:mt-6 bg-blue-50 border-l-4 border-blue-500 p-3 sm:p-4">
            <p class="text-xs sm:text-sm text-white-700 leading-relaxed">
                Dengan ini saya menyatakan mengisi formulir Asesmen Mandiri dengan sebenar-benarnya dan penuh kesadaran,
                serta memahami bahwa segala bentuk kecurangan akan mengakibatkan sanksi dan pembatalan sertifikasi.
            </p>
        </div><br>

        {{-- Form Tanda Tangan --}}
        <form id="signature-upload-form" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="bg-white border-2 border-gray-200 rounded-xl p-6 shadow-lg mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Area Tanda Tangan</h3>

                <div id="signature-preview-container"
                    class="drag-drop-area relative w-full h-64 border-2 border-dashed border-gray-400 rounded-xl flex items-center justify-center bg-gray-50 mb-6 cursor-pointer group transition-all hover:border-blue-500 hover:bg-blue-50">
                    <img id="signature-preview" class="max-h-full max-w-full object-contain p-6 hidden"
                        alt="Preview Tanda Tangan">
                    <div id="upload-placeholder" class="text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400 group-hover:text-blue-600 transition-colors mb-3"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <p class="text-sm font-medium text-gray-600">Klik atau drag & drop file tanda tangan</p>
                        <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG • Maksimal 2MB</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-between gap-4">
                    <div class="file-input-wrapper">
                        <label for="signature-file-input"
                            class="cursor-pointer inline-flex items-center px-6 py-3 bg-blue-100 text-blue-700 font-semibold rounded-lg hover:bg-blue-200 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Pilih File
                        </label>
                        <input type="file" id="signature-file-input" name="signature_file" accept="image/png,image/jpeg,image/jpg">
                    </div>

                    <div class="flex gap-3">
                        <button type="button" id="clear-signature"
                            class="px-6 py-3 bg-red-100 text-red-700 font-semibold rounded-lg hover:bg-red-200 transition flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus
                        </button>
                        <button type="button" id="save-signature"
                            class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 shadow-md transition flex items-center disabled:bg-gray-400 disabled:cursor-not-allowed"
                            disabled>
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            <span>Simpan</span>
                        </button>
                    </div>
                </div>

                <input type="hidden" name="data_tanda_tangan" id="data-tanda-tangan-base64" value="">
            </div>
        </form>

        {{-- Footer Buttons --}}
        <div
            class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 sm:gap-4 mt-8 sm:mt-10 pt-6 border-t border-gray-200">
            @csrf

            <a href="{{ route('APL_01_2', $sertifikasi->id_data_sertifikasi_asesi) }}"
                class="px-6 sm:px-8 py-2.5 sm:py-3 bg-white border border-gray-300 text-gray-700 font-semibold text-sm rounded-lg hover:bg-gray-50 transition shadow-sm text-center flex items-center justify-center">
                Kembali
            </a>
            <button type="button" id="tombol-selanjutnya"
                class="px-6 sm:px-8 py-2.5 sm:py-3 bg-blue-600 text-white font-semibold text-sm rounded-lg hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5 text-center flex items-center justify-center">
                Selanjutnya
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('signature-file-input');
            const previewContainer = document.getElementById('signature-preview-container');
            const previewImg = document.getElementById('signature-preview');
            const placeholder = document.getElementById('upload-placeholder');
            const saveBtn = document.getElementById('save-signature');
            const clearBtn = document.getElementById('clear-signature');
            const base64Input = document.getElementById('data-tanda-tangan-base64');
            const form = document.getElementById('signature-upload-form');
            const asesiId = form.dataset.asesiId;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || form.querySelector(
                'input[name="_token"]').value;

            let currentBase64 = null;

            // === 1. Load data pemohon + tanda tangan lama ===
            fetch(`/api/show-detail/${asesiId}`)
                .then(r => r.ok ? r.json() : Promise.reject('Gagal mengambil data'))
                .then(res => {
                    const d = res.data;
                    document.getElementById('nama-pemohon').textContent = ': ' + (d.nama_lengkap || '-');
                    const kerja = d.data_pekerjaan?.[0] || {};
                    document.getElementById('jabatan-pemohon').textContent = ': ' + (kerja.jabatan || '-');
                    document.getElementById('perusahaan-pemohon').textContent = ': ' + (kerja
                        .nama_institusi_pekerjaan || '-');
                    document.getElementById('alamat-perusahaan-pemohon').textContent = ': ' + (kerja
                        .alamat_institusi || '-');

                    if (d.tanda_tangan) {
                        previewImg.src = d.tanda_tangan.startsWith('http') ? d.tanda_tangan :
                            `{{ route('secure.file', ['path' => 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', d.tanda_tangan);
                        previewImg.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                        base64Input.value = d.tanda_tangan;
                        saveBtn.innerHTML =
                            '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Tersimpan';
                        saveBtn.disabled = true;
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Gagal memuat data pemohon.');
                });

            // === 2. Proses file yang diupload ===
            function processFile(file) {
                if (!file.type.match(/image\/(png|jpeg|jpg)/)) return alert('Format harus JPG atau PNG');
                if (file.size > 2 * 1024 * 1024) return alert('Ukuran maksimal 2MB');

                const reader = new FileReader();
                reader.onload = e => {
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    currentBase64 = e.target.result;
                    saveBtn.disabled = false;
                    saveBtn.innerHTML =
                        '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>Simpan';
                };
                reader.readAsDataURL(file);
            }

            fileInput.addEventListener('change', () => {
                if (fileInput.files[0]) processFile(fileInput.files[0]);
            });

            // === 3. Drag & Drop ===
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(ev => previewContainer.addEventListener(ev,
                e => {
                    e.preventDefault();
                    e.stopPropagation();
                }));
            previewContainer.addEventListener('dragenter', () => previewContainer.classList.add('is-dragover'));
            previewContainer.addEventListener('dragover', () => previewContainer.classList.add('is-dragover'));
            previewContainer.addEventListener('dragleave', () => previewContainer.classList.remove('is-dragover'));
            previewContainer.addEventListener('drop', e => {
                previewContainer.classList.remove('is-dragover');
                if (e.dataTransfer.files[0]) {
                    processFile(e.dataTransfer.files[0]);
                    fileInput.files = e.dataTransfer.files;
                }
            });
            previewContainer.addEventListener('click', () => fileInput.click());

            // === 4. Simpan Tanda Tangan ===
            saveBtn.addEventListener('click', function() {
                if (!currentBase64) return alert('Upload tanda tangan dulu!');

                this.disabled = true;
                this.innerHTML =
                    '<svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"></circle><path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" class="opacity-75"></path></svg>Menyimpan...';

                fetch(`/api/ajax-simpan-tandatangan/${asesiId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            data_tanda_tangan: currentBase64
                        })
                    })
                    .then(r => r.json())
                    .then(res => {
                        if (res.success) {
                            alert(res.message || 'Tanda tangan berhasil disimpan!');
                            base64Input.value = res.path || currentBase64;
                            saveBtn.innerHTML =
                                '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Tersimpan';
                            saveBtn.disabled = true;
                            currentBase64 = null;
                        } else {
                            throw new Error(res.message || 'Gagal menyimpan');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Gagal menyimpan tanda tangan: ' + err.message);
                        saveBtn.disabled = false;
                        saveBtn.innerHTML =
                            '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>Simpan';
                    });
            });

            // === 5. Hapus Tanda Tangan ===
            clearBtn.addEventListener('click', function() {
                if (!confirm('Yakin ingin menghapus tanda tangan ini?')) return;

                this.disabled = true;
                this.innerHTML =
                    '<svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"></circle><path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" class="opacity-75"></path></svg>Menghapus...';

                fetch(`/api/ajax-hapus-tandatangan/${asesiId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(res => {
                        if (res.success) {
                            alert(res.message || 'Tanda tangan berhasil dihapus');
                            previewImg.classList.add('hidden');
                            placeholder.classList.remove('hidden');
                            fileInput.value = '';
                            currentBase64 = null;
                            base64Input.value = '';
                            saveBtn.disabled = true;
                            saveBtn.innerHTML =
                                '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>Simpan';
                        } else throw new Error(res.message);
                    })
                    .catch(err => {
                        alert('Gagal menghapus: ' + err.message);
                    })
                    .finally(() => {
                        clearBtn.disabled = false;
                        clearBtn.innerHTML =
                            '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>Hapus';
                    });
            });
        });
    </script>
@endsection
