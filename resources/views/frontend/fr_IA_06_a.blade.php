@extends('layouts.app-sidebar')

@section('content')

<div class="p-8">

    {{-- Pesan Sukses --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Berhasil!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Header & Tombol Tambah --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">FR.IA.06.A - Daftar Pertanyaan Tertulis Esai</h1>

        {{-- Tombol Trigger Modal Tambah --}}
        <button onclick="openModal('modalTambah')" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded shadow flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Soal
        </button>
    </div>

    <!-- Box Info Atas (Header Dokumen) - Sama seperti sebelumnya -->
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
    </div>

    <!-- Daftar Pertanyaan -->
    <div class="mb-6">
        <h3 class="font-semibold text-gray-700 mb-3">Jawablah semua pertanyaan di bawah ini:</h3>

        <div class="overflow-x-auto border border-gray-300 rounded-md shadow-sm bg-white divide-y divide-gray-200">

            @forelse ($soalItems as $index => $soal)
            <div class="p-4 hover:bg-gray-50 transition duration-150 group">
                <div class="flex flex-col gap-2">

                    <div class="flex justify-between items-start">
                        <span class="font-bold text-gray-700 mt-1">No. {{ $index + 1 }}</span>

                        {{-- Tombol Aksi (Edit & Hapus) --}}
                        <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            {{-- Tombol Edit Trigger Modal --}}
                            <button onclick="openModal('modalEdit-{{ $soal->id_soal_ia06 }}')" class="text-xs bg-yellow-100 text-yellow-700 hover:bg-yellow-200 px-3 py-1 rounded border border-yellow-300">
                                Edit
                            </button>

                            <form action="{{ route('soal.destroy', $soal->id_soal_ia06) }}" method="POST" onsubmit="return confirm('Hapus soal ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1 rounded border border-red-300">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Tampilan Soal (Readonly) --}}
                    <div class="pl-0 md:pl-12">
                        <p class="text-gray-800 whitespace-pre-wrap">{{ $soal->soal_ia06 }}</p>
                        @if($soal->kunci_jawaban_ia06)
                            <div class="mt-2 text-sm text-green-700 bg-green-50 p-2 rounded border border-green-100 inline-block">
                                <strong>Kunci:</strong> {{Str::limit($soal->kunci_jawaban_ia06, 100) }}
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            {{-- ================= MODAL EDIT (Per Item) ================= --}}
            <div id="modalEdit-{{ $soal->id_soal_ia06 }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    {{-- Overlay Background --}}
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal('modalEdit-{{ $soal->id_soal_ia06 }}')"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    {{-- Modal Content --}}
                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <form action="{{ route('soal.update', $soal->id_soal_ia06) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">Edit Pertanyaan No. {{ $index + 1 }}</h3>

                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Pertanyaan Esai:</label>
                                    <textarea name="soal_ia06" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>{{ $soal->soal_ia06 }}</textarea>
                                </div>

                                <div class="mb-2">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Kunci Jawaban (Master):</label>
                                    <textarea name="kunci_jawaban_ia06" rows="2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $soal->kunci_jawaban_ia06 }}</textarea>
                                </div>
                            </div>

                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                    Simpan Perubahan
                                </button>
                                <button type="button" onclick="closeModal('modalEdit-{{ $soal->id_soal_ia06 }}')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- ================= END MODAL EDIT ================= --}}

            @empty
            <div class="p-8 text-center text-gray-500">
                <p>Belum ada pertanyaan. Silakan tambah soal baru.</p>
            </div>
            @endforelse

        </div>
    </div>

</div>

{{-- ================= MODAL TAMBAH SOAL ================= --}}
<div id="modalTambah" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal('modalTambah')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('soal.store') }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Pertanyaan Baru</h3>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pertanyaan:</label>
                        <textarea name="soal_ia06" rows="3" class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" required placeholder="Tulis pertanyaan di sini..."></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kunci Jawaban (Opsional):</label>
                        <textarea name="kunci_jawaban_ia06" rows="2" class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" placeholder="Jawaban benar..."></textarea>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                    <button type="button" onclick="closeModal('modalTambah')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
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