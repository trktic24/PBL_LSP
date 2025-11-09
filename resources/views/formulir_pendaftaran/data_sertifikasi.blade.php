<!DOCTYPE html>
<html lang="id">

<head>
    {{-- Perbaikan: Charset standar adalah UTF-8 --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Sertifikasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="flex min-h-screen">
        <x-sidebar></x-sidebar>
        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-4xl mx-auto">

                <!-- ... (Kode progress bar Anda tetap sama) ... -->
                <div class="flex items-center justify-center mb-12">
                    <div class="flex flex-col items-center">
                        <div
                            class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            1</div>
                    </div>
                    <div class="w-24 h-0.5 bg-gray-300 mx-4"></div>
                    <div class="flex flex-col items-center">
                        <div
                            class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold text-lg">
                            2</div>
                    </div>
                    <div class="w-24 h-0.5 bg-gray-300 mx-4"></div>
                    <div class="flex flex-col items-center">
                        <div
                            class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold text-lg">
                            3</div>
                    </div>
                </div>
                <!-- ... (Sisa kode Anda tetap sama) ... -->

                <h1 class="text-4xl font-bold text-gray-900 mb-4">Data Sertifikasi</h1>
                <p class="text-gray-600 mb-8">
                    Pilih Tujuan Asesmen serta Daftar Unit Kompetensi sesuai kemasan pada skema sertifikasi yang anda
                    ajukan.
                </p>

                <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 mb-8">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Skema Sertifikasi / Klaster Asesmen</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-1 text-sm font-medium text-gray-600">Judul</div>
                        <div class="col-span-2 text-sm text-gray-900">: Lorem ipsum Dolor Sit Amet</div>

                        <div class="col-span-1 text-sm font-medium text-gray-600">Nomor</div>
                        <div class="col-span-2 text-sm text-gray-900">: SKM12XXXXXX</div>
                    </div>
                </div>

                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Pilih Tujuan Asesmen</h3>
                    {{--
                        PERBAIKAN: Tambahkan 'sm:auto-rows-fr'
                        Ini memaksa semua barIS grid (di layar 'sm' ke atas)
                        untuk memiliki tinggi yang sama.
                    --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:auto-rows-fr">

                        {{-- 1. Sertifikasi --}}
                        <label
                            class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer h-full">
                            <input type="checkbox" name="tujuan_asesmen" value="Sertifikasi"
                                class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 tujuan-checkbox">
                            <span class="ml-3 text-sm font-medium text-gray-700">Sertifikasi</span>
                        </label>

                        {{-- 2. Sertifikasi Ulang --}}
                        <label
                            class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer h-full">
                            <input type="checkbox" name="tujuan_asesmen" value="Sertifikasi Ulang"
                                class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 tujuan-checkbox">
                            <span class="ml-3 text-sm font-medium text-gray-700">Sertifikasi Ulang</span>
                        </label>

                        {{-- 3. PKT --}}
                        <label
                            class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer h-full">
                            <input type="checkbox" name="tujuan_asesmen" value="PKT"
                                class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 tujuan-checkbox">
                            <span class="ml-3 text-sm font-medium text-gray-700">Pengakuan Kompetensi Terkini
                                (PKT)</span>
                        </label>

                        {{-- 4. RPL --}}
                        <label
                            class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer h-full">
                            <input type="checkbox" name="tujuan_asesmen" value="RPL"
                                class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 tujuan-checkbox">
                            <span class="ml-3 text-sm font-medium text-gray-700">Rekognisi Pembelajaran Lampau</span>
                        </label>

                        {{-- 5. Lainnya (Dengan ID untuk Javascript) --}}
                        <label
                            class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer h-full">
                            <input type="checkbox" name="tujuan_asesmen" value="Lainnya"
                                class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 tujuan-checkbox"
                                id="checkbox-lainnya">
                            <span class="ml-3 text-sm font-medium text-gray-700">Lainnya</span>
                        </label>
                    </div>

                    {{-- TAMBAHAN: Input Keterangan 'Lainnya', disembunyikan by default --}}
                    <div>
                        <div id="keterangan-lainnya-wrapper" class="hidden mt-4">
                            <label for="keterangan-lainnya-input"
                                class="block text-sm font-medium text-gray-700 mb-1">Keterangan Lainnya:</label>
                            <input type="text" name="keterangan_lainnya" id="keterangan-lainnya-input"
                                class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Tuliskan keterangan..">

                            {{-- Tombol Edit dan Simpan --}}
                            <div class="flex justify-end items-center mt-4 space-x-3">
                                <button type="button"
                                    class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors disabled:bg-gray-300 disabled:opacity-70"
                                    id="edit-keterangan-lainnya" disabled>
                                    Edit
                                </button>
                                <button type="button"
                                    class="px-4 py-2 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 shadow-md transition-colors disabled:bg-blue-300 disabled:opacity-70"
                                    id="save-keterangan-lainnya">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ... (Sisa kode tabel Anda tetap sama) ... -->
                <div class="mb-10">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Unit Kompetensi</h3>
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        No</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Kode Unit</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Judul Unit</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Jenis Standard</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @for ($i = 1; $i <= 7; $i++)
                                    <tr class="{{ $i % 2 == 0 ? 'bg-gray-50' : 'bg-white' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $i }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">J.XXXXXXXX.XXX.XX
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Lorem ipsum Dolor
                                            Sit Amet</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">SKKNI No xxx tahun
                                            20xx</td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- ... (Sisa kode tombol Anda tetap sama) ... -->
                <div class="flex justify-end items-center">
                    <a href="/bukti_pemohon"
                        class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-full hover:bg-blue-700 shadow-md">
                        Selanjutnya
                    </a>
                </div>

            </div>
        </main>

    </div>

    {{-- SCRIPT LENGKAP: (1) Logika "Pilih Satu" + (2) Logika "Keterangan Lainnya" + (3) Logika Tombol Simpan/Edit --}}
    <script>
        // Pastikan semua script berjalan setelah halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {

            // --- Variabel Elemen ---
            const tujuanCheckboxes = document.querySelectorAll('.tujuan-checkbox');
            const keteranganWrapper = document.getElementById('keterangan-lainnya-wrapper');
            const checkboxLainnya = document.getElementById('checkbox-lainnya');

            // Variabel untuk tombol
            const keteranganInput = document.getElementById('keterangan-lainnya-input');
            const saveButton = document.getElementById('save-keterangan-lainnya');
            const editButton = document.getElementById('edit-keterangan-lainnya');


            // --- Event Listener untuk Checkbox ---
            tujuanCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('click', (e) => {
                    const clickedCheckbox = e.target;

                    // 1. LOGIKA PILIH SATU
                    tujuanCheckboxes.forEach(cb => {
                        if (cb !== clickedCheckbox) {
                            cb.checked = false;
                        }
                    });

                    // 2. LOGIKA KETERANGAN LAINNYA
                    if (checkboxLainnya.checked) {
                        keteranganWrapper.classList.remove('hidden');
                    } else {
                        keteranganWrapper.classList.add('hidden');
                    }
                });
            });


            // --- Event Listener untuk Tombol Simpan ---
            saveButton.addEventListener('click', function() {
                const inputText = keteranganInput.value.trim(); // Ambil teks & hapus spasi

                if (inputText) {
                    // Ada teks, kunci inputnya
                    keteranganInput.disabled = true;

                    // Ubah tombol Simpan
                    this.textContent = 'Tersimpan';
                    this.disabled = true;

                    // Aktifkan tombol Edit
                    editButton.disabled = false;

                    // Anda bisa ganti alert ini dengan notifikasi yang lebih cantik nanti
                    alert('Keterangan berhasil disimpan!');

                } else {
                    // Tidak ada teks, beri peringatan
                    alert('Harap isi keterangan terlebih dahulu.');
                }
            });

            // --- Event Listener untuk Tombol Edit ---
            editButton.addEventListener('click', function() {
                // Aktifkan kembali input
                keteranganInput.disabled = false;

                // Kembalikan tombol Simpan
                saveButton.textContent = 'Simpan';
                saveButton.disabled = false;

                // Nonaktifkan tombol Edit
                this.disabled = true;

                // Beri fokus ke input agar user bisa langsung mengetik
                keteranganInput.focus();
            });

        });
    </script>

</body>

</html>
