<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Asesmen dan Kerahasiaan</title>
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- SWEETALERT --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        input[type="checkbox"]:disabled {
            opacity: 1 !important;
            cursor: default;
            color: #2563eb;
        }
        input[type="checkbox"]:disabled+span {
            color: #374151;
        }
    </style>
</head>

{{-- [PERBAIKAN] Tambahkan tag BODY dan pindahkan class flex ke sini --}}
<body class="bg-gray-50 md:bg-gray-100 font-sans">
    
    <div class="flex min-h-screen flex-col md:flex-row md:h-screen md:overflow-hidden">

        {{-- Sidebar --}}
        <div class="hidden md:block md:w-80 flex-shrink-0">
            <x-sidebar2 :idAsesi="$asesi->id_asesi" :sertifikasi="$sertifikasi" />
        </div>

        {{-- Mobile Header --}}
        @php
            $gambarSkema = null;
            if ($sertifikasi->jadwal && $sertifikasi->jadwal->skema && $sertifikasi->jadwal->skema->gambar) {
                $gambarSkema = asset('storage/' . $sertifikasi->jadwal->skema->gambar);
            }
        @endphp

        <x-mobile_header :title="'Persetujuan Asesmen dan Kerahasiaan'" 
            :code="$sertifikasi->jadwal->skema->kode_unit ?? ($sertifikasi->jadwal->skema->nomor_skema ?? '-')" 
            :name="$asesi->nama_lengkap ?? Auth::user()->name"
            :image="$gambarSkema" :sertifikasi="$sertifikasi" />

        {{-- Main Content --}}
        <main class="flex-1 p-6 md:p-12 bg-white overflow-y-auto" data-sertifikasi-id="{{ $id_sertifikasi }}">
            <div class="max-w-4xl mx-auto">

                <h1 class="text-2xl md:text-4xl font-bold text-gray-900 mb-8">Persetujuan Asesmen dan Kerahasiaan</h1>

                <p class="text-gray-700 mb-8 text-sm">
                    Persetujuan Asesmen ini untuk menjamin bahwa Peserta telah diberi arahan secara rinci tentang
                    perencanaan dan proses asesmen.
                </p>
                <hr class="my-8 border-gray-300">

                <dl class="grid grid-cols-1 md:grid-cols-4 gap-y-6 text-sm">
                    {{-- TUK --}}
                    <dt class="col-span-1 font-medium text-gray-800">TUK</dt>
                    <dd class="col-span-3 flex flex-wrap gap-x-6 gap-y-2 items-center">
                        <label class="flex items-center text-gray-700 cursor-default">
                            <input type="checkbox" id="tuk_Sewaktu" disabled class="w-4 h-4 rounded border-gray-300 mr-2">
                            <span>Sewaktu</span>
                        </label>
                        <label class="flex items-center text-gray-700 cursor-default">
                            <input type="checkbox" id="tuk_Tempat Kerja" disabled class="w-4 h-4 rounded border-gray-300 mr-2">
                            <span>Tempat Kerja</span>
                        </label>
                        <label class="flex items-center text-gray-700 cursor-default">
                            <input type="checkbox" id="tuk_Mandiri" disabled class="w-4 h-4 rounded border-gray-300 mr-2">
                            <span>Mandiri</span>
                        </label>
                    </dd>

                    {{-- Nama --}}
                    <dt class="col-span-1 font-medium text-gray-800">Nama Asesor</dt>
                    <dd class="col-span-3 text-gray-800 font-semibold">: <span id="nama_asesor">...</span></dd>

                    <dt class="col-span-1 font-medium text-gray-800">Nama Asesi</dt>
                    <dd class="col-span-3 text-gray-800 font-semibold">: <span id="nama_asesi">...</span></dd>

                    {{-- Bukti --}}
                    <dt class="col-span-1 font-medium text-gray-800 pt-1">Bukti yang akan dikumpulkan</dt>
                    <dd class="col-span-3">
                        <div id="container_bukti" class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-6">
                            <span class="text-gray-400 text-xs">Memuat daftar bukti...</span>
                        </div>
                    </dd>
                </dl>

                {{-- Pernyataan --}}
                <div class="mt-10 bg-blue-50 p-4 rounded-md border border-blue-100">
                    <p class="text-gray-700 text-sm leading-relaxed mb-2">
                        <span class="font-bold">1.</span> Bahwa saya sudah mendapatkan penjelasan mengenai Hak dan
                        Prosedur Banding oleh Asesor.
                    </p>
                    <p class="text-gray-700 text-sm leading-relaxed">
                        <span class="font-bold">2.</span> Saya setuju mengikuti asesmen dengan pemahaman bahwa informasi
                        yang dikumpulkan hanya digunakan untuk pengembangan profesional dan hanya dapat diakses oleh
                        orang tertentu saja.
                    </p>
                </div>

                {{-- Tanda Tangan --}}
                <div class="mt-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan Asesi</label>
                    <div class="w-full h-48 bg-white border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center overflow-hidden relative"
                        id="ttd_container">
                        <p id="ttd_placeholder" class="text-gray-400 text-sm">Memuat tanda tangan...</p>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end items-center mt-10 mb-10">
                    <button type="button" id="tombol-selanjutnya" disabled
                        class="w-48 py-3 bg-gray-400 text-white font-semibold rounded-full shadow-md cursor-not-allowed transition-all text-center">
                        Setuju
                    </button>
                </div>

            </div>
        </main>
    </div>

    {{-- JAVASCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const mainEl = document.querySelector('main[data-sertifikasi-id]');
            const idSertifikasi = mainEl ? mainEl.dataset.sertifikasiId : null;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const userRole = "{{ Auth::user()->role->nama_role ?? '' }}"; 

            const ttdContainer = document.getElementById('ttd_container');
            const btnSelanjutnya = document.getElementById('tombol-selanjutnya');
            const containerBukti = document.getElementById('container_bukti');

            if (!idSertifikasi) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'ID Sertifikasi hilang.' });
                return;
            }

            // --- LOAD DATA ---
            fetch(`/api/v1/kerahasiaan/${idSertifikasi}`)
                .then(response => response.json())
                .then(resp => {
                    if (!resp.success) throw new Error(resp.message);
                    const data = resp;

                    document.getElementById('nama_asesor').innerText = data.asesor.nama_lengkap || '-';
                    document.getElementById('nama_asesi').innerText = data.asesi.nama_lengkap;

                    // TUK Checkbox
                    const tukValue = data.tuk;
                    const tukEl = document.getElementById(`tuk_${tukValue}`);
                    if (tukEl) tukEl.checked = true;

                    // Bukti Checkbox
                    containerBukti.innerHTML = '';
                    if (data.master_bukti && data.master_bukti.length > 0) {
                        data.master_bukti.forEach(item => {
                            const isChecked = data.respon_bukti.includes(item.id_bukti_ak01);
                            const html = `
                                <label class="flex items-center text-gray-700 cursor-default">
                                    <input type="checkbox" class="w-4 h-4 rounded border-gray-300 mr-2" ${isChecked ? 'checked' : ''} disabled>
                                    <span>${item.bukti}</span>
                                </label>`;
                            containerBukti.innerHTML += html;
                        });
                    } else {
                        containerBukti.innerHTML = '<span class="text-red-500 text-xs">Bukti kosong.</span>';
                    }

                    // --- [FIX] TANDA TANGAN ---
                    if (data.tanda_tangan_valid && data.asesi.tanda_tangan_base64) {
                        const img = document.createElement('img');
                        
                        // PERBAIKAN: Langsung pakai data dari API karena sudah mengandung prefix 'data:image/...'
                        img.src = data.asesi.tanda_tangan_base64; 
                        
                        img.alt = "Tanda Tangan Asesi";
                        img.className = "max-h-full max-w-full object-contain";

                        ttdContainer.innerHTML = '';
                        ttdContainer.appendChild(img);

                        btnSelanjutnya.disabled = false;
                        btnSelanjutnya.classList.remove('bg-gray-400', 'cursor-not-allowed');
                        btnSelanjutnya.classList.add('bg-blue-500', 'hover:bg-blue-600');
                    } else {
                        ttdContainer.innerHTML = `
                            <div class="text-center">
                                <p class="text-red-500 font-medium">Tanda tangan belum tersedia.</p>
                                <a href="/asesi/profil" class="text-blue-600 underline text-sm">Lengkapi Profil</a>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    Swal.fire({ icon: 'error', title: 'Gagal Memuat Data', text: error.message });
                });


            // --- SIMPAN DATA ---
            btnSelanjutnya.addEventListener('click', async function () {
                const result = await Swal.fire({
                    title: 'Konfirmasi',
                    text: "Anda menyetujui kerahasiaan dan asesmen ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#2563EB',
                    confirmButtonText: 'Ya, Setuju',
                    cancelButtonText: 'Batal'
                });

                if (!result.isConfirmed) return;

                this.innerText = 'Menyimpan...';
                this.disabled = true;

                fetch(`/api/v1/kerahasiaan/${idSertifikasi}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Disimpan.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            if (userRole === 'asesor') {
                                window.location.href = `/asesor/tracker/${idSertifikasi}`;
                            } else {
                                window.location.href = `/asesi/tracker/${data.id_jadwal}`;
                            }
                        });
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: error.message });
                    this.innerText = 'Setuju';
                    this.disabled = false;
                });
            });

        });
    </script>
</body>
</html>