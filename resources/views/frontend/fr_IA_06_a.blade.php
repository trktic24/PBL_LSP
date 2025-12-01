@extends('layouts.app-sidebar')

@section('content')

<div class="p-8">

    {{-- ================= ALERT NOTIFIKASI ================= --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Berhasil!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    {{-- ================= HEADER & TOMBOL TAMBAH ================= --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">FR.IA.06.A - Daftar Pertanyaan Tertulis Esai</h1>

        {{-- Tombol Trigger Modal Tambah --}}
        <button onclick="openModal('modalTambah')" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded shadow flex items-center gap-2 transition transform hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Soal
        </button>
    </div>

    {{-- ================= BOX INFO HEADER (STATIS) ================= --}}
    <div class="bg-gray-50 p-6 rounded-md shadow-sm mb-6 border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8">
            <!-- Kolom Kiri -->
            <div class="space-y-3">
                <div class="grid grid-cols-[180px,10px,1fr] gap-x-2 text-sm items-center">
                    <span class="font-medium text-gray-700">Skema Sertifikasi</span><span class="font-medium">:</span>
                    <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" placeholder="KKNI/Okupasi/Klaster">
                </div>
                <div class="grid grid-cols-[180px,10px,1fr] gap-x-2 text-sm items-center">
                    <span class="font-medium text-gray-700">TUK</span><span class="font-medium">:</span>
                    <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="Sewaktu/Tempat Kerja/Mandiri*">
                </div>
                <div class="grid grid-cols-[180px,10px,1fr] gap-x-2 text-sm items-center">
                    <span class="font-medium text-gray-700">Nama Asesor</span><span class="font-medium">:</span>
                    <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent">
                </div>
            </div>
            <!-- Kolom Kanan -->
            <div class="space-y-3">
                <div class="grid grid-cols-[180px,10px,1fr] gap-x-2 text-sm items-center">
                    <span class="font-medium text-gray-700">Nama Asesi</span><span class="font-medium">:</span>
                    <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent">
                </div>
                <div class="grid grid-cols-[180px,10px,1fr] gap-x-2 text-sm items-center">
                    <span class="font-medium text-gray-700">Tanggal</span><span class="font-medium">:</span>
                    <input type="date" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent">
                </div>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-4 italic">*Coret yang tidak perlu</p>
    </div>

    {{-- ================= DAFTAR PERTANYAAN ================= --}}
    <div class="mb-8">
        <h3 class="font-semibold text-gray-700 mb-3 text-lg border-b pb-2">Daftar Pertanyaan & Kunci Jawaban</h3>

        <div class="space-y-4">
            @forelse ($soalItems as $index => $soal)
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition duration-200 p-5 group relative">

                {{-- Header Item Soal --}}
                <div class="flex justify-between items-start mb-3">
                    <span class="font-bold text-gray-800 text-lg bg-gray-100 px-2 py-1 rounded">No. {{ $index + 1 }}</span>

                    {{-- Tombol Aksi (Muncul saat hover) --}}
                    <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 absolute top-4 right-4 bg-white p-1 rounded shadow-sm border">
                        {{-- Tombol Edit --}}
                        <button onclick="openModal('modalEdit-{{ $soal->id_soal_ia06 }}')" class="flex items-center gap-1 text-xs font-medium bg-yellow-50 text-yellow-700 hover:bg-yellow-100 px-3 py-1.5 rounded border border-yellow-200 transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit
                        </button>

                        {{-- Tombol Hapus --}}
                        <form action="{{ route('soal.destroy', $soal->id_soal_ia06) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini? Data jawaban terkait juga akan terhapus.');">
                            @csrf @method('DELETE')
                            <button type="submit" class="flex items-center gap-1 text-xs font-medium bg-red-50 text-red-700 hover:bg-red-100 px-3 py-1.5 rounded border border-red-200 transition">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Konten Soal --}}
                <div class="ml-0 md:ml-2">
                    <div class="mb-3">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Pertanyaan</label>
                        <p class="text-gray-800 whitespace-pre-wrap bg-gray-50 p-3 rounded border border-gray-100 text-sm leading-relaxed">{{ $soal->soal_ia06 }}</p>
                    </div>

                    @if($soal->kunci_jawaban_ia06)
                    <div>
                        <label class="block text-xs font-bold text-green-600 uppercase tracking-wide mb-1">Kunci Jawaban</label>
                        <div class="text-sm text-gray-700 bg-green-50 p-3 rounded border border-green-100 italic leading-relaxed">
                            {{ $soal->kunci_jawaban_ia06 }}
                        </div>
                    </div>
                    @endif
                </div>

                {{-- ================= MODAL EDIT (PER ITEM) ================= --}}
                <div id="modalEdit-{{ $soal->id_soal_ia06 }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="closeModal('modalEdit-{{ $soal->id_soal_ia06 }}')"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-200">
                            <form action="{{ route('soal.update', $soal->id_soal_ia06) }}" method="POST">
                                @csrf @method('PUT')

                                <div class="bg-white px-6 pt-6 pb-4">
                                    <div class="flex justify-between items-center mb-5 border-b pb-2">
                                        <h3 class="text-lg leading-6 font-bold text-gray-900">Edit Pertanyaan No. {{ $index + 1 }}</h3>
                                        <button type="button" onclick="closeModal('modalEdit-{{ $soal->id_soal_ia06 }}')" class="text-gray-400 hover:text-gray-500">
                                            <span class="text-2xl">&times;</span>
                                        </button>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Pertanyaan Esai <span class="text-red-500">*</span></label>
                                        <textarea name="soal_ia06" rows="4" class="w-full p-3 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" required>{{ $soal->soal_ia06 }}</textarea>
                                    </div>

                                    <div class="mb-2">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Kunci Jawaban (Master)</label>
                                        <textarea name="kunci_jawaban_ia06" rows="3" class="w-full p-3 border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition">{{ $soal->kunci_jawaban_ia06 }}</textarea>
                                    </div>
                                </div>

                                <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-2">
                                    <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:text-sm transition">
                                        Simpan Perubahan
                                    </button>
                                    <button type="button" onclick="closeModal('modalEdit-{{ $soal->id_soal_ia06 }}')" class="w-full sm:w-auto inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:text-sm transition">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- ================= END MODAL EDIT ================= --}}

            </div>
            @empty
            <div class="p-10 text-center bg-white border border-dashed border-gray-300 rounded-lg">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada pertanyaan</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat pertanyaan baru.</p>
                <div class="mt-6">
                    <button onclick="openModal('modalTambah')" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Tambah Soal Baru
                    </button>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ================= TABEL PENYUSUN & VALIDATOR ================= --}}
    <div class="mb-6">
        <h3 class="font-semibold text-gray-700 mb-3 text-lg">Penyusun dan Validator</h3>
        <div class="overflow-x-auto border border-gray-300 rounded-md shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 border-b border-gray-200 text-gray-700">
                    <tr>
                        <th class="p-3 text-left font-bold w-1/6">Status</th>
                        <th class="p-3 text-left font-bold w-[5%]">No</th>
                        <th class="p-3 text-left font-bold w-1/4">Nama</th>
                        <th class="p-3 text-left font-bold w-1/4">Nomor MET</th>
                        <th class="p-3 text-left font-bold">Tanda Tangan dan Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">

                    {{-- BARIS PENYUSUN (STATIS) --}}
                    <tr>
                        <td class="p-3 font-bold align-top border-r bg-gray-50 text-gray-600" rowspan="2">PENYUSUN</td>
                        <td class="p-3 align-top pt-4 text-center">1</td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded focus:border-blue-500 outline-none"></td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded focus:border-blue-500 outline-none"></td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded focus:border-blue-500 outline-none"></td>
                    </tr>
                    <tr>
                        <td class="p-3 align-top pt-4 text-center">2</td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded focus:border-blue-500 outline-none"></td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded focus:border-blue-500 outline-none"></td>
                        <td class="p-3"><input type="text" class="w-full p-2 border border-gray-300 rounded focus:border-blue-500 outline-none"></td>
                    </tr>

                    {{-- BARIS VALIDATOR (DINAMIS) --}}
                    @forelse($validators as $index => $validator)
                    <tr>
                        {{-- Rowspan 'VALIDATOR' hanya di baris pertama --}}
                        @if($index === 0)
                            <td class="p-3 font-bold align-top border-r bg-gray-50 text-gray-600" rowspan="{{ $validators->count() }}">
                                VALIDATOR
                            </td>
                        @endif

                        <td class="p-3 align-top pt-4 text-center">{{ $index + 1 }}</td>

                        <td class="p-3">
                            <input type="text"
                                   class="w-full p-2 border border-gray-200 bg-gray-100 text-gray-600 rounded cursor-not-allowed"
                                   value="{{ $validator->nama_validator }}"
                                   readonly>
                        </td>

                        <td class="p-3">
                            <input type="text"
                                   class="w-full p-2 border border-gray-200 bg-gray-100 text-gray-600 rounded cursor-not-allowed"
                                   value="{{ $validator->no_MET_validator }}"
                                   readonly>
                        </td>

                        <td class="p-3">
                            <input type="text" class="w-full p-2 border border-gray-300 rounded focus:border-blue-500 outline-none" placeholder="Tanda Tangan...">
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="p-3 font-bold align-top border-r bg-gray-50 text-gray-600">VALIDATOR</td>
                        <td class="p-3 text-center">1</td>
                        <td colspan="3" class="p-3 text-red-500 italic text-center bg-red-50">
                            Data Validator kosong. Silakan jalankan seeder.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ================= MODAL TAMBAH SOAL (GLOBAL) ================= --}}
<div id="modalTambah" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" onclick="closeModal('modalTambah')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-200">
            <form action="{{ route('soal.store') }}" method="POST">
                @csrf
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="flex justify-between items-center mb-5 border-b pb-2">
                        <h3 class="text-lg font-bold text-gray-900">Tambah Pertanyaan Baru</h3>
                        <button type="button" onclick="closeModal('modalTambah')" class="text-gray-400 hover:text-gray-500">
                            <span class="text-2xl">&times;</span>
                        </button>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pertanyaan Esai <span class="text-red-500">*</span></label>
                        <textarea name="soal_ia06" rows="4" class="w-full p-3 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" required placeholder="Tulis pertanyaan di sini..."></textarea>
                    </div>

                    <div class="mb-2">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kunci Jawaban (Opsional)</label>
                        <textarea name="kunci_jawaban_ia06" rows="3" class="w-full p-3 border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" placeholder="Jawaban benar..."></textarea>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-2">
                    <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:text-sm transition">
                        Simpan Soal
                    </button>
                    <button type="button" onclick="closeModal('modalTambah')" class="w-full sm:w-auto inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:text-sm transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT SEDERHANA UNTUK MODAL --}}
<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
</script>

@endsection