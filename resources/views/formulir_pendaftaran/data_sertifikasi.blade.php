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
        <x-sidebar :idAsesi="$asesi->id_asesi"></x-sidebar>
        <main class="flex-1 p-12 bg-white overflow-y-auto" data-sertifikasi-id="{{ $id_sertifikasi_untuk_js }}">
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
                        <div class="col-span-2 text-sm text-gray-900" id="skema-judul">: ...Memuat data...</div>

                        <div class="col-span-1 text-sm font-medium text-gray-600">Nomor</div>
                        <div class="col-span-2 text-sm text-gray-900" id="skema-nomor">: ...Memuat data...</div>
                    </div>
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
                <a href="{{ route('bukti.pemohon', ['id_asesi' => $asesi->id_asesi]) }}"
                    class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-full hover:bg-blue-700 shadow-md">
                    Selanjutnya
                </a>
            </div>

    </div>
    </main>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ==========================================================
            // BAGIAN 1: KODE BARU UNTUK FETCH DATA SKEMA (DARI API)
            // ==========================================================

            // 1. Ambil ID Sertifikasi dari tag <main>
            const mainElement = document.querySelector('main[data-sertifikasi-id]');

            // 2. Cek dulu ID-nya ada atau tidak
            if (mainElement && mainElement.dataset.sertifikasiId) {

                const sertifikasiId = mainElement.dataset.sertifikasiId;
                console.log('Berhasil dapet ID Sertifikasi:', sertifikasiId); // Cek di console (F12)

                // 3. Tembak API detail sertifikasi
                fetch(`/api/data-sertifikasi/detail/${sertifikasiId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`Gagal memuat data skema (Status: ${response.status})`);
                        }
                        return response.json();
                    })
                    .then(apiResponse => {
                        // Cek di console (F12) -> Tab "Console"
                        console.log('Data API diterima:', apiResponse);

                        if (apiResponse.success && apiResponse.data) {
                            const data = apiResponse.data;

                            // 4. Isi data ke HTML
                            if (data.jadwal && data.jadwal.skema) {
                                document.getElementById('skema-judul').textContent = ': ' + data.jadwal.skema
                                    .nama_skema;
                                document.getElementById('skema-nomor').textContent = ': ' + data.jadwal.skema
                                    .kode_unit;
                            } else {
                                document.getElementById('skema-judul').textContent =
                                    ': Data Skema Tidak Ditemukan';
                                document.getElementById('skema-nomor').textContent = ': -';
                                console.warn('API sukses tapi data skema/jadwal tidak ada di JSON');
                            }
                        } else {
                            throw new Error(apiResponse.message || 'API merespon tapi tidak sukses');
                        }
                    })
                    .catch(error => {
                        console.error('Error Fetch API:', error);
                        document.getElementById('skema-judul').textContent = ': Gagal memuat data';
                        document.getElementById('skema-nomor').textContent = ': -';
                    });

            } else {
                // Ini kalau `data-sertifikasi-id` tidak ditemukan di tag <main>
                console.error('FATAL: Tidak bisa menemukan "data-sertifikasi-id" di tag <main>.');
                alert('Terjadi error. ID Sertifikasi tidak ditemukan di halaman.');
                document.getElementById('skema-judul').textContent = ': Error! ID tidak ada.';
                document.getElementById('skema-nomor').textContent = ': -';
            }


            // ==========================================================
            // BAGIAN 2: KODE LAMA KAMU (CHECKBOX & TOMBOL LAINNYA)
            // ==========================================================

            // --- Variabel Elemen ---
            const tujuanCheckboxes = document.querySelectorAll('.tujuan-checkbox');
            const keteranganWrapper = document.getElementById('keterangan-lainnya-wrapper');
            const checkboxLainnya = document.getElementById('checkbox-lainnya');

            const keteranganInput = document.getElementById('keterangan-lainnya-input');
            const saveButton = document.getElementById('save-keterangan-lainnya');
            const editButton = document.getElementById('edit-keterangan-lainnya');

            const idAsesi = {{ $asesi->id_asesi }};

            // =============================
            // 1. GET DATA DARI API
            // =============================
            try {
                const response = await fetch(`/api/data-sertifikasi/${idAsesi}`);
                const data = await response.json();

                // Prefill Judul & Nomor Skema
                document.getElementById('judul-skema').innerHTML = ": " + data.skema.nama_skema;
                document.getElementById('nomor-skema').innerHTML = ": " + data.skema.nomor_skema;

                // Prefill Tujuan Asesmen
                if (data.asesi.tujuan_asesmen) {
                    const tujuan = data.asesi.tujuan_asesmen;

                    tujuanCheckboxes.forEach(cb => {
                        if (cb.value === tujuan) cb.checked = true;
                    });

                    // Jika "lainnya"
                    if (tujuan === "Lainnya") {
                        checkboxLainnya.checked = true;
                        keteranganWrapper.classList.remove('hidden');

                        keteranganInput.value = data.asesi.keterangan_lainnya ?? "";
                    }
                }

            } catch (err) {
                console.error("Gagal load API", err);
            }


            // =============================
            // 2. LOGIKA PILIH SATU CHECKBOX
            // =============================
            tujuanCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('click', () => {
                    tujuanCheckboxes.forEach(cb => {
                        if (cb !== checkbox) cb.checked = false;
                    });

                    if (checkboxLainnya.checked) {
                        keteranganWrapper.classList.remove('hidden');
                    } else {
                        keteranganWrapper.classList.add('hidden');
                    }
                });
            });


            // =============================
            // 3. SIMPAN KE DATABASE (POST API)
            // =============================
            saveButton.addEventListener('click', async () => {

                const tujuanDipilih = [...tujuanCheckboxes]
                    .find(cb => cb.checked)?.value;

                    // Ubah tombol Simpan
                    this.textContent = 'Tersimpan';
                    this.disabled = true;

                    // Aktifkan tombol Edit
                    editButton.disabled = false;

                    alert('Keterangan berhasil disimpan!');

                } else {
                    alert('Harap isi keterangan terlebih dahulu.');
                }

                let keterangan = "";
                if (tujuanDipilih === "Lainnya") {
                    keterangan = keteranganInput.value.trim();
                    if (!keterangan) {
                        alert("Isi keterangan lainnya!");
                        return;
                    }
                }

                try {
                    const response = await fetch('/api/data-sertifikasi/save', {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            id_asesi: idAsesi,
                            tujuan_asesmen: tujuanDipilih,
                            keterangan_lainnya: keterangan
                        })
                    });

                    const result = await response.json();

                    if (result.status === "success") {
                        alert("Data berhasil disimpan!");
                    }

                } catch (e) {
                    alert("Gagal menyimpan data!");
                    console.error(e);
                }
            });

        }); // <-- Ini adalah penutup WAJIB dari 'DOMContentLoaded'
    </script>


</body>

</html>
