@extends('layouts.app-sidebar')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- SUCCESS/ERROR MESSAGE --}}
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg shadow-sm">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg shadow-sm">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    {{-- HEADER --}}
    <div class="mb-8 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">FR.IA.03 - Pertanyaan Mendukung Observasi</h1>
                <p class="text-sm text-gray-500 mt-1">Mode Admin - Kelola Pertanyaan</p>
            </div>
            
            {{-- ✅ TOMBOL KANAN: LIHAT JAWABAN & KEMBALI --}}
            <div class="mt-4 md:mt-0 flex gap-2">
                {{-- Tombol Lihat Jawaban Asesor --}}
                @if($pertanyaanIA03->isNotEmpty())
                <a href="{{ route('admin.ia03.jawaban', $sertifikasi->id_data_sertifikasi_asesi) }}" 
                   target="_blank"
                   class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Lihat Jawaban Asesor
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                </a>
                @endif
            </div>
        </div>

        {{-- Informasi Asesmen --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div>
                <div class="grid grid-cols-[140px_10px_1fr] mb-2">
                    <span class="font-semibold text-gray-600">Skema Sertifikasi</span>
                    <span>:</span>
                    <span class="font-bold text-gray-900">{{ $skema->nama_skema ?? '-' }}</span>
                </div>
                <div class="grid grid-cols-[140px_10px_1fr] mb-2">
                    <span class="font-semibold text-gray-600">No. Skema</span>
                    <span>:</span>
                    <span class="text-gray-900">{{ $skema->nomor_skema ?? '-' }}</span>
                </div>
            </div>
            <div>
                <div class="grid grid-cols-[140px_10px_1fr] mb-2">
                    <span class="font-semibold text-gray-600">Nama Asesor</span>
                    <span>:</span>
                    <span class="font-bold text-gray-900">{{ $asesor->nama_lengkap ?? '-' }}</span>
                </div>
                <div class="grid grid-cols-[140px_10px_1fr] mb-2">
                    <span class="font-semibold text-gray-600">Nama Asesi</span>
                    <span>:</span>
                    <span class="font-bold text-gray-900">{{ $asesi->nama_lengkap ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.ia03.store', $sertifikasi->id_data_sertifikasi_asesi) }}" id="formPertanyaan">
        @csrf

        @php
            $adaPertanyaan = $pertanyaanIA03->isNotEmpty();
        @endphp

        {{-- LOOP KELOMPOK PEKERJAAN --}}
        @foreach($kelompokPekerjaan as $kelompokIndex => $kelompok)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-8">
                {{-- Header Kelompok --}}
                <div class="p-6 bg-gradient-to-r from-blue-50 to-blue-100 border-b border-blue-200">
                    <h2 class="text-2xl font-bold text-blue-800 mb-1">
                        Kelompok Pekerjaan {{ $kelompokIndex + 1 }}
                    </h2>
                    <p class="text-blue-600 text-sm font-medium">
                        {{ $kelompok->nama_kelompok_pekerjaan ?? '-' }}
                    </p>
                </div>

                {{-- Daftar Unit Kompetensi --}}
                <div class="p-6 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Daftar Unit Kompetensi</h3>
                    <div class="overflow-hidden rounded-lg border border-gray-300">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr class="bg-gray-100 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    <th class="p-3 border-b border-gray-300 w-16 text-center">No</th>
                                    <th class="p-3 border-b border-gray-300">Kode Unit</th>
                                    <th class="p-3 border-b border-gray-300">Judul Unit</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($kelompok->unitKompetensi ?? [] as $i => $unit)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="p-3 text-center font-medium text-gray-700">{{ $i + 1 }}</td>
                                        <td class="p-3 font-medium text-gray-700">{{ $unit->kode_unit ?? '-' }}</td>
                                        <td class="p-3 text-gray-700">{{ $unit->judul_unit ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="p-6 text-center text-gray-500">Tidak ada unit kompetensi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pertanyaan --}}
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Kelola Pertanyaan</h3>
                    </div>
                    
                    <div id="pertanyaan-container-{{ $kelompok->id_kelompok_pekerjaan }}">
                        @php
                            // Ambil pertanyaan untuk kelompok ini SAJA
                            $pertanyaanKelompok = $pertanyaanIA03->where('id_kelompok_pekerjaan', $kelompok->id_kelompok_pekerjaan)->values();
                        @endphp

                        @forelse($pertanyaanKelompok as $pIndex => $pertanyaan)
                            <div class="mb-4 p-4 bg-gray-50 border border-gray-200 rounded-lg pertanyaan-item">
                                <div class="flex justify-between items-start mb-2">
                                    <label class="block text-sm font-bold text-gray-700">
                                        Pertanyaan {{ $pIndex + 1 }}:
                                    </label>
                                    {{-- Tombol Hapus (Disabled saat ada pertanyaan) --}}
                                    @if($adaPertanyaan)
                                    <button type="button" 
                                        class="btn-hapus px-3 py-1 bg-gray-300 text-gray-500 text-xs rounded cursor-not-allowed transition"
                                        disabled>
                                        Hapus
                                    </button>
                                    @else
                                    <button type="button" 
                                        onclick="this.closest('.pertanyaan-item').remove(); enableSaveButton();"
                                        class="btn-hapus px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 transition">
                                        Hapus
                                    </button>
                                    @endif
                                </div>
                                {{-- PENTING: Hidden input untuk menyimpan ID pertanyaan yang sudah ada --}}
                                <input type="hidden" name="id_ia03[]" value="{{ $pertanyaan->id_IA03 }}">
                                <input type="hidden" name="id_kelompok[]" value="{{ $kelompok->id_kelompok_pekerjaan }}">
                                
                                <textarea name="pertanyaan[]" rows="3" required
                                    class="pertanyaan-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-100"
                                    readonly>{{ $pertanyaan->pertanyaan ?? '' }}</textarea>
                            </div>
                        @empty
                            {{-- Form Kosong untuk Input Pertanyaan Baru --}}
                            <div class="mb-4 p-4 bg-gray-50 border border-gray-200 rounded-lg pertanyaan-item">
                                <div class="flex justify-between items-start mb-2">
                                    <label class="block text-sm font-bold text-gray-700">
                                        Pertanyaan 1:
                                    </label>
                                    <button type="button" 
                                        onclick="this.closest('.pertanyaan-item').remove()"
                                        class="btn-hapus px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 transition">
                                        Hapus
                                    </button>
                                </div>
                                <input type="hidden" name="id_ia03[]" value="">
                                <input type="hidden" name="id_kelompok[]" value="{{ $kelompok->id_kelompok_pekerjaan }}">
                                
                                <textarea name="pertanyaan[]" rows="3" required
                                    class="pertanyaan-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Tuliskan pertanyaan untuk kelompok ini..."></textarea>
                            </div>

                            <div class="mb-4 p-4 bg-gray-50 border border-gray-200 rounded-lg pertanyaan-item">
                                <div class="flex justify-between items-start mb-2">
                                    <label class="block text-sm font-bold text-gray-700">
                                        Pertanyaan 2:
                                    </label>
                                    <button type="button" 
                                        onclick="this.closest('.pertanyaan-item').remove()"
                                        class="btn-hapus px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 transition">
                                        Hapus
                                    </button>
                                </div>
                                <input type="hidden" name="id_ia03[]" value="">
                                <input type="hidden" name="id_kelompok[]" value="{{ $kelompok->id_kelompok_pekerjaan }}">
                                
                                <textarea name="pertanyaan[]" rows="3" required
                                    class="pertanyaan-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Tuliskan pertanyaan untuk kelompok ini..."></textarea>
                            </div>
                        @endforelse
                    </div>

                    {{-- Tombol Tambah Pertanyaan (Disabled saat ada pertanyaan) --}}
                    @if(!$adaPertanyaan)
                    <button type="button" 
                        id="btnTambah-{{ $kelompok->id_kelompok_pekerjaan }}"
                        onclick="tambahPertanyaan({{ $kelompok->id_kelompok_pekerjaan }})"
                        class="btn-tambah px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-lg hover:bg-green-600 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Pertanyaan
                    </button>
                    @else
                    <button type="button" 
                        id="btnTambah-{{ $kelompok->id_kelompok_pekerjaan }}"
                        disabled
                        class="btn-tambah px-4 py-2 bg-gray-300 text-gray-500 text-sm font-semibold rounded-lg cursor-not-allowed flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Pertanyaan
                    </button>
                    @endif
                </div>
            </div>
        @endforeach

        {{-- Tombol Aksi --}}
        <div class="flex justify-end gap-4 pb-8">
            @if($adaPertanyaan)
                {{-- Tombol Edit (Muncul jika sudah ada pertanyaan dan dalam mode readonly) --}}
                <button type="button" id="btnEdit"
                    onclick="toggleEditMode()"
                    class="px-6 py-3 bg-yellow-600 text-white font-semibold rounded-lg shadow-md hover:bg-yellow-700 transition">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Pertanyaan
                </button>
            @endif

            {{-- Tombol Simpan --}}
            <button type="submit" id="btnSimpan"
                class="px-8 py-3 bg-blue-600 text-white font-bold rounded-lg shadow-lg hover:bg-blue-700 transition flex items-center gap-2 {{ $adaPertanyaan ? 'opacity-50 cursor-not-allowed' : '' }}"
                {{ $adaPertanyaan ? 'disabled' : '' }}>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Simpan Pertanyaan
            </button>
        </div>
    </form>

</div>

<script>
let editMode = false;

function toggleEditMode() {
    editMode = !editMode;
    
    const textareas = document.querySelectorAll('.pertanyaan-input');
    const btnSimpan = document.getElementById('btnSimpan');
    const btnEdit = document.getElementById('btnEdit');
    const btnHapusList = document.querySelectorAll('.btn-hapus');
    const btnTambahList = document.querySelectorAll('.btn-tambah');
    
    if (editMode) {
        // Mode Edit: Aktifkan semua
        textareas.forEach(textarea => {
            textarea.readOnly = false;
            textarea.classList.remove('bg-gray-100');
            textarea.classList.add('bg-white');
        });
        
        btnSimpan.disabled = false;
        btnSimpan.classList.remove('opacity-50', 'cursor-not-allowed');
        
        // Aktifkan tombol hapus
        btnHapusList.forEach(btn => {
            btn.disabled = false;
            btn.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
            btn.classList.add('bg-red-500', 'text-white', 'hover:bg-red-600');
            btn.onclick = function() {
                this.closest('.pertanyaan-item').remove();
                enableSaveButton();
            };
        });
        
        // Aktifkan tombol tambah
        btnTambahList.forEach(btn => {
            btn.disabled = false;
            btn.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
            btn.classList.add('bg-green-500', 'text-white', 'hover:bg-green-600');
        });
        
        btnEdit.textContent = 'Batal Edit';
        btnEdit.classList.remove('bg-yellow-600', 'hover:bg-yellow-700');
        btnEdit.classList.add('bg-gray-600', 'hover:bg-gray-700');
        
    } else {
        // Mode Readonly: Nonaktifkan semua
        textareas.forEach(textarea => {
            textarea.readOnly = true;
            textarea.classList.add('bg-gray-100');
            textarea.classList.remove('bg-white');
        });
        
        btnSimpan.disabled = true;
        btnSimpan.classList.add('opacity-50', 'cursor-not-allowed');
        
        // Nonaktifkan tombol hapus
        btnHapusList.forEach(btn => {
            btn.disabled = true;
            btn.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
            btn.classList.remove('bg-red-500', 'text-white', 'hover:bg-red-600');
            btn.onclick = null;
        });
        
        // Nonaktifkan tombol tambah
        btnTambahList.forEach(btn => {
            btn.disabled = true;
            btn.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
            btn.classList.remove('bg-green-500', 'text-white', 'hover:bg-green-600');
        });
        
        btnEdit.innerHTML = `
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit Pertanyaan
        `;
        btnEdit.classList.add('bg-yellow-600', 'hover:bg-yellow-700');
        btnEdit.classList.remove('bg-gray-600', 'hover:bg-gray-700');
    }
}

function enableSaveButton() {
    const btnSimpan = document.getElementById('btnSimpan');
    btnSimpan.disabled = false;
    btnSimpan.classList.remove('opacity-50', 'cursor-not-allowed');
}

function tambahPertanyaan(idKelompok) {
    const container = document.getElementById('pertanyaan-container-' + idKelompok);
    
    // Hitung jumlah pertanyaan yang ada saat ini (real-time count)
    const jumlahPertanyaanSekarang = container.querySelectorAll('.pertanyaan-item').length;
    const nomorPertanyaanBaru = jumlahPertanyaanSekarang + 1;
    
    const newItem = document.createElement('div');
    newItem.className = 'mb-4 p-4 bg-gray-50 border border-gray-200 rounded-lg pertanyaan-item';
    newItem.innerHTML = `
        <div class="flex justify-between items-start mb-2">
            <label class="block text-sm font-bold text-gray-700">
                Pertanyaan ${nomorPertanyaanBaru}:
            </label>
            <button type="button" 
                onclick="this.closest('.pertanyaan-item').remove(); enableSaveButton(); updateNomorPertanyaan(${idKelompok});"
                class="btn-hapus px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 transition">
                Hapus
            </button>
        </div>
        <input type="hidden" name="id_ia03[]" value="">
        <input type="hidden" name="id_kelompok[]" value="${idKelompok}">
        <textarea name="pertanyaan[]" rows="3" required
            class="pertanyaan-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Tuliskan pertanyaan..."></textarea>
    `;
    container.appendChild(newItem);
    
    // Enable tombol simpan karena ada perubahan
    enableSaveButton();
}

function updateNomorPertanyaan(idKelompok) {
    const container = document.getElementById('pertanyaan-container-' + idKelompok);
    const items = container.querySelectorAll('.pertanyaan-item');
    
    items.forEach((item, index) => {
        const label = item.querySelector('label');
        if (label) {
            label.textContent = `Pertanyaan ${index + 1}:`;
        }
    });
}
</script>

@endsection