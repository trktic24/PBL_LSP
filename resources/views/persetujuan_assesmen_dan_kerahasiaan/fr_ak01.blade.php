<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Asesmen dan Kerahasiaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Styling khusus checkbox disabled biar tetap terbaca */
        input[type="checkbox"]:disabled { 
            opacity: 1 !important; 
            cursor: default;
            color: #2563eb; /* Warna biru tetap menyala */
        }
        input[type="checkbox"]:disabled + span {
            color: #374151; /* Warna teks abu gelap */
        }
    </style>
</head>

<body class="bg-gray-100">

    <div class="flex h-screen overflow-hidden">
        
        {{-- Sidebar --}}
        <x-sidebar2 :idAsesi="$asesi->id_asesi" :sertifikasi="$sertifikasi" />

        {{-- PENTING: Simpan ID Sertifikasi di sini --}}
        <main class="flex-1 p-12 bg-white overflow-y-auto" data-sertifikasi-id="{{ $id_sertifikasi }}">
            <div class="max-w-4xl mx-auto">
                
                <h1 class="text-4xl font-bold text-gray-900 mb-8">Persetujuan Asesmen dan Kerahasiaan</h1>
                
                <p class="text-gray-700 mb-8 text-sm">
                    Persetujuan Asesmen ini untuk menjamin bahwa Peserta telah diberi arahan secara rinci tentang perencanaan dan proses asesmen.
                </p>
                <hr class="my-8 border-gray-300">
                
                <dl class="grid grid-cols-1 md:grid-cols-4 gap-y-6 text-sm">
                    
                    {{-- 1. TUK (Read Only) --}}
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
                    
                    {{-- 2. Data Nama --}}
                    <dt class="col-span-1 font-medium text-gray-800">Nama Asesor</dt>
                    <dd class="col-span-3 text-gray-800 font-semibold">: <span id="nama_asesor">...</span></dd>

                    <dt class="col-span-1 font-medium text-gray-800">Nama Asesi</dt>
                    <dd class="col-span-3 text-gray-800 font-semibold">: <span id="nama_asesi">...</span></dd>
                    
                    {{-- 3. Bukti (Read Only - Tampilan Sama dengan TUK) --}}
                    <dt class="col-span-1 font-medium text-gray-800 pt-1">Bukti yang akan dikumpulkan</dt>
                    <dd class="col-span-3">
                        {{-- Container Bukti (Grid tanpa border, mirip TUK) --}}
                        <div id="container_bukti" class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-6">
                            <span class="text-gray-400 text-xs">Memuat daftar bukti...</span>
                        </div>
                    </dd>
                </dl>
                
                {{-- Pernyataan --}}
                <div class="mt-10 bg-blue-50 p-4 rounded-md border border-blue-100">
                    <p class="text-gray-700 text-sm leading-relaxed mb-2">
                        <span class="font-bold">1.</span> Bahwa saya sudah mendapatkan penjelasan mengenai Hak dan Prosedur Banding oleh Asesor.
                    </p>
                    <p class="text-gray-700 text-sm leading-relaxed">
                        <span class="font-bold">2.</span> Saya setuju mengikuti asesmen dengan pemahaman bahwa informasi yang dikumpulkan hanya digunakan untuk pengembangan profesional dan hanya dapat diakses oleh orang tertentu saja.
                    </p>
                </div>

                {{-- 4. Tanda Tangan (View Only) --}}
                <div class="mt-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan Asesi</label>
                    <div class="w-full h-48 bg-white border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center overflow-hidden relative" id="ttd_container">
                        <p id="ttd_placeholder" class="text-gray-400 text-sm">Memuat tanda tangan...</p>
                    </div>
                </div>
                
                {{-- Tombol Aksi --}}
                <div class="flex justify-end items-center mt-12">
                    <button type="button" id="tombol-selanjutnya" disabled
                            class="px-8 py-3 bg-gray-400 text-white font-semibold rounded-full shadow-md cursor-not-allowed transition-all">
                        Setuju
                    </button>
                </div>

            </div>
        </main>
    </div>

    {{-- JAVASCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. Inisialisasi
            const mainEl = document.querySelector('main[data-sertifikasi-id]');
            const idSertifikasi = mainEl ? mainEl.dataset.sertifikasiId : null;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            
            const ttdContainer = document.getElementById('ttd_container');
            const btnSelanjutnya = document.getElementById('tombol-selanjutnya');
            const containerBukti = document.getElementById('container_bukti');

            if (!idSertifikasi) {
                alert("ID Sertifikasi tidak ditemukan.");
                return;
            }

            // 2. LOAD DATA DARI API
            fetch(`/api/v1/kerahasiaan/${idSertifikasi}`)
                .then(response => response.json())
                .then(resp => {
                    if (!resp.success) {
                        throw new Error(resp.message);
                    }
                    
                    const data = resp;
                    
                    // A. Isi Nama
                    document.getElementById('nama_asesor').innerText = data.asesor.nama_lengkap || '-';
                    document.getElementById('nama_asesi').innerText = data.asesi.nama_lengkap;

                    // B. Isi TUK
                    const tukValue = data.tuk; 
                    const tukEl = document.getElementById(`tuk_${tukValue}`);
                    if(tukEl) tukEl.checked = true;

                    // C. Render Checkbox Bukti (Gaya Minimalis - Read Only)
                    containerBukti.innerHTML = ''; 
                    if (data.master_bukti && data.master_bukti.length > 0) {
                        data.master_bukti.forEach(item => {
                            
                            // Cek apakah bukti ini ada di daftar 'respon_bukti'
                            const isChecked = data.respon_bukti.includes(item.id_bukti_ak01);
                            
                            // HTML Checkbox
                            const html = `
                                <label class="flex items-center text-gray-700 cursor-default">
                                    <input type="checkbox" 
                                           class="w-4 h-4 rounded border-gray-300 mr-2"
                                           ${isChecked ? 'checked' : ''} disabled>
                                    <span>${item.bukti}</span>
                                </label>
                            `;
                            containerBukti.innerHTML += html;
                        });
                    } else {
                        containerBukti.innerHTML = '<span class="text-red-500 text-xs">Data bukti tidak tersedia.</span>';
                    }

                    // D. Tampilkan Tanda Tangan
                    if (data.tanda_tangan_valid) {
                        const img = document.createElement('img');
                        img.src = `/${data.asesi.tanda_tangan}`; 
                        img.alt = "Tanda Tangan Asesi";
                        img.className = "max-h-full max-w-full object-contain";
                        
                        ttdContainer.innerHTML = ''; 
                        ttdContainer.appendChild(img);
                        
                        // Aktifkan Tombol
                        btnSelanjutnya.disabled = false;
                        btnSelanjutnya.classList.remove('bg-gray-400', 'cursor-not-allowed');
                        btnSelanjutnya.classList.add('bg-blue-500', 'hover:bg-blue-600');
                        
                    } else {
                        ttdContainer.innerHTML = `
                            <div class="text-center">
                                <p class="text-red-500 font-medium">Tanda tangan belum tersedia.</p>
                                <a href="/halaman-tanda-tangan/${idSertifikasi}" class="text-blue-600 underline text-sm">Klik di sini untuk tanda tangan</a>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Gagal memuat data: " + error.message);
                });


            // 3. LOGIC LANJUT (Tanpa Kirim Bukti)
            btnSelanjutnya.addEventListener('click', function() {
                
                if (!confirm('Dengan ini, saya menyetujui asesmen dan kerahasiaan. Lanjutkan?')) {
                    return;
                }

                this.innerText = 'Menyimpan...';
                this.disabled = true;

                // Kirim body kosong, karena controller cuma butuh ID dari URL buat update status
                fetch(`/api/v1/kerahasiaan/${idSertifikasi}`, { 
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': csrfToken, 
                        'Accept': 'application/json' 
                    },
                    body: JSON.stringify({}) 
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Persetujuan berhasil disimpan!');
                        window.location.href = `/tracker/${data.id_jadwal}`; 
                    } else {
                        throw new Error(data.message || 'Gagal menyimpan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + error.message);
                    this.innerText = 'Selanjutnya';
                    this.disabled = false;
                });
            });

        });
    </script>

</body>
</html>