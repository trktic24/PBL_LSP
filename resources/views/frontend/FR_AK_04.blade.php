@extends('layouts.app-sidebar') {{-- Menggunakan layout sidebar yang konsisten --}}

@section('content')
    <div class="p-8 max-w-5xl mx-auto">

        {{-- 1. Menggunakan Komponen Header Form --}}
        {{-- Di sini Anda mungkin ingin menggunakan x-header_form.header_form yang sesuai --}}
        <x-header_form.header_form title="BANDING ASESMEN" />

        {{-- Jika Anda punya komponen Identitas Skema, letakkan di sini --}}
        {{-- <x-identitas_skema_form.identitas_skema_form /> --}}

        <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-8 shadow-sm">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">Informasi Banding</h2>
            <dl class="grid grid-cols-1 md:grid-cols-4 gap-y-4 text-sm mb-6">
                <dt class="col-span-1 font-medium text-gray-800">TUK</dt>
                <dd class="col-span-3 flex flex-wrap gap-x-6 gap-y-2 items-center">
                    <label class="flex items-center text-gray-700">
                        <input type="checkbox" value="Sewaktu" disabled
                            class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2 opacity-100 cursor-default">
                        Sewaktu
                    </label>
                    <label class="flex items-center text-gray-700">
                        <input type="checkbox" value="Tempat Kerja" disabled
                            class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2 opacity-100 cursor-default">
                        Tempat Kerja
                    </label>
                    <label class="flex items-center text-gray-700">
                        <input type="checkbox" value="Mandiri" disabled
                            class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2 opacity-100 cursor-default">
                        Mandiri
                    </label>
                </dd>

                <dt class="col-span-1 font-medium text-gray-800">Nama Asesor</dt>
                <dd class="col-span-3 text-gray-500 font-medium">: <span id="nama_asesor">[Memuat...]</span></dd>

                <dt class="col-span-1 font-medium text-gray-800">Nama Asesi</dt>
                <dd class="col-span-3 text-gray-500 font-medium">: <span id="nama_asesi">[Memuat...]</span></dd>
            </dl>
        </div>

        <p class="text-sm text-gray-700 mb-4">
            Jawablah dengan Ya atau Tidak pertanyaan-pertanyaan berikut ini :
        </p>

        <div class="overflow-x-auto border border-gray-300 rounded-md shadow-sm mb-6">
            <table class="w-full text-sm">
                <thead class="bg-black text-white">
                    <tr>
                        <th class="p-3 text-left font-medium w-[70%]">Komponen</th>
                        <th class="p-3 text-center font-medium w-[15%]">Ya</th>
                        <th class="p-3 text-center font-medium w-[15%]">Tidak</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    <tr>
                        <td class="p-3">Apakah Proses Banding telah dijelaskan kepada Anda?</td>
                        <td class="p-3 text-center"><input type="radio" name="banding_1" value="ya"
                                class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="banding_1" value="tidak"
                                class="h-4 w-4 text-blue-600"></td>
                    </tr>
                    <tr>
                        <td class="p-3">Apakah Anda telah mendiskusikan Banding dengan Asesor?</td>
                        <td class="p-3 text-center"><input type="radio" name="banding_2" value="ya"
                                class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="banding_2" value="tidak"
                                class="h-4 w-4 text-blue-600"></td>
                    </tr>
                    <tr>
                        <td class="p-3">Apakah Anda mau melibatkan "orang lain" membantu Anda dalam Proses Banding?
                        </td>
                        <td class="p-3 text-center"><input type="radio" name="banding_3" value="ya"
                                class="h-4 w-4 text-blue-600"></td>
                        <td class="p-3 text-center"><input type="radio" name="banding_3" value="tidak"
                                class="h-4 w-4 text-blue-600"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6 shadow-inner">
            <p class="text-sm text-gray-700 font-medium mb-3">
            <p class="text-sm text-gray-700 mb-2">
                Banding diajukan atas Keputusan Asesmen yang dibuat terhadap Skema Sertifikasi Okupasi Nasional berikut
                :
            </p>

            <dl class="grid grid-cols-1 md:grid-cols-4 gap-y-2 text-sm mb-6 ml-4">
                <dt class="col-span-1 font-medium text-gray-800">Skema Sertifikasi</dt>
                <dd class="col-span-3 text-gray-500 font-medium">: <span id="skema_sertifikasi">[Memuat...]</span></dd>
                <dt class="col-span-1 font-medium text-gray-800">No. Skema Sertifikasi</dt>
                <dd class="col-span-3 text-gray-500 font-medium">: <span id="no_skema_sertifikasi">[Memuat...]</span>
                </dd>
            </dl>
        </div>

        <p class="text-sm text-gray-700 mb-2">
            Banding diajukan atas alasan sebagai berikut :
        </p>

        <textarea rows="4" id="alasan_banding" placeholder="Berikan Keterangan Disini"
            class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm mb-6 resize-none"></textarea>

        <p class="text-xs text-gray-500 italic mb-4">
            Anda mempunyai hak mengajukan banding jika Anda menilai proses asesmen tidak sesuai SOP dan tidak memenuhi
            Prinsip Asesmen.
        </p>

        {{-- Area Tanda Tangan --}}
        <div class="mt-8">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan Peserta</label>
            <div class="w-full h-56 bg-white border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center overflow-hidden relative group hover:border-gray-400 transition-colors"
                id="ttd_container">
                <p id="ttd_placeholder" class="text-gray-400 text-sm">Area Tanda Tangan akan muncul di sini</p>
            </div>

        </div>

        {{-- Tombol Footer --}}
        <div class="mt-8 flex justify-end items-center pt-6 border-t border-gray-200">
            {{-- CSRF Token Hidden --}}
            @csrf

            {{-- Tombol Kirim (Hapus tombol "Kembali" dan "Hapus" dari footer agar konsisten dengan AK01) --}}
            <button type="button" id="tombol-kirim-ak04"
                class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5">
                Kirim Banding
            </button>
        </div>
    </div>

    {{-- Skrip JavaScript minimal untuk Tanda Tangan (Statis/Mockup) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ttdContainer = document.getElementById('ttd_container_ak04');
            const ttdPlaceholder = document.getElementById('ttd_placeholder_ak04');
            const tombolHapus = document.getElementById('tombol-hapus-ak04');
            const tombolKirim = document.getElementById('tombol-kirim-ak04');

            // --- Mock Data Tanda Tangan (Seperti Logika Fetch AK01) ---
            // Simulasikan ada tanda tangan yang sudah tersimpan (data.asesi.tanda_tangan)
            const tandaTanganURL = 'https://via.placeholder.com/400x200.png?text=Tanda+Tangan+Siti+Amelia';

            if (tandaTanganURL) {
                const img = document.createElement('img');
                img.src = tandaTanganURL;
                img.alt = "Tanda Tangan Asesi";
                img.className = "object-contain h-full w-full p-4"; // Style dari AK01

                if (ttdPlaceholder) ttdPlaceholder.style.display = 'none';
                ttdContainer.innerHTML = '';
                ttdContainer.appendChild(img);
            } else {
                // Jika tidak ada tanda tangan
                if (ttdPlaceholder) {
                    ttdPlaceholder.style.display = 'block';
                    ttdPlaceholder.innerText = 'Silakan lakukan tanda tangan di sini.';
                }
                tombolHapus.disabled = true;
            }

            // --- LOGIC TOMBOL "HAPUS" (Sesuai Logika Hapus AK01) ---
            tombolHapus.addEventListener('click', function() {
                if (!confirm('Yakin mau hapus tanda tangan ini PERMANEN?')) {
                    return;
                }

                // Simulasikan AJAX Delete Berhasil
                alert('Tanda tangan berhasil dihapus (Mockup).');
                ttdContainer.innerHTML = '';
                ttdContainer.appendChild(ttdPlaceholder);
                ttdPlaceholder.style.display = 'block';
                ttdPlaceholder.innerText = 'Tanda tangan berhasil dihapus (Mockup).';
                tombolHapus.disabled = true;
                tombolHapus.classList.add('opacity-50', 'cursor-not-allowed');

                // Di kode aslinya, Anda akan menjalankan:
                // fetch(`/api/ajax-hapus-tandatangan/${asesiId}`, { method: 'POST', ... })
            });

            // --- LOGIC TOMBOL "KIRIM" (Sesuai Logika Selanjutnya AK01) ---
            tombolKirim.addEventListener('click', function() {
                if (!confirm('Yakin ingin mengirim banding ini?')) {
                    return;
                }

                // Simulasikan Loading State
                this.innerHTML =
                    `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengirim...`;
                this.disabled = true;
                this.classList.add('opacity-75', 'cursor-not-allowed');

                // Di kode aslinya, Anda akan menjalankan:
                // fetch(`/api/submit-banding/${asesiId}`, { method: 'POST', ... })

                setTimeout(() => {
                    alert('Banding berhasil dikirim (Mockup).');
                    // window.location.href = '/tracker/' + asesiId; // Redirect ke halaman selanjutnya
                    this.textContent = 'Banding Terkirim';
                }, 1500);
            });
        });
    </script>
@endsection
