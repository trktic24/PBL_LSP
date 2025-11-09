<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Asesmen dan Kerahasiaan (API)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Gaya tambahan agar checkbox yang disabled tidak terlihat terlalu pudar */
        input[type="checkbox"]:disabled {
            opacity: 1; 
            cursor: default;
        }
        /* Gaya untuk menonaktifkan interaksi pada label */
        label input[type="checkbox"]:disabled:hover {
            cursor: default;
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">
        
        {{-- Asumsi komponen sidebar ada di sini --}}
        <x-sidebar2></x-sidebar2>

        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-3xl mx-auto">
                
                <h1 class="text-4xl font-bold text-gray-900 mb-8">Persetujuan Asesmen dan Kerahasiaan</h1>

                <p class="text-gray-700 mb-8 text-sm">
                    Persetujuan Asesmen ini untuk menjamin bahwa Peserta telah diberi arahan secara rinci tentang perencanaan dan proses asesmen
                </p>

                <dl class="grid grid-cols-1 md:grid-cols-4 gap-y-6 text-sm">
                    
                    <dt class="col-span-1 font-medium text-gray-800">TUK</dt>
                    <dd class="col-span-3 flex flex-wrap gap-x-6 gap-y-2 items-center">
                        {{-- Elemen TUK: ID digunakan untuk diisi oleh JS --}}
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
                    
                    {{-- Nama Asesor (diisi oleh JS) --}}
                    <dt class="col-span-1 font-medium text-gray-800">Nama Asesor</dt>
                    <dd class="col-span-3 text-gray-800">: <span id="nama_asesor">[Memuat Data...]</span></dd>

                    {{-- Nama Asesi (diisi oleh JS) --}}
                    <dt class="col-span-1 font-medium text-gray-800">Nama Asesi</dt>
                    <dd class="col-span-3 text-gray-800">: <span id="nama_asesi">[Memuat Data...]</span></dd>
                    
                    <dt class="col-span-1 font-medium text-gray-800">Bukti yang akan dikumpulkan</dt>
                    <dd class="col-span-3 grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-4">
                        
                        {{-- Elemen Bukti: ID digunakan untuk diisi oleh JS --}}
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" value="Verifikasi Portofolio" id="bukti_Verifikasi Portofolio" disabled class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                            Verifikasi Portofolio
                        </label>
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" value="Hasil Test Tulis" id="bukti_Hasil Test Tulis" disabled class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                            Hasil Test Tulis
                        </label>
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" value="Hasil Tes Lisan" id="bukti_Hasil Tes Lisan" disabled class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                            Hasil Tes Lisan
                        </label>
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" value="Hasil Wawancara" id="bukti_Hasil Wawancara" disabled class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                            Hasil Wawancara
                        </label>
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" value="Observasi Langsung" id="bukti_Observasi Langsung" disabled class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
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
                    {{-- Container TTD: Akan diisi gambar oleh JavaScript --}}
                    <div class="w-full h-48 bg-gray-50 border border-gray-300 rounded-lg shadow-inner flex items-center justify-center overflow-hidden" id="ttd_container">
                        <p id="ttd_placeholder" class="text-gray-500 text-sm">Area Tanda Tangan</p>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <p class="text-red-600 text-xs italic">*Tanda Tangan di sini</p>
                        <button class="px-4 py-1.5 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                            Hapus
                        </button>
                    </div>
                </div>
                
                <div class="flex justify-between items-center mt-12">
                    <button class="px-8 py-3 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300 transition-colors">
                        Sebelumnya
                    </button>
                    <button class="px-8 py-3 bg-blue-500 text-white font-semibold rounded-full hover:bg-blue-600 shadow-md transition-colors">
                        Selanjutnya
                    </button>
                </div>

            </div>
        </main>

    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil URL API dari named route Laravel
        const apiUrl = '{{ route('api.asesmen.fr_ak01') }}'; 

        fetch(apiUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                // 1. ISI NAMA ASESOR DAN ASESI
                document.getElementById('nama_asesor').innerText = data.asesor.nama_lengkap;
                document.getElementById('nama_asesi').innerText = data.asesi.nama_lengkap;

                // 2. ISI TUK
                const tukId = `tuk_${data.data_asesmen.tuk}`;
                const tukElement = document.getElementById(tukId);
                if (tukElement) {
                    tukElement.checked = true;
                }
                
                // 3. ISI BUKTI DIKUMPULKAN
                data.data_asesmen.bukti_dikumpulkan.forEach(bukti => {
                    const buktiId = `bukti_${bukti}`; 
                    const buktiElement = document.getElementById(buktiId);
                    if (buktiElement) {
                        buktiElement.checked = true;
                    }
                });

                // 4. TAMPILKAN TANDA TANGAN ASESI (TTD)
                if (data.tanda_tangan) {
                    const ttdContainer = document.getElementById('ttd_container');
                    
                    // Path harus relatif ke folder public
                    const ttdPath = `/${data.tanda_tangan}`; 
                    
                    // Kosongkan container dan isi dengan tag gambar
                    ttdContainer.innerHTML = `
                        <img 
                            src="${ttdPath}" 
                            alt="Tanda Tangan Asesi" 
                            class="object-contain h-full w-full p-2"
                            onerror="this.parentNode.innerHTML = '<p class=\\'text-red-500 text-sm\\'>Gagal memuat gambar TTD atau file tidak ditemukan.</p>'"
                        >`;
                } else {
                    // Jika path TTD NULL di DB
                    document.getElementById('ttd_placeholder').innerText = 'Tanda tangan belum tersedia di database.';
                }

            })
            .catch(error => {
                console.error('Gagal memuat data dari API:', error);
                document.getElementById('nama_asesor').innerText = 'Gagal Memuat Data!';
                document.getElementById('nama_asesi').innerText = 'Gagal Memuat Data!';
                // Tampilkan pesan error di container TTD
                document.getElementById('ttd_container').innerHTML = '<p class="text-red-500 text-sm">Gagal memuat semua data formulir.</p>';
            });
    });
</script>

</body>
</html>