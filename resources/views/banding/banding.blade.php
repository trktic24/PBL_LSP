<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banding Asesmen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- WAJIB: Tambahkan Meta CSRF Token di HEAD --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* Styling khusus untuk TUK checkbox yang disabled (read-only) */
        input[type="checkbox"]:disabled {
            opacity: 1 !important;
            cursor: default;
            accent-color: #2563eb;
        }

        input[type="checkbox"]:disabled+span {
            color: #374151;
            /* Untuk memastikan warna
            biru tetap terlihat pada TUK yang dipilih */
        }
    </style>
</head>

<body class="bg-gray-100">

    <div class="flex min-h-screen">

        {{-- ================================================= --}}
        {{-- BAGIAN 1: SIDEBAR (Komponen X-Sidebar2 Asli Anda) --}}
        <div class="sticky top-0 h-screen">
            {{-- Pastikan data seperti $asesor dan $idAsesi dikirim dari controller --}}
            <x-sidebar2 backUrl="/tracker" :asesorNama="$asesor['nama']" :asesorNoReg="$asesor['no_reg']" :idAsesi="$idAsesi" />
        </div>

        <main class="flex-1 p-12 bg-white overflow-y-auto" data-sertifikasi-id="{{ $id_sertifikasi }}">
            <div class="max-w-3xl mx-auto">

                <h1 class="text-4xl font-bold text-gray-900 mb-10">Banding Asesmen</h1>

                {{-- Data TUK, Asesor, Asesi, Skema (Read-Only) --}}
                {{-- Menggunakan DL (Definition List) dengan Tailwind Grid --}}
                <dl class="grid grid-cols-1 md:grid-cols-4 gap-y-4 text-sm mb-8">

                    {{-- 1. TUK (Read Only) --}}
                    <dt class="col-span-1 font-medium text-gray-800">TUK</dt>
                    {{-- KRITIS: Tambahkan id="tuk_container" --}}
                    <dd class="col-span-3 flex flex-wrap gap-x-6 gap-y-2 items-center" id="tuk_container">
                        <label class="flex items-center text-gray-700 cursor-default">
                            <input type="checkbox" id="tuk_Sewaktu" disabled
                                class="w-4 h-4 rounded border-gray-300 mr-2">
                            <span>Sewaktu</span>
                        </label>
                        <label class="flex items-center text-gray-700 cursor-default">
                            {{-- KRITIS: Perbaiki ID menjadi tanpa spasi (tuk_TempatKerja) --}}
                            <input type="checkbox" id="tuk_TempatKerja" disabled
                                class="w-4 h-4 rounded border-gray-300 mr-2">
                            <span>Tempat Kerja</span>
                        </label>
                        <label class="flex items-center text-gray-700 cursor-default">
                            <input type="checkbox" id="tuk_Mandiri" disabled
                                class="w-4 h-4 rounded border-gray-300 mr-2">
                            <span>Mandiri</span>
                        </label>
                    </dd>

                    {{-- 2. Nama Asesor --}}
                    <dt class="col-span-1 font-medium text-gray-800">Nama Asesor</dt>
                    {{-- Isi ID di sini, sesuai kode Anda: ": <span id="nama_asesor">...</span>" --}}
                    <dd class="col-span-3 text-gray-800 font-semibold">: <span id="nama_asesor">Memuat...</span></dd>

                    {{-- 3. Nama Asesi --}}
                    <dt class="col-span-1 font-medium text-gray-800">Nama Asesi</dt>
                    <dd class="col-span-3 text-gray-800 font-semibold">: <span id="nama_asesi">Memuat...</span></dd>

                    {{-- 4. Skema (Ditambahkan untuk kelengkapan data read-only) --}}
                    <dt class="col-span-1 font-medium text-gray-800">Skema</dt>
                    <dd class="col-span-3 text-gray-800 font-semibold">: <span id="skema_judul">Memuat...</span></dd>

                </dl>

                {{-- ... Lanjutkan dengan tabel pertanyaan banding, alasan, dan TTD ... --}}

            </div>
            <div class="text-sm text-gray-700 mb-6">Jawablah dengan Ya atau Tidak pertanyaan-pertanyaan berikut ini:
            </div>

            {{-- Tabel Komponen Banding (User Input) --}}
            <div class="shadow border border-gray-200 rounded-lg overflow-hidden">
                <table class="min-w-full" id="tabel_banding">
                    <thead class="bg-gray-900 text-white">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-bold uppercase tracking-wider">
                                Komponen</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-sm font-bold uppercase tracking-wider w-20">Ya
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-sm font-bold uppercase tracking-wider w-20">Tidak
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        {{-- Pertanyaan 1: Proses Banding telah dijelaskan --}}
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-700">Apakah Proses Banding telah dijelaskan
                                kepada Anda?</td>
                            <td class="px-6 py-4 align-middle">
                                <div class="flex justify-center">
                                    {{-- Value 1 untuk Ya (sesuai boolean di DB) --}}
                                    <input type="checkbox" name="penjelasan_banding" value="1"
                                        class="w-5 h-5 text-blue-600 rounded-lg border-2 border-blue-500 focus:ring-blue-500">
                                </div>
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <div class="flex justify-center">
                                    {{-- Value 0 untuk Tidak (sesuai boolean di DB) --}}
                                    <input type="checkbox" name="penjelasan_banding" value="0"
                                        class="w-5 h-5 text-red-600 rounded-lg border-2 border-red-500 focus:ring-red-500">
                                </div>
                            </td>
                        </tr>
                        {{-- Pertanyaan 2: Diskusi dengan Asesor --}}
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-700">Apakah Anda telah mendiskusikan Banding
                                dengan Asesor?</td>
                            <td class="px-6 py-4 align-middle">
                                <div class="flex justify-center">
                                    <input type="checkbox" name="diskusi_dengan_asesor" value="1"
                                        class="w-5 h-5 text-blue-600 rounded-lg border-2 border-blue-500 focus:ring-blue-500">
                                </div>
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <div class="flex justify-center">
                                    <input type="checkbox" name="diskusi_dengan_asesor" value="0"
                                        class="w-5 h-5 text-red-600 rounded-lg border-2 border-red-500 focus:ring-red-500">
                                </div>
                            </td>
                        </tr>
                        {{-- Pertanyaan 3: Melibatkan 'orang lain' --}}
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-700">Apakah Anda mau melibatkan 'orang lain'
                                membantu Anda dalam Proses Banding?</td>
                            <td class="px-6 py-4 align-middle">
                                <div class="flex justify-center">
                                    <input type="checkbox" name="melibatkan_orang_lain" value="1"
                                        class="w-5 h-5 text-blue-600 rounded-lg border-2 border-blue-500 focus:ring-blue-500">
                                </div>
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <div class="flex justify-center">
                                    <input type="checkbox" name="melibatkan_orang_lain" value="0"
                                        class="w-5 h-5 text-red-600 rounded-lg border-2 border-red-500 focus:ring-red-500">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Data Skema (Read Only) --}}
            <div class="mt-6 text-sm text-gray-700 space-y-2">
                <p>Banding ini diajukan atas Keputusan Asesmen yang dibuat terhadap Skema Sertifikasi Okupasi
                    Nasional berikut:</p>
                <p>Skema Sertifikasi: <span class="font-medium text-gray-900" id="judul_skema_bawah">Memuat...</span>
                </p>
                <p>No. Skema Sertifikasi: <span class="font-medium text-gray-900"
                        id="nomor_skema_bawah">Memuat...</span>
                </p>
            </div>

            {{-- Alasan Banding (Input) --}}
            <div class="mt-6">
                <label for="alasan_banding" class="text-sm font-medium text-gray-700">Banding ini diajukan atas
                    alasan sebagai berikut:</label>
                <textarea id="alasan_banding" rows="4" placeholder="Berikan Keterangan Disini"
                    class="mt-2 w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"></textarea>
            </div>

            {{-- Tanda Tangan (Read Only) --}}
            <div class="mt-6">
                <p class="mt-4 text-gray-700 text-sm leading-relaxed">
                    Anda mempunyai hak mengajukan banding jika Anda menilai proses asesmen tidak sesuai SOP dan
                    tidak memenuhi Prinsip Asesmen.
                </p>
                <div class="mt-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan Asesi</label>
                    <div class="w-full h-48 bg-white border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center overflow-hidden relative"
                        id="ttd_container">
                        <p id="ttd_placeholder" class="text-gray-400 text-sm">Memuat tanda tangan...</p>
                    </div>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <p class="text-red-600 text-xs italic">*Tanda Tangan di sini</p>
                    <button
                        class="px-4 py-1.5 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300"
                        disabled>
                        Hapus
                    </button>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-between items-center mt-12">
                <button id="btn_sebelumnya"
                    class="px-8 py-3 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300 transition-colors">
                    Sebelumnya
                </button>
                <button id="btn_kirim"
                    class="px-8 py-3 bg-blue-500 text-white font-semibold rounded-full hover:bg-blue-600 shadow-md transition-colors">
                    Kirim
                </button>
            </div>

    </div>
    </main>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async function() {

            // Ambil elemen dan token
            const mainEl = document.querySelector('main[data-sertifikasi-id]');
            const idSertifikasi = mainEl.dataset.sertifikasiId;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            // Elemen Display Data (Read-Only)
            const namaAsesorEl = document.getElementById('nama_asesor');
            const namaAsesiEl = document.getElementById('nama_asesi');
            const skemaJudulEl = document.getElementById('skema_judul');
            const skemaJudulBawahEl = document.getElementById('judul_skema_bawah');
            const skemaNomorBawahEl = document.getElementById('nomor_skema_bawah');
            // const skemaNomorEl = document.getElementById('skema_nomor'); // Dihapus/diabaikan jika digabung di Judul

            // Elemen Form & TTD
            const ttdContainer = document.getElementById('ttd_container');
            const alasanBandingEl = document.getElementById('alasan_banding');
            const btnKirim = document.getElementById('btn_kirim');
            const btnSebelumnya = document.getElementById('btn_sebelumnya');
            const prevUrl = "{{ route('umpan.balik', ['id_sertifikasi' => $id_sertifikasi]) }}";

            /* ========== 1. CHECKBOX SINGLE CHOICE (Sudah Benar) ========== */
            // Memastikan hanya satu Ya/Tidak yang bisa tercentang per pertanyaan
            document.querySelectorAll('#tabel_banding input[type="checkbox"]').forEach(cb => {
                cb.addEventListener('click', function() {
                    const name = this.name;
                    if (this.checked) {
                        document.querySelectorAll(`input[name="${name}"]`).forEach(other => {
                            if (other !== this) other.checked = false;
                        });
                    }
                });
            });


            /* ========== 2. LOAD DATA API (GET) ========== */
            try {
                // KRITIS: URL API GET SAMA DENGAN AK.01: /api/v1/banding/{idSertifikasi}
                const response = await fetch(`/api/v1/banding/${idSertifikasi}`);
                const resp = await response.json();

                if (!response.ok || !resp.success) {
                    // Tambahkan kode status di alert agar mudah didiagnosa
                    throw new Error(
                        `[${response.status}] ${resp.message || "Gagal memuat data read-only dan riwayat."}`
                    );
                }

                const data = resp.data;
                const ak04 = data.respon_ak04 || {};

                // A. Data Asesor dan Asesi (Read-Only)
                namaAsesorEl.innerText = data.asesor.nama_lengkap ?? "-";
                namaAsesiEl.innerText = data.asesi.nama_lengkap ?? "-";

                // B. Data Skema (Read-Only)
                skemaJudulEl.innerText =
                    `${data.jadwal.skema.nama_skema ?? "-"} (${data.jadwal.skema.nomor_skema ?? "-"})`;


                // C. Data TUK (Read-Only)
                // Sesuaikan ID checkbox: Sewaktu, Tempat Kerja. (Hapus spasi)
                // ... (Dalam bagian 2. LOAD DATA API) ...

                // TUK
                // Data dari API: data.tuk_lokasi bisa berupa 'Tempat Kerja'
                const tukLokasiTanpaSpasi = data.tuk_lokasi.replace(' ',
                    ''); // Akan menjadi 'TempatKerja' atau 'Sewaktu'
                const tukCheckbox = document.getElementById(`tuk_${tukLokasiTanpaSpasi}`);
                if (tukCheckbox) tukCheckbox.checked = true;

                // Tambahkan variabel baru untuk bagian bawah
                skemaJudulBawahEl.innerText = data.jadwal.skema.nama_skema ?? "-"; // Mengisi Judul Skema
                skemaNomorBawahEl.innerText = data.jadwal.skema.nomor_skema ?? "-";

                // ...
                // D. Tanda Tangan Asesi
                if (data.asesi.tanda_tangan) {
                    const img = document.createElement("img");
                    // KRITIS: Pastikan path tanda tangan dimulai dengan / jika disimpan di storage
                    img.src = `/${data.asesi.tanda_tangan}`;
                    img.className = "max-h-full max-w-full object-contain";
                    ttdContainer.innerHTML = "";
                    ttdContainer.appendChild(img);
                } else {
                    ttdContainer.innerHTML =
                        `<p class="text-red-500 text-sm">Tanda tangan belum tersedia. Mohon hubungi admin/asesor.</p>`;
                }

                // E. Load Jawaban Sebelumnya (Riwayat Banding AK.04)
                if (ak04.id_respon_ak04) {
                    alasanBandingEl.value = ak04.alasan_banding ?? "";

                    // Centang jawaban Ya/Tidak sebelumnya (Nilai 1=Ya, 0=Tidak)
                    document.querySelectorAll('#tabel_banding input[name]').forEach(cb => {
                        const cbValue = cb.value;
                        if (cb.name === "penjelasan_banding" && cbValue == ak04.penjelasan_banding) cb
                            .checked = true;
                        if (cb.name === "diskusi_dengan_asesor" && cbValue == ak04
                            .diskusi_dengan_asesor) cb.checked = true;
                        if (cb.name === "melibatkan_orang_lain" && cbValue == ak04
                            .melibatkan_orang_lain) cb.checked = true;
                    });
                }

            } catch (err) {
                console.error("Load data error:", err);
                alert("Gagal memuat data banding: " + err.message);
            }


            /* ========== 3. SIMPAN DATA API (POST) ========== */
            btnKirim.addEventListener('click', async function() {

                const p1 = document.querySelector(`input[name="penjelasan_banding"]:checked`);
                const p2 = document.querySelector(`input[name="diskusi_dengan_asesor"]:checked`);
                const p3 = document.querySelector(`input[name="melibatkan_orang_lain"]:checked`);

                if (!p1 || !p2 || !p3 || !alasanBandingEl.value.trim()) {
                    alert(
                        'Mohon lengkapi semua data banding (3 pertanyaan Ya/Tidak dan Alasan Banding).'
                    );
                    return;
                }

                // Payload POST
                const payload = {
                    id_data_sertifikasi_asesi: idSertifikasi,
                    // Mengirim nilai 1 (true) atau 0 (false)
                    penjelasan_banding: p1.value,
                    diskusi_dengan_asesor: p2.value,
                    melibatkan_orang_lain: p3.value,
                    alasan_banding: alasanBandingEl.value.trim()
                };

                btnKirim.disabled = true;
                btnKirim.innerText = "Mengirim...";

                try {
                    // KRITIS: URL API POST SAMA DENGAN AK.01: /api/v1/banding/{idSertifikasi}
                    const response = await fetch(`/api/v1/banding/${idSertifikasi}`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrfToken
                        },
                        body: JSON.stringify(payload)
                    });

                    const result = await response.json();

                    if (!response.ok || !result.success) {
                        throw new Error(result.message || "Gagal menyimpan data");
                    }

                    alert("Data Banding berhasil disimpan!");
                    // KRITIS: Menggunakan ID Jadwal dari respons POST untuk redirect ke tracker
                    window.location.href = `/tracker/${result.id_jadwal}`;

                } catch (err) {
                    console.error(err);
                    alert("Gagal menyimpan: " + err.message);
                }

                btnKirim.disabled = false;
                btnKirim.innerText = "Kirim";
            });


            /* ========== 4. TOMBOL SEBELUMNYA (Sudah Benar) ========== */
            btnSebelumnya.addEventListener('click', () => {
                window.location.href = prevUrl;
            });

        });
    </script>

</body>

</html>
