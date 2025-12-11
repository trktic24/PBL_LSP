<x-app-layout>
    {{-- Tambahkan CDN SweetAlert2 di head atau di sini jika belum ada di layout utama --}}
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    @endpush

    <div class="flex h-screen overflow-hidden">

        <x-sidebar2 :idAsesi="$asesi->id_asesi ?? null" :sertifikasi="$sertifikasi" />

        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-4xl mx-auto">

                <h1 class="text-4xl font-bold text-gray-900 mb-10">Umpan Balik dan Catatan Asesmen</h1>

                {{-- Tambahkan ID pada form agar mudah dipilih oleh JavaScript --}}
                <form id="form-umpan-balik" action="{{ route('asesi.ak03.store', $sertifikasi->id_data_sertifikasi_asesi) }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-y-4 text-sm mb-8">
                        {{-- Bagian Info TUK dll (Sama seperti sebelumnya) --}}
                        <div class="col-span-1 font-medium text-gray-800 pt-2">TUK</div>
                        <div class="col-span-3 flex flex-wrap gap-x-6 gap-y-2 items-center">
                            @php
                                $tukRaw = $sertifikasi->jadwal->jenisTuk->jenis_tuk ?? $sertifikasi->jadwal->jenisTuk->nama_tuk ?? '';
                                $tukDb = strtolower($tukRaw);
                            @endphp

                            <label class="flex items-center text-gray-700 cursor-not-allowed opacity-75">
                                <input type="radio" name="tuk_display_only" class="w-4 h-4 text-blue-600 border-gray-300 mr-2 cursor-not-allowed bg-gray-100" disabled {{ $tukDb == 'sewaktu' ? 'checked' : '' }}>
                                Sewaktu
                            </label>

                            <label class="flex items-center text-gray-700 cursor-not-allowed opacity-75">
                                <input type="radio" name="tuk_display_only" class="w-4 h-4 text-blue-600 border-gray-300 mr-2 cursor-not-allowed bg-gray-100" disabled {{ str_contains($tukDb, 'tempat') ? 'checked' : '' }}>
                                Tempat Kerja
                            </label>
                        </div>

                        <div class="col-span-1 font-medium text-gray-800">Nama Asesor</div>
                        <div class="col-span-3 text-gray-800 font-semibold">
                            : {{ $sertifikasi->jadwal->asesor->nama_lengkap ?? 'Belum Ditentukan' }}
                        </div>

                        <div class="col-span-1 font-medium text-gray-800">Nama Asesi</div>
                        <div class="col-span-3 text-gray-800 font-semibold">
                            : {{ $asesi->nama_lengkap ?? Auth::user()->name }}
                        </div>
                    </div>

                    {{-- Tabel Input --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-900 text-white">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-sm font-bold uppercase tracking-wider">Komponen</th>
                                    <th scope="col" class="px-6 py-3 text-center text-sm font-bold uppercase tracking-wider w-20">Ya</th>
                                    <th scope="col" class="px-6 py-3 text-center text-sm font-bold uppercase tracking-wider w-20">Tidak</th>
                                    <th scope="col" class="px-6 py-3 text-left text-sm font-bold uppercase tracking-wider w-48">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($komponen as $index => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-start">
                                            <span class="font-semibold text-gray-800 mr-3">{{ $loop->iteration }}</span>
                                            <p class="text-sm text-gray-700">{{ $item->komponen }}</p>
                                        </div>
                                    </td>
                                    {{-- Tambahkan class 'input-radio' untuk validasi JS nanti jika perlu --}}
                                    <td class="px-6 py-4 align-middle text-center">
                                        <input type="radio" name="jawaban[{{ $item->id_poin_ak03 }}][hasil]" value="ya" class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500 input-radio" required>
                                    </td>
                                    <td class="px-6 py-4 align-middle text-center">
                                        <input type="radio" name="jawaban[{{ $item->id_poin_ak03 }}][hasil]" value="tidak" class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500 input-radio" required>
                                    </td>
                                    <td class="px-6 py-4 align-middle">
                                        <input type="text" name="jawaban[{{ $item->id_poin_ak03 }}][catatan]" placeholder="Tambahkan Pesan..." class="w-full text-xs border-b border-gray-300 focus:border-blue-500 focus:outline-none py-1 px-2">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        <label for="catatan_tambahan" class="text-sm font-medium text-gray-700">Catatan/komentar lainnya (apabila ada):</label>
                        <textarea id="catatan_tambahan" name="catatan_tambahan" rows="4" class="mt-2 w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm p-2 border"></textarea>
                    </div>

                    <div class="flex justify-end items-center mt-12">
                        
                        {{-- Tambahkan ID pada button submit --}}
                        <button type="button" id="btn-submit" class="px-8 py-3 bg-blue-500 text-white font-semibold rounded-full hover:bg-blue-600 shadow-md transition-colors flex items-center justify-center">
                            <span>Kirim Umpan Balik</span>
                        </button>
                    </div>

                </form>

            </div>
        </main>
    </div>

    {{-- Script untuk SweetAlert dan handling submit --}}
    {{-- Jika kamu menggunakan stack 'scripts' di layout --}}
    {{-- @push('scripts') --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const btnSubmit = document.getElementById('btn-submit');
                const form = document.getElementById('form-umpan-balik');

                btnSubmit.addEventListener('click', function (e) {
                    e.preventDefault(); // Mencegah submit langsung

                    // Cek validasi HTML5 standar (required fields)
                    if (!form.checkValidity()) {
                        form.reportValidity(); // Menampilkan pesan error browser jika ada field yang kosong
                        return;
                    }

                    Swal.fire({
                        title: 'Konfirmasi Pengiriman',
                        text: "Apakah Anda yakin seluruh data sudah benar? Formulir yang telah dikirim tidak dapat diubah kembali.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3B82F6', // Blue-500
                        cancelButtonColor: '#6B7280', // Gray-500
                        confirmButtonText: 'Ya, Kirim Data',
                        cancelButtonText: 'Batal, Periksa Lagi',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Tampilkan loading state
                            btnSubmit.disabled = true;
                            btnSubmit.innerHTML = `
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Mengirim...
                            `;
                            
                            // Submit form secara manual
                            form.submit();
                        }
                    });
                });
            });
        </script>
    {{-- @endpush --}}

</x-app-layout>