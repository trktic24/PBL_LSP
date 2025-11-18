<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Asesmen dan Kerahasiaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Agar checkbox disabled tetap terlihat jelas warnanya */
        input[type="checkbox"]:disabled {
            opacity: 1;
            cursor: default;
            color: #2563eb; /* blue-600 */
            background-color: #f3f4f6; /* gray-100 */
        }
    </style>
</head>

{{-- Pastikan ID Asesi tersedia di sini --}}
<body class="bg-gray-100" data-asesi-id="1">

    <div>
        {{-- 1. Sidebar Component (Sama seperti halaman sebelumnya) --}}
        <x-sidebar.sidebar :idAsesi="1"></x-sidebar.sidebar>

        {{-- 2. Main Content Wrapper (Margin kiri untuk desktop) --}}
        <main class="flex-1 bg-white min-h-screen overflow-y-auto lg:ml-64 p-6 lg:p-12">

            {{-- Tombol Hamburger (Hanya muncul di HP) --}}
            <button id="hamburger-btn" type="button" class="lg:hidden text-gray-700 mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <div class="max-w-4xl mx-auto">


                {{-- Header --}}
                <h1 class="text-2xl lg:text-4xl font-bold text-gray-900 mb-4">Persetujuan Asesmen</h1>
                <p class="text-gray-600 mb-8">
                    Persetujuan ini untuk menjamin bahwa Peserta telah diberi arahan secara rinci tentang perencanaan dan proses asesmen.
                </p>

                {{-- 4. Data Card (Agar rapi seperti kotak upload sebelumnya) --}}
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-8 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Detail Pelaksanaan</h3>
                    
                    <dl class="grid grid-cols-1 md:grid-cols-4 gap-y-6 text-sm">
                        <dt class="col-span-1 font-medium text-gray-500">TUK</dt>
                        <dd class="col-span-3 flex flex-wrap gap-x-6 gap-y-2 items-center">
                            <label class="flex items-center text-gray-900 font-medium">
                                <input type="checkbox" value="Sewaktu" id="tuk_Sewaktu" disabled class="w-4 h-4 rounded border-gray-300 mr-2">
                                Sewaktu
                            </label>
                            <label class="flex items-center text-gray-900 font-medium">
                                <input type="checkbox" value="Tempat Kerja" id="tuk_Tempat Kerja" disabled class="w-4 h-4 rounded border-gray-300 mr-2">
                                Tempat Kerja
                            </label>
                            <label class="flex items-center text-gray-900 font-medium">
                                <input type="checkbox" value="Mandiri" id="tuk_Mandiri" disabled class="w-4 h-4 rounded border-gray-300 mr-2">
                                Mandiri
                            </label>
                        </dd>
                        
                        <dt class="col-span-1 font-medium text-gray-500">Nama Asesor</dt>
                        <dd class="col-span-3 text-gray-900 font-semibold block">: <span id="nama_asesor" class="animate-pulse">[Memuat...]</span></dd>
    
                        <dt class="col-span-1 font-medium text-gray-500">Nama Asesi</dt>
                        <dd class="col-span-3 text-gray-900 font-semibold block">: <span id="nama_asesi" class="animate-pulse">[Memuat...]</span></dd>
                        
                        <dt class="col-span-1 font-medium text-gray-500">Bukti yang dikumpulkan</dt>
                        <dd class="col-span-3 grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-4">
                            <label class="flex items-center text-gray-700">
                                <input type="checkbox" value="Verifikasi Portofolio" id="bukti_Verifikasi Portofolio" disabled class="w-4 h-4 rounded border-gray-300 mr-2"> Verifikasi Portofolio
                            </label>
                            <label class="flex items-center text-gray-700">
                                <input type="checkbox" value="Hasil Test Tulis" id="bukti_Hasil Test Tulis" disabled class="w-4 h-4 rounded border-gray-300 mr-2"> Hasil Test Tulis
                            </label>
                            <label class="flex items-center text-gray-700">
                                <input type="checkbox" value="Hasil Tes Lisan" id="bukti_Hasil Tes Lisan" disabled class="w-4 h-4 rounded border-gray-300 mr-2"> Hasil Tes Lisan
                            </label>
                            <label class="flex items-center text-gray-700">
                                <input type="checkbox" value="Hasil Wawancara" id="bukti_Hasil Wawancara" disabled class="w-4 h-4 rounded border-gray-300 mr-2"> Hasil Wawancara
                            </label>
                            <label class="flex items-center text-gray-700">
                                <input type="checkbox" value="Observasi Langsung" id="bukti_Observasi Langsung" disabled class="w-4 h-4 rounded border-gray-300 mr-2"> Observasi Langsung
                            </label>
                        </dd>
                    </dl>
                </div>
                
                {{-- Pernyataan --}}
                <div class="p-4 bg-blue-50 border border-blue-100 rounded-lg mb-6">
                    <p class="text-gray-800 text-sm leading-relaxed mb-2 font-medium">
                        Bahwa saya sudah mendapatkan penjelasan Hak dan Prosedur Banding oleh Asesor.
                    </p>
                    <p class="text-gray-700 text-sm leading-relaxed">
                        Saya setuju mengikuti asesmen dengan pemahaman bahwa informasi yang dikumpulkan hanya digunakan untuk pengembangan profesional dan hanya dapat diakses oleh orang tertentu saja.
                    </p>
                </div>

                {{-- Area Tanda Tangan --}}
                <div class="mt-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan Peserta</label>
                    <div class="w-full h-56 bg-white border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center overflow-hidden relative group hover:border-gray-400 transition-colors" id="ttd_container">
                        <p id="ttd_placeholder" class="text-gray-400 text-sm">Area Tanda Tangan akan muncul di sini</p>
                    </div>
                    
                    <div class="flex justify-between items-center mt-3">
                        <button type="button" id="tombol-hapus"
                                class="px-5 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition shadow-sm">
                            Hapus Tanda Tangan
                        </button>
                    </div>
                </div>
                
                {{-- Tombol Footer --}}
                <div class="flex justify-end items-center mt-10 pt-6 border-t border-gray-200">
                    {{-- CSRF Token Hidden --}}
                    @csrf
                    <button type="button" id="tombol-selanjutnya"
                            class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5">
                        Setuju & Selanjutnya
                    </button>
                </div>

            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // --- 1. LOGIKA SIDEBAR RESPONSIVE (Sama seperti halaman sebelumnya) ---
            const hamburgerBtn = document.getElementById('hamburger-btn');
            const sidebar = document.getElementById('sidebar');

            if (hamburgerBtn && sidebar) {
                hamburgerBtn.addEventListener('click', () => {
                    sidebar.classList.toggle('-translate-x-full');
                });
            }

            // --- 2. LOGIKA API & TANDA TANGAN ---
            const asesiId = document.body.dataset.asesiId;
            const apiUrl = `/api/get-frak01-data/${asesiId}`; 
            const csrfToken = document.querySelector('input[name="_token"]')?.value; 
            const ttdContainer = document.getElementById('ttd_container');
            const ttdPlaceholder = document.getElementById('ttd_placeholder');

            // Fetch Data
            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error); return;
                    }
                    
                    // Hapus animasi loading
                    document.getElementById('nama_asesor').classList.remove('animate-pulse');
                    document.getElementById('nama_asesi').classList.remove('animate-pulse');

                    // Isi Data Teks
                    document.getElementById('nama_asesor').innerText = data.asesor.nama_lengkap;
                    document.getElementById('nama_asesi').innerText = data.asesi.nama_lengkap;

                    // Isi Checkbox TUK
                    const tukElement = document.getElementById(`tuk_${data.data_asesmen.tuk}`);
                    if (tukElement) tukElement.checked = true;
                    
                    // Isi Checkbox Bukti
                    if(data.data_asesmen.bukti_dikumpulkan) {
                        data.data_asesmen.bukti_dikumpulkan.forEach(bukti => {
                            // Handle spasi di ID jika perlu (walaupun di HTML ID pake spasi itu valid tapi tricky di selector)
                            // Code asli lu pake replace, gw ikutin logic lu
                            const buktiElement = document.getElementById(`bukti_${bukti}`);
                            if (buktiElement) buktiElement.checked = true;
                        });
                    }

                    // Tampilkan Tanda Tangan
                    if (data.asesi.tanda_tangan) {
                        const ttdPath = `{{ asset('') }}${data.asesi.tanda_tangan}`; 
                        const img = document.createElement('img');
                        img.src = ttdPath;
                        img.alt = "Tanda Tangan Asesi";
                        img.className = "object-contain h-full w-full p-4";
                        
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
                    console.error('Error fetching data:', error);
                });


            // --- 3. LOGIC TOMBOL "SELANJUTNYA" ---
            const tombolSelanjutnya = document.getElementById('tombol-selanjutnya');
            
            tombolSelanjutnya.addEventListener('click', function() {
                if (!confirm('Dengan ini, saya menyetujui asesmen dan kerahasiaan. Lanjutkan?')) {
                    return;
                }
                
                // Loading state visual
                this.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...`;
                this.disabled = true;
                this.classList.add('opacity-75', 'cursor-not-allowed');

                fetch(`/api/setuju-kerahasiaan/${asesiId}`, { 
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Alert sukses sederhana atau redirect
                        window.location.href = '/tracker/' + asesiId; 
                    } else {
                        alert('Error: ' + data.message);
                        this.textContent = 'Setuju & Selanjutnya';
                        this.disabled = false;
                        this.classList.remove('opacity-75', 'cursor-not-allowed');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan sistem.');
                    this.textContent = 'Setuju & Selanjutnya';
                    this.disabled = false;
                    this.classList.remove('opacity-75', 'cursor-not-allowed');
                });
            });

            // --- 4. LOGIC TOMBOL "HAPUS" ---
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
                        ttdContainer.innerHTML = ''; 
                        // Kembalikan placeholder
                        const p = document.createElement('p');
                        p.id = 'ttd_placeholder';
                        p.className = 'text-gray-400 text-sm';
                        p.innerText = 'Tanda tangan berhasil dihapus.';
                        ttdContainer.appendChild(p);
                    } else {
                        alert('Error: ' + data.message);
                    }
                    this.textContent = 'Hapus Tanda Tangan';
                    this.disabled = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal terhubung ke server.');
                    this.textContent = 'Hapus Tanda Tangan';
                    this.disabled = false;
                });
            });
        });
    </script>

</body>
</html>