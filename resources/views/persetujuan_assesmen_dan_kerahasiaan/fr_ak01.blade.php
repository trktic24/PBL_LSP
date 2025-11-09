<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Asesmen dan Kerahasiaan (API)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        input[type="checkbox"]:disabled { opacity: 1; cursor: default; }
    </style>
</head>

<body class="bg-gray-100" data-asesi-id="{{ $id_asesi_untuk_js }}">

    <div class="flex min-h-screen">
        
        <x-sidebar :idAsesi="$id_asesi_untuk_js" />

        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-3xl mx-auto">
                
                <h1 class="text-4xl font-bold text-gray-900 mb-8">Persetujuan Asesmen dan Kerahasiaan</h1>
                
                <p class="text-gray-700 mb-8 text-sm">
                    Persetujuan Asesmen ini untuk menjamin bahwa Peserta telah diberi arahan secara rinci tentang perencanaan dan proses asesmen
                </p>
                <hr class="my-8 border-gray-300">
                <dl class="grid grid-cols-1 md:grid-cols-4 gap-y-6 text-sm">
                    <dt class="col-span-1 font-medium text-gray-800">TUK</dt>
                    <dd class="col-span-3 flex flex-wrap gap-x-6 gap-y-2 items-center">
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" value="Sewaktu" id="tuk_Sewaktu" disabled class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                            Sewaktu
                        </label>
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" value="Tempat Kerja" id="tuk_Tempat Kerja" disabled class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                            Tempat Kerja
                        </label>
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" value="Mandiri" id="tuk_Mandiri" disabled class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                            Mandiri
                        </label>
                    </dd>
                    
                    <dt class="col-span-1 font-medium text-gray-800">Nama Asesor</dt>
                    <dd class="col-span-3 text-gray-800">: <span id="nama_asesor">[Memuat Data...]</span></dd>

                    <dt class="col-span-1 font-medium text-gray-800">Nama Asesi</dt>
                    <dd class="col-span-3 text-gray-800">: <span id="nama_asesi">[Memuat Data...]</span></dd>
                    
                    <dt class="col-span-1 font-medium text-gray-800">Bukti yang akan dikumpulkan</dt>
                    <dd class="col-span-3 grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-4">
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" value="Verifikasi Portofolio" id="bukti_Verifikasi Portofolio" disabled class="w-4 h-4 ... mr-2">
                            Verifikasi Portofolio
                        </label>
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" value="Hasil Test Tulis" id="bukti_Hasil Test Tulis" disabled class="w-4 h-4 ... mr-2">
                            Hasil Test Tulis
                        </label>
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" value="Hasil Tes Lisan" id="bukti_Hasil Tes Lisan" disabled class="w-4 h-4 ... mr-2">
                            Hasil Tes Lisan
                        </label>
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" value="Hasil Wawancara" id="bukti_Hasil Wawancara" disabled class="w-4 h-4 ... mr-2">
                            Hasil Wawancara
                        </label>
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" value="Observasi Langsung" id="bukti_Observasi Langsung" disabled class="w-4 h-4 ... mr-2">
                            Observasi Langsung
                        </label>
                    </dd>
                </dl>
                
                <p class="mt-10 text-gray-700 text-sm leading-relaxed">
                    Bahwa saya sudah Mendapatkan Penjelasan Hak dan Prosedur Banding Oleh Asesor
                </p>
                <p class="mt-4 text-gray-700 text-sm leading-relaxed">
                    Saya setuju mengikuti asesmen dengan pemahaman bahwa informasi yang dikumpulkan hanya digunakan untuk pengembangan profesional dan hanya dapat diakses oleh orang tertentu saja.
                </p>
                <div class="mt-6">
                    <div class="w-full h-48 bg-gray-50 border border-gray-300 rounded-lg shadow-inner flex items-center justify-center overflow-hidden" id="ttd_container">
                        <p id="ttd_placeholder" class="text-gray-500 text-sm">Area Tanda Tangan</t/p>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <button type="button" id="tombol-hapus"
                                class="px-4 py-1.5 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                            Hapus
                        </button>
                    </div>
                </div>
                
                <div class="flex justify-end items-center mt-12">
                    <button type="button" id="tombol-selanjutnya"
                            class="px-8 py-3 bg-blue-500 text-white font-semibold rounded-full hover:bg-blue-600 shadow-md">
                        Selanjutnya
                    </button>
                </div>

            </div>
        </main>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- AMBIL DATA ---
        const asesiId = document.body.dataset.asesiId;
        const apiUrl = `/api/get-frak01-data/${asesiId}`; 
        const csrfToken = document.querySelector('input[name="_token"]')?.value; 
        const ttdContainer = document.getElementById('ttd_container');
        const ttdPlaceholder = document.getElementById('ttd_placeholder');

        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error); return;
                }
                
                // 1. ISI NAMA ASESOR & ASESI
                document.getElementById('nama_asesor').innerText = data.asesor.nama_lengkap;
                document.getElementById('nama_asesi').innerText = data.asesi.nama_lengkap;

                // 2. ISI TUK (DUMMY)
                const tukElement = document.getElementById(`tuk_${data.data_asesmen.tuk}`);
                if (tukElement) tukElement.checked = true;
                
                // 3. ISI BUKTI (DUMMY)
                data.data_asesmen.bukti_dikumpulkan.forEach(bukti => {
                    const buktiElement = document.getElementById(`bukti_${bukti.replace(/ /g, ' ')}`);
                    if (buktiElement) buktiElement.checked = true;
                });

                // 4. TAMPILKAN TANDA TANGAN (NGISI KE FRAME)
                if (data.asesi.tanda_tangan) {
                    const ttdPath = `{{ asset('') }}${data.asesi.tanda_tangan}`; 
                    const img = document.createElement('img');
                    img.src = ttdPath;
                    img.alt = "Tanda Tangan Asesi";
                    img.className = "object-contain h-full w-full p-2";
                    if(ttdPlaceholder) ttdPlaceholder.style.display = 'none';
                    ttdContainer.innerHTML = ''; 
                    ttdContainer.appendChild(img);
                } else {
                    if(ttdPlaceholder) {
                        ttdPlaceholder.style.display = 'block';
                        ttdPlaceholder.innerText = 'Tanda tangan belum tersedia.';
                    }
                }
            })
            .catch(error => {
                // ... (logic error lu) ...
            });


        // --- LOGIC TOMBOL "SETUJU" (SELANJUTNYA) ---
        const tombolSelanjutnya = document.getElementById('tombol-selanjutnya');
        
        tombolSelanjutnya.addEventListener('click', function() {
            if (!confirm('Dengan ini, saya menyetujui asesmen dan kerahasiaan. Lanjutkan?')) {
                return;
            }
            this.textContent = 'Menyimpan...';
            this.disabled = true;

            fetch(`/api/setuju-kerahasiaan/${asesiId}`, { 
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Persetujuan berhasil disimpan!');
                    window.location.href = '/tracker/' + asesiId; // <-- Pindahin ke tracker
                } else {
                    alert('Error: ' + data.message);
                    this.textContent = 'Selanjutnya';
                    this.disabled = false;
                }
            })
            .catch(error => {
                // ... (logic error catch) ...
            });
        });

        // --- LOGIC TOMBOL "HAPUS" PERMANEN ---
        const tombolHapus = document.getElementById('tombol-hapus');
        
        tombolHapus.addEventListener('click', function() {
            
            if (!confirm('Yakin mau hapus tanda tangan ini PERMANEN?')) {
                return; 
            }
            this.textContent = 'Menghapus...';
            this.disabled = true;

            fetch(`/api/ajax-hapus-tandatangan/${asesiId}`, { 
                method: 'POST', 
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message); 
                    ttdContainer.innerHTML = ''; 
                    if(ttdPlaceholder) {
                        ttdPlaceholder.innerText = 'Tanda tangan berhasil dihapus.';
                        ttdPlaceholder.style.display = 'block';
                    }
                } else {
                    alert('Error: ' + data.message);
                }
                this.textContent = 'Hapus';
                this.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal terhubung ke server. Coba lagi.');
                this.textContent = 'Hapus';
                this.disabled = false;
            });
        });
    });
</script>

</body>
</html>