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
        }

        /* Animasi untuk modal */
        .modal {
            transition: opacity 0.25s ease;
        }

        body.modal-active {
            overflow-x: hidden;
            overflow-y: visible !important;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans antialiased">

    <div class="flex min-h-screen">

        {{-- ================================================= --}}
        {{-- BAGIAN 1: SIDEBAR (Komponen X-Sidebar2 Asli Anda) --}}
        <div class="sticky top-0 h-screen z-10">
            <x-sidebar :idAsesi="$asesi->id_asesi" :sertifikasi="$sertifikasi ?? null" />
        </div>

        {{-- Main Content --}}
        <main class="flex-1 p-8 md:p-12 bg-white overflow-y-auto" data-sertifikasi-id="{{ $id_sertifikasi }}">
            <div class="max-w-4xl mx-auto">

                <div class="mb-10 border-b-2 border-gray-800 pb-4 text-center">
                    <h1 class="text-4xl font-bold text-gray-900">Banding Asesmen</h1>
                </div>

                {{-- Data TUK, Asesor, Asesi, Skema (Read-Only) --}}
                <dl class="grid grid-cols-1 md:grid-cols-4 gap-y-4 text-sm mb-12">

                    {{-- 1. TUK --}}
                    <dt class="col-span-1 font-medium text-gray-800">TUK</dt>
                    <dd class="col-span-3 flex flex-wrap gap-x-6 gap-y-2 items-center" id="tuk_container">
                        <label class="flex items-center text-gray-700 cursor-default">
                            <input type="checkbox" id="tuk_Sewaktu" disabled
                                class="w-4 h-4 rounded border-gray-300 mr-2">
                            <span>Sewaktu</span>
                        </label>
                        <label class="flex items-center text-gray-700 cursor-default">
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
                    <dd class="col-span-3 text-gray-800 font-semibold">: <span id="nama_asesor">Memuat...</span></dd>

                    {{-- 3. Nama Asesi --}}
                    <dt class="col-span-1 font-medium text-gray-800">Nama Asesi</dt>
                    <dd class="col-span-3 text-gray-800 font-semibold">: <span id="nama_asesi">Memuat...</span></dd>

                    {{-- 4. Skema --}}
                    <dt class="col-span-1 font-medium text-gray-800">Skema</dt>
                    <dd class="col-span-3 text-gray-800 font-semibold">: <span id="skema_judul">Memuat...</span></dd>

                </dl>

                <div class="text-sm text-gray-700 mb-4">Jawablah dengan Ya atau Tidak pertanyaan-pertanyaan berikut ini:
                </div>

                {{-- Tabel Komponen Banding --}}
                <div class="shadow border border-gray-200 rounded-lg overflow-hidden mb-8">
                    <table class="min-w-full" id="tabel_banding">
                        <thead class="bg-gray-900 text-white">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">
                                    Komponen</th>
                                <th scope="col"
                                    class="px-6 py-4 text-center text-sm font-bold uppercase tracking-wider w-24">Ya
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-center text-sm font-bold uppercase tracking-wider w-24">Tidak
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            {{-- Pertanyaan 1 --}}
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-700">Apakah Proses Banding telah dijelaskan
                                    kepada Anda?</td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex justify-center">
                                        <input type="checkbox" name="penjelasan_banding" value="1"
                                            class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex justify-center">
                                        <input type="checkbox" name="penjelasan_banding" value="0"
                                            class="w-5 h-5 text-red-600 rounded border-gray-300 focus:ring-red-500">
                                    </div>
                                </td>
                            </tr>
                            {{-- Pertanyaan 2 --}}
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-700">Apakah Anda telah mendiskusikan Banding
                                    dengan Asesor?</td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex justify-center">
                                        <input type="checkbox" name="diskusi_dengan_asesor" value="1"
                                            class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex justify-center">
                                        <input type="checkbox" name="diskusi_dengan_asesor" value="0"
                                            class="w-5 h-5 text-red-600 rounded border-gray-300 focus:ring-red-500">
                                    </div>
                                </td>
                            </tr>
                            {{-- Pertanyaan 3 --}}
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-700">Apakah Anda mau melibatkan 'orang lain'
                                    membantu Anda dalam Proses Banding?</td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex justify-center">
                                        <input type="checkbox" name="melibatkan_orang_lain" value="1"
                                            class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex justify-center">
                                        <input type="checkbox" name="melibatkan_orang_lain" value="0"
                                            class="w-5 h-5 text-red-600 rounded border-gray-300 focus:ring-red-500">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Data Skema Bawah --}}
                <div class="mt-8 text-sm text-gray-700 space-y-2">
                    <p>Banding ini diajukan atas Keputusan Asesmen yang dibuat terhadap Skema Sertifikasi Okupasi
                        Nasional berikut:</p>
                    <p>Skema Sertifikasi: <span class="font-medium text-gray-900"
                            id="judul_skema_bawah">Memuat...</span>
                    </p>
                    <p>No. Skema Sertifikasi: <span class="font-medium text-gray-900"
                            id="nomor_skema_bawah">Memuat...</span>
                    </p>
                </div>

                {{-- Alasan Banding --}}
                <div class="mt-8">
                    <label for="alasan_banding" class="text-sm font-medium text-gray-800">Banding ini diajukan atas
                        alasan sebagai berikut:</label>
                    <textarea id="alasan_banding" rows="5" placeholder="Berikan Keterangan Disini"
                        class="mt-3 w-full border-2 border-gray-400 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm p-3"></textarea>
                </div>

                {{-- Tanda Tangan --}}
                <div class="mt-8">
                    <p class="mt-4 text-gray-700 text-sm leading-relaxed mb-6 max-w-2xl">
                        Anda mempunyai hak mengajukan banding jika Anda menilai proses asesmen tidak sesuai SOP dan
                        tidak memenuhi Prinsip Asesmen.
                    </p>

                    <div class="w-full flex flex-col items-center">
                        {{-- Request No. 4: Tanda tangan dikecilin (pake w-64 atau max-w-sm) --}}
                        <label class="block text-sm font-bold text-gray-800 mb-2">Tanda Tangan Asesi</label>
                        <div class="w-full md:w-80 h-40 bg-white border-2 border-dashed border-gray-400 rounded-lg flex items-center justify-center overflow-hidden relative mx-auto"
                            id="ttd_container">
                            <p id="ttd_placeholder" class="text-gray-400 text-sm">Memuat tanda tangan...</p>
                        </div>
                    </div>
                    {{-- Request No. 5 & 6: Tulisan "Tanda tangan disini" dan Tombol Hapus DIHAPUS --}}
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end mt-12 pb-10">
                    {{-- Request No. 7: Tombol Sebelumnya DIHAPUS --}}

                    {{-- Tombol Kirim memicu Modal --}}
                    <button id="btn_trigger_modal"
                        class="px-10 py-3 bg-blue-600 text-white font-semibold rounded-full hover:bg-blue-700 shadow-md transition-colors transform hover:scale-105">
                        Kirim Banding
                    </button>
                </div>

            </div>
        </main>
    </div>

    <div id="confirmation_modal"
        class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center z-50">
        <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

        <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">

            <div class="modal-content py-4 text-left px-6">
                <div class="flex justify-between items-center pb-3">
                    <p class="text-2xl font-bold text-gray-900">Konfirmasi Banding</p>
                    <div class="modal-close cursor-pointer z-50" id="close_modal_x">
                        <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18"
                            height="18" viewBox="0 0 18 18">
                            <path
                                d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                            </path>
                        </svg>
                    </div>
                </div>

                <div class="my-5">
                    <p class="text-gray-700">
                        Apakah Anda yakin untuk mengirim banding ini? Banding ini hanya dilakukan satu kali. Jika Anda
                        mengirimnya, Anda tidak dapat mengubahnya lagi.
                    </p>
                </div>

                <div class="flex justify-end pt-2 space-x-4">
                    <button id="close_modal_btn"
                        class="px-4 py-2 bg-gray-200 p-3 rounded-lg text-gray-700 hover:bg-gray-300 font-medium">Batal</button>
                    <button id="btn_kirim_final"
                        class="px-4 py-2 bg-blue-600 p-3 rounded-lg text-white hover:bg-blue-700 font-medium">Ya, Kirim
                        Banding</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async function() {

            // Ambil elemen dan token
            const mainEl = document.querySelector('main[data-sertifikasi-id]');
            const idSertifikasi = mainEl.dataset.sertifikasiId;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            // Elemen Display Data
            const namaAsesorEl = document.getElementById('nama_asesor');
            const namaAsesiEl = document.getElementById('nama_asesi');
            const skemaJudulEl = document.getElementById('skema_judul');
            const skemaJudulBawahEl = document.getElementById('judul_skema_bawah');
            const skemaNomorBawahEl = document.getElementById('nomor_skema_bawah');

            // Elemen Form & TTD
            const ttdContainer = document.getElementById('ttd_container');
            const alasanBandingEl = document.getElementById('alasan_banding');

            // Modal Elements
            const modal = document.getElementById('confirmation_modal');
            const btnTriggerModal = document.getElementById('btn_trigger_modal');
            const btnCloseModalX = document.getElementById('close_modal_x');
            const btnCloseModalBtn = document.getElementById('close_modal_btn');
            const btnKirimFinal = document.getElementById('btn_kirim_final');

            /* ========== CHECKBOX SINGLE CHOICE ========== */
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

            /* ========== LOGIC MODAL ========== */
            function toggleModal() {
                modal.classList.toggle('opacity-0');
                modal.classList.toggle('pointer-events-none');
                document.body.classList.toggle('modal-active');
            }

            // Trigger Modal (Validasi dulu sebelum buka modal)
            btnTriggerModal.addEventListener('click', function(e) {
                e.preventDefault();

                // Cek Validasi Input dulu
                const p1 = document.querySelector('input[name="penjelasan_banding"]:checked');
                const p2 = document.querySelector('input[name="diskusi_dengan_asesor"]:checked');
                const p3 = document.querySelector('input[name="melibatkan_orang_lain"]:checked');

                if (!p1 || !p2 || !p3 || !alasanBandingEl.value.trim()) {
                    alert(
                        'Mohon lengkapi semua data banding (3 pertanyaan Ya/Tidak dan Alasan Banding) sebelum mengirim.');
                    return;
                }

                // Kalau valid, buka modal
                toggleModal();
            });

            // Tutup Modal
            btnCloseModalX.addEventListener('click', toggleModal);
            btnCloseModalBtn.addEventListener('click', toggleModal);


            /* ========== LOAD DATA API (GET) ========== */
            try {
                const response = await fetch(`/api/v1/banding/${idSertifikasi}`);
                const resp = await response.json();

                if (!response.ok || !resp.success) {
                    throw new Error(`[${response.status}] ${resp.message || "Gagal memuat data read-only."}`);
                }

                const data = resp.data;
                const ak04 = data.respon_ak04 || {};

                namaAsesorEl.innerText = data.asesor.nama_lengkap ?? "-";
                namaAsesiEl.innerText = data.asesi.nama_lengkap ?? "-";
                skemaJudulEl.innerText =
                    `${data.jadwal.skema.nama_skema ?? "-"} (${data.jadwal.skema.nomor_skema ?? "-"})`;

                // TUK
                const tukLokasiTanpaSpasi = data.tuk_lokasi.replace(' ', '');
                const tukCheckbox = document.getElementById(`tuk_${tukLokasiTanpaSpasi}`);
                if (tukCheckbox) tukCheckbox.checked = true;

                skemaJudulBawahEl.innerText = data.jadwal.skema.nama_skema ?? "-";
                skemaNomorBawahEl.innerText = data.jadwal.skema.nomor_skema ?? "-";

                // Tanda Tangan
                if (data.asesi.tanda_tangan) {
                    const img = document.createElement("img");
                    img.src = `/${data.asesi.tanda_tangan}`;
                    img.className = "max-h-full max-w-full object-contain";
                    ttdContainer.innerHTML = "";
                    ttdContainer.appendChild(img);
                } else {
                    ttdContainer.innerHTML = `<p class="text-red-500 text-sm">Tanda tangan belum tersedia.</p>`;
                }

                // Load Jawaban Sebelumnya
                if (ak04.id_respon_ak04) {
                    alasanBandingEl.value = ak04.alasan_banding ?? "";
                    document.querySelectorAll('#tabel_banding input[name]').forEach(cb => {
                        const cbValue = cb.value;
                        if (cb.name === "penjelasan_banding" && cbValue == ak04.penjelasan_banding) cb
                            .checked = true;
                        if (cb.name === "diskusi_dengan_asesor" && cbValue == ak04
                            .diskusi_dengan_asesor) cb.checked = true;
                        if (cb.name === "melibatkan_orang_lain" && cbValue == ak04
                            .melibatkan_orang_lain) cb.checked = true;
                    });

                    // Kalau sudah ada data, disable tombol kirim biar gak double submit (opsional)
                    // btnTriggerModal.disabled = true;
                    // btnTriggerModal.classList.add('opacity-50', 'cursor-not-allowed');
                }

            } catch (err) {
                console.error("Load data error:", err);
                alert("Gagal memuat data banding: " + err.message);
            }


            /* ========== SIMPAN DATA API (POST) - DI DALAM MODAL ========== */
            // Logika ini dipindah dari tombol lama ke tombol 'Ya, Kirim' di dalam modal
            btnKirimFinal.addEventListener('click', async function() {

                // Kita ambil lagi datanya (sudah divalidasi saat buka modal, tapi ambil lagi buat payload)
                const p1 = document.querySelector('input[name="penjelasan_banding"]:checked');
                const p2 = document.querySelector('input[name="diskusi_dengan_asesor"]:checked');
                const p3 = document.querySelector('input[name="melibatkan_orang_lain"]:checked');

                const payload = {
                    id_data_sertifikasi_asesi: idSertifikasi,
                    penjelasan_banding: p1.value,
                    diskusi_dengan_asesor: p2.value,
                    melibatkan_orang_lain: p3.value,
                    alasan_banding: alasanBandingEl.value.trim()
                };

                // UI Loading state
                btnKirimFinal.disabled = true;
                btnKirimFinal.innerText = "Mengirim...";

                try {
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

                    toggleModal(); // Tutup modal
                    alert("Data Banding berhasil disimpan!");
                    window.location.href = `/asesi/tracker/${result.id_jadwal}`;

                } catch (err) {
                    console.error(err);
                    alert("Gagal menyimpan: " + err.message);
                    btnKirimFinal.disabled = false;
                    btnKirimFinal.innerText = "Ya, Kirim Banding";
                }
            });

        });
    </script>

</body>

</html>
