@extends('layouts.app-sidebar')
@section('content')
<div class="min-h-screen bg-gray-50 p-8">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Bank Soal Esai (IA.06)</h1>
            <p class="text-gray-500 text-sm">Kelola daftar pertanyaan esai berdasarkan skema.</p>
        </div>

        {{-- Tombol Tambah (Hanya muncul jika skema dipilih) --}}
        @if($selectedSkema && Auth::user()->role->nama_role == 'superadmin')
            <button onclick="openModal('add')" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-semibold shadow-sm flex items-center gap-2 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Soal Baru
            </button>
        @endif
    </div>

    {{-- ALERT SUKSES --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r shadow-sm flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700 font-bold">&times;</button>
        </div>
    @endif

    {{-- FILTER SKEMA --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-8">
        <form action="{{ route('admin.ia06.index') }}" method="GET">
            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Skema Sertifikasi:</label>
            <div class="flex gap-4">
                <div class="relative w-full md:w-1/2">
                    <select name="skema_id" class="w-full appearance-none border border-gray-300 rounded-lg py-3 px-4 pr-8 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                        <option value="">-- Pilih Skema --</option>
                        @foreach($skemas as $skema)
                            <option value="{{ $skema->id_skema }}" {{ $selectedSkema == $skema->id_skema ? 'selected' : '' }}>
                                {{ $skema->nama_skema }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- TABEL DATA --}}
    @if($selectedSkema)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-16">No</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-2/5">Pertanyaan</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-2/5">Kunci Jawaban</th>
                            @if(Auth::user()->role->nama_role == 'superadmin')
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($soals as $index => $soal)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 leading-relaxed">{{ $soal->soal_ia06 }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 italic leading-relaxed bg-gray-50/50">
                                {{ \Illuminate\Support\Str::limit($soal->kunci_jawaban_ia06, 80) }}
                            </td>
                            @if(Auth::user()->role->nama_role == 'superadmin')
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-3">
                                    {{-- Edit Button --}}
                                    <button onclick="editSoal({{ $soal }})" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded hover:bg-indigo-100 transition">
                                        Edit
                                    </button>

                                    {{-- Delete Button --}}
                                    <form action="{{ route('admin.ia06.destroy', $soal->id_soal_ia06) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded hover:bg-red-100 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p class="text-lg font-medium">Belum ada soal untuk skema ini.</p>
                                    <p class="text-sm">Silakan klik tombol "Tambah Soal Baru" di atas.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm leading-5 font-medium text-blue-800">Perhatian</h3>
                    <div class="mt-2 text-sm leading-5 text-blue-700">
                        <p>Silakan pilih <b>Skema Sertifikasi</b> pada dropdown di atas untuk mulai mengelola Bank Soal.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- MODAL FORM (Create & Edit) --}}
<div id="modalBackdrop" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-40 transition-opacity"></div>
<div id="modalForm" class="fixed inset-0 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl transform transition-all scale-100">

        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h3 class="text-lg font-bold text-gray-900" id="modalTitle">Tambah Soal Baru</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="soalForm" method="POST" class="p-6">
            @csrf
            {{-- Method Spoofing untuk PUT (akan diisi JS jika Edit) --}}
            <input type="hidden" name="_method" id="methodField" value="POST">
            <input type="hidden" name="id_skema" value="{{ $selectedSkema }}">

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Pertanyaan Esai</label>
                    <textarea name="soal_ia06" id="inputSoal" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3" placeholder="Tuliskan pertanyaan lengkap..." required></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Kunci Jawaban (Panduan Asesor)</label>
                    <textarea name="kunci_jawaban_ia06" id="inputKunci" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3 bg-blue-50" placeholder="Tuliskan jawaban yang diharapkan..." required></textarea>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-md transition">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('modalForm');
    const backdrop = document.getElementById('modalBackdrop');
    const form = document.getElementById('soalForm');
    const title = document.getElementById('modalTitle');
    const inputSoal = document.getElementById('inputSoal');
    const inputKunci = document.getElementById('inputKunci');
    const methodField = document.getElementById('methodField');

    function openModal(mode) {
        modal.classList.remove('hidden');
        backdrop.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent scroll

        if (mode === 'add') {
            title.innerText = "Tambah Soal Baru";
            form.action = "{{ route('admin.ia06.store') }}";
            methodField.value = "POST";
            inputSoal.value = "";
            inputKunci.value = "";
        }
    }

    function editSoal(data) {
        openModal('edit'); // Buka modal dulu
        title.innerText = "Edit Soal";

        // Ganti URL Action form menjadi Update
        let url = "{{ route('admin.ia06.update', ':id') }}";
        form.action = url.replace(':id', data.id_soal_ia06);

        // Set Method jadi PUT
        methodField.value = "PUT";

        // Isi data lama
        inputSoal.value = data.soal_ia06;
        inputKunci.value = data.kunci_jawaban_ia06;
    }

    function closeModal() {
        modal.classList.add('hidden');
        backdrop.classList.add('hidden');
        document.body.style.overflow = 'auto'; // Enable scroll
    }

    // Close modal on backdrop click
    backdrop.addEventListener('click', closeModal);
</script>
@endsection