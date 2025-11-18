@extends('layouts.app-sidebar') {{-- Menggunakan layout sidebar yang sudah responsive --}}

@section('content')
    {{-- Container Utama: Memastikan konten berada di tengah dan memiliki lebar maksimum --}}
    <div class="p-4 sm:p-6 md:p-8 max-w-5xl mx-auto">

        {{-- Header Form Utama --}}
        <x-header_form.header_form title="FR.AK.04. BANDING ASESMEN" /><br>

        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 sm:p-6 mb-8 shadow-sm">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Informasi Banding</h2>

            {{-- Grid Responsif: Menggunakan grid 1 kolom di HP, 2 kolom di tablet/desktop --}}
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 text-sm">

                {{-- TUK --}}
                <div class="col-span-1 md:col-span-2 flex flex-col sm:flex-row sm:items-center gap-y-2">
                    <dt class="min-w-[120px] font-medium text-gray-500">TUK</dt>
                    <dd class="flex flex-wrap gap-x-6 gap-y-2 items-center text-gray-900 font-medium">
                        <label class="flex items-center">
                            <input type="checkbox" value="Sewaktu" disabled
                                class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2 opacity-100 cursor-default">
                            Sewaktu
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" value="Tempat Kerja" disabled
                                class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2 opacity-100 cursor-default">
                            Tempat Kerja
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" value="Mandiri" disabled
                                class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2 opacity-100 cursor-default">
                            Mandiri
                        </label>
                    </dd>
                </div>

                {{-- Nama Asesor & Asesi dipisah di 2 kolom agar fleksibel --}}
                <div class="col-span-1 md:col-span-2 flex items-center">
                    <dt class="min-w-[120px] font-medium text-gray-500">Nama Asesor</dt>
                    <dd class="text-gray-900 font-semibold ml-2">: <span id="nama_asesor">Tatang Sidartang</span>
                    </dd>
                </div>

                {{-- Baris 2: Nama Asesi. Gunakan col-span-2 untuk memastikan dia mengisi seluruh lebar (1 baris). --}}
                <div class="col-span-1 md:col-span-2 flex items-center">
                    <dt class="min-w-[120px] font-medium text-gray-500">Nama Asesi</dt>
                    <dd class="text-gray-900 font-semibold ml-2">: <span id="nama_asesi">Peserta Uji </span></dd>
                </div>
            </dl>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-8">
            <div class="p-4 bg-blue-50 border-b border-blue-100">
                <p class="text-sm text-gray-800 font-medium">
                    Jawablah dengan <span class="font-bold">Ya</span> atau <span class="font-bold">Tidak</span>
                    pertanyaan-pertanyaan berikut ini:
                </p>
            </div>

            {{-- Overflow-x-auto untuk membuat tabel bisa di-scroll horizontal di layar sangat kecil --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-900 text-white">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left font-bold uppercase tracking-wider min-w-[300px]">
                                Komponen</th>
                            <th scope="col" class="px-6 py-3 text-center font-bold uppercase tracking-wider w-24">Ya</th>
                            <th scope="col" class="px-6 py-3 text-center font-bold uppercase tracking-wider w-24">Tidak
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-gray-700">
                        @for ($i = 1; $i <= 3; $i++)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    {{ ['Apakah Proses Banding telah dijelaskan kepada Anda?', 'Apakah Anda telah mendiskusikan Banding dengan Asesor?', 'Apakah Anda mau melibatkan "orang lain" membantu Anda dalam Proses Banding?'][$i - 1] }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="radio" name="banding_{{ $i }}" value="ya"
                                        class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500 cursor-pointer">
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="radio" name="banding_{{ $i }}" value="tidak"
                                        class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500 cursor-pointer">
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-6 border border-gray-200 rounded-xl shadow-sm mb-8">

            {{-- Informasi Skema (Diberi Highlight) --}}
            <div class="bg-white p-6 border border-gray-200 rounded-xl shadow-sm mb-8">
                <p class="text-sm text-gray-700 mb-6 leading-relaxed">
                    Banding ini diajukan atas Keputusan Asesmen yang dibuat terhadap Skema Sertifikasi Okupasi Nasional
                    berikut:
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Skema Sertifikasi</label>
                        <input type="text" value="Junior Web Developer" disabled
                            class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 text-gray-700 text-sm focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Skema Sertifikasi</label>
                        <input type="text" value="SKM/001/JWD/2024" disabled
                            class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 text-gray-700 text-sm focus:outline-none">
                    </div>
                </div>

                <label for="alasan_banding" class="block text-sm font-bold text-gray-900 mb-2">
                    Banding ini diajukan atas alasan sebagai berikut:
                </label>
                <textarea id="alasan_banding" rows="5"
                    class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm p-4"
                    placeholder="Berikan keterangan atau alasan pengajuan banding disini..."></textarea>

                <div class="mb-8">
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                        <p class="text-sm text-red-700">
                            <strong>Catatan:</strong> Anda mempunyai hak mengajukan banding jika Anda menilai proses asesmen
                            tidak sesuai SOP dan tidak memenuhi Prinsip Asesmen.
                        </p>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan Pemohon Banding</label>

                    {{-- Container Tanda Tangan --}}
                    <div class="w-full h-56 bg-white border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center overflow-hidden relative group hover:border-gray-400 transition-colors cursor-pointer"
                        id="ttd_container">
                        <div class="text-center">
                            <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p id="ttd_placeholder" class="mt-2 text-sm text-gray-500">Klik area ini untuk menandatangani
                            </p>
                        </div>
                    </div>

                    {{-- Tombol Footer --}}
                    <div class="flex justify-between items-center mt-12 border-t border-gray-200 pt-6">
                        <a href="#"
                            class="px-8 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition shadow-sm">
                            Sebelumnya
                        </a>
                        <button type="button" id="tombol-kirim-ak04"
                            class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5">
                            Kirim Pengajuan
                        </button>
                    </div>

                </div>

                {{-- Script JavaScript untuk Mockup Tanda Tangan dan Loading --}}
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Mockup: Simulasikan Tanda Tangan sudah ada
                        const ttdContainer = document.getElementById('ttd_container');
                        const ttdPlaceholder = document.getElementById('ttd_placeholder');

                        // Contoh URL Tanda Tangan (bisa diganti dengan data real)
                        const tandaTanganURL = 'https://via.placeholder.com/400x200.png?text=Tanda+Tangan+Tersimpan';

                        if (tandaTanganURL) {
                            const img = document.createElement('img');
                            img.src = tandaTanganURL;
                            img.alt = "Tanda Tangan Asesi";
                            img.className = "object-contain h-full w-full p-4";

                            if (ttdPlaceholder) {
                                ttdPlaceholder.parentElement.style.display = 'none'; // Sembunyikan placeholder & icon
                            }
                            ttdContainer.innerHTML = '';
                            ttdContainer.appendChild(img);
                        }

                        // Logika Tombol Kirim (untuk menampilkan loading state)
                        const tombolKirim = document.getElementById('tombol-kirim-ak04');
                        tombolKirim.addEventListener('click', function() {
                            if (!confirm('Yakin ingin mengirim banding ini?')) {
                                return;
                            }
                            this.innerHTML =
                                `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengirim...`;
                            this.disabled = true;
                            this.classList.add('opacity-75', 'cursor-not-allowed');

                            setTimeout(() => {
                                alert('Banding berhasil dikirim (Mockup).');
                                this.textContent = 'Banding Terkirim';
                            }, 1500);
                        });
                    });
                </script>
            @endsection
