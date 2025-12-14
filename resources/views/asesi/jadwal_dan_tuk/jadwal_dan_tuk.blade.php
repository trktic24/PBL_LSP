@php
    // --- Ambil variabel CSS dari tracker.blade.php untuk konsistensi ---

    // CONTAINER KARTU (Responsive: Box di Mobile, Transparent di Desktop)
    $responsiveCardClass =
        'bg-white p-5 rounded-xl shadow-[0_4px_20px_-5px_rgba(0,0,0,0.1)] border border-gray-100 md:bg-white md:p-6 md:rounded-2xl md:shadow-lg md:border-gray-200 relative z-10';

    // BUTTON STYLES
    $btnBase = 'mt-2 px-4 py-1.5 text-xs font-semibold rounded-md inline-block transition-all';
    $btnBlue = "$btnBase bg-blue-500 text-white hover:bg-blue-600 shadow-blue-100 hover:shadow-lg";

    // Data untuk Mobile Header (Asumsi $sertifikasi sudah di-load)
    $gambarSkema =
        $sertifikasi->jadwal && $sertifikasi->jadwal->skema && $sertifikasi->jadwal->skema->gambar
            ? asset('images/' . $sertifikasi->jadwal->skema->gambar)
            : null;
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal dan TUK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        input[type="checkbox"] {
            /* Menggunakan aksen warna biru untuk checkbox (di browser modern) */
            accent-color: #3b82f6;
            width: 1.2em;
            height: 1.2em;
        }

        /* Style disabled checkbox */
        input[type="checkbox"]:disabled {
            opacity: 1;
            cursor: default;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans">

    {{-- Gunakan Flexbox utama untuk Layout Responsive --}}
    <div class="flex min-h-screen flex-col md:flex-row md:h-screen md:overflow-hidden">

        {{-- 1. SIDEBAR (Desktop Only) --}}
        <div class="hidden md:block md:w-80 flex-shrink-0">
            {{-- Menggunakan x-sidebar (bukan sidebar2) agar konsisten dengan tracker.blade.php --}}
            {{-- Asumsi Anda memiliki komponen x-sidebar --}}
            {{-- Menggunakan x-sidebar2 sesuai yang ada di kode Anda, tapi pastikan komponen ini benar --}}
            <x-sidebar2 :idAsesi="$asesi->id_asesi" :sertifikasi="$sertifikasi" backUrl="{{ route('asesi.tracker', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}" />
        </div>

        {{-- 2. HEADER MOBILE (Data Dinamis) --}}
        {{-- Menggunakan x-mobile_header agar konsisten dengan tracker.blade.php --}}
        {{-- Menambahkan backUrl agar tombol kembali di mobile header berfungsi --}}
        <x-mobile_header
            :title="'Jadwal & TUK'"
            :code="$sertifikasi->jadwal->skema->kode_unit ?? ($sertifikasi->jadwal->skema->nomor_skema ?? '-')"
            :name="$sertifikasi->asesi->nama_lengkap ?? 'Nama Peserta'"
            :image="$gambarSkema"
            :backUrl="route('asesi.tracker', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi])"
        />


        {{-- 3. MAIN CONTENT --}}
        <main class="flex-1 w-full relative md:p-10 md:overflow-y-auto mt-14 md:mt-0">
            <div class="max-w-3xl mx-auto p-6 md:p-0">

                <div class="text-center mb-8">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Jadwal & Tempat Uji Kompetensi</h1>
                    <div class="w-full h-0.5 bg-gray-200 mt-4 mx-auto"></div>
                </div>

                {{-- CARD 1: INFORMASI TUK --}}
                <div class="{{ $responsiveCardClass }} mb-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0l-4.243-4.243m.215-2.078c.07-.152.126-.299.176-.438.05-.14.086-.27.108-.39.022-.12.032-.24.032-.36s-.01-.24-.032-.36c-.022-.12-.058-.25-.108-.39-.05-.139-.106-.286-.176-.438l-4.3-4.3a10 10 0 1114.142 0l-4.3 4.3z"></path></svg>
                        Informasi TUK
                    </h2>
                    <div class="w-full h-px bg-gray-200 mb-6"></div>

                    <div class="space-y-4 text-sm md:text-base text-gray-800">

                        {{-- Baris TUK (Checkbox) --}}
                        <div class="grid grid-cols-1 gap-2 md:grid-cols-4 md:gap-4 items-center">
                            <div class="font-semibold text-gray-700 md:col-span-1">Jenis TUK</div>
                            <div class="md:col-span-3 flex flex-wrap gap-4 text-gray-600">
                                <label class="flex items-center space-x-2 cursor-default">
                                    <input type="checkbox" id="tuk_Sewaktu" disabled class="rounded border-gray-400">
                                    <span>Sewaktu</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-default">
                                    <input type="checkbox" id="tuk_Tempat Kerja" disabled
                                        class="rounded border-gray-400">
                                    <span>Tempat Kerja</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-default">
                                    <input type="checkbox" id="tuk_Mandiri" disabled class="rounded border-gray-400">
                                    <span>Mandiri</span>
                                </label>
                            </div>
                        </div>

                        {{-- Baris Lokasi --}}
                        <div class="grid grid-cols-1 gap-1 md:grid-cols-4 md:gap-4">
                            <div class="font-semibold text-gray-700 md:col-span-1">Lokasi Uji</div>
                            <div class="md:col-span-3 text-gray-600 break-words" id="text_lokasi">Memuat...</div>
                        </div>

                        {{-- Baris Alamat --}}
                        <div class="grid grid-cols-1 gap-1 md:grid-cols-4 md:gap-4">
                            <div class="font-semibold text-gray-700 md:col-span-1">Alamat Lengkap</div>
                            <div class="md:col-span-3 text-gray-600 break-words" id="text_alamat">Memuat...</div>
                        </div>

                        {{-- Baris Kontak --}}
                        <div class="grid grid-cols-1 gap-1 md:grid-cols-4 md:gap-4">
                            <div class="font-semibold text-gray-700 md:col-span-1">Kontak TUK</div>
                            <div class="md:col-span-3 text-gray-600" id="text_kontak">Memuat...</div>
                        </div>

                        {{-- Baris Tanggal --}}
                        <div class="grid grid-cols-1 gap-1 md:grid-cols-4 md:gap-4">
                            <div class="font-semibold text-gray-700 md:col-span-1">Tanggal Pelaksanaan</div>
                            <div class="md:col-span-3 text-gray-600" id="text_tanggal">Memuat...</div>
                        </div>

                        {{-- Baris Waktu --}}
                        <div class="grid grid-cols-1 gap-1 md:grid-cols-4 md:gap-4">
                            <div class="font-semibold text-gray-700 md:col-span-1">Waktu Mulai</div>
                            <div class="md:col-span-3 text-gray-600" id="text_waktu">Memuat...</div>
                        </div>
                    </div>
                </div>

                {{-- CARD 2: PETA LOKASI --}}
                <div class="{{ $responsiveCardClass }} mb-6">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                        </svg>
                        <h2 class="text-lg font-bold text-gray-800">Peta Lokasi</h2>
                    </div>

                    <div class="w-full h-[300px] md:h-[450px] bg-gray-200 rounded-lg overflow-hidden shadow-inner">
                        <iframe id="iframe_maps" src="" width="100%" height="100%" style="border:0;"
                            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>

                {{-- PANDUAN SINGKAT (Di luar card agar lebih menonjol) --}}
                <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-800 p-4 mb-6 text-sm md:text-base font-medium rounded-lg">
                    <span class="font-bold">Panduan Tambahan:</span> Dari Gerbang Utama, lurus terus sampai bundaran,
                    belok kanan ke Gedung Magister Terapan.
                </div>

                {{-- CARD 3: FOTO GEDUNG --}}
                <div class="{{ $responsiveCardClass }} mb-8">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L15 15m0 0l4.14 4.14a2 2 0 01-.632.74l-4.14-4.14m-1.789-2.21l-3.21-3.21m3.21 3.21L15 15m4.364-1.706l-4.137-4.137M12 10V6"></path></svg>
                        Tampilan Gedung TUK
                    </h2>
                    <div
                        class="w-full h-[300px] md:h-[500px] bg-gray-200 rounded-lg overflow-hidden shadow-inner flex items-center justify-center relative">
                        <img id="img_gedung" src="" class="w-full h-full object-cover hidden"
                            alt="Foto Gedung TUK">
                        <span id="placeholder_gedung" class="text-gray-500 font-bold text-xl">MEMUAT FOTO...</span>
                    </div>
                </div>

                {{-- TOMBOL NAVIGASI --}}
                <div class="flex justify-between items-center mt-10 mb-10">
                    <a href="{{ route('asesi.tracker', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}"
                        class="px-6 py-2 md:px-12 md:py-3 bg-gray-300 text-gray-700 font-semibold rounded-full hover:bg-gray-400 transition-all shadow-sm text-sm md:text-base">
                        &larr; Kembali
                    </a>
                    <button id="btn_selanjutnya"
                        class="px-6 py-2 md:px-8 md:py-3 bg-blue-500 text-white font-semibold rounded-full hover:bg-blue-600 transition-all shadow-md text-sm md:text-base">
                        Selanjutnya &rarr;
                    </button>
                </div>

            </div>
        </main>
    </div>

    {{-- JAVASCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const idSertifikasi = document.querySelector('main').dataset.sertifikasiId;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            // Pastikan rute ini konsisten dengan route di tracker.blade.php (ITEM 5)
            const nextUrl = "{{ route('asesi.kerahasiaan.fr_ak01', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}";


            if (!idSertifikasi) {
                alert("ID Sertifikasi tidak ditemukan");
                return;
            }

            // 1. FETCH DATA
            fetch(`/api/v1/jadwal-tuk/${idSertifikasi}`)
                .then(res => res.json())
                .then(resp => {
                    if (resp.success) {
                        const data = resp.data;

                        // A. Isi Checkbox TUK
                        const tukEl = document.getElementById(`tuk_${data.jenis_tuk}`);
                        if (tukEl) tukEl.checked = true;

                        // B. Isi Teks Informasi
                        document.getElementById('text_lokasi').innerText = data.lokasi || 'Belum Ditentukan';
                        document.getElementById('text_alamat').innerText = data.alamat || 'Belum Ditentukan';
                        document.getElementById('text_kontak').innerText = data.kontak || 'Belum Ditentukan';

                        // C. Format Tanggal (Indo)
                        if (data.tanggal) {
                            const date = new Date(data.tanggal);
                            const options = {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            };
                            document.getElementById('text_tanggal').innerText = date.toLocaleDateString('id-ID', options);
                        } else {
                            document.getElementById('text_tanggal').innerText = 'Belum Ditentukan';
                        }


                        // D. Format Waktu
                        if (data.waktu) {
                            // Waktu harus digabung dengan tanggal (date object) untuk format
                            const dateTimeString = `${data.tanggal}T${data.waktu}`;
                            const waktuObj = new Date(dateTimeString);

                            // 2. Format jadi Jam:Menit sesuai zona waktu Jakarta (WIB)
                            const jamWib = waktuObj.toLocaleTimeString('id-ID', {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: false // Format 24 jam
                            });

                            // 3. Tampilkan
                            document.getElementById('text_waktu').innerText = jamWib + ' WIB';
                        } else {
                             document.getElementById('text_waktu').innerText = 'Belum Ditentukan';
                        }


                        // E. Isi Maps
                        const iframeMaps = document.getElementById('iframe_maps');
                        if (data.link_gmap) {
                            iframeMaps.src = data.link_gmap;
                        } else {
                            // Opsional: tampilkan pesan placeholder di iframe
                            iframeMaps.contentDocument.write(
                                '<body style="background: #e5e7eb; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; font-family: sans-serif;"><span style="color: #6b7280; font-size: 1.25rem; font-weight: bold;">LINK GOOGLE MAPS TIDAK TERSEDIA</span></body>'
                            );
                        }

                        // F. Isi Foto Gedung
                        const imgEl = document.getElementById('img_gedung');
                        const placeholderEl = document.getElementById('placeholder_gedung');
                        if (data.foto_gedung) {
                            // Asumsi path foto_gedung disimpan di server public/images/
                            imgEl.src = `{{ asset('images') }}/${data.foto_gedung}`;
                            imgEl.classList.remove('hidden');
                            placeholderEl.classList.add('hidden');
                        } else {
                            placeholderEl.innerText = "FOTO TIDAK TERSEDIA";
                            imgEl.classList.add('hidden');
                        }
                    } else {
                        // Handle error fetch/data not found
                        document.getElementById('text_lokasi').innerText = 'Data tidak ditemukan';
                        document.getElementById('text_alamat').innerText = 'Data tidak ditemukan';
                        document.getElementById('text_kontak').innerText = 'Data tidak ditemukan';
                        document.getElementById('text_tanggal').innerText = 'Data tidak ditemukan';
                        document.getElementById('text_waktu').innerText = 'Data tidak ditemukan';
                        document.getElementById('placeholder_gedung').innerText = "DATA FOTO TIDAK TERSEDIA";
                        console.error('Fetch error:', resp.message);
                    }
                })
                .catch(err => {
                    console.error('Error fetching data:', err);
                    document.getElementById('text_lokasi').innerText = 'Gagal memuat data';
                    document.getElementById('text_alamat').innerText = 'Gagal memuat data';
                    document.getElementById('text_kontak').innerText = 'Gagal memuat data';
                    document.getElementById('text_tanggal').innerText = 'Gagal memuat data';
                    document.getElementById('text_waktu').innerText = 'Gagal memuat data';
                    document.getElementById('placeholder_gedung').innerText = "GAGAL MEMUAT FOTO";
                });

            // 2. TOMBOL NAVIGASI
            document.getElementById('btn_selanjutnya').addEventListener('click', () => {
                // Konfirmasi/Simpan Status (Opsional) - Asumsi ini adalah step final untuk Jadwal & TUK
                fetch(`/api/v1/jadwal-tuk/konfirmasi/${idSertifikasi}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        // Tidak perlu cek success kalau cuma redirect
                        window.location.href = nextUrl;
                    })
                    .catch(err => {
                         // Jika ada error fetch/konfirmasi, tetap pindah halaman
                         console.error("Error konfirmasi, tetap melanjutkan:", err);
                         window.location.href = nextUrl;
                    });
            });

            // Tombol Sebelumnya (Kembali ke Tracker)
            // Tombol ini diganti menjadi tag <a> di HTML dengan link kembali ke tracker
        });
    </script>

</body>

</html>