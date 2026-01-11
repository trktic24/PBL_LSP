@use('App\Models\DataSertifikasiAsesi')

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banding Asesmen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- FONT POPPINS --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* Terapkan Poppins ke seluruh body */
        body { font-family: 'Poppins', sans-serif; }
        
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

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
        .modal { transition: opacity 0.25s ease; }
        body.modal-active { overflow-x: hidden; overflow-y: visible !important; }
    </style>
</head>

<body class="bg-gray-50 md:bg-gray-100 font-sans">

    {{-- WRAPPER UTAMA --}}
    <div class="flex min-h-screen flex-col md:flex-row md:h-screen md:overflow-hidden">

        {{-- 1. SIDEBAR (Desktop Only) --}}
        <div class="hidden md:block md:w-80 flex-shrink-0">
            <x-sidebar :idAsesi="$asesi->id_asesi" :sertifikasi="$sertifikasi ?? null" />
        </div>

        {{-- 2. HEADER MOBILE --}}
        @php
            $gambarSkema = null;
            if (isset($sertifikasi) && $sertifikasi->jadwal && $sertifikasi->jadwal->skema && $sertifikasi->jadwal->skema->gambar) {
                 $gambarSkema = asset('storage/' . $sertifikasi->jadwal->skema->gambar);
            }
        @endphp

        <x-mobile_header
            :title="'Banding Asesmen'"
            :code="$sertifikasi->jadwal->skema->kode_unit ?? $sertifikasi->jadwal->skema->nomor_skema ?? '-'"
            :name="$asesi->nama_lengkap ?? Auth::user()->name"
            :image="$gambarSkema"
            :sertifikasi="$sertifikasi"
        />

        {{-- 3. MAIN CONTENT --}}
        <main class="flex-1 w-full relative md:p-12 md:overflow-y-auto bg-gray-50 md:bg-white z-0" 
              data-sertifikasi-id="{{ $id_sertifikasi }}">
            
            {{-- CONTAINER RESPONSIF (Card Style) --}}
            <div class="max-w-4xl mx-auto mt-16 md:mt-0 p-6 md:p-0 transition-all bg-white rounded-t-[40px] md:rounded-none md:bg-transparent min-h-screen md:min-h-0 shadow-2xl md:shadow-none">

                {{-- JUDUL HALAMAN --}}
                <h1 class="text-2xl md:text-4xl font-bold text-gray-900 mb-2 md:mb-4 pt-4 md:pt-0 text-center md:text-left">
                    Formulir Banding
                </h1>
                <p class="text-gray-600 mb-8 text-sm md:text-base text-center md:text-left">
                    Silakan lengkapi formulir banding di bawah ini jika Anda merasa keputusan asesmen tidak sesuai.
                </p>

                {{-- INFO BOX (TUK, ASESOR, ASESI) --}}
                <div class="bg-[#F9F6E6] border-[#EAE5C8] md:bg-amber-50 md:border-amber-200 border rounded-lg p-6 mb-8 text-sm">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-y-3">
                        {{-- TUK --}}
                        <div class="col-span-1 font-semibold text-gray-800">TUK</div>
                        <div class="col-span-3 flex flex-wrap gap-x-6 gap-y-2 items-center" id="tuk_container">
                            <label class="flex items-center text-gray-700 cursor-default">
                                <input type="checkbox" id="tuk_Sewaktu" disabled class="w-4 h-4 rounded border-gray-300 mr-2">
                                <span>Sewaktu</span>
                            </label>
                            <label class="flex items-center text-gray-700 cursor-default">
                                <input type="checkbox" id="tuk_TempatKerja" disabled class="w-4 h-4 rounded border-gray-300 mr-2">
                                <span>Tempat Kerja</span>
                            </label>
                            <label class="flex items-center text-gray-700 cursor-default">
                                <input type="checkbox" id="tuk_Mandiri" disabled class="w-4 h-4 rounded border-gray-300 mr-2">
                                <span>Mandiri</span>
                            </label>
                        </div>

                        {{-- Nama Asesor --}}
                        <div class="col-span-1 font-semibold text-gray-800">Nama Asesor</div>
                        <div class="col-span-3 text-gray-800">: <span id="nama_asesor" class="font-medium">Memuat...</span></div>

                        {{-- Nama Asesi --}}
                        <div class="col-span-1 font-semibold text-gray-800">Nama Asesi</div>
                        <div class="col-span-3 text-gray-800">: <span id="nama_asesi" class="font-medium">Memuat...</span></div>

                         {{-- Skema --}}
                         <div class="col-span-1 font-semibold text-gray-800">Skema</div>
                         <div class="col-span-3 text-gray-800">: <span id="skema_judul" class="font-medium">Memuat...</span></div>
                    </div>
                </div>

                <div class="text-sm text-gray-700 mb-4 font-medium">
                    Jawablah dengan <b>Ya</b> atau <b>Tidak</b> pertanyaan-pertanyaan berikut ini:
                </div>

                {{-- TABEL BANDING --}}
                <div class="mb-8 border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200" id="tabel_banding">
                        <thead class="bg-black text-white">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs md:text-sm font-bold uppercase tracking-wider">Komponen</th>
                                <th scope="col" class="px-4 py-3 text-center text-xs md:text-sm font-bold uppercase tracking-wider w-20">Ya</th>
                                <th scope="col" class="px-4 py-3 text-center text-xs md:text-sm font-bold uppercase tracking-wider w-20">Tidak</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            {{-- Pertanyaan 1 --}}
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-700">Apakah Proses Banding telah dijelaskan kepada Anda?</td>
                                <td class="px-6 py-4 align-middle text-center">
                                    <input type="checkbox" name="penjelasan_banding" value="1" class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 cursor-pointer">
                                </td>
                                <td class="px-6 py-4 align-middle text-center">
                                    <input type="checkbox" name="penjelasan_banding" value="0" class="w-5 h-5 text-red-600 rounded border-gray-300 focus:ring-red-500 cursor-pointer">
                                </td>
                            </tr>
                            {{-- Pertanyaan 2 --}}
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-700">Apakah Anda telah mendiskusikan Banding dengan Asesor?</td>
                                <td class="px-6 py-4 align-middle text-center">
                                    <input type="checkbox" name="diskusi_dengan_asesor" value="1" class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 cursor-pointer">
                                </td>
                                <td class="px-6 py-4 align-middle text-center">
                                    <input type="checkbox" name="diskusi_dengan_asesor" value="0" class="w-5 h-5 text-red-600 rounded border-gray-300 focus:ring-red-500 cursor-pointer">
                                </td>
                            </tr>
                            {{-- Pertanyaan 3 --}}
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-700">Apakah Anda mau melibatkan 'orang lain' membantu Anda dalam Proses Banding?</td>
                                <td class="px-6 py-4 align-middle text-center">
                                    <input type="checkbox" name="melibatkan_orang_lain" value="1" class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 cursor-pointer">
                                </td>
                                <td class="px-6 py-4 align-middle text-center">
                                    <input type="checkbox" name="melibatkan_orang_lain" value="0" class="w-5 h-5 text-red-600 rounded border-gray-300 focus:ring-red-500 cursor-pointer">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- INFO SKEMA BAWAH --}}
                <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-100 text-sm">
                    <p class="text-gray-600 mb-2">Banding ini diajukan atas Keputusan Asesmen yang dibuat terhadap Skema Sertifikasi Okupasi Nasional berikut:</p>
                    <div class="flex flex-col gap-1">
                        <p><span class="text-gray-500 w-32 inline-block">Skema</span> : <span class="font-semibold text-gray-900" id="judul_skema_bawah">Memuat...</span></p>
                        <p><span class="text-gray-500 w-32 inline-block">Nomor</span> : <span class="font-semibold text-gray-900" id="nomor_skema_bawah">Memuat...</span></p>
                    </div>
                </div>

                {{-- ALASAN BANDING --}}
                <div class="mb-8">
                    <label for="alasan_banding" class="block text-sm font-bold text-gray-900 mb-2">Alasan Pengajuan Banding:</label>
                    <textarea id="alasan_banding" rows="5" placeholder="Jelaskan alasan Anda mengajukan banding di sini..."
                        class="w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm p-3 bg-white"></textarea>
                </div>

                {{-- TANDA TANGAN --}}
                <div class="mb-10 flex flex-col items-center justify-center p-6 border border-dashed border-gray-300 rounded-xl bg-gray-50">
                    <p class="text-gray-600 text-sm text-center mb-4 max-w-md">
                        Dengan ini saya menyatakan bahwa proses banding ini diajukan dengan sebenar-benarnya sesuai SOP.
                    </p>
                    <label class="block text-sm font-bold text-gray-800 mb-3">Tanda Tangan Asesi</label>
                    <div class="w-64 h-32 bg-white border border-gray-200 rounded-lg flex items-center justify-center overflow-hidden shadow-sm" id="ttd_container">
                        <p id="ttd_placeholder" class="text-gray-400 text-xs italic">Memuat tanda tangan...</p>
                    </div>
                </div>

                {{-- TOMBOL AKSI --}}
                <div class="flex justify-end items-center pb-20 md:pb-0 gap-4 mt-8 border-t border-gray-100 pt-6">
                     <button id="btn_trigger_modal"
                        class="w-full md:w-64 text-center px-8 py-3 bg-blue-600 text-white font-bold rounded-full hover:bg-blue-700 shadow-md transition-all shadow-blue-200">
                        Kirim Banding
                    </button>
                </div>

            </div>
        </main>
    </div>

    {{-- MODAL KONFIRMASI (Bawaan Kode Asli) --}}
    <div id="confirmation_modal" class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center z-50">
        <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
        <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded-2xl shadow-lg z-50 overflow-hidden transform transition-all">
            
            <div class="modal-content py-6 text-left px-6">
                {{-- Header Modal --}}
                <div class="flex justify-between items-center pb-3">
                    <p class="text-xl font-bold text-gray-900">Konfirmasi</p>
                    <div class="modal-close cursor-pointer z-50 p-2 hover:bg-gray-100 rounded-full transition-colors" id="close_modal_x">
                        <svg class="fill-current text-gray-500" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                            <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                        </svg>
                    </div>
                </div>

                {{-- Body Modal --}}
                <div class="my-4">
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Apakah Anda yakin untuk mengirim banding ini? Banding ini hanya dilakukan satu kali. Jika Anda mengirimnya, Anda tidak dapat mengubahnya lagi.
                    </p>
                </div>

                {{-- Footer Modal --}}
                <div class="flex justify-end pt-4 space-x-3">
                    <button id="close_modal_btn" class="px-5 py-2.5 bg-gray-100 rounded-full text-gray-700 hover:bg-gray-200 font-semibold text-sm transition-colors">
                        Batal
                    </button>
                    <button id="btn_kirim_final" class="px-5 py-2.5 bg-blue-600 rounded-full text-white hover:bg-blue-700 font-semibold text-sm transition-colors shadow-lg shadow-blue-200">
                        Ya, Kirim
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT --}}
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
                    alert('Mohon lengkapi semua data banding (3 pertanyaan Ya/Tidak dan Alasan Banding) sebelum mengirim.');
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
                skemaJudulEl.innerText = `${data.jadwal.skema.nama_skema ?? "-"} (${data.jadwal.skema.nomor_skema ?? "-"})`;

                // TUK
                if(data.tuk_lokasi) {
                    const tukLokasiTanpaSpasi = data.tuk_lokasi.replace(/\s/g, ''); // Hapus semua spasi
                    const tukCheckbox = document.getElementById(`tuk_${tukLokasiTanpaSpasi}`);
                    if (tukCheckbox) tukCheckbox.checked = true;
                }

                skemaJudulBawahEl.innerText = data.jadwal.skema.nama_skema ?? "-";
                skemaNomorBawahEl.innerText = data.jadwal.skema.nomor_skema ?? "-";

                // Tanda Tangan (Secure Access)
                if (data.asesi.tanda_tangan) {
                    const img = document.createElement("img");
                    
                    // --- PERBAIKAN DI SINI ---
                    // Karena backend udah ngirim Base64, langsung masukin ke src
                    img.src = data.asesi.tanda_tangan; 
                    
                    img.className = "max-h-full max-w-full object-contain";
                    ttdContainer.innerHTML = "";
                    ttdContainer.appendChild(img);
                } else {
                    ttdContainer.innerHTML = `<p class="text-red-500 text-xs">Tanda tangan tidak tersedia.</p>`;
                }

                // Load Jawaban Sebelumnya
                if (ak04.id_respon_ak04) {
                    alasanBandingEl.value = ak04.alasan_banding ?? "";
                    document.querySelectorAll('#tabel_banding input[name]').forEach(cb => {
                        const cbValue = cb.value;
                        if (cb.name === "penjelasan_banding" && cbValue == ak04.penjelasan_banding) cb.checked = true;
                        if (cb.name === "diskusi_dengan_asesor" && cbValue == ak04.diskusi_dengan_asesor) cb.checked = true;
                        if (cb.name === "melibatkan_orang_lain" && cbValue == ak04.melibatkan_orang_lain) cb.checked = true;
                    });
                }

            } catch (err) {
                console.error("Load data error:", err);
                alert("Gagal memuat data banding: " + err.message);
            }


            /* ========== SIMPAN DATA API (POST) - DI DALAM MODAL ========== */
            btnKirimFinal.addEventListener('click', async function() {

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
                btnKirimFinal.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg> Mengirim...`;

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
                    btnKirimFinal.innerText = "Ya, Kirim";
                }
            });

        });
    </script>

</body>
</html>